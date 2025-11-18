<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>
<?php
// process.php
require_once('session_init.php');
 // $dbNum = $_SESSION['db_num'];
 // echo $db_num;
 // echo $_SESSION['db_num'];
/*
 *  Specify the field names that are in the form. This is meant
 *  for security so that someone can't send whatever they want
 *  to the form.
 */

$allowedFields = array(
    'store',
    'due',
    'auto_pay',
    'recurring_amount',
);
 
// Specify the field names that you want to require...
$requiredFields = array(
    'store',
    'due',
);
 
// Loop through the $_POST array, which comes from the form...
$errors = array();
foreach($_POST AS $key => $value)
{
    // first need to make sure this is an allowed field
    if(in_array($key, $allowedFields))
    {
        $$key = $value;
         
        // is this a required field?
        if(in_array($key, $requiredFields) && $value == '' )
        {
            $errors[] = "The field $key is required.";
        }
    }  
}
$result = count($errors);
	if($due < 1 || $due > 30){
		 $errors[$result+1] = "The due date must be a number between 1 and 30";
		 }	 
 
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
    echo $errorString; 
    // display the previous form
    include 'updateBill.php';
}
else
{
require('dbConfig.php');
/*
$localhost="anaRoot.db.10947084.hostedresource.com";
$DBusername="anaRoot";
$DBpassword="Incubus1!";
$database="anaRoot";
*/


$con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);


// Handle auto_pay checkbox - if not checked, it won't be in $_POST
$auto_pay = isset($_POST['auto_pay']) ? 1 : 0;
$recurring_amount = isset($_POST['recurring_amount']) ? $_POST['recurring_amount'] : '0.00';

$sqlQuery="UPDATE `{$_SESSION['db_num']}` SET due_on = $_POST[due], website = '$_POST[website]', username ='$_POST[username]', password = '$_POST[password]', auto_pay = '$auto_pay', recurring_amount = '$recurring_amount' where store ='$_POST[store]'";

if (!mysqli_query($con, $sqlQuery ))
  {
  die('Error: ' . mysql_error());
  }
//echo "1 record added";

mysqli_close($con);
    // At this point you can send out an email or do whatever you want
    // with the data...
     
    // each allowed form field name is now a php variable that you can access
     
    // display the thank you page
   header("Location: bills.php");
   exit();
}
?>