<?php

session_start();
session_destroy();
if(isset($_SESSION['role']))
  unset($_SESSION['role']);
  header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private");
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header("Location: index.php");
?>