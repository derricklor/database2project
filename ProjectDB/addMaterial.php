<?php
$ownedMat;
  if(isset($_GET['ID']) && isset($_GET['MID'])){
    $db = mysqli_connect('localhost', 'root', '', 'user');
    if(!$db){
      $error = "Could not connect to Database: " . mysqli_connect_error();
      header("Location: index.php");
    }
    else{

      $id = $_GET['ID'];
      $meeting = $_GET['MID'];

      if(isset($_GET['remove'])){
        $query = "DELETE FROM assign WHERE meet_id=" . $meeting . " AND material_id=" . $_GET['remove'] . ';';
        mysqli_query($db, $query);
      }
      elseif(isset($_GET['add'])){
        $query = "INSERT INTO assign(meet_id, material_id) VALUES (" . $meeting . ", " . $_GET['add'] . ");";
        mysqli_query($db, $query);
      }

      $query = 'SELECT material_id FROM assign WHERE meet_id=' . $meeting . ';';
      $ownedMat = mysqli_fetch_all(mysqli_query($db, $query));
      $query = 'SELECT material_id FROM material WHERE material_id NOT IN (SELECT material_id FROM assign WHERE meet_id=' . $meeting . ');';
      $unownedMat = mysqli_fetch_all(mysqli_query($db, $query));
    }
  }


?>

<!DOCTYPE html>
<html>
  <head>
    <title>Add/Remove Matierals</title>
  </head>
  <body>
    <center>
      <h1>Add & Remove Materials</h1>
      <table>
        <tr>
          <th>Assigned Materials</th><th/>
          <th>Unassigned Materials</th>
        </tr>
        <tr>
          <td>
            <?php
              if(!sizeof($ownedMat)){
                echo "No materials in meeting.";
              }
              foreach($ownedMat as $oMat){
                $oMat = $oMat[0];
                $query = 'SELECT title, author, type, url, notes FROM material WHERE material_id=' . $oMat . ';';
                $result = mysqli_fetch_all(mysqli_query($db, $query));
                ?>
                <table>
                  <tr>
                    <td>Title: </td>
                    <td><?php echo $result[0][0];?></td>
                  </tr>
                  <tr>
                    <td>Author: </td>
                    <td><?php echo $result[0][1];?></td>
                  </tr>
                  <tr>
                    <td>Subject: </td>
                    <td><?php echo $result[0][2];?></td>
                  </tr>
                  <tr>
                    <td>URL: </td>
                    <td><?php echo $result[0][3];?></td>
                  </tr>
                  <tr>
                    <td>Notes: </td>
                    <td><?php echo $result[0][4];?></td>
                  </tr>
                  <tr>
                  <td/>
                    <td>
                      <form>
                        <button type="submit" formaction="addMaterial.php" name="remove" value=<?php echo $oMat;?>>Remove</button>
                        <input type="hidden" name="ID" value=<?php echo $id;?>></input>
                        <input type="hidden" name="MID" value=<?php echo $meeting;?>></input>
                        <?php
                          if(isset($_GET['return_ID'])){
                              $return_id = $_GET['return_ID'];
                              ?>
                              <input type="hidden" name="return_ID" value=<?php echo $return_id;?>></input>
                              <?php
                          }
                          ?>
                      </form>
                    </td>
                  </tr>
                </table>
                <?php
              }
            ?>
          </td>
          <td/>
          <td>
            <?php
              if(!sizeof($unownedMat)){
                echo "No materials to add.";
              }
              foreach($unownedMat as $uMat){
                $uMat = $uMat[0];
                $query = 'SELECT title, author, type, url, notes FROM material WHERE material_id=' . $uMat . ';';
                $result = mysqli_fetch_all(mysqli_query($db, $query));
                ?>
                <table>
                  <tr>
                    <td>Title: </td>
                    <td><?php echo $result[0][0];?></td>
                  </tr>
                  <tr>
                    <td>Author: </td>
                    <td><?php echo $result[0][1];?></td>
                  </tr>
                  <tr>
                    <td>Subject: </td>
                    <td><?php echo $result[0][2];?></td>
                  </tr>
                  <tr>
                    <td>URL: </td>
                    <td><?php echo $result[0][3];?></td>
                  </tr>
                  <tr>
                    <td>Notes: </td>
                    <td><?php echo $result[0][4];?></td>
                  </tr>
                  <tr>
                    <td/>
                    <td>
                      <form>
                        <button type="submit" formaction="addMaterial.php" name="add" value=<?php echo $uMat;?>>Add</button>
                        <input type="hidden" name="ID" value=<?php echo $id;?>></input>
                        <input type="hidden" name="MID" value=<?php echo $meeting;?>></input>
                        <?php
                          if(isset($_GET['return_ID'])){
                              $return_id = $_GET['return_ID'];
                            ?>
                              <input type="hidden" name="return_ID" value=<?php echo $return_id;?>></input>
                            <?php
                          }
                        ?>
                      </form>
                    </td>
                  </tr>
                </table>
                <?php
              }
            ?>
          </td>
        </tr>
      </table>
      <form>
        <button type="submit" formaction="manageMeetings.php" name="ID" value=<?php echo $id;?>>Return</button>
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
