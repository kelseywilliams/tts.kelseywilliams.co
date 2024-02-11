<?php

session_start();

$config = include("config.php");
// Keep out the weirdos
$method = $_SERVER["REQUEST_METHOD"];
if($method != "post"){
    header("Location: /", 301);
}

// Connect to the mysql database
$mysqli = new mysqli($config["host"], $config["username"], $config["password"], $config["db"]);

// This line indicates invalid credentials and config infor.  Check config.php if this line is triggered.
// A php warning will still be issued if the database is offline.
if ($mysqli->connect_error) {
    echo json_encode("Failed to connect to database.");
    exit();
}

// Get the incoming data from index.html
$data = $_POST["data"];

// Get the date
$date = $_POST["time"];

// Set the charset of the sql server so that we can accept emojis
$mysqli->set_charset("utf8mb4");

// Prepare the sql statement and insert the data into the database
$stmt = $mysqli->prepare("INSERT INTO posts (data, time, post_read) VALUES (?, ?, ?)");
$false = "false";
$stmt->bind_param("sss", $data, $date, $false);
$stmt->execute();
$stmt->close();

$_SESSION["success"] = "Post successfully created!";
header("Location: /", 301);
?>
