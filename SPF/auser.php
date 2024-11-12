<?php
require("session_handling.php");
?>

<!DOCTYPE html>
<html lang="utf-8">
<head>
    <title>Admin Users</title>
    <link rel="stylesheet" href="astyle.css">
</head>
<body>

<div class="sidebar">
    <img src="images/logo.png" width="160" height="100">
    <img src="images/profile.png" class="profile">
    <a href="admin.php">Dashboard</a>
    <a href="apselect.php">Product</a>
    <a href="auser.php">Users</a>
    <a href="areport.php">Statistic</a>
    <a href="logout.php">Logout</a>
</div>

<!-- search function -->
<div class="invisible-header">
    <div class="search">
        <form action="#" method="post">
            <input type="text" name="search-query" placeholder="Search user" autocomplete="off">
            <input type="submit" name="search-btn">
        </form>
    </div>
</div>

<div class="content-area">
    <table>
        <?php
        include("Connectdb.php");

        // Handle user role update
        if (isset($_POST['update_role'])) {
            $user_id = $_POST['user_id'];
            $new_role = $_POST['role'];

            // Update role in the database
            $update_query = "UPDATE db SET role = '$new_role' WHERE id = '$user_id'";
            $update_result = mysqli_query($con, $update_query);

            if ($update_result) {
                echo "<p style='color: green;'>Role updated successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error updating role: " . mysqli_error($con) . "</p>";
            }
        }

        // If the user wants to delete a record
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $delete = mysqli_query($con, "DELETE from `db` where id = '$id'");
        }

        $query = "";

        // if the user uses the search filter
        if (isset($_POST['search-query'])) {
            // Cleaning the input from whitespace & special characters
            $userSearch = $_POST['search-query'];
            $userSearch = trim($userSearch);
            $userSearch = addslashes($userSearch);

            $query = "SELECT id, name, email, role from db WHERE name LIKE '%$userSearch%' OR email LIKE '%$userSearch%'";

            // Retrieving query result
            $result = mysqli_query($con, $query);

            if (!$result) {
                // Check if query was successful
                echo "<p style='color: red;'>Query failed: " . mysqli_error($con) . "</p>";
            } elseif (mysqli_num_rows($result) > 0) {
                echo "<tr>";
                echo "    <th>Username</th>";
                echo "    <th>Email</th>";
                echo "    <th>Role</th>";
                echo "    <th></th>";
                echo "</tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "    <td>" . $row['name'] . "</td>";
                    echo "    <td>" . $row['email'] . "</td>";
                    echo "    <td>";
                    echo "        <form action='auser.php' method='post'>";
                    echo "            <select name='role'>";
                    echo "                <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>";
                    echo "                <option value='sub_admin' " . ($row['role'] == 'sub_admin' ? 'selected' : '') . ">Sub Admin</option>";
                    echo "                <option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">User</option>";
                    echo "            </select>";
                    echo "            <input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                    echo "            <input type='submit' name='update_role' value='Update Role'>";
                    echo "        </form>";
                    echo "    </td>";
                    echo "    <td><button class='delete-btn delete' value='" . $row['id'] . "'>Delete</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<p style='margin-top: 24%; font-size: 1.5rem;'>0 results</p>";
            }
        } else {
            // Display all users if search filter is empty
            $query = "SELECT id, name, email, role from db";
            $result = mysqli_query($con, $query);

            if (!$result) {
                // Check if query was successful
                echo "<p style='color: red;'>Query failed: " . mysqli_error($con) . "</p>";
            } elseif (mysqli_num_rows($result) > 0) {
                echo "<tr>";
                echo "    <th>Username</th>";
                echo "    <th>Email</th>";
                echo "    <th>Role</th>";
                echo "    <th>Actions</th>";
                echo "</tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "    <td>" . $row['name'] . "</td>";
                    echo "    <td>" . $row['email'] . "</td>";
                    echo "    <td>";
                    echo "        <form action='auser.php' method='post'>";
                    echo "            <select name='role'>";
                    echo "                <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>";
                    echo "                <option value='sub_admin' " . ($row['role'] == 'sub_admin' ? 'selected' : '') . ">Sub Admin</option>";
                    echo "                <option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">User</option>";
                    echo "            </select>";
                    echo "            <input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                    echo "            <input type='submit' name='update_role' value='Update'>";
                    echo "        </form>";
                    echo "    </td>";
                    echo "    <td><button class='delete-btn delete' value='" . $row['id'] . "'>Delete</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<p style='margin-top: 24%; font-size: 1.5rem;'>No users found.</p>";
            }
        }

        // Closing database connection
        mysqli_close($con);
        ?>
    </table>
</div>

<!-- pop-up alert to confirm if user wants to delete item -->
<div id="dimOverlay" class="dimmed-overlay" style="display: none;"></div>
<div id="confirmDel" class="confirm-del" style="display: none;">
    <p>Are you sure you want to delete this user?</p>
    <div class="confirm-del-btn">
        <form action="auser.php" method="post">
            <input type="hidden" name="id">
            <button type="submit">Yes</button>
        </form>
        <button id="dismissBtn">No</button>
    </div>
</div>

<script>
    let delButtons = document.querySelectorAll(".delete-btn");
    let dismissBtn = document.getElementById("dismissBtn");
    const dimmedBg = document.getElementById("dimOverlay");
    const alertBox = document.getElementById("confirmDel");

    delButtons.forEach(function (delBtn) {
        delBtn.addEventListener("click", function () {
            let productID = delBtn.getAttribute("value");
            if (alertBox.style.display === "none") {
                dimmedBg.style.display = "block";
                alertBox.style.display = "block";
            } else {
                dimmedBg.style.display = "none";
                alertBox.style.display = "none";
            }
            document.querySelector("input[name='id']").value = productID;
        });
    });

    dismissBtn.addEventListener("click", function () {
        if (alertBox.style.display === "none") {
            dimmedBg.style.display = "block";
            alertBox.style.display = "block";
        } else {
            dimmedBg.style.display = "none";
            alertBox.style.display = "none";
        }
    });
</script>
</body>
</html>