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
 echo "hello is this working?";
 $table = $_SESSION['db_num'];
 $bill = $_GET['bill'];
 $_SESSION['bill'] = $bill;

echo $table;
//include("<---path to MySql connection file--->"); 


require('dbConfig.php');
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);


if (isset($_POST)) {
  $name = $_POST['name']; 
  $phone_number = $_POST['phone_number'];
  $carrier = $_POST['carrier'];
  
    if($name !='' && $phone_number !='' && $carrier != ''){
        $insert = "INSERT INTO messages (name, phone_number, carrier, table_name, bill) VALUES ('$name', '$phone_number', '$carrier', '$table', '$bill')";
        mysqli_query($con, $insert);
    }
}


$global_dbh = mysqli_connect($localhost, $DBusername, $DBpassword, $database);




$sql="SELECT store FROM `{$_SESSION['db_num']}`"; 
$result=mysqli_query($global_dbh, $sql); 

$options=""; 

while ($row=mysqli_fetch_array($result)) { 

    $id=$row["store"]; 
   
   $options.="<OPTION VALUE=\"$id\">".$id.'</option>';
} 
?>



<?php
function display_db_query($query_string, $connection, $header_bool, $table_params) { 
    // perform the database query 
    $result_id = mysqli_query($connection, $query_string);
  
    // find out the number of columns in result 
    $column_count = mysqli_num_fields($result_id);
  
    // Here the table attributes from the $table_params variable are added 
    print("<TABLE $table_params >\n"); 
    // optionally print a bold header at top of table 
    if($header_bool) { 
        print("<TR>"); 
     
    	print("<TH>Name</TH><TH>Number</TH><TH>Carrier</TH><TH>Bill</TH><TH>Paid</TH>");
        print("</TR>\n"); 
    } 
   
 
//print_r (mysql_fetch_row($result_id));
  $number = 0;
   // print the body of the table 
    while($row = mysqli_fetch_row($result_id)) { 
      //  print_r ($row);
     if($number == 0){
     
     $bill=mysqli_result($result_id,$number,"bill"); 
     $_SESSION['bill'] = $bill;}
    // echo $bill;
        print("<TR ALIGN=CENTER VALIGN=TOP>"); 
        for($column_num = 0; $column_num < $column_count; $column_num++) { 
            print("<TD>$row[$column_num]</TD>\n"); ?>
     
  <?php    } 
		
		
       // print($rowid[0]);
        //print ($row[2]);
		print("</TR>\n");
		$number++; 
    } 
	print("</TABLE>\n");  
	//print($column_count);
	
} 

function display_db_table($tablename, $connection, $header_bool, $table_params) { 
    $query_string = "SELECT name, phone_number, carrier, bill, paid FROM messages where bill = '$_POST[store]' and table_name ={$_SESSION['db_num']}"; 
 // echo $query_string;
  display_db_query($query_string, $connection, 
    $header_bool, $table_params); 
	}
	


 
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
 <form id = "cbs" name = "form1" Method = "POST" action= "alertManager.php">
 
<?php 
//database to connect to
$table = "messages";


print("<a href = 'bills.php'>Back to Bills</a>"); 
?>
<table width ="100%" border ="0">
<tr><td valign="top">
<table border="0" valign="top">
<tr><td ><SELECT NAME=store> 
<OPTION VALUE="<?php $store ?>">Choose</OPTION> 
<?php echo $options?> 
</SELECT> </td>
<td><input type = "submit" name = "getMessages" value = "get messages"/></td></tr>
</table>


<?php
display_db_table($table, $global_dbh, 
TRUE, "border='1' style='background-color:white;'"); 
 

?> 



</TD>

<!--</TR></table>-->
<br />
</form>
</td><td valign="bottom">
<form id="sms" name="sms" method="post" action="processAddRecipient.php?bill=<?php echo $_SESSION['bill']; ?>">
<table width="400" border="0" valign ="bottom">

   <tr>
    <td align="right">&nbsp </td>
    <td align="left"> </td>
  </tr>
  <tr>
    <td align="right" >&nbsp </td>
    <td align="left"> </td>
  </tr>
  <tr>
    <TD> Add for <?php echo $_SESSION['bill']; ?> </td>
    
  </tr>
  <tr>
    <td align="right" valign="top">Name:</td>
    <td align="left"><input name="name" type="text" id="from" size="30" /></td>
  </tr>
  <tr>
    <td align="right" valign="top">Number:</td>
    <td align="left"><input name="phone_number" type="text" id="to" size="30" /></td>
  </tr>
  <tr>
    <td align="right" valign="top">Carrier:</td>
    <td align="left"><select name="carrier" id="carrier">
      <option value="verizon">Verizon</option>
      <option value="tmobile">T-Mobile</option>
      <option value="sprint">Sprint</option>
      <option value="att">AT&amp;T</option>
      <option value="virgin">Virgin Mobile</option>
    </select></td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><input type="submit" name="Submit" value="Submit" /></td>
    </tr>
</table>

</td></tr>
</table>