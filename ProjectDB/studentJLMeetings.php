<?php
//contains the code to establish connection to var $db
include 'dbconnection.php';

//set the return ID and return php page
include 'returnCheck.php';
$rid = $id;
$sid = $_GET['ID'];

//get the student grade
$query = 'SELECT grade FROM students WHERE students.student_id =' . $sid;
$result = mysqli_query($db, $query);

if(!$result){ echo 'Query resultgrade failed:';}
$array = mysqli_fetch_array($result, MYSQLI_ASSOC); //1D assoc array
$stuGrade = $array['grade'];

//set new $query and $result 
$query = 'SELECT * FROM meetings WHERE meetings.group_id IN 
(select groups.group_id FROM groups, students WHERE groups.mentee_grade_req = ' . $stuGrade .')';

$query2 = 'SELECT * FROM meetings WHERE meetings.group_id IN 
(select groups.group_id FROM groups, students WHERE groups.mentor_grade_req <= ' . $stuGrade .')';

$result = mysqli_query($db, $query);
	if(!$result) echo 'Query1 failed:';
$result2 = mysqli_query($db, $query2);
	if(!$result2) echo 'Query2 failed:';


//array of arrays - 2D assoc and num array
$array = mysqli_fetch_all($result, MYSQLI_BOTH);
$array2 = mysqli_fetch_all($result2, MYSQLI_BOTH);

//mentees have the ability to join all meetings and leave all meetings, as either mentee or mentor

