<?php
include 'config.php';

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];
    $flat = $_POST['flat'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $pin_code = $_POST['pin_code'];
    $total_products = $_POST['total_products'];
    $placed_on = $_POST['placed_on'];
    $total_price = $_POST['total_price'];
    $chef_id = $_POST['chef_id'];

    // Insert the data into the orders table
    $order_query = mysqli_query($conn, "INSERT INTO orders (user_id, chef_id, name, number, email, method, address, total_products, total_price, placed_on, order_status, chef_status)  VALUES ('$user_id', '$chef_id', '$name', '$number', '$email', '$method', '$flat, $street, $city, $state, $country - $pin_code', '$total_products', '$total_price', '$placed_on', 'pending', 'pending')")  or die ('Order insertion failed');
    $cart_query = mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die ('query failed');

    if ($order_query && $cart_query) {
        echo 'done';
    } else {
        echo 'failed';
    }
} else {
    // Invalid request method
    echo 'Invalid request method';
}
?>
