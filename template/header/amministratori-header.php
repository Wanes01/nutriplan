<header class="flex flex-row items-center justify-between py-2 px-1 bg-salvia">
    <p class="text-2xl font-bold">NutriPlan</p>
    <p class="text-xl">Bentornato <?php echo $_SESSION['nickname']?>! 👋</p>
    <a href="<?php echo ROOT . "api/logout.php" ?>" class="border-2 boder-legno text-orange-900 bg-crema font-semibold py-1 px-2 rounded-md">Logout</a>
</header>