<?php
class DatabaseHelper {
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    public function updateRecipe($nickname, $title, $public, $preparation, $preparationTime, $portions, $ingredients) {
        "START TRANSACTION;

        -- 1. Rimozione degli ingredienti non più usati
        DELETE FROM utilizzi
        WHERE nomeIngrediente IN (?, ?, ..., ?)
        AND titolo = ?
        AND nicknameEditore = ?;

        -- 2. Aggiunta dei nuovi ingredienti alla ricetta
        INSERT INTO utilizzi (nomeIngrediente, titolo, nicknameEditore, quantita)
        VALUES (?, ?, ?, ?), …, (?, ?, ?, ?);

        -- 3. Ricalcolo delle calorie per la ricetta
        UPDATE ricette R
        SET kcalTotali = (
            SELECT SUM(I.kcal * (U.quantita / 100))
            FROM ingredienti I, utilizzi U
                WHERE I.nome = U.nomeIngrediente
                AND U.titolo = R.titolo
                AND U.nicknameEditore = R.nicknameEditore
            ),
            costoTotale = (
            SELECT SUM(I.costo * (U.quantita / 100))
            FROM ingredienti I, utilizzi U
                WHERE I.nome = U.nomeIngrediente
                AND U.titolo = R.titolo
                AND U.nicknameEditore = R.nicknameEditore
            ),
            preparazione = ?
        WHERE titolo = ? AND nicknameEditore = ?;

        -- 4. Ricalcolo delle calorie totali nelle diete che usano la ricetta
        UPDATE diete D
        SET kcalDieta = (
            SELECT SUM(R.kcalTotali / R.porzioni)
            FROM ricette R, composizioni C
            WHERE R.titolo = C.titolo
            AND R.nicknameEditore = C.nicknameEditore
            AND C.nomeDieta = D.nome
            AND C.nicknameAutore = D.nicknameAutore
        ) WHERE EXISTS ( -- solo per le diete che includono la ricetta modificata
            SELECT *
            FROM composizioni C
            WHERE C.nomeDieta = D.nome
            AND C.nicknameAutore = D.nicknameAutore
            AND C.titolo = ?
            AND C.nicknameEditore = ?
        );

        COMMIT;
        ";
    }

