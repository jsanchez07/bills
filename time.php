<?php 

if($_POST["time"]) {



//header('Location: https://www.paypal.com/cgi-bin/webscr');
//exit;//it's a good habit to call exit after header function because script don't stop executing after redirection
}

   ?>


<HTML>
<HEAD>

<br />
<br />
<TITLE>Finances</TITLE></HEAD> 
<BODY> 
 <form id = "time" name = "theform" Method = "POST" action= "">
 <table border ='1' width ='100%' align='center'>

 <tr align ="center" style ="background-color: blue; color: white">
 <td>Time</td>
 <td><input type = 'text' name = 'time'></td>
 <td><input type = submit name ='submit'></td>
 </tr><tr>
 <td>Result</td>
 <td><input type = 'text' name ='result'></td>
 </tr>
 
<?php 
//ini_set('max_execution_time', 300); 
            
   

?>

</TABLE>
</form>
<br />
