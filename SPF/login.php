<?php
session_start();

include("Connectdb.php");

// OLD CODE
// if ($_SERVER['REQUEST_METHOD']=="POST"){


// $uname = $_POST['name'];
// $pw = $_POST['pw'];

// if(!empty($uname) && !empty($pw)){

// $query = "SELECT * from db WHERE name = '$uname' limit 1 ";

// $result = mysqli_query($con, $query);

// if($result){

// if ($result && mysqli_num_rows($result)>0){

// 	$user_data = mysqli_fetch_assoc($result);

// 		if(password_verify($pw,$user_data['pw'])){

// 			//retrieving user ID from the database for the logged in session
// 			$userID = $user_data['id'];
// 			$_SESSION['userID'] = $userID;

// 			if($uname == 'admin'){
// 				// if admin, direct to admin dashboard
// 				header("Location: admin.php");
// 				die;
// 			}
// 			else {
// 				// directing regular users to homepage
// 				header("Location: index.php");
// 				die;
// 			}
// 		}
// 	} 
// }
// echo "Make sure to enter email and password correctly, please try again.";

// }else
// {
// echo "Please make sure to fill in everything.";
// }
// }

// NEW CODE
// Initialize the message variable
$message = "";

// Check failed attempts
if (isset($_SESSION['last_failed_attempt']) && isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= 3) {
    $lockout_time = $_SESSION['last_failed_attempt'] + 300; // 5 minutes
    if (time() < $lockout_time) {
        // User is still locked out
        $remaining_time = $lockout_time - time();
        $message = "Too many failed attempts. Please try again in " .htmlspecialchars($remaining_time). " seconds.";
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
        $uname = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $pw = $_POST['pw'];
        $recaptcha_response = $_POST['g-recaptcha-response'];  // Get the reCAPTCHA response

        if (!empty($uname) && !empty($pw)) {

            // Verify reCAPTCHA response
            $recaptcha_secret_key = '6LeMQ3wqAAAAAFRMriEKNg37499YyvTGiZjIpHSb';
            $recaptcha_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_data = [
                'secret' => $recaptcha_secret_key,
                'response' => $recaptcha_response
            ];

            // Make a POST request to verify the reCAPTCHA response
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $recaptcha_verify_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $recaptcha_data);
            $recaptcha_result = curl_exec($ch);
            curl_close($ch);

            $recaptcha_result = json_decode($recaptcha_result);

            // Check if reCAPTCHA was successful
            if (!$recaptcha_result->success) {
                $message = "Please complete the reCAPTCHA challenge.";
            } else {
                // Proceed with login logic if reCAPTCHA is valid
                $query = "SELECT * FROM db WHERE name = ? LIMIT 1";
                $stmt = $con->prepare($query);
                $stmt->bind_param("s", $uname);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user_data = $result->fetch_assoc();

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
<html lang="utf=8">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
  
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
 

<nav>
		<img src ="images/logo.png" width="190" height="90">
	<ul>
		<li><a href="index.php"><img src ="images/home.png" width="150" height="50"></a></li>
		<li><a href="products_main.php"><img src ="images/p.png" width="150" height="50"></a></li>
		<li><a href="feedback.php"><img src ="images/fb.png" width="150" height="50"></a></li>
		<li><a href="about_us.php"><img src ="images/au.png" width="150" height="50"></a></li>
	</ul>
</nav>


<div class="layout">
<h1>Login</h1>

	<!-- NEW ADDITION -->
	<!-- Display the message at the top of the page -->
    <?php if ($message != ""): ?>
    <div class="error-message"><?php echo $message; ?></div>
	<?php endif; ?>

	<form action="#" method="post">
        <p>Username:</p>
        <input type="text" name="name" placeholder="Username" autocomplete="off" required />
        <p>Password:</p>
        <input type="password" name="pw" placeholder="Password" autocomplete="off" required />
        <br/><br/> 
      
        <div class="g-recaptcha" data-sitekey="6LeMQ3wqAAAAACj6O3Jj3yK67h1DSzStHPUcMtHi"></div>

        <br/><br/>
        <a href="su.php">Sign Up Here</a>
        <br/>
        <button type="submit">Login</button>
    </form>
</div>
</div>

</body>
</html>