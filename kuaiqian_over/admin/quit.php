<?php
session_start();
if(isset($_SESSION ['pw'])){
    session_destroy();
}
header('location:index.php');
?>
