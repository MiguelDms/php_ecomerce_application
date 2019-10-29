<?php 

require_once '../core/connection.php';
if (!isLoggedIn()) {
    login_error_redirect();
}

if (!hasPermission('admin')) {
    permission_error_redirect('index.php');
}
include 'includes/head.php';
include 'includes/navigation.php';


//APAGAR USERS DA BD
if (isset($_GET['delete'])) {
    $delete_id = sanitize($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = '$delete_id'");
    $_SESSION['success_flash'] = 'O utilizador foi apagado';
    header('Location: users.php');
}

if (isset($_GET['add'])) { // se carregar no botao adicionar novo user faz isto, senão faz tudo o resto, como no products-php

    $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');

    $errors = array();

    if ($_POST) {

    
    // see if email exists in db
    $emailQuery = $conn->query("SELECT * FROM users WHERE email = '$email'");
    $emailCount = mysqli_num_rows($emailQuery);

    if ($emailCount != 0) {
       $errors[] = 'O email introduzido já existe na base de dados';
    }

        $required = array('name', 'email', 'password', 'confirm', 'permissions'); 

        //see if fields are empty

        foreach($required as $field) { 
            if ($_POST[$field] == '') {
                $errors[] = 'Todos os campos com asterisco são de preenchimento obrigatório.';
                break;
        }
    }

      // see if password has more than 6 characters

    if (strlen($password) < 6) {
        $errors[] = 'A password tem de ter pelo menos 6 caracteres.';
    }

    // see if passwords match

    if ($password != $confirm) {
        $errors[] = 'As passwords não coincidem';
    }

    // see if is valid email
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Tem de preencher um email válido';
    }

    if (!empty($errors)) {
        echo displayErrors($errors);
    
     } else {
         // add user to db
        $hashed = password_hash($password, PASSWORD_DEFAULT);
         $conn->query("INSERT INTO users (full_name,email,password,permissions) VALUES ('$name', '$email', '$hashed', '$permissions')");
         $_SESSION['success_flash'] = 'O utilizador foi adicionado';
         header('Location: users.php');
     }
    }

   ?>

<h2 class="text-center">Adicionar novo utilizador</h2><hr>


    <form action="users.php?add=1" method="POST">
        <div class="form-group col-md-6">
            <label for="name">Nome completo*:</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo $name;?>">
        </div>
        <div class="form-group col-md-6">
            <label for="email">email*:</label>
            <input type="text" class="form-control" name="email" id="email" value="<?php echo $email;?>">
        </div>
        <div class="form-group col-md-6">
            <label for="password">password*:</label>
            <input type="password" class="form-control" name="password" id="password" value="<?php echo $password;?>">
        </div>
        <div class="form-group col-md-6">
            <label for="confirm">Confirmar password*:</label>
            <input type="password" class="form-control" name="confirm" id="confirm" value="<?php echo $confirm;?>">
        </div>
        <div class="form-group col-md-6">
            <label for="permissions">Permissões*:</label>
                <select class="form-control" name="permissions">
                    <option value=""<?php echo (($permissions == '')?' selected':'');?>></option>
                    <option value="editor"<?php echo (($permissions == 'editor')?' selected':'');?>>Editor</option>
                    <option value="admin,editor"<?php echo (($permissions == 'admin,editor')?' selected':'');?>>Admin</option>
                </select>
        </div>
        <div class="form-group">
            <a href="users.php" class="btn btn-light">Cancelar</a>
            <input type="submit" value="Adicionar user" class="btn btn-primary btn-sm">
        </div>
    </form>
<?php 
} else { 

//SELECCIONAR USERS
$userQuery = $conn->query("SELECT * FROM users ORDER BY full_name");
?>


<body>

<header>
        <div id="headerWrapper">
        
        </div>
    </header>   
    <div>
        <h2 class="text-center">Utilizadores</h2>
        <a href="users.php?add=1" class="btn btn-success float-right" style="margin-bottom: 10px;">Adicionar novo utilizador</a>
        <hr style="clear: both;">
    </div>
        <table class="table table-bordered table-striped table-condensed">
            <thead><th></th><th>Nome</th><th>Email</th><th>Data de inscrição</th><th>Ultimo login</th><th>Permissões</th></thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($userQuery)): ?>
                <tr>
                    <td>
                    
                    <?php
                    if ($user['id'] != $user_data['id']) :
                    ?>
                        <a href="users.php?delete=<?php echo $user['id'];?>"class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span></a>
                    <?php endif;?>
                    </td>
                    <td><?php echo $user['full_name'];?></td>
                    <td><?php echo $user['email'];?></td>
                    <td><?php echo prettyDate($user['join_date']);?></td>
                    <td><?php echo (($user['last_login'] == '0000-00-00 00:00:00')? 'Nunca': prettyDate($user['last_login'])) ;?></td>
                    <td><?php echo $user['permissions'];?></td>
                </tr>
                <?php endwhile;?>
            </tbody>
        </table>
    <div class="container-fluid">
     

                    <?php } include 'includes/footer.php'; ?>


    <script src="../js/main.js"></script>

</body>
</html>