<?php

session_start();
 
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
 <?php
 date_default_timezone_set('America/Chicago');
 
// print_r($_POST);
 if($_POST['from']){
 $from = $_POST['from'];
 $start_date = date("YmdHis", strtotime("$from"));
 //echo $start_date."<br />";
 //echo $_POST['datepicker'];
 }
 else{
 $start_date = '19000101000000';        // from date, to..
 }
 
 if($_POST['to']){ 
 $to = $_POST['to'];
 $toTemp = date("Ymd", strtotime("$to"))."000000";
     if (strtotime($toTemp) > strtoTime(date('Ymd')))
     {
     $end_date = date('YmdHis');
     }
     else
     {
         $end_date = $toTemp;
     }
 //echo $end_date;
 }
 else{
   $end_date = date('YmdHis');        // now
 }
 
 // transaction retrieval date range


 

// this function is just used to generate a random transaction id below
function getRandomString($length = 40, $charset = 'alphanum') {
    $alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '0123456789';
    switch ($charset) {
        case 'alpha':
            $chars = $alpha;
            break;
        case 'alphanum':
            $chars = $alpha . $num;
            break;
        case 'num':
            $chars = $num;
            break;            
    }
    
    $randstring='';
    $maxvalue=strlen($chars)-1;
    for ($i = 0; $i < $length; $i++)
      $randstring .= substr($chars, rand(0, $maxvalue), 1);
    return $randstring;
}

// user login info
$user = 'i3mj23';
$pass = 'Incubus1!';

// account info
$accnt_num = '1500775835';
$accnt_type = 'CHECKING';

// date and a random unique transaction id
$txn_id = getRandomString(6);
$tz_offset = date('Z')/3600;
$date = date("YmdHis[$tz_offset:T]");

// Bank of America info
$org = 'IBC';
$fid = '1001';
$bank_id = '114902528';


$xml = "
<?xml version='1.0'?>
OFXHEADER:100
DATA:OFXSGML
VERSION:102
SECURITY:NONE
ENCODING:USASCII
CHARSET:NONE
COMPRESSION:NONE
OLDFILEUID:NONE
NEWFILEUID:NONE
<OFX>
    <SIGNONMSGSRQV1>
        <SONRQ>
            <DTCLIENT>$date</DTCLIENT>
            <USERID>$user</USERID>
            <USERPASS>$pass</USERPASS>
            <LANGUAGE>ENG</LANGUAGE>
            <FI>
                <ORG>$org</ORG>
                <FID>$fid</FID>
            </FI>
            <APPID>QWIN</APPID>
            <APPVER>1800</APPVER>
        </SONRQ>
    </SIGNONMSGSRQV1>
    
    <BANKMSGSRQV1>
        <STMTTRNRQ>
            <TRNUID>$txn_id</TRNUID>
            <STMTRQ>
                <BANKACCTFROM>
                    <BANKID>$bank_id</BANKID>
                    <ACCTID>$accnt_num</ACCTID>
                    <ACCTTYPE>$accnt_type</ACCTTYPE>
                </BANKACCTFROM>
                <INCTRAN>
                    <DTSTART>$start_date</DTSTART>
                    <DTEND>$end_date</DTEND>
                    <INCLUDE>Y</INCLUDE>
                </INCTRAN>
            </STMTRQ>
        </STMTTRNRQ>
    </BANKMSGSRQV1>
</OFX>";
$all = $headers+$xml;

$ch = curl_init();
$ch1 = curl_setopt($ch, CURLOPT_URL, 'https://ibcbankonline2.ibc.com/scripts/serverext.dll');
$ch2 = curl_setopt($ch, CURLOPT_POST, 1.1);
$ch3 = curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-ofx'));
$ch4 = curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
$ch5 = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$ch6 = curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$result = curl_exec($ch);
//if ($result) {
//    $correctedXML = preg_replace('/<([A-Za-z]*?)>(?![\s\S]*?<\/\1>)(.+)/m', '<\1>\2</\1>', $result);
 //  $x = new SimpleXMLElement($correctedXML);
//}
//else
 //   echo "<pre>CURL ERROR: " . curl_error($ch) . "</pre>";
//echo $correctedXML;



curl_close ($ch);






//echo $result;
//echo $stream;
   





$file = 'file.ofx';
// Write the contents back to the file
file_put_contents($file, $result);

$fileText = 'fileText.txt';
file_put_contents($fileText, $result);

