<?php
//challenge students to create independently initially */ 
require "includes/connect.php";
require "includes/header.php";

// Get all products, newest first
$sql = "SELECT * FR products ORDER BY created_at DESC";
// Prepare the query

?>

<main class="container mt-4">
    <h1 class="mb-4">Our Products</h1>

</main>

<?php require "includes/footer.php"; ?>