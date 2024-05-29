<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?> 

<?php

header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private");
 

session_start();
if(isset($_SESSION['role']))
  unset($_SESSION['role']);
 
?>


<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
<script type="text/javascript" src="scripts.js"></script>

</head>
 <title>Index.php
 </title>
<html>
	<body>
		<div id="loginHeaderDiv">
			<div id="loginHeaderText">
				<h1>billWarn</h1>
			</div>		
			<div id="loginHeaderImage" class= "leaf">
				<img src="beta.png" alt="leaves" height="55" width="50px" />
			</div>
		</div>
		<div id="loginDiv" style="text-align: center;">
			<form action="input.php" method="post">
				<h2>Login</h2>
				<label for="user">Username:</label>
				<input type="text" name="user" id="user" /><br>
				<label for="pass">Password:</label>
				<input type="password" name="pass" id="pass" /><br>
				<button type="submit" id="loginSubmitButton">Submit</button>
			</form>
			<!-- <a href="registerNew.php" style="color: #C0C0C0">Register New</a> -->
		</div>

	</body>
</html>