<?php
include 'config.php';

session_start();

$chef_id = $_SESSION['chef_id'];

if (!isset($chef_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Completed Orders</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom admin CSS file link -->
   <link rel="stylesheet" href="css/admin_style.css">

   <!-- Custom CSS for table -->
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
           padding: 12px 15px;
           border: 1px solid #ddd;
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
        <?php
        // Select only orders with chef_status 'ready'
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE chef_id = '$chef_id' AND chef_status = 'ready'") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
        ?>
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
                </tr>
            </thead>
            <tbody>
                <?php
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
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <?php
        } else {
            echo '<h1>No orders completed yet</h1>';
        }
        ?>
    </div>
</section>

<!-- Custom JS file link -->
<script src="js/admin_script.js"></script>


</body>
</html>
