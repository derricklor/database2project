<?php  
	if(isset($_GET['ID'])){
		print "Success";
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Phase 2 main login page</title>
</head>
<body>

<center>
	<h1>Login</h1>

	<form action="loginAdmin.php">
		<input type="submit" value="Admin">
	</form>
	
	<br>

	<form action="loginParent.php">
		<input type="submit" value="Parent">
	</form>

	<br>

	<form action="loginStudent.php">
		<input type="submit" value="Student">
	</form>

	<br><br>


	<h2>Register</h2>

	<form action="registerParent.php">
		<input type="submit" value="Parent">
	</form>

	<br>
	
	<form action="registerStudent.php">
		<input type="submit" value="Student">
	</form>
</center>
</body>
</html>