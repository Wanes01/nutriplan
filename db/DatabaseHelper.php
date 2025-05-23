<?php
class DatabaseHelper {
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    public function deleteDiet($dietName, $author) {
        $stmt = $this->db->prepare("
            DELETE FROM diete
            WHERE nome = ?
            AND nicknameAutore = ?
        ");
        $stmt->bind_param("ss", $dietName, $author);
        $stmt->execute();
        $stmt->close();
    }

    public function removeRecipeFromDiet($dietName, $author, $title, $editor) {
        $this->db->begin_transaction();
        try {
            // Eliminazione della ricetta nella dieta 
            $stmt1 = $this->db->prepare("
                DELETE FROM composizioni
                WHERE nomeDieta = ?
                AND nicknameAutore = ?
                AND titolo = ?
                AND nicknameEditore = ?;
            ");
            $stmt1->bind_param("ssss", $dietName, $author, $title, $editor);
            $stmt1->execute();
            $stmt1->close();

            // Ricalcolo delle calorie nella dieta
            $stmt2 = $this->db->prepare("
                UPDATE diete D
                SET kcalDieta = (
                    SELECT SUM(R.kcalTotali / R.porzioni)
                    FROM ricette R, composizioni C
                    WHERE R.titolo = C.titolo
                    AND R.nicknameEditore = C.nicknameEditore
                    AND C.nomeDieta = D.nome
                    AND C.nicknameAutore = D.nicknameAutore
                )
                WHERE D.nome = ?
                AND D.nicknameAutore = ?;
            ");
            $stmt2->bind_param("ss", $dietName, $author);
            $stmt2->execute();
            $stmt2->close();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
        }
    }

    public function getDietaData($nickname, $dietName) {
        $stmt = $this->db->prepare("
            SELECT nome, kcalDieta
            FROM diete
            WHERE nicknameAutore = ?
            AND nome = ?"
        );
        $stmt->bind_param("ss", $nickname, $dietName);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];
    }

    public function getDietRecipes($dietName, $author) {
        $stmt = $this->db->prepare("
            SELECT titolo, nicknameEditore
            FROM composizioni
            WHERE nomeDieta = ?
            AND nicknameAutore = ?"
        );
        $stmt->bind_param("ss", $dietName, $author);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addToDiet($title, $editor, $author, $dietName) {
        $this->db->begin_transaction();
        try {
            // -- 2. Associazione delle ricette alla dieta (dieta giá creata)
            $stmt1 = $this->db->prepare("
                INSERT INTO composizioni (nomeDieta, nicknameAutore, titolo, nicknameEditore)
                VALUES (?, ?, ?, ?)"
            );
            $stmt1->bind_param("ssss", $dietName, $author, $title, $editor);
            $stmt1->execute();
            $stmt1->close();

            // -- 3. Calcolo dell'etichetta in base alle kcal totali per porzione
            $stmt2 = $this->db->prepare("
                UPDATE diete
                SET kcalDieta = (
                    SELECT SUM(R.kcalTotali / R.porzioni)
                    FROM ricette R, composizioni C
                    WHERE R.titolo = C.titolo
                    AND R.nicknameEditore = C.nicknameEditore
                    AND C.nomeDieta = ?
                    AND C.nicknameAutore = ?
                )
                WHERE nome = ? AND nicknameAutore = ?"
            );
            $stmt2->bind_param("ssss", $dietName, $author, $dietName, $author);
            $stmt2->execute();
            $stmt2->close();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
        }
    }

    public function getUserDietsOnRecipeNotIncluded($title, $editor, $author) {
        $stmt = $this->db->prepare("
            SELECT nome
            FROM diete D
            WHERE D.nicknameAutore = ?
            AND NOT EXISTS (
                SELECT *
                FROM composizioni C
                WHERE C.nomeDieta = D.nome
                AND C.nicknameAutore = D.nicknameAutore
                AND C.titolo = ?
                AND C.nicknameEditore = ?
            )"
        );
        $stmt->bind_param("sss", $author, $title, $editor);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserDiets($nickname) {
        $stmt = $this->db->prepare("
            SELECT nome, kcalDieta
            FROM diete
            WHERE nicknameAutore = ?"
        );
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addDiet($nickname, $dietName) {
        $stmt = $this->db->prepare("
            INSERT INTO diete (nome, nicknameAutore, kcalDieta)
            VALUES (?, ?, 0);
        ");
        $stmt->bind_param("ss", $dietName, $nickname);
        if (!$stmt->execute()) {
            throw new Exception("Hai giá registrato una ricetta con questo nome!");
        }
    }

    public function updateAccreditedUsers() {
        $this->db->begin_transaction();
        try {

            $this->db->query("
                CREATE TEMPORARY TABLE accreditabili AS
                    SELECT R.nicknameEditore
                    FROM ricette R
                    LEFT JOIN valutazioni V -- delle ricette potrebbero NON avere valutazioni
                    ON R.titolo = V.titolo
                    AND R.nicknameEditore = V.nicknameEditore
                    GROUP BY R.nicknameEditore
                    HAVING COUNT(R.titolo) >= 10 AND AVG(V.voto) > 4"
            );

            // -- 1. Gli utenti che hanno i requisiti vengono accreditati
            $this->db->query("
                UPDATE utenti
                SET accreditato = 1
                WHERE nickname IN (SELECT nicknameEditore FROM accreditabili)"
            );

            // -- 2. Gli utenti senza i requisiti tornano/rimangono non accreditati
            $this->db->query("
                UPDATE utenti
                SET accreditato = 0
                WHERE nickname NOT IN (SELECT nicknameEditore FROM accreditabili)"
            );

            $this->db->query("DROP TEMPORARY TABLE accreditabili");
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
        }
    }

    public function updateRestrictions() {
        $this->db->query("
            UPDATE utenti
            SET fineLimitazione = NOW() + INTERVAL 1 MONTH
            WHERE fineLimitazione IS NULL
            AND nickname IN (
                SELECT nicknameValutatore
                FROM infrazioni
                WHERE dataOra BETWEEN NOW() - INTERVAL 1 MONTH AND NOW()
                GROUP BY nicknameValutatore
                HAVING COUNT(*) >= 5
            )
        ");
    }

    public function registerViolation($adminNickname, $reason, $evaluator, $title, $editor) {
        $this->db->begin_transaction();
        try {
            // -- 1. Nasconde la valutazione solo se questa ha un commento associato
            $stmt1 = $this->db->prepare("
                UPDATE valutazioni
                SET nascosta = 1
                WHERE nicknameValutatore = ?
                AND titolo = ?
                AND nicknameEditore = ?
                AND commento IS NOT NULL;"
            );
            $stmt1->bind_param("sss", $evaluator, $title, $editor);
            $stmt1->execute();
            $stmt1->close();

            // -- 2. Inserisce l’infrazione SOLO se la valutazione risulta nascosta (quindi solo se la query precedente ha prodotto un effetto)
            $stmt2 = $this->db->prepare("
                INSERT INTO infrazioni (nicknameAmministratore, nicknameValutatore, titolo, nicknameEditore, motivazione, dataOra)
                    SELECT ?, ?, ?, ?, ?, NOW()
                    FROM valutazioni
                    WHERE nicknameValutatore = ?
                    AND titolo = ?
                    AND nicknameEditore = ?
                    AND commento IS NOT NULL
                    AND nascosta = 1;
            ");
            $stmt2->bind_param("ssssssss", $adminNickname, $evaluator, $title, $editor, $reason, $evaluator, $title, $editor);
            $stmt2->execute();
            $stmt2->close();
            
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
        }
    }

    public function getRecipeComments($nickname, $title) {
        $stmt = $this->db->prepare("
            SELECT nicknameValutatore, dataOra, voto, commento
            FROM valutazioni
            WHERE nascosta = 0
            AND titolo = ?
            AND nicknameEditore = ?
        ");
        $stmt->bind_param("ss", $title, $nickname);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addEvaluation($title, $editor, $evaluator, $vote, $comment) {
        $stmt = $this->db->prepare("
            INSERT INTO valutazioni (titolo, nicknameEditore, nicknameValutatore, voto, commento, dataOra, nascosta)
            VALUES (?, ?, ?, ?, ?, NOW(), 0)
        ");

        $cm = $comment == "" ? NULL : $comment;

        $stmt->bind_param(
            "sssis",
            $title,
            $editor,
            $evaluator,
            $vote,
            $cm
        );

        if (!$stmt->execute()) {
            throw new Exception("Hai giá inserito un commento per questa ricetta!");
        }

        $stmt->close();
    }

    public function filterRecipes($title, $minKcals, $maxKcals, $minPrice, $maxPrice, $accreditedOnly, $order, $limit) {
        $query = "
            SELECT R.*, U.accreditato
            FROM ricette R, utenti U
            WHERE R.nicknameEditore = U.nickname
            AND pubblica = 1 ";

        $pTypes = "";
        $params = array();

        if ($title != "") {
            $query .= "AND titolo LIKE ? ";
            $pTypes .= "s";
            array_push($params, "%" . $title . "%");
        }

        $query .= "AND kcalTotali / porzioni BETWEEN ? AND ? ";
        $query .= "AND costoTotale / porzioni BETWEEN ? AND ? ";
        $pTypes .= "dddd";
        array_push(
            $params,
            $minKcals == "" ? "0" : $minKcals,
            $maxKcals == "" ? "99999" : $maxKcals,
            $minPrice == "" ? "0" : $minPrice,
            $maxPrice == "" ? "99999" : $maxPrice,
        );
        
        if ($accreditedOnly) {
            $query .= "AND U.accreditato = 1 ";
        }

        $query .= "ORDER BY ";
        switch ($order) {
            case "incrKcal":
                $query .= "kcalTotali / porzioni ASC ";
                break;
            case "decrKcal":
                $query .= "kcalTotali / porzioni DESC ";
                break;
            case "random":
                $query .= "RAND() ";
                break;
        }
        $query .= "LIMIT ?";
        $pTypes .= "i";
        array_push($params, $limit);

        $stmt = $this->db->prepare($query);
        $stmt->bind_param($pTypes, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteRecipe($nickname, $title) {
        $this->db->begin_transaction();
        try {
            // -- 1. Creazione di una tabella temporanea per salvare le diete che utilizzano la ricetta prima che questa venga eliminata
            $stmt1 = $this->db->prepare("
                CREATE TEMPORARY TABLE aggiornabili AS
                SELECT C.nomeDieta, C.nicknameAutore
                FROM composizioni C
                WHERE C.titolo = ?
                AND C.nicknameEditore = ?"
            );
            $stmt1->bind_param("ss", $title, $nickname);
            $stmt1->execute();
            $stmt1->close();

            // -- 2. Eliminazione della ricetta solo se non ha commenti
            $stmt2 = $this->db->prepare("
                DELETE FROM ricette 
                WHERE titolo = ?
                AND nicknameEditore = ?
                AND NOT EXISTS (
                    SELECT *
                    FROM valutazioni V
                    WHERE V.titolo = ?
                    AND V.nicknameEditore = ?
                    AND V.commento IS NOT NULL
                )"
            );
            $stmt2->bind_param("ssss", $title, $nickname, $title, $nickname);
            $stmt2->execute();

            // Non é avvenuta nessuna eliminazione
            if ($stmt2->affected_rows == 0) {
                throw new Exception("Non é possibile eliminare una ricetta che ha ricevuto commenti.");
            }
            $stmt2->close();

            // -- 3. Aggiornamento delle kcal delle diete che usavano la ricetta
            $this->db->query("
                UPDATE diete D
                SET kcalDieta = (
                    SELECT SUM(R.kcalTotali / R.porzioni)
                    FROM ricette R, composizioni C
                    WHERE R.titolo = C.titolo
                    AND R.nicknameEditore = C.nicknameEditore
                    AND C.nomeDieta = D.nome
                    AND C.nicknameAutore = D.nicknameAutore
                ) WHERE EXISTS (
                    SELECT *
                    FROM aggiornabili A
                    WHERE A.nomeDieta = D.nome
                    AND A.nicknameAutore = D.nicknameAutore
                )"
            );

            // -- 4. Eliminazione della tabella temporanea
            $this->db->query("DROP TEMPORARY TABLE aggiornabili");

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function updateRecipe($nickname, $oldTitle, $title, $public, $preparation, $preparationTime, $portions, $ingredients) {
        $this->db->begin_transaction();
        try {
            // -- 1. Rimozione degli ingredienti attualmente in uso
            $stmt1 = $this->db->prepare("
                DELETE FROM utilizzi
                WHERE titolo = ?
                AND nicknameEditore = ?"
            );
            $stmt1->bind_param("ss", $oldTitle, $nickname);
            $stmt1->execute();
            $stmt1->close();

            // -- 2. Aggiunta dei nuovi ingredienti alla ricetta
            $query = "INSERT INTO utilizzi (nomeIngrediente, titolo, nicknameEditore, quantita) VALUES ";
            $pms = array();
            $pTypes = "";
            for ($i = 0; $i < count($ingredients); $i++) {
                $query .= "(?, ?, ?, ?)" . ($i < count($ingredients) - 1 ? "," : "");
                $pTypes .= "sssi";
                array_push($pms, $ingredients[$i][0], $oldTitle, $nickname, $ingredients[$i][1]);
            }
            $stmt2 = $this->db->prepare($query);
            $stmt2->bind_param($pTypes, ...$pms);
            $stmt2->execute();
            $stmt2->close();

            // -- 3. Ricalcolo delle calorie per la ricetta e aggiornamento degli attributi semplici
            $stmt3 = $this->db->prepare("
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
                    titolo = ?,
                    preparazione = ?,
                    tempoPreparazione = ?,
                    porzioni = ?,
                    pubblica = ?
                WHERE titolo = ? AND nicknameEditore = ?
            ");
            $stmt3->bind_param("ssiiiss", $title, $preparation, $preparationTime, $portions, $public, $oldTitle, $nickname);
            $stmt3->execute();
            $stmt3->close();

            // -- 4. Ricalcolo delle calorie totali nelle diete che usano la ricetta
            $stmt4 = $this->db->prepare("
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
                    AND C.nicknameEditore = ?)
            ");
            $stmt4->bind_param("ss", $title, $nickname);
            $stmt4->execute();
            $stmt4->close();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function getRecipeData($nickname, $title) {
        $data = array();
        $stmt = $this->db->prepare("
            SELECT R.*, U.accreditato
            FROM ricette R, utenti U
            WHERE R.nicknameEditore = U.nickname
            AND nicknameEditore = ?
            AND titolo = ?
        ");
        $stmt->bind_param("ss", $nickname, $title);
        $stmt->execute();
        $data["recipe"] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];

        if (count($data["recipe"]) === 0) {
            return null;
        }

        $stmt2 = $this->db->prepare("
            SELECT U.nomeIngrediente, U.quantita, I.unitaMisura
            FROM utilizzi U, ingredienti I
            WHERE U.nomeIngrediente = I.nome
            AND nicknameEditore = ?
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
    con il problema riscontrato durante il login.
    Rimuove la limitazione utente se questa é scaduta */
    public function login($nickname, $password) {
        $role = $this->nicknameRole($nickname);
        if (!$role) {
            throw new Exception("Nickname inesistente");
        }

        $this->updateUserRestriction($nickname);

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

    private function updateUserRestriction($nickname) {
        $stmt = $this->db->prepare("
            UPDATE utenti
            SET fineLimitazione = NULL
            WHERE nickname = ?
            AND fineLimitazione < NOW();
        ");
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $stmt->close();
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