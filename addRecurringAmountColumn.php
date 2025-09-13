<?php
// Script to add recurring_amount column to existing bills table
require_once('session_init.php');

if(!isset($_SESSION['role'])) {
    header("Location: logout.php");
}
if ($_SESSION['role'] == 0){
    header("Location: logout.php");
}

require('dbConfig.php');

$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);

if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}

echo "Connected to database successfully!<br>";

// Add recurring_amount column to main bills table
$sql = "ALTER TABLE `{$_SESSION['db_num']}` ADD COLUMN recurring_amount DECIMAL(10,2) DEFAULT 0.00 AFTER auto_pay";
if (!mysqli_query($con, $sql)) {
    echo "Error adding recurring_amount column: " . mysqli_error($con) . "<br>";
} else {
    echo "Successfully added recurring_amount column to bills table<br>";
}

// Verify the column was added
$result = mysqli_query($con, "DESCRIBE `{$_SESSION['db_num']}`");
echo "<br>Main bills table columns:<br>";
while ($row = mysqli_fetch_array($result)) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")<br>";
}

mysqli_close($con);

echo "<br><br>Database upgrade complete!<br>";
echo "<a href='bills.php'>Back to Bills</a>";
?>
