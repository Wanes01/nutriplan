<div class="flex flex-col h-screen justify-center items-center gap-5">
    <h1 class="text-xl font-bold">Le tue ricette</h1>
    <ul class="flex flex-col rounded-md overflow-hidden gap-3">
        <?php foreach ($params["recipes"] as $recipe): ?>
            <li class="flex flex-row items-center justify-center gap-30 p-3 border-2 border-legno bg-crema overflow-hidden rounded-md">
                <div class="flex flex-col items-center justify-center">
                    <p class="underline font-semibold">Titolo</p>
                    <p><?php echo $recipe["titolo"] ?></p>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <p class="underline font-semibold">Pubblica</p>
                    <p><?php echo $recipe["pubblica"] == 0 ? "❌" : "✅" ?></p>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <p class="underline font-semibold">KCAL Totali</p>
                    <p><?php echo $recipe["kcalTotali"] . " kcals (" . number_format($recipe["kcalTotali"] / $recipe["porzioni"], 2) . " per porzione)" ?></p>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <p class="underline font-semibold">Costo stimato</p>
                    <p><?php echo "€" . $recipe["costoTotale"]?></p>
                </div>
                <div class="flex flex-row gap-3">
                    <a href="<?php echo ROOT . "template/recipe-crud.php?action=update&title=" . $recipe["titolo"] ?>" class="border-2 border-legno rounded-md bg-white py-3 px-2">Modifica ✏️</a>
                    <a href="" class="border-2 border-legno rounded-md bg-white py-3 px-2">Visualizza ➡️</a>
                </div>
            </li>
        <?php endforeach; ?>
        <?php
        if (empty($params["recipes"])) {
            echo "<li>Non hai ancora registrato nessuna ricetta!</li>";
        }
        ?>
    </ul>
    <a href="<?php echo ROOT . "template/recipe-crud.php?action=add" ?>" class="border-2 border-oliva p-2 bg-green-100 rounded-md">Aggiungi ricetta ➕</a>
</div>