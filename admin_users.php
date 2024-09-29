<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom admin CSS file link -->
    <link rel="stylesheet" href="css/admin_style.css">

    <style>
        .user-table {
            display: none; 
        }


        .btn-container {
            margin-bottom: 20px;
            text-align: center;
        }

      
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .user-table th,
        .user-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 16px;
        }

        .user-table th {
            background-color: #f2f2f2;
            color: #8e44ad;
        }

        .user-table tr:hover {
            background-color: #f5f5f5;
        }

        .delete-btn {
            padding: 5px 10px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 3px;
            text-decoration: none;
        }

        .btn {
            padding: 10px 20px;
            background-color: #8e44ad;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
        }

        .btn-container {
            margin-bottom: 20px;
            text-align: center;
        }

    </style>

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="users">

        <h1 class="title">User Accounts</h1>

        <!-- Button for fetching user data -->
        <div class="btn-container">
            <button class="btn" onclick="toggleUserData('user')">Show Users</button>
            <button class="btn" onclick="toggleUserData('admin')">Show Admins</button>
            <button class="btn" onclick="toggleUserData('chef')">Show Chefs</button>
            <button class="btn" onclick="toggleUserData('rider')">Show Riders</button>
        </div>

        <div id="user-data-container">
            <?php
            // Query to fetch users of all types
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            // Display each user type table
            $user_types = ['user', 'admin', 'chef', 'rider'];
            foreach ($user_types as $user_type) {
                echo '<table class="user-table" id="' . $user_type . 'Table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>User ID</th>';
                echo '<th>Username</th>';
                echo '<th>Email</th>';
                echo '<th>User Type</th>';
                echo '<th>Action</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                    if ($fetch_users['user_type'] === $user_type) {
                        echo '<tr>';
                        echo '<td>' . $fetch_users['id'] . '</td>';
                        echo '<td>' . $fetch_users['name'] . '</td>';
                        echo '<td>' . $fetch_users['email'] . '</td>';
                        echo '<td>' . $fetch_users['user_type'] . '</td>';
                        echo '<td><a href="admin_users.php?delete=' . $fetch_users['id'] . '" onclick="return confirm(\'Delete this user?\');" class="delete-btn">Delete User</a></td>';
                        echo '</tr>';
                    }
                }
                echo '</tbody>';
                echo '</table>';
                // Reset the internal pointer of the result set
                mysqli_data_seek($select_users, 0);
            }
            ?>
        </div>
    </section>

    <!-- Custom admin js file link -->
    <script src="js/admin_script.js"></script>

    <script>
        function toggleUserData(userType) {
            // Hide all user tables
            var tables = document.getElementsByClassName('user-table');
            for (var i = 0; i < tables.length; i++) {
                tables[i].style.display = 'none';
            }

            // Show the user table corresponding to the clicked button
            var userTable = document.getElementById(userType + 'Table');
            if (userTable) {
                userTable.style.display = 'table';
            }
        }
    </script>

</body>

</html>
