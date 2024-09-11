<?php  
	$error = "";
	if(isset($_POST['Submit'])){
		$db = mysqli_connect('localhost','root', '', 'user');
		if(!$db){
			$error = "Could not connect to Database: " . mysqli_connect_error();
		}
		else{
			$em = $_POST['Email'];
			$ph = $_POST['Phone'];
			$nm = $_POST['Name'];
			$query1 = 'SELECT email FROM users WHERE email = "'. $em . '"';
			$result = mysqli_query($db, $query1);
			$email = mysqli_fetch_all($result);
			if(sizeof($email)){ // Make sure the Email isn't already used
				$error = "A User has Already Registered with this Email";
			}
			else {
				// add stuff for writing the user info to the database
				$password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
				$insert1 = 'INSERT INTO users(email, password, name, phone) VALUES("' . $em . '", "' . $password . '", "' . $nm . '", "' . $ph . '")';
				if(mysqli_query($db, $insert1)){ // Successful Insert
					$query2 = 'SELECT id FROM users WHERE email = "' . $em . '"';
					$result = mysqli_query($db, $query2);
					$id = mysqli_fetch_all($result);
					$insert2 = 'INSERT INTO parents(parent_id) VALUES(' . $id[0][0] . ')';
					if(mysqli_query($db, $insert2)){
						mysqli_close($db);
						header('Location: success.php'); // Return to login page after registration
					}
					else{
						$error = "Database Failed to Change parents Table";
					}
				}
				else{
					$error = "Database Failed to Change users Table";
				}
			}
			mysqli_close($db);
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Parent register page</title>
</head>
<body>

<center>
	<h1>Parent Registration</h1>
	<?php print "<h4>" . $error . "</h4>"; ?>
	<form action="registerParent.php" method="POST">
	<table>
	<tr>
  	<td align="right">Name: </td>
  	<td><input type="text" name="Name" id="Name" size="12" maxlength="80" required/></td>
	</tr>



	<tr>
  	<td align="right">Password: </td>
  	<td><input type="password" name="Password" id="Password" size="12" minlength="8" maxlength="80" required/></td>
	</tr>



	<tr>
  	<td align="right">Email: </td>
  	<td><input type="email" name="Email" id="Email" size="12" maxlength="80" required/></td>
	</tr>



	<tr>
  	<td align="right">Phone: </td>
  	<td><input type="text" name="Phone" id="Phone" size="10" maxlength="10" pattern="[0-9]{10}" required/></td>
	</tr>
	</table>
	<br>

	<button type="submit" name="Submit">Register</button>
	</form>
	<br>
	<form>
    <button type="submit" formaction="index.php" name="Return">Return to Home</button>
	</form>
</center>


</body>
</html>