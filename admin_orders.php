<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
        .orders {
            text-align: center;
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
            border-bottom: 1px solid #ddd;
            text-align: left;
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

        .chef {
            font-size: 1.4rem;
            color: #f39c12;
         }
    </style>

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">
   <h1 class="title">Placed Orders</h1>
   <div class="table-container">
      <table>
         <thead>
            <tr>
               <th>User ID</th>
               <th>Placed On</th>
               <th>User Name</th>
               <th>Number</th>
               <th>Email</th>
               <th>Address</th>
               <th>Total Products</th>
               <th>Total Price</th>
               <th>Payment Method</th>
               <th>Chef Name</th>
               <th>Preparation Status</th>
               <th>Rider Status</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            if(mysqli_num_rows($select_orders) > 0){
               while($fetch_orders = mysqli_fetch_assoc($select_orders)){
                  // Fetch chef's name based on chef_id from users table
                  $chef_id = $fetch_orders['chef_id'];
                  $select_chef = mysqli_query($conn, "SELECT name FROM `users` WHERE id = '$chef_id'");
                  $chef_data = mysqli_fetch_assoc($select_chef);
                  $chef_name = $chef_data ? $chef_data['name'] : 'Unknown Chef'; // Use a default name if chef not found
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
               <td><?php echo $fetch_orders['method']; ?></td>
               <td class="chef"><?php echo $chef_name; ?></td>
               <td><?php echo $fetch_orders['chef_status']; ?></td>
               <td><?php echo $fetch_orders['order_status']; ?></td>
            </tr>
            <?php
               }
            } else {
               echo '<tr><td colspan="11">No orders placed yet!</td></tr>';
            }
            ?>
         </tbody>
      </table>
   </div>
</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>
