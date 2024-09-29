<?php

include 'config.php';
session_start();
$chef_id = $_SESSION['chef_id'];

if(!isset($chef_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Chef Panel</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'chef_header.php'; ?>


<section class="dashboard">

   <h1 class="title">Dashboard</h1>

   <div class="box-container">

   <div class="box">
         <?php
            $select_pending = mysqli_query($conn, "SELECT * FROM `orders` WHERE chef_id = '$chef_id' AND chef_status = 'accepted'") or die('Query failed');
            $number_of_pending_orders = mysqli_num_rows($select_pending);
         ?>
         <h3><?php echo $number_of_pending_orders; ?></h3>
         <p>pending Orders</p>
      </div>

      <div class="box">
         <?php
            $select_completed = mysqli_query($conn, "SELECT * FROM `orders` WHERE chef_id = '$chef_id' AND order_status = 'delivered'") or die('Query failed');
            $number_of_completed_orders = mysqli_num_rows($select_completed);
         ?>
         <h3><?php echo $number_of_completed_orders; ?></h3>
         <p>completed Orders</p>
      </div>

      <div class="box">
         <?php
            $total_completed = 0;
            $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE chef_id = '$chef_id' AND order_status = 'delivered'") or die('Query failed');
            if(mysqli_num_rows($select_completed) > 0){
               while($fetch_completed = mysqli_fetch_assoc($select_completed)){
                  $total_price = $fetch_completed['total_price'];
                  $total_completed += $total_price;
               }
            }
         ?>
         <h3><?php echo $total_completed; ?>/-</h3>
         <p>total order amount</p>
      </div>

      <div class="box">
         <?php 
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE chef_id = '$chef_id'") or die('Query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>total Products Added</p>
      </div>
   </div>

</section>


<!-- Custom JS file link -->
<script src="js/admin_script.js"></script>

</body>
</html>
