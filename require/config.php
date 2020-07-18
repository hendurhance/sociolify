<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "sociolify";


// Connect to database
$connectdb = new mysqli($server, $username, $password, $database);

// Check if is it connected successfully

if ($connectdb->connect_error) {
    die("Connection failed" . $connectdb->connect_error);
}


