<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/core/connection.php';

$parentId = (int)$_POST['parentId'];
$selected = sanitize($_POST['selected']);
$childSql = $conn->query("SELECT * FROM categories WHERE parent = '$parentId' ORDER BY category");
ob_start();
?>
<option value=""></option>
<?php while($child = mysqli_fetch_assoc($childSql)): ?>
            <option value="<?php echo $child['id'];?>"<?php echo (($selected == $child['id'])?' selected':'');?>><?php echo $child['category'];?></option>
            <?php endwhile; ?>
<?php echo ob_get_clean(); ?>
