<?php
	$db = mysqli_connect('localhost','root', '', 'user');
	if(!$db){
		$error = "Could not connect to Database: " . mysqli_connect_error();
	}
	else{
		$return = "index.php";
		$id = 0;
		if(isset($_GET['return_ID'])){
			$id = $_GET['return_ID'];
		}
		elseif (isset($_GET['ID'])){
			$id = $_GET['ID'];
		}
		elseif(isset($_POST['return_ID'])){
			$id = $_POST['return_ID'];
		}
		$Pquery = 'SELECT * FROM parents WHERE parent_id = ' . $id;
		$result = mysqli_fetch_all(mysqli_query($db, $Pquery));
		if (sizeof($result)){
			$return = "parentInfo.php";
		}
		else {
			$Squery = 'SELECT * FROM students WHERE student_id = ' . $id;
			$result = mysqli_fetch_all(mysqli_query($db, $Squery));
			if (sizeof($result)){
				$return = "studentInfo.php";
			}
			else{
				$Aquery = 'SELECT * FROM admins WHERE admin_id = ' . $id;	
				$result = mysqli_fetch_all(mysqli_query($db, $Aquery));
				if(sizeof($result)){
					$return = "adminInfo.php";
				}
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Account Managment</title>
</head>
<body>
	<center>
		<?php 
			if (isset($_POST['update'])){ 
				if(isset($_POST['email'])){
					$query = 'SELECT * FROM users WHERE email = "' . $_POST['email'] . '"';
					if (sizeof(mysqli_fetch_all(mysqli_query($db, $query)))){
						header("Location: editAccount.php?ID=" . $_POST['ID'] . "&return_ID=" . $_POST['return_ID'] . "&email=");
					}
					$update = 'UPDATE users SET email = "' . $_POST['email'] . '" WHERE id = ' . $_POST['ID'];
					mysqli_query($db, $update);
				}
				elseif(isset($_POST['phone'])){
					$update = 'UPDATE users SET phone = ' . $_POST['phone'] .' WHERE id = ' . $_POST['ID'];
					mysqli_query($db, $update);
				}
				elseif (isset($_POST['password'])){
					$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
					$update = 'UPDATE users SET password = "' . $pass . '" WHERE id = ' . $_POST['ID'];
					mysqli_query($db, $update);
				}

			?>
			<h4>Your account has been updated</h4>
		<?php } elseif (isset($_GET['email'])){ ?>
			<h4>Change Your Email</h4>
			<form action="editAccount.php" method="post">
				<input type="email" name="email" maxlength="80" required/>
				<input type="hidden" name="ID" value=<?php print $_GET['ID']?> />
				<input type="hidden" name="return_ID" value=<?php print $id ?> />
				<button type="submit" name="update">Update</button>
			</form>
		<?php } elseif (isset($_GET['phone'])){ ?>
			<h4>Change Your Phone Number</h4>
			<form action="editAccount.php" method="post">
				<input type="tel" name="phone" pattern="[0-9]{10}" maxlength="10" required/>
				<input type="hidden" name="ID" value=<?php print $_GET['ID']?> />
				<input type="hidden" name="return_ID" value=<?php print $id ?> />
				<button type="submit" name="update">Update</button>
			</form>
		<?php } elseif (isset($_GET['password'])){ ?>
			<h4>Change Your Password</h4>
			<form action="editAccount.php" method="post">
				<input type="password" name="password" minlength="8" maxlength="80" required/>
				<input type="hidden" name="ID" value=<?php print $_GET['ID']?> />
				<input type="hidden" name="return_ID" value=<?php print $id ?> />
				<button type="submit" name="update">Update</button>
			</form>
		<?php } }?>
		<br>
		<form method="get" action=<?php print "\"". $return ."\""; ?>>
			<button type="submit" name="ID" value=<?php print "\"" . $id ."\""; ?>>Return to Account</button>
		</form>
	</center>
</body>
</html>