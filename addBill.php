<?php
require_once('session_init.php');
 
 
 if(!isset($_SESSION['role'])) {
     header("Location: logout.php");
     exit();
 }
 if ($_SESSION['role'] == 0){
 header("Location: logout.php");
 exit();
}
// echo $_SESSION['db_num'];

// index.php
//$errorString

?>
<?ini_set( 'error_reporting', E_ALL ^ E_NOTICE );?>
<?ini_set( 'display_errors', '0' );?>
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
    <title>Adding a new bill</title>
	
</head>

<body>

<script>
function toggleRecurringAmount() {
    var checkbox = document.querySelector('input[name="auto_pay"]');
    var recurringField = document.getElementById('recurring_amount');
    
    if (checkbox && recurringField) {
        if (checkbox.checked) {
            recurringField.style.display = 'block';
            recurringField.required = true;
        } else {
            recurringField.style.display = 'none';
            recurringField.required = false;
            recurringField.value = '';
        }
    }
}

// Make sure the function is available when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the field as hidden
    var recurringField = document.getElementById('recurring_amount');
    if (recurringField) {
        recurringField.style.display = 'none';
    }
});
</script>
 <?= 
 @$errorString
 ?>

 <a href = 'bills.php'>Back to Bills</a>
 <br />
 <br />
 <b>Add a new bill</b>
<form action="validateNewBill.php" method="POST">
<table border="0">
<tr><td>Store*:</td><td><input type="text" name="store" value="<?php $store?>" /></td></tr>
<tr><td>Due Date*:</td><td><input type="text" name="due" value="<?php $due?>" /></td></tr>
<tr><td>Website:</td><td><input type="text" name="website" value="<?php $website?>" /></td></tr>
<tr><td>Username:</td><td><input type="text" name="username" value="<?php $username?>" /></td></tr>
<tr><td>Password:</td><td><input type="text" name="password" value="<?php $password?>" /></td></tr>
<tr><td>Auto-Pay:</td><td><input type="checkbox" name="auto_pay" value="1" onchange="toggleRecurringAmount()" /> Check if this bill is paid automatically</td></tr>
<tr><td>Recurring Amount:</td><td><input type="text" name="recurring_amount" id="recurring_amount" placeholder="Enter monthly amount" style="display:none;" /></td></tr>



<tr><td></td><td><input type="submit" name="submit" value="Submit Form" /></td></tr>
</form>
     
</body>
</html>
