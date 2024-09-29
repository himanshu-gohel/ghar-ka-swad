<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <!-- jQuery library -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <style>
    

   .container {
      display: flex;
      justify-content: center;
      align-items: center;
   }

   .btn {
      margin-left: 20px;
   }

   .table-container {
      margin-top: 20px;
      overflow-x: auto;
   }

   .orders-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 16px;
   }

   .orders-table th, .orders-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
   }

   .orders-table th {
      background-color: #f2f2f2;
      color: #8e44ad;
      font-size: 16px;
   }

   .orders-table tbody tr:hover {
      background-color: #f5f5f5;
   }

   </style>

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Your Orders</h3>
   <p><a href="home.php">Home</a> / Orders </p>
</div>

<section class="placed-orders">

   <div class="container">
      <button type="button" class="option-btn" id="placed-order-btn">Showing Placed Orders</button> 
      <button type="button" class="btn" id="order-history-btn">Order History</button>
   </div>    

   <div class="table-container placed-orders-content">
      <table class="orders-table">
         <thead>
            <tr>
               <th>Placed On</th>
               <th>Name</th>
               <th>Number</th>
               <th>Email</th>
               <th>Address</th>
               <th>Payment Method</th>
               <th>Total Products</th>
               <th>Total Price</th>
               <th>Order Status</th>
               <th>Rider Status</th>
            </tr>
         </thead>
         <tbody>

            <?php
           
            $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND order_status != 'delivered' AND chef_status != 'declined'") or die('Query failed');
            if (mysqli_num_rows($order_query) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
            ?>
            <tr>
               <td><?php echo $fetch_orders['placed_on']; ?></td>
               <td><?php echo $fetch_orders['name']; ?></td>
               <td><?php echo $fetch_orders['number']; ?></td>
               <td><?php echo $fetch_orders['email']; ?></td>
               <td><?php echo $fetch_orders['address']; ?></td>
               <td><?php echo $fetch_orders['method']; ?></td>
               <td><?php echo $fetch_orders['total_products']; ?></td>
               <td><?php echo $fetch_orders['total_price']; ?>/-</td>
               <td><?php echo $fetch_orders['chef_status']; ?></td>
               <td><?php echo $fetch_orders['order_status']; ?></td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="10">No orders placed yet!</td></tr>';
            }
            ?>
         </tbody>
      </table>
   </div>

</section>

<section class="order-history">

   <div class="container">
      <button type="button" class="option-btn" id="placed-order-btn-2">Showing Placed Orders</button> 
      <button type="button" class="btn" id="order-history-btn-2">Order History</button>
   </div>            
   
   <div class="table-container order-history-content">
      <table class="orders-table">
         <thead>
            <tr>
               <th>Placed On</th>
               <th>Name</th>
               <th>Number</th>
               <th>Email</th>
               <th>Address</th>
               <th>Payment Method</th>
               <th>Total Products</th>
               <th>Total Price</th>
               <th>Order Status</th>
               <th>Rider Status</th>
            </tr>
         </thead>
         <tbody>
            <?php
          
            $order_history_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND ((chef_status = 'ready' AND order_status = 'delivered') OR chef_status = 'declined')") or die('Query failed');
            if (mysqli_num_rows($order_history_query) > 0) {
                while ($fetch_order_history = mysqli_fetch_assoc($order_history_query)) {
            ?>
            <tr>
               <td><?php echo $fetch_order_history['placed_on']; ?></td>
               <td><?php echo $fetch_order_history['name']; ?></td>
               <td><?php echo $fetch_order_history['number']; ?></td>
               <td><?php echo $fetch_order_history['email']; ?></td>
               <td><?php echo $fetch_order_history['address']; ?></td>
               <td><?php echo $fetch_order_history['method']; ?></td>
               <td><?php echo $fetch_order_history['total_products']; ?></td>
               <td><?php echo $fetch_order_history['total_price']; ?>/-</td>
               <td><?php echo $fetch_order_history['chef_status']; ?></td>
               <td><?php echo $fetch_order_history['order_status']; ?></td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="10">No order history available!</td></tr>';
            }
            ?>
         </tbody>
      </table>
   </div>
</section>


<?php include 'footer.php'; ?>

<script>
$(document).ready(function(){
   
    $('.order-history').hide();

    
    $('#placed-order-btn, #placed-order-btn-2').click(function(){
        $('.placed-orders').show();
        $('.order-history').hide();
    });

    
    $('#order-history-btn, #order-history-btn-2').click(function(){
        $('.placed-orders').hide();
        $('.order-history').show();
    });
});
</script>

<script src="js/script.js"></script>

</body>
</html>
