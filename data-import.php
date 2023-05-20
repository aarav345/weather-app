<?php

require_once "process.php";
require_once "function.php";

// selects the value from the table
$result= selectValue($conn,$database,$city);

if ($result->num_rows == 0) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&units=metric&appid=$api";

    // Get data from openweathermap and store in JSON object
    $json_data = file_get_contents($url);
    $response_data = json_decode($json_data);


    // a function to insert value in the database
    insertValue($response_data,$conn,$database,$city);
}
?>