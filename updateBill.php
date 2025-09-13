<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>
<?php
require_once('session_init.php');

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

$con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
$db_num = $_SESSION['db_num'];


$sql="SELECT * FROM `{$_SESSION['db_num']}`"; 

$result=mysqli_query($con, $sql); 
$num=mysqli_num_rows($result);

$options=""; 
$selectedStore = isset($_GET['store']) ? $_GET['store'] : '';

// Initialize form variables
$website = '';
$username = '';
$password = '';
$due = '';
$auto_pay = 0;
$recurring_amount = '0.00';

// If a store is selected, get its data
if ($selectedStore) {
    $storeQuery = "SELECT * FROM `{$_SESSION['db_num']}` WHERE store = '" . mysqli_real_escape_string($con, $selectedStore) . "'";
    $storeResult = mysqli_query($con, $storeQuery);
    
    if ($storeResult && $storeRow = mysqli_fetch_array($storeResult)) {
        $website = $storeRow['website'];
        $username = $storeRow['username'];
        $password = $storeRow['password'];
        $due = $storeRow['due_on'];
        $auto_pay = isset($storeRow['auto_pay']) ? $storeRow['auto_pay'] : 0;
        $recurring_amount = isset($storeRow['recurring_amount']) ? $storeRow['recurring_amount'] : '0.00';
    }
}

while ($row=mysqli_fetch_array($result)) { 

    $id=$row["store"]; 
    $selected = ($id == $selectedStore) ? 'selected' : '';
   
   $options.="<OPTION VALUE=\"$id\" $selected>".$id.'</option>';
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

body{ background-image: url(images/Gray-background.gif);
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

<script>
function toggleRecurringAmount() {
    var checkbox = document.querySelector('input[name="auto_pay"]');
    var recurringField = document.getElementById('recurring_amount');
    
    if (checkbox.checked) {
        recurringField.style.display = 'block';
        recurringField.required = true;
    } else {
        recurringField.style.display = 'none';
        recurringField.required = false;
        recurringField.value = '';
    }
}
</script>
 

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
<tr><td>Auto-Pay:</td><td><input type="checkbox" name="auto_pay" value="1" onchange="toggleRecurringAmount()" <?php echo (isset($auto_pay) && $auto_pay == 1) ? 'checked' : ''; ?> /> Check if this bill is paid automatically</td></tr>
<tr><td>Recurring Amount:</td><td><input type="text" name="recurring_amount" id="recurring_amount" value="<?php echo isset($recurring_amount) ? $recurring_amount : ''; ?>" placeholder="Enter monthly amount" style="<?php echo (isset($auto_pay) && $auto_pay == 1) ? 'display:block;' : 'display:none;'; ?>" /></td></tr>


<tr><td></td><td><input type="submit" name="submit" value="Update Bill" /></td></tr></table>
</form>
<b>*submitting form will update all fields even if left blank</b>
     
</body>
</html>