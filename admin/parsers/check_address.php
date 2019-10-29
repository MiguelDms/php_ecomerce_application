<?php 

require_once $_SERVER['DOCUMENT_ROOT']. '/Php_ecommerce_website/core/connection.php';

$name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$zip_code = sanitize($_POST['zip_code']);

$errors = array();
$required = array(
    'full_name' => 'Full Name',
    'email' => 'Email',
    'street' => 'Morada',
    'city' => 'Localidade',
    'zip_code' => 'Código Postal'
);

// check if all required fields are filled out 

foreach ($required as $field => $display) {
   if (empty($_POST[$field]) || $_POST[$field] == '') {
       $errors[] = $display. ' é de preenchimento obrigatório.';
   }
}

// check if email is valid

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Tem de preencher um email válido.';
}


if (!empty($errors)) {
    echo displayErrors($errors);
} else {
    echo 'passed';
}

?>