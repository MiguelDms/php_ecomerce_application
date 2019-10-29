<?php

$sql = "SELECT * FROM categories WHERE parent = 0";

$pquery = $conn->query($sql); //tá a fazer um query á base de dados usando a conexao $conn

?>


<nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top">
        <div class="container">
            <a href="index.php" class="navbar-brand">Miguel's Boutique</a>
            <ul class="nav navbar-nav">

            <?php  while($parent = mysqli_fetch_assoc($pquery)) :  /* Esta é a parte que vai buscar os elementos propriamente ditos e vai colocá-los numa variavel; vai busca-los dentro de uma associative array */
                 $parent_id = $parent['id'];
                $sql2 = "SELECT * FROM categories WHERE parent = $parent_id";
                $cquery = $conn->query($sql2);
                 ?>

                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle first-item" role="button" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span> </a> <!-- o data-toggle é uma funçao bootstrap de js-->
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php while($child = mysqli_fetch_assoc($cquery)) :?> <!-- Esta é a parte que vai buscar os elementos propriamente ditos e vai colocá-los numa variavel; vai busca-los dentro de uma associative array -->
          <a class="dropdown-item" href="category.php?cat=<?php echo $child['id'];?>"><?php echo $child['category']; ?></a>
          <?php endwhile; ?>
        </div>
                </li>

<?php endwhile; ?>
                <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart">O meu carrinho</span></a></li>
            </ul>
        </div>
    </nav>