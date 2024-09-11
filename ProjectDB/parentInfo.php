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

      $query = 'SELECT student_id FROM students WHERE parent_id=' . $id . ';';
      $result = mysqli_fetch_all(mysqli_query($db, $query));
    }
  }
  else{
    header("Location: index.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Parent Information</title>
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

      </table>
      </br></br>
      <table>
        <tr><td colspan=3><center><h2>Student Information</h2></center></td></tr>
        <tr>
        <?php
          if(!sizeof($result)){
            echo "<td>You have no students registered.</td>";
          }
          $x = 0;
          foreach ($result as $student){
            if($x % 3 == 0 && $x){echo "</tr><tr>";}
            $student = $student[0];
            $query = 'SELECT email, name, phone FROM users WHERE id=' . $student . ';';
            $result = mysqli_fetch_array(mysqli_query($db, $query));
            $student_email = $result[0];
            $student_name = $result[1];
            $student_phone = $result[2];

            $query = 'SELECT grade FROM students WHERE student_id=' . $student . ';';
            $result = mysqli_fetch_array(mysqli_query($db, $query));
            $student_grade = $result[0];
            ?>
            <td>
              <table>
                <tr>
                  <td>Name: </td>
                  <td colspan=2><?php echo $student_name;?></td>
                </tr>

                <tr>
                  <td>Grade: </td>
                  <td colspan=2><?php echo $student_grade;?></td>
                </tr>

                <tr>
                  <td>Email: </td>
                  <td><?php echo $student_email;?></td>
                  <td><form>
                    <button type="submit" formaction="editAccount.php" name="email">Edit Email</button>
                    <input type="hidden" name="ID" value=<?php echo $student;?>></input>
                    <input type="hidden" name="return_ID" value=<?php echo $id;?>></input>
                  </form></td>
                </tr>

                <tr>
                  <td>Phone: </td>
                  <td><?php echo $student_phone;?></td>
                  <td><form>
                    <button type="submit" formaction="editAccount.php" name="phone">Edit Phone</button>
                    <input type="hidden" name="ID" value=<?php echo $student;?>></input>
                    <input type="hidden" name="return_ID" value=<?php echo $id;?>></input>
                  </form></td>
                </tr>

                <tr>
                  <td colspan=2>Password: </td>
                  <td><form>
                    <button type="submit" formaction="editAccount.php" name="password">Edit Password</button>
                    <input type="hidden" name="ID" value=<?php echo $student;?>></input>
                    <input type="hidden" name="return_ID" value=<?php echo $id;?>></input>
                  </form></td>
                </tr>

                <tr>
                  <td>Meetings: </td>
                  <td><form>
                    <button type="submit" formaction="studentJLMeetings.php" name="ID" value=<?php echo $student;?>>Join/Leave Meetings</button>
                    <input type="hidden" name="return_ID" value=<?php echo $id;?>></input>
                  </form></td>
                  <td><form>
                    <button type="submit" formaction="studentMeetings.php" name="ID" value=<?php echo $student;?>>View Meetings</button>
                    <input type="hidden" name="return_ID" value=<?php echo $id;?>></input>
                  </form></td>
                </tr>

                <tr>
                  <td colspan=3><form><center>
                    <button type="submit" formaction="viewMaterials.php" name="ID" value=<?php echo $student;?>>View Study Materials</button>
                    <input type="hidden" name="return_ID" value=<?php echo $id;?>></input>
                  </center></form></td>
                </tr>
              </table>
            </td>
            <?php
            ++$x;
          }
        ?>
        </tr>
      </table>
      <br><br>
      <form>
        <button type="submit" formaction="index.php">Return to Login</button>
      </form>
    </center>
  </body>
</html>
