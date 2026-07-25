<?php
include_once 'session.php';
include_once 'db.php';

if($_SESSION['role'] != 'administrator')
{
    header("Location: dashboard.php");
    exit();
}
?>