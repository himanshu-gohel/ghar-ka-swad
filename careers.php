<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <style>

        body {
            font-family: 'Rubik', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
        }
        .career-option {
            display: inline-block;
            margin: 20px;
        }
        .career-option img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
        }
        .career-option h2 {
            margin-top: 10px;
        }
        .career-option button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #8e44ad;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .career-option button:hover {
            background-color: black;
            color: white;
        }
    </style>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head> 
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Work With Us</h3>
   <p> <a href="home.php">home</a> / careers </p>
</div> 

<div class="container">
        <div class="career-option">
            <img src="images/chef.jpeg" alt="Chef"> <br> <br> <br>
            <button onclick="location.href='chef_registration.php';">Apply as Chef</button>
        </div>
        <div class="career-option">
            <img src="images/rider.png" alt="Rider"> <br> <br> <br>
            <button onclick="location.href='rider_registration.php';">Join as Rider</button>
        </div>
    </div>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>