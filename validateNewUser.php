<?php
 session_start();
 
 //echo $_SESSION['db_num'];
 
 
 if(!isset($_SESSION['role'])) {
      header("Location: logout.php");
 }
 if ($_SESSION['role'] == null) {
 header("Location: logout.php");
 }
 if ($_SESSION['role'] == 0){
  header("Location: logout.php");
 
 }

 $sessionDB = $_SESSION['db_num'];

 require('dbConfig.php');
/*
$localhost="anaRoot.db.10947084.hostedresource.com";
$DBusername="anaRoot";
$DBpassword="Incubus1!";
$database="anaRoot";
*/
$con = mysql_connect($localhost,$DBusername,$DBpassword);
@mysql_select_db($database) or die( "Unable to select database");


$query="SELECT DISTINCT(db_num) FROM user";
$result=mysql_query($query);

$num=mysql_num_rows($result);








/*
 *  Specify the field names that are in the form. This is meant
 *  for security so that someone can't send whatever they want
 *  to the form.
 */
$allowedFields = array(
    'username',
    'pwd1',
    'pwd2',
    'role',
    );
 
// Specify the field names that you want to require...
$requiredFields = array(
    'username',
    'pwd1',
    'pwd2',
    'role',
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
    if($pwd1 =="" || strlen ($pwd1)<6){
		 $errors[$result+1] = "The password must be at least 6 characters long!";
		 }	 
    if($pwd1!=$pwd2){
    	 $errors[$result+2] = "The passwords must match!";
		 }	     
    
    if($pwd1 != "" && $pwd1 == $username){
    	 $errors[$result+3] = "Username and password cannot be the same!";
		 }
         
$queryUsername = "Select username from user";
$result2 =mysql_query($queryUsername);
$num2 = mysql_num_rows($result2);
for ($i = 0; $i < $num2; $i++){
    while($row=mysql_fetch_array($result2))
{
$user=$row['username']; 


if ($username == $user){
    $errors[$result+4] = "Username already exists, please choose another!";
}}
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
   // echo $errorString; 
    // display the previous form
    include 'addUser.php';
}
else
{ 




$con = mysql_connect($localhost,$DBusername,$DBpassword);


if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($database, $con);

$sql="INSERT INTO user (username, role, password, db_num)
VALUES
('$_POST[username]','$_POST[role]', '$_POST[pwd1]', '$sessionDB')";

if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }

 	
 
//echo "1 record added";

mysql_close($con);
    // At this point you can send out an email or do whatever you want
    // with the data...
     
    // each allowed form field name is now a php variable that you can access
     
    // display the thank you page
    header("Location: thanks.html");  
}
?>