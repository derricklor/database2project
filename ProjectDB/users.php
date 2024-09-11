<?php
 
 // make a include file that contains this connection.
  $myconn = mysqli_connect('localhost', 'root', '', 'user');
  //check connection
  if(!$myconn){
   echo 'Could not connect: ' . mysqli_connect_error();
  }

  //$mydb = mysqli_select_db ($myconn, 'user') or die ('Could not select database');

  $query = 'SELECT * FROM users order by id';
  $result = mysqli_query($myconn, $query);
  

  //echo 'Name &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ID<br>';

/*
  while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) {    
    echo $row["name"];
    echo "&nbsp;&nbsp;&nbsp;";
    echo $row["id"];
    echo '<br>';
  }*/

  /*
  <div style="text-align: left">
    <?php
      foreach ($array as $user) {
        echo htmlspecialchars($user['id']);
        echo str_repeat('&nbsp;', 5);
        echo htmlspecialchars($user['email']);
        echo str_repeat('&nbsp;', 5);
        echo htmlspecialchars($user['password']);
        echo str_repeat('&nbsp;', 5);
        echo htmlspecialchars($user['name']);
        echo str_repeat('&nbsp;', 5);
        echo htmlspecialchars($user['phone']);
        echo '<br>';
      }
    ?>
    </div>
  */

  //array of arrays
  $array = mysqli_fetch_all($result, MYSQLI_ASSOC);

  
  mysqli_free_result($result);
  mysqli_close($myconn);

?>

<!DOCTYPE html>
<html>
<body>
  <h1>List of user info in ascending order of id</h1>
  <table> <!--Create a table for formatting data-->
    <tr>  <!--Create a row with all the header names-->
      <th>ID</th>
      <th>Email</th>
      <th>Password</th>
      <th>Name</th>
      <th>Phone</th>
    </tr>

    <?php foreach ($array as $user){ ?> <!--For each array, print a row with data-->
      <tr>
        <td><?php echo htmlspecialchars($user['id']);?></td>
        <td><?php echo htmlspecialchars($user['email']);?></td>
        <td><?php echo htmlspecialchars($user['password']);?></td>
        <td><?php echo htmlspecialchars($user['name']);?></td>
        <td><?php echo htmlspecialchars($user['phone']);?></td>
      </tr> 
    <?php } ?>  <!--end of the foreach-->
  </table>

    
</body>
</html>