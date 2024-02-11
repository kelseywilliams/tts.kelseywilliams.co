<?php
session_start();

if(isset($_SESSION["success"])){
    echo $_SESSION["success"];
    unset($_SESSION["success"]);
    exit();
}
else{
    echo "empty";
    exit();
}
?>