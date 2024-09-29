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

<header class="header">

   <div class="flex">

      <a href="chef_page.php" class="logo">Chef<span>Panel</span></a>

      <nav class="navbar">
         <a href="chef_page.php">home</a>
         <a href="chef_products.php">products</a>
         <a href="chef_orders.php">pending orders</a>
         <a href="chef_completed.php">completed orders</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>
 
      <div class="account-box">
         <p>username : <span><?php echo $_SESSION['chef_name']; ?></span></p>
         <p>email : <span><?php echo $_SESSION['chef_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
         <div>new <a href="login.php">login</a> | <a href="register.php">register</a></div>
      </div>

   </div>

</header>