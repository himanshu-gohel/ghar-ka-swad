<?php
include 'config.php'; 
session_start();
$msg = "";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $_SESSION['email'] = $email;
    $rand = rand(100000, 999999); 

   
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the user data
        $user_data = mysqli_fetch_assoc($result);
        $select_email = $user_data['email'];

        // Send email with OTP
        $to = $email;
        $subject = "Verification Code";
        $body = "Hi,\n\nThis is your verification code: $rand";
        $header = "From: himanshugohel07@gmail.com";

        if (mail($to, $subject, $body, $header)) {
            $_SESSION['otp'] = $rand;
            header('location: otp.php');
            exit();
        } else {
            $msg = "OTP Sending Fail";
        }
    } else {
        $msg = "Please enter a valid email address";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }

        .form-gap {
            padding-top: 150px;
        }

        .btn:hover {
            color: white;
        }
    </style>
</head>

<body>
    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Forgot Password?</h2>
                            <p style="color: red"><?php echo $msg; ?> </p>
                            <div class="panel-body">
                                <form method="post"> 
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="email" name="email" placeholder="Enter your email address"
                                                class="form-control" type="email" required="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="submit" class="btn" value="Request OTP">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>