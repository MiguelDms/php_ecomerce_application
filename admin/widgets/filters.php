<?php 

    $cat_id = ((isset($_REQUEST['cat']))?sanitize($_REQUEST['cat']): ''); /* para no caso de estarmos numa pagina de category */
    $price_sort = ((isset($_REQUEST['price_sort']))?sanitize($_REQUEST['price_sort']): '');
    $min_price = ((isset($_REQUEST['min_price']))?sanitize($_REQUEST['min_price']): '');
    $max_price = ((isset($_REQUEST['max_price']))?sanitize($_REQUEST['max_price']): '');
    $b =  ((isset($_REQUEST['brand']))?sanitize($_REQUEST['brand']): '');
    $brandQ = $conn->query("SELECT * FROM brand ORDER BY brand");

?>

<h3 class="text-center">Procurar por:</h3>
<h4 class="text-center">Preço</h4>
<form action="search.php" method="POST">
    <input type="hidden" name="cat" value="<?php echo $cat_id;?>"> <!-- para se fazer o post para a search.php, para o caso da pagina ser category -->
    <input type="hidden" value="0" name="price_sort"> <!-- para no caso de nenhum valor min-max estar chekado -->
    <input type="radio" name="price_sort" id="" value="low" <?php echo (($price_sort == 'low')?' checked':'');?>>Baixo para alto <br>
    <input type="radio" name="price_sort" id="" value="high" <?php echo (($price_sort == 'high')?' checked':'');?>>Alto para baixo <br>
    <input type="text" name="min_price" class="price-range" placeholder="Min $" value="<?php echo $min_price; ?>">a
    <input type="text" name="max_price" class="price-range" placeholder="Max $" value="<?php echo $max_price; ?>"><br><br>
    <h4 class="text-center">Marca</h4>
    <input type="radio" name="brand" value=""<?php echo (($b == '')?' checked':'');?>>Todas<br>
    <?php while($brand = mysqli_fetch_assoc($brandQ)): ?>
        <input type="radio" name="brand" value="<?php echo $brand['id'];?>" <?php echo (($b == $brand['id'])? ' checked': ''); ?>><?php echo $brand['brand'];?> <br>
<?php endwhile; ?>
<input type="submit" value="procurar" class="btn btn-sm btn-primary">
</form>
