<!DOCTYPE html>
<html lang="it" class="overscroll-none">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT . "css/tailwind_output.css" ?>">
    <title><?php echo htmlspecialchars($params["title"]); ?></title>
</head>

<body>
    <?php
    if (!empty($params["header"])) {
        include $params["header"];
    }
    ?>

    <main>
        <?php
        if (!empty($params["main"])) {
            include $params["main"];
        } else {
            echo "<p>Contenuto non disponibile.</p>";
        }
        ?>
    </main>

    <?php
    if (!empty($params["footer"])) {
        include $params["footer"];
    }
    ?>

    <?php
    if (!empty($params["js"])) {
        foreach($params["js"] as $jsScript) {
            echo '<script src="'. ROOT . "js/" . $jsScript .'"></script>';
        }
    }
    ?>
</body>

</html>
