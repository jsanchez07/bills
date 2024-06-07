<?php
require('dbConfig.php');

$data = json_decode(file_get_contents('php://input'), true);
$item = $data['item'];

$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
        if (!$con) {
            // Handle error
        }
$sql = "DELETE FROM Groceries WHERE item = '$item'";

mysqli_query($con, $sql);
    if (!mysqli_query($con, $sql))
        {
        die('Error in this one:' . mysql_error());
        }



  ?>