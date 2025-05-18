<div class="flex flex-col justify-center mt-6">
    <div class="flex flex-col justify-between items-center gap-2">        
        <h1 class="text-2xl font-bold" id="title"><?php echo $recipe['titolo'] ?></h1>
        <p class="italic">Ricetta a cura di <span id="editor"><?php echo $recipe['nicknameEditore'] ?></span></p>
        <?php
        if ($recipe['accreditato'] == 1) {
            echo '<p class="py-1 px-2 text-[0.9rem] bg-green-200 border-1 border-gray-400 shadow shadow-gray-400 rounded-xl font-semibold">UTENTE ACCREDITATO ✔️</p>';
        }
        ?>
        <div class="flex flex-col items-center justify-center border-1 px-10 py-2 rounded-lg w-3/4 bg-crema gap-5">
            <h2 class="text-lg font-bold underline text-center">Ingredienti ed informazioni</h2>
            <div class="grid grid-cols-2 gap-5 place-items-center">
                <div class="flex flex-col gap-3">
                    <p><span class="font-semibold">Tempo di preparazione:</span> <?php echo $recipe['tempoPreparazione']?> min</p>
                    <p><span class="font-semibold">Porzioni</span> per <?php echo $recipe['porzioni'] ?> persone</p>
                    <p><span class="font-semibold">Apporto calorico:</span> <?php echo number_format($recipe["kcalTotali"] / $recipe["porzioni"], 2) ?> kcal per porzione (<?php echo $recipe["kcalTotali"] ?> kcal totali) </p>
                    <p><span class="font-semibold">Costo stimato:</span> €<?php echo number_format($recipe["costoTotale"] / $recipe["porzioni"], 2) ?> per porzione (€<?php echo $recipe["costoTotale"] ?> totali) </p>
                </div>
                <ul class="list-disc list-inside flex flex-col gap-1">
                    <?php foreach ($ingredients as $ingr): ?>
                        <li><?php echo $ingr['nomeIngrediente'] . " ➡️ " . $ingr['quantita'] . $ingr['unitaMisura']?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <h2 class="text-lg font-bold underline text-center">Preparazione</h2>
            <div class="flex flex-col gap-3">
                <p><?php echo $recipe['preparazione']?></p>
            </div>
        </div>
        <h2 class="font-bold text-lg mt-5 underline">Commenti</h2>
        <p class="w-2/3 text-center italic"><span class="underline">Nota sui commenti</span>: non é possibile commentare una propria ricetta o commentare una ricetta piú di una volta. Un utente che ha subito una limitazione viene momentaneamente rimosso dalla possibilitá di commentare.</p>
        <?php
        // AGGIUNGERE CHECK PER COMMENTO GIAà REGISTRATO
        if ($_SESSION['nickname'] != $recipe['nicknameEditore']
        && $_SESSION['fineLimitazione'] == NULL) {
            echo '
            <form action="' . ROOT . 'api/add-comment.php' . '" method="post" class="flex flex-col gap-1 w-1/2 p-3 border-1 rounded-md">
            <h3 class="font-semibold text-center">Inserisci una valutazione</h3>
            <div class="flex flex-row gap-1 items-center">
                <label for="vote">Voto</label>
                <input type="number" name="vote" min="1" max="5" id="vote" required class="p-1 border-1 rounded-md"/>
                <p>/5</p>
            </div>
            <div class="flex flex-col gap-1">
                <label for="comment">Commento (opzionale)</label>
                <textarea name="comment" id="comment" class="border-1 rounded-md p-1 resize-none h-40 overflow-y-scroll"></textarea>
            </div>
            <input type="submit" value="Aggiungi commento" class="mt-4 border-2 border-legno bg-crema font-semibold text-orange-900 cursor-pointer px-3 py-1 rounded-md w-1/3 self-center" />
        </form>';
        }
        if (isset($_SESSION['commentError'])) {
            echo '<p class="text-center text-red-700">Non puoi commentare due volte la stessa ricetta!</p>';
            unset($_SESSION['commentError']);
        }

        foreach ($comments as $comm):
        ?>
        <div class="flex flex-col border-1 rounded-md p-2">
            <p>Valutazione di <span class="underline italic"><?php echo $comm['nicknameValutatore'] ?></span> | Voto: <?php echo str_repeat("⭐", $comm['voto']) ?></p>
            <?php
            if ($comm['commento']) {
                echo '<p><span class="underline">Commento:</span> ' . $comm['commento'] . '</p>';
            }
            ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>