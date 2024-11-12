<?php
session_start();

include("Connectdb.php");

// Initialize the message variable
$message = "";

// Check failed attempts
if (isset($_SESSION['last_failed_attempt']) && isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= 3) {
    $lockout_time = $_SESSION['last_failed_attempt'] + 300; // 5 minutes
    if (time() < $lockout_time) {
        // User is still locked out
        $remaining_time = $lockout_time - time();
        $message = "Too many failed attempts. Please try again in $remaining_time seconds.";
    } else {
        // Reset failed attempts after the lockout time has passed
        unset($_SESSION['failed_attempts']);
        unset($_SESSION['last_failed_attempt']);
    }
}

// Proceed with login attempt if user is not locked out
if ($_SERVER['REQUEST_METHOD'] == "POST" && (!isset($_SESSION['failed_attempts']) || $_SESSION['failed_attempts'] < 3 || time() >= $_SESSION['last_failed_attempt'] + 300)) {
    // Check if POST variables are set
    if (isset($_POST['name']) && isset($_POST['pw'])) {
        $uname = $_POST['name'];
        $pw = $_POST['pw'];

        if (!empty($uname) && !empty($pw)) {

            $query = "SELECT * FROM db WHERE name = '$uname' LIMIT 1";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if (password_verify($pw, $user_data['pw'])) {
                    // Successful login: reset failed attempts and lockout time
                    $_SESSION['userID'] = $user_data['id'];
                    $_SESSION['role'] = $user_data['role'];
                    unset($_SESSION['failed_attempts']);
                    unset($_SESSION['last_failed_attempt']);

                    // Redirect based on user role
                    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'sub_admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: index.php");
                    }
                    die;
                } else {
                    // Incorrect password, increment failed attempts
                    if (!isset($_SESSION['failed_attempts'])) {
                        $_SESSION['failed_attempts'] = 0;
                    }
                    $_SESSION['failed_attempts']++;

                    // Record the time of the last failed attempt
                    $_SESSION['last_failed_attempt'] = time();

                    // Lockout condition: after 3 failed attempts, lockout for 5 minutes
                    if ($_SESSION['failed_attempts'] >= 3) {
                        $message = "Too many failed attempts. Please try again in 5 minutes.";
                    } else {
                        $message = "Invalid username or password.";
                    }
                }
            } else {
                // Username does not exist
                $message = "Invalid username or password.";
            }
        } else {
            $message = "Please fill in both fields.";
        }
    } else {
        // If the POST variables are not set
        $message = "Please fill in both fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="utf-8">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <img src="images/logo.png" width="190" height="90">
    <ul>
        <li><a href="index.php"><img src="images/home.png" width="150" height="50"></a></li>
        <li><a href="products_main.php"><img src="images/p.png" width="150" height="50"></a></li>
        <li><a href="feedback.php"><img src="images/fb.png" width="150" height="50"></a></li>
        <li><a href="about_us.php"><img src="images/au.png" width="150" height="50"></a></li>
    </ul>
</nav>

<div class="layout">
    <h1>Login</h1>

    <!-- Display the message at the top of the page -->
    <?php if ($message != ""): ?>
    <div class="error-message"><?php echo $message; ?></div>
<?php endif; ?>

    <form action="#" method="post">
        <p>Username:</p>
        <input type="text" name="name" placeholder="Username">
        <p>Password:</p>
        <input type="password" name="pw" placeholder="Password">
        <br/><br/>
        <a href="su.php">Sign Up Here</a>
        <br/>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
