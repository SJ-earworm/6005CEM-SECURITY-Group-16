<?php
require("session_handling.php");
?>

<!DOCTYPE html>
<html lang="utf-8">

<head>
    <title>Purchase Report</title>
    <link rel="stylesheet" href="astyle.css">
</head>
<body>

<div class="sidebar">
    <img src="images/logo.png" width="160" height="100">
    <img src="images/profile.png" class="profile">
    <a href="admin.html">Dashboard</a>
    <a href="apselect.php">Product</a>
    <?php if ($_SESSION['role'] == 'admin') { ?>
    <a href="auser.php">Users</a>
	<?php } ?>
    <a href="areport.php">Statistic</a>
    <a href="logout.php">Logout</a>
</div>

<table>
    <tr>
        <th>Id No.</th>
        <th>Purchased By</th>
        <th>Email</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Product Id</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Date of Purchased</th>
    </tr>
    
<?php
include("Connectdb.php");

$query = "SELECT 
          payment.id, 
          payment.billName, 
          payment.email, 
          payment.address, 
          payment.city,
          payment.state, 
          payment.zip, 
          cart.pid,
          cart.quantity,
          cart.price,
          payment.datePay 
          FROM payment
          JOIN cart ON payment.id = cart.id";

$result = mysqli_query($con, $query);

// Check if the query was successful
if (!$result) {
    echo "Error executing query: " . mysqli_error($con);
} else {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["id"] .  "</td><td>" . $row["billName"] .  "</td><td>" . $row["email"] . "</td><td>" . $row["address"] . "</td><td>" . 
            $row["city"] . "</td><td>" . $row["state"] . "</td><td>" . $row["zip"] . "</td><td>" . $row["pid"] . "</td><td>" . $row["quantity"] ."</td><td>RM" . 
            $row["price"] . "</td><td>" . $row["datePay"] . "</td><tr>";
        }
        echo "</table>";
    } else {
        echo "<tr><td colspan='11' style='text-align:center;'>No report available</td></tr>";
    }
}

$con->close();
?>
</table>
<br/><br/>
</body>
</html>
