<?php
if (isset($params["recipeData"])) {
    $recipe = $params["recipeData"]["recipe"];
    $ingrs = $params["recipeData"]["ingredients"];
}
?>

<div class="flex flex-col justify-center items-center mt-2">
    <form action="<?php echo ROOT . "api/recipe-crud.php" ?>" method="post" class="flex flex-col gap-2 border-2 bg-crema border-legno p-4 rounded-lg w-1/2">
        <div class="flex flex-col gap-1">
            <label for="title">Titolo</label>
            <input type="text" name="title" id="title" required minlength="3" autocomplete="off" class="p-1 border-1 border-legno rounded-md bg-white" value="<?php echo (isset($recipe) ? $recipe["titolo"] : "") ?>" />
        </div>
        <div class="flex flex-col gap-1">
            <label for="preparation">Preparazione</label>
            <textarea name="preparation" id="preparation" required minlength="3" autocomplete="off" class="resize-none min-h-70 p-1 border-1 border-legno rounded-md bg-white"><?php echo (isset($recipe) ? $recipe["preparazione"] : "") ?></textarea>
        </div>
        <div class="flex flex-row justify-between">
            <div class="flex flex-col gap-1">
                <label for="preparationTime">Tempo di preparazione (minuti)</label>
                <input type="number" name="preparationTime" id="preparationTime" required min="1" class="p-1 border-1 border-legno rounded-md bg-white" value="<?php echo (isset($recipe) ? $recipe["tempoPreparazione"] : "") ?>"/>
            </div>
            <div class="flex flex-col gap-1">
                <label for="portions">Porzioni</label>
                <input type="number" name="portions" id="portions" required min="1" class="p-1 border-1 border-legno rounded-md bg-white" value="<?php echo (isset($recipe) ? $recipe["porzioni"] : "") ?>"/>
            </div>
            <div class="flex flex-row justify-center items-center gap-1">
                <input type="checkbox" name="public" id="public" class="mt-1 w-4 h-4 accent-oliva" class="p-1 border-1 border-legno rounded-md bg-white" <?php echo (isset($recipe) && $recipe["pubblica"] == 1 ? "checked" : "") ?>/>
                <label for="public">Pubblica</label>
            </div>
        </div>
        <div class="flex flex-row justify-center items-center gap-5">
            <div class="flex flex-col gap-1">
                <p>Ingredienti utilizzabili</p>
                <ul class="border-1 bg-slate-50 p-2 max-h-60 overflow-y-scroll rounded-md divide-y-1 divide-gray-400">
                    <?php foreach ($params["ingredients"] as $ingr): ?>
                    <li id="<?php echo normalizeToIdentifier($ingr["nome"]) ?>" class="py-1 text-center cursor-pointer"><?php echo $ingr["nome"] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="flex flex-col gap-1">
                <p>Ingredienti usati</p>
                <textarea name="usedIngredients" id="usedIngredients" required minlength="3" autocomplete="off" class="resize-none h-60 p-1 border-1 border-legno rounded-md bg-white"><?php 
                    if (isset($ingrs)) {
                        $str = "";
                        foreach ($ingrs as $ingr) {
                            $str .= $ingr["nomeIngrediente"] . "," . $ingr["quantita"] . "\n";
                        }
                        echo $str;
                    }
                 ?></textarea>
            </div>
        </div>
        <?php
            if (isset($_SESSION["recipeError"])) {
                echo "<p class='text-red-700 font-semibold text-center'>" . $_SESSION["recipeError"] ."</p>";
                unset($_SESSION["recipeError"]);
            }
        ?>
        <div class="flex flex-row justify-center mt-5">
            <?php
            if ($_GET["action"] == "add") {
                echo '<input type="submit" name="add" value="Aggiungi ricetta" class="border-2 border-green-700 text-green-700 bg-white px-3 py-2 rounded-md font-semibold cursor-pointer"/>';
            } else {
                echo '<div class="flex flex-col justify-center items-center gap-2">
                <input type="submit" name="update" value="Modifica ricetta" class="border-2 border-legno text-orange-900 bg-white px-3 py-2 rounded-md font-semibold cursor-pointer" />
                <p class="underline">Stai modificando: <span id="oldTitle" class="no-underline">'. $recipe["titolo"] .'</span></p>
                </div>';
            }
            ?>
            <!-- <input type="submit" name="delete" value="Elimina ricetta" class="border-2 border-red-700 text-red-700 bg-white px-3 py-2 rounded-md font-semibold cursor-pointer" /> -->
        </div>
    </form>
</div>