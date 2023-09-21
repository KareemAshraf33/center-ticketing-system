<?php

// $host = "localhost:4306";
// $username = "root";
// $password = "";
// $dbname = "api-test";

// $conn = new mysqli($host, $username, $password, $dbname);
// if (!$conn) {
//     die("connection failed");
// } else {
//     echo "connection successfully";
// }

$host = "localhost";
$username = "root";
$password = "";
$dbname = "center_ticketing_system";

$conn = new mysqli($host, $username, $password, $dbname);
if (!$conn) {
    die("connection failed");
} else {
    echo "connection successfully";
}

