<div class="flex flex-col justify-center items-center gap-3 h-[85vh]">
    <h1 class="font-bold text-lg"><?php echo $diet['nome'] ?></h1>
    <p class="italic"><?php echo $diet['kcalDieta'] ?> kcal</p>
    <ul class="flex flex-col gap-3">
    <?php if (empty($recipes)): ?>
        <p class="text-oliva font-semibold italic">Non hai ancora inserito alcuna ricetta in questa dieta!</p>
    <?php else: ?>
        <p class="italic text-legno text-center">La dieta risulta: <span class="font-semibold"><?php echo computeTag($diet['kcalDieta']) ?></span></p>
    <?php endif; ?>
    <?php foreach($recipes as $rec): ?>
        <li class="flex flex-row items-center p-3 border-2 border-legno rounded-md bg-crema gap-3">
            <h2 class="mr-5"><span class="font-semibold"><?php echo $rec['titolo'] ?></span> di <span class="underline"><?php echo $rec['nicknameEditore'] ?></span></h2>
            <div class="flex flex-row flex-1 justify-end gap-2">
                <a href="<?php echo ROOT . "api/diet-crud.php?del=&title=" . rawurlencode($rec['titolo']) . "&editor=" . rawurlencode($rec['nicknameEditore'])  . "&dName=" . rawurlencode($diet['nome']) ?>" class="p-2 border-2 border-red-800 text-red-800 font-semibold rounded-md bg-white">Rimuovi ❌</a>
                <a href="<?php echo ROOT . "template/recipe.php?title=" . rawurlencode($rec['titolo']) . "&nickname=" . rawurlencode($rec['nicknameEditore']) ?>" class="p-2 border-2 border-blue-800 text-blue-800 font-semibold rounded-md bg-white">Visualizza ➡️</a>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
</div>