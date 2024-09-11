<?php

  if(isset($_GET['ID'])){
    $db = mysqli_connect('localhost', 'root', '', 'user');
    if(!$db){
      $error = "Could not connect to Database: " . mysqli_connect_error();
      header("Location: index.php");
    }
    else{
      $id = $_GET['ID'];

      if(isset($_GET['make'])){
        $query = 'INSERT INTO material(title, author, type, url, assigned_date, notes) VALUES("' . $_GET['title'] . '", "' . $_GET['author'] . '", "' . $_GET['subject'] . '", "' . $_GET['url'] . '", "' . date("Y-m-d") . '", "' . $_GET['notes'] . '");';
        mysqli_query($db, $query);
      }
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Create Study Material</title>
  </head>
  <body>
    <center>
      <h1>Create Study Material</h1>
      <form>
        <table>
          <tr>
            <td>Title: </td>
            <td><input type="text" name="title" required></input></td>
          </tr>
          <tr>
            <td>Author: </td>
            <td><input type="text" name="author" required></input></td>
          </tr>
          <tr>
            <td>URL: </td>
            <td><input type="text" name="url"></input></td>
          </tr>
          <tr>
            <td>Notes: </td>
            <td><input type="text" name="notes"></input></td>
          </tr>
          <tr>
            <td>Subject: </td>
            <td><input type="text" name="subject" required></input></td>
          </tr>
          <tr>
            <td colspan=2><center><button type="submit" formaction="makeMaterial.php" name="make">Create Material</button></center></td>
          </tr>
        </table>
        <input type="hidden" name="ID" value=<?php echo $id;?>></input>
      </form>

      <form>
        <button type="submit" formaction="adminInfo.php" name="ID" value=<?php echo $id;?>>Return</button>
      </form>

    </center>
  </body>
</html>
