<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?> 

<?php

header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private");
//header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

 

session_start();
if(isset($_SESSION['role']))
  unset($_SESSION['role']);
 
//echo $_SESSION['my_username']."<br />";
//echo $_SESSION['my_password']."<br />";
//echo $_SESSION['role']."<br />";
?>


<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
<script type="text/javascript" src="scripts.js"></script>

</head>
 <title>Index.php</title>
      
<body>

	<div id ="loginHeaderDiv">
	    <table = "loginHeaderTable">
    		<tr>
    			<td><h1>billWarn</h1></td>
    			<td valign = "top" ><img src="beta.png" alt="leaves" height = "55" width="50px" /></td>
    		</tr>
    
    	</table>
    </div>


	<div id="loginDiv">
	<table id="loginTable">
    		<tr>
    			<td colspan = "2">
    			<formTextHeading>Login</formTextHeading>
    			<br />
    			</td>
    		</tr>
    		<tr>
      			<td> 
        		    <form action="input.php" method="post">
       			    <formText> Username:</formText>
       			</td>
         		<td> 
         		    <input type="text" name="user" id="user"  />
        		</td>
        	</tr>
      		<tr>
         		<td>  
     			    <formText> Password: </formText>
     			</td>
           		<td> 
           		    <input type="password" name="pass" id="pass"  /> 
           		</td>
  		</tr>
        	<tr>
        		<td>
        		<br />
        		</td>
        	</tr>
        	<tr>
        		<td colspan = "2">
         	        <button type="submit" id="loginSubmitButton">Submit</button>
       			</td>
        	</tr>   </form>    
        	<tr>
        		<td align ="center">
       	<!--	<a href="registerNew.php" style="color: #C0C0C0">Register New</a> -->
        		</td>
        	</tr>
      
    		</tr>
  	</table>
  	</div>
</body>
</html>