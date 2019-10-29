<h3 class="text-center">Carrinho de compras</h3>

<div class="">

<?php 
if (empty($cart_id)):
?>
    <p>O seu carrinho de compras está vazio</p>

    <?php 
    else:

    $cartQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $results = mysqli_fetch_assoc($cartQ);

    $items = json_decode($results['items'], true);
    $sub_total = 0;
?>

<table class="table-condensed-table-striped table" id="cart-widget">
        <tbody>
            <?php foreach ($items as $item):
                $productQ = $conn->query("SELECT * FROM products WHERE id = '{$item['id']}'");
                $product = mysqli_fetch_assoc($productQ);
                ?>
                <tr>
                    <td><?php echo $item['quantity']?></td>
                    <td><?php echo substr($product['title'],0,15);?></td> <!-- este metodo vai fazer com que não hajam mais de 15 caracteres na mesa. -->
                    <td><?php echo money($item['quantity'] * $product['price']); ?></td>
                </tr>

    <?php
            $sub_total += ($item['quantity'] * $product['price']);
endforeach;?>
        <tr>
         <td></td>
         <td>Sub-Total</td>
         <td><?php echo money($sub_total);?></td>
        </tr>
        </tbody>
</table>
<a href="cart.php" class="btn btn-sm btn-primary" style="float: right;">Ir para carrinho</a>
        <div class="clearfix"></div>

<?php 
endif;
?>
</div>