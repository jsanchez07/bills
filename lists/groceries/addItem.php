<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require('dbConfig.php');

//Get the item to remove from the JSON request
$data = json_decode(file_get_contents('php://input'), true);

// Connect to the database
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
        if (!$con) {
            echo("Failed to connect to the database");
        }
//these need an active $con to database
$item = mysqli_real_escape_string($con, trim($data['item']));
$category = mysqli_real_escape_string($con, trim($data['categoryName']));
$id = mysqli_real_escape_string($con, trim($data['id']));
$categoryID = mysqli_real_escape_string($con, trim($data['categoryID']));


$sql = "INSERT INTO Groceries (isChecked, item, category, id, category_id) VALUES (0, '$item', '$category', '$id', '$categoryID')";

// Execute the query
if (mysqli_query($con, $sql)) {
    //echo "Message successfully added!";
} else {
    echo "Error" . mysqli_error($conn);
}

// Close the connection
mysqli_close($con);

// After removing the item, return a JSON response
$response = array('status' => 'success', 'message' => 'Item added successfully');
echo json_encode($response);


  ?>