<?php
  if(isset($_GET['ID'])){
    $db = mysqli_connect('localhost', 'root', '', 'user');
    if(!$db){
      $error = "Could not connect to Database: " . mysqli_connect_error();
      header("Location: index.php");
    }
    else{
      $id = $_GET['ID'];
      $query = 'SELECT email, name, phone FROM users WHERE id=' . $id . ';';
      $result = mysqli_fetch_array(mysqli_query($db, $query));
      $email = $result[0];
      $name = $result[1];
      $phone = $result[2];

      $query = 'SELECT grade, parent_id FROM students WHERE student_id=' . $id . ';';
      $result = mysqli_fetch_array(mysqli_query($db, $query));
      $grade = $result[0];
      $parent_id = $result[1];

      $query = 'SELECT email FROM users WHERE id=' . $parent_id . ';';
      $result = mysqli_fetch_array(mysqli_query($db, $query));
      $parent_email = $result[0];
    }
  }
  else{
    header("Location: index.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Student Information</title>
  </head>
  <body>
    <center>
    <?php
      echo "<h1>" . $name . "'s Info</h1></br>";
    ?>
    <table>
      <tr>
        <td>Grade: </td>
        <td><?php echo $grade; ?></td>
      </tr>

      <tr>
        <td>Parent Email: </td>
        <td><?php echo $parent_email; ?></td>
      </tr>

      <tr>
        <td>Email: </td>
        <td><?php echo $email; ?></td>
        <td><form>
          <button type="submit" formaction="editAccount.php" name="email">Edit Email</button>
          <input type="hidden" name="ID" value=<?php echo $id; ?>></input>
        </form></td>
      </tr>

      <tr>
        <td>Phone: </td>
        <td><?php echo $phone; ?></td>
        <td><form>
          <button type="submit" formaction="editAccount.php" name="phone">Edit Phone</button>
          <input type="hidden" name="ID" value=<?php echo $id; ?>></input>
        </form></td>
      </tr>

      <tr>
        <td>Password: </td>
        <td></td>
        <td><form>
          <button type="submit" formaction="editAccount.php" name="password">Edit Password</button>
          <input type="hidden" name="ID" value=<?php echo $id; ?>></input>
        </form></td>
      </tr>

      <tr>
        <td>Meetings: </td>
        <td><form><button type="submit" formaction="studentJLMeetings.php" name="ID" value=<?php echo $id; ?>>Join/Leave Meetings</button></form></td>
        <td><form><button type="submit" formaction="studentMeetings.php" name="ID" value=<?php echo $id; ?>>View My Meetings</button></form></td>
      </tr>

      <tr>
        <td colspan="3"><form><center>
          <button type="submit" formaction="viewMaterials.php" name="ID" value=<?php echo $id;?>>View Study Materials</button>
        </center></form></td>
      </tr>
    </table>
    </br>
    <form>
      <button type="submit" formaction="index.php">Return to Login</button>
    </form>
    </center>
  </body>
</html>
