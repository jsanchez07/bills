<?php
require_once('session_init.php');
 
 if(!isset($_SESSION['role'])) {
      header("Location: logout.php");
 }
 if ($_SESSION['role'] == null) {
 header("Location: logout.php");
 }
 if ($_SESSION['role'] == 0){
  header("Location: logout.php");
 
 }
 


//echo"<pre>";
//print_r($_POST);
$checkedBoxes = implode("', '",$_POST['rowid']);
$query = "DELETE FROM `{$_SESSION['db_num']}` where store IN ('$checkedBoxes')";
$recQuery = "UPDATE `rec{$_SESSION['db_num']}` SET deleted = 1 WHERE store IN ('$checkedBoxes')";
$financeQuery = "DELETE FROM `finances{$_SESSION['db_num']}` where store IN ('$checkedBoxes')";

//echo $query;

/*$localhost="anaRoot.db.10947084.hostedresource.com";
$username="anaRoot";
$password="Incubus1!";
$database="anaRoot";
*/
require('dbConfig.php');


$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

//mysql_select_db($database, $con);

if (!mysqli_query($con, $recQuery))
  {
  die('Error: ' . mysql_error());
  }


if (!mysqli_query($con, $query))
  {
  die('Error: ' . mysql_error());
  }
    
if (!mysqli_query($con, $financeQuery))
  {
    die('Error: ' . mysql_error());
  }

echo "Records successfully deleted! ";

mysqli_close($con);
  ?>
<html>
<style type="text/css">

body{ background-image: url(Gray-background.gif);
      background-repeat: no repeat;
      background-size: 100%;
      background-color: #0000fa}
p{ color:white;
   }
li{color:white;}
</style><body> <br /><br /> <a href = 'bills.php'>Back to Bills</a>
<br />
</body></html>