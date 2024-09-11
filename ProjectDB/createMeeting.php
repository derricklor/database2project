<?php
require "dbconnection.php";
$id = 0;
$success = "";
if (isset($_GET['ID'])) {
	$id = $_GET['ID'];
}
elseif (isset($_POST['ID'])) {
	$id = $_POST['ID'];
}
if ($id) {
	if (isset($_POST['meet'])) {
		$insert = 'INSERT INTO meetings(meet_name, date, time_slot_id, capacity, announcement, group_id) VALUES("' . $_POST['name'] . '", "' . $_POST['date'] . '", ' . $_POST['time'] . ', ' . $_POST['capacity'] . ', "' . $_POST['anno'] . '", ' . $_POST['GID'] . ')';
		mysqli_query($db, $insert);
		$success = "A new Meeting has been Created";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Meeting</title>
</head>
<body>
<center>
	<h1>Create a Meeting</h1>
	<?php print "<h4>" . $success . "</h4>"; ?>
	<table>
	<form method="POST">
	<tr>
  	<td align="right">Meeting Name: </td>
  	<td><input type="text" name="name" size="12" maxlength="80" required/></td>
	</tr>

	<tr>
  	<td align="right">Group: </td>
  	<td align="left"><select name="GID">
  		<?php
  		$query2 = "SELECT group_id, name FROM groups";
  		$groups = mysqli_fetch_all(mysqli_query($db, $query2));
  		foreach ($groups as $group) { ?>
  		 	<option value=<?php print '"'.$group[0].'">'.$group[1]; ?> </option>
  		<?php } ?>
  	</select></td>
	</tr>

	<tr>
  	<td align="right">Date: </td>
  	<td><input type="date" name="date" size="12" min=<?php print '"'.date("Y-m-d").'"'; ?>required/></td>
	</tr>

	<tr>
  	<td align="right">Time Slot: </td>
  	<td align="left"><select name="time">
  		<?php
  		$query1 = "SELECT * FROM time_slot"; 
  		$times = mysqli_fetch_all(mysqli_query($db, $query1)); 
  		foreach ($times as $time) { ?>
  			<option value=<?php print '"'.$time[0].'">'.$time[1].': '.substr($time[2], 0, -3).' to '.substr($time[3], 0, -3); ?> </option>
  		<?php } ?>
  	</select></td>
	</tr>

	<tr>
  	<td align="right">Capacity: </td>
  	<td><input type="number" name="capacity" size="12" min="1" max="30" required/></td>
	</tr>

	

	<tr>
  	<td align="right">Announcement: </td>
  	<td><input type="text" name="anno" size="12" maxlength="80" required/></td>
	</tr>

	
	</table>
	<br>
	<button type="submit" name="meet">Schedule Meeting</button>
	</form>
	<br>
	<br>

	<form>
    <button type="submit" formaction="adminInfo.php" name="ID" value=<?php print $id ?>>Return to Account</button>
	</form>
</center>
</body>
</html>
<?php } ?>