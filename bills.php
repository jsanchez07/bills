<?php
require_once('session_init.php');
 
 //echo $_SESSION['db_num'];

 
 if(!isset($_SESSION['role'])) {
      header("Location: logout.php");
 }
 if ($_SESSION['role'] == null) {
 header("Location: logout.php");
 }
 if ($_SESSION['role'] == 0){
  header("Location: logout.php");
 
 }
 
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>


<link rel="stylesheet" type="text/css" href="style.css" media="all"/>
<script src="scripts.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

</head>
<body>
    
<script>
    const arrayOfStores = new Array();
    const arrayOfAmounts = new Array();
</script>

	<nav role ="navigation">
		<ul class="navBar">
			<li><a href="addBill.php">Add bill</a></li>
			<li><a href="deleteBill.php">Delete bill</a></li>
			<li><a href="updateBill.php">Update bill</a></li>
			<li><a href="historyBills.php">History</a></li>
			<li><a href="addUser.php">Add user</a></li>
			<li><a href="viewDeleted.php">View deleted</a></li>
			<li><a href="finances.php">Finances</a></li>
			<li><a href="alertManager.php">Alert manager</a></li>
		</ul>
	</nav>
		
	<nav class="userNav">
    	<div class="dropdown">
            <button onclick="myFunction()" class="dropbtn">Logged in as <uN><?php echo $_SESSION['my_username']?></uN></button>
            <div id="myDropdown" class="dropdown-content">
                <a href="logout.php">Logout</a>
                
            </div>
        </div>
	</nav>	


<br />
<br />
<?php
 require('dbConfig.php');


$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);


$query="SELECT store, due_on, last_payment, last_amount, website, username, password, auto_pay, recurring_amount FROM `{$_SESSION['db_num']}` order by store";
$result=mysqli_query($con, $query);

$num=mysqli_num_rows($result);


date_default_timezone_set('America/Chicago');
$today = date("m-d-Y", time());


?>


    <div id = "timeDisplay">
        <?php
         print date("l, jS \of F, Y h:i:s A");
         ?>
    </div>

<?php

$totalAmount = 0;
$firstHalfTotal = 0;
$secondHalfTotal = 0;
mysqli_close($con);
?>


            <ul class = "listOfHeaders">
                <li class= "storeHeader">Store</li>
                <li class= "dueDateHeader">Next Due Date</li>
                <li class= "daysLeftHeader">Days Left</li>
                <li class= "lastPayDateHeader">Last Payment Date</li>
                <li class= "lastPayAmountHeader">Last Payment Amount</li>
                <li class= "statusHeader"> Status</li>
                <li class= "paymentHeader">Make a Payment</li>
                
            </ul>
            <hr />



<table border="0" >
<div class = "bill1">
    <image src="images/bill1.jpeg">
</div>

<div class ="bill2">
    <image src="images/bill2.jpeg">
</div>

<div class = "cash">
    <image src="images/cash.jpeg">
</div>


<?php
   
$i=0;



list($monthNow,$dayNow,$yearNow) = explode("-",$today);


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
        $row = mysqli_fetch_row($result);
       
           
        $f1=$row[0];
        //echo ("store name: ".$f1."<br />");
        
        $dueOn=$row[1];
        //echo ("  due on : ".$dueOn."<br />");

        $f3=$row[2];
        //echo (" last payment: ".$f3."<br />");
        
        $f4=$row[3];
        //echo (" last_amount: ".$f4."<br />");
        
        $website=$row[4];
        //echo (" website: ".$website."<br />");
        
        $user=$row[5];
        //echo (" username: ".$user."<br />");
        
        $pass=$row[6];
        //echo (" password: ".$pass."<br />");
        
        $auto_pay=$row[7];
        //echo (" auto_pay: ".$auto_pay."<br />");
        
        $recurring_amount=$row[8];
        //echo (" recurring_amount: ".$recurring_amount."<br />");
        
        $credentials="username: ".$user."\n"."password: ".$pass;
        //echo (" credentials: ".$credentials."<br />");
        

        
        
        list($year,$month,$day) = explode("-",$f3);
        $lastMonthPaid = $month;
        //echo ("lastMonthPaid: ".$lastMonthPaid."<br />");
        $lastDayPaid=$day;
        //echo ("lastDayPaid: ".$lastDayPaid."<br />");
        $lastYearPaid=$year;
        //echo ("lastYearPaid: ".$lastYearPaid."<br />");
        //echo ("<br />");

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
  


