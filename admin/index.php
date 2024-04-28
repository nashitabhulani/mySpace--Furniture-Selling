<?php
session_start();
include '../includes/db.php';
include ('verifySession.php');
include ('header.php');


// Fetch admin details
$adminQuery = $con->prepare("SELECT fullname FROM admin WHERE id = ?");
$adminQuery->bind_param("i", $id); // Use $id instead of $admin_id
$adminQuery->execute();
$result = $adminQuery->get_result();
$admin = $result->fetch_assoc();
?>

<div class="wrapper">
  <div class="main-content">

    <h1 class="heading">ADMIN PANEL</h1>
    <?php
    if (isset($_SESSION['add'])) {
      echo $_SESSION['add'];
      unset($_SESSION['add']);
    }

    if (isset($_SESSION['delete'])) {
      echo $_SESSION['delete'];
      unset($_SESSION['delete']);
    }
    ?>
    <br />
    <a href="add-admin.php" class="add">ADD ADMIN</a>

    <br /><br /> <br />
    <table class="full">
      <tr>
        <th>Serial no.</th>
        <th>Full name</th>
        <th>User Name</th>
        <th>Actions</th>
      </tr>
      <?php

      $sql = "SELECT * FROM admin";
      $res = mysqli_query($con, $sql);

      if ($res == TRUE) {
        $count = mysqli_num_rows($res);

        if ($count > 0) {
          while ($rows = mysqli_fetch_assoc($res)) {
            $id = $rows['id'];
            $fullname = $rows['fullname'];
            $username = $rows['username'];
            ?>

            <tr>
              <td><?php echo $id ?></td>
              <td><?php echo $fullname ?></td>
              <td><?php echo $username ?></td>
              <td>
                <!-- <a href="<?php echo "../admin/"; ?>change-password.php?id=<?php echo $id; ?>" class="btn">Change Password</a>  -->
                <a href="<?php echo "../admin/"; ?>delete-admin.php?id=<?php echo $id; ?>" class="btn">DELETE ADMIN</a>

              </td>
            </tr>
            <?php

          }
        } else {

        }
      }
      ?>
    </table>
  </div>
</div>
</body>

</html>