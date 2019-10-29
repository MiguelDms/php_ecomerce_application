<?php 

require_once $_SERVER['DOCUMENT_ROOT']. '/Php_ecommerce_website/core/connection.php';


$product_id = sanitize($_POST['product_id']); //estes estao a vir do clicar o comprar no details_modal
$size = sanitize($_POST['size']);
$available = sanitize($_POST['available']);
$quantity = sanitize($_POST['quantity']);
$item = array();
$item[] = array(
    'id' => $product_id,
    'size' => $size,
    'quantity' => $quantity,
);

if (!isLoggedIn() && $product_id == null) {
    login_error_redirect($url = '../login.php');
    return;
} else {

$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false; //Se o http_host não for localhost, entao vamos defini-lo como http_host, senão, se for localhost, o domain vai ser false. I DONT KNOE LOL
$query = $conn->query("SELECT * FROM products WHERE id = '{$product_id}'");
$product = mysqli_fetch_assoc($query);
var_dump($cart_id);


// check to see if cart_cokkie exists

if ($cart_id != '') {
   $cartQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'"); //cart id é uma array, hence os brackets
   $cart = mysqli_fetch_assoc($cartQ);
   $previous_items = json_decode($cart['items'],true);
   $item_match = 0;
   $new_items = array();
   foreach($previous_items as $pItem) {
       if ($item[0]['id'] == $pItem['id'] && $item[0]['size'] == $pItem['size']) {
           $pItem['quantity'] = $pItem['quantity'] + $item[0]['quantity'];
           if ($pItem['quantity'] > $available) {
            $pItem['quantity'] = $available;
           }
           $item_match = 1;
       }
       $new_items[] = $pItem;
   }
   if ($item_match != 1) {
       $new_items = array_merge($item,$previous_items);
   }
   $items_json = json_encode($new_items);
   $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
   $conn->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");
   
   setcookie(CART_COOKIE,'',1,'/',$domain,false);
   setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
   
} else {
    
    //add cart to db and set cookie
    $items_json = json_encode($item);
    $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
    $conn->query("INSERT INTO cart (items,expire_date) VALUES ('{$items_json}','{$cart_expire}')");
    $cart_id = $conn->insert_id; //atribui á variavel cart_id o ultimo id passado para dentro da bd, que foi com o insert acima. 

    setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
}
}
?>