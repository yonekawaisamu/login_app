<?php

session_start();
if (!isset($_SESSION['id'])) {
    header('Location: sign_in.php');
    exit();
}

$_SESSION = array();
setcookie(session_name(), '', time() - 1, '/' );
session_destroy();

header('Location: ../top.html');
exit();