$store_dashed = preg_replace('/[^a-zA-Z0-9\-]/', '-', $f1); 
   //echo $store_dashed;
$id_for_dropdown = "my-payment-dropdown-$store_dashed";
$id_for_cred_dropdown = "my-cred-dropdown-$store_dashed";
$id_for_button = "pay-button-$store_dashed";


  
  $totalAmount = $totalAmount +$f4;
  
  if($dueOn < 15)
  {
  $firstHalfTotal = $firstHalfTotal+$f4;
  }
  else
  {
  $secondHalfTotal = $secondHalfTotal+$f4;
  }
  //echo "\n".$i."= ".$newMonthArray."\n";

//print($rowid[0]);
//print ($row[0]);

if($lastDayPaid<=$dueOn && $lastMonthPaid == $monthNow){     /*|| $lastDayPaid==$dueOn) */ 
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
	{
	 $newMonthArray =1;
	 $yearNow= $yearNow+1;
	 $daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow));
	 $daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn+$daysInMonthNext);
	}

    if($newMonthArray == 14)
	{
	 $newMonthArray =2;
	 $yearNow= $yearNow+1;
	 $daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow+1));
	 $daysLeftArray = (($daysInMonthNow - $dayNow) + $dueOn+$daysInMonthNext);
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