$als = array();
$sla = array();

 // 1. Read in the file
  $cont = file_get_contents($file);
  // 2. Separate out and remove the header
  $bline = strpos($cont,"<OFX>");
 // echo $bline."\n";
  $head = substr($cont,0,$bline-2);
  //echo $head."\n";
  $ofx = substr($cont,$bline-1);
  // 3. Examine tags that might be improperly terminated
  $ofxx = $ofx;
 //  echo "<h2>phase1</h2><br />".$ofxx;
   $file1 = 'file1.txt';
    file_put_contents($file1, $ofxx);
     $pos = 0;
     $pos2 = 2;
    // $ele = 'xml';
  $tot=0;
  while (($pos2-$pos-1) != -1) {
      $pos = strpos($ofxx,'<');
    $tot++;
    $pos2 = strpos($ofxx,'>');
    $ele = substr($ofxx,$pos+1,$pos2-$pos-1);
    if (substr($ele,0,1) =='/') $sla[] = substr($ele,1);
    else $als[] = $ele;
    $ofxx = substr($ofxx,$pos2+1);
 
    file_put_contents($file1, $ofxx);
   $ofxx = file_get_contents($file1);

 
  }
 // print "total: ".$tot."\n";
 // print_r($als);
 // print_r($sla);
  $adif = array_diff($als,$sla);
  $adif = array_unique($adif);
  $ofxy = $ofx;
  // 4. Terminate those that need terminating
 // ini_set('max_execution_time', 300);
 
  foreach ($adif as $dif) {
    $dpos = 0;
    while ($dpos = strpos($ofxy,$dif,$dpos+1)) {
      $npos = strpos($ofxy,'<',$dpos+1);
      $ofxy = substr_replace($ofxy,"</$dif>\n<",$npos,1);
      $dpos = $npos+strlen($ele)+3;
    }
  }
  // 5. Deal with special characters
  $ofxy = str_replace('&','&amp;',$ofxy);
  // 6. write the resulting string to the screen
  //echo "<h2>OFXY</h2><br />".$ofxy;


//$xmlFile = 'xmlFile.ofx'
file_put_contents($fileText, $ofxy);


 
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>

<script type="text/javascript" src="scripts.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" media="all"/>
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  

</head>
<body>
<a href = 'bills.php'>Back to Bills</a>


   
   <form id = 'ofx' name = 'form2' Method = 'POST' action= 'ofxtest.php'>
<p align = 'center'>From: <input type='text' id='datepicker' name ='from'>&nbsp &nbsp To : <input type='text' id='datepicker2' name='to'>
 <input type='submit' value='Submit'></p>
  </form>


<?php



 // test ofx
  $xmlstr = file_get_contents($fileText);
  $xml = new SimpleXMLElement($xmlstr);
  
  $file12 = 'xml.xml';
// Write the contents back to the file
file_put_contents($file12, $xmlstr);
  
  
  // Let's get the balance first
  $bal = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->BALAMT;
  $dat = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->DTASOF;
   $availbal = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->AVAILBAL->BALAMT;
  $data = strtotime(substr($dat,0,8));
  $datb = date('Y-m-d',$data);
  echo "<table align ='center'><tr style = 'background-color:white;'><td colspan='2'align ='center'><h2>$org</h2></td>
             <td colspan='4' align = 'right'>Available Balance: <b> \$$availbal </b> <br />
             Current Balance: <b>\$$bal</b> <br />as of <b>$datb</b></td></tr>";
 
 (float)$pend = (float)$bal - (float)$availbal;
// echo $pend;
 ?>
  
 

<tr style ="background-color:white;" align="center">
<td><rows>Date Posted</rows></td>
<td><rows>FITID</rows></td>
<td><rows>Name</rows></td>
<td><rows>Transaction Amount</rows></td>
<td><rows>Transaction Type</rows></td>

</tr>
  
  
  
  <?php
  if($pend > 0.00){
 echo "<tr style ='background-color:beige;'><td>Not Yet Posted</td><td> 000 </td><td>PENDING TRANSACTIONS </td>
 <td>-$pend</td><td>PEND</td></tr>";

  }
  
  
  // Now point at the array of transactions and show the detail for each
  $trans = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN;
  foreach ($trans as $tran) {
    $trandate = trim($tran->DTPOSTED);
   $tfitid = $tran->FITID;
   $tname = $tran->NAME;
    $tdate = date("Y-m-d",strtotime(substr($trandate,0,8)));
    $tranamt = $tran->TRNAMT;
    $trancrdr = $tran->TRNTYPE;
  // echo "<br />";
   echo "<tr style ='background-color:beige;'><td>$tdate</td><td> $tfitid </td><td>$tname </td><td>$tranamt</td><td> $trancrdr</td></tr>";
   // echo "$tdate $tfitid $tname $tranamt $trancrdr\n";
  }
  ?>

   
 
</body>
</html>
 <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
   $(function() {
    $( "#datepicker2" ).datepicker();
  });
    $(function() {
    $( "#accordion" ).accordion();
  });
  </script>


