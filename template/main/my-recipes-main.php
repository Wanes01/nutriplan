<div class="flex flex-col h-screen justify-center items-center gap-5">
    <h1 class="text-xl font-bold">Le tue ricette</h1>
    <ul class="flex flex-col rounded-md overflow-hidden gap-3">
        <?php foreach ($params["recipes"] as $recipe): ?>
            <li class="flex flex-row items-center justify-center gap-20 p-3 border-2 border-legno bg-crema overflow-hidden rounded-md">
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
                    <a href="<?php echo ROOT . "api/recipe-crud.php?del=&title=" . rawurlencode($recipe["titolo"]) ?>" class="border-2 border-red-900 text-red-900 font-semibold rounded-md bg-white py-3 px-2">Elimina ✖️</a>
                    <a href="<?php echo ROOT . "template/recipe-crud.php?action=update&title=" . rawurlencode($recipe["titolo"]) ?>" class="border-2 border-legno text-orange-900 font-semibold rounded-md bg-white py-3 px-2">Modifica ✏️</a>
                    <a href="<?php echo ROOT . "template/recipe.php?title=" . rawurlencode($recipe["titolo"]) . "&nickname=" . rawurlencode($_SESSION['nickname']) ?>" class="border-2 border-blue-900 text-blue-900 font-semibold rounded-md bg-white py-3 px-2">Visualizza ➡️</a>
                </div>
            </li>
        <?php endforeach; ?>
        <?php
        if (empty($params["recipes"])) {
            echo "<li>Non hai ancora registrato nessuna ricetta!</li>";
        }
        if (isset($_SESSION["recipeError"])) {
            echo "<p class='text-red-700 font-semibold text-center'>" . $_SESSION["recipeError"] ."</p>";
            unset($_SESSION["recipeError"]);
        }
        ?>
    </ul>
    <a href="<?php echo ROOT . "template/recipe-crud.php?action=add" ?>" class="border-2 border-oliva p-2 bg-green-100 rounded-md">Aggiungi ricetta ➕</a>
</div>