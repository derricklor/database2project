
<?php
	$success = false;
	if(isset($_POST["Submit"])){
		$db = mysqli_connect('localhost','root', '', 'user');
		if(!$db){
			$error = "Could not connect to Database: " . mysqli_connect_error();
		}
		else{
			$password = password_hash($_POST["Password"], PASSWORD_DEFAULT);
			$update1 = 'UPDATE users SET password = "' . $password . '" WHERE email = "parent@gmail.com"';
			if(mysqli_query($db, $update1)){
				$success = true;
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Password Reset Page</title>
</head>
<body>
<center>
<?php if($success){ ?>
	<div style="text-align: center">
		<h3>Password Successfully Reset</h3>
		<button><a href="..\index.php">Return to Login Screen</a></button>
	</div>
<?php }
else { ?>
	<div style="text-align: center">
		<h3>Reset Password for parent@gmail.com</h3>
		<form action='MjA0NTgxNjE1.php' method='POST'>
			<tr>
			<td>New Password</td>
			<td align="left"><input type="password" name="Password" size="12" minlength="8" maxlength="80" required /></td>
			</tr>
			<br><br>
			<button type="submit" name="Submit">Reset</button>
		</form>
	</div>
<?php } ?>
</center>
</body>
</html>