<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/core/connection.php';
$mode = sanitize($_POST['mode']);
$edit_size = sanitize($_POST['edit_size']);
$edit_id = sanitize($_POST['edit_id']);
$cartQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
$result = mysqli_fetch_assoc($cartQ);
$items = json_decode($result['items'], true);
$updated_items = array();
$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
if ($mode == 'removeOne') {
    foreach ($items as $item) {
       if ($item['id'] == $edit_id && $item['size'] == $edit_size) {
        $item['quantity'] = $item['quantity'] - 1;
       }
       if ($item['quantity'] > 0) {
          $updated_items[] = $item; //ou seja, so irá passar o item para dentro da array (para se fazer o json_enconde) se for superior a 0, de maneira a que não se possam passar valores negativos.
       }
    }
   
}

if ($mode == 'addOne') {
    foreach ($items as $item) {
       if ($item['id'] == $edit_id && $item['size'] == $edit_size) {
        $item['quantity'] = $item['quantity'] + 1;
       }
       
          $updated_items[] = $item; 
       
    }
}

if (!empty($updated_items)) {
   $json_updated = json_encode($updated_items);
   $conn->query("UPDATE cart SET items = '{$json_updated}' WHERE id = '{$cart_id}'");
   $_SESSION['success_flash'] = 'O seu carrinho de compras foi actualizado!';
}


if (empty($updated_items)) {
  $conn->query("DELETE FROM cart WHERE id = '{$cart_id}'"); 
   setcookie(CART_COOKIE,'',1,'/',$domain,false); 
  }
  var_dump($updated_items);

?>