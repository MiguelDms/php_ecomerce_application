
<?php 
require_once '../core/connection.php';
$id = $_POST['id'];
$id = (int)$id; //vai-se certificar que é um int a ser passado; isto é mais algo por segurança
$sql = "SELECT * FROM products WHERE id = '$id'"; 
$results = $conn->query($sql);
$product = mysqli_fetch_assoc($results);
$brand_id = $product['brand'];
$sql2 = "SELECT brand FROM brand WHERE id = '$brand_id'";
$brand_query = $conn->query($sql2);
$brand = mysqli_fetch_assoc($brand_query);
$sizestring = $product['sizes'];
$sizeArray = explode(',', $sizestring);


//nao se faz while loop porque so esperamos um produto retornado




?>

<!-- details modal -->
<?php ob_start(); ?>
    <div class="modal fade details-1" id="details-1" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="">
        
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center"><?php echo $product['title'];?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                    </button>
                 </div>

                <div class="modal-body">
                <span id="modal-errors" class="bg-danger"></span>
                    <div class="container-fluid">
                    
                        <div class="row">
                       
                            <div class="col-sm-6">
                            <?php $photos = explode(',', $product['image']); 
                                foreach($photos as $photo):     ?>
                                <div class="center-block">
                                    <img src="<?php echo $photo;?>" alt="<?php echo $product['title'];?>" width="290px" height="560px" class="details img-fluid">
                                    <?php
                                    endforeach;   ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h4>Detalhes</h4>
                                <p><?php echo $product['description'];?></p>
                                <hr>
                                <p>Preço: <?php echo $product['price'];?></p>
                                <p>Marca: <?php echo $brand['brand'];?></p>
                                <form action="add_cart.php" method="post" id="add_product_form">
                                <input type="hidden" name="product_id" value="<?php echo $id;?>"> 
                                <input type="hidden" name="available" id="available" value="<?php echo $available;?>">
                                    <div class="form-group">
                                        <div class="col-xs-3">
                                            <label for="quantity">Quantidade:</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" min="0">
                                        </div>
                                       
                                    </div>
                                    <div class="form-group">
                                        <label for="size">Tamanho:</label>
                                        <select name="size" id="size" class="form-control">
                                            <?php foreach($sizeArray as $string) {
                                                $string_array = explode(':', $string);
                                                $size = $string_array['0'];
                                                $available = $string_array['1'];
                                                echo ' <option value="'.$size.'" data-available="'.$available.'">'.$size.' ('.$available.' disponíveis)</option>';
                                            } ?>
                                           
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" onclick="add_to_cart(); return false;"><span><i class="fas fa-cart-plus"></i></span> Comprar</button>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        let available = $('#size option:selected').data("available");
        $('#available').val(available);

        $('#size').change(function() {
            let available = $('#size option:selected').data("available");
            $('#available').val(available);
           
        })
    </script>
<?php echo ob_get_clean(); ?>