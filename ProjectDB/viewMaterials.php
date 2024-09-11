<?php
	require 'dbconnection.php';
	require 'returnCheck.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Meeting Materials</title>
</head>
<body>
	<center><h2>Studying Materials</h2>
<?php 
	if(isset($_GET['ID'])){
		$query1 = 'SELECT * FROM meetings WHERE group_id = ANY (SELECT group_id FROM groups WHERE mentee_grade_req = ANY (SELECT grade FROM students WHERE student_id = ' . $_GET['ID'] . ')) ORDER BY date ASC';
		$result = mysqli_query($db, $query1);
		$meetings = mysqli_fetch_all($result);
		if(sizeof($meetings)){
			foreach ($meetings as $meeting) { ?>
				<table><th><?php print $meeting[1] . ": " . $meeting[2]; ?></th><?php
				$query2 = 'SELECT * FROM material WHERE material_id = ANY (SELECT material_id FROM assign WHERE meet_id = '. $meeting[0] . ') ORDER BY assigned_date ASC';
				$result = mysqli_query($db, $query2);
				$materials = mysqli_fetch_all($result);
				if(sizeof($materials)){
					foreach ($materials as $material) { ?>
						<table>
						<tr><td>Title:</td><td><?php print $material[1]; ?></td></tr>
						<tr><td>Author:</td><td><?php print $material[2]; ?></td></tr>
						<tr><td>Type:</td><td><?php print $material[3]; ?></td></tr>
						<tr><td>URL:</td><td><?php print $material[4]; ?></td></tr>
						<tr><td>Date Assigned:</td><td><?php print $material[5]; ?></td></tr>
						<tr><td>Notes:</td><td><?php print $material[6]; ?></td></tr>
						</table></table><?php
					}
				}
				else {?>
					<tr><td>No Materials Assigned</td></tr></table><?php
				}
			}
		}
		else{?>
			<table><tr>You are not Registered for any Meetings</tr></table><?php
		}?>
		<br>
		<form method="GET" action= <?php print $return; ?>>
			<button type="submit" name="ID" value= <?php print $id; ?>>Return to Account</button>
		</form>
		<?php
	}
?>
	</center>
</body>
</html>