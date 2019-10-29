<?php 

require_once '../core/connection.php';
if (!isLoggedIn()) {
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

//get brands from db

$sql = "SELECT * FROM brand ORDER BY brand";
$results = $conn->query($sql);
$errors = array();

// edit brand
if (isset(($_GET['edit']))) {
    $edit_id = $_GET['edit'];
   $edit_id = sanitize($edit_id);
   $sqlEdit = "SELECT * FROM brand WHERE id = '$edit_id'";
   $edit_result = $conn->query($sqlEdit);
   $editBrand = mysqli_fetch_assoc($edit_result);
 }


// delete brand
if (isset(($_GET['delete']))) {
   $delete_id = $_GET['delete'];
   $delete_id = sanitize($delete_id);
   $sqlDelete = "DELETE FROM brand WHERE id = '$delete_id'";
   $conn->query($sqlDelete);
   header('Location: brands.php');
}

//if form is submitted

if (isset($_POST['add_submit'])) {
    $brand = sanitize($_POST['brand']);

    //check if input brand is blank
    if ($_POST['brand'] == '') {
       $errors[] .= 'Coloque uma marca!';
    }
    //check if brand exists in db

    $sql2 = "SELECT * FROM brand WHERE brand = '$brand'";
    if (isset($_GET['edit'])) {
        $sql2 = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'"; //este check vai procurar por uma entrada onde a marca é igual a alguma já existente na db, mas com um id diferente, porque ao fazermos o edit poderiamos editar para uma marca já existente, por engano. E desta forma, isso não nos é permitido. 
    }

    $result = $conn->query($sql2);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        $errors[] .= 'A marca '.$brand.' já foi inserida. Por favor coloque outra marca.';
    }
    
    //display errors
    if (!empty($errors)) {
        echo displayErrors($errors);
    } else {
        //add brand to db
        $sql3 = "INSERT INTO brand (brand) VALUES ('$brand')";
        if (isset($_GET['edit'])) {
            $sql3 = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";

            var_dump($sql3);
        }

        $conn->query($sql3);
        header('Location: brands.php'); 
    }
}

?>


<body>

<header>
        <div id="headerWrapper">
          
         </div>
        <h2 class="text-center text-uppercase font-weight-bold">Marcas</h2><hr>

        <!-- Brand form -->

    <div class="form-centered">
        <form action="brands.php<?php echo ((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post" class="form-inline">
            <div class="form-group">
            <?php
            $brand_value = '';
            if(isset($_GET['edit'])) {
                $brand_value = $editBrand['brand'];
            }else {
                if (isset($_POST['brand'])) {
                   $brand_value = sanitize($_POST['brand']);
                }
            } ; ?>


                <label for="brand"><?php echo ((isset($_GET['edit']))?'Edite':'Adicione');?> uma marca:</label>
                <input type="text" name="brand" id="brand" class="form-control" value="<?php echo $brand_value;  ?>">
                <?php if (isset($_GET['edit'])): ?>
                <a href="brands.php" class="btn btn-light" role="button">Cancelar</a>
                <?php endif;?>

                <input type="submit" name="add_submit" value=<?php echo ((isset($_GET['edit']))?'Edite':'Adicione');?> class="btn btn-large btn-success">
            </div>
        </form>
    </div>

    <hr>

        <table class="table table-bordered table-striped table-auto">
            <thead>
                <th></th><th>Brand</th><th></th>
            </thead>
            <tbody>
                <?php while ($brand = mysqli_fetch_assoc($results)) : ?>
                <tr>
                     <td><a href="brands.php?edit=<?php echo $brand['id'] ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                    <td><?php echo $brand['brand']; ?></td>
                    <td><a href="brands.php?delete=<?php echo $brand['id'] ?> " class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
</header>   
    <div class="container-fluid">


     

    <?php include 'includes/footer.php'; ?>


    <script src="../js/main.js"></script>

</body>
</html>