<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//echo "i made it to this page, removeItem.php";
require('dbConfig.php');

$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Check if the JSON decoding was successful
if (json_last_error() === JSON_ERROR_NONE) {
    // Access the categoryOrderIndexes array
    $items = $data['itemsWithIndex'];
} else {
    echo "Invalid JSON data.";
}


// Connect to the database
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
    if (!$con) {
        echo json_encode(["status" => "error", "message" => "Failed to connect to the database"]);
        exit;
    }


foreach ($items as $item) {
    $itemID = mysqli_real_escape_string($con, $item['id']);
    $index = mysqli_real_escape_string($con, $item['index']);
    $sql = "UPDATE Groceries SET order_index = '$index' WHERE id = '$itemID'";

    // Execute the query
    if (mysqli_query($con, $sql)) {
        //$response["updatedItems"][] = ["id" => $itemID, "index" => $index];
    } else {
        //$response["errors"][] = ["id" => $itemID, "error" => mysqli_error($con)];
    }
}



/*$sql = "UPDATE Groceries WHERE id = '$itemID'";

// Execute the query
if (mysqli_query($con, $sql)) {
    //echo "Message successfully added!";
} else {
    echo "Error" . mysqli_error($con);
}
*/
// Close the connection
mysqli_close($con);

// After removing the item, return a JSON response
//$response = array('status' => 'success', 'message' => 'Items updated successfully');
//echo json_encode($response);


  ?>