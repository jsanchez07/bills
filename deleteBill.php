<?php 

 session_start();
 
 if(!isset($_SESSION['role'])) {
      header("Location: logout.php");
 }
 if ($_SESSION['role'] == null) {
 header("Location: logout.php");
 }
 if ($_SESSION['role'] == 0){
  header("Location: logout.php");
 }
 

require('dbConfig.php');

/*$localhost="anaRoot.db.10947084.hostedresource.com";
$username="anaRoot";
$password="Incubus1!";
$database="anaRoot";
*/
$global_dbh = mysqli_connect($localhost, $DBusername, $DBpassword, $database) 
or die("Could not connect to database"); 
//mysqli_select_db($database, $global_dbh) 
//or die("Could not select database"); 




function display_db_query($query_string, $connection, $header_bool, $table_params) { 
    // perform the database query 
    $result_id = mysqli_query($connection, $query_string) 
    or die("display_db_query:" . mysqli_error()); 
    // find out the number of columns in result 
    $column_count = mysqli_num_fields($result_id) 
    or die("display_db_query:" . mysqli_error()); 
    // Here the table attributes from the $table_params variable are added 
    print("<TABLE $table_params >\n"); 
    // optionally print a bold header at top of table 
    if($header_bool) { 
        print("<TR>"); 
     
		print("<TH>Store</TH><TH>Due On</TH><TH>Last Payment Date</TH><TH>Last Payment Amount</TH>");
        print("</TR>\n"); 
    } 
   
 
//print_r (mysql_fetch_row($result_id));
 
   // print the body of the table 
    while($row = mysqli_fetch_row($result_id)) { 
      //  print_r ($row);
        print("<TR ALIGN=LEFT VALIGN=TOP>"); 
        for($column_num = 0; $column_num < $column_count; $column_num++) { 
            print("<TD>$row[$column_num]</TD>\n"); ?>
     
  <?php    } 
		
		print(" <td><input type = 'checkbox' name = 'rowid[]' value = '$row[0]' /></td>");
        
       // print($rowid[0]);
        //print ($row[2]);
		print("</TR>\n");
		 
    } 
	print("</TABLE>\n");  
	//print($column_count);
	
} 

function display_db_table($tablename, $connection, $header_bool, $table_params) { 
    $query_string = "SELECT store, due_on, last_payment, last_amount  FROM $tablename ORDER by store ASC;"; 
    display_db_query($query_string, $connection, 
    $header_bool, $table_params); 
	}
	
function removeSelected(){




} //end removeSelected()

 
?> 
<HTML>
<HEAD>
<style type="text/css">

body{ background-image: url(Gray-background.gif);
      background-repeat: no repeat;
      background-size: 100%;
      background-color: #0000fa}
p{ color:white;
   }
li{color:white;}
</style>

<TITLE>Removing a bill from database</TITLE></HEAD> 
<BODY> 
 <form id = "cbs" name = "form1" Method = "POST" action= "confirmDel.php">
 
<?php 
//database to connect to
$table = "`{$_SESSION['db_num']}`";


print("<a href = 'bills.php'>Back to Bills</a>"); 
display_db_table($table, $global_dbh, 
TRUE, "border='1' style='background-color:#F44747;'"); 
 
removeSelected();
?> 

</TD></TR>

</TABLE>
<br />
<table width = "53%" border ="0">
<tr><td><input type = "submit" name = "remove" value = "remove"/></td></tr>
</table>
</form>

