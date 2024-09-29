<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

// if(isset($_GET['delete'])){
//    $delete_id = $_GET['delete'];
//    mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
//    header('location:admin_contacts.php');
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom admin CSS file link -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>

        .container {
            padding: 12px 15px;
            text-align: center;
            margin-top: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
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

   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<div class="container">

    <h1 class="title">View applications</h1>

   <div class="btn-group">
      <button class="btn" onclick="showChefs()">View for Chefs</button>
      <button class="btn" onclick="showRiders()">View for Riders</button>
   </div>
   <div id="chefTable" class="table-container" style="display: none;">

      <table>
         <thead>
            <tr>
               <th>ID</th>
               <th>Name</th>
               <th>Mobile</th>
               <th>Email</th>
               <th>Address</th>
               <th>Specialization</th>
               <th>Experience (Years)</th>
            </tr>
         </thead>
         <tbody>
            <?php
            // Fetch and display chef details from the chef table
            $select_chefs = mysqli_query($conn, "SELECT * FROM `chefs`") or die(mysqli_error($conn)); // Add error checking
            if(mysqli_num_rows($select_chefs) > 0) {
               while($row = mysqli_fetch_assoc($select_chefs)){
                  echo "<tr>";
                  echo "<td>{$row['id']}</td>";
                  echo "<td>{$row['name']}</td>";
                  echo "<td>{$row['mobile']}</td>";
                  echo "<td>{$row['email']}</td>";
                  echo "<td>{$row['address']}</td>";
                  echo "<td>{$row['specialization']}</td>";
                  echo "<td>{$row['experience']}</td>";
                  echo "</tr>";
               }
            } else {
               echo "<tr><td colspan='7'>No chefs found</td></tr>";
            }
            ?>
         </tbody>
      </table>
   </div>
   <div id="riderTable" style="display: none;">
     
      <table>
         <thead>
            <tr>
               <th>ID</th>
               <th>Name</th>
               <th>Mobile</th>
               <th>Email</th>
               <th>Address</th>
               <th>Vehicle</th>
            </tr>
         </thead>
         <tbody>
            <?php
            // Fetch and display rider details from the rider table
            $select_riders = mysqli_query($conn, "SELECT * FROM `riders`") or die(mysqli_error($conn)); // Add error checking
            if(mysqli_num_rows($select_riders) > 0) {
               while($row = mysqli_fetch_assoc($select_riders)){
                  echo "<tr>";
                  echo "<td>{$row['id']}</td>";
                  echo "<td>{$row['name']}</td>";
                  echo "<td>{$row['mobile']}</td>";
                  echo "<td>{$row['email']}</td>";
                  echo "<td>{$row['address']}</td>";
                  echo "<td>{$row['vehicle']}</td>";
                  echo "</tr>";
               }
            } else {
               echo "<tr><td colspan='6'>No riders found</td></tr>";
            }
            ?>
         </tbody>
      </table>
   </div>
</div>

<!-- Custom admin JS file link -->
<script src="js/admin_script.js"></script>
<script>
   function showChefs() {
      document.getElementById("chefTable").style.display = "block";
      document.getElementById("riderTable").style.display = "none";
   }

   function showRiders() {
      document.getElementById("chefTable").style.display = "none";
      document.getElementById("riderTable").style.display = "block";
   }
</script>

</body>
</html>
