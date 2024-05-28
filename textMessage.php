<?php

require('dbConfig.php');
/*
$localhost="anaRoot.db.10947084.hostedresource.com";
$DBusername="anaRoot";
$DBpassword="Incubus1!";
$database="anaRoot";
*/
$con = mysql_connect($localhost,$DBusername,$DBpassword);
@mysql_select_db($database) or die( "Unable to select database");

// its telling you here how to add numbers to names of tables 
//mysql_query("SELECT * FROM {$_SESSION['SESS_LOGIN']}_blog");

$today = date("m-d-Y", time()+7320);
list($monthNow,$dayNow,$yearNow) = explode("-",$today);
$daysInMonthNow = (cal_days_in_month(CAL_GREGORIAN, $monthNow, $yearNow));


$query="SELECT * FROM messages where paid = 0 order by bill";
$result=mysql_query($query);
$num=mysql_num_rows($result);
$i=0;

while ($i < $num) {
$row = mysql_fetch_row($result);
$name=mysql_result($result,$i,"name");
$phone=mysql_result($result,$i,"phone_number");
$carrier=mysql_result($result,$i,"carrier");
$table=mysql_result($result,$i,"table_name");
$bill=mysql_result($result,$i,"bill");
$paid=mysql_result($result,$i,"paid");


//echo $row;
echo $name."   ";
//echo $phone;
//echo $carrier;
//echo $table;
echo $bill."   ";



$email = "";
if ($carrier =="verizon"){
 $email = "@vtext.com";
}
else if($carrier =="sprint"){
    $email = "@pm.sprint.com";
}
else if($carrier == "tmobile"){
    $email = "@tomomail.net";
}
else if($carrier == "att"){
    $email = "@txt.att.net";
}
else if($carrier =="virgin"){
    $email = "@vmobl.com";
}

//echo $email;


$query2="SELECT * FROM  `$table` where store = '$bill'";
$result2=mysql_query($query2);

$num2=mysql_num_rows($result2);

//echo $num2;
$i2=0;
while ($i2 < $num2) {
$row2= mysql_fetch_row($result2);
$due_on=mysql_result($result2,$i2,"due_on");
$last_amount=mysql_result($result2,$i2,"last_amount");
$last_payment=mysql_result($result2,$i2,"last_payment");
$store=mysql_result($result2,$i2,"store");


list($year,$month,$day) = explode("-",$last_payment);
$lastMonthPaid = $month;
$lastDayPaid=$day;
$lastYearPaid=$year;
//echo $website;
$daysInMonth = (cal_days_in_month(CAL_GREGORIAN, $month, $year));





if($due_on <= $dayNow)
{ 
  $newMonthArray = $monthNow +1;
// echo "case1";
  }

else if ($due_on > $dayNow && (($monthNow-$lastMonthPaid) == 1))
{ 
  $newMonthArray = $monthNow+1;
// echo "case2";
  }
  else if ($due_on> $dayNow){
      $newMonthArray = $monthNow;
    //echo "case3";
  }
  else
    {
        echo "no case";
    }
  

/*if($i==0){
    echo "lastDayPaid: ".$lastDayPaid."<br />";
    echo "dueOn: ".$due_on."<br />";
    echo "newMonthArray: ".$newMonthArray."<br />";
    echo "lastMonthPaid: ".$lastMonthPaid."<br />";
    echo "daysLeftArray: ".$daysLeftArray."<br />";
    echo "monthNow: ".$monthNow."<br />";
}
  */
  


if($lastDayPaid<$due_on && $lastMonthPaid == $monthNow){     /*|| $lastDayPaid==$dueOn) */ 
 $daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on);
$paidArray = "paid";
$newMonthArray = $monthNow +1;
    if($newMonthArray ==13)
	{$newMonthArray=1;
	 $yearNow =$yearNow+1;}
	$case = 1;
    
    
}

else if($lastDayPaid >=$due_on && ((($newMonthArray - $lastMonthPaid) ==2)||(($newMonthArray - $lastMonthPaid) ==1)) 
&& ($newMonthArray == $monthNow)){
    
    $daysLeftArray = ($due_on- $dayNow);
$paidArray = "paid";




    if($newMonthArray == 13)
    {$newMonthArray =1;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on);
	}

	 if($newMonthArray == 14)
	{$newMonthArray =2;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow+1));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on);
	
	}
$case = 1.5;
    
}
else if($lastDayPaid > $due_on &&($newMonthArray - $lastMonthPaid ==1) && $newMonthArray = $monthNow+1){
    
  
  
  
  
$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, $monthNow+1, $yearNow));
$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on + $daysInMonthNext);
$paidArray = "paid";
$newMonthArray = $newMonthArray+1;




    if($newMonthArray == 13)
	{$newMonthArray =1;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on+$daysInMonthNext);
	}

	 if($newMonthArray == 14)
	{$newMonthArray =2;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow+1));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on);
	
	}
