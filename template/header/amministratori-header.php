<header class="flex flex-row items-center justify-between py-2 px-1 bg-salvia">
    <div class="flex flex-row items-center justify-center gap-3">
        <p class="text-2xl font-bold">NutriPlan - ADMIN</p>
        <a href="<?php echo ROOT . "api/update-accredited.php" ?>" class="border-2 boder-legno text-orange-900 bg-crema font-semibold py-1 px-2 rounded-md">Aggiorna accreditati ✅🔁</a>
        <a href="<?php echo ROOT . "api/update-restrictions.php" ?>" class="border-2 boder-legno text-orange-900 bg-crema font-semibold py-1 px-2 rounded-md">Aggiorna limitazioni 👤❌</a>
    </div>
    <p class="text-xl">Bentornato <?php echo $_SESSION['nickname']?>! 👋</p>
    <div class="flex flex-row items-center justify-center gap-3">
        <a href="<?php echo ROOT . "template/home.php" ?>" class="border-2 boder-legno text-orange-900 bg-crema font-semibold py-1 px-2 rounded-md">Ingredienti 🍇</a>
        <a href="<?php echo ROOT . "template/recipe-search.php" ?>" class="border-2 boder-legno text-orange-900 bg-crema font-semibold py-1 px-2 rounded-md">Cerca ricette 🔎</a>
        <a href="<?php echo ROOT . "api/logout.php" ?>" class="border-2 boder-legno text-orange-900 bg-crema font-semibold py-1 px-2 rounded-md">Logout ↪</a>
    </div>
</header>