//free the allocated data
mysqli_free_result($result);
mysqli_free_result($result2);
//mysqli_close($db);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Join/Leave Meetings</title>
</head>
<body>
	<center>
	<h1 > Join/Leave Meetings</h1>

	<table>
		<td><table style="display: inline-block;">
			<th> Meetings you can Mentee in:</th>
			<?php if (!sizeof($array)){ ?>
                <tr><td>No meetings you can join</td></tr>
            <?php }  ?>

            <tr> 
	            <?php
				$col = 1;
				foreach ($array as $meeting){ ?> 
					<td><table style="display: inline-block;">
						<tr><td>Meeting ID:</td><td><?php echo htmlspecialchars($meeting['meet_id']);?> </td></tr>
						<tr><td>Subject: </td><td><?php echo htmlspecialchars($meeting['meet_name']);?> </td></tr>
						<tr><td>Date: </td><td><?php echo htmlspecialchars($meeting['date']);?> </td></tr>
						<?php
						$query_time = "SELECT start_time, end_time FROM time_slot WHERE time_slot_id =".$meeting['time_slot_id'];
            			$array_time = mysqli_fetch_array(mysqli_query($db, $query_time), MYSQLI_ASSOC);
            			?>
						<tr><td>Start Time: </td><td><?php echo htmlspecialchars($array_time['start_time']);?> </td></tr>
						<tr><td>End Time: </td><td><?php echo htmlspecialchars($array_time['end_time']);?> </td></tr>
						<tr><td>Group: </td><td><?php echo htmlspecialchars($meeting['group_id']);?> </td></tr>
					
						<td><center>
			        		<form style="display: inline-block" method="post">
				        		<input type="hidden" name="insertMentee" value=<?php echo $meeting['meet_id']?>>
				        		<button type="submit" >Join</button>
			    			</form>

			    			<form style="display: inline-block" method="post">
				    			<input type="hidden" name="deleteMentee" value=<?php echo $meeting['meet_id']?>>
				        		<button type="submit" >Leave</button>
			    			</form>
	    				</center></td>
					</table></td> 
					 
					<?php 
			  		if ($col % 3 == 0)
						{print "</tr><tr>";}
					++$col;
		  		}?>
	  		</tr>
		</table></td>

			
		<td><table style="display: inline-block;">
			<th> Meetings you can Mentor in:</th>
			<?php if (!sizeof($array2)){ ?>
                <tr><td>No meetings you can join</td></tr>
            <?php }  ?>

            <tr> 
	            <?php
				$col = 1;
				foreach ($array2 as $meeting2){ ?> 
					<td><table style="display: inline-block;">
						<tr><td>Meeting ID:</td><td><?php echo htmlspecialchars($meeting2['meet_id']);?> </td></tr>
						<tr><td>Subject: </td><td><?php echo htmlspecialchars($meeting2['meet_name']);?> </td></tr>
						<tr><td>Date: </td><td><?php echo htmlspecialchars($meeting2['date']);?> </td></tr>
						<?php
						$query_time = "SELECT start_time, end_time FROM time_slot WHERE time_slot_id =".$meeting2['time_slot_id'];
	        			$array_time = mysqli_fetch_array(mysqli_query($db, $query_time), MYSQLI_ASSOC);
	        			?>
						<tr><td>Start Time: </td><td><?php echo htmlspecialchars($array_time['start_time']);?> </td></tr>
						<tr><td>End Time: </td><td><?php echo htmlspecialchars($array_time['end_time']);?> </td></tr>
						<tr><td>Group: </td><td><?php echo htmlspecialchars($meeting2['group_id']);?> </td></tr>
					
						<td><center>
			        		<form style="display: inline-block" method="post">
				        		<input type="hidden" name="insertMentor" value=<?php echo $meeting2['meet_id']?>>
				        		<button type="submit" >Join</button>
			    			</form>

			    			<form style="display: inline-block" method="post">
				    			<input type="hidden" name="deleteMentor" value=<?php echo $meeting2['meet_id']?>>
				        		<button type="submit" >Leave</button>
			    			</form>
	    				</center></td>
					</table></td> 
						 
			  		<?php 
			  		if ($col % 3 == 0)
						{print "</tr><tr>";}
					++$col;
		  		}?>
			</tr>

		</table></td>
	</table>


	<br><br>
	<form style="display: inline-block" method="post">
		<input type="hidden" name="joinAll" value="1" >
		<button type="submit" >Join All Meetings</button>
	</form>
	<br><br>
	<form style="display: inline-block" method="post">
		<input type="hidden" name="leaveAll" value="1" >
		<button type="submit" >Leave All Meetings</button>
	</form>
	<br><br>
	

	<?php

		if(isset($_POST['joinAll'])){
			//join for all mentees
			foreach ($array as $meeting){
				//check if each meeting has reached capacity
				$qcount = "SELECT count(*) FROM enroll WHERE meet_id = " . $meeting['meet_id'];
				$arraycount = mysqli_fetch_array(mysqli_query($db, $qcount)); //1D num array
				$qcapacity = "SELECT capacity FROM meetings WHERE meet_id =" . $meeting['meet_id'];
				$arraycapacity = mysqli_fetch_array(mysqli_query($db, $qcapacity)); //1D num array
				if($arraycount[0] >= $arraycapacity[0])
				{	
					echo "<br> Meeting ". $meeting['meet_id']." is full of mentees";
				}else{
					// duplicate inserts of the same sid are ignored by database
					$insertMentee = "INSERT INTO mentees(mentee_id) VALUES(" . $sid .")";
					$insertEnroll = "INSERT INTO enroll(meet_id, mentee_id) VALUES(" .$meeting['meet_id'] .",".$sid .")";
					$result_insertMentee = mysqli_query($db, $insertMentee);
					$result_insertEnroll = mysqli_query($db, $insertEnroll);
					
				}
			}
			//join for all mentors
			foreach ($array2 as $meeting2){
				//check if each meeting has reached capacity
				$qcount = "SELECT count(*) FROM enroll2 WHERE meet_id = " . $meeting2['meet_id'];
				$arraycount = mysqli_fetch_array(mysqli_query($db, $qcount)); //1D num array
				$qcapacity = "SELECT capacity FROM meetings WHERE meet_id =" . $meeting2['meet_id'];
				$arraycapacity = mysqli_fetch_array(mysqli_query($db, $qcapacity)); //1D num array
				if($arraycount[0] >= $arraycapacity[0])
				{	
					echo "<br> Meeting ". $meeting2['meet_id']." is full of mentors";
				}else{
					// duplicate inserts of the same sid are ignored by database
					$insertMentor = "INSERT INTO mentors(mentor_id) VALUES(" . $sid .")";
					$insertEnroll2 = "INSERT INTO enroll2(meet_id, mentor_id) VALUES(" .$meeting2['meet_id'] .",".$sid .")";
					$result_insertMentor = mysqli_query($db, $insertMentor);
					$result_insertEnroll2 = mysqli_query($db, $insertEnroll2);
					
				}
			}
			echo "<br> Join all success";
		}

		if(isset($_POST['leaveAll'])){
			foreach ($array as $meeting){
				$deleteEnroll = "DELETE FROM enroll WHERE mentee_id = ".$sid." AND meet_id =".$meeting['meet_id'];
				$result_deleteEnroll = mysqli_query($db, $deleteEnroll);
				
				// if count of sid in enroll table is 0 then delete sid from mentee table
				$count = "SELECT count(*) FROM enroll WHERE mentee_id =".$sid;
				$result_count = mysqli_query($db, $count);
				$array_count = mysqli_fetch_array($result_count);
				if($array_count[0] == 0)
				{
					$deleteMentee = "DELETE FROM mentees WHERE mentee_id = " . $sid;
					$result_deleteMentee = mysqli_query($db, $deleteMentee);
				}
			}

			foreach ($array2 as $meeting2){
				$deleteEnroll2 = "DELETE FROM enroll2 WHERE mentor_id = ".$sid." AND meet_id =".$meeting2['meet_id'];
				$result_deleteEnroll2 = mysqli_query($db, $deleteEnroll2);
				
				// if count of sid in enroll2 table is 0 then delete sid from mentor table
				$count = "SELECT count(*) FROM enroll2 WHERE mentor_id =".$sid;
				$result_count = mysqli_query($db, $count);
				$array_count = mysqli_fetch_array($result_count);
				if($array_count[0] == 0)
				{
					$deleteMentor = "DELETE FROM mentors WHERE mentor_id = " . $sid;
					$result_deleteMentor = mysqli_query($db, $deleteMentor);
				}
			}
			echo "<br> Leave all success";
		}

		if(isset($_POST['insertMentee'])){
			//check if meeting has reached capacity 
			$qcount = "SELECT count(*) FROM enroll WHERE meet_id = " . $_POST['insertMentee'];
			$arraycount = mysqli_fetch_array(mysqli_query($db, $qcount)); //1D num array
			$qcapacity = "SELECT capacity FROM meetings WHERE meet_id =" . $_POST['insertMentee'];
			$arraycapacity = mysqli_fetch_array(mysqli_query($db, $qcapacity)); //1D num array
			if($arraycount[0] >= $arraycapacity[0])
			{	//We run into an awkward situation where if you were the last person to join
				//and press the button again, it displays "meeting is full" and not "already joined".
				//The code below fixes that.
				$qcheck = "SELECT count(*) FROM enroll WHERE meet_id =". $_POST['insertMentee']." AND mentee_id =" . $sid;
				$arraycheck = mysqli_fetch_array(mysqli_query($db, $qcheck)); //1D num array
				if ($arraycheck[0] == 1){
					echo "Already joined";
				}else{ echo "Meeting is full of mentees";}
			}else{
				// duplicate inserts of the same sid are ignored by database
				$insertMentee = "INSERT INTO mentees(mentee_id) VALUES(" . $sid .")";
				$insertEnroll = "INSERT INTO enroll(meet_id, mentee_id) VALUES(" .$_POST['insertMentee'] .",".$sid .")";
				$result_insertMentee = mysqli_query($db, $insertMentee);
				$result_insertEnroll = mysqli_query($db, $insertEnroll);
				if(!$result_insertEnroll){
					echo "Already joined";
				}else{
					echo "Join success";
				}
			}
		}

		if(isset($_POST['deleteMentee'])){
			//check if you are already in the meeting
			$qcheck = "SELECT count(*) FROM enroll WHERE meet_id =". $_POST['deleteMentee']." AND mentee_id =" . $sid;
			$arraycheck = mysqli_fetch_array(mysqli_query($db, $qcheck)); //1D num array
			if ($arraycheck[0] == 0){
				echo "You are not joined";
			}else{ 
				$deleteEnroll = "DELETE FROM enroll WHERE mentee_id = ".$sid." AND meet_id =".$_POST['deleteMentee'];
				$result_deleteEnroll = mysqli_query($db, $deleteEnroll);
				if ($result_deleteEnroll)
				echo "Leave success";

				// if count of sid in enroll table is 0 then delete sid from mentee table
				$count = "SELECT count(*) FROM enroll WHERE mentee_id =".$sid;
				$result_count = mysqli_query($db, $count);
				$array_count = mysqli_fetch_array($result_count);
				if($array_count[0] == 0)
				{
					$deleteMentee = "DELETE FROM mentees WHERE mentee_id = " . $sid;
					$result_deleteMentee = mysqli_query($db, $deleteMentee);
				}
			}
		}

		if(isset($_POST['insertMentor'])){
			//max mentors is 3 per meeting
			$qcount = "SELECT count(*) FROM enroll2 WHERE meet_id = " . $_POST['insertMentor'];
			$resultqcount = mysqli_query($db, $qcount);
			$arraycount = mysqli_fetch_array($resultqcount); //1D array
			if($arraycount[0] >= 3)
			{	//We run into an awkward situation where if you were the last person to join
				//and press the button again, it displays "meeting is full" and not "already joined".
				//The code below fixes that.
				$qcheck = "SELECT count(*) FROM enroll2 WHERE meet_id =". $_POST['insertMentor']." AND mentor_id =" . $sid;
				$arraycheck = mysqli_fetch_array(mysqli_query($db, $qcheck)); //1D array
				if ($arraycheck[0] == 1){
					echo "Already joined";
				}else{ echo "Meeting is full of mentors";}
			}else{
				$insertMentor = "INSERT INTO mentors(mentor_id) VALUES(" . $sid .")";
				$insertEnroll2 = "INSERT INTO enroll2(meet_id, mentor_id) VALUES(" .$_POST['insertMentor'] .",".$sid .")";
				$result_insertMentor = mysqli_query($db, $insertMentor);
				$result_insertEnroll2 = mysqli_query($db, $insertEnroll2);
				if(!$result_insertEnroll2){
					echo "Already joined";
				}else{
					echo "Join success";
				}
			}
		}

		if(isset($_POST['deleteMentor'])){
			//check if you are already in the meeting
			$qcheck = "SELECT count(*) FROM enroll2 WHERE meet_id =". $_POST['deleteMentor']." AND mentor_id =" . $sid;
			$arraycheck = mysqli_fetch_array(mysqli_query($db, $qcheck)); //1D array
			if ($arraycheck[0] == 0){
				echo "You are not joined";
			}else{ 
				$deleteEnroll2 = "DELETE FROM enroll2 WHERE mentor_id = ".$sid." AND meet_id =".$_POST['deleteMentor'];
				$result_deleteEnroll2 = mysqli_query($db, $deleteEnroll2);
				if ($result_deleteEnroll2)
				echo "Leave success";

				// if count of sid in enroll2 table is 0 then delete sid from mentor table
				$count2 = "SELECT count(*) FROM enroll2 WHERE mentor_id =".$sid;
				$result_count2 = mysqli_query($db, $count2);
				$array_count2 = mysqli_fetch_array($result_count2);
				if($array_count2[0] == 0)
				{
					$deleteMentor = "DELETE FROM mentors WHERE mentor_id = " . $sid;
					$result_deleteMentor = mysqli_query($db, $deleteMentor);
				}
			}
		}
	?>

	<br><br><br>
	<form action=<?php print $return;?> method="get">
		<button type="submit" name="ID" value = <?php print $rid; ?>>Return to Account</button>
	</form>

	</center>
</body>
</html>

