<div class="flex flex-col justify-center mt-6">
    <div class="flex flex-col justify-between items-center gap-2">        
        <h1 class="text-2xl font-bold"><?php echo $recipe['titolo'] ?></h1>
        <p class="italic">Ricetta a cura di <?php echo $recipe['nicknameEditore'] ?></p>
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
    </div>
</div>