

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>

<?php

header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private");
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');



session_start();

 
//echo $_SESSION['my_username']."<br />";
//echo $_SESSION['my_password']."<br />";
//echo $_SESSION['role']."<br />";
?>


<head>
<link rel="stylesheet" type="text/css" href="style.css"/>
<script src="jquery.js"></script>
<script type="text/javascript" src="scripts.js"></script>

</head>
 <title>addUser</title>
      
<body>

 <?= 
 @$errorString
 ?>

 <a href = 'bills.php'>Back to Bills</a>
 <br />
 <br />


<table width = "25%" height ="10%" border = "0" cellspacing = "0" cellpadding ="0" align = "center">


<tr>

<td align = "center" ><h1>billWarn</h1></td>
<td valign = "top" ><img src="beta.png" alt="leaves" height = "55" width="50px" /></td>
</tr>

</table>



<div align="center" padding-left = "20px">
<table width="25%" border="0" background="darkgray.jpg" padding-left = "20 px">
 <formtextheading>Add New</formtextheading>
    <tr>
    <td>
    
    <br />
    </td>
    </tr><form action="validateNewUser.php" method="post">
    <tr>
      <td align="right"> 
        
       <formText> Username:</formText>
          <input type="text" name="username" id="username"  />
      
        </td></tr>
      <tr>
         <td align ="right">  
     
         <formText> Password: </formText>
            <input type="password" name="pwd1" id="pwd1"  />
    </td></tr>
    <tr><td align ="right">
     <formText> Confirm Password:</formText>
          <input type="password" name="pwd2" id="pwd2"  />
      
        </td></tr>
      <tr><td align = "right">
        <formText> Role: 
       <input type="radio" name="role" value= 1 checked> <label style="color:white;"> Admin</label>
        <input type="radio" name="role" value= 2><label style="color:white;"> User</label> 
        
    
    
        <tr>
        <td><br /></td>
        </tr>
        <tr>
        <td align= "center">
         <input type="submit" name ="submit" value"New Database"/>
       
    <!--  <input type ="submit" name ="submit" value ="Login"/>-->
       </td>
        </tr>  </form>    
        <tr>
       </tr>
      
    </tr>
  </table>

  
  </div>
  </body>
</html>