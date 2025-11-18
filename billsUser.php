<?php
require_once('session_init.php');
 
 //echo $_SESSION['db_num'];

 
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
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>


<link rel="stylesheet" type="text/css" href="style.css" media="all"/>
<script type="text/javascript" src="scripts.js"></script>


</head>
<body>

<table border="0" width="95%">
<td align="right" style ="color:#31B404;"/>Logged in as <uN><?php echo $_SESSION['my_username']?></uN></td>
<td align="right"><a href="logout.php" style ="color:#31B404;"/>exit</a></td></tr>
</table>
<br />
<br />
<?php
 
 require('dbConfig.php');
/*
$localhost="anaRoot.db.10947084.hostedresource.com";
$username="anaRoot";
$password="Incubus1!";
$database="anaRoot";
*/
$con = mysql_connect($localhost,$DBusername,$DBpassword);
@mysql_select_db($database) or die( "Unable to select database");

// its telling you here how to add numbers to names of tables 
//mysql_query("SELECT * FROM {$_SESSION['SESS_LOGIN']}_blog");

$query="SELECT * FROM `{$_SESSION['db_num']}` order by store";
$result=mysql_query($query);
//echo $query;
$num=mysql_num_rows($result);

$today = date("m-d-Y", time()+7320);
$headerToday = date("m-d-Y \a\\t h:i a", time()+7320);
?>

<?php
if($_SESSION['my_username']== "ana")
{
?>
<table width ="17%" align="center">
<tr><td><img src="heart.jpg" alt="I Love You, Ana Sanchez"></tr></td>
</table>
<?php } ?>


<table width ="25%" align="center">
<tr style="background-color:beige; color: blue"><td align ="center" >
<font face="Arial, Helvetica, sans-serif">
<?php

print " Today is : ";
?><b><?php print $headerToday;?></b>
</td></tr></table>

<?php

