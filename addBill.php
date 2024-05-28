<?php

 session_start();
 
 
 if(!isset($_SESSION['role'])) {
      header("Location: logout.php");
 }
 if ($_SESSION['role'] == 0){
  header("Location: logout.php");
 
 }
// echo $_SESSION['db_num'];

// index.php
//$errorString

?>
<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>
<html>
<head>

<style type="text/css">

body{ background-image: url(Gray-background.gif);
      background-repeat: no repeat;
      background-size: 100%;
      background-color: #0000fa}
p{ color:white;
   }
li{color:white;}
td{color:#34B404;}

 
</style>
    <title>Adding a new bill</title>
	
</head>

<body>
 <?= 
 @$errorString
 ?>

 <a href = 'bills.php'>Back to Bills</a>
 <br />
 <br />
 <b>Add a new bill</b>
<form action="validateNewBill.php" method="POST">
<table border="0">
<tr><td>Store*:</td><td><input type="text" name="store" value="<?php $store?>" /></td></tr>
<tr><td>Due Date*:</td><td><input type="text" name="due" value="<?php $due?>" /></td></tr>
<tr><td>Website:</td><td><input type="text" name="website" value="<?php $website?>" /></td></tr>
<tr><td>Username:</td><td><input type="text" name="username" value="<?php $username?>" /></td></tr>
<tr><td>Password:</td><td><input type="text" name="password" value="<?php $password?>" /></td></tr>



<tr><td></td><td><input type="submit" name="submit" value="Submit Form" /></td></tr>
</form>
     
</body>
</html>
