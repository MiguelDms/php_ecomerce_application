<?php 

require_once 'core/connection.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/header_full.php';

if ($cart_id != '') {
    $cartQ = $conn->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'], true); //o true torna isto numa associative array em vez de num objecto
    $i = 1;
    $sub_total = 0;
    $item_count = 0;
}

?>
</div>

        <h2 class="text-center">O meu carrinho</h2><hr>
 
        <?php if($cart_id == ''): ?>
        <div class="bg-danger">
            <p class="text-center">O seu carrinho está vazio.</p>
        </div>
        <?php else: ?>
        <table class="table table-bordered table-condensed table-striped">
            <thead><th>#</th><th>Item</th><th>Preço</th><th>Quantidade</th><th>Tamanho</th><th>Sub-total</th></thead>
            <tbody>
            <?php 
             foreach ($items as $item) {
                 $product_id = $item['id'];
                 $product_Q = $conn->query("SELECT * FROM products WHERE id = '{$product_id}'");
                 $product = mysqli_fetch_assoc($product_Q);
                 $sArray = explode(',',$product['sizes']); // este vai separar os tamanhos
                 foreach ($sArray as $sizeString) {
                     $s = explode(':',$sizeString); //este vai separar a quantidade do tamanho
                    if ($s[0] == $item['size']) {
                       $available = $s[1];
                    }
                 }
                 ?>
                <tr>
                 <td><?php echo $i; ?></td>
                 <td><?php echo $product['title']; ?></td>
                 <td><?php echo money($product['price']); ?></td>
                 <td>
                    <button class="btn btn-sm btn-light" onclick="updateCart('removeOne','<?php echo $product['id'];?>','<?php echo $item['size'];?>');">-</button>
                 <?php echo $item['quantity']; ?>
                    <?php if ($item['quantity'] < $available): ?>   <!-- o botao + so vai estar visivel se ainda houver quantidade suficiente -->
                    <button class="btn btn-sm btn-light" onclick="updateCart('addOne','<?php echo $product['id'];?>','<?php echo $item['size'];?>');">+</button>
                    <?php else : ?>
                        <span class="text-info">Item esgotado.</span>
                    <?php endif ; ?>
                 </td>
                 <td><?php echo $item['size']; ?></td>
                 <td><?php echo money($item['quantity'] * $product['price']); ?></td>
                </tr>

                 <?php $i ++;
                $item_count += $item['quantity'];
                $sub_total += ($product['price'] * $item['quantity']);                 
                }
                $tax = TAXRATE * $sub_total;
                $tax = number_format($tax,2);
                $grand_total = $tax + $sub_total;
                ?>
            </tbody>
        </table>
        <legend>Total</legend>
        <table class="table table-bordered table-condensed float-right">
                <thead class="totals-head-table"><th>Items</th><th>Sub-total</th><th>IVA</th><th>Total</th></thead>
                <tbody><tr>
                <td><?php echo $item_count; ?></td>
                <td><?php echo money($sub_total); ?></td>
                <td><?php echo money($tax); ?></td>
                <td class="bg-success"><?php echo money($grand_total); ?></td>
                </tr></tbody>
        </table>

        <!--Checkout button-->
<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#checkOutModal">
  <span class="glyphicon glyphicon-shopping-cart"></span> Checkout >>
</button>

