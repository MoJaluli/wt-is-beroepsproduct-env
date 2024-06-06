<?php
require_once 'db_connectie.php';
session_start();
if(isset($_SESSION['gebruiker'])){
session_unset();
header("Location: index.php");
}
?>