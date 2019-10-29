<?php 

require_once '../core/connection.php';
if (!isLoggedIn()) {
    login_error_redirect($url = 'login.php');
}


include 'includes/head.php';
include 'includes/navigation.php';

?>


<body>

<header>
        <div id="headerWrapper">
          
        </div>
    </header>   
    <div class="container-fluid">
     

    <?php include 'includes/footer.php'; ?>


    <script src="../js/main.js"></script>

</body>
</html>