$totalAmount = 0;
mysql_close();
?>
<form id = "cbs" name = "form1" Method = "POST" action= "confirmPay.php">
<table border="1" cellspacing="2" cellpadding="2" align = "center">
<tr  align ="center"><td style ="background-image: linear-gradient(bottom, #3349B8 23%, #52C2DE 90%);
background-image: -o-linear-gradient(bottom, #3349B8 23%, #52C2DE 90%);
background-image: -moz-linear-gradient(bottom, #3349B8 23%, #52C2DE 90%);
background-image: -webkit-linear-gradient(bottom, #3349B8 23%, #52C2DE 90%);
background-image: -ms-linear-gradient(bottom, #3349B8 23%, #52C2DE 90%);

background-image: -webkit-gradient(
    linear,
	left bottom,
	left top,
	color-stop(0.23, #3349B8),
	color-stop(0.9, #52C2DE)
);
" colspan ="6"><h2>Info</h2></td>
<td style ="background-image: linear-gradient(bottom, #6A9E26 23%, #19FF6A 90%);
background-image: -o-linear-gradient(bottom, #6A9E26 23%, #19FF6A 90%);
background-image: -moz-linear-gradient(bottom, #6A9E26 23%, #19FF6A 90%);
background-image: -webkit-linear-gradient(bottom, #6A9E26 23%, #19FF6A 90%);
background-image: -ms-linear-gradient(bottom, #6A9E26 23%, #19FF6A 90%);

background-image: -webkit-gradient(
	linear,
	left bottom,
	left top,
	color-stop(0.23, #6A9E26),
	color-stop(0.9, #19FF6A)
);" colspan="4"><h2>Pay</h2></td></tr>
<tr style ="background-color:white;" align="center">
<td><rows>Store</rows></td>
<td><rows>Next Due Date</rows></td>
<td><rows>Days Left</rows></td>
<td><rows>Last Payment</rows></td>
<td><rows>Payment Amount</rows></td>
<td><rows>Case</rows></td>
<td><rows>Date Paid</rows></td>
<td><rows>Amount Paid</rows></td>
<td><rows>Make Payment</rows></td>
<td><rows>Go to Website</rows></td>

</tr>
<?php
   if($num ==0)
   { ?>
    
    <tr style="background-color:beige; color: green"><td colspan="10" align ="center">
    <h2> go to <u>add bill</u> on top left corner to begin adding bills </h2></td></tr>
    
    
    

<?php }


/*$lastMonthPaid[] = array();
$lastDayPaid[] = array();
$lastYearPaid[] = array();
$paidArray[]= array();
$newMonthArray[] = array();
$daysLeftArray[]= array();
*/
$i=0;




list($monthNow,$dayNow,$yearNow) = split("-",$today);
$daysInMonthNow = (cal_days_in_month(CAL_GREGORIAN, $monthNow, $yearNow));
$monthName = 'none';

switch($monthNow){
case '01': $monthName='Jan';
     break;
case '02': $monthName='Feb';
     break;
case '03': $monthName='Mar';
     break;
case '04': $monthName='Apr';
     break;
case '05': $monthName='May';
     break;
case '06': $monthName='Jun';
     break;
case '07': $monthName='Jul';
     break;
case '08': $monthName='Aug';
     break;
case '09': $monthName='Sep';
     break;
case '10': $monthName='Oct';
     break;
case '11': $monthName='Nov';
     break;
case '12': $monthName='Dec';
     break;
default: $monthName ='DEF';
  break;
}



while ($i < $num) {
$row = mysql_fetch_row($result);
$f1=mysql_result($result,$i,"store");
$dueOn=mysql_result($result,$i,"due_on");
$f3=mysql_result($result,$i,"last_payment");
$f4=mysql_result($result,$i,"last_amount");
$website=mysql_result($result,$i,"website");
$user=mysql_result($result,$i,"username");
$pass=mysql_result($result,$i,"password");
$credentials = "username: ".$user."\n"."password: ".$pass;
$f5 = date_pay.$i;
list($year,$month,$day) = split("-",$f3);
$lastMonthPaid = $month;
$lastDayPaid=$day;
$lastYearPaid=$year;
//echo $website;
$daysInMonth = (cal_days_in_month(CAL_GREGORIAN, $month, $year));





if($dueOn <= $dayNow)
{ 
  $newMonthArray = $monthNow +1;
// echo "case1";
  }

else if ($dueOn > $dayNow && (($monthNow-$lastMonthPaid) == 1))
{ 
  $newMonthArray = $monthNow+1;
// echo "case2";
  }
  else if ($dueOn> $dayNow){
      $newMonthArray = $monthNow;
    //echo "case3";
  }
  else
    {
        echo "no case";
    }
  
/*  
if($i==0){
    echo "lastDayPaid: ".$lastDayPaid."<br />";
    echo "dueOn: ".$dueOn."<br />";
    echo "newMonthArray: ".$newMonthArray."<br />";
    echo "lastMonthPaid: ".$lastMonthPaid."<br />";
    echo "daysLeftArray: ".$daysLeftArray."<br />";
    echo "monthNow: ".$monthNow."<br />";
}
  */
  
  
  $totalAmount = $totalAmount +$f4;
  //echo "\n".$i."= ".$newMonthArray."\n";

//print($rowid[0]);
//print ($row[0]);

if($lastDayPaid<$dueOn && $lastMonthPaid == $monthNow){     /*|| $lastDayPaid==$dueOn) */ 
 $daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn);
$paidArray = "paid";
$newMonthArray = $monthNow +1;
	if($newMonthArray ==13)
	{$newMonthArray=1;
	 $yearNow =$yearNow+1;}
	$case = 1;
}

else if($lastDayPaid >=$dueOn && ((($newMonthArray - $lastMonthPaid) ==2)||(($newMonthArray - $lastMonthPaid) ==1)) 
&& ($newMonthArray == $monthNow)){
    
    $daysLeftArray = ($dueOn- $dayNow);
$paidArray = "paid";




    if($newMonthArray == 13)
    {$newMonthArray =1;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn);
	}

	 if($newMonthArray == 14)
	{$newMonthArray =2;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow+1));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn);
	
	}
$case = 1.5;
    
}
else if($lastDayPaid > $dueOn &&($newMonthArray - $lastMonthPaid ==1) && $newMonthArray = $monthNow+1){
    
  
  
  
  
$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, $monthNow+1, $yearNow));
$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn + $daysInMonthNext);
$paidArray = "paid";
$newMonthArray = $newMonthArray+1;




    if($newMonthArray == 13)
	{$newMonthArray =1;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn+$daysInMonthNext);
	}

	 if($newMonthArray == 14)
	{$newMonthArray =2;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow+1));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn);
	
	}
$case = 1.75;
}



else if($lastDayPaid >=$dueOn && ((($newMonthArray - $lastMonthPaid) ==2) ||
($newMonthArray - $lastMonthPaid == 0))){ /*|| $lastDayPaid==$dueOn)*/


$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, $monthNow+1, $yearNow));
$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn);
$paidArray = "paid";
$newMonthArray = $newMonthArray;




	if($newMonthArray == 13)
	{$newMonthArray =1;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn);
	}

	 if($newMonthArray == 14)
	{$newMonthArray =2;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow+1));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn);
	
	}
