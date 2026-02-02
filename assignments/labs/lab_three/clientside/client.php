<?php
//Echoing Inputs---------
    echo "{$_POST["firstName"]} <br>";
    echo "{$_POST["lastName"]} <br>";
    echo "{$_POST["email"]} <br>";

//Sanitization---------
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS); 
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS); 
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

?>