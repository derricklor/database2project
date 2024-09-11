<?php  
	$error = "";
	if(isset($_POST['Submit'])){
		$db = mysqli_connect('localhost','root', '', 'user');
		if(!$db){
			$error = "Could not connect to Database: " . mysqli_connect_error();
		}
		else{
			$pr = $_POST['Parent'];
			$em = $_POST['Email'];
			$ph = $_POST['Phone'];
			$nm = $_POST['Name'];
			$gr = $_POST['Grade'];
			$query1 = 'SELECT id FROM users WHERE email = "' . $pr . '" AND id IN (SELECT parent_id FROM parents)';
			$query2 = 'SELECT email FROM users WHERE email = "' . $em . '"';
			$result = mysqli_query($db, $query1);
			$parent = mysqli_fetch_all($result);
			$result = mysqli_query($db, $query2);
			$email = mysqli_fetch_all($result);
			if (sizeof($parent)){ // Check the database for the Parent
				if(sizeof($email)){ // Make sure the Email isn't already used
					$error = "A User has Already Registered with this Email";
				}
				else{
					// add stuff for writing the user info to the database
					$password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
					$insert1 = 'INSERT INTO users(email, password, name, phone) VALUES("' . $em . '", "' . $password . '", "' . $nm . '", "' . $ph . '")';
					if(mysqli_query($db, $insert1)){ // Successful Insert
						$query3 = 'SELECT id FROM users WHERE email = "' . $em . '"';
						$result = mysqli_query($db, $query3);
						$id = mysqli_fetch_all($result);
						$insert2 = 'INSERT INTO students(student_id, grade, parent_id) VALUES(' . $id[0][0] . ', ' . $gr . ', ' . $parent[0][0] . ')';
						if(mysqli_query($db, $insert2)){
							mysqli_close($db);
							header('Location: success.php'); // Return to login page after registration
						}
						else{
							$error = "Database Failed to Change students Table";
						}

					}
					else{
						$error = "Database Failed to Change users Table";
					}
				}
			}
			else {
				$error = "The Parent's Email \"" . $_POST['Parent'] . "\" Does Not Exist";
			}
		}
		mysqli_close($db);
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Student register page</title>
</head>
<body>

<center>
	<h1>Student Registration</h1>
	<?php print "<h4>" . $error . "</h4>"; ?>
	<table>
	<form action="registerStudent.php" method="POST">
	<tr>
  	<td align="right">Name: </td>
  	<td><input type="text" name="Name" id="Name" size="12" maxlength="80" required/></td>
	</tr>


	<tr>
  	<td align="right">Password: </td>
  	<td><input type="password" name="Password" id="Password" size="12" minlength="8" maxlength="80" required/></td>
	</tr>


	<tr>
  	<td align="right">Parent Email: </td>
  	<td><input type="text" name="Parent" id="Parent" size="12" maxlength="80" required/></td>
	</tr>


	<tr>
  	<td align="right">Email: </td>
  	<td><input type="email" name="Email" id="Email" size="12" maxlength="80" required/></td>
	</tr>
	


	<tr>
  	<td align="right">Phone: </td>
  	<td><input type="tel" name="Phone" id="Phone" size="10" maxlength="10" pattern="[0-9]{10}" required /></td>
	</tr>


	<tr>
  	<td align="right">Grade: </td>
  	<td align="left"><select name="Grade" id="Grade">
  		<option value="6">6</option>
  		<option value="7">7</option>
  		<option value="8">8</option>
  		<option value="9">9</option>
  		<option value="10">10</option>
  		<option value="11">11</option>
  		<option value="12">12</option>
  	</select></td>
	</tr>
	</table>
	<br>
	<button type="submit" name="Submit">Register</button>
	</form>
	<br>
	<br>

	<form>
    <button type="submit" formaction="index.php" name="Return">Return to Home</button>
	</form>
</center>


</body>
</html>