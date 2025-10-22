<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//echo "i made it to this page, removeItem.php";
require('dbConfig.php');

//Get the item to remove from the JSON request
$data = json_decode(file_get_contents('php://input'), true);

// Connect to the database
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
        if (!$con) {
            echo("Failed to connect to the database");
        }

$itemID = mysqli_real_escape_string($con, $data['itemID']);

// Get the image URL before deleting
$imageQuery = "SELECT image_url FROM Groceries WHERE id = '$itemID'";
$result = mysqli_query($con, $imageQuery);
$row = mysqli_fetch_assoc($result);

// Delete the image file if it exists
if ($row && !empty($row['image_url']) && file_exists($row['image_url'])) {
    unlink($row['image_url']);
}

$sql = "DELETE FROM Groceries WHERE id = '$itemID'";

// Execute the query
if (mysqli_query($con, $sql)) {
    //echo "Message successfully added!";
} else {
    echo "Error" . mysqli_error($con);
}

// Close the connection
mysqli_close($con);

// After removing the item, return a JSON response
$response = array('status' => 'success', 'message' => 'Item removed successfully');
echo json_encode($response);


  ?>