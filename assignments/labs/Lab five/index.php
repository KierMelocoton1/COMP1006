<?php
require __DIR__ . "/connect.php";

$sql = "SELECT * From images";
$pdoStatement = $pdo->prepare($sql);
$pdoStatement->execute();

$images = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <main>
        <header>
            <h1>Home</h1>
        </header>
        <a href="upload.php">Uploaded Images</a>
            <?php
            foreach ($images as $image) { ?>
                <div style="margin-top: 40px">
                    <div><?= $image["filename"] ?></div>
                    <img src="<?= '.' . $image["url"] ?>" alt="<?= $image["filename"] ?>
                </div>
            <?php
            }
            ?>
    </main>
</body>
</html>