$case = 1.75;

}



else if($lastDayPaid >=$due_on && ((($newMonthArray - $lastMonthPaid) ==2) ||
($newMonthArray - $lastMonthPaid == 0))){ /*|| $lastDayPaid==$dueOn)*/


$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, $monthNow+1, $yearNow));
$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on);
$paidArray = "paid";
$newMonthArray = $newMonthArray;




	if($newMonthArray == 13)
	{$newMonthArray =1;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on);
	}

	 if($newMonthArray == 14)
	{$newMonthArray =2;
	$yearNow= $yearNow+1;
	$daysInMonthNext = (cal_days_in_month(CAL_GREGORIAN, 1, $yearNow+1));
	$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on);
	
	}
$case = 2;

}




else if ($due_on < $dayNow){

$paidArray ="owe";
$daysLeftArray = (($daysInMonthNow - $dayNow) + $due_on);
  $newMonthArray = $monthNow +1;
  if($newMonthArray == 13)
{$newMonthArray =1;
$yearNow= $yearNow+1;}
  $case = 3;

}
 
else{
 $daysLeftArray = ($due_on - $dayNow);
  $newMonthArray = $monthNow;
  $case = 4;
  
}




$formattedNum= $phone.$email;
echo $formattedNum;
echo $daysLeftArray."<br />";

if ($num2 >0){
    
if($bill == "Jesses Rent"){
    $campaCut = (1050*(.3));
    $froyCut = (1050*(.3));
    $danielaCut = 200;
    $jesseCut = (1050 * (.4));
    
    switch($name){
        case "Daniela": $cut = number_format($danielaCut, 2, '.', '');;
        break;
        case "Jesse": $cut = number_format($jesseCut, 2, '.', '');;
        break;
        case "Froy": $cut = number_format($froyCut, 2, '.', '');;
        break;
        case "Campa": $cut = number_format($campaCut, 2, '.', '');;
        break;
    }
  
    
    if($daysLeftArray == 5 || $daysLeftArray == 2 || $daysLeftArray == 1){
    mail("$formattedNum", "$name", "The Rent is due in $daysLeftArray days. Your cut = \$$cut .", "From: BillWarn <jsanchez@accruent.com>\r\n");
    }//end if daysleftarray



    if($daysLeftArray == 0){
   mail("$formattedNum", "$name", "The Rent is due today!!!. Your cut = \$$cut .", "From: BillWarn <jsanchez@accruent.com>\r\n");
    }//end if
    
}//end if bill == Jesses Rent

else if ($bill == "TWC internet" || $bill == "DirectTV" || $bill == "COA Utilites" || $bill == "Texas Gas"){
       
       if($name == "Jesse" || $name =="jesse"){
           $tempCut = (($last_amount/5)*2);
           $cut = number_format($tempCut, 2, '.', '');
           }
         else{
             $tempCut = ($last_amount/5);
             $cut = number_format($tempCut, 2, '.', '');
             }
              
    if($daysLeftArray == 5 || $daysLeftArray == 2 || $daysLeftArray == 1){
    mail("$formattedNum", "$name", "$bill due in $daysLeftArray days. Your cut = \$$cut .", "From: BillWarn <jsanchez@accruent.com>\r\n");
    }//end if daysleftarray

    if($daysLeftArray == 0){
   mail("$formattedNum", "$name", "$bill is due today!!!. Your cut = \$$cut .", "From: BillWarn <jsanchez@accruent.com>\r\n");
    }
}//end if bill == TWC internet or DirectTV or COA utilities or Texas Gas 


else {
    $cut = number_format($last_amount, 2, '.', '');

    if($daysLeftArray == 5 || $daysLeftArray == 2 || $daysLeftArray == 1){
    mail("$formattedNum", "$name", "$bill due in $daysLeftArray days. Your cut = \$$cut .", "From: BillWarn <jsanchez@accruent.com>\r\n");
    }//end if daysleftarray

    if($daysLeftArray == 0){
    mail("$formattedNum", "$name", "$bill is due today!!!. Your cut = \$$cut .", "From: BillWarn <jsanchez@accruent.com>\r\n");
    }
}//end else bill == anything else (not roommate bills)


}//end if num2 >0
echo "CUT: $".$cut."    ";
echo "<br />";
$i2++;
}//end 2nd loop with only 1 result

$today = date("m-d-Y");
list($monthNow,$dayNow,$yearNow) = explode("-",$today);


$i++;
}//end outer while loop that goes through all of the messages table one by one
?> 
