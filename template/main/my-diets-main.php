<div class="flex flex-col h-screen justify-center items-center gap-5">
    <h1 class="text-xl font-bold">Le tue diete</h1>
    <p class="italic">Le calorie totali delle diete fanno riferimento ad una singola porzione di ogni ricetta in esse contenute</p>
    <ul class="flex flex-col rounded-md overflow-hidden gap-3">
        <?php foreach ($params["diets"] as $diet): ?>
            <li class="flex flex-row items-center justify-center gap-20 p-3 border-2 border-legno bg-crema overflow-hidden rounded-md">
                <div class="flex flex-col items-center justify-center">
                    <p class="underline font-semibold">Nome</p>
                    <p><?php echo $diet["nome"] ?></p>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <p class="underline font-semibold">KCAL Totali</p>
                    <p><?php echo $diet["kcalDieta"] . " kcals" ?></p>
                </div>
                <div class="flex flex-row gap-3">
                    <a href="<?php echo ROOT . "api/diet-crud.php?delete=&name=" . rawurlencode($diet["nome"]) ?>" class="border-2 border-red-900 text-red-900 font-semibold rounded-md bg-white py-3 px-2">Elimina ✖️</a>
                    <a href="<?php echo ROOT . "template/diet.php?name=" . rawurlencode($diet["nome"]) ?>" class="border-2 border-blue-900 text-blue-900 font-semibold rounded-md bg-white py-3 px-2">Visualizza ➡️</a>
                </div>
            </li>
        <?php endforeach; ?>
        <?php
        if (empty($params["diets"])) {
            echo "<li>Non hai ancora registrato nessuna dieta!</li>";
        }
        ?>
    </ul>
    <form action="<?php echo ROOT . "api/diet-crud.php" ?>" method="post" class="border-1 p-2 rounded-md flex flex-col">
        <label for="name" class="text-center">Nome dieta</label>
        <input type="text" name="name" required minlength="3" autocomplete="off" id="name" class="p-1 border-1 rounded-md"/>
        <input type="submit" name="add" value="Aggiungi dieta ➕" class="border-2 border-oliva p-2 bg-green-100 rounded-md mt-3 cursor-pointer" />
    </form>
    <?php
    if (isset($_SESSION["dietError"])) {
        echo "<p class='text-red-700 font-semibold text-center'>Hai giá registrato una dieta con questo nome!</p>";
        unset($_SESSION["dietError"]);
    }
    ?>
</div>