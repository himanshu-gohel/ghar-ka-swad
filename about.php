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
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>about us</h3>
   <p> <a href="home.php">home</a> / about </p>
</div>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about-bg.jpeg" alt="">
      </div>

      <div class="content">
         <h3 style="text-align: center">why choose us?</h3>
         <p>Ghar ka Swad is dedicated to bringing homemade goodness to your doorstep. We believe in the tradition of homemade cooking and want to connect housewives and tiffin service providers with individuals who crave delicious homemade meals.</p>
         <p>Our platform serves as a marketplace where chefs and tiffin service providers can showcase their culinary talents, and customers can explore a variety of homemade dishes and place orders conveniently.</p>
         <p>We strive to promote homemade cooking, support local talent, and provide a convenient solution for individuals who seek delicious and healthy homemade meals.</p>
         <a href="contact.php" class="btn">contact us</a>
      </div>

   </div>

</section>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>