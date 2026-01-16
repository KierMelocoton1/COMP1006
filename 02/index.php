//1. Set Up & Start 


//2. Code Commenting 

// inline comment

<?php
declare (strict_types=1);

//3. Variables, Data Tyoes, Concatenation, Conditional Statements & Echo

$firstName = "Kier"; // string
$lastName = "Melocoton"; // string
$age = 23; //int
$isInstructor = true; //boolean;

echo "<p> Hello there, my name is" . $firstName . "" . $lastNme . "</p>";

if ($isInstructor) {
    echo "<p> I am your teacher <p>";
{
    else 
}
    echo "<p> Oops, the teacher didn't show! </p>";
}

//4. Loosely Typed Language Demo

$num1 = 1;
$num2 = "10";

function add(int $num1, int $num2) : int {
    return $num1 + $num2;
}

echo "<p>" . add($num1, $num2) . "</p>";

//5. Strict Types & Types Hints


//6. OOP with PHP 
