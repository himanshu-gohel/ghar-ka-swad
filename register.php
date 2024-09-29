<?php
include 'config.php';


$name = '';
$email = '';
$mobile = '';
$address = '';

if(isset($_POST['submit'])){
    // Capture form field values
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $hashed_pass = md5($cpass);
    $user_type = $_POST['user_type'];
    $mobile = $_POST['mobile'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Validation: Check if email and mobile number are already registered
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' OR mobile = '$mobile'") or die('query failed');
    if(mysqli_num_rows($select_users) > 0){
        $message[] = 'Email or mobile number already registered!';
    }else{
        // Validation: Check mobile number length
        if(!preg_match('/^\d{10}$/', $mobile)){
            $message[] = 'Mobile number should be exactly 10 digits long and contain only digits!';
        }
        // Validation: Check password length
        if(strlen($pass) < 6 || strlen($pass) > 12){
            $message[] = 'Password should be between 6 to 12 characters long.';
        }
        // Validation: Check for at least one uppercase letter
        if(!preg_match('/[A-Z]/', $pass)){
            $message[] = 'Password should contain at least one uppercase letter.';
        }
        // Validation: Check for at least one lowercase letter
        if(!preg_match('/[a-z]/', $pass)){
            $message[] = 'Password should contain at least one lowercase letter.';
        }
        // Validation: Check for at least one special character
        if(!preg_match('/[@$!%*#?&]/', $pass)){
            $message[] = 'Password should contain at least one special character.';
        }
        // Proceed with registration if all validations pass
        if(empty($message)){
            if($pass != $cpass){
                $message[] = 'Confirm password does not match!';
            }else{
                mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type, mobile, address) VALUES('$name', '$email', '$hashed_pass', '$user_type', '$mobile', '$address')") or die('query failed');
                $message[] = 'Registered successfully!';
                header('location:login.php');
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
   <title>Register</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>Register Now</h3>
      <input type="text" name="name" placeholder="Enter your name" required class="box" value="<?php echo $name; ?>">
      <input type="number" name="mobile" placeholder="Enter your mobile number" required class="box" value="<?php echo $mobile; ?>">
      <input type="email" name="email" placeholder="Enter your email" required class="box" value="<?php echo $email; ?>">
      <input type="text" name="address" placeholder="Enter your full address" required class="box" value="<?php echo $address; ?>">   
      <input type="password" name="password" placeholder="Enter your password" required class="box">
      <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">
      <select name="user_type" class="box">
         <option value="user">User</option>
         <!-- <option value="admin">Admin</option> -->
         <option value="chef">Chef</option>
         <option value="rider">Rider</option>
      </select>
      <input type="submit" name="submit" value="Register Now" class="btn">
      <p>Already have an account? <a href="login.php">Login Now</a></p>
   </form>

</div>

</body>
</html>
