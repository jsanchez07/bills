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
 
  
 function printDate() {
 echo "The Date and Time is: ";
 $today = date(DATE_RFC822);
 echo $today;
}
 
 print_r($_POST);
 if($_POST['from']){
 $from = $_POST['from'];
 echo $from;
 //echo $_POST['datepicker'];
 }
 if($_POST['to']){ 
 $to = $_POST['to'];
 echo $to;
 }
 
 
 

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

// transaction retrieval date range
$start_date = '20131130000000';        // from date, to..
$end_date = date('YmdHis');        // now
$apiURL = 'https://ibcbankonline2.ibc.com/scripts/serverext.dll';

function getInfo($apiURL,$user,$pass,$accnt_num,$accnt_type,$txn_id,$date,$org,$fid,$bank_id,$start_date,$end_date){

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


$ch = curl_init();
$ch1 = curl_setopt($ch, CURLOPT_URL, $apiURL);
$ch2 = curl_setopt($ch, CURLOPT_POST, 1.1);
$ch3 = curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-ofx'));
$ch4 = curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
$ch5 = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$ch6 = curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$result = curl_exec($ch);
curl_close ($ch);




$file = 'file'.$txn_id.'.ofx';
// Write the contents back to the file
file_put_contents($file, $result);

$fileText = 'fileText'.$txn_id.'.txt.';
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
   $file1 = 'file1'.$txn_id.'.txt';
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

// test ofx
  $xmlstr = file_get_contents($fileText);
  $xml = new SimpleXMLElement($xmlstr);
  // Let's get the balance first
  $bal = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->BALAMT;
  $dat = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->DTASOF;
  $data = strtotime(substr($dat,0,8));
  $datb = date('Y-m-d',$data);
  
  //delete the files
        unlink($file);
        unlink($file1);
        unlink($fileText);
 
 return $xml;
}  
 ?>

 <html>
 <script type="text/javascript" src="scripts.js"></script>

 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
 <link rel="stylesheet" type="text/css" href="style.css" media="all"/>
 
  <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
   $(function() {
    $( "#datepicker2" ).datepicker();
  });

  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>


  </script>




 <body>

 <div id="tabs">
  <ul>
    <li><a href="#tabs-1"><?php echo $org?></a></li>
    <li><a href="#tabs-2">Chase Bank</a></li>
    <li><a href="#tabs-3">Org 3?</a></li>
  </ul>
  <div id="tabs-1">
      <?php
       $xml = getInfo($apiURL,$user,$pass,$accnt_num,$accnt_type,$txn_id,$date,$org,$fid,$bank_id,$start_date,$end_date);
  
  $bal = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->BALAMT;
  $dat = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->DTASOF;
  $data = strtotime(substr($dat,0,8));
  $datb = date('Y-m-d',$data);
      ?>
   <form id = 'ofx' name = 'form2' Method = 'POST' action= 'ofxtest.php'>
<p align = 'center'>From: <input type='text' id='datepicker' name ='from'>&nbsp &nbsp To : <input type='text' id='datepicker2' name='to'>
 <input type='submit' value='Submit'></p>
  </form>
 <table align ='center'><tr style = 'background-color:white;'><td colspan='2'align ='center'><h2><?php echo $org ?></h2></td>
             <td colspan='4' align = 'right'>Balance: <b>$<?php echo $bal?></b> <br />as of <b><?php echo $datb ?></b></td></tr>
             
               <?php
    
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
             </table>

     </div>
  <div id="tabs-2">
   
   <?php
   // user login info
$user = 'hjsd2000';
$pass = 'i3mj23';

// account info
//$accnt_num = '929215572';
$accnt_type = 'CHECKING';

// date and a random unique transaction id
$txn_id = getRandomString(6);
$tz_offset = date('Z')/3600;
$date = date("YmdHis[$tz_offset:T]");

// Bank of America info
$org = 'ISC';
$fid = '2101';
//$bank_id = '111000614';

// transaction retrieval date range
$start_date = '20131130000000';        // from date, to..
$end_date = date('YmdHis');        // now
$apiURL = 'https://www.oasis.cfree.com/fip/genesis/prod/02101.ofx';
    $xml = getInfo($apiURL,$user,$pass,$accnt_num,$accnt_type,$txn_id,$date,$org,$fid,$bank_id,$start_date,$end_date);
  
  $bal = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->BALAMT;
  $dat = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->DTASOF;
  $data = strtotime(substr($dat,0,8));
  $datb = date('Y-m-d',$data);
    
    ?>
    
     <form id = 'ofx' name = 'form3' Method = 'POST' action= 'ofxtest.php'>
<p align = 'center'>From: <input type='text' id='datepicker' name ='from'>&nbsp &nbsp To : <input type='text' id='datepicker2' name='to'>
 <input type='submit' value='Submit'></p>
  </form>
 <table align ='center'><tr style = 'background-color:white;'><td colspan='2'align ='center'><h2><?php echo $org ?></h2></td>
             <td colspan='4' align = 'right'>Balance: <b>$<?php echo $bal?></b> <br />as of <b><?php echo $datb ?></b></td></tr>
             
               <?php
    
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
             </table>
    
    
    </div>
  <div id="tabs-3">
    <?php
   // user login info
$user = 'analsanchez79';
$pass = 'sanchez0731';

// account info
$accnt_num = '929215572';
$accnt_type = 'CHECKING';

// date and a random unique transaction id
$txn_id = getRandomString(6);
$tz_offset = date('Z')/3600;
$date = date("YmdHis[$tz_offset:T]");

// Bank of America info
$org = 'B1';
$fid = '10898';
$bank_id = '111000614';

// transaction retrieval date range
$start_date = '20131130000000';        // from date, to..
$end_date = date('YmdHis');        // now
$apiURL = 'https://ofx.chase.com';
    $xml = getInfo($apiURL,$user,$pass,$accnt_num,$accnt_type,$txn_id,$date,$org,$fid,$bank_id,$start_date,$end_date);
  
  $bal = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->BALAMT;
  $dat = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->DTASOF;
  $data = strtotime(substr($dat,0,8));
  $datb = date('Y-m-d',$data);
    
    ?>
    
     <form id = 'ofx' name = 'form3' Method = 'POST' action= 'ofxtest.php'>
<p align = 'center'>From: <input type='text' id='datepicker' name ='from'>&nbsp &nbsp To : <input type='text' id='datepicker2' name='to'>
 <input type='submit' value='Submit'></p>
  </form>
 <table align ='center'><tr style = 'background-color:white;'><td colspan='2'align ='center'><h2><?php echo $org ?></h2></td>
             <td colspan='4' align = 'right'>Balance: <b>$<?php echo $bal?></b> <br />as of <b><?php echo $datb ?></b></td></tr>
             
               <?php
    
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
             </table>
    </div>
</div>
</body>
</html>

<?php




?>