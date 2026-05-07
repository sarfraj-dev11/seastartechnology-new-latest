<?php
session_start();
unset($_SESSION['mcc_admin']);
header('Location: login.php');
exit;
