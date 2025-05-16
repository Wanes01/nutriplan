<div class="w-screen h-screen flex flex-col bg-crema justify-center items-center text-2xl">
    <h1 class="italic font-bold">Ciao! Benvenuto in NutriPlan!</h1>
    <ul class="flex flex-col items-center gap-5 mt-7">
        <li class="flex flex-row gap-5 items-center">
            <p>Hai giá un account? ➡️</p>
            <a href="<?php echo TEMPL . "user-login.php"?>"
            class="p-2 border-2 border-legno rounded-lg font-semibold text-orange-900 bg-orange-50">Accedi</a>
        </li>
        <li class="flex flex-row gap-5 items-center">
            <p>Sei nuovo? ➡️</p>
            <a href="<?php echo TEMPL . "user-register.php"?>"
            class="p-2 border-2 border-legno rounded-lg font-semibold text-orange-900 bg-orange-50">Registrati</a>
        </li>
    </ul>
</div>