<?php

/*Use this script to query posts.  Use the post request to alter the read status of posts and to query posts read and unread.
/!\ NOTE /!\ 
For POST requests:
    Structure the post request with an optional password key with a value of *password* in order to alter the read status of posts 
    and a filter key with a value of read, unread, or all
    
    Structure the get request with a filter key with a value of read, unread, or all
    
    Data is returned in JSON format*/

$config = include("config.php");

$auth = false;

$method = $_SERVER["REQUEST_METHOD"];

// Connect to the mysql database
$mysqli = new mysqli($config["host"], $config["username"], $config["password"], $config["db"]);

// This line indicates invalid credentials and config infor.  Check config.php if this line is triggered.
// A php warning will still be issued if the database is offline.
if ($mysqli->connect_error) {
    echo json_encode("Failed to connect to database.");
    exit();
}

// Set the charset of the sql server so that we can accept emojis
$mysqli->set_charset("utf8mb4");

if($method == "POST"){
    // If the password key exists, verify that it is the password hashed in the pwds table.  
    //Authentication will allow the user to manipulate the read status of posts
    if(isset($_POST["password"])){
        $password = $_POST["password"];
        $result = $mysqli->query("SELECT hash FROM pwds");
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $auth = password_verify($password, $row["hash"]);
            }
        }
        // If the password key exists, but its value is not valid, prohibit further access and exit the script
        if(!$auth){
            echo json_encode("Invalid password");
            exit();
        }
    }

    // Regardles of whether a user has been authenticated or not, if a filter key exists, return requested data according to filter value
    if(isset($_POST["filter"])){
        if($_POST["filter"] == "read"){
            $result = $mysqli->query("SELECT data, time, post_read FROM posts WHERE post_read = \"true\"");
        }
        else if($_POST["filter"] == "unread"){
            $result = $mysqli->query("SELECT data, time, post_read FROM posts WHERE post_read = \"false\"");
        }
        else if($_POST["filter"] == "all"){
            $result = $mysqli->query("SELECT * FROM posts");
        }
        if($result->num_rows > 0){
            $base_array = array();
            while($row = $result->fetch_assoc()){
                array_push($base_array, array("data"=>$row["data"], "time"=>$row["time"], "post_read"=>$row["post_read"]));
            }
            echo json_encode($base_array, JSON_FORCE_OBJECT);
        }
        // If no filter value has been entered, return an empty json string
        else{
            echo json_encode("{}");
        }
    }
}

if($method == "GET"){
    // If filter key exists, return data according to filter value
    if(isset($_GET["filter"])){
        if($_GET["filter"] == "read"){
            $result = $mysqli->query("SELECT data, time, post_read FROM posts WHERE post_read = \"true\"");
        }
        else if($_GET["filter"] == "unread"){
            $result = $mysqli->query("SELECT data, time, post_read FROM posts WHERE post_read = \"false\"");
        }
        else if($_GET["filter"] == "all"){
            $result = $mysqli->query("SELECT * FROM posts");
        }
        else{
            http_response_code(404);
            exit();
        }
    }
    else if(!isset($_GET["filter"])){
        $result = $mysqli->query("SELECT * FROM posts");
    }
    if($result->num_rows > 0){
        $base_array = array();
        while($row = $result->fetch_assoc()){
            array_push($base_array, array("data"=>$row["data"], "time"=>$row["time"], "post_read"=>$row["post_read"]));
        }
        echo json_encode($base_array, JSON_FORCE_OBJECT);
    }
    else{
        echo json_encode("{}");
    }
}

if($auth){
    $mysqli->query("UPDATE posts SET post_read = \"true\" WHERE post_read = \"false\"");
}

$mysqli->close();

?>