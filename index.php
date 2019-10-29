<?php 


require_once 'core/connection.php';
include 'includes/head.php';



$sql = "SELECT * FROM products WHERE Featured = 1"; //Featured neste caso é um boolean, que vai determinar se o produto entra ou nao na home page

$featured = $conn->query($sql); // Está a ir buscar o query acima mencionado através da conecxao



?>

<body>

<?php include 'includes/navigation.php';
include 'includes/header_full.php';
?>
    
    <!-- left side bar -->
        <div class="col-md-2"> 
        <?php include 'admin/widgets/filters.php';
?>
        </div>
    <!-- main content -->
        <div class="col-md-8">
        <h2 class="text-center">Produtos em display</h2>

        <?php while ($product = mysqli_fetch_assoc($featured)) :?><!-- isto evita com que se faça a while da maneira usual, com os parentesis e os curly bracelets, e se tenha por isso de escrever o codigo html que vem a seguir dentro do php, com echos e coiso e tal -->

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
    <div class="col-md-2">

        <?php 
        include 'admin/widgets/cart.php';
        include 'admin/widgets/recent.php';
        ?>
    
    </div>
    </div>  
   
    <div class="col-md-12 text-center">&copy; Copyright 2017-2019 Loja do Miguel</div>


    <script src="js/main.js"></script>
  
</body>
</html>