<?php 
session_start();
include('../includes/db.php');
include ('verifySession.php');
include ('header.php');
?>

<div class="wrapper">
       <div class="main-content">
      
            <h1>Manage products</h1>
            <br/><br/> 
            <?php
            if(isset($_SESSION['create']))
         {
            echo $_SESSION['create'];
            unset($_SESSION['create']);

         }
         if(isset($_SESSION['dump']))
         {
            echo $_SESSION['dump'];
            unset($_SESSION['cdump']);

         }

         ?>
            <a href="add-products.php" class="add">Add products</a>

            <br/><br/> <br/>
            <table class="full">
               <tr>
               <th>Serial no.</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>3d model</th>
                <th>Category</th>
                <th>Actions</th>
               </tr>
               <?php
               $sql="SELECT * from products";

               $res= mysqli_query($con,$sql);

                 if($res==TRUE)
                 {
                    $count=mysqli_num_rows($res);

                    if($count>0)
                    {
                      while($rows=mysqli_fetch_assoc($res))
                      {
                        $id=$rows['id'];
                        $name = $rows['name'];
                        $description = nl2br($rows['description']);
                        $price = $rows['price'];
                        $image = $rows['image'];
                        $model_name=$rows['model_name'];
                     $category = $rows['category_id'];


                         ?>
               <tr>
                    <td><?php echo $id ?></td>
                    <td> <?php echo $name ?></td>
                    <td><?php echo $description ?></td>
                    <td><?php echo $price ?></td> 
                    <!-- <td><?php echo $image ?></td> -->
                    <td><img src="../project_root/assets/images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($name); ?>" style="width: 100px;"></td>
<td><?php echo $model_name ?></td>
                    <td><?php echo $category ?></td>
                    <td>
                    
                    <a href="delete-product.php?id=<?php echo $row['id']; ?>" class="btn">Delete Product</a>
                    
                    </td>
                </tr>
                <?php 
   }
}
else
{

}
}
                ?>
           </table>
       </div>
</div>
</body>
</html>