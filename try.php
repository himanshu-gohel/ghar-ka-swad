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
   } else {
      die('Failed to accept order'); // Display error message if query fails
   }
}

if(isset($_GET['update_state'])){
   $order_id = $_GET['id'];
   $new_state = $_GET['order_state'];
   // Update order status to the selected state
   $update_query = "UPDATE orders SET order_status = '$new_state' WHERE id = '$order_id'";
   $update_result = mysqli_query($conn, $update_query);
   if($update_result) {
      header('location:rider_pending.php');
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

.hidden {
    display: none; 
}

   </style>

</head>
<body>
   
<?php include 'rider_header.php'; ?>

<section class="dashboard">
    <div class="order-list">
        <?php
        // Fetch pending orders with additional details
        $select_orders = mysqli_query($conn, "SELECT orders.*, users.name AS chef_name FROM orders INNER JOIN users ON orders.chef_id = users.id WHERE orders.order_status = 'pending'") or die(mysqli_error($conn));
        $select_orders2 = mysqli_query($conn, "SELECT orders.*, users.name AS chef_name FROM orders INNER JOIN users ON orders.chef_id = users.id") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_orders2) > 0){
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Order ID</th>';
            echo '<th>Customer Name</th>';
            echo '<th>Contact Number</th>';
            echo '<th>Email</th>';
            echo '<th>Payment Method</th>';
            echo '<th>Payment Status</th>';
            echo '<th>Chef Name</th>';
            echo '<th>Delivery Address</th>';
            echo '<th>Total Price</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while($row = mysqli_fetch_assoc($select_orders2)){
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['number'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['method'] . '</td>';
                echo '<td>' . $row['payment_status'] . '</td>';
                echo '<td>' . $row['chef_name'] . '</td>';
                echo '<td>' . $row['address'] . '</td>';
                echo '<td>$' . $row['total_price'] . '</td>';
                echo '<td>';
                if ($row['order_status'] == 'pending') {
                    echo "<a href='rider_pending.php?accept_order={$row['id']}' class='btn'>Accept Order</a>";
                }
                if ($row['order_status'] == 'accepted') {
                    echo "<div class='dropdown' id='dropdown-{$row['id']}'>";
                    echo "<select name='order_state[{$row['id']}]' class='order-state-dropdown' data-order-id='{$row['id']}'>";
                    echo '<option value="preparing">Preparing</option>';
                    echo '<option value="in delivery">In Delivery</option>';
                    echo '<option value="delivered">Delivered</option>';
                    echo '</select>';
                    // echo "<a href='rider_pending.php?change_order_state={$row['id']}' class='option-btn'>updt</a>";
                    echo '<button type="submit" class="option-btn" name="update_state">Update</button>';
                    echo '</div>';
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo "<h1>No pending orders available.</h1>";
        }
        ?>
    </div>
</section>

<!-- Custom JS file link -->
<!-- <script src="js/admin_script.js"></script> -->

</body>
</html>
