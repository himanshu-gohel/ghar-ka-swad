<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

$message = []; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $vehicle = $_POST['vehicle'];

    // Validate mobile number
    if (!isValidMobile($mobile)) {
        $message[] = 'Mobile number should be 10 digits';
    }

    // Validate email
    if (!isValidEmail($email)) {
        $message[] = 'Invalid email address format';
    }

    // Insert data into the riders table only if there are no validation errors
    if (empty($message)) {
        $sql = "INSERT INTO riders (name, mobile, email, address, vehicle) VALUES ('$name', '$mobile', '$email', '$address', '$vehicle')";
        if (mysqli_query($conn, $sql)) {
            $message[] = 'Registered successfully!';
        } else {
            $message[] = 'Could not register!';
        }
    }
}

function isValidMobile($number)
{
    // Check if the mobile number has 10 digits
    return preg_match('/^[0-9]{10}$/', $number);
}

function isValidEmail($email)
{
    // Check if the email address has a valid format
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .container {
            font-family: 'Rubik', sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        input[type="text"],
        input[type="tel"],
        input[type="email"],
        textarea,
        input[type="number"],
        button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #8e44ad;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            color: white;
            background-color: black;
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>Work With Us</h3>
        <p><a href="home.php">Home</a> / Careers</p>
    </div>

    <div class="container">
        <h1>Rider Registration Form</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="tel" id="mobile" name="mobile" value="<?php echo isset($_POST['mobile']) ? $_POST['mobile'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required><?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="vehicle">Vehicle:</label>
                <input type="text" id="vehicle" name="vehicle" value="<?php echo isset($_POST['vehicle']) ? $_POST['vehicle'] : ''; ?>" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Custom JS file link -->
    <script src="js/script.js"></script>

</body>

</html>
