<?php 

function displayErrors($errors) {
  

    $display = '<ul class="bg-danger">';
    foreach($errors as $error) {
        $display .= '<li class="text-light">'.$error.'</li>';

    }
        $display .= '</ul>';
       
        return $display;
       
    }


function sanitize($dirty) {
    return htmlentities($dirty,ENT_QUOTES, "UTF-8");
}

function money($number) {
    return '$'.number_format($number,2); 
}

//login stuff

function login($user_id) {
    $_SESSION['SBUser'] = $user_id; //Assim que a funçao ocorrer, o user_id vai ser passado para a superglobal session
    global $conn;
    $date = date("Y-m-d H:m:s"); //é assim que a base de dados guarda os dados.
    $conn->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
    $_SESSION['success_flash'] = 'Você está logged in';
    header('Location: index.php');
}

function isLoggedIn() {
    if (isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0) {
       return true;
    } else {
        return false;
    }
}

function login_error_redirect($url) {
    $_SESSION['error_flash'] = 'Tem de estar logged in para aceder a esta página';
    header('Location: '.$url);
}

// permission

function hasPermission($permission = 'admin') {
    global $user_data;
    $permissions = explode(',', $user_data['permissions']);
    if (in_array($permission,$permissions,true)) { //ou seja, se 'admin' tiver dentro da array em que se aplicou o explode, ela vai passar.
       return true;
    } else {
        return false;
    }
}


function permission_error_redirect($url = 'login.php') {
    $_SESSION['error_flash'] = 'Não tem permissão para aceder a essa página ';
    header('Location: '.$url);
}

function prettyDate($date) {
    return date("M d, Y h:i A", strtotime($date));
}

function getCategory($child_id) {
 global $conn; 
  $id = sanitize($child_id);
  $sql = "SELECT p.id AS `pid`, p.category AS `parent`, c.id AS `cid`, c.category AS `child`  
  FROM categories c 
  INNER JOIN categories p 
  ON c.parent = p.id
  WHERE c.id = '$id'";

  $query = $conn->query($sql);
  $category = mysqli_fetch_assoc($query);
  return $category;
}