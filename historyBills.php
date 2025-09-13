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
 

//include("<---path to MySql connection file--->"); 
require('dbConfig.php');

$con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);




$sql="SELECT store FROM `{$_SESSION['db_num']}`"; 
$result=mysqli_query($con, $sql); 

$options=""; 

while ($row=mysqli_fetch_array($result)) { 

    $id=$row["store"]; 
   
   $options.="<OPTION VALUE=\"$id\">".$id.'</option>';
} 
?>



<?php
function display_db_query($query_string, $connection, $header_bool, $table_params) { 
    // perform the database query 
    $result_id = mysqli_query($connection, $query_string );
   
    // find out the number of columns in result 
    $column_count = mysqli_num_fields($result_id) ;
 
    // Here the table attributes from the $table_params variable are added 
    print("<TABLE $table_params >\n"); 
    // optionally print a bold header at top of table 
    if($header_bool) { 
        print("<TR>"); 
     
		print("<TH>Store</TH><TH>Due On</TH><TH>Payment Date</TH><TH>Payment Amount</TH><TH>Paid On</TH>");
        print("</TR>\n"); 
    } 
   
 
//print_r (mysql_fetch_row($result_id));
 
   // print the body of the table 
    while($row = mysqli_fetch_row($result_id)) { 
      //  print_r ($row);
        print("<TR ALIGN=CENTER VALIGN=TOP>"); 
        for($column_num = 0; $column_num < $column_count; $column_num++) { 
            print("<TD>$row[$column_num]</TD>\n"); ?>
     
  <?php    } 
		
		
       // print($rowid[0]);
        //print ($row[2]);
		print("</TR>\n");
		 
    } 
	print("</TABLE>\n");  
	//print($column_count);
	
} 

function display_db_table($tablename, $connection, $header_bool, $table_params) { 
    $query_string = "SELECT * FROM `history{$_SESSION['db_num']}` where store = '$_POST[store]'"; 
 // echo $query_string;
  display_db_query($query_string, $connection, 
    $header_bool, $table_params); 
	}
	


 
?> 
<HTML>
<HEAD>
<style type="text/css">

body{ background-image: url(images/Gray-background.gif);
      background-repeat: no repeat;
      background-size: 100%;
      background-color: #0000fa}
p{ color:white;
   }
li{color:white;}
</style>

<TITLE>Removing a bill from database</TITLE></HEAD> 
<BODY> 
 <form id = "cbs" name = "form1" Method = "POST" action= "historyBills.php">
 
<?php 
//database to connect to
$table = "history";


print("<a href = 'bills.php'>Back to Bills</a>"); 
?>

<table border="0">
<tr><td><SELECT NAME=store> 
<OPTION VALUE="<?php $store ?>">Choose</OPTION> 
<?php echo $options?> 
</SELECT> </td>
<td><input type = "submit" name = "getHistory" value = "get history"/></td></tr>
</table>


<?php
display_db_table($table, $con, 
TRUE, "border='1' style='background-color:white;'"); 
 

?> 



</TD></TR>

</TABLE>
<br />
</form>