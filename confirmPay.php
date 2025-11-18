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


$errors = array();
echo"<pre>";
print_r($_POST);
$Months = implode(",",$_POST['slct1']);
$query = "Months($Months)";
echo "<br />".$Months."<br >";

$days = implode(",",$_POST['slct2']);
$queryDays = "<br />Days ($days)<br />";
echo "<br />".$days."<br >";

$years = implode(",",$_POST['slct3']);
$queryYear = "Year($year)<br />";
echo "<br />".$years."<br >";

$pay = implode(",",$_POST['pay']);
$queryPay = "Pay($pay)<br />";
$key = array_search('pay', $_POST['pay']);
//echo $queryPay;
echo "<br />key: ".$key;

$amount = implode(",",$_POST['amount']);
$queryAmount = "Amount($amount)<br />";
echo "<br />".$amount."<br >";;


$arrPay = explode('y', $pay);
$procPay = $key;                //$arrPay[1];
echo "<br /> procPay:".$procPay."<br >";

$arrMon = explode(',', $Months);
$procMon = $arrMon[0];
echo "<br />arrMon: ".$arrMon;
echo "<br />arrmon[0]: ".$arrMon[0];
echo "<br />procMonth: ".$procMon."<br >";

$arrDays = explode(',', $days);
$procDay = $arrDays[0];
echo "<br />arrDays[0]: ".$arrDays[0];

$arrYear = explode(',', $years);
$procYear = $arrYear[0];
echo "<br />arrYear[0]: ".$arrYear[0];

$arrAmount = explode(',',$amount);
$procAmount = $arrAmount[0];
echo "<br />arrAmount[0]: ".$arrAmount[0];

echo "<br />".$procYear."-".$procMon."-".$procDay;

$procAmount = number_format($procAmount,2,'.','');

//echo "procYear: ".$procYear;
//echo "arrYear: ".$arrYear;

$lastPayment = $procYear."-".$procMon."-".$procDay;
echo  "<br />last payment: ".$lastPayment;

echo "<br />processed amount formated: ".$procAmount;

/*$localhost="anaRoot.db.10947084.hostedresource.com";
$username="anaRoot";
$password="Incubus1!";
$database="anaRoot";

$localhost="127.0.0.1";
$DBusername="root";
$DBpassword="i3mj23";
$database="bills";
*/

