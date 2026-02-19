<?php
// Testing Connection ======================

    $conn = new mysqli("localhost", "root", "password", "mysportsteam");
    
    if(!$conn){
        die(mysqli_error($con));
    }
    

?>