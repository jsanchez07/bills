<?php 
 session_start();
 
 if(!isset($_SESSION['role'])) {
     header("Location: logout.php");
     exit();
 }
 if ($_SESSION['role'] == null) {
header("Location: logout.php");
exit();
}
if ($_SESSION['role'] == 0){
 header("Location: logout.php");
 exit();
}
 
require('dbConfig.php');


$bill = $_GET["billName"];
$_SESSION['billName'] = $bill;

$table ="`finances{$_SESSION['db_num']}`";

$connection = mysql_connect($localhost, $DBusername, $DBpassword) 
or die("Could not connect to database"); 

mysql_select_db($database, $connection) 
or die("Could not select database"); 

$i = 0;

$query_string = "SELECT payment FROM $table where store = '$bill'";
					
$result_id = mysql_query($query_string, $connection) 
    or die("display_db_query:" . mysql_error()); 
    
$rows = mysql_num_rows($result_id) 
    or die("display_db_query:" . mysql_error());    
if ($rows > 1 )
echo "There is an error, there are more than one results";
 
 else 
   {
         while ($i<$rows)
  		{
		$payment=mysql_result($result_id,$i,"payment");
   			if($payment == '0')
   			{
   		 	 $sql="Update $table set payment ='1' where store = '$bill' ";
   			}
   		    else
   		   	{
   		     $sql="Update $table set payment ='0' where store = '$bill' ";
   			}
   		
   		if (!mysql_query($sql,$connection))
 				 {
 					 die('Error: ' . mysql_error());
 				 }
   		$i++;
  		 }
   
  // echo $payment;
   header("Location: finances.php");
   
   }










?>