require('dbConfig.php');
echo "<br />is this it";
echo "<br />".$localhost." ".$DBusername." ".$DBpassword." ".$database;

    
$con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);
    if (mysqli_connect_errno()) {
        echo "<br />Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
    else{
        echo "<br />this connected and did not fail!!!";   
    }
//echo $con;

$query="SELECT * FROM `{$_SESSION['db_num']}` order by store";
echo "<br />query: ".$query;
$result=mysqli_query($con, $query);
$num=mysqli_num_rows($result);

//echo $result;               /*enabling this breaks the page for debugging*/
//echo $num;


$i=0;
date_default_timezone_set('America/Chicago');
$today = date("m-d-Y");
echo $today;
list($monthNow,$dayNow,$yearNow) =explode("-",$today);
$daysInMonthNow = (cal_days_in_month(CAL_GREGORIAN, $monthNow, $yearNow));
 
while ($i < $num) {
    
    $row = mysqli_fetch_row($result);
    
    //$f1[$i]=mysql_result($result,$i,"store");
    $f1[$i]=$row[0];
    
    //$dueOn[$i]=mysql_result($result,$i,"due_on");
    $dueOn[$i]=$row[1];
    
    //$f3=mysql_result($result,$i,"last_payment");
    $f3=$row[2];
    
    //$f4=mysql_result($result,$i,"last_amount");
    $f4=$row[3];
    
    //$f5 = date_pay.$i;
    
    list($year,$month,$day) = explode("-",$f3);
    
    $lastMonthPaid = $month;
    $lastDayPaid=$day;
    $lastYearPaid=$year;
    
    
    
    
    
    if($lastDayPaid<$dueOn[$i] && $lastMonthPaid== $monthNow){
         $daysLeftArray[$i] = (($daysInMonthNow - $dayNow) + $dueOn[$i]);
        $paidArray[$i] = "paid";
        $newMonthArray[$i] = $monthNow +1;
        $case[$i] = 1;
    }
    else if($lastDayPaid >$dueOn[$i] && ($monthNow - $lastMonthPaid) ==1){
        $daysLeftArray[$i] = (($daysInMonthNow - $dayNow) + $dueOn[$i]);
        $paidArray[$i] = "paid";
        $newMonthArray[$i] = $monthNow +1;
        $case[$i] = 2;
    }
    
    else if ($dueOn[$i] < $dayNow){
        $paidArray[$i] ="owe";
        $daysLeftArray[$i] = (($daysInMonthNow - $dayNow) + $dueOn[$i]);
        $newMonthArray[$i] = $monthNow +1;
        $case[$i] = 3;
      
    }
    else{
        $daysLeftArray[$i] = ($dueOn[$i] - $dayNow);
        $newMonthArray[$i] = $monthNow;
        $case[$i] = 4;
    }
    //echo " dueOn: ".$dueOn." daynow: :".$dayNow." daysInMonth : ".$daysInMonthNow." daysLeftArray: ".$daysLeftArray." newMonthArr: ".$newMonthArray." case: ".$case."<br />";

    $i++;
}

mysqli_close($con);






/*

echo $procMon;
echo "<br />";
echo $monthNow;
echo "<br />";
echo $procPay;
echo "<br />";
echo $newMonthArray[$procPay];

echo $procPay;
echo "<br />";

echo $procDay;
echo "<br />";
echo $procYear;
echo "<br />";
echo $procAmount;
echo "<br />";

echo $query;
echo $queryDays;
echo $queryPay;
echo $queryYear;
echo $queryAmount;
*/



 
// Loop through the $_POST array, which comes from the form...


    // first need to make sure this is an allowed field
  
        // is this a required field?
       
       if(($newMonthArray[$procPay] - $procMon) >2 )
   
        {
        $errors[] = "Month Selected is out of range to give payment!";
         }
       
      if($procYear > $yearNow+1 && $procMon > 1){
          $errors [] = "Cannot give payments in future years!";
      }
      
      if($procMon > $monthNow)
         {
         $errors[]="Cannot give payments in future months!";
         }
       
       
       if(($procMon ==$monthNow) && ($procDay > $dayNow))
       {
       $errors[]="Cannot give payments in the future of same month!";
       }
       
       if((($newMonthArray[$procPay] - $procMon)==2)&&($procDay < $dueOn[$procPay]))
       {
       $errors[]="Payment is out of date, make payment for current due date.";
       }
       
       if($procAmount < 0)
       {
       $errors[]="You must input a positive number for payment";
       }
       
       if($procAmount == '')
        {
            $errors[] = "The field Amount Paid is required.";
        }
        
       
       if($procMon == "")
        {
            $errors[] = "The field Month is required.";
        }
        if($procDay == "")
        {
            $errors[] = "The field Day is required.";
        }
        if((!is_numeric($procAmount))&&$procAmount != "")
        {
          $errors[] = "The amount is not a number.";
        }
        
        if($procMon == "02" && $procDay>28){
        $errors[] = "February only has 28 days.";
        }
       
       if(($procMon =="04") && ($procDay>30)){
           $errors[] = "April only has 30 days";
        }
        
        if ($procMon =="06"&& $procDay>30) {
        $errors[] = "June only has 30 days";
        }
       
       if($procMon =="09" && $procDay>30){
        $errors[] = "June only has 30 days";
        }
       
       if($procMon =="11" && $procDay>30){
         $errors[] = "November only has 30 days";
         }
         
         	 
 
// were there any errors?
if(count($errors) > 0)
{
  
   $errorString = '<p>There was an error processing the form.</p>';
   
    $errorString .= '<ul>';
    foreach($errors as $error)
    {
        $errorString .= "<li>$error</li>";
    }
    $errorString .= '</ul>';
 // echo( "<body> <br /> <a href = 'bills.php'>Back to Bills</a><br />");
  echo $errorString;
    // display the previous form
    include 'bills.php';
}
else
{
header("Location: bills.php");

  $sql="Update `{$_SESSION['db_num']}` set last_payment ='$lastPayment', last_amount ='$procAmount' where store ='$f1[$procPay]'";
//echo $sql;  
 //$sqlForMessages="Update messages set paid = 0 where store = '$f1[$procPay]' AND table_name = `{$_SESSION['db_num']}`";

$con = mysqli_connect($localhost,$DBusername,$DBpassword,$database);
//@mysql_select_db($database) or die( "Unable to select database, second one");
 
  if (!mysqli_query($con, $sql))
  {
  die('Error: ' . mysqli_error());
  }
  
//print("<a href='bills.php' />Back to Bills</a>");
//echo "1 record added";


mysqli_query($con, "INSERT INTO `history{$_SESSION['db_num']}` (store, last_payment, last_amount, due_on, paidOn) VALUES('$f1[$procPay]', '$lastPayment', '$procAmount','$dueOn[$procPay]', NOW())") 
or die("Error: ".mysqli_error()); 

mysqli_query($con, "Update messages set paid = 0 where bill = '$f1[$procPay]' AND table_name = `{$_SESSION['db_num']}`") 
or die("Error: ".mysqli_error()); 

mysqli_close($con);
   
    
//print("<a href='bills.php' />Back to Bills</a>");
}
?>