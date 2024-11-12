<?php

include("Connectdb.php");

if ($_SERVER['REQUEST_METHOD']=="POST"){

$email = $_POST['email'];
$uname = $_POST['name'];
$pw = $_POST['pw'];
$hashed_pw = password_hash($pw, PASSWORD_DEFAULT);

if(!empty($email) && !empty($uname) && !empty($pw)){

$query = "INSERT INTO db(email, name, pw) VALUES ('$email','$uname','$hashed_pw')";

mysqli_query($con, $query);
header("Location: login.php");
die;
}else
{
echo "Please fill up all details to register.";
}

}


?>


<!DOCTYPE html>
<html lang="utf=8">



<head>
<title>Sign up</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
 

<nav>
		<img src ="images/logo.png" width="190" height="90">
	<ul>
		<li><a href="index.php"><img src ="images/home.png" width="150" height="50"></a></li>
		<li><a href="products_main.php"><img src ="images/p.png" width="150" height="50"></a></li>
		<li><a href="feedback.php"><img src ="images/fb.png" width="150" height="50"></a></li>
		<li><a href="about_us.php"><img src ="images/au.png" width="150" height="50"></a></li>
		<li><a href="login.php"><img src ="images/login.png" width="100" height="50"></a></li>
	</ul>
</nav>

<div class="layout">
	<br/><br/>
		<h1>Sign Up</h1>
	<form action="#" method="post">
		<p>Email:</p>
		<input type="text" name="email" placeholder="Email">
		<p>Username:</p>
		<input type="text" name="name" placeholder="Username">
		<p>Password:</p>
		<input type="password" name="pw" placeholder="Password">
		<button type="submit">Create Account</button>
	</form>
</div>


</body>
</html>