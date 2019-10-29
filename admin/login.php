<?php 

require_once  $_SERVER['DOCUMENT_ROOT'].'/Php_ecommerce_website/core/connection.php';
include 'includes/head.php';
/* $password = 'Hermes3megisto'; //como fazer hash á password
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed; */

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();
?>

<div id="login-form"> 
    <div class="">
    
    <?php 
    if ($_POST) {
       //form validation 
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $errors[] = 'Tem de preencher ambos os campos';
        }

        //email validation

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Tem de entrar um email valido';
        }

        // password more than six characters 

        if (strlen($password) < 6) {
            $errors[] = 'A password tem de conter mais de 6 caracteres';
        }

        // check if email exists in db
        $query = $conn->query("SELECT * FROM users WHERE email = '$email'");
        $user = mysqli_fetch_assoc($query);
        $userCount = mysqli_num_rows($query);

        if ($userCount < 1) {
            $errors[] = 'Esse user não existe na base de dados';
        }

        if (!password_verify($password, $user['password'])) {
           $errors[] = 'A password não coincide. Por favor tente outra vez';
        }

        if (!empty($errors)) {
           echo displayErrors($errors);
        } else {
            $user_id = $user['id'];
           login($user_id);
        }

    }
    ?>
    
    </div>
    <h2 class="text-center">Login</h2><hr>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" class="form-control" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label for="password">password:</label>
                <input type="password" name="password" id="password" class="form-control" value="<?php echo $password; ?>">
            </div>
            <div class="form-group">
                <input type="submit" value="login">
            </div>
        </form>
        <p class="text-right"><a href="/Php_ecommerce_website/index.php" alt="home">Visitar Website</a></p>
</div>


<?php 
include 'includes/footer.php';

?>