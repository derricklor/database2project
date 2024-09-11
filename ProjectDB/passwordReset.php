<?php
	$em = "The Email Does Not Exist";
	if(isset($_POST['Reset'])){
		$db = mysqli_connect('localhost','root', '', 'user');
		if(!$db){
			$em = "Could not connect to Database: " . mysqli_connect_error();
		}
		elseif (isset($_POST['Email'])){
			$query1 = 'SELECT email FROM users WHERE email = "' . $_POST['Email'] . '"';
			$result = mysqli_query($db, $query1);
			if(sizeof(mysqli_fetch_all($result))){
				$fp = fopen($_POST['Email'] . ".txt", 'a');
				$link = base64_encode(rand(9999, getrandmax()));
				$fp2 = fopen("reset\\" . $link . ".php", 'w');
				fwrite($fp, "A Password Reset has been requested\r\nFollow the link to continue: http://localhost/reset/" . $link . ".php\r\n\r\n");
				fwrite($fp2, "
<?php
	\$success = false;
	if(isset(\$_POST[\"Submit\"])){
		\$db = mysqli_connect('localhost','root', '', 'user');
		if(!\$db){
			\$error = \"Could not connect to Database: \" . mysqli_connect_error();
		}
		else{
			\$password = password_hash(\$_POST[\"Password\"], PASSWORD_DEFAULT);
			\$update1 = 'UPDATE users SET password = \"' . \$password . '\" WHERE email = \"" . $_POST['Email'] . "\"';
			if(mysqli_query(\$db, \$update1)){
				\$success = true;
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
<?php if(\$success){ ?>
	<div style=\"text-align: center\">
		<h3>Password Successfully Reset</h3>
		<button><a href=\"..\\index.php\">Return to Login Screen</a></button>
	</div>
<?php }
else { ?>
	<div style=\"text-align: center\">
		<h3>Reset Password for " . $_POST['Email'] . "</h3>
		<form action='" . $link . ".php' method='POST'>
			<tr>
			<td>New Password</td>
			<td align=\"left\"><input type=\"password\" name=\"Password\" size=\"12\" minlength=\"8\" maxlength=\"80\" required /></td>
			</tr>
			<br><br>
			<button type=\"submit\" name=\"Submit\">Reset</button>
		</form>
	</div>
<?php } ?>
</center>
</body>
</html>");
				fclose($fp);
				fclose($fp2);
				$em = "A reset link has been sent to " . $_POST['Email'];	
			}
		}
		
	} 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Password Reset</title>
</head>
<body>
	<center>
	<h3><?php print $em; ?></h3>
	<button><a href="index.php">Return to Login Screen</a></button>
</center>
</body>
</html>