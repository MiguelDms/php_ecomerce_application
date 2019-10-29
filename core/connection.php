<?php

$dbServerName = 'postgres://rnsofsnmugjarl:d16c8f6f4aacf79ef1120973745a64ac244c70f9cca4cfb58c521ad8b1bb89f3@ec2-184-73-209-230.compute-1.amazonaws.com:5432/dalilf7d8kev88';
$dbUserName = 'root';
$dbPassword = '';
$dbName = 'ecommerce_miguel\'s_store';

$conn = mysqli_connect($dbServerName, $dbUserName, $dbPassword, $dbName);

if (mysqli_connect_errno()) {
    echo 'Database connection failed with following error: '. mysqli_connect_error();
    die(); 
}
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/config.php';
require_once BASEURL. 'helpers/helpers.php';
require BASEURL. '/vendor/autoload.php';

$cart_id = '';
if (isset($_COOKIE[CART_COOKIE])) {
    $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}

if (isset($_SESSION['SBUser'])) {
    $user_id = $_SESSION['SBUser'];
    $query = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($query);
    $fn = explode(' ', $user_data['full_name']);
    $user_data['first'] = $fn[0];
    $user_data['last'] = $fn[1];
}

if (isset($_SESSION['success_flash'])) {
echo '<div class="bg-success"><p class="text-default text-center">'.$_SESSION['success_flash'].'</p></div>';
unset($_SESSION['success_flash']); //isto faz com que o echo só apareça uma vez no caso de o user mudar para outra pagina
}

if (isset($_SESSION['error_flash'])) {
    echo '<div class="bg-danger"><p class="text-default text-center">'.$_SESSION['error_flash'].'</p></div>';
    unset($_SESSION['error_flash']); //isto faz com que o echo só apareça uma vez no caso de o user mudar para outra pagina
    }
