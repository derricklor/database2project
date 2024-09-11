<?php  
	$error = "";
	if(isset($_POST['Submit'])){
		$db = mysqli_connect('localhost','root', '', 'user');
		if(!$db){
			$error = "Could not connect to Database: " . mysqli_connect_error();
		}
		elseif (isset($_POST['Password'])) {
			$nm = $_POST['Email'];
			$ps = $_POST['Password'];
			$query1 = 'SELECT id, password FROM users WHERE email = "' . $nm . '" AND id IN (SELECT student_id FROM students)';
			$result = mysqli_query($db, $query1);
			$pass = mysqli_fetch_all($result);
			if(sizeof($pass)){
				if(password_verify($ps, $pass[0][1])){
					header("Location: studentInfo.php?ID=" . $pass[0][0]);
				}
				else{
					$error = "The Password Does Not Match";
				}
			}
			else{
				$error = "The Username Does Not Exist";
			}
		}
		else{
			$error = "No Password Entered";
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Student login page</title>
</head>
<body>

<center>
	<h1>Student login</h1>
	<?php print "<h4>" . $error . "</h4>"; ?>
	<form method="POST">
		<table>
	<tr>
  	<td align="right">Email: </td>
  	<td align="left"><input type="email" name="Email" size="12" maxlength="80" required /></td>
	</tr>

	

	<tr>
  	<td align="right">Password: </td>
  	<td align="left"><input type="password" name="Password" size="12" maxlength="80"/></td>
	</tr>
</table>
	<br>

	<button type="submit" formaction="loginStudent.php" name="Submit">Login</button>
	<button type="submit" formaction="passwordReset.php" name="Reset">Forgot Password</button>
	</form>
	<br>

	<form>
    	<button type="submit" formaction="index.php" name="Return">Return to Home</button>
	</form>
</center>


</body>
</html>