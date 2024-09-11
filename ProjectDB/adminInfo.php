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
    }
  }
  else{
    header("Location: index.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Admin Information</title>
  </head>
  <body>
    <center>
      <h1><?php echo $name . "'s Info";?></h1></br>
      <table>
        <tr>
          <td>Name: </td>
          <td colspan=2><?php echo $name;?></td>
        </tr>

        <tr>
          <td>Email: </td>
          <td><?php echo $email;?></td>
          <td><form>
            <button type="submit" formaction="editAccount.php" name="email">Edit Email</button>
            <input type="hidden" name="ID" value=<?php echo $id;?>></input>
          </form></td>
        </tr>

        <tr>
          <td>Phone: </td>
          <td><?php echo $phone;?></td>
          <td><form>
            <button type="submit" formaction="editAccount.php" name="phone">Edit Phone</button>
            <input type="hidden" name="ID" value=<?php echo $id;?>></input>
          </form></td>
        </tr>

        <tr>
          <td colspan=2>Password: </td>
          <td><form>
            <button type="submit" formaction="editAccount.php" name="password">Edit Password</button>
            <input type="hidden" name="ID" value=<?php echo $id;?>></input>
          </form></td>
        </tr>

        <tr>
          <td>Meetings: </td>
          <td><form>
            <button type="submit" formaction="manageMeetings.php" name="ID" value=<?php echo $id;?>>View Meetings</button>
          </form></td>
          <td><form action="createMeeting.php">
            <button type="submit" name="ID" value=<?php echo $id;?>>Create Meeting</button>
          </form></td>
        </tr>

        <tr>
          <td colspan=3><form action="makeMaterial.php">
            <center><button type="submit" name="ID" value=<?php echo $id;?>>Create Study Material</button></center>
          </td></form>
        </tr>
      </table>
      <br>
      <form>
        <button type="submit" formaction="index.php">Return to Login</button>
      </form>
    </center>
  </body>
</html>
