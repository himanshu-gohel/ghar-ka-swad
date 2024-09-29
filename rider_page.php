<?php

include 'config.php';
session_start();
$rider_id = $_SESSION['rider_id'];

if(!isset($rider_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Rider Panel</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'rider_header.php'; ?>


<section class="dashboard">

   <h1 class="title">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <?php
            $total_completed_amount = 0;
            $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE rider_id = '$rider_id' AND order_status = 'delivered'") or die('Query failed');
            if(mysqli_num_rows($select_completed) > 0){
               while($fetch_completed = mysqli_fetch_assoc($select_completed)){
                  $total_price = $fetch_completed['total_price'];
                  $total_completed_amount += $total_price;
               }
            }
         ?>
         <h3><?php echo $total_completed_amount; ?>/-</h3>
         <p>total order amount</p>
      </div>

      <div class="box">
         <?php 
         $select_accepted = mysqli_query($conn, "SELECT * FROM `orders` WHERE rider_id = '$rider_id' AND order_status = 'accepted'") or die('Query failed');
         $num_orders_accepted = mysqli_num_rows($select_accepted);
         ?>
         <h3><?php echo $num_orders_accepted; ?></h3>
         <p>orders left to deliver</p>
      </div> 


      <div class="box">
         <?php 
            $select_delivered = mysqli_query($conn, "SELECT * FROM `orders` WHERE rider_id = '$rider_id' AND order_status = 'delivered'") or die('Query failed');
            $num_delivered_orders = mysqli_num_rows($select_delivered);
         ?>
         <h3><?php echo $num_delivered_orders; ?></h3>
         <p>delivered orders</p>
      </div>

   </div>

</section>


<!-- Custom JS file link -->
<script src="js/admin_script.js"></script>

</body>
</html>
