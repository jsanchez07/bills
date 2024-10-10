<?php
// Database connection
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//echo "i made it to this page, removeItem.php";
require('dbConfig.php');

// Connect to the database
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
        if (!$con) {
            echo("Failed to connect to the database");
        }

// Get the raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Check if the JSON decoding was successful
if (json_last_error() === JSON_ERROR_NONE) {
    // Access the categoryOrderIndexes array
    $categories = $data['categoryOrderIndexes'];
} else {
    echo "Invalid JSON data.";
}

foreach ($categories as $category) {
    $category_id = $category['category_id'];
    $order_index = $category['order_index'];
    
    $sql = "UPDATE Categories SET order_index = $order_index WHERE id = '$category_id'";
    
   // Execute the query
    if (mysqli_query($con, $sql)) {
        //echo "Message successfully added!";
    } else {
        echo "Error" . mysqli_error($con);
    }
}

$con->close();

// After reordering the categories, return a JSON response
$response = array('status' => 'success', 'message' => 'Categories reordered successfully');
echo json_encode($response);

?>