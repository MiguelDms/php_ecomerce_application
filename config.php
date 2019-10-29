<?php 

define('BASEURL', $_SERVER['DOCUMENT_ROOT']. '/Php_ecommerce_website/'); //isto dá-nos o caminho para o root do folder onde este documento está inserido, portanto, o php_ecommerce_website path
define('CART_COOKIE','sBwueHSjxUInw22');
define('CART_COOKIE_EXPIRE',time() + (86400 * 30)); //Isto equivale a 30 dias.
define('TAXRATE', 0.23);
define('CURRENCY', 'eur');
define('CHECKOUT_MODE', 'TEST');

if (CHECKOUT_MODE == 'TEST') {
    define('STRIPE_PRIVATE', 'sk_test_HzvNmUXnC0JmSLpMOqj2UEjW00cGJJ1ALf');
    define('STRIPE_PUBLIC', 'pk_test_AtelU3GsCoF3USmXZNwmsZ4700xMfbdXrx');
}

if (CHECKOUT_MODE == 'PUBLIC') {
    define('STRIPE_PRIVATE', '');
    define('STRIPE_PUBLIC', '');
}