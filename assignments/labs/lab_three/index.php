<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Form</h2>
    <form method="post">
        <label>First Name:</label><br>
        <input type="text" name="firstName" required><br>
        <label>Last Name:</label><br>
        <input type="text" name="lastName" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br> 
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php
require "clientside/client.php"
?>