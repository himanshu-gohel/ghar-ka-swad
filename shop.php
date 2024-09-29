<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if(isset($_POST['add_to_cart'])){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
 
    // Fetch the chef ID of the product
    $chef_id_query = mysqli_query($conn, "SELECT chef_id FROM `products` WHERE name = '$product_name' LIMIT 1");
    $chef_id_result = mysqli_fetch_assoc($chef_id_query);
    $chef_id = $chef_id_result['chef_id'];

    // Check if the cart is empty or has products from the same chef
    $cart_check_query = mysqli_query($conn, "SELECT chef_id FROM `cart` WHERE user_id = '$user_id' LIMIT 1");
    $cart_check_result = mysqli_fetch_assoc($cart_check_query);
    
    if(mysqli_num_rows($cart_check_query) == 0 || $cart_check_result['chef_id'] == $chef_id) {
        // If cart is empty or has products from the same chef, allow adding to cart
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image, chef_id) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image', '$chef_id')") or die('query failed');
        $message[] = 'Product added to cart!';
    } else {
        // If cart has products from a different chef, display an error message
        $message[] = 'You can only add products from the same chef at a time!';
    }
}

// Retrieve categories from the database
$select_categories = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
$categories = mysqli_fetch_all($select_categories, MYSQLI_ASSOC);
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
 
    .categories {
        margin-top: 20px;
    }

    .categories .box-container {
        display: flex;
        justify-content: center; 
        flex-wrap: wrap;
    }

    .categories .box {
        flex: 0 0 calc(15% - 20px); 
        margin-bottom: 20px;
        text-align: center;
    }

    .categories .box .image {
        width: 120px; 
        height: 120px; 
        border-radius: 50%;
        margin: 0 auto 10px;
    }

    .categories .box .name {
        font-size: 14px; 
    }


    .products .box img {
        max-width: 100%; 
        height: auto; 
        display: block; 
        margin: 0 auto 10px; 
    }

    .chef {
        font-size: 1.2rem; 
        color: #8e44ad;
    }

    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="heading">
    <h3>Our Shop</h3>
    <p><a href="home.php">Home</a> / Shop</p>
</div>
<!-- Section for displaying categories -->
<section class="categories">
    <h1 class="title">Categories</h1>
    <div class="box-container">
        <?php 
        // Add an image for "All Items" category
        echo '<form action="" method="post" class="box">';
        echo '<input type="hidden" name="category_names" value="All Items">';
        echo '<img class="image" src="uploaded_img/all food.jpeg" alt="All Items" name="view_category">';
        echo '<input type="submit" value="All Items" name="view_all_item" class="btn">';
        echo '</form>';

        // Fetch and display other categories
        foreach ($categories as $category): 
        ?>
            <form action="" method="post" class="box">
                <?php if (isset($category['image'])): ?>
                    <img class="image" src="uploaded_img/<?php echo $category['image']; ?>" alt="<?php echo isset($category['name']) ? $category['name'] : ''; ?>">
                <?php endif; ?> <br>
                <input type="hidden" name="category_names" value="<?php echo isset($category['category_names']) ? $category['category_names'] : ''; ?>"> 
                <input type="submit" value="<?php echo isset($category['category_names']) ? $category['category_names'] : ''; ?>" name="view_category" class="btn">
            </form>
        <?php endforeach; ?>
    </div>
</section>

<!-- Section for displaying results -->
<section class="products">
    <h1 class="title">Dishes</h1> 
    <div class="box-container">
        <?php
        if (isset($_POST['view_all_item'])) {
            // Display all products from all chefs
            $select_products = mysqli_query($conn, "SELECT products.*, users.name AS chef_name FROM `products` INNER JOIN `users` ON products.chef_id = users.id") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <form action="" method="post" class="box">
                        <?php if (isset($fetch_products['image'])): ?>
                            <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                        <?php endif; ?>
                        <div class="name"><?php echo isset($fetch_products['name']) ? $fetch_products['name'] : ''; ?></div>
                        <div class="chef">chef: <?php echo isset($fetch_products['chef_name']) ? $fetch_products['chef_name'] : ''; ?></div>
                        <div class="price"><?php echo isset($fetch_products['price']) ? $fetch_products['price'] : ''; ?>/-</div>
                        <input type="number" min="1" name="product_quantity" value="1" class="qty">
                        <input type="hidden" name="product_name"
                               value="<?php echo isset($fetch_products['name']) ? $fetch_products['name'] : ''; ?>">
                        <input type="hidden" name="product_price"
                               value="<?php echo isset($fetch_products['price']) ? $fetch_products['price'] : ''; ?>">
                        <input type="hidden" name="product_image"
                               value="<?php echo isset($fetch_products['image']) ? $fetch_products['image'] : ''; ?>">
                        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                    </form>
                <?php }
            } else {
                echo '<p class="empty">No products found!</p>';
            }
        } elseif (isset($_POST['view_category'])) {
            $category_name = $_POST['category_names']; 
            $select_products = mysqli_query($conn, "SELECT products.*, users.name AS chef_name FROM `products` INNER JOIN `users` ON products.chef_id = users.id WHERE products.category_names = '$category_name'") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <form action="" method="post" class="box">
                        <?php if (isset($fetch_products['image'])): ?>
                            <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                        <?php endif; ?>
                        <div class="name"><?php echo isset($fetch_products['name']) ? $fetch_products['name'] : ''; ?></div>
                        <div class="chef">chef: <?php echo isset($fetch_products['chef_name']) ? $fetch_products['chef_name'] : ''; ?></div>
                        <div class="price"><?php echo isset($fetch_products['price']) ? $fetch_products['price'] : ''; ?>/-</div>
                        <input type="number" min="1" name="product_quantity" value="1" class="qty">
                        <input type="hidden" name="product_name"
                               value="<?php echo isset($fetch_products['name']) ? $fetch_products['name'] : ''; ?>">
                        <input type="hidden" name="product_price"
                               value="<?php echo isset($fetch_products['price']) ? $fetch_products['price'] : ''; ?>">
                        <input type="hidden" name="product_image"
                               value="<?php echo isset($fetch_products['image']) ? $fetch_products['image'] : ''; ?>">
                        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                    </form>
                <?php }
            } else {
                echo '<p class="empty">No products found in this category!</p>';
            }
        } else {
            echo '<p class="empty">Select a category to view products.</p>';
        }
        ?>
    </div>
</section>


<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
