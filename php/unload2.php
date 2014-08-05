<?php
 session_start();
 unset($_SESSION['administrateur']);  
 header('Location: ../index.php'); 
?>
