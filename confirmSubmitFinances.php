<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>
<?php
require_once('session_init.php');
 
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
 
$errors = array();
//echo"<pre>";
//print_r($_POST);

$amountOwed =  implode(",",$_POST['amount']);
$APR =  implode(",",$_POST['apr']);
$submit = implode(",",$_POST['submit']);
$number = split('T', $submit)[1];

$arrAmount = explode(',', $amountOwed);
$procAmount = $arrAmount[$number];

$arrAPR = explode(',', $APR);
$procAPR = $arrAPR[$number];


//echo $number;
//echo "<br />".$procAPR;
//echo "<br />".$procAmount;



$tablename = "`finances{$_SESSION['db_num']}`";
require('dbConfig.php');
$con = mysql_connect($localhost,$DBusername,$DBpassword);
@mysql_select_db($database) or die( "Unable to select database, duh");





//mysql_select_db($database, $con) 
//or die("Could not select database"); 

$query_string = "SELECT store, amount_owed, APR FROM $tablename ORDER by store ASC";
$result_id = mysql_query($query_string, $con) 
    or die("display_db_query:" . mysql_error()); 
    
$rows = mysql_num_rows($result_id) 
    or die("display_db_query:" . mysql_error());    
   
  
          

$store=mysql_result($result_id,$number,"store");
$amountOwed=mysql_result($result_id,$number,"amount_owed");
$APR=mysql_result($result_id,$number,"APR");

//echo "<br />".$store;

$procAmount = doubleVal($procAmount);
$procAPR = doubleVal($procAPR);


       
   /* if((is_nan($procAmount)) == true )
   
        {
        $errors[] = "Amount is not a number!";
         }
       
    if((is_nan($procAPR)) == true){
          $errors [] = "APR is not a number!";
      }*/
    
 
// were there any errors?
if(count($errors) > 0)
{
  
   $errorString = '<p>There was an error processing the form.</p>';
   
    $errorString .= '<ul>';
    foreach($errors as $error)
    {
        $errorString .= "<li>$error</li>";
    }
    $errorString .= '</ul>';
 // echo( "<body> <br /> <a href = 'bills.php'>Back to Bills</a><br />");
  echo $errorString;
    // display the previous form
    include 'finances.php';
}
else
{
header("Location: finances.php");



if($procAmount && !(is_nan($procAmount)) && $procAPR && !(is_nan($procAPR)) )
{
$sql="Update `finances{$_SESSION['db_num']}` set amount_owed ='$procAmount', APR = '$procAPR' where store = '$store'";
 
  if (!mysql_query($sql,$con))
  {
  die('Error: one' . mysql_error());
  }
  include 'finances.php';
}


else if($procAmount && !(is_nan($procAmount)) && (!$procAPR || is_nan($procAPR)))
{
  $sql="Update `finances{$_SESSION['db_num']}` set amount_owed ='$procAmount' where store = '$store'";
 
  if (!mysql_query($sql,$con))
  {
  die('Error: two' . mysql_error());
  }
  include 'finances.php';
  }

  else if($procAPR && !(is_nan($procAPR)) && (!$procAmount || is_nan($procAmount)))
  {
  $sql="Update `finances{$_SESSION['db_num']}` set APR ='$procAPR' where store = '$store'";
 
  if (!mysql_query($sql,$con))
  {
  die('Error: three' . mysql_error());
  }
  include 'finances.php';
  }
 
    else if($procAmount == 0)
    {
        $sql="Update `finances{$_SESSION['db_num']}` set amount_owed ='0', APR = '0' where store = '$store'";
        
        if (!mysql_query($sql,$con))
        {
            die('Error: three' . mysql_error());
        }
        include 'finances.php';
        
    }
}
?>