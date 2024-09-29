<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

function isValidMobile($number) {
    // Check if the mobile number has 10 digits
    return preg_match('/^[0-9]{10}$/', $number);
}

function isValidEmail($email) {
    // Check if the email address has a valid format
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function placeOrder($conn, $user_id, $chef_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on)
{
    $order_query = mysqli_query($conn, "INSERT INTO `orders`(user_id, chef_id, name, number, email, method, address, total_products, total_price, placed_on, order_status, chef_status) VALUES('$user_id', '$chef_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', 'pending', 'pending')") or die('query failed');

    if ($order_query) {
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        header('Location: orders.php');
    } else {
        return false;
    }
}

if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $flat = mysqli_real_escape_string($conn, $_POST['flat']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $pin_code = $_POST['pin_code'];
    $address = "$flat, $street, $city, $state, $country - $pin_code";
    $placed_on = date('d-M-Y');

    if (!isValidMobile($number)) {
        $message[] = 'Mobile number should be 10 digits';
    }

    if (!isValidEmail($email)) {
        $message[] = 'Invalid email address format';
    }

    if (empty($message)) {
        
        $cart_total = 0;
    $cart_products = array();
    $chef_id = '';

    $cart_query = mysqli_query($conn, "SELECT cart.*, products.chef_id FROM `cart` INNER JOIN `products` ON cart.name = products.name WHERE cart.user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
            $chef_id = $cart_item['chef_id'];
        }
    }

    $total_products = implode(', ', $cart_products);

    if (($method === 'online payment')) {
        // If payment method is 'online payment', show Razorpay payment popup
        ?>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
                    var user_id = <?php echo $user_id; ?>;
                    var amount = <?php echo $cart_total * 100; ?>;
                    var chef_id = '<?php echo $chef_id; ?>';

                    var options = {
                        "key": "rzp_test_XvGAe9S81PREwT",
                        "amount": amount,
                        "name": "Ghar ka Swad",
                        "image": "images/logo.jpg",
                        "handler": function(response) {
                            var paymentid = response.razorpay_payment_id;
                            $.ajax({
                                url: "payment-process.php",
                                type: "POST",
                                data: {
                                    user_id: user_id,
                                    name: '<?php echo $name; ?>',
                                    number: '<?php echo $number; ?>',
                                    email: '<?php echo $email; ?>',
                                    method: 'online payment',
                                    flat: '<?php echo $flat; ?>',
                                    street: '<?php echo $street; ?>',
                                    city: '<?php echo $city; ?>',
                                    state: '<?php echo $state; ?>',
                                    country: '<?php echo $country; ?>',
                                    pin_code: '<?php echo $pin_code; ?>',
                                    total_products: '<?php echo $total_products; ?>',
                                    placed_on: '<?php echo $placed_on; ?>',
                                    total_price: '<?php echo $cart_total; ?>',
                                    chef_id: chef_id,
                                    payment_id: paymentid // Pass the payment ID
                                },
                                success: function(finalresponse) {
                                    if (finalresponse == 'done') {
                                        alert('Order placed successfully!');
                                        window.location.href = "orders.php";
                                    } else {
                                        alert('Please check console.log to find error');
                                        console.log(finalresponse);
                                    }
                                }
                            })
                        },
                        "theme": {
                            "color": "#3399cc"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
        </script>
        <?php
    }  else {
      if (placeOrder($conn, $user_id, $chef_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on)) {
          $message[] = 'Order placed successfully!';
      } else {
          $message[] = 'Failed to place order!';
      }
  }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>Checkout</h3>
        <p><a href="home.php">Home</a> / Checkout</p>
    </div>

    <section class="display-order">
        <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                $grand_total += $total_price;
        ?>
                <p>
                    <?php echo $fetch_cart['name']; ?> <span>(
                        <?php echo $fetch_cart['price'] . '/-' . ' x ' . $fetch_cart['quantity']; ?>)
                    </span>
                </p>
        <?php
            }
        } else {
            echo '<p class="empty">Your cart is empty</p>';
        }
        ?>
        <div class="grand-total">Grand Total: <span>
                <?php echo $grand_total; ?>/-
            </span></div>
    </section>

    <section class="checkout">
    <form action="" method="post" id="orderForm">
        <h3>Place Your Order</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Your Name:</span>
                <input type="text" name="name" required placeholder="Enter your name">
            </div>
            <div class="inputBox">
                <span>Your Number:</span>
                <input type="tel" name="number" required placeholder="Enter your number">
            </div>
            <div class="inputBox">
                <span>Your Email:</span>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="inputBox">
                <span>Payment Method:</span>
                <select name="method" id="paymentMethod">
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="online payment">Online Payment</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Address Line 01:</span>
                <input type="text" name="flat" required placeholder="E.g. flat no.">
            </div>
            <div class="inputBox">
                <span>Address Line 02:</span>
                <input type="text" name="street" required placeholder="E.g. street name">
            </div>
            <div class="inputBox">
                <span>City:</span>
                <input type="text" name="city" required placeholder="E.g. Mumbai">
            </div>
            <div class="inputBox">
                <span>State:</span>
                <input type="text" name="state" required placeholder="E.g. Maharashtra">
            </div>
            <div class="inputBox">
                <span>Country:</span>
                <input type="text" name="country" required placeholder="E.g. India">
            </div>
            <div class="inputBox">
                <span>Pin Code:</span>
                <input type="number" min="0" name="pin_code" required placeholder="E.g. 123456">
            </div>
        </div>
        <input type="submit" value="Order Now" class="btn" name="order_btn">
    </form>
</section>

    <?php include 'footer.php'; ?>

    <!-- Custom JS file link -->
    <script src="js/script.js"></script>

</body>

</html>
