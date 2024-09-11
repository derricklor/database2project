<?php
// set up connection to our db
include 'dbconnection.php';
// get ID from get or post and set return
include 'returnCheck.php';

$meeting_id = $_GET['MID'];

if(isset($_GET['remove'])){
    $deleteEnroll2 = "DELETE FROM enroll2 WHERE mentor_id = ".$_GET['remove']." AND meet_id =".$meeting_id;
	$result_deleteEnroll2 = mysqli_query($db, $deleteEnroll2);
	
	// if count of student_id in enroll2 table is 0 then delete student_id from mentor table
	$count2 = "SELECT count(*) FROM enroll2 WHERE mentor_id =".$_GET['remove'];
	$result_count2 = mysqli_query($db, $count2);
	$array_count2 = mysqli_fetch_array($result_count2);
	if($array_count2[0] == 0)
	{
		$deleteMentor = "DELETE FROM mentors WHERE mentor_id = " . $_GET['remove'];
		$result_deleteMentor = mysqli_query($db, $deleteMentor);
	}
}
elseif(isset($_GET['add'])){
	//admins can overule the 3 max mentors
	$addMentor = "INSERT INTO mentors(mentor_id) VALUES(" . $_GET['add'] .")";
	$addEnroll2 = "INSERT INTO enroll2(meet_id, mentor_id) VALUES(" .$meeting_id .",".$_GET['add'] .")";
	$result_addMentor = mysqli_query($db, $addMentor);
	$result_addEnroll2 = mysqli_query($db, $addEnroll2);
	if($result_addEnroll2){
		$stuname = mysqli_fetch_all(mysqli_query($db, "SELECT email FROM users WHERE id = " . $_GET['add']))[0][0];
		$file = fopen($stuname . ".txt", 'a');
		fwrite($file, $stuname . " has been added as a mentor for Meeting #" . $meeting_id . "\r\n\r\n");
	}
	
}

//query student mentors already in this meeting
$queryalready = " SELECT * FROM students WHERE student_id IN ( SELECT mentor_id as student_id FROM enroll2 WHERE meet_id =" .$meeting_id.")";
$arrayalready = mysqli_fetch_all(mysqli_query($db, $queryalready), MYSQLI_ASSOC); //2D assoc array

//check mentor grade requirement for meeting
$querygradereq = "SELECT mentor_grade_req FROM groups WHERE group_id IN (SELECT group_id FROM meetings WHERE meet_id =" . $meeting_id . ")";
$arraygradereq = mysqli_fetch_array(mysqli_query($db, $querygradereq)); //1D num array

//query for all student mentors that meet the grade requirement AND not in this meeting
$querymentors = "SELECT * FROM students WHERE student_id IN 
	(SELECT student_id FROM (SELECT student_id FROM students WHERE grade >=" . $arraygradereq[0] . ") X 
	WHERE student_id NOT IN ( SELECT mentor_id as student_id FROM enroll2 WHERE meet_id =" .$meeting_id."))"; 
$arraymentors = mysqli_fetch_all(mysqli_query($db, $querymentors), MYSQLI_ASSOC); //2D assoc array


?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<center>
		<table>
			<tr>
				<th>Assigned Mentors</th>
				<th>Available Mentors</th>
			</tr>
			<tr>
          		<td>
          			<?php
          			if(!sizeof($arrayalready)){
		                echo "No mentors in meeting.";
		            }
					foreach($arrayalready as $students){ 

						$query = "SELECT name, email FROM users WHERE id = " . $students['student_id'];
						$array = mysqli_fetch_array(mysqli_query($db, $query)); //1D array  ?>

						<table>
							<tr><td>Name:</td><td><?php echo htmlspecialchars($array[0]);?> </td></tr>
							<tr><td>Email: </td><td><?php echo htmlspecialchars($array[1]);?> </td></tr>
				          	<tr><td>Grade: </td><td><?php echo htmlspecialchars($students['grade']);?> </td></tr>

				          	<td><center>
					    	<form method="get">
					    		<input type="hidden" name="ID" value=<?php echo $id;?>></input>
		                        <input type="hidden" name="MID" value=<?php echo $meeting_id;?>></input>
		                        <button type="submit" formaction="addMentors.php"
		                        		name="remove" value=<?php echo $students['student_id'];?>>Remove
		                        </button>
                      		</form>
				    		</center></td>
			    		</table>
			        <?php } ?>
			    </td>

			    <td>
			    	<?php
          			if(!sizeof($arraymentors)){
		                echo "No mentors to add.";
		            }
		            foreach($arraymentors as $students){ 

						$query = "SELECT name, email FROM users WHERE id = " . $students['student_id'];
						$array = mysqli_fetch_array(mysqli_query($db, $query)); //1D array  ?>

						<table>
							<tr><td>Name:</td><td><?php echo htmlspecialchars($array[0]);?> </td></tr>
							<tr><td>Email: </td><td><?php echo htmlspecialchars($array[1]);?> </td></tr>
				          	<tr><td>Grade: </td><td><?php echo htmlspecialchars($students['grade']);?> </td></tr>

				          	<td><center>
					    	<form method="get">
					    		<input type="hidden" name="ID" value=<?php echo $id;?>></input>
		                        <input type="hidden" name="MID" value=<?php echo $meeting_id;?>></input>
		                        <button type="submit" formaction="addMentors.php"
		                        		name="add" value=<?php echo $students['student_id'];?>>Add
		                        </button>
                      		</form>
				    		</center></td>
			    		</table>
			        <?php } ?>
			    </td>


			</tr>
		</table>
		<form action="manageMeetings.php" method="get">
			<button type="submit" name="ID" value = <?php print $id; ?>>Return</button>
		</form>


		<?php
		if(isset($_POST['addMentor'])){
			//admins can overule the 3 max mentors
			$addMentor = "INSERT INTO mentors(mentor_id) VALUES(" . $_POST['addMentor'] .")";
			$addEnroll2 = "INSERT INTO enroll2(meet_id, mentor_id) VALUES(" .$meeting_id .",".$_POST['addMentor'] .")";
			$result_addMentor = mysqli_query($db, $addMentor);
			$result_addEnroll2 = mysqli_query($db, $addEnroll2);
			if(!$result_addEnroll2){
				echo "Already joined";
			}else{
				$stuname = mysqli_fetch_all(mysqli_query($db, "SELECT email FROM users WHERE id = " . $_POST['addMentor']))[0][0];
				$file = fopen($stuname . ".txt", 'a');
				fwrite($file, $stuname . " has been added as a mentor for Meeting #" . $meeting_id . "\r\n\r\n");
				echo "Add success";
			}
			
		}
		?>
	</center>
</body>
</html>