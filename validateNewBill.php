<?php
// process.php
require_once('session_init.php');
 

 if(!isset($_SESSION['role'])) {
      header("Location: logout.php");
 }
 if ($_SESSION['role'] == 0){
  header("Location: logout.php");
 }

/*$localhost="anaRoot.db.10947084.hostedresource.com";
$DBusername="anaRoot";
$DBpassword="Incubus1!";
$database="anaRoot";

*/
require('dbConfig.php');


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
        if(in_array($key, $requiredFields) && $value == '' ||$value =='Choose')
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
    include 'addBill.php';
}
else
{ 
 
//date_default_timezone_set('America/Chicago');
//$theDate =  date(“Y-m-d”);
 echo "Hello7";
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);


if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }



// Handle auto_pay checkbox - if not checked, it won't be in $_POST
$auto_pay = isset($_POST['auto_pay']) ? 1 : 0;
$recurring_amount = isset($_POST['recurring_amount']) ? $_POST['recurring_amount'] : '0.00';

// Set last payment date based on auto-pay status
if ($auto_pay == 1) {
    // For auto-pay bills, set last payment to current month's due date
    // This shows they were paid this month
    $current_year = date('Y');
    $current_month = date('m');
    $due_day = $_POST['due'];
    
    $last_payment = $current_year . '-' . sprintf('%02d', $current_month) . '-' . sprintf('%02d', $due_day);
} else {
    // For manual bills, use the default date
    $last_payment = '1983-07-07';
}

$sql="INSERT INTO `{$_SESSION['db_num']}` (store, due_on, last_payment, last_amount, website, username, password, auto_pay, recurring_amount)
VALUES
('$_POST[store]','$_POST[due]', '$last_payment' ,'$recurring_amount', '$_POST[website]', '$_POST[username]', '$_POST[password]', '$auto_pay', '$recurring_amount')";
//echo $sql;

if (!mysqli_query($con, $sql))
  {
  die('Error in this one:' . mysql_error());
  }
  
  $sqlRec="INSERT INTO `rec{$_SESSION['db_num']}` (store, due_on, website, username, password, deleted, auto_pay)
VALUES
('$_POST[store]','$_POST[due]', '$_POST[website]', '$_POST[username]', '$_POST[password]', '0', '$auto_pay')";
  
  if (!mysqli_query($con, $sqlRec))
  {
  die('Error here: ' . mysql_error());
  }
  
 
    $sqlRec="INSERT INTO `finances{$_SESSION['db_num']}` (store, amount_owed, APR, payment)
    VALUES
    ('$_POST[store]','0', '0', '0')";
    
    if (!mysqli_query($con,$sqlRec))
    {
        die('Error here: ' . mysqli_error());
    }
  
//echo "1 record added";

mysqli_close($con);
    // At this point you can send out an email or do whatever you want
    // with the data...
     
    // each allowed form field name is now a php variable that you can access
     
    // display the thank you page
    header("Location: bills.php");
}
?>