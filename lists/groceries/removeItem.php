<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require('dbConfig.php');

//Get the item to remove from the JSON request
$data = json_decode(file_get_contents('php://input'), true);
$item = $data['item'];
console.log($item);

// Connect to the database
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
        if (!$con) {
            // Handle error
        }
$sql = "DELETE FROM Groceries WHERE item = '$item'";

// Execute the query
mysqli_execute_query($con, $sql);
    if (!mysqli_query($con, $sql))
        {
        die('Error in this one:' . mysql_error());
        }
// Close the connection
mysqli_close($con);

// After removing the item, return a JSON response
$response = array('status' => 'success', 'message' => 'Item removed successfully');
echo json_encode($response);


  ?>