<?php 

require_once  $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/core/connection.php';

unset($_SESSION['SBUser']);

header('Location: login.php');
