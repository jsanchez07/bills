<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>
<?php

 session_start();

 if(!isset($_SESSION['role'])) {
      header("Location: logout.php");
 }
 if ($_SESSION['role'] == null) {
 header("Location: logout.php");
 }
 if ($_SESSION['role'] == 0){
  header("Location: logout.php"); 
 }
 require('dbConfig.php');
/*
$localhost="anaRoot.db.10947084.hostedresource.com";
$DBusername="anaRoot";
$DBpassword="Incubus1!";
$database="anaRoot";
*/
$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
$db_num = $_SESSION['db_num'];


$sql="SELECT * FROM `{$_SESSION['db_num']}`"; 

$result=mysqli_query($con, $sql); 
$num=mysqli_num_rows($result);

$options=""; 

while ($row=mysqli_fetch_array($result)) { 

    $id=$row["store"]; 
   
   $options.="<OPTION VALUE=\"$id\">".$id.'</option>';
} 


 function change(){
     echo "is this shit working at all???<br />";
 /* $theStoreChange = $document->getElementById( 'store' );
   $mySql = "SELECT * FROM `{$_SESSION['db_num']}`";
     $myResult = mysql_query($mySql);
     
    while ($row=mysql_fetch_array($myResult)) { 
     $store=$row["store"]; 
if($theStoreChange == $store){
    $due = $row["due_on"];
    $website = $row["website"];
    $username = $row["user"];
    $password = $row["pass"];
   }//end if
   
   echo $website;
} //end while*/
     
 } //end function change()




?>

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
    <title>Updating bills</title>
	
</head>

<body>
 

 <a href = 'bills.php'>Back to Bills</a>
 <br />
 <br />
 <b>Select store you wish to update</b>
<form action="validateUpdateBill.php" method="POST">
<table border="0">
<tr><td><SELECT id = "store" NAME="store" onchange="<?php change() ?>"> 
<OPTION VALUE="<?php $store ?>">Choose</OPTION> 
<?php echo $options?> 
</SELECT> </td></tr>

<tr><td>Due Date:</td><td><input type="text" size ="4" name="due" value="<?php echo $due?>" /></td></tr>
<tr><td>Website:</td><td><input type="text" name="website" value="<?php echo $website?>" /></td></tr>
<tr><td>Username:</td><td><input type="text" name="username" value="<?php echo $username?>" /></td></tr>
<tr><td>Password:</td><td><input type="text" name="password" value="<?php echo $password?>" /></td></tr>


<tr><td></td><td><input type="submit" name="submit" value="Update Bill" /></td></tr></table>
</form>
<b>*submitting form will update all fields even if left blank</b>
     
</body>
</html>