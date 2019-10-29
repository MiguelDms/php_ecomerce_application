<nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top">
        <div class="container">
            <a href="/Php_ecommerce_website/admin/index.php" class="navbar-brand">Miguel's Boutique Admin</a>
            <ul class="nav navbar-nav">

            <?php
            if (hasPermission('admin')):
             ?>
            <li class="nav-item"><a class="nav-link" href="users.php">Utilizadores</a></li>  
        
           <?php endif;?>    
           
            <li class="nav-item"><a class="nav-link" href="brands.php">Marcas</a></li>      
            <li class="nav-item"><a class="nav-link" href="categories.php">Categorias</a></li>   
            <li class="nav-item"><a class="nav-link" href="products.php">Produtos</a></li>  
            <li class="nav-item"><a class="nav-link" href="archived_products.php">Produtos arquivados</a></li>  
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Olá <?php echo $user_data['first'];?><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="change-password.php">Mudar password</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
            </li>
            
             

<!-- 
                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle first-item" role="button" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span> </a>  --><!-- o data-toggle é uma funçao bootstrap de js-->
                    <!-- <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                   
                </div>
                </li> -->

            </ul>
        </div>
    </nav>