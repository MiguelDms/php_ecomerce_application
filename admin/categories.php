<?php 

require_once  $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/core/connection.php';
if (!isLoggedIn()) {
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

$sql = "SELECT * FROM categories WHERE parent = 0";
$result = $conn->query($sql);
$errors = array();
$category = '';
$post_parent = '';
$selected = '';

// Delete category 

if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_id = sanitize($delete_id);
    $deleteSql = "DELETE FROM categories WHERE id = '$delete_id'";
    $conn->query($deleteSql);

// deleting childs along with parents

    $deleteChildSql = "DELETE FROM categories WHERE parent = '$delete_id'";
    $conn->query($deleteChildSql);

// header redirection    
    header('Location: categories.php');
    
}


//Edit category

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
   $edit_id = $_GET['edit'];
   $edit_id = sanitize($edit_id);
   $editSql = "SELECT * FROM categories WHERE id = '$edit_id'";
   $editResult = $conn->query($editSql);
   $edit_category = mysqli_fetch_assoc($editResult);
  
}


//process form

if (isset($_POST) && !empty($_POST)) {
    
    $post_parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);
    $sqlForm = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'"; //teem de estar os dois aqui porque eu posso querer adicionar uma categoria que já existe (como por ex assessorios noutro parent)
    

    if (isset($_GET['edit'])) { //if editing
        $id = $edit_category['id'];
       $sqlForm = "SELECT * FROM categories WHERE category = '$category' AND parent ='$post_parent' AND id != '$id'"; //este check vai procurar por uma entrada onde a marca é igual a alguma já existente na db, mas com um id diferente, porque ao fazermos o edit poderiamos editar para uma marca já existente, por engano. E desta forma, isso não nos é permitido. Editarmos algo vai criar uma entrada na db, com um novo id...
    }

    //if category is blank
    $fresult = $conn->query($sqlForm);
    $count = mysqli_num_rows($fresult);
    if ($category == '') {
        $errors[] .= 'Insira algum valor na categoria.';
    } 

    //if it exists in db

    if ($count > 0) {
        $errors[] .= $category. ' já existe. Por favor escolha outra categoria.';    
    }

    //display errors or add to db

    if (!empty($errors)) {
        $display = displayErrors($errors);
?>

    <script>
        $('document').ready(function () {
           $('#error-display').html('<?php echo $display;?>') 
        });
    </script>
<?php        
    } else {

        //update database with new entry
        $updatesql ="INSERT INTO categories (category, parent) VALUES ('$category','$post_parent')";

        //update database with new edit
        if (isset($_GET['edit'])) {
            $updatesql = "UPDATE categories SET category = '$category', parent = '$post_parent' WHERE id = '$edit_id'";
 }

        $conn->query($updatesql);
        header('Location: categories.php');

       
    }
}

// Add the values to the input bar after $_GET[edit] is set

$category_values = '';
$parent_value = 0; // este parent value vai ser usado abaixo no isset(post);
if (isset($_GET['edit'])) {
  $category_values = $edit_category['category'];
   $parent_value = $edit_category['parent'];
} else {
    if (isset($_POST)) {
       $category_values = $category; //se houver algum erro por parte no user no edit, o value irá lá ficar á mesma
       $parent_value = $post_parent;
    }   
}

?>


<h2 class="text-center">Categorias</h2><hr>
<div class="row">

<!-- FORM -->
    <div class="col-md-6">
        <form action="categories.php<?php echo ((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post" class="form">
            <legend><?php echo ((isset($_GET['edit']))?'Edite':'Adicione');?> uma cetegoria</legend>
            <div id="error-display"></div>
            <div class="form-group">
                <label for="parent" >Parente</label>
                <select name="parent" class="form-control" id="parent"> <!-- o id aqui é relativo ao for da label -->
                    <option value="0"<?=(($parent_value == 0)?' selected="selected"':'');?>>Parente</option>
                    <?php while ($parent = mysqli_fetch_assoc($result)): ?>
                    <option value="<?=$parent['id'];?>"<?=(($parent_value == $parent['id'])?'selected=" selected"':'');?>><?=$parent['category'];?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="category"></label>
                <input type="text" class="form-control" id="category" name="category" value="<?php echo $category_values;?>">
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="<?php echo ((isset($_GET['edit']))?'Editar':'Adicione');?> categoria">
                <?php if (isset($_GET['edit'])): ?>
                <a href="categories.php" class="btn btn-light btn-lg" role="button">Cancelar</a>
                <?php endif;?>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="col-md-6">
        <table class="table table-bordered">
            <thead>
                <th>Categoria</th>
                <th>Parent</th>
                <th></th>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM categories WHERE parent = 0";
                $result = $conn->query($sql);
                while($parent = mysqli_fetch_assoc($result)):
                    $parent_id = $parent['id'];
                    $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
                    $childResult = $conn->query($sql2);

                    ?>
                <tr class="bg-light">
                    <td><?php echo $parent['category'];?></td>
                    <td>Parente</td>
                    <td>
                        <a href="categories.php?edit=<?php echo $parent['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href="categories.php?delete=<?php echo $parent['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                    </td>
                </tr>

                <?php
                 while($child = mysqli_fetch_assoc($childResult)): ?>

                
                <tr class="bg-info">
                    <td><?php echo $child['category'];?></td>
                    <td><?php echo $parent['category'];?></td>
                    <td>
                        <a href="categories.php?edit=<?php echo $child['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href="categories.php?delete=<?php echo $child['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                    </td>
                </tr>

                <?php endwhile;?>
                <?php endwhile;?>
            </tbody>
        </table>
    </div>
</div>


<?php 

include 'includes/footer.php';