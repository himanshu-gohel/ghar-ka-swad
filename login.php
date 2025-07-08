<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, string: md5($_POST['password']));
   $user_type = $_POST['user_type']; 

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass' AND user_type = '$user_type'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){

      $row = mysqli_fetch_assoc($select_users);

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      } elseif($row['user_type'] == 'user'){

         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');

      } elseif($row['user_type'] == 'chef'){ 
         $_SESSION['chef_name'] = $row['name'];
         $_SESSION['chef_email'] = $row['email'];
         $_SESSION['chef_id'] = $row['id'];
         header('location:chef_page.php'); 
      }
         elseif($user_type == 'rider') {
            $_SESSION['rider_name'] = $row['name'];
            $_SESSION['rider_email'] = $row['email'];
            $_SESSION['rider_id'] = $row['id'];
            header('location:rider_page.php');
      }
   }else{
      $message[] = 'Incorrect email, password, or user type!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      p {
         margin-top: -10px;
      }
   </style>

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
      <h3>Login Now</h3>
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <input type="password" name="password" placeholder="Enter your password" required class="box">
      <select name="user_type" class="box" required>
         <option value="">Select User Type</option>
         <option value="user">User</option>
         <option value="admin">Admin</option>
         <option value="chef">Chef</option> 
         <option value="rider">Rider</option> 
      </select>
      <!-- <p align="left"><a href="forgot_password.php">Forgot Password ?</a></p>  -->
      <input type="submit" name="submit" value="Login Now" class="btn"> <p>
      <p>Don't have an account? <a href="register.php">Register Now</a></p> 
   </form>

</div>

</body>
</html>
