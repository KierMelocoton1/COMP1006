<?php
// Database Insertion ======================

  include "connect.php";

  if(isset($_POST["submit"])){
    $number = $_POST["number"];
    $firstname = $_POST["fname"];
    $lastname =$_POST["lname"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $email =$_POST["position"];
    $team =$_POST["team"];
   
    $sql = "insert into 'mysportsteam' (number, fname, lname, phone, email, position, team)values('$number', '$firstname', '$lastname', '$phone', '$email', '$email', '$team')";

    $result = mysqli_query($conn, $sql);

    if($result){
      echo "Data insertion successful";
    }
    else{
      die(mysqli_error($con));
    }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Players</title>
  </head>
  <body>
    <div class="container my-5">
      <form method="post">

        <div class="form-group">
          <label>Number:</label>
          <input type="text" class="form-control" placeholder="Enter player number" name="number">
        </div>

        <div class="form-group">
          <label>First Name:</label>
          <input type="text" class="form-control" placeholder="Enter first name" name="fname">
        </div>

        <div class="form-group">
          <label>Last Name:</label>
          <input type="text" class="form-control" placeholder="Enter last name" name="lname">
        </div>

        <div class="form-group">
          <label>Phone:</label>
          <input type="text" class="form-control" placeholder="Enter phone number" name="phone">
        </div>

        <div class="form-group">
          <label>Email:</label>
          <input type="text" class="form-control" placeholder="Enter email" name="email">
        </div>

        <div class="form-group">
           <label for="position">Position:</label>
            <select id="position" name="position">
              <option value="centre">Centre</option>
              <option value="smallforward">Small Forward</option>
              <option value="powerforward">Power Forward</option>
              <option value="pointguard">Point Guard</option>
              <option value="shootingguard">Shooting Guard</option>
            </select> 
        </div>

        <div class="form-group">
          <label for="team">Team:</label>
            <select id="team" name="team">
              <option value="barrie">Barrie Bears</option>
              <option value="toronto">Toronto Mooses</option>
              <option value="waterloo">Waterloo Beavers</option>
              <option value="thunderbay">Thunder Bay Ravens</option>
              <option value="ottawa">Ottawa Lions</option>
            </select> 
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
      </form>
    </div>
  </body>
</html>
