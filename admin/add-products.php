<?php
session_start();
 include '../includes/db.php';
 include ('verifySession.php');
 include ('header.php');

 $categoriesQuery = "SELECT id, name FROM category";
 $categoriesResult = mysqli_query($con, $categoriesQuery);
 $categories = [];
 if ($categoriesResult && mysqli_num_rows($categoriesResult) > 0) {
     while ($row = mysqli_fetch_assoc($categoriesResult)) {
         $categories[] = $row;
     }
 }
?>

<div class="wrapper">
       <div class="main-content">

      
       <h1>Add product</h1>
       <br><br>
       <?php
         if(isset($_SESSION['create']))
         {
            echo $_SESSION['create'];
            unset($_SESSION['create']);

         }
         if(isset($_SESSION['upload']))
         {
            echo $_SESSION['upload'];
            unset($_SESSION['upload']);

         }
         
       ?>
       <form action="" method="POST" enctype="multipart/form-data">

       <table class="full">
        <tr>
            <td>Name:</td>
            <td><input type="text" name="name" placeholder=""></td>
        </tr>
        <tr>
            <td>Description:</td>
            <td><textarea  cols="50" rows="10" name="description"  placeholder="Description..."></textarea>  ></textarea></td>
        </tr>
        <tr>
            <td>Price</td>
            <td><input type="text" name="price" placeholder="price.."></td>
        </tr>
        <tr>
    <td>Category:</td>
    <td>
        <select name="category_id">
            <option value="">Select a Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>
        <tr>
            <td>Image:</td>
            <td><input type="file" name="image"></td>
        </tr>
        <tr>
            <td>3D model:</td>
            <td><input type="file" name="model_name" id=""></td>
        </tr>
        <tr>
            <td colspan="2">
            <input type="submit"name="submit" value="ADD PRODUCT" class="button1">
           
        </tr>
</table>
            </div>
            </div>
</form>
<?php

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = (int)$_POST['price']; // Cast price as integer for database insertion
    $category_id = (int)$_POST['category_id'];

    $image_name = "";
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../project_root/assets/images/" . $image_name;
        if (!move_uploaded_file($source_path, $destination_path)) {
            $_SESSION['upload'] = "Failed to upload image";
            header("location: add-products.php");
            exit;
        }
    }

    $model_name = "";
    if (isset($_FILES['model_name']['name']) && $_FILES['model_name']['name'] != "") {
        $model_name = $_FILES['model_name']['name'];
        $model_source_path = $_FILES['model_name']['tmp_name'];
        $model_destination_path = "../project_root/assets/3d_models/" . $model_name;
        if (!move_uploaded_file($model_source_path, $model_destination_path)) {
            $_SESSION['upload'] = "Failed to upload 3D model.";
            header("location: add-products.php");
            exit;
        }
    }

    // Prepared statement to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO products (name, description, price, image, model_name, category_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissi", $name, $description, $price, $image_name, $model_name, $category_id);
    if ($stmt->execute()) {
        $_SESSION['create'] = "Product Added Successfully.";
        header("location:view-products.php");
    } else {
        $_SESSION['create'] = "Failed to Add Product: " . $stmt->error;
        header("location:add-products.php");
    }
    $stmt->close();
}
?>
</body>
</html>