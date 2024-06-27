<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//echo "i made it to this page, addItem.php";
require('dbConfig.php');

//Get the item to remove from the JSON request
$data = json_decode(file_get_contents('php://input'), true);


// Connect to the database
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
        if (!$con) {
            echo("Failed to connect to the database");
        }

//mysqli_real_escape_string needs an active connection to the database
$id = mysqli_real_escape_string($con, trim($data['categoryID']));


$sqlCat = "DELETE FROM Categories WHERE id = '$id'";
$sqlGro = "DELETE FROM Groceries WHERE category_id = '$id'";
//echo $sql;

// Execute the query
if (mysqli_query($con, $sqlCat) && mysqli_query($con, $sqlGro)) {
    //echo "Message successfully added!";
} else {
    echo "Error" . mysqli_error($conn);
}



// Close the connection
mysqli_close($con);

// After removing the item, return a JSON response
$response = array('status' => 'success', 'message' => 'Category deleted successfully');
echo json_encode($response);


  ?>