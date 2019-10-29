<?php 

require_once  $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/core/connection.php';
if (!isLoggedIn()) {
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

//restaure all

if (isset($_GET['restaure_all'])) {
   
    $conn->query("UPDATE products SET deleted = 0");

    header('Location: archived_products.php');
}

//restaure individual

if (isset($_GET['restaure'])) {
    $restaure_id = (int)$_GET['restaure'];
   
    $conn->query("UPDATE products SET deleted = 0 WHERE id = '$restaure_id'");

    header('Location: archived_products.php');
}

//delete permanentely from db

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
   
    $conn->query("DELETE FROM products WHERE id = '$delete_id'");

    header('Location: archived_products.php');
}

$sql = "SELECT * FROM products WHERE deleted = 1";
$product_results = $conn->query($sql);
?>

<h2 class="text-center">Produtos Arquivados</h2>
<a href="archived_products.php?restaure_all=1" class="btn btn-success" id="add-product-btn">Restaurar todos</a>
<hr>

<table class="table table-bordered table-condensed table-striped">
    <thead><th></th><th>Produto</th><th>Preço</th><th>Categoria</th><th>Vendido</th></thead>
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
            <a href="archived_products.php?restaure=<?php echo $product['id']; ?>" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="left" title="Restaurar"><span class="glyphicon glyphicon-ok"></span></a>
            <a href="archived_products.php?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="left" title="Apagar da base de dados"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
         <td><?php echo $product['title'];?></td>
         <td><?php echo money($product['price']);?></td>
         <td><?php echo $productCategory;?></td>
         
        <td>0</td>
        </tr>
    <?php endwhile;?>
    </tbody>
</table>


<?php 

include 'includes/footer.php';
?>