<?php
$return = "index.php";
$id = 0;
if(isset($_GET['return_ID'])){
	$id = $_GET['return_ID'];
}
elseif (isset($_GET['ID'])){
	$id = $_GET['ID'];
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