<!-- Modal -->
<div class="modal fade" id="checkOutModal" tabindex="-1" role="dialog" aria-labelledby="checkOutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkOutModalLabel">Endereço de envio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form action="thank_you.php" method="POST" id="payment_form">
       <span class="bg-danger" id="payment_errors"></span>
        <div id="stepOne" style="display: block;">
                <div class="form-group col-md-6">
                  <label for="full_name">Nome completo:</label>
                  <input type="text" class="form-control" id="full_name" name="full_name">
                </div>
                <div class="form-group col-md-6">
                  <label for="email">email:</label>
                  <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="form-group col-md-6">
                  <label for="street">Morada:</label>
                  <input type="text" class="form-control" id="street" name="street">
                </div>
                <div class="form-group col-md-6">
                  <label for="street2">Morada 2:</label>
                  <input type="text" class="form-control" id="street2" name="street2">
                </div>
                <div class="form-group col-md-6">
                  <label for="city">Localidade:</label>
                  <input type="text" class="form-control" id="city" name="city">
                </div>
                <div class="form-group col-md-6">
                  <label for="zip_code">Código Postal:</label>
                  <input type="text" class="form-control" id="zip_code" name="zip_code">
                </div>
        </div>
        <div id="stepTwo" style="display: none;">
                <div class="form-group col-md-3">
                  <label for="name">Nome no cartão:</label>
                  <input type="text" id="name" class="form-control">
                </div>
                <div class="form-group col-md-3">
                  <label for="number">Numero no cartão:</label>
                  <input type="text" id="number" class="form-control">
                </div>
                <div class="form-group col-md-2">
                  <label for="cvc">CVC:</label>
                  <input type="text" id="cvc" class="form-control">
                </div>
                <div class="form-group col-md-2">
                  <label for="expired">Mês de expiração:</label>
                  <select id="expired" class="form-control">
                    <option value=""></option>
                    <?php for($i=1;$i < 13;$i++): ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                   <?php endfor; ?>                  
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label for="expired-year">Ano de expiração:</label>
                  <select id="expired-year" class="form-control">
                    <option value=""></option>
                    <?php $yr = date("Y");?>
                    <?php for($i=0;$i < 11;$i++): ?>
                      <option value="<?php echo $yr + $i; ?>"><?php echo $yr + $i; ?></option>
                   <?php endfor; ?>         
                  </select>
                </div>
        </div>
       
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display: none;">Antes <<</button>
        <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Próximo >></button>
        <button type="submit" class="btn btn-primary" id="checkout_button" style="display: none;">Checkout >></button>
        </form>
      </div>
    </div>
  </div>
</div>
        <?php endif;?>
</div>  
    <div class="col-md-2" style="clear: both;">Right side bar</div>
    <div class="col-md-12 text-center">&copy; Copyright 2017-2019 Loja do Miguel</div>


    <script src="js/main.js"></script>
    <script>

    function back_address() {
      $('#payment_errors').html('');
      $('#stepOne').css("display", "block");
        $('#stepTwo').css("display", "none");
        $('#next_button').css("display", "inline-block");
        $('#back_button').css("display", "none");
        $('#checkout_button').css("display", "none");
        $('#checkOutModalLabel').html('Endereço de Envio');

    }
    
    function check_address() {
      let data = {'full_name' : $('#full_name').val(), 
      'email' : $('#email').val(),
      'street' : $('#street').val(),
      'street2' : $('#street2').val(),
      'city' : $('#city').val(),
      'zip_code' : $('#zip_code').val()
      }

      $.ajax({
    url: "/Php_ecommerce_website/admin/parsers/check_address.php",
    method: "POST",
    data: data,
    success: function (data) {
      if (data != 'passed') {
        $('#payment_errors').html(data);
        
      }

      if (data == 'passed') {
        $('#payment_errors').html('');
        $('#stepOne').css("display", "none");
        $('#stepTwo').css("display", "block");
        $('#next_button').css("display", "none");
        $('#back_button').css("display", "inline-block");
        $('#checkout_button').css("display", "inline-block");
        $('#checkOutModalLabel').html('Detalhes de pagamento');
      }
    }, //esta data não é a mesma que se está a enviar para o parser, é a que está a VIR do parser.
    error: function () {
      alert("No dice");
    }
  });
}



// Create a Stripe client.
var stripe = Stripe(<?php echo STRIPE_PUBLIC;?>);

// Create an instance of Elements.
var elements = stripe.elements();


// Handle form submission.
var form = document.getElementById('payment_form');
form.addEventListener('submit', function(event) {
  event.preventDefault();
  showLoading();

  var sourceData = {
    type: 'sepa_debit',
    currency: 'eur',
    owner: {
      name: document.querySelector('input[name="name"]').value,
      email: document.querySelector('input[name="email"]').value,
    },
    mandate: {
      // Automatically send a mandate notification email to your customer
      // once the source is charged.
      notification_method: 'email',
    }
  };

  console.log(souceData.owner);
  
});

    </script>
  
</body>
</html>