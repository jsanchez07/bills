<?php
require_once('session_init.php');
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


$con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);


//connect to database using correct login credentials

if (!$con)
  {
  die('Could not connect: ' . mysqli_connect_error());

  }
  else{
    //echo("Connected to database");
}


// Database is already selected in mysqli_connect
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

// Process the user data
$user_found = false;
while($row=mysqli_fetch_array($result))
{
    $user=$row['username']; 
    $pass=$row['password'];
    $role=$row['role'];
    $db_num=$row['db_num'];
    
    if($username == $user && $password == $pass) {
        $user_found = true;
        
        if($role == 1) {
            $_SESSION['role']= 1; 
            $_SESSION['db_num']=$db_num;
            header("Location: bills.php");
            exit();
        } else {
            $_SESSION['role']= 2; 
            $_SESSION['db_num']=$db_num;
            header("Location: billsUser.php");
            exit();
        }
    }
}

// If no matching user/password found
if(!$user_found) {
    header("Location: loginFalse.html");
    $_SESSION['role']= 0; 
    exit();
}
?>