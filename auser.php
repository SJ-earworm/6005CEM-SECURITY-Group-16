<?php
// error handling setup
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', 'Off');   // syntax errors considered startup errors cos they run before the execution of the page render
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('error_log', 'C:/Applications/XAMPP/apache/logs/SPF/SPF-error.log');

require("session_handling.php");

?>



<!DOCTYPE html>
<html lang="utf=8">
	<head>
		<title>Admin Users</title>
		<link rel="stylesheet" href="astyle.css">
	</head>
	<body>

		<div class="sidebar">
			<img src ="images/logo.png" width="160" height="100">
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

					// if (isset($_POST['id'])){
					// 	$id=$_POST['id'];	
					// 	$delete= mysqli_query($con, "DELETE from `db` where id = '$id'");
						
					// }

					// $query = "";
					
					// // if the user uses the search filter
					// if (isset($_POST['search-query'])) {
					// 	// cleaning the input from whitespace & special characters
					// 	$userSearch = $_POST['search-query'];
					// 	$userSearch = trim($userSearch);
					// 	$userSearch = addslashes($userSearch);

					// 	$query = "SELECT id, name, email from db WHERE ";
					// 	$query .= "name LIKE '%$userSearch%' OR email LIKE '%$userSearch%'";

					// 	// retriving query result
					// 	$result = mysqli_query($con, $query);

					// 	if (mysqli_num_rows($result) > 0) {
					// 		echo "<tr>";
					// 		echo "	<th>Username</th>";
					// 		echo "	<th>Email</th>";
					// 		echo "	<th></th>";
					// 		echo "</tr>";

					// 		while ($row = mysqli_fetch_assoc($result)){
					// 			echo "<tr>";
					// 			echo "    <td>".$row['name']."</td>";
					// 			echo "    <td>".$row['email']."</td>";
					// 			echo "    <td><button class='delete-btn delete' value='".$row['id']."'>Delete</div></td>";
					// 			echo "</tr>";
					// 		}
					// 		echo"</table>";
					// 	}
					// 	else {
							
					// 		echo"<p style='margin-top: 24%; font-size: 1.5rem;'>0 result</p>";		
					// 	}
					// }
					// else {
					// 	// display all users if search filter is empty
					// 	$query = "SELECT id, name, email from db ";

					// 	// retriving query result
					// 	$result = mysqli_query($con, $query);

					// 	if (mysqli_num_rows($result) > 0) {
					// 		echo "<tr>";
					// 		echo "	<th>Username</th>";
					// 		echo "	<th>Email</th>";
					// 		echo "	<th></th>";
					// 		echo "</tr>";

					// 		while ($row = mysqli_fetch_assoc($result)){
					// 			echo "<tr>";
					// 			echo "    <td>".$row['name']."</td>";
					// 			echo "    <td>".$row['email']."</td>";
					// 			echo "    <td><button class='delete-btn delete' value='".$userID = $row['id']."'>Delete</div></td>";
					// 			echo "</tr>";
		
					// 		}
					// 		echo"</table>";
					// 	}
					// 	else {
							
					// 		echo"<p style='margin-top: 24%; font-size: 1.5rem;'>0 result</p>";		
					// 	}
					// }

					// // closing database connection
					// $con-> close();


					// NEW CODE
					// Handle user role update
					if (isset($_POST['update_role'])) {
						// sanitising the retrieved values
						$user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
						$extraSanitisedUser_Id = abs($user_id);  // force casting int into positive numbers
						$new_role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
			
						// Update role in the database
						$update_query = "UPDATE db SET role = ? WHERE id = ?";
						$stmt = $con->prepare($update_query);
						$stmt->bind_param("si", $new_role, $extraSanitisedUser_Id);
						$stmt->execute();

						if ($stmt->errno) {
							echo "<p style='color: red;'>Error updating role</p>";
							// temporary
							// echo mysqli_error($con);
						} else {
							echo "<p style='color: green;'>Role updated successfully!</p>";
						}
			
						// original
						// if ($update_result) {
						// 	echo "<p style='color: green;'>Role updated successfully!</p>";
						// } else {
						// 	echo "<p style='color: red;'>Error updating role: " . mysqli_error($con) . "</p>";
						// }
					}
			
					// If the user wants to delete a record
					if (isset($_POST['id'])) {
						// sanitising userID from delete button
						$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
						$extraSanitisedUser_IdDel = abs($id);
						$deleteQuery = "DELETE from `db` where id = ?";
						
						$stmt = $con->prepare($deleteQuery);
						$stmt->bind_param("i", $extraSanitisedUser_IdDel);
						$stmt->execute();
						if ($stmt->errno) {
							echo "<p style='color: red;'>Error deleting user</p>";
							// temporary
							// echo $stmt->errno;
						}
					}
			
					$query = "";
			
					// if the user uses the search filter
					if (isset($_POST['search-query'])) {
						// Cleaning the input from whitespace & special characters
						$rawUserSearchTrim = trim($_POST['search-query']);
						$sanitisedUserSearch = filter_var($rawUserSearchTrim, FILTER_SANITIZE_STRING);
						$userSearch = "%$sanitisedUserSearch%";
			
						$query = "SELECT id, name, email, role from db WHERE name LIKE ? OR email LIKE ?";
						$stmt = $con->prepare($query);
						$stmt->bind_param("ss", $userSearch, $userSearch);
						$stmt->execute();
						$result = $stmt->get_result();
			
						// Retrieving query result			
						if ($stmt->errno) {
							// Check if query was successful
							echo "<p style='color: red;'>Could not fetch users</p>";
							// temporary
							// echo $stmt->errno;
						} elseif ($result->num_rows > 0) {
							echo "<tr>";
							echo "    <th>Username</th>";
							echo "    <th>Email</th>";
							echo "    <th>Role</th>";
							echo "    <th></th>";
							echo "</tr>";
			
							while ($row = $result->fetch_assoc()) {
								echo "<tr>";
								echo "    <td>" . htmlspecialchars($row['name']) . "</td>";
								echo "    <td>" . htmlspecialchars($row['email']) . "</td>";
								echo "    <td>";
								echo "        <form action='auser.php' method='post'>";
								echo "            <select name='role'>";
								echo "                <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>";
								echo "                <option value='sub_admin' " . ($row['role'] == 'sub_admin' ? 'selected' : '') . ">Sub Admin</option>";
								echo "                <option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">User</option>";
								echo "            </select>";
								echo "            <input type='hidden' name='user_id' value='" . (int)$row['id'] . "'>";
								echo "            <input type='submit' name='update_role' value='Update Role'>";
								echo "        </form>";
								echo "    </td>";
								echo "    <td><button class='delete-btn delete' value='" . htmlspecialchars($row['id']) . "'>Delete</button></td>";
								echo "</tr>";
							}
						} else {
							echo "<p style='margin-top: 24%; font-size: 1.5rem;'>0 results</p>";
						}
					} else {
						// Display all users if search filter is empty
						$query = "SELECT id, name, email, role from db";
						$stmt = $con->prepare($query);
						$stmt->execute();
						$result = $stmt->get_result();
			
						if ($stmt->errno) {
							// Check if query was successful
							echo "<p style='color: red;'>Could not fetch users</p>";
							// temporary
							// echo $stmt->errno;
						} elseif ($result->num_rows > 0) {
							echo "<tr>";
							echo "    <th>Username</th>";
							echo "    <th>Email</th>";
							echo "    <th>Role</th>";
							echo "    <th>Actions</th>";
							echo "</tr>";
			
							while ($row = $result->fetch_assoc()) {
								echo "<tr>";
								echo "    <td>" . htmlspecialchars($row['name']) . "</td>";
								echo "    <td>" . htmlspecialchars($row['email']) . "</td>";
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
								echo "    <td><button class='delete-btn delete' value='" . htmlspecialchars($row['id']) . "'>Delete</button></td>";
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
		<br/><br/>


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


		<!-- javascript -->
		<script>
            // 'querySelectorAll' for selecting all elements that match the 'delete-btn' class
            let delButtons = document.querySelectorAll(".delete-btn");
            let dismissBtn = document.getElementById("dismissBtn");
            const dimmedBg = document.getElementById("dimOverlay");
            const alertBox = document.getElementById("confirmDel");

            // if user clicks on "Yes", perform steps to send product ID into `db_deleteproduct.php`
            delButtons.forEach(function(delBtn) {
                delBtn.addEventListener("click", function() {
                    // retrieving the user ID from the button
                    let userID = delBtn.getAttribute("value");

                    // displaying the alertbox
                    if (alertBox.style.display === "none") {
                        dimmedBg.style.display = "block";
                        alertBox.style.display = "block";
                    }
                    else {
                        dimmedBg.style.display = "none";
                        alertBox.style.display = "none";
                    }

                    // locating the hidden input form field & passing productID into it
                    document.querySelector("input[name='id']").value = userID;
                });
            })



            // if user click son "No"
            dismissBtn.addEventListener("click", function() {
                // displaying the alert box
                if (alertBox.style.display === "none") {
                    dimmedBg.style.display = "block";
                    alertBox.style.display = "block";
                }
                else {
                    dimmedBg.style.display = "none";
                    alertBox.style.display = "none";
                }
            });

        </script>
	</body>
</html>
