<?php
  error_reporting(E_ALL);
  ini_set('display_errors','On');

  $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

  if (!$mysqli || $mysqli->connect_errno) {
    echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
  }

  if (isset($_POST["clrdatabase"])) {
    clearDatabase();
  }
  if (isset($_POST["add"])) {
    addToDatabase();
  }
  if (isset($_POST["check-out"])) {
    checkOutVideo();
  }
  if (isset($_POST["check-in"])) {
    checkInVideo();
  }
  if (isset($_POST["remove"])) {
    removeVideo();
  }

  function clearDatabase() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $clear = $mysqli->prepare("TRUNCATE videodb");
    $clear->execute();
    $clear->close();
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
  }

  function addToDatabase() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $name = $_POST["name"];
    $category = $_POST["category"];
    $length = $_POST["length"];

    if ($name == NULL) {
      echo "The name field is a required field and must be unique.";
      echo "<meta http-equiv=\"refresh\" content=\"2;URL=index.php\">";
      exit(1);
    }

    if(!($adding = $mysqli->prepare("INSERT INTO videodb (name, category, length) VALUES (?,?,?)"))) {
      echo "Prepare failed.";
    }

    if (!$adding->bind_param("ssi", $name, $category, $length)) {
      echo "Binding parameters failed.";
    }

    if (!$adding->execute()) {
      echo "Execute failed.";
    }

    echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
  }

  function checkOutVideo() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $id = $_POST["id"];
    $checkOut = $mysqli->prepare("UPDATE videodb SET rented = 1 WHERE id = ?");
    $checkOut->bind_param("i", $id);
    $checkOut->execute();
    $checkOut->close();
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
  }

  function checkInVideo() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $id = $_POST["id"];
    $checkIn = $mysqli->prepare("UPDATE videodb SET rented = 0 WHERE id = ?");
    $checkIn->bind_param("i", $id);
    $checkIn->execute();
    $checkIn->close();
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
  }

  function removeVideo() {
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "hahnl-db", "3C15z4js2nneWpks", "hahnl-db");

    if (!$mysqli || $mysqli->connect_errno) {
      echo "Error connecting to MySQLi Session:(".$mysqli->connect_errno."): ".$mysqli->connect_error;
    }

    $id = $_POST["id"];
    $remove = $mysqli->prepare("DELETE FROM videodb WHERE id = ?");
    $remove->bind_param("i", $id);
    $remove->execute();
    $remove->close();
    echo "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
  }
?>