    public function getRecipeData($nickname, $title) {
        $data = array();
        $stmt = $this->db->prepare("
            SELECT *
            FROM ricette
            WHERE nicknameEditore = ?
            AND titolo = ?
        ");
        $stmt->bind_param("ss", $nickname, $title);
        $stmt->execute();
        $data["recipe"] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];

        if (count($data["recipe"]) === 0) {
            return null;
        }

        $stmt2 = $this->db->prepare("
            SELECT nomeIngrediente, quantita
            FROM utilizzi
            WHERE nicknameEditore = ?
            AND titolo = ?
        ");
        $stmt2->bind_param("ss", $nickname, $title);
        $stmt2->execute();
        $data["ingredients"] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        
        return $data;
    }

    public function addRecipe($nickname, $title, $public, $preparation, $preparationTime, $portions, $ingredients) {
        $this->db->begin_transaction();
        try {
            // -- 1. Inserimento della ricetta (ponendo a 0 kcalTotali e costoTotale)
            $stmt1 = $this->db->prepare("
                INSERT INTO ricette (titolo, nicknameEditore, pubblica, preparazione, porzioni, tempoPreparazione, kcalTotali, costoTotale)
                VALUES (?, ?, ?, ?, ?, ?, 0, 0);
            ");
            $stmt1->bind_param("ssisii", $title, $nickname, $public, $preparation, $portions, $preparationTime);
            $stmt1->execute();
            $stmt1->close();

            // -- 2. Inserimento degli ingredienti associati alla ricetta
            $query = "INSERT INTO utilizzi (nomeIngrediente, titolo, nicknameEditore, quantita) VALUES ";
            $pms = array();
            $pTypes = "";
            for ($i = 0; $i < count($ingredients); $i++) {
                $query .= "(?, ?, ?, ?)" . ($i < count($ingredients) - 1 ? "," : "");
                $pTypes .= "sssi";
                array_push($pms, $ingredients[$i][0], $title, $nickname, $ingredients[$i][1]);
            }
            $stmt2 = $this->db->prepare($query);
            $stmt2->bind_param($pTypes, ...$pms);
            $stmt2->execute();
            $stmt2->close();

            // -- 3. Calcolo delle calorie e costo totale
            $stmt3 = $this->db->prepare("
                UPDATE ricette
                SET 
                    kcalTotali = (
                        SELECT SUM(I.kcal * (U.quantita / 100))
                        FROM ingredienti I, utilizzi U
                        WHERE I.nome = U.nomeIngrediente
                        AND U.titolo = ricette.titolo
                        AND U.nicknameEditore = ricette.nicknameEditore
                    ),
                    costoTotale = (
                        SELECT SUM(I.costo * (U.quantita / 100))
                        FROM ingredienti I, utilizzi U
                        WHERE I.nome = U.nomeIngrediente
                        AND U.titolo = ricette.titolo
                        AND U.nicknameEditore = ricette.nicknameEditore
                    )
                WHERE titolo = ? AND nicknameEditore = ?;
            ");
            $stmt3->bind_param("ss", $title, $nickname);
            $stmt3->execute();
            $stmt3->close();

            $this->db->commit();
        } catch (Exception $e) {
            // Qualcosa é andato storto. Ritorno ad uno stato consistente
            $this->db->rollback();
            throw new Exception("Uno stesso utente non puó registrare due ricette con lo stesso nome");
        }
    }

    public function getUserRecipes($nickname) {
        $query = "
            SELECT titolo, pubblica, preparazione, porzioni, tempoPreparazione, kcalTotali, costoTotale
            FROM ricette
            WHERE nicknameEditore = ?
            ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function deleteIngredient($name) {
        $query = "
        DELETE FROM ingredienti
        WHERE nome = ?
        AND NOT EXISTS (
            SELECT *
            FROM utilizzi U
            WHERE U.nomeIngrediente = ?
        )
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $name, $name);
        $stmt->execute();
        $rowsAffected = $stmt->affected_rows;
        $stmt->close();

        // non é avvenuta nessuna eliminazione
        if ($rowsAffected == 0) {
            // Verifico se l'ingrediente esiste
            $checkQuery = "SELECT COUNT(*) as count FROM ingredienti WHERE nome = ?";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bind_param("s", $name);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $row = $result->fetch_assoc();
            $checkStmt->close();
            
            throw new Exception($row['count'] == 0
                ? "L'ingrediente non esiste"
                : "Impossibile eliminare l'ingrediente: é in uso in una o piú ricette");
        }
    }

    public function updateIngredient($oldName, $name, $kcal, $price, $carbs, $proteins, $insFats, $satFats, $unit) {
        $this->db->begin_transaction();
        try {
            // -- 1. Modifica dell'ingrediente
            $stmt1 = $this->db->prepare("
                UPDATE ingredienti
                SET
                nome = ?,
                costo = ?,
                unitaMisura = ?,
                kcal = ?,
                proteine = ?,
                carboidrati = ?,
                grassiInsaturi = ?,
                grassiSaturi = ?
                WHERE nome = ?"
            );
            $stmt1->bind_param("sdsddddds", $name, $price, $unit, $kcal, $proteins, $carbs, $insFats, $satFats, $oldName);
            $stmt1->execute();
            $stmt1->close();

            // -- 2. Salvataggio delle ricette che usano l'ingrediente
            $stmt2 = $this->db->prepare("
                CREATE TEMPORARY TABLE ricetteIngrediente AS
                SELECT R.titolo, R.nicknameEditore
                FROM ricette R, utilizzi U 
                WHERE R.titolo = U.titolo
                AND R.nicknameEditore = U.nicknameEditore
                AND U.nomeIngrediente = ?"
            );
            $stmt2->bind_param("s", $name);
            $stmt2->execute();
            $stmt2->close();

            // -- 3. Ricalcolo calorie e costo ricette
            $this->db->query("
                UPDATE ricette R
                JOIN ricetteIngrediente RI
                ON R.titolo = RI.titolo
                AND R.nicknameEditore = RI.nicknameEditore
                SET
                R.kcalTotali = (
                    SELECT SUM(I.kcal * (U.quantita / 100))
                    FROM ingredienti I, utilizzi U
                    WHERE I.nome = U.nomeIngrediente
                    AND U.titolo = R.titolo
                    AND U.nicknameEditore = R.nicknameEditore
                ),
                R.costoTotale = (
                    SELECT SUM(I.costo * (U.quantita / 100))
                    FROM ingredienti I, utilizzi U
                    WHERE I.nome = U.nomeIngrediente
                    AND U.titolo = R.titolo
                    AND U.nicknameEditore = R.nicknameEditore
                )
            ");

            // -- 4. Ricalcolo kcal delle diete che usano le ricette aggiornate
            $this->db->query("
                UPDATE diete D
                SET kcalDieta = (
                    SELECT SUM(R.kcalTotali / R.porzioni)
                    FROM ricette R, composizioni C
                    WHERE R.titolo = C.titolo
                    AND R.nicknameEditore = C.nicknameEditore
                    AND C.nomeDieta = D.nome
                    AND C.nicknameAutore = D.nicknameAutore
                )
                WHERE (D.nome, D.nicknameAutore) IN (
                    SELECT C.nomeDieta, C.nicknameAutore
                    FROM composizioni C, ricetteIngrediente RI
                    WHERE C.titolo = RI.titolo
                    AND C.nicknameEditore = RI.nicknameEditore
                )"
            );

            // -- 5 Eliminazione della tabella temporanea
            $this->db->query("DROP TEMPORARY TABLE ricetteIngrediente");

            $this->db->commit();
        } catch (Exception $e) {
            //error_log($e->getMessage());
            // Qualcosa é andato storto. Ritorno ad uno stato consistente
            $this->db->rollback();
            throw new Exception("Non esiste un ingrediente con questo nome");
        }
    }

    /* $statements: un array di query a cui é giá stato effettuato il binding dei parametri
    Esegue tutte le query presenti in statements dentro una transazione. In caso di errore
    effettua il rollback e ritorna l'errore riscontrato */
    private function transactionExecuter($statements) {
        $this->db->begin_transaction();
        try {
            foreach ($statements as $stmt) {
                $stmt->execute();
                $stmt->close();
            }
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function addIngredient($name, $kcal, $price, $carbs, $proteins, $insFats, $satFats, $unit) {
        $query = "
            INSERT INTO ingredienti (nome, costo, unitaMisura, kcal, proteine, carboidrati, grassiInsaturi, grassiSaturi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sdsddddd", $name, $price, $unit, $kcal, $proteins, $carbs, $insFats, $satFats);
        if (!$stmt->execute()) {
            throw new Exception("Esiste giá un ingrediente con questo nome");
        }
    }

    /* Registra l'utente o lancia una eccezione con il problema riscontrato
    durante la registrazione */
    public function register($name, $surname, $nickname, $password) {
        // Controlla che il nickname non esista giá
        if ($this->nicknameRole($nickname)) {
            throw new Exception("Nickname giá registrato");
        }

        // Controllo basilare della password
        if (!checkPassword($password)) {
            throw new Exception("Password troppo debole. Inserire minimo 8 caratteri, tra cui 1 lettera maiuscola, 1 numero ed 1 carattere speciale");
        }

        $query = "
            INSERT INTO utenti (nickname, nome, cognome, password, accreditato, fineLimitazione)
            VALUES (?, ?, ?, ?, 0, NULL)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssss", $nickname, $name, $surname, $password);
        $stmt->execute();
    }

    /* Imposta le variabili di sessione relative all'utente o lancia una eccezione
    con il problema riscontrato durante il login */
    public function login($nickname, $password) {
        $role = $this->nicknameRole($nickname);
        if (!$role) {
            throw new Exception("Nickname inesistente");
        }
        $personData = $this->getPersonData($role, $nickname, $password);
        if (!$personData) {
            throw new Exception("Password errata");
        }

        // imposta le variabili di sessione con i dati utente
        $_SESSION["role"] = $role;
        $_SESSION["nickname"] = $personData["nickname"];
        $_SESSION["nome"] = $personData["nome"];
        $_SESSION["cognome"] = $personData["cognome"];

        if ($role === "utenti") {
            $_SESSION["accreditato"] = $personData["accreditato"] == 1;
            $_SESSION["fineLimitazione"] = $personData["fineLimitazione"];
        }
    }

    public function getIngredients() {
        $query = "
            SELECT *
            FROM ingredienti
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    private function getPersonData($role, $nickname, $password) {
        $query = "
            SELECT *
            FROM " . $role . "
            WHERE nickname = ?
            AND password = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $nickname, $password);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $person = $result->fetch_assoc();
        return $person ? $person : null;
    }

    /* Ritorna null se il nickname non esiste, altrimenti ritorna una stringa
    rappresentante il ruolo (utenti/amministratori) */
    private function nicknameRole($nickname) {
        $query = "
            SELECT 'utenti' AS ruolo, nickname
            FROM utenti
            WHERE nickname = ?
            UNION ALL
            SELECT 'amministratori' AS ruolo, nickname
            FROM amministratori
            WHERE nickname = ?
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $nickname, $nickname);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['ruolo'];
        } else {
            return null; // nickname non trovato
        }
    }
}
?>