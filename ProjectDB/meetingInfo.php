<?php
  if(isset($_GET['ID']) & isset($_GET['meeting_id'])){
    $db = mysqli_connect('localhost', 'root', '', 'user');
    if(!$db){
      $error = "Could not connect to Database: " . mysqli_connect_error();
      header("Location: index.php");
    }
    else{
      $id = $_GET['ID'];
      $meeting_id = $_GET['meeting_id'];
      $query = 'SELECT meet_name, date, time_slot_id, group_id FROM meetings WHERE meet_id=' . $meeting_id . ';';
      $result = mysqli_fetch_array(mysqli_query($db, $query));
      $meet_name = $result[0];
      $date = $result[1];
      $time_slot_id = $result[2];
      $group_id = $result[3];

      $query = 'SELECT start_time, end_time FROM time_slot WHERE time_slot_id=' . $time_slot_id . ';';
      $result = mysqli_fetch_array(mysqli_query($db, $query));
      $start_time = $result[0];
      $end_time = $result[1];

      $query = 'SELECT mentee_id FROM enroll WHERE meet_id=' . $meeting_id . ';';
      $mentee_ids = mysqli_fetch_all(mysqli_query($db, $query));

      $query = 'SELECT mentor_id FROM enroll2 WHERE meet_id=' . $meeting_id . ';';
      $mentor_ids = mysqli_fetch_all(mysqli_query($db, $query));
    }
  }
  else{
    header("Location: index.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Meeting Information</title>
  </head>
  <body>
    <center>
      <h1>Meeting Information:</h1>
      <table>
        <tr>
          <td>Subject: </td>
          <td><?php echo $meet_name;?></td>
        </tr>

        <tr>
          <td>Date: </td>
          <td><?php echo $date;?></td>
        </tr>

        <tr>
          <td>Start Time: </td>
          <td><?php echo $start_time;?></td>
        </tr>

        <tr>
          <td>End Time: </td>
          <td><?php echo $end_time;?></td>
        </tr>

        <tr>
          <td>Group: </td>
          <td><?php echo $group_id;?></td>
        </tr>
      </table>
      </br>
      <table>
        <tr><td>
          <table>
            <tr><th colspan=2>Mentees in the Meeting: </br></th></tr>
            <?php
            if(!sizeof($mentee_ids)){
              echo "<tr><td>No Mentees in Meeting.</td></tr>";
            }
            foreach ($mentee_ids as $mentee){
              $mentee = $mentee[0];
              $query = 'SELECT name, email FROM users WHERE id=' . $mentee . ';';
              $result = mysqli_fetch_array(mysqli_query($db, $query));
              $name = $result[0];
              $email = $result[1];

              echo "<tr><td>Name: </td><td>$name</td></tr>";
              echo "<tr><td>Email: </td><td>$email</td></tr>";
              echo "<tr><td><br></td></tr>";
            }
            ?>
          </table>
        </td>
        <td><br></td>
        <td>
          <table>
            <tr><th colspan=2>Mentors in the Meeting: </br></th></tr>
            <?php
            if(!sizeof($mentor_ids)){
              echo "<tr><td>No Mentors in Meeting.</td></tr>";
            }
            foreach ($mentor_ids as $mentor){
              $mentor = $mentor[0];
              $query = 'SELECT name, email FROM users WHERE id=' . $mentor . ';';
              $result = mysqli_fetch_array(mysqli_query($db, $query));
              $name = $result[0];
              $email = $result[1];

              echo "<tr><td>Name: </td><td>$name</td></tr>";
              echo "<tr><td>Email: </td><td>$email</td></tr>";
              echo "<tr><td><br></td></tr>";
            }
            ?>
          </table>
        </td></tr>
      </table>
      <form>
        <button type="submit" formaction="studentMeetings.php" name="ID" value=<?php echo $id;?>>Return to Meetings</button>
        <?php
          if(isset($_GET['return_ID'])){
              $return_id = $_GET['return_ID'];
        ?>
              <input type="hidden" name="return_ID" value=<?php echo $return_id;?>></input>
        <?php
          }
        ?>
      </form>
    </center>
  </body>
</html>