$case = 2;
}




else if ($dueOn < $dayNow){

$paidArray ="owe";
$daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn);
  $newMonthArray = $monthNow +1;
  if($newMonthArray == 13)
{$newMonthArray =1;
$yearNow= $yearNow+1;}
  $case = 3;
  
}
 
else{
 $daysLeftArray = ($dueOn - $dayNow);
  $newMonthArray = $monthNow;
  $case = 4;
}

//for(int j=0; j<$num; j++)


if(($newMonthArray - $lastMonthPaid) > 2 ||($newMonthArray - $lastMonthPaid) ==2 && $lastDayPaid<$dueOn){ 
//<!--GRAY BACKGROUND-->
echo '<tr style="background-color:gray; color: red">';
}
else if($daysLeftArray < 11){ 
//<!--RED BACKGROUND-->
echo '<tr style="background-color:red; color: white">';
}
else if($daysLeftArray < 20 && $daysLeftArray > 10)
{   
//<!--YELLOW BACKGROUND-->
echo '<tr style="background-image: linear-gradient(bottom, #EEFF00 19%, #F3F7B7 90%);
background-image: -o-linear-gradient(bottom, #EEFF00 19%, #F3F7B7 90%);
background-image: -moz-linear-gradient(bottom, #EEFF00 19%, #F3F7B7 90%);
background-image: -webkit-linear-gradient(bottom, #EEFF00 19%, #F3F7B7 90%);
background-image: -ms-linear-gradient(bottom, #EEFF00 19%, #F3F7B7 90%);

background-image: -webkit-gradient(
    linear,
	left bottom,
	left top,
	color-stop(0.19, #EEFF00),
	color-stop(0.9, #F3F7B7)
);">';
}
else
{ 
//<!--GREEN BACKGROUND-->
echo '<tr style="background-image: linear-gradient(bottom, #329C0F 19%, #91FA91 90%);
background-image: -o-linear-gradient(bottom, #329C0F 19%, #91FA91 90%);
background-image: -moz-linear-gradient(bottom, #329C0F 19%, #91FA91 90%);
background-image: -webkit-linear-gradient(bottom, #329C0F 19%, #91FA91 90%);
background-image: -ms-linear-gradient(bottom, #329C0F 19%, #91FA91 90%);

background-image: -webkit-gradient(
    linear,
	left bottom,
	left top,
	color-stop(0.19, #329C0F),
	color-stop(0.9, #91FA91)
);">';
} ?>
<td><rows><?php echo $f1; ?></rows></td>
<td><rows><?php echo $newMonthArray."-".$dueOn."-".$yearNow; ?></rows></td>
<td><rows><?php echo $daysLeftArray; ?></rows></td>
<td><rows><?php echo $month."-".$day."-".$year; ?></rows></td>
<td><rows>$<?php echo $f4; ?></rows></td>
<td><rows><?php echo $case; ?></rows></td>
<td><rows><select id="sl1[]" name="slct1[]" >
  <option selected value="<?php echo $monthNow ?>"><?php echo $monthName ?></option>
  <option value="01">Jan</option>
  <option value="02">Feb</option>
  <option value="03">Mar</option>
  <option value="04">Apr</option>
  <option value="05">May</option>
  <option value="06">Jun</option>
  <option value="07">Jul</option>
  <option value="08">Aug</option>
  <option value="09">Sep</option>
  <option value="10">Oct</option>
  <option value="11">Nov</option>
  <option value="12">Dec</option></select>
 
 
 <select id="sl2[]" name="slct2[]">
  <option selected value="<?php echo $dayNow ?>"><?php echo $dayNow ?></option>
  <option value="01">1</option>  <option value="02">2</option>   <option value="03">3</option>
  <option value="04">4</option>  <option value="05">5</option>   <option value="06">6</option>
  <option value="07">7</option>  <option value="08">8</option>  <option value="09">9</option>
  <option value="10">10</option>  <option value="11">11</option>  <option value="12">12</option>
  <option value="13">13</option>  <option value="14">14</option>  <option value="15">15</option>
  <option value="16">16</option>  <option value="17">17</option>  <option value="18">18</option>
  <option value="19">19</option>  <option value="20">20</option>  <option value="21">21</option>
  <option value="22">22</option>  <option value="23">23</option>  <option value="24">24</option>
  <option value="25">25</option>  <option value="26">26</option>  <option value="27">27</option>
  <option value="28">28</option>  <option value="29">29</option>  <option value="30">30</option>
  <option value="31">31</option></select>
 
  
  
  
  
   <!-- selected Year automatically updated-->
 <select id="sl3[]" name="slct3[]">
  <option value="<?php echo $yearNow-1 ?>"><?php echo $yearNow-1 ?></option>
  <option selected value ="<?php echo $yearNow ?>"><?php echo $yearNow ?></option>
  <option value="<?php echo $yearNow+1 ?>"><?php echo $yearNow+1 ?></option>
  <option value="<?php echo $yearNow+2 ?>"><?php echo $yearNow+2 ?></option>
  
 </select>
  </rows></td>
<td><font face="Arial, Helvetica, sans-serif">$<input type = "text" size ="7" name = "amount[]"/></font></td>
<td align = "center"><font face="Arial, Helvetica, sans-serif"><input type = "submit" name="pay[]" value ="<?php echo'pay'.$i ?>"/></font></td>
<?php if ($website != "") { ?>
<td><a href="<?php echo $website ?>" target="_blank" alt="<?php echo $credentials ?>" title="<?php echo $credentials ?>"/>go to <?php echo $f1 ?> website</a></td>
<?php } else { ?>
<td>No website</td>
<?php } ?>
</tr>
<?php 


$today = date("m-d-Y");
list($monthNow,$dayNow,$yearNow) = split("-",$today);



$i++;
}
/*print("<br />");
echo $year;
print("<br />");
echo $month;
print("<br />");
echo $day;
print("<br />");
print_r($monthArray);
print_r($lastDayPaid);*/
?>
</form>
<table width ="25%" align="center">
<tr style="background-color:beige; color: red"><td align ="center" valign="top">
<font face="Arial, Helvetica, sans-serif">
<?php
$english_format_number = number_format($totalAmount, 2, '.', '');
print "<br />Total amount for month: ";
?><b><?php print"$".$english_format_number;
print "<br />";

print "<br />";
?></b>
</td>/</tr></table>
 </body>

</html>