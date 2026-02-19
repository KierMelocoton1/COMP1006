<?php
include "connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
    <table class="table table-hover my-5">
        <thead>
            <tr>
            <th scope="col">Number</th>
            <th scope="col">First Name</th>
            <th scope="col">Phone</th>
            <th scope="col">Email</th>
            <th scope="col">Position</th>
            <th scope="col">Team</th>
            </tr>
        </thead>

        <?php
        // Obtaining data from the database ===============

            $sql = "Select * from 'mysportsteam'";
            $result = mysqli_query($conn, $sql);
            if($result){
                while($row = mysqli_fetch_assoc($result)){
                    $number = $row['number'];
                    $fname = $row['fname'];
                    $lname = $row['lname'];
                    $phone = $row['phone'];
                    $email = $row['email'];
                    $position = $row['position'];
                    $team = $row['team'];
                    echo "<tr>
                            <th scope='".$number."'>1</th>
                            <td>".$fname."</td>
                            <td>".$lname."</td>
                            <td>".$phone."</td>
                            <td>".$email."</td>
                            <td>".$position."</td>
                            <td>".$team."</td>
                            <button class='btn btn-primary'><a href='' class='text-light'>Update</a></button>
                            <button class='btn btn-danger'><a href='' class='text-light'>Delete</a></button>
                          </tr>";
                }
            }
        ?>

        <tbody>
            <!-- <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            </tr>
            <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
            </tr>
            <tr>
            <th scope="row">3</th>
            <td colspan="2">Larry the Bird</td>
            <td>@twitter</td>
            </tr>
        </tbody> -->
    </table>
    <div class="container">
        <button class="btn btn-primary my-5"><a href="members.php" class="text-light">Add Player</a>
        </button>
    </div>
</body>
</html>