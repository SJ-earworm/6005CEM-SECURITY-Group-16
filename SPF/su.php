<?php

include("Connectdb.php");

// Initialize the message variable
$message = "";

// Handle the sign-up form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = $_POST['email'];
    $uname = $_POST['name'];
    $pw = $_POST['pw'];
    $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);

    // Check if password meets the requirements
    if (strlen($pw) < 6) {
        $message = "Password must be at least 6 characters long.";
    } elseif (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $pw)) {
        $message = "Password must include at least one special character.";
    } else {
        // If password is valid, proceed to insert the data
        if (!empty($email) && !empty($uname) && !empty($pw)) {

            // SQL Query to Insert Data
            $query = "INSERT INTO db(email, name, pw) VALUES ('$email','$uname','$hashed_pw')";
            $result = mysqli_query($con, $query);

            if ($result) {
               header("Location: login.php");
                exit;
            } else {
                // If there was an issue with the query
                $message = "Error: Unable to create your account. Please try again later.";
            }
        } else {
            $message = "Please fill up all details to register.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="utf-8">

<head>
    <title>Sign up</title>
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
            <li><a href="login.php"><img src="images/login.png" width="100" height="50"></a></li>
        </ul>
    </nav>

    <div class="layout">
        <br/><br/>
        <h1>Sign Up</h1>

        <!-- Error or success message display -->
        <?php if ($message != ""): ?>
            <div class="error-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="#" method="post">
            <p>Email:</p>
            <input type="text" name="email" placeholder="Email">
            <p>Username:</p>
            <input type="text" name="name" placeholder="Username">
            <p>Password:</p>
            <input type="password" name="pw" id="password" placeholder="Password">
            <div id="password-strength">
                <div class="strength-bar" id="strength-bar"></div>
            </div>
            <div id="strength-message" class="strength-message"></div>
            <button type="submit">Create Account</button>
        </form>
    </div>

<script>
    const passwordField = document.getElementById('password');
    const strengthMessage = document.getElementById('strength-message');
    const form = document.querySelector('form'); // The form element

    // Function to check password strength, including special character check
    function checkPasswordStrength(password) {
        let strength = 0;
        const length = password.length;
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password); // Regular expression to check for special characters

        // Check length for basic strength evaluation
        if (length < 6) {
            strength = 1; // Weak
        } else if (length >= 6 && length < 12) {
            strength = 2; // Medium
        } else if (length >= 12) {
            strength = 3; // Strong
        }

        // Adjust strength message and level based on length
        let strengthLevel = 'Weak';
        let strengthClass = 'weak'; // Default class for weak password

        if (strength === 2) {
            strengthLevel = 'Medium';
            strengthClass = 'medium';
        } else if (strength === 3) {
            strengthLevel = 'Strong';
            strengthClass = 'strong';
        }

        // Check if password contains at least one special character
        if (!hasSpecialChar) {
            strengthMessage.textContent = 'Password must include at least one special character';
            strengthMessage.className = 'strength-message weak';
        } else {
            // If password has special character, show the strength level
            strengthMessage.textContent = `Password Strength: ${strengthLevel}`;
            strengthMessage.className = `strength-message ${strengthClass}`;
        }

        // Password must be at least medium strength and have a special character
        return strength >= 2 && hasSpecialChar; 
    }

    // Add event listener to the password input field
    passwordField.addEventListener('input', function () {
        checkPasswordStrength(passwordField.value);
    });

    // Prevent form submission if password doesn't meet requirements
    form.addEventListener('submit', function(event) {
        
        const password = passwordField.value;
        if (!checkPasswordStrength(password)) {
            event.preventDefault(); // Prevent form submission
        }
    });
</script>

</body>

</html>
