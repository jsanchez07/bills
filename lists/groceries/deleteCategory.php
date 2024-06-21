<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//echo "i made it to this page, addItem.php";
require('dbConfig.php');

//Get the item to remove from the JSON request
$data = json_decode(file_get_contents('php://input'), true);
$category = $data['category'];

// Connect to the database
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
        if (!$con) {
            echo("Failed to connect to the database");
        }
$sqlCat = "DELETE FROM Categories WHERE category_name = '$category'";
$sqlGro = "DELETE FROM Groceries WHERE category = '$category'";
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