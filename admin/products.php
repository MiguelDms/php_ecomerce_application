<?php 

require_once  $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/core/connection.php';
if (!isLoggedIn()) {
    login_error_redirect($url = 'login.php');
}
include 'includes/head.php';
include 'includes/navigation.php';
//delete product

if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    $conn->query("UPDATE products SET deleted = 1 WHERE id = '$id'");

    header('Location: products.php');
}


//add product part
if (isset($_GET['add']) || isset($_GET['edit'])) {
    $brandQuery = $conn->query("SELECT * FROM brand ORDER BY brand");
    $parentQuery = $conn->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category"); //select parent categories
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):''); 
    $childCategory = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):''); 
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
    $list_price = ((isset($_POST['list-price']) && $_POST['list-price'] != '')?sanitize($_POST['list-price']):'');
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
    $saved_image = '';

    ?>
    <script>
$(document).ready(function() {
get_child_options('<?php echo $childCategory;?>')
})
</script>
<?php

    if (isset($_GET['edit'])) {
        $editId = (int)$_GET['edit'];
        $productResults = $conn->query("SELECT * FROM products WHERE id = '$editId'");
        $product = mysqli_fetch_assoc($productResults);
        if (isset($_GET['delete_image'])) {
            $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
            unlink($image_url);
            $conn->query("UPDATE products SET image = '' WHERE id = '$editId'");
            header('Location: products.php?edit='.$editId);
        }
        $category = ((isset($_POST['child']) &&  $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);
        $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
        $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand']);
        $parentQuerys = $conn->query("SELECT * FROM categories WHERE id = '$category'");
        $parentResult = mysqli_fetch_assoc($parentQuerys);
        $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResult['parent']);
        $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
        $list_price = ((isset($_POST['list-price']))?sanitize($_POST['list-price']):$product['list_price']);
        $description = ((isset($_POST['description']))?sanitize($_POST['description']):$product['description']);
        $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product['sizes']);
        $saved_image = (($product['image'] != '')?$product['image']:'');
        $dbPath = $saved_image;

        ?>
        <script>
    $(document).ready(function() {
    get_child_options('<?php echo $category;?>')
    })
    </script>
    <?php
       
    }

    if (!empty($sizes)) {
       $sizeString = sanitize($sizes);
       $sizesArray = explode(',',$sizeString); //isto deve automaticamente colocar uma string numa array, aqui irá separar por ','
       $sArray = array(); 
       $qArray = array(); 
       foreach ($sizesArray as $ss) {
          $s = explode(':', $ss); //vai separar os elementos em dois indexes, um o size, o outro a quantidade, que irá colocar nas duas arrays abaixo
          $sArray[] = $s[0];
          $qArray[] = $s[1];
       }
    } else {
        $sizesArray = array();
    }


