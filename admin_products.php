<?php
include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
}

// Add product
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $category_names = implode(', ', $_POST['category_names']); 

    $add_product_query = mysqli_query($conn, "INSERT INTO `products` (name, price, image, category_names) VALUES ('$name', '$price', '$image', '$category_names')") or die('query failed');

    if ($add_product_query) {
        move_uploaded_file($image_tmp_name, $image_folder);
        $message[] = 'Product added successfully!';
    } else {
        $message[] = 'Product could not be added!';
    }
}

// Delete product
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_products.php');
}

// Update product
if (isset($_POST['update_product'])) {
    
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];
    $update_old_image = $_POST['update_old_image'];
    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_category_names = implode(', ', $_POST['update_category_names']); 
    
    // Update product query
    if (!empty($update_image)) {
        // If a new image is uploaded
        $update_folder = 'uploaded_img/' . $update_image;
        $update_product_query = mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', image = '$update_image', category_names = '$update_category_names' WHERE id = '$update_p_id' AND chef_id = '$chef_id'") or die('query failed');

        if ($update_product_query) {
            // If update query is successful
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('uploaded_img/' . $update_old_image); // Remove old image
            header('location:admin_products.php');
        } else {
            // If update query fails
            $message[] = 'Product could not be updated!';
        }
    } else {
        // If no new image is uploaded
        $update_product_query = mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', category_names = '$update_category_names' WHERE id = '$update_p_id' AND chef_id = '$chef_id'") or die('query failed');
        
        if ($update_product_query) {
            // If update query is successful
            header('location:admin_products.php');
        } else {
            // If update query fails
            $message[] = 'Product could not be updated!';
        }
    }
}

// Add category
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $category_image = $_FILES['category_image']['name'];
    $category_image_tmp_name = $_FILES['category_image']['tmp_name'];
    $category_image_folder = 'uploaded_img/' . $category_image;

    $add_category_query = mysqli_query($conn, "INSERT INTO `categories` (category_names, image) VALUES ('$category_name', '$category_image')") or die('query failed');

    if ($add_category_query) {
        move_uploaded_file($category_image_tmp_name, $category_image_folder);
        $message[] = 'Category added successfully!';
    } else {
        $message[] = 'Category could not be added!';
    }
}


// Handle category deletion
if (isset($_POST['delete_category'])) {
    $category_name = $_POST['category_name'];
    
    // Fetch the image associated with the category
    $fetch_image_query = mysqli_query($conn, "SELECT image FROM `categories` WHERE category_names = '$category_name'");
    $fetch_image = mysqli_fetch_assoc($fetch_image_query);
    $image_path = 'uploaded_img/' . $fetch_image['image'];
    
    // Delete the image file
    unlink($image_path);
    
    // Delete the category from the database
    $delete_category_query = mysqli_query($conn, "DELETE FROM `categories` WHERE category_names = '$category_name'") or die('query failed');
    
    // Redirect back to the admin products page
    header('Location: admin_products.php');
    exit;
}


// Fetch categories from the database
$select_categories = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
$categories = mysqli_fetch_all($select_categories, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- custom admin css file link -->
    <link rel="stylesheet" href="css/admin_style.css">
    <style>

        .categories {
            overflow-x: auto;
            white-space: nowrap;
        }

        .category-box {
            display: inline-block;
            margin-right: 20px;
            text-align: center;
        }

        .category-box img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .categories {
            overflow-x: auto;
            white-space: nowrap;
        }

        .category-box {
            display: inline-block;
            margin-right: 20px;
            text-align: center;
        }

        .category-box img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover; 
        }

        .show-products .box img {
            max-width: 100%; 
            height: auto; 
            display: block; 
            margin: 0 auto 10px;
        }

        .add-category form {
            display: flex;
            align-items: center;
        }

        .add-category .box {
            margin-right: 15px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .chef{
            font-size: 1.4rem;
            color: #8e44ad;
        }

        .name {
            font-size: 1.4rem;
            color: black;
        }
    </style>
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <!-- Categories section -->
    <section class="add-category">
        <h1 class="title">Add Category</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="category_name" class="box" placeholder="Enter category name" required>
            <input type="file" name="category_image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" value="Add Category" name="add_category" class="btn">
        </form>
    </section>

    <!-- Categories section -->
<section class="categories">
    <h1 class="title">Categories</h1>
    <div class="box-container">
        <?php foreach ($categories as $category): ?>
        <div class="category-box">
            <?php if (isset($category['image'])): ?>
            <img class="image" src="uploaded_img/<?php echo $category['image']; ?>"
                alt="<?php echo isset($category['name']) ? $category['name'] : ''; ?>">
            <?php endif; ?>
            <div class="name"><?php echo isset($category['category_names']) ? $category['category_names'] : ''; ?>
            </div>
            <form action="" method="post">
                <input type="hidden" name="category_name" value="<?php echo $category['category_names']; ?>">
                <input type="submit" value="Delete" name="delete_category" class="btn delete-btn"
                    onclick="return confirm('Are you sure you want to delete this category?');">
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</section> <br> <br> <br>

    <!-- show products -->
    <section class="show-products">
        <div class="box-container">
            <?php
           $select_products = mysqli_query($conn, "SELECT products.*, users.name AS chef_name FROM `products` INNER JOIN `users` ON products.chef_id = users.id") or die('Query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
            <div class="box">
                
                <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                <div class="name"><?php echo $fetch_products['name']; ?></div>
                <div class="price"><?php echo $fetch_products['price']; ?>/-</div>
                <div class="chef">Chef: <?php echo $fetch_products['chef_name']; ?></div>
                <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">Update</a>
                <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn"
                    onclick="return confirm('Delete this product?');">Delete </a>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
        </div>
    </section>

    <!-- Edit product form -->
    <section class="edit-product-form">
        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                $fetch_update = mysqli_fetch_assoc($update_query);
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
            <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
            <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required
                placeholder="Enter product name">
            <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box"
                required placeholder ="Enter product price">
            <select name="update_category_names[]" class="box" multiple required>
                <?php foreach ($categories as $category): ?>
                <?php $selected = in_array($category['category_names'], explode(', ', $fetch_update['category_names'])) ? 'selected' : ''; ?>
                <option value="<?php echo $category['category_names']; ?>" <?php echo $selected; ?>><?php echo $category['category_names']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
            <input type="submit" value="Update" name="update_product" class="btn">
            <input type="reset" value="Cancel" id="close-update" class="option-btn">
        </form>
        <?php
            }
        } else {
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
        }
        ?>
    </section>

    <!-- custom admin js file link -->
    <script src="js/admin_script.js"></script>
</body>
