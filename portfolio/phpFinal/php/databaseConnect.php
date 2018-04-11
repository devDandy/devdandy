<?php

$serverName = "localhost";
$username = "devdude";
$password = "devdude";
$database = "wdv341";


// $serverName = "localhost";
// $username = "devdan37_dan";
// $password = "SuperSmoothCookies33435";
// $database = "devdan37_wdv341";
try {
    $conn = new PDO("mysql:host=$serverName;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>