$status = "";

    if(($newMonthArray - $lastMonthPaid) > 2 ||($newMonthArray - $lastMonthPaid) == 2 && $lastDayPaid<$dueOn || $lastYearPaid == 1983) { 
        //<!--GRAY BACKGROUND-->
        echo '<tr class = "gray">';
        $status = "gray";
        echo '<style>
                    .this-row-'.$store_dashed.'{
                            background-color: #e0e0e1;
                    }
            </style>';
    }
    else if($daysLeftArray < 10){ 
        //<!--RED BACKGROUND-->
        echo '<tr class= "red">';
        $status = "red";
        echo '<style>
                    .this-row-'.$store_dashed.'{ background-color: #ffcccc; }
            </style>';
    }
    else if($daysLeftArray < 15 && $daysLeftArray > 9)
    {   
        //<!--YELLOW BACKGROUND-->
        echo '<tr class = "yellow">';
        $status = "yellow";
        echo '<style>
                    .this-row-'.$store_dashed.'{ background-color: #ffffcc; }
            </style>';
    }
    else
    { 
        //<!--GREEN BACKGROUND-->
        echo '<tr class = "green">';
        $status = "green";
        echo '<style>
                    .this-row-'.$store_dashed.'{ background-color: #eeffee; }
            </style>';
        
    }
    
    $statusImg = "";
    if($status == "green")
    {
        $statusImg = "<img src='images/icon-green-checkmark.png' style='width:25px;height:25px;'>";
    }
    else if ($status == "yellow")
    {
        $statusImg = "<img src='images/icon-orange-warning.png' style='width:25px;height:25px;'>";
    }
    else if ($status == "red")
    {
        $statusImg = "<img src='images/icon-red-x.webp' style='width:25px;height:25px;'>";
    }
    else
    {
       $statusImg = "<img src='images/icon-gray-question-mark.png' style='width:25px;height:25px;'>"; 
    }
    
   
    
?>
</form>

 <ul>
        <li class = "rowsOfBills">
            <ul class = "listOfBills">
                <div class = "this-row-<?php echo $store_dashed?>">
                <li class= "store"><?php echo $f1; ?></li>
                <li class= "nextDue"><?php echo $newMonthArray."-".$dueOn."-".$yearNow; ?></li>
                <li class= "daysLeft"><?php echo $daysLeftArray; ?></li>
                <li class= "lastPayment"><?php echo $f3; ?></li>
                <li class= "payAmount">$<?php echo $f4; ?></li>
                <li class= "status"><?php echo $statusImg?></li>                 
                <li class= "paymentButtonListItem"> 
                    <?php 
                    if($auto_pay == 1) {
                        echo '<a href="updateBill.php?store='.urlencode($f1).'" class="editButton">Edit</a>';
                    } else {
                        echo '<button onCLick="payDropFunction(\''.$id_for_dropdown.'\', \''.$id_for_button.'\')" id=\''.$id_for_button.'\' class="paymentButton" >Pay Now</button>';
                    }
                    ?>
                </li>
                <div class="payment-dropdown-content" id="<?php echo $id_for_dropdown ?>"">
                    <button class= "link-to-website-button"  onMouseOver = "mouseOver('<?php echo $id_for_cred_dropdown ?>')" onMouseOut = "mouseOut('<?php echo $id_for_cred_dropdown ?>')"> 
                        <a href = "<?php echo $website; ?>" target="_blank" alt="<?php echo $credentials ?>"> Go to <?php echo $f1; ?>'s website</a>
                    </button>
                        <div id="<?php echo $id_for_cred_dropdown ?>">
                            Username: <?php echo $user ?>&nbsp&nbsp
                            Password: <?php echo $pass ?>
                        </div>
                    <div class = "payForm">    
                        <form id = "makePayment" name = "makePaymentForm" Method = "POST" action= "confirmPay.php">
                            <div id ="payFormTitle">
                                <h2>
                                    Capture Payment
                                </h2>
                            <div id = "payFormHeaders">
                                <ol>
                                    <li>Payment Amount</li>
                                    <li>Payment Date</li>
                                    <li>Capture Pay</li>
                            </div>
                            <div id ="payFormRow">
                                <input type = "text" size ="9" name = "amount[]"/>
                                <select id="sl1[]" name="slct1[]" class="selectors">
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
                                    <option value="12">Dec</option>
                                </select>
                                <select id="sl2[]" name="slct2[]" class="selectors">
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
                                    <option value="31">31</option>
                                </select>
                                <select id="sl3[]" name="slct3[]" class="selectors">
                                    <option value="<?php echo $yearNow-1 ?>"><?php echo $yearNow-1 ?></option>
                                    <option selected value ="<?php echo $yearNow ?>"><?php echo $yearNow ?></option>
                                    <option value="<?php echo $yearNow+1 ?>"><?php echo $yearNow+1 ?></option>
                                    <option value="<?php echo $yearNow+2 ?>"><?php echo $yearNow+2 ?></option>
                                  
                                 </select>
                                 
                                <div align = "center"><input type = "submit" id = "capturePaymentButton" name="pay[<?php echo $i?>]" value ="pay"/>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
             </div>
                    
            
        </li>
  
    </ul>
    
    
    


<?php 

//$today = date_default_timezone_get();
$today = date("m-d-Y");
//$today = date("m-d-Y", date_default_timezone_get());
list($monthNow,$dayNow,$yearNow) = explode("-",$today);
?>
<script>
    arrayOfStores.push("<?php echo $f1?>");
    arrayOfAmounts.push("<?php echo $f4?>");
    /*console.log(arrayOfStores);*/
</script>

<?php
$i++;
}
 mysqli_free_result($result);
 $total_amount_formatted = number_format($totalAmount, 2, '.', ',');
 
 

?>

<div id="monthlyBreakdown">
    <h2>Total amount : $<?php echo $total_amount_formatted ?></h2>
    <h3>Monthly Breakdown</h3>
    <div class="breakdownContainer">
        <div class="breakdownHalf">
            <h4>First Half (1st - 14th)</h4>
            <p class="breakdownAmount">$<?php echo number_format($firstHalfTotal, 2, '.', ','); ?></p>
        </div>
        <div class="breakdownHalf">
            <h4>Second Half (15th - End of Month)</h4>
            <p class="breakdownAmount">$<?php echo number_format($secondHalfTotal, 2, '.', ','); ?></p>
        </div>
    </div>
</div>

<canvas id="myChart"></canvas>

<script>
    var barColors = [
        "red",
        "blue",
        "yellow",
        "orange",
        "purple",
        "black",
        "green",
        "brown",
        "aquamarine",
        "lightpink",
        "magenta",
        "navy",
        "orangered",
        "teal"
    ];
    new Chart("myChart", {
    type: "pie",
        data: {
            labels: arrayOfStores,
            datasets: [{
                backgroundColor: barColors,
                data: arrayOfAmounts
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: "Bills Broken Down"
            },
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: 20,
                    bottom: 20
                }
            },
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 15,
                    padding: 15
                }
            }
        }
    });

</script>
 </body>

</html>