<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require('dbConfig.php');

$con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);
if(!$con){
    echo json_encode(array('success' => false, 'message' => 'Failed to connect to the database'));
    exit;
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

$categoryID = $data['categoryID'];
$itemIDs = $data['itemIDs'];

if (empty($itemIDs)) {
    echo json_encode(array('success' => false, 'message' => 'No items to delete'));
    exit;
}

// Get all image URLs before deleting
$placeholders = implode(',', array_fill(0, count($itemIDs), '?'));
$imageQuery = "SELECT image_url FROM Groceries WHERE id IN ($placeholders)";
$imageStmt = mysqli_prepare($con, $imageQuery);

if ($imageStmt) {
    $types = str_repeat('s', count($itemIDs));
    mysqli_stmt_bind_param($imageStmt, $types, ...$itemIDs);
    mysqli_stmt_execute($imageStmt);
    $imageResult = mysqli_stmt_get_result($imageStmt);
    
    // Delete all image files
    while ($row = mysqli_fetch_assoc($imageResult)) {
        if (!empty($row['image_url']) && file_exists($row['image_url'])) {
            unlink($row['image_url']);
        }
    }
    mysqli_stmt_close($imageStmt);
}

// Prepare the SQL statement to delete items
$sql = "DELETE FROM Groceries WHERE id IN ($placeholders)";
$stmt = mysqli_prepare($con, $sql);

if ($stmt) {
    // Bind the parameters
    $types = str_repeat('s', count($itemIDs));
    mysqli_stmt_bind_param($stmt, $types, ...$itemIDs);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(array('success' => true, 'message' => 'All items deleted successfully', 'deletedCount' => mysqli_stmt_affected_rows($stmt)));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Failed to delete items: ' . mysqli_error($con)));
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to prepare statement: ' . mysqli_error($con)));
}

mysqli_close($con);
?>

