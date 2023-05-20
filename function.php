<?php
function databaseExist($db,$conn) {
    // Check if Database Exists
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA
    WHERE SCHEMA_NAME = '$db'";

    $result = $conn->query($query);
    return $result;
}

function createDatabase($result,$conn,$db) {
    
    if(!$result->num_rows){
        // Create Database
        $create_db = "CREATE DATABASE $db;";
        // echo($create_db);
        $conn->query($create_db);
    }

}

function createTable($db,$conn) {
    // Select Database
    $conn->select_db($db);

    // Create Table
    $create_table = "
        CREATE TABLE weather_data(
            ID int primary key auto_increment,
            City varchar(40),
            Temperature float,
            Description varchar(80),
            Humidity float,
            Pressure float,
            Wind_speed float,
            Wind_direction float,
            Icon varchar(20),
            Weather_when datetime
        );
    ";
    $conn->query($create_table);
}

function insertValue($response,$conn,$db,$city) {
    // selects database
    $conn->select_db($db);

    // fetches data from server-api and stores in respective variable
    $icon = $response->weather[0]->icon;
    $temp = $response->main->temp;
    $description = $response->weather[0]->description;
    $pressure = $response->main->pressure;
    $humidity = $response->main->humidity;
    $wind_speed = $response->wind->speed;
    $wind_deg = $response->wind->deg;

    // for inserting values in the table weather_data
    $insert_query = "INSERT IGNORE INTO 
    weather_data(City, Temperature, Description, Humidity, Pressure, Wind_speed, Wind_direction, Icon, Weather_when) 
    VALUES('$city',
    '$temp',
     '$description',
     '$pressure',
     '$humidity',
     '$wind_speed',
     '$wind_deg',
     '$icon',
     now());";

    // Run SQL statement and report errors
    if (!$conn-> query($insert_query)) {
        echo("<h4>SQL error description: " . $conn -> error . "</h4>");
    }

}

// selects value from the table in weather database
function selectValue($conn,$db,$city){
    // selects the database 
    $conn->select_db($db);

    // selects the required field from the table weather_data
    $select_query = "SELECT City, Temperature, Description, Humidity, Pressure, Wind_speed, Wind_direction, Icon, Weather_when
    from weather_data
    where City = '$city'
    AND Weather_when >= DATE_SUB(NOW(), INTERVAL 10 SECOND)
    ORDER BY weather_when DESC limit 1;";


    //uploads the code to the mysql
    $results = $conn->query($select_query);

    //returns the result
    return $results;
}

?>