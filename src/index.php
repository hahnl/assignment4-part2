<?php
  error_reporting(E_ALL);
  ini_set('display_errors','On');

  include 'storage.php';

  $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

  if (!$mysqli || $mysqli->connect_errno) {
    echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Larissa Hahn - Assignment 4, Part 2 - OSU CS290</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <form class="database-form" method="POST" action="action.php">
    <h3> Add Movie to Video Database: </h3>
    <label>(Required) Name: <input type="text" name="name" maxlength="255"></label><br><br>
    <label>Category: <input type="text" name="category" maxlength="255"></label><br><br>
    <label>Movie Length: <input type="number" min="1" max="400" name="length"></label><br><br>
    <input type="submit" value="Add Video" name="add">
  </form>
  <br>
  <h2> Video Database: </h2>
  <table class="header-table">
  <tr><td>
  <form class="database-form" method="POST" action="action.php">
    <label><input type="submit" value="Delete All Videos" name="clrdatabase"></label>
  </form>
  <?php
    if (!isset($_POST["categories"])) {
      $filter = 'All Movies';
    }
    else {
      $filter = $_POST["categories"];
    }
  ?></td>
  <td>
  <form class="database-form" method="POST" action="index.php">
    <label>Filter: <select name="categories">
      <option value="All Movies">All Movies</option>
      <?php
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if (!$mysqli || $mysqli->connect_errno) {
          echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
        }

        $display_categories = "SELECT DISTINCT category FROM videodb";

        if ($all = $mysqli->query($display_categories)) {
          while ($row = $all->fetch_row()) {
            echo '<option name="categories" value="'.$row[0].'">'.$row[0].'</option>';
          }
        }

        $all->close();
      ?>
    </select></label>
    <input type="submit" value="Filter Movies"><br>
  </form></td>
 </table>
  <table class="main-table">
    <tr>
      <td><h3>Name</h3></td><td><h3>Category</h3></td><td><h3>Length</h3></td><td><h3>Status</h3></td><td><h3>Remove?</h3></td>
    </tr>
  <?php
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connection to MySQLi Session(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    if ($filter != 'All Movies') {
      $filtering = "SELECT id, name, category, length, rented FROM videodb WHERE category = '".$filter."'";
    }
    else{
      $filtering = "SELECT id, name, category, length, rented FROM videodb";
    }

    $dbTable = $mysqli->query($filtering);
    if ($dbTable->num_rows > 0) {
      while ($row = $dbTable->fetch_row()) {
        if ($row[4] === '1') {
          $status = 'Checked Out';
        }
        elseif ($row[4] === '0') {
          $status = 'Checked In';
        }
        $idNum = $row[0];
        echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td>";
        if ($row[4] === '0') {
          echo "<td>".$status."<form action='action.php' method='POST'><input type='hidden' name='id' value='$idNum'><input type='submit' name='check-out' value='Check Out'></form></td>";
        }
        elseif ($row[4] === '1'){
          echo "<td>".$status."<form action='action.php' method='POST'><input type='hidden' name='id' value='$idNum'><input type='submit' name='check-in' value='Check In'></form></td>";
        }
        echo "<form action='action.php' method='POST'><input type='hidden' name='id' value='$idNum'><td><input type='submit' name='remove' value='Remove'></form></td>";
      }
    }
  ?>
  </table>
  <br><br>
</body>
</html>
