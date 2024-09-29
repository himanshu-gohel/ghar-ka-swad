<?php
include ('config.php');
session_start();
$msg = "";

$otp = $_SESSION['otp'];
if (isset($_POST['submit'])) {
    $submit_otp = $_POST['otp'];
    if ($submit_otp == $otp) {
        header('location:new_password.php');
    } else {
        $msg = "Please Enter Valid OTP";
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
            padding-top: 120px;
        }

        .btn:hover {
            color: white;
        }

        .option-btn:hover {
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
                            <h2 class="text-center">Check your email for the OTP</h2>
                            <p style="color: red"><?php echo $msg; ?> </p>
                            <div class="panel-body">

                                <form id="register-form" action="" role="form" autocomplete="off" class="form"
                                    method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="otp" name="otp" placeholder="Enter OTP" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="submit" class="btn"value="submit">
                                    </div>
                                    <a href="login.php" class="option-btn">Cancel </a>


                                    <input type="hidden" class="hide" name="token" id="token" value="">
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