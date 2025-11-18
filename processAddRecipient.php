<?php

//include("<---path to MySql connection file--->"); 
require('dbConfig.php');
/*$localhost="anaRoot.db.10947084.hostedresource.com";
$username="anaRoot";
$password="Incubus1!";
$database="anaRoot";
*/
$con = mysql_connect($localhost,$DBusername,$DBpassword);
@mysql_select_db($database) or die( "Unable to select database");

 $table = $_SESSION['db_num'];
 $bill = $_GET["bill"];
//$_SESSION['bill'] = $bill;


if (isset($_POST)) {
  $name = $_POST['name']; 
  $phone_number = $_POST['phone_number'];
  $carrier = $_POST['carrier'];
  
if($name !='' && $phone_number !='' && $carrier != ''){
$insert = "INSERT INTO messages (name, phone_number, carrier, table_name, bill) VALUES ('$name', '$phone_number', '$carrier', '$table', '$bill')";
        if (!mysql_query($insert,$con))
        {
         die('Error: ' . mysql_error());
        }
        header("Location: alertManager.php");
        exit();
    }
}

?>