if ($_POST) {

    //error check, it prevents modal values from resetting on submit error Video 16
    $errors = array();
        $required = array('title', 'brand', 'parent', 'child', 'price', 'sizes'); //video 17 form validation

        /* multiple photos */

       
        $tmpLoc = array();// para as multiplas fotos
        $allowed = array('png', 'jpeg', 'gif', 'jpg'); // para as multiplas fotos
        $uploadLoc = array(); // para as multiplas fotos


        foreach($required as $field) { //see if fields are empty
            if ($_POST[$field] == '') {
                $errors[] = 'Todos os campos com asterisquo são de preenchimento obrigatório.';
               
                break;
        }
    }
  
   
        /* single photo */

 /* if (is_uploaded_file($_FILES['photo']['tmp_name'])) { //check for image correctness
        var_dump($_FILES);
        $photo = $_FILES['photo'];
        $name = $photo['name'];
        $photoName = explode('.', $name);
        $fileName = $photoName[0];
        $fileExt = $photoName[1];
        $mime = explode('/', $photo['type']);
        $mimeType = $mime[0];
        $mimeExt = $mime[1];
        $tmpLoc = $photo['tmp_name'];
        $fileSize = $photo['size'];
        $allowed = array('png', 'jpeg', 'gif', 'jpg');
        $uploadName = md5(microtime()).'.'.$fileExt;
        $uploadLoc = BASEURL.'images/'.$uploadName; 
        $dbPath = '/php_ecommerce_website/images/'.$uploadName;
        if ($mimeType != 'image') {
            $errors[] = 'O ficheiro inserido tem de ser uma imagem';
           
        }
        if (!in_array($fileExt, $allowed)) {
            $errors[] = 'O ficheiro tem de ser do formato "png", "jpj",  "jpeg", "gif"';
        }
 
        if ($fileSize > 1000000) {
         $errors[] = 'O ficheiro tem de ter menos de ser menos de 1MB';
        }
 
        if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpeg')) { //não esta a funcionar como deve ser
         $errors[] = 'O ficheiro não coincide"';
        }
     } 
     
     if (!empty($errors)) {
       echo displayErrors($errors);
   
    } else {
    if (!empty($_FILES)) {
        move_uploaded_file($tmpLoc, $uploadLoc); //will insert in project folder
    }  */
    
    
/* multiple photos */


 $photo_count = count($_FILES['photo']['tmp_name']); 
 $name = $_FILES['photo']['name']; // isto aqui é para eu conseguir fazer o upload de um produto sem ter de carregar uma imagem, porque o photo_count vai-me sempre passar um valor. 
     if ($photo_count > 0 && $name[0] != '') { //check for image correctness
        for ($i=0; $i < $photo_count; $i++) {
            var_dump($name[0]); 
       $name = $_FILES['photo']['name'][$i];
       $photoName = explode('.', $name);
       $fileName = $photoName[0];
       $fileExt = $photoName[1];
       $mime = explode('/', $_FILES['photo']['type'][$i]);
       $mimeType = $mime[0];
       $mimeExt = $mime[1];
       $tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
       $fileSize = $_FILES['photo']['size'][$i];
       $uploadName = md5(microtime().$i).'.'.$fileExt;
       $uploadLoc[] = BASEURL.'images/'.$uploadName;
       
       if ($i != 0) {
        $dbPath .= ','; // vai colocar uma , para conseguir colocar mais que o path nas fotos com um index != de 0 
       }
       $dbPath .= '/php_ecommerce_website/images/'.$uploadName;
       if ($mimeType != 'image') {
           $errors[] = 'O ficheiro inserido tem de ser uma imagem';
          
       }
       if (!in_array($fileExt, $allowed)) {
           $errors[] = 'O ficheiro tem de ser do formato "png", "jpj",  "jpeg", "gif"';
       }

       if ($fileSize > 1000000) {
        $errors[] = 'O ficheiro tem de ter menos de ser menos de 1MB';
       }

       if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg' )) { //não esta a funcionar como deve ser
        $errors[] = 'O ficheiro não coincide"';
       } 
        }
     
       
    } 
 
    if (!empty($errors)) {
       echo displayErrors($errors);
   
    } else {
    if ($photo_count > 0 && $name != '') {
        for ($i=0; $i < $photo_count ; $i++) { 
            move_uploaded_file($tmpLoc[$i], $uploadLoc[$i]); //will insert in project folder
        }
    } 
     
       // WIL QUERY VALUES INTO DB
       $insertSql = "INSERT INTO products (`title`, `price`, `list_price`, `brand`, `categories`, `sizes`, `image`, `description`) VALUES ('$title', '$price','$list_price','$brand','$childCategory','$sizes','$dbPath', '$description')";

        if (isset($_GET['edit'])) {
           
            $insertSql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand', categories = '$childCategory', sizes = '$sizes', image = '$dbPath', description = '$description' WHERE id = '$editId'";

        }

       $conn->query($insertSql);
      
        header('Location: products.php');

        
    }
}


?>

