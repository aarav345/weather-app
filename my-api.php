<?php

require_once "process.php";
require_once "function.php";

// for solving CORS error
header("Access-Control-Allow-Origin: *");

//establishes connection with the database
$conn = new mysqli($servername,$username,$password);

// checks for connection error
if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conn -> connect_error;
}

// checks whether a database exist or not
$result = databaseExist($database,$conn);

// creates a database
createDatabase($result,$conn,$database);

// creates a table named weather_data
createTable($database,$conn);


include("data-import.php");

// selects the value from the table
$result_select= selectValue($conn,$database,$city);

// Get data, convert to JSON and print
$row = $result_select->fetch_assoc();
print(json_encode($row));

// Free result set and close connection
$result_select-> free_result();
?>