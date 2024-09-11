<?php

//contains the code to establish connection to var $db
include 'dbconnection.php';
  
//set the return ID and return php page
include 'returnCheck.php';
$rid = $id;
$sid = $_GET['ID'];
//if ID is admin display all meetings
//check the current date before thursday midnight against the meeting time slot.

  
$query = 'SELECT * from meetings WHERE meetings.meet_id IN (SELECT meet_id FROM enroll WHERE mentee_id = ' . $sid . ")";
$query2 = 'SELECT * from meetings WHERE meetings.meet_id IN (SELECT meet_id FROM enroll2 WHERE mentor_id = ' . $sid . ")";
$result = mysqli_query($db, $query);
$result2 = mysqli_query($db, $query2);
if(!$result) echo 'Query1 failed:';
if(!$result2) echo 'Query2 failed:';

//array of arrays
$array = mysqli_fetch_all($result, MYSQLI_BOTH);
$array2 = mysqli_fetch_all($result2, MYSQLI_BOTH);

mysqli_free_result($result);
mysqli_free_result($result2);


?>

<!DOCTYPE html>
<html>
<body>
  <center>
    <h1>
      <?php 
      $query_name = 'SELECT name FROM users WHERE id = '. $sid ;
      $array_name = mysqli_fetch_array(mysqli_query($db, $query_name));
      echo $array_name[0];
      ?>'s Meetings
    </h1>

    <table>
        <td><table style="display: inline-block;">

            <th> Meetings you are a mentee in:</th>
            <?php if (!sizeof($array)){ ?>
                <tr><td>You are not a mentee for any meetings</td></tr>
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
                      
                    </table></td> 
                    <?php 
                    if ($col % 3 == 0)
                        {print "</tr><tr>";}
                    ++$col;
                }?>
            </tr>
        </table></td>
        <td>


        <table style="display: inline-block;">
          <th>Meetings you are a mentor in:</th>
            <?php if (!sizeof($array2)){ ?>
                    <tr><td>You are not mentoring any meetings</td></tr>
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
                        
                        <tr><td colspan="2">
                          <form action="meetingInfo.php" method="get">
                            <input type="hidden" name="return_ID" value=<?php echo $rid;?> >
                            <input type="hidden" name="ID" value=<?php echo $sid;?>>
                            <input type="hidden" name="meeting_id" value=<?php echo $meeting2['meet_id'];?>>
                            <center><button type="submit" >Meeting Info</button></center>
                          </form>
                        </td></tr>

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
    <form action=<?php print $return;?> method="get">
      <button type="submit" name="ID" value = <?php print $rid; ?>>Return to Account</button>
    </form>
  </tr>
  </center>
    
</body>
</html>