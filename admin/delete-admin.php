<?php
  session_start();
  include ('../includes/db.php');

  $id = $_GET['id'];

  $sql= "DELETE FROM admin WHERE id=$id";

  $res=mysqli_query($con,$sql);

  if($res==TRUE)
  {
     //echo "ADMIN DELETED";

     $_SESSION['delete']= "ADMIN DELETED SUCCESSFULLY";
     
     header('location: index.php');
  }
  else{

    $_SESSION['delete']="FAILED TO DELETE";
   
    header('location: index.php');
  }
?>