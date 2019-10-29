<?php 

require_once  $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/core/connection.php';
if (!isLoggedIn()) { //faz-se isto pot segurança, porque se nao estivesse aqui, bastava escrever o endereço da pagina, e ia parar aqui a esta pagina. Nunca necessitava de estar logged in.
    login_error_redirect();  
}
include 'includes/head.php';
/* $password = 'Hermes3megisto'; //como fazer hash á password
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed; */

$user_id = $user_data['id'];
$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$newHash = password_hash($password, PASSWORD_DEFAULT);
$errors = array();
?>

<div id="login-form"> 
    <div class="">
    
    <?php 
    if ($_POST) {
       //form validation 
        if (empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])) {
            $errors[] = 'Tem de preencher todos os campos';
        }

        // password more than six characters 

        if (strlen($password) < 6) {
            $errors[] = 'A password tem de conter mais de 6 caracteres';
        }

        //if new password matches confirm
        if ($password != $confirm) {
            $errors[] = 'A nova password não coincide com a password confirmada';
        }

        if (!password_verify($old_password, $hashed)) {
           $errors[] = 'A password antiga não coincide. Por favor tente outra vez';
        }

        if (!empty($errors)) {
           echo displayErrors($errors);
        } else {
            //mudar para a nova password
            $conn->query("UPDATE users SET password = '$newHash' WHERE id = '$user_id'");
            $_SESSION['success_flash'] = 'A sua password foi mudada com sucesso';
            header('Location: index.php');
        }

    }
    ?>
    
    </div>
    <h2 class="text-center">Mudar password</h2><hr>
        <form action="change-password.php" method="POST">
            <div class="form-group">
                <label for="old_password">Password antiga:</label>
                <input type="password" name="old_password" id="old_password" class="form-control" value="<?php echo $old_password; ?>">
            </div>
            <div class="form-group">
                <label for="password">Nova password:</label>
                <input type="password" name="password" id="password" class="form-control" value="<?php echo $password; ?>">
            </div>
            <div class="form-group">
                <label for="confirm">Confirmar password:</label>
                <input type="password" name="confirm" id="confirm" class="form-control" value="<?php echo $confirm; ?>">
            </div>
            <div class="form-group">
                <a href="index.php" class="btn btn-info">Cancelar</a>
                <input type="submit" class="btn btn-outline-dark" value="login">
            </div>
        </form>
        <p class="text-right"><a href="/Php_ecommerce_website/index.php" alt="home">Visitar Website</a></p>
</div>


<?php 
include 'includes/footer.php';

?>