<h2 class="text-center"><?php echo ((isset($_GET['edit'])?'Edite um ':'Adicione um '));?>produto</h2><hr>
<form action="products.php?<?php echo ((isset($_GET['edit'])?'edit='.$editId:'add=1'));?>" method="POST" enctype="multipart/form-data">
    <div class="form-group col-md-3">
        <label for="title">Titulo:<span class="asterisk">*</span></label>
        <input type="text" name="title" class="form-control" id="title" value="<?php echo $title;?>">
    </div>
    <div class="form-gourp col-md-3">
        <label for="brand">Marca:<span class="asterisk">*</span></label>
        <select name="brand" id="brand" class="form-control">
            <option value=""<?php echo (($brand == '')?' selected':'') ;?>></option>
            <?php while($bran = mysqli_fetch_assoc($brandQuery)): ?>
            <option value="<?php echo $bran['id'];?>"<?php echo (($brand == $bran['id'])?' selected':'') ;?>><?php echo $bran['brand'];?></option>    
            <?php endwhile; ?>
        </select>
        
    </div>
    <div class="form-group col-md-3">
        <label for="parent">Categoria Parente:<span class="asterisk">*</span></label>
        <select name="parent" id="parent" class="form-control">
            <option value=""<?php echo (($parent == '')?' selected':'') ;?>></option>
            <?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
            <option value="<?php echo $p['id'];?>"<?php echo (($parent == $p['id'])?' selected':'') ;?>><?php echo $p['category'];?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="child">Categoria Child:<span class="asterisk">*</span></label>
        <select name="child" id="child" class="form-control" >
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="price">Preço:<span class="asterisk">*</span></label>
        <input type="text" id="price" name="price" class="form-control" value="<?php echo $price;?>">
    </div>
    <div class="form-group col-md-3">
        <label for="list-price">Preço de lista:</label>
        <input type="text" id="list-price" name="list-price" class="form-control" value=" <?php echo $list_price;?>">
    </div>
    <div class="form-group col-md-3">
    <label>Quantidades & Tamanhos</label>
        <button class="btn btn-info form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantidades & Tamanhos</button>
    </div>
    <div class="form-group col-md-3">
        <label for="sizes">Tamanhos & Quantidade Preview</label>
        <input type="text" name="sizes" class="form-control" id="sizes" value="<?php echo $sizes;?>" readonly>
    </div>
    <div class="form-group col-md-6">
    <?php 
    if ($saved_image != ''): ?> 
    <div class="saved-image"><img src="<?php echo $saved_image;?>" alt="saved image"></div><br>
    <a href="products.php?delete_image=1&edit=<?php echo $editId;?>" class="btn btn-sm btn-danger">Apagar imagem</a>

    <?php else:?> <!-- isto é para não aparecer o input caso se esteja a ver a imagem -->
        <label for="photo">Fotografia do produto</label>
        <input type="file" class="form-control" name="photo[]" id="photo" multiple>
     <?php endif;?>
    </div>
   
    <div class="form-group col-md-6">
        <label for="description">Descrição</label>
        <textarea name="description" id="description" class="form-control" rows="6"><?php echo $description; ?></textarea>
    </div>
    <div class="form-group col-md-3">
        <a href="products.php" class="btn btn-light">Cancelar</a>
        <input type="submit" value="<?php echo ((isset($_GET['edit'])?'Editar ':'Adicionar'));?> Produto" class=" btn-success">
    </div>
</form>

<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sizesModalLabel">Tamanho & Quantidade</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> 
      </div>
      <div class="modal-body">
        <?php for($i = 1; $i <= 12; $i++): ?> 
            <div class="form-group col-md-4">
                <label for="size<?php echo $i;?>">Size:</label>
                <input type="text" name="size<?php echo $i;?>" id="size<?php echo $i;?>" value="<?php echo ((!empty($sArray[$i-1]))?$sArray[$i-1]:'') ?>" class="form-control">
            </div>
            <div class="form-group col-md-2">
                <label for="quantity<?php echo $i;?>">Quantidade:</label>
                <input type="number" name="quantity<?php echo $i;?>" id="quantity<?php echo $i;?>" value="<?php echo ((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>" min="0" class="form-control">
            </div>
        <?php endfor; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle'); return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>


    <?php     
} else {
//if not on add product, getting products
$sql = "SELECT * FROM products WHERE deleted = 0";
$product_results = $conn->query($sql);
//featured change
if (isset($_GET['featured'])) {
   $id = $_GET['id'];
   $featured =  $_GET['featured'];
    $sql_featured = "UPDATE products SET Featured = '$featured' WHERE id = '$id'";
    $conn->query($sql_featured);
    
    header('Location: products.php');
}

?>

<h2 class="text-center">Produtos</h2>
<a href="products.php?add=1" class="btn btn-success" id="add-product-btn">Adicionar produto</a>
<hr>

<table class="table table-bordered table-condensed table-striped">
    <thead><th></th><th>Produto</th><th>Preço</th><th>Categoria</th><th>Featured</th><th>Vendido</th></thead>
    <tbody>
    <?php while($product = mysqli_fetch_assoc($product_results)):

    // categories query
        $child_id = $product['categories']; 
        $categories_sql = "SELECT * FROM categories WHERE id = '$child_id'";
        $result = $conn->query($categories_sql);
        $child = mysqli_fetch_assoc($result);
        $parent_id = $child['parent'];
        $parentSql = "SELECT * FROM categories WHERE id = '$parent_id'";
        $p_result = $conn->query($parentSql);
        $parent = mysqli_fetch_assoc($p_result);
        $productCategory = $parent['category'].'~'.$child['category'];
        
        ?>
        
        <tr>
        <td>
            <a href="products.php?edit=<?php echo $product['id']; ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="products.php?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
         <td><?php echo $product['title'];?></td>
         <td><?php echo money($product['price']);?></td>
         <td><?php echo $productCategory;?></td>
         <td><a href="products.php?featured=<?php echo (($product['Featured'] == 0)?'1':'0')?>&id=<?php echo $product    ['id'];?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-<?php echo (($product['Featured']    == 1)?'minus':'plus'); ?>"></span>
         </a>&nbsp<?php echo (($product['Featured'] == 1)?'Produto Featured':''); ?></td>
        <td>0</td>
        </tr>
    <?php endwhile;?>
    </tbody>
</table>

    <?php }



include 'includes/footer.php';
?>


