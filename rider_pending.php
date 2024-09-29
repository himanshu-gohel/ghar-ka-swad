<?php
include 'config.php';
session_start();
$rider_id = $_SESSION['rider_id'];

if(!isset($rider_id)){
   header('location:login.php');
}

if(isset($_GET['accept_order'])){
   $order_id = $_GET['accept_order'];
   // Update order status to 'accepted' when a rider accepts it
   $accept_query = "UPDATE orders SET order_status = 'accepted', rider_id = '$rider_id' WHERE id = '$order_id' AND order_status = 'pending'";
   $accept_result = mysqli_query($conn, $accept_query);
   if($accept_result) {
      header('location:rider_pending.php');
      exit; // Ensure the script stops execution after redirection
   } else {
      die('Failed to accept order'); // Display error message if query fails
   }
}

if(isset($_POST['update_state'])){
   $order_id = $_POST['order_id'];
   $new_state = $_POST['order_state'];
   // Update order status to the selected state
   $update_query = "UPDATE orders SET order_status = '$new_state' WHERE id = '$order_id'";
   $update_result = mysqli_query($conn, $update_query);
   if($update_result) {
      header('location:rider_pending.php');
      exit; // Ensure the script stops execution after redirection
   } else {
      die('Failed to change order state'); // Display error message if query fails
   }
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

    .btn {
        padding: 8px 16px;
        background-color: #8e44ad;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    .dropdown select {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        background-color: #fff;
    }

    .dropdown .option-btn {
        padding: 8px 16px;
        background-color: #8e44ad;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .dropdown .option-btn:hover {
        background-color: #6c3483; 
    }

    .title {
        margin-top: 20px;
    }

   </style>

</head>

<body>
   
<?php include 'rider_header.php'; ?>

<section class="dashboard">
    <div class="order-list">
        <?php
        // $select_orders = mysqli_query($conn, "SELECT orders.*, users.name AS chef_name FROM orders INNER JOIN users ON orders.chef_id = users.id WHERE orders.order_status = 'pending'") or die(mysqli_error($conn));
        $select_orders2 = mysqli_query($conn, "SELECT orders.*, users.name AS chef_name, users.address AS chef_address FROM orders INNER JOIN users ON orders.chef_id = users.id WHERE (chef_status = 'accepted' OR chef_status = 'preparing' OR chef_status = 'ready') AND (rider_id = 0 OR rider_id = '$rider_id')") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_orders2) > 0){
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Order ID</th>';
            echo '<th>Placed On</th>'; 
            echo '<th>Customer Name</th>';
            echo '<th>Contact Number</th>';
            echo '<th>Payment Method</th>';
            echo '<th>Preparation Status</th>';
            echo '<th>Chef Name</th>';
            echo '<th>Chef Address</th>';
            echo '<th>Delivery Address</th>';
            echo '<th>Total Price</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($select_orders2)) {
                // Check if the order status is DELIVERED
                if ($row['order_status'] != 'delivered') {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['placed_on'] . '</td>'; 
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['number'] . '</td>';
                    echo '<td>' . $row['method'] . '</td>';
                    echo '<td>' . $row['chef_status'] . '</td>';
                    echo '<td>' . $row['chef_name'] . '</td>';
                    echo '<td>' . $row['chef_address'] . '</td>';
                    echo '<td>' . $row['address'] . '</td>';
                    echo '<td>' . $row['total_price'] . '/-</td>';
                    echo '<td>';
                    if ($row['order_status'] == 'pending') {
                        echo "<a href='rider_pending.php?accept_order={$row['id']}' class='btn'>Accept Order</a>";
                    }
                    if ($row['order_status'] == 'accepted' || $row['order_status'] == 'in delivery' || $row['order_status'] == 'delivered') {
                        echo "<form method='POST' action='rider_pending.php'>";
                        echo "<div class='dropdown'>";
                        echo "<input type='hidden' name='order_id' value='{$row['id']}'>";
                        echo "<select name='order_state' class='order-state-dropdown'>";
                        echo "<option value='' selected disabled>{$row['order_status']}</option>";
                        echo '<option value="in delivery">In Delivery</option>';
                        echo '<option value="delivered">Delivered</option>';
                        echo '</select>';
                        echo '<button type="submit" class="option-btn" name="update_state">Update</button>';
                        echo '</div>';
                        echo '</form>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
            }            
            echo '</tbody>';
            echo '</table>';
        } else {
            echo "<h1 >No pending orders available.</h1>";
        }
        ?>
    </div>
    </section>

<!-- Custom JS file link -->
<script src="js/admin_script.js"></script>

</body>
</html>
