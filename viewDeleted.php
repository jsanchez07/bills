<?php
require_once('session_init.php');
 
 if(!isset($_SESSION['role'])) {
     header("Location: logout.php");
     exit();
 }
 if ($_SESSION['role'] == null) {
header("Location: logout.php");
exit();
}
if ($_SESSION['role'] == 0){
 header("Location: logout.php");
 exit();
}
 
require('dbConfig.php');
/*
$localhost="anaRoot.db.10947084.hostedresource.com";
$username="anaRoot";
$password="Incubus1!";
$database="anaRoot";
*/
$tablename = "`rec{$_SESSION['db_num']}`";

$connection = mysqli_connect($localhost, $DBusername, $DBpassword, $database) 
or die("Could not connect to database"); 

//mysql_select_db($database, $connection) 
//or die("Could not select database"); 

$query_string = "SELECT store, due_on, website, username, password, deleted FROM $tablename ORDER by store ASC";
$result_id = mysqli_query($connection, $query_string) 
    or die("display_db_query:" . mysql_error()); 
    
$rows = mysqli_num_rows($result_id) 
    or die("display_db_query:" . mysql_error());    
   
   $i=0;
   ?>


<HTML>
<HEAD>
<style type="text/css">

body{ background-image: url(images/Gray-background.gif);
      background-repeat: no repeat;
      background-size: 100%;
      background-color: #0000fa}
p{ color:white;
   }
   
li{color:white;}
</style>
<table border="0" width="95%">
<tr><td align="left">
<a href = 'bills.php'>Back to Bills</a> </td>
</table>

<br />
<br />
<TITLE>Bills that have been removed</TITLE></HEAD> 
<BODY> 
 <table border ='1' width ='100%' align='center'>

 <tr style ="background-color: blue; color: white"><td width = "100">Store</td><td width = "100">Due On</td><td width = "100">Website</td><td width = "100">Username</td>
 <td width = "100">Password</td><td width = "100">deleted</td></tr>
 <tbody>
<?php 
 
    
       while ($i<$rows){
           $row = mysqli_fetch_row($result_id);
                
                $store = $row[0];
                $dueOn = $row[1];
                $website = $row[2];
                $user = $row[3];
                $pass = $row[4];
                $deleted = $row[5];
        
          
//$store=mysql_result($result_id,$i,"store");
//$dueOn=mysql_result($result_id,$i,"due_on");
//$website=mysql_result($result_id,$i,"website");
//$user=mysql_result($result_id,$i,"username");
//$pass=mysql_result($result_id,$i,"password");
//$deleted=mysql_result($result_id,$i,"deleted");
           
           if ($deleted == 0){
           echo '<tr style="background-color:white; color: black">';
           echo"<TD>".$store."</TD><TD>".$dueOn."</TD><TD>".$website."</TD><TD>".$user."</TD>
           <TD>".$pass."</TD><TD>".$deleted."</TD>";
           echo "</tr>";
           }
          else
          {
           echo '<tr style="background-color:red; color:white">';
           echo"<TD>".$store."</TD><TD>".$dueOn."</TD><TD>".$website."</TD><TD>".$user."</TD>
           <TD>".$pass."</TD><TD>".$deleted."</TD>";
           echo "</tr>";
          }
          
          $i++;
}

?>
</tbody>
</TABLE>
<br />
<table width = "53%" border ="0">
<tr></tr>
</table>