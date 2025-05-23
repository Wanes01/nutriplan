<div class="min-h-screen flex flex-row mt-3 gap-3">
    <div class="basis-1/3 flex flex-col rounded-r-2xl bg-orange-200 p-5 border-2 border-legno">
        <form action="#" method="post" class="flex flex-col gap-3">
            <div class="flex flex-col gap-1">
                <label for="titolo">Titolo</label>
                <input type="text" name="title" autocomplete="off" id="title" class="bg-white p-1 border-2 border-legno rounded-md"/>
            </div>
            <div class="flex flex-row gap-1 items-center">
                <label for="minKcals" class="block w-55">Calorie minime per porzione</label>
                <input type="number" min="1" step=".01" name="minKcals" id="minKcals" class="bg-white p-1 border-2 border-legno rounded-md" />
            </div>
            <div class="flex flex-row gap-1 items-center">
                <label for="maxKcals" class="block w-55">Calorie massime per porzione</label>
                <input type="number" min="1" step=".01" name="maxKcals" id="maxKcals" class="bg-white p-1 border-2 border-legno rounded-md" />
            </div>
            <div class="flex flex-row gap-1 items-center">
                <label for="minPrice" class="block w-55">Prezzo minimo per porzione</label>
                <input type="number" min="0.1" step=".01" name="minPrice" id="minPrice" class="bg-white p-1 border-2 border-legno rounded-md" />
            </div>
            <div class="flex flex-row gap-1 items-center">
                <label for="maxPrice" class="block w-55">Prezzo massimo per porzione</label>
                <input type="number" min="1" step=".01" name="maxPrice" id="minPrice" class="bg-white p-1 border-2 border-legno rounded-md" />
            </div>
            <div class="flex flex-row gap-1 items-center">
                <input type="checkbox" name="accredited" id="accredited" class="w-4 h-4 accent-legno" />
                <label for="accredited">Solo accreditati</label>
            </div>
            <p class="mt-5 underline">Ordina per...</p>
            <div class="flex flex-row gap-1 items-center">
                <input type="radio" name="order" id="incrKcal" value="incrKcal" class="w-4 h-4 accent-legno" />
                <label for="incrKcal">Calorie per porzione crescenti</label>
            </div>
            <div class="flex flex-row gap-1 items-center">
                <input type="radio" name="order" id="decrKcal" value="decrKcal" class="w-4 h-4 accent-legno" />
                <label for="decrKcal">Calorie per porzione decrescenti</label>
            </div>
            <div class="flex flex-row gap-1 items-center">
                <input type="radio" name="order" id="random" value="random" checked class="w-4 h-4 accent-legno" />
                <label for="random">Randomico</label>
            </div>
            <input type="submit" value="Cerca ricette" class="mx-10 mt-5 py-1 px-5 border-2 border-legno rounded-full text-orange-900 font-semibold bg-white cursor-pointer" />
        </form>
    </div>
    <div class="basis-2/3 grid grid-cols-3 gap-3 px-2 grid-rows-7">
    <?php foreach ($recipes as $rec): ?>
        <div class="flex flex-row p-3 border-1 border-legno shadow shadow-gray-300 rounded-md gap-2 bg-crema">
            <div class="flex flex-col">
                <p class="font-bold"><?php echo $rec['titolo'] ?></p>
                <p>di <span class="italic"><?php echo $rec['nicknameEditore'] ?></span>
                <?php
                if ($rec['accreditato'] == 1) {
                    echo '<span class="p-1 text-sm bg-green-200 border-1 rounded-md ml-2">Acc.✔️</span>';
                }
                ?>
            </p>
                <p><?php echo number_format($rec["kcalTotali"] / $rec["porzioni"], 2) ?> kcal/porzione</p>
                <p>€<?php echo number_format($rec["costoTotale"] / $rec["porzioni"], 2) ?>/porzione</p>
            </div>
            <a href="<?php echo ROOT . "template/recipe.php?title=" . rawurlencode($rec["titolo"]) . "&nickname=" . rawurlencode($rec['nicknameEditore']) ?>" class="border-2 border-legno text-orange-900 font-semibold flex-1 flex flex-col items-center justify-center rounded-md bg-white px-2">Visualizza<span>➡️</span></a>
        </div>
    <?php endforeach; ?>
    </div>
</div>