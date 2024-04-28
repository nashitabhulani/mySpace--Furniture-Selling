<?php
session_start();
include '../includes/db.php'; 

include ('verifySession.php');
include ('header.php');


$sql = "SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.created_at DESC";
$result = mysqli_query($con, $sql);
?>


<div class="wrapper">
       <div class="main-content">
    <h2>Orders</h2>
   
    <table class="full">
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Phone</th>
            <th>Email</th>
            <th >Address</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Ordered At</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                <td>".$row['order_id']."</td>
                <td>".$row['username']."</td>
             
                <td>".htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8')."</td>
                <td>".htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8')."</td>
                <td>".htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8')."</td>
                <td>".$row['total_price']."</td>
                <td>
                    <form action='update_order_status.php' method='post'>
                        <input type='hidden' name='order_id' value='".$row['order_id']."'>
                        <select name='order_status' onchange='this.form.submit()'>
                            <option value='in Process'".($row['order_status'] == 'in Process' ? ' selected' : '').">In Process</option>
                            <option value='delivered'".($row['order_status'] == 'delivered' ? ' selected' : '').">Delivered</option>
                            <option value='cancelled'".($row['order_status'] == 'cancelled' ? ' selected' : '').">Cancelled</option>
                        </select>
                      
                    </form>
                </td>
                <td>".$row['created_at']."</td>
            </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No orders found</td></tr>";
        }
        ?>
    </table>
    </div>
    </div>

<!-- </div> -->

</body>
</html>
