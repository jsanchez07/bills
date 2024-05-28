<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>
<?php

session_start();
 
 
 if(!isset($_SESSION['role'])) {
      header("Location: logout.php");
 }
 if ($_SESSION['role'] == 0){
  header("Location: logout.php");
 }

$today = date("m-d-Y", time()+7320);
list($monthNow,$dayNow,$yearNow) = explode("-",$today);
$daysInMonthNow = (cal_days_in_month(CAL_GREGORIAN, $monthNow, $yearNow));

$table = $_SESSION['db_num'];

$bill = $_GET["bill"];
$_SESSION['bill'] = $bill;

require('dbConfig.php');
/*$localhost="anaRoot.db.10947084.hostedresource.com";
$username="anaRoot";
$password="Incubus1!";
$database="anaRoot";
*/
$con = mysql_connect($localhost,$DBusername,$DBpassword);
@mysql_select_db($database) or die( "Unable to select database");


/*if (isset($_POST)) {
  $name = $_POST['name']; 
  $phone_number = $_POST['phone_number'];
  $carrier = $_POST['carrier'];
  
if($name !='' && $phone_number !='' && $carrier != ''){
$insert = "INSERT INTO messages (name, phone_number, carrier, table_name, bill) VALUES ('$name', '$phone_number', '$carrier', '$table', '$bill')";
        if (!mysql_query($insert,$con))
        {
         die('Error: ' . mysql_error());
        }
    }
}
*/




$query="SELECT * FROM messages WHERE table_name = '$table' AND bill = '$bill'";

$result=mysql_query($query);
$num=mysql_num_rows($result);
//echo $num;





?>
<!DOCTYPE html>  
<head> 


<link rel="stylesheet" type="text/css" href="style.css" media="all"/>
<script type="text/javascript" src="scripts.js"></script>


</head> 
  <body>  
  <a href = "bills.php" />Back to Bills</a>

<br />
<table width="60%" border="1">
  <tr>
  <td colspan ="3" align ="center"><h3>Recipients for <?php echo $bill ?> bill</h3></td>
  </tr>
  <tr>
    <td align="center">Name:</td>
    <td align="center">Number</td>
    <td align="center">Carrier</td>
    <td align="center">Paid</td>
  </tr>
  <form action='addRecipient.php?bill=<?php echo $_SESSION['bill']; ?>' method='POST'>
<?php 

while ($i < $num) {
$row = mysql_fetch_row($result);
$DBname=mysql_result($result,$i,"name");
$DBphone_number=mysql_result($result,$i,"phone_number");
$DBcarrier=mysql_result($result,$i,"carrier");
$DBpaid=mysql_result($result,$i, "paid");
if ($DBpaid ==0)
{
$checkSet = false;
//echo $DBpaid;
}
else
{
  //  echo $DBpaid;
$checkSet = true;
}


$isChecked = false;
  $constr = 0;
  echo $_POST['$DBname'];
  if(isset($_POST['$DBname'])){
    
      $isChecked = true;
      $constr = 1;
    }      
   
  
   mysql_query("UPDATE messages SET paid = $constr WHERE bill = '$bill' AND name = '$DBname'") or die(mysql_error());

 //echo "UPDATE messages SET paid = $constr WHERE bill = $bill AND name = $DBname";
 

?>
     <tr><td><?php echo $DBname ?></td><td><?php echo $DBphone_number ?></td><td><?php echo $DBcarrier?></td><td>
      <input name="$DBname" type="checkbox" id="construction" onChange="this.form.submit()" <?php if($isChecked) echo "checked='checked'"; ?> />
      <?php

      ?>
      
      
      
    

<?php


$i++;
}


/*
if($DBpaid == 0){
echo "<tr><td>$DBname</td><td>$DBphone_number</td><td>$DBcarrier</td><td><input type='checkbox' name='paid' /></td></tr>";
}
else
echo "<tr><td>$DBname</td><td>$DBphone_number</td><td>$DBcarrier</td><td><input type='checkbox' name='paid' checked/></td></tr>";
$i++;
}
*/
?>
</form>  
</table>
 </body>  
</html>  