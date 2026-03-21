<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filename = $_FILES["image"]["name"];
    $filePath = "./uploads/" . $filename;


move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__ . $filePath);

require __DIR__ . "/connect.php";

$sql = "INSERT INTO images (filename, url) VALUES (?, ?)";
$pdoStatement = $pdo->prepare($sql);

$result = $pdoStatement->execute([$filename, $filePath]);

if($result) {
    $message = "Image has been uploaded";
}
else {
    $message = "Image could not be uploaded";
}

}
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
    <header>
        <h1>Image Upload</h1>
    </header>
    <main>
        <form action="upload.php" method="post" enctype="multipart/form-data"> 
            <div class="fileupload">
                <input type ="file" name="image" id="image" accept=".jpg, .jpeg, .png"/>
            </div>
            <div class= "button">
                <input type="submit" value="Submit">
            </div><br>
            <a href="index.php">Go Back</a>
        </form><br>
    </main>
</body>
</html>