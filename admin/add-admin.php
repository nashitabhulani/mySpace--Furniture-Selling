<?php 
session_start();
include('../includes/db.php');
include ('verifySession.php');
include ('header.php'); ?>


<div class="wrapper">
       <div class="main-content">
      
            <h1>ADD ADMIN </h1>
            <br/>

            <?php
                if(isset($_SESSION['add']))
                {
                  echo $_SESSION['add'];
                  unset ($_SESSION['add']);
                }
             ?>
             <br/>
            <div class="form">
            <form action="" method="POST">
               <table class="table2">
                    <tr>
                        <td>Full name: </td>
                        <td><input type="text" name="fullname" placeholder="enter your name here"></td>
                        
                    </tr>
                
                    <tr>
                        <td>Username: </td>
                        <td><input type="text" name="username" placeholder="enter your username here"></td>
                        
                    </tr>

                    <tr>
                        <td>Password: </td>
                        <td><input type="password" name="password" placeholder="enter your password here"></td>
                        
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit"name="submit" value="ADD ADMIN" class="button1">
                             
                        </td>   
                    </tr>
                
                </table>


            </form> 
</div>
        </div>
</div>

</body>
</html>

<?php 
       //checking the submit button
       if(isset($_POST['submit']))
       {
        //echo"Button clicked";
        //get data
        $fullname=$_POST['fullname'];
        $username=$_POST['username'];
        $password=md5($_POST['password']);

        //save into database

        $sql="INSERT INTO admin SET
          fullname='$fullname',
          username='$username',
          password='$password'
        ";

        
        $res= mysqli_query($con,$sql) or die(mysqli_error());

        //session variable

        if($res==TRUE){
            $_SESSION['add'] = "admin added successfully";

            header("location: index.php");
         }
         else{
            $_SESSION['add'] = "FAILED TO ADD ADMIN";

            header("location:  add-admin.php");
         }


       }
       

?>