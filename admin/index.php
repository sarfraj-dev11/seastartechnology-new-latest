<?php
session_start();
if (!empty($_SESSION['mcc_admin'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
