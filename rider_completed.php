<?php
include 'config.php';
session_start();
$rider_id = $_SESSION['rider_id'];

if (!isset($rider_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Rider Panel - Completed Orders</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
    .dashboard {
        margin: 20px;
        text-align: center;
    }

    .order-list table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-list th, .order-list td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        font-size: 16px;
    }

    .order-list th {
        background-color: #f2f2f2;
        color: #8e44ad;
    }

    .order-list tbody tr:hover {
        background-color: #f5f5f5;
    }

   </style>

</head>
<body>
   
<?php include 'rider_header.php'; ?>

<section class="dashboard">
    <div class="order-list">
        <?php
       
        $select_orders = mysqli_query($conn, "SELECT orders.*, users.name AS chef_name, users.address AS chef_address FROM orders INNER JOIN users ON orders.chef_id = users.id WHERE orders.order_status = 'delivered' AND orders.rider_id = '$rider_id'") or die(mysqli_error($conn));
        
        if(mysqli_num_rows($select_orders) > 0){
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Order ID</th>';
            echo '<th>Customer Name</th>';
            echo '<th>Contact Number</th>';
            echo '<th>Email</th>';
            echo '<th>Payment Method</th>';
            echo '<th>Chef Name</th>';
            echo '<th>Chef Address</th>';
            echo '<th>Delivery Address</th>';
            echo '<th>Total Price</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while($row = mysqli_fetch_assoc($select_orders)){
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['number'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['method'] . '</td>';
                echo '<td>' . $row['chef_name'] . '</td>';
                echo '<td>' . $row['chef_address'] . '</td>';                
                echo '<td>' . $row['address'] . '</td>';
                echo '<td>' . $row['total_price'] . '/-</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo "<h1>No completed orders available.</h1>";
        }
        ?>
    </div>
</section>

<!-- Custom JS file link -->
<script src="js/admin_script.js"></script>

</body>
</html>
