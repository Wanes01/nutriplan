<div class="flex flex-col h-screen w-screen justify-center items-center bg-crema">
    <div
        class="flex flex-col items-center border-1 border-legno shadow shadow-legno w-2/3 md:w-1/3 p-2 rounded-md bg-white">
        <h1 class="text-xl font-bold mb-3">Login</h1>
        <form action="<?php echo ROOT . "api/login.php" ?>" method="post" class="flex flex-col gap-6 justify-center w-full px-3 py-2">
            <ul class="flex flex-col gap-3">
                <li class="flex flex-col">
                    <label for="nickname">Nickname</label>
                    <input type="text" name="nickname" id="nickname" required minlength="2" autocomplete="off" class="p-1 border-1 border-legno rounded-md" />
                </li>
                <li class="flex flex-col">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required minlength="8" autocomplete="off" class="p-1 border-1 border-legno rounded-md" />
                </li>
            </ul>
            <?php
            if (isset($_SESSION["loginError"])) {
                echo "<p class='text-red-700 font-semibold'>" . $_SESSION["loginError"] ."</p>";
                unset($_SESSION["loginError"]);
            }
            ?>
            <input type="submit" value="Accedi"
                class="p-1 border-2 font-bold text-orange-900 border-orange-900 rounded-md bg-crema cursor-pointer" />
        </form>
    </div>
</div>