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

$financesT = "`finances{$_SESSION['db_num']}`";
$mainT="`{$_SESSION['db_num']}`";

$connection = mysqli_connect($localhost, $DBusername, $DBpassword, $database) 
or die("Could not connect to database"); 


$query_string = "SELECT $mainT.store, $financesT.amount_owed,
					$financesT.APR, $financesT.payment, $mainT.last_amount 
					FROM $financesT
					LEFT JOIN $mainT
					ON $financesT.store = $mainT.store 
					ORDER by store ASC";
$result_id = mysqli_query($connection, $query_string) 
    or die("display_db_query:" . mysqli_error()); 
    
$rows = mysqli_num_rows($result_id) 
    or die("display_db_query:" . mysqli_error());    
   
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
<TITLE>Finances</TITLE></HEAD> 
<BODY> 
 <form id = "abc" name = "form1" Method = "POST" action= "confirmSubmitFinances.php">
 <table border ='1' width ='100%' align='center'>

 <tr align ="center" style ="background-color: blue; color: white"><td>Store</td>
 <td>Amount Owed</td><td>APR</td><td>Last Payment</td><td>Time in months</td><td>Amount Paid</td><td>OVERRIDE Amount Owed</td>
 <td>OVERRIDE APR</td><td>SUBMIT</td><td>Payment?</td>
 </tr>
 <tbody>
<?php 
//ini_set('max_execution_time', 300); 
            
    $total = 0; 
    $totalAccPayments = 0;
    $totalPaymentsInMonth = 0;
       while ($i<$rows){
            $row = mysqli_fetch_row($result_id);
                
                echo "0: ".$row[0];
                echo "1: ".$row[1];
                echo "2: ".$row[2];
                echo "3: ".$row[3];
                echo "4: ".$row[4];
                echo "5: ".$row[5];
     
$store=$row[0];
$amountOwed=$row[1];
$APR=row[2];
$lastPayment=$row[4];
$payment=$row[5];

if (is_null($APR))
{
    $APR = 0;
}
 $monthlyAPR = ($APR/12)/100;
 $months = 0;
 $tempAmtOwed = $amountOwed;
 $accPayments = 0;
  
  while ($tempAmtOwed > 0)
 {
     if($tempAmtOwed < 1 || $lastPayment < 1)
     {
         break;
     }
       $tempAmtOwed = ($tempAmtOwed + ($tempAmtOwed*($monthlyAPR)) - $lastPayment);
       $accPayments = $accPayments+$lastPayment;
       
       $months++;
       //echo $store." ".$tempAmtOwd." ".$accPayments." ".$months."<br />";
       if( $tempAmtOwed > $amountOwed)
       {
        $accPayments = $amountOwed;
        $months = -1;
        break;
       }
 }         
         if($payment == '0')
          {
          echo '<tr style="background-color:gray; color:black">';
          }
           else
           {
           echo '<tr style="background-color:red; color:white">';
                   
           }
           echo "<TD name ='store[]'>".$store."</TD>";
           echo "<TD>$".$amountOwed."</TD><TD>".$APR."%</TD>";
           echo "<TD>$".$lastPayment."</TD><TD align='center'>".$months."</TD><TD>".$accPayments."</TD>";
           echo "<TD align = 'center'><input type = 'text' name ='amount[]'</TD><TD align = 'center'>";
           echo "<input type = 'text' name ='apr[]'</TD><TD align = 'center'><input type = 'submit' name='submit[]' value =";echo "SUBMIT".$i; echo "></TD>";
           echo "<td align = 'center'><a href = 'excludeBill.php?billName=$store' title=$store>x</td>";
           echo "</tr>";
      if($payment == '1')
       		{ 
       		  $totalAccPayments = $totalAccPayments + $accPayments;
              $total = $total + $amountOwed;
              $totalPaymentsInMonth = $totalPaymentsInMonth + $lastPayment;
             }
 
          $i++;
}

?>
<tr style ="background-color: blue; color: white"><td>Total</td><td>$<?php echo $total ?></td><td></td><td>$<?php echo $totalPaymentsInMonth ?></td><td></td><td>$<?php echo $totalAccPayments ?></td>
</tbody>
</TABLE>
</form>
<br />
<table width = "53%" border ="0">
<tr></tr>
</table>