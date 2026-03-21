<?php
$dbHost = "localhost"; 
$dbName = "test"; 
$dbUser = "root"; 
$dbPassword = ""; 

$dsn = "mysql:dbname=" . $dbName . ";host=" . $dbHost;

$pdo = new PDO($dsn, $dbUser, $dbPassword);

?>