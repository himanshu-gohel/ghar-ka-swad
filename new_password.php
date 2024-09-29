<?php
include ('config.php');
session_start();
$msg = "";

if (isset($_POST['submit'])) {
   $new_password = $_POST['new_password'];
   $con_password = $_POST['con_password'];

   // Validate passwords
   if (validatePassword($new_password, $con_password)) {
      // Hash the new password
      $hashed_password = md5($new_password); 

      // Get the email of the logged-in user from the session
      $email = $_SESSION['email'];

      // Prepare and execute the update query
      $updt_sqr = "UPDATE users SET password ='$hashed_password' WHERE email = '$email'";
      $result = mysqli_query($conn, $updt_sqr);

      if ($result) {
         $msg = "Password changed Successfully!";
         header('location: login.php');
         exit(); 
      } else {
         $msg = "Failed to update password.";
      }
   }
}

// Function to validate passwords
function validatePassword($new_password, $con_password) {
   global $msg;

   // Password must be between 6 and 12 characters
   if (strlen($new_password) < 6 || strlen($new_password) > 12) {
      $msg = "Password must be between 6 and 12 characters long!";
      return false;
   }

   // Password must contain at least one uppercase letter
   if (!preg_match("/[A-Z]/", $new_password)) {
      $msg = "Password must contain at least one uppercase letter!";
      return false;
   }

   // Password must contain at least one lowercase letter
   if (!preg_match("/[a-z]/", $new_password)) {
      $msg = "Password must contain at least one lowercase letter!";
      return false;
   }

   // Password must contain at least one digit
   if (!preg_match("/\d/", $new_password)) {
      $msg = "Password must contain at least one digit!";
      return false;
   }

   // Password must contain at least one special character
   if (!preg_match("/[^a-zA-Z0-9]/", $new_password)) {
      $msg = "Password must contain at least one special character!";
      return false;
   }

   // Check if passwords match
   if ($new_password !== $con_password) {
      $msg = "New password does not match with confirm password!";
      return false;
   }

   // All validations passed
   return true;
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
                     <h2 class="text-center">Enter New Password</h2>
                     <p style="color: red"><?php echo $msg; ?> </p>
                     <div class="panel-body">
                        <form method="post" id="register-form" role="form" autocomplete="off" class="form">
                           <div class="form-group">
                              <div class="input-group">
                                 <span class="input-group-addon"><i
                                       class="glyphicon glyphicon-user color-blue"></i></span>
                                 <input id="new_password" name="new_password" placeholder="Enter new password"
                                    class="form-control" type="password" required="">
                              </div>
                           </div>

                           <div class="form-group">
                              <div class="input-group">
                                 <span class="input-group-addon"><i
                                       class="glyphicon glyphicon-user color-blue"></i></span>
                                 <input id="con_password" name="con_password" placeholder="Enter Confirm password"
                                    class="form-control" type="password" required="">
                              </div>
                           </div>

                           <div class="form-group">
                              <input type="submit" name="submit" class="btn" value="Submit">
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