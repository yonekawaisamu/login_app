<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: admin_login.php');
    exit();
}

$_SESSION = array();
setcookie(session_name(), '', time() - 1, '/' );
session_destroy();

header('Location: sign_in.php');
exit();