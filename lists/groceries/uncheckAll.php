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

// Assuming $data['checkedItemsText'] and $data['uncheckedItemsText'] contain arrays of item IDs
//$checkedItems = $data['checkedItemsText'];
$categoryID = $data['categoryID'];


$sql = "UPDATE Groceries SET isChecked = 0 WHERE category = '$categoryID'";
   
if (mysqli_query($con, $sql)) {
    // Uncomment for debugging: echo "Message successfully added for checked item: $itemID\n";
} else {
    echo "Error updating checked item $itemID: " . mysqli_error($con) . "\n";
}


// Close the connection
mysqli_close($con);

// After removing the item, return a JSON response
$response = array('status' => 'success', 'message' => 'All checkboxes unchcked successfully');
echo json_encode($response);
?>