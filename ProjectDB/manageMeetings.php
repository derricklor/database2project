<?php
require "dbconnection.php";
$id = 0;
if(isset($_GET['ID'])){
	$id = $_GET['ID'];
} elseif (isset($_POST['ID'])) {
	$id = $_POST['ID'];
}
if ($id){
	$query1 = 'SELECT * FROM admins WHERE admin_id = ' . $id;
	if(sizeof(mysqli_fetch_all(mysqli_query($db, $query1)))){
		if (isset($_POST['MID'])) {
			$query2 = 'SELECT email FROM users WHERE id IN (SELECT mentee_id FROM enroll WHERE meet_id = ' . $_POST['MID'] . ')';
			$query3 = 'SELECT email FROM users WHERE id IN (SELECT mentor_id FROM enroll2 WHERE meet_id = ' . $_POST['MID'] . ')';
			$delete = 'DELETE FROM meetings WHERE meet_id = ' . $_POST['MID'];
			$mentees = mysqli_fetch_all(mysqli_query($db, $query2));
			$mentors = mysqli_fetch_all(mysqli_query($db, $query3));
			mysqli_query($db, $delete);
			$file = fopen("Meeting #" . $_POST['MID'] . ".txt", 'w');
			fwrite($file, "Meeting #" . $_POST['MID'] . " has been canceled.\r\n\r\n");
			if(sizeof($mentors)){
				fwrite($file, "The following Mentors should be contacted:\r\n");
				foreach ($mentors as $mentor) {
					fwrite($file, $mentor[0] . "\r\n");
				}
				fwrite($file, "\r\n");
			}
			if(sizeof($mentees)){
				fwrite($file, "The following Mentees should be contacted:\r\n");
				foreach ($mentees as $mentee) {
					fwrite($file, $mentee[0] . "\r\n");
				}
			}
			fclose($file);
		}
		$query4 = 'SELECT * FROM meetings';
		$meetings = mysqli_fetch_all(mysqli_query($db, $query4));
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Meetings</title>
</head>
<body>
	<center>
	<h2>Meetings</h2>
	<table>
		<tr>
			<center>
			<?php $col = 1;
			foreach ($meetings as $meeting) { 
				$qtime = 'SELECT start_time, end_time FROM time_slot WHERE time_slot_id = ' . $meeting[3];
				$qmentor = 'SELECT COUNT(mentor_id) FROM enroll2 WHERE meet_id = ' . $meeting[0]; 
				$time = mysqli_fetch_all(mysqli_query($db, $qtime)); 
				$nummentor = mysqli_fetch_all(mysqli_query($db, $qmentor)); 
				?>
			<td>
				<table>
					<tr><td>Meeting ID:</td><td><?php print $meeting[0]; ?></td></tr>
					<tr><td>Subject:</td><td><?php print $meeting[1]; ?></td></tr>
					<tr><td>Date:</td><td><?php print $meeting[2]; ?></td></tr>
					<tr><td>Start Time:</td><td><?php print $time[0][0]; ?></td></tr>
					<tr><td>End Time:</td><td><?php print $time[0][1]; ?></td></tr>
					<tr><td>Group:</td><td><?php print $meeting[6]; ?></td></tr>
					<tr><td><form method="POST">
						<input type="hidden" name="ID" value= <?php print $id; ?>>
						<button type="submit" name="MID" value= <?php print $meeting[0]; ?>>Delete Meeting</button>
						</form></td>
						<td><form action="addMaterial.php" method="GET">
						<input type="hidden" name="ID" value= <?php print $id; ?>>
						<button type="submit" name="MID" value= <?php print $meeting[0]; ?>>Materials</button>
						</form></td>
					</tr>
					<tr><td colspan="2"><center>
						<form action="addMentors.php" method="GET">
						<input type="hidden" name="ID" value= <?php print $id; ?>>
						<button type="submit" name="MID" value= <?php print $meeting[0];
						if (($nummentor[0][0] - 2) < 0){ ?>>Requires <?php print (2 - $nummentor[0][0]); ?> More Mentors</button><?php } 
						else { ?>
							>Add Mentors</button>
						<?php } ?>
						</form></center>
					</td></tr>
				</table>

			</td>
			<?php if ($col % 5 === 0){
					print "</tr><tr>";
				}
				++$col;
			} ?>
			</center>
		</tr>
	</table>
	<form>
    	<button type="submit" formaction="adminInfo.php" name="ID" value= <?php print $id; ?>>Return to Account</button>
    </form>
	</center>
</body>
</html>
<?php
	}
}
?>