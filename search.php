<?php 


require_once 'core/connection.php';
include 'includes/head.php';


$sql = "SELECT * FROM products"; 
$cat_id = ((isset($_POST['cat']) != '')?sanitize($_REQUEST['cat']): '');

if ($cat_id == '') {
    $sql .= ' WHERE deleted = 0';
} else {
    $sql .= " WHERE categories = '{$cat_id}' AND deleted = 0";
}

$price_sort = (($_POST['price_sort'] != '')?sanitize($_POST['price_sort']) : '');
$min_price = (($_POST['min_price'] != '')?sanitize($_POST['min_price']) : '');
$max_price = (($_POST['max_price'] != '')?sanitize($_POST['max_price']) : '');
$brand = (($_POST['brand'] != '')?sanitize($_POST['brand']) : '');

if ($min_price != '') {
    $sql .= " AND price >= '{$min_price}'";
}

if ($max_price != '') {
    $sql .= " AND price <= '{$max_price}'";
}

if ($brand != '') {
    $sql .= " AND brand = '{$brand}'";
}

if ($price_sort == 'low') {
    $sql .= ' ORDER BY price';
}

if ($price_sort == 'high') {
    $sql .= ' ORDER BY price DESC';
}

$productC = $conn->query($sql); // Está a ir buscar o query acima mencionado através da conecxao
$category = getCategory($cat_id);



?>

<body>

<?php include 'includes/navigation.php';
include 'includes/header_full.php'; 

?>
    
    <!-- left side bar -->
        <div class="col-md-2"> Left side bar </div>
    <!-- main content -->
        <div class="col-md-8">
        <?php if($cat_id != ''): ?>
        <h2 class="text-center"><?php echo $category['parent']. ' '.$category['child'];?></h2> 

        <?php else:  ?>

        <h2 class="text-center">Resultados:</h2>
<?php endif;  ?>
        <?php while ($product = mysqli_fetch_assoc($productC)) :?><!-- isto evita com que se faça a while da maneira usual, com os parentesis e os curly bracelets, e se tenha por isso de escrever o codigo html que vem a seguir dentro do php, com echos e coiso e tal -->

            <div class="row">
                <div class="col-sm">
                    <h4><?php echo utf8_encode($product['title']);?></h4>
                    <img src="<?php echo $product['image'];?>" alt="<?php echo $product['title'];?>" width="230px" height="400px">
                    <p class="list-price text-danger">Preços de lista <s><?php echo $product['list_price'];?></s></p>
                    <p class="price">O nosso preço: <b><?php echo $product['price'];?></b></p>
                    <button type="button" class="btn btn-lg btn-success" onclick="detailsModal(<?php echo $product['id'];?>)">Detalhes </button>
                </div>
        <?php endwhile; ?>
            </div>
        </div>
    <!-- right side bar -->

    </div>  
    <div class="col-md-2">Right side bar</div>
    <div class="col-md-12 text-center">&copy; Copyright 2017-2019 Loja do Miguel</div>

    <script src="js/main.js"></script>
  
</body>
</html>