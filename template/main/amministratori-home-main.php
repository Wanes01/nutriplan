<div class="flex flex-col px-3 mt-3">
    <div class="grid grid-cols-5">
        <!-- Visualizzazione ingredienti e aggiunta -->
        <div class="flex flex-col gap-3 col-span-3">
            <!-- table card -->
            <div class="flex flex-col border-2 border-legno rounded-md overflow-hidden">
                <div class="flex flex-col pt-2 bg-mattone font-semibold border-b-2 border-legno">
                    <h1 class="text-center text-lg pb-2 border-b-2 border-legno">Ingredienti utilizzabili (valori relativi a 100g)</h1>
                    <ul class="flex flex-row text-center items-center">
                        <li class="w-8/28">Nome</li>
                        <li class="w-1/14">KCAL</li>
                        <li class="w-1/14">Costo</li>
                        <li class="w-2/15">Carboidrati</li>
                        <li class="w-2/15">Proteine</li>
                        <li class="w-83/840">Grassi insaturi</li>
                        <li class="w-83/840">Grassi saturi</li>
                        <li class="w-1/7">Misura</li>
                    </ul>
                </div>
                <div id="table-content" class="flex flex-col pb-2 h-[70vh] overflow-y-scroll overscroll-none">
                    <?php foreach ($params['ingredienti'] as $ingr): ?>
                    <ul id="<?php echo normalizeToIdentifier($ingr['nome'])?>" class="flex flex-row text-center items-center py-2 border-b-1 border-legno odd:bg-crema last:border-0 cursor-pointer">
                        <li id="<?php echo normalizeToIdentifier($ingr['nome']) . "-nome" ?>" class="w-8/28"><?php echo $ingr['nome'] ?></li>
                        <li id="<?php echo normalizeToIdentifier($ingr['nome']) . "-kcal" ?>" class="w-1/14"><?php echo $ingr['kcal'] ?></li>
                        <li id="<?php echo normalizeToIdentifier($ingr['nome']) . "-costo" ?>" class="w-1/14"><?php echo $ingr['costo'] ?></li>
                        <li id="<?php echo normalizeToIdentifier($ingr['nome']) . "-carboidrati" ?>" class="w-2/15"><?php echo $ingr['carboidrati'] ?></li>
                        <li id="<?php echo normalizeToIdentifier($ingr['nome']) . "-proteine" ?>" class="w-2/15"><?php echo $ingr['proteine'] ?></li>
                        <li id="<?php echo normalizeToIdentifier($ingr['nome']) . "-grassiInsaturi" ?>" class="w-83/840"><?php echo $ingr['grassiInsaturi'] ?></li>
                        <li id="<?php echo normalizeToIdentifier($ingr['nome']) . "-grassiSaturi" ?>" class="w-83/840"><?php echo $ingr['grassiSaturi'] ?></li>
                        <li id="<?php echo normalizeToIdentifier($ingr['nome']) . "-unitaMisura" ?>" class="w-1/7"><?php echo $ingr['unitaMisura'] ?></li>
                    </ul>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-3 col-span-2 justify-center items-center">
            <form action="<?php echo ROOT . "api/ingredient-crup.php" ?>" method="post" class="border-2 border-legno bg-crema px-5 py-3 rounded-md">
                <ul class="flex flex-col gap-3">
                    <li class="flex flex-col">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id="name" required minlength="2" autocomplete="off" class="p-1 border-1 border-legno rounded-md bg-white"/>
                    </li>
                    <li class="flex flex-row gap-3">
                        <div class="flex flex-col">
                            <label for="name">KCAL</label>
                            <input type="number" name="kcal" id="kcal" required min="0" max="1000" step=".01" class="p-1 border-1 border-legno rounded-md min-w-40 bg-white"/>
                        </div>
                        <div class="flex flex-col">
                            <label for="price">Costo</label>
                            <input type="number" name="price" id="price" required min="0" max="1000" step=".01" class="p-1 border-1 border-legno rounded-md min-w-40 bg-white"/>
                        </div>
                    </li>
                    <li class="flex flex-row gap-3">
                        <div class="flex flex-col">
                            <label for="carbs">Carboidrati</label>
                            <input type="number" name="carbs" id="carbs" required min="0" max="1000" step=".01" class="p-1 border-1 border-legno rounded-md min-w-40 bg-white"/>
                        </div>
                        <div class="flex flex-col">
                            <label for="proteins">Proteine</label>
                            <input type="number" name="proteins" id="proteins" required min="0" max="1000" step=".01" class="p-1 border-1 border-legno rounded-md min-w-40 bg-white"/>
                        </div>
                    </li>
                    <li class="flex flex-row gap-3">
                        <div class="flex flex-col">
                            <label for="unsFat">Grassi insaturi</label>
                            <input type="number" name="unsFat" id="unsFat" required min="0" max="1000" step=".01" class="p-1 border-1 border-legno rounded-md min-w-40 bg-white"/>
                        </div>
                        <div class="flex flex-col">
                            <label for="satFat">Grassi saturi</label>
                            <input type="number" name="satFat" id="satFat" required min="0" max="1000" step=".01" class="p-1 border-1 border-legno rounded-md min-w-40 bg-white"/>
                        </div>
                    </li>
                    <li class="flex flex-row gap-3 justify-center items-center">
                        <label for="unit">Unitá di misura</label>
                        <select name="unit" id="unit" class="p-1 border-1 border-legno rounded-md bg-white min-w-20">
                            <option value="g">g</option>
                            <option value="ml">ml</option>
                        </select>
                    </li>
                </ul>
                <?php
                if (isset($_SESSION["success"]) || isset($_SESSION["ingredientError"])) {
                    echo "<p class='text-center mt-1 max-w-80 " . (isset($_SESSION["ingredientError"]) ? "text-red-700" : "text-green-700") . " font-semibold'>" . (isset($_SESSION["ingredientError"]) ? $_SESSION["ingredientError"] : $_SESSION["success"]) ."</p>";
                    unset($_SESSION["ingredientError"]);
                    unset($_SESSION["success"]);
                }
                ?>
                <div class="flex flex-col gap-3 items-center justify-center mt-5">
                    <input type="submit" name="add" value="Aggiungi ingrediente" class="bg-white py-2 border-2 border-oliva rounded-md font-semibold text-green-800 w-full cursor-pointer" />
                    <div class="flex flex-col gap-3 items-center justify-center border-2 rounded-md border-gray-400 bg-slate-50 p-1 w-full">
                        <p class="bg-white py-2 rounded-md text-center w-full"><u>Selezionato:</u> <span></span></p>
                        <input type="submit" name="update" value="Modifica ingrediente" class="bg-white py-2 border-2 border-legno rounded-md font-semibold text-orange-900 w-full cursor-pointer" />
                        <input type="submit" name="delete" value="Elimina ingrediente" class="bg-white py-2 border-2 border-red-800 rounded-md font-semibold text-red-900 w-full cursor-pointer" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>