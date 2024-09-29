<?php
include 'config.php';

session_start();

$chef_id = $_SESSION['chef_id'];

if (!isset($chef_id)) { 
    header('location:login.php');
}


if(isset($_GET['accept'])){
    $order_id = $_GET['accept'];
    mysqli_query($conn, "UPDATE `orders` SET chef_status = 'accepted' WHERE id = '$order_id' AND chef_id = '$chef_id'") or die('query failed');
    header('location:chef_orders.php');
}

if(isset($_GET['decline'])){
    $order_id = $_GET['decline'];
    mysqli_query($conn, "UPDATE `orders` SET chef_status = 'declined', order_status = '--' WHERE id = '$order_id' AND chef_id = '$chef_id'") or die('query failed');
    header('location:chef_orders.php');
}

if(isset($_POST['update_food_state'])){
    $order_id = $_POST['order_id'];
    $food_preparation_state = $_POST['food_preparation_state'];
    mysqli_query($conn, "UPDATE `orders` SET chef_status = '$food_preparation_state' WHERE id = '$order_id' AND chef_id = '$chef_id'") or die('query failed');
    header('location:chef_orders.php');
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

   <!-- Custom admin CSS file link -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
       .orders {
           text-align: center;
           margin-top: 10px;
       }

       .table-container {
           overflow-x: auto;
       }

       table {
           border-collapse: collapse;
           width: 100%;
       }

       th, td {
           border: 1px solid #ddd;
           padding: 12px 15px;
       }

       th {
           background-color: #f2f2f2;
           color: #8e44ad;
           font-weight: bold;
           font-size: 16px;
       }

       tr:hover {
           background-color: #f5f5f5;
       }

       td {
           font-size: 16px;
       }

       .btn {
           padding: 8px 16px;
           background-color: #8e44ad;
           color: #fff;
           text-decoration: none;
           border-radius: 5px;
           font-size: 14px;
           margin-right: 5px;
       }

       .accept {
           background-color: green;
       }

       .decline {
           background-color: red;
       }

       .update {
           background-color: orange;
       }

       select {
           padding: 8px 12px;
           font-size: 14px;
       }

   </style>
</head>
<body>
   
<?php include 'chef_header.php'; ?>

<section class="orders">
    <div class="table-container">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Placed On</th>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead> 
            <tbody>
                <?php

                $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE chef_id = '$chef_id' AND chef_status != 'ready' AND chef_status != 'declined'") or die('query failed');
                if(mysqli_num_rows($select_orders) > 0){
                    while($fetch_orders = mysqli_fetch_assoc($select_orders)){
                ?>
                <tr>
                    
                    <td><?php echo $fetch_orders['user_id']; ?></td>
                    <td><?php echo $fetch_orders['placed_on']; ?></td>
                    <td><?php echo $fetch_orders['name']; ?></td>
                    <td><?php echo $fetch_orders['number']; ?></td>
                    <td><?php echo $fetch_orders['email']; ?></td>
                    <td><?php echo $fetch_orders['address']; ?></td>
                    <td><?php echo $fetch_orders['total_products']; ?></td>
                    <td><?php echo $fetch_orders['total_price']; ?>/-</td>
                    <td>
                        <?php if ($fetch_orders['chef_status'] == 'pending'): ?>
                            <a href="chef_orders.php?accept=<?php echo $fetch_orders['id']; ?>" class="btn accept">Accept</a>
                            <a href="chef_orders.php?decline=<?php echo $fetch_orders['id']; ?>" class="btn decline">Decline</a>
                        <?php elseif ($fetch_orders['chef_status'] == 'accepted' || $fetch_orders['chef_status'] == 'preparing' || $fetch_orders['chef_status'] == 'ready'): ?>
                            <form action="chef_orders.php" method="post">
                                <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                                <select name="food_preparation_state">
                                    <option value="" selected disabled><?php echo $fetch_orders['chef_status']; ?></option>
                                    <option value="preparing">Preparing</option>
                                    <option value="ready">Ready</option>
                                </select>
                                <input type="submit" value="Update" name="update_food_state" class="btn update">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="9">No orders placed yet!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Custom JS file link -->
<script src="js/admin_script.js"></script>


</body>
</html>
