<?php
require('dbconnect.php');
session_start();

$admin = $db->query('SELECT id FROM admin LIMIT 1');
$admin = $admin->fetch();

if ($admin !== false) {
    if (!isset($_SESSION['id'])) {
        header('Location: sign_in.php');
        exit();
    }
}

if (!isset($_SESSION['join'])) {
    header('Location: sign_in.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['join'])) {
    $state = $db->prepare('INSERT INTO admin SET user_name=?, password=?, created_at=NOW()');
    $state->execute(array(
        $_SESSION['join']['user_name'],
        password_hash($_SESSION['join']['password'], PASSWORD_DEFAULT)
    ));
    unset($_SESSION['join']);
    setcookie('msg', 'on');
    header('Location: sign_in.php');
    exit();
}

$count = strlen($_SESSION['join']['password']);
$pass  = str_repeat('*', $count);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>sign_up確認</title>
    <link rel="stylesheet" type="text/css" href="../css/form.css">
</head>
<body link="#337ab7" vlink="#337ab7">
    <header> 
    </header>
    <h1>登録確認</h1>
    <form action="" method="post">
        <div>
            <table>
                <tr>
                    <td class="table-left">ユーザー名:</td>
                    <td class="table-right"><?php echo htmlspecialchars($_SESSION['join']['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td class="table-left">パスワード:</td>
                    <td class="table-right"><?php echo $pass ?></td>
                </tr>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td class="table-left"><a href="sign_up.php">戻る</a></td>
                    <td class="table-right"><button type="submit">登録する</button></td>
                </tr>
            </table>
        </div>
    </form>
</body>
</html>