<?php
session_start();
$username = $_POST['user'];
//echo $username;
$password = $_POST['pass'];
//echo $password;
//echo $_POST["user"];
//echo("<br />");
//echo $_POST['pass'];
//echo("<br />");

$_SESSION['my_username']=$username; 
$_SESSION['my_password']=$password; 
$_SESSION['role']=0;

require('dbConfig.php');


$con = mysqli_connect($localhost, $DBusername, $DBpassword);


//connect to database using correct login credentials

if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));

  }
  else{
    //echo("Connected to database");
}


//select database
$db_selected = mysqli_select_db($con, $database);


if (!$db_selected) {
    die ('Could not select databse : ' . mysqli_error($con));
}
$result= mysqli_query($con, "SELECT * FROM user WHERE username = '".mysqli_real_escape_string($con, $_POST['user'])."'"); 


if (!$result) {
    die('Invalid query: ' . mysqli_error($con));
}

//no matches for Username
$num=mysqli_num_rows($result);
if ($num == 0)
{
	header("Location: loginFalse.html");
}

for($i=1;$i<=$num; $i++){
while($row=mysqli_fetch_array($result))
{

$user=$row['username']; 
$pass=$row['password'];
$role=$row['role'];
$db_num=$row['db_num'];

}

if($username == $user && $password ==$pass && $role == 1)
{
header("Location: bills.php");
$_SESSION['role']= 1; 
$_SESSION['db_num']=$db_num;
echo("Correct<br />superUSER");
}
else if($username == $user && $password ==$pass)
{
header("Location: billsUser.php");
$_SESSION['role']= 2; 
$_SESSION['db_num']=$db_num;
echo("Correct");
}
else if($password != $pass || $username != $user ) //this is dumb because username has to equal some user or it wouldn't return results
{
header("Location: loginFalse.html");
$_SESSION['role']= 0; 
echo("Please Enter Correct Username and Password ...");
}
}
?>