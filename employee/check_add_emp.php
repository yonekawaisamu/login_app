<?php
session_start();
require('dbconnect.php');

if (!isset($_SESSION['join']) && !isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $name = $_SESSION['join']['last_name'] . ' ' . $_SESSION['join']['first_name'];
    $emp_user_name = $_SESSION['join']['emp_user_name'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['join'])) {
    $state = $db->prepare('INSERT INTO employees SET last_name=?, first_name=?, emp_user_name=?, created_at=NOW()');
    $state->execute(array(
        $_SESSION['join']['last_name'],
        $_SESSION['join']['first_name'],
        $_SESSION['join']['emp_user_name']
    ));
    unset($_SESSION['join']);
    
    header('Location: /login_app/admin/index.php');
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>社員登録確認</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/emp_form.css">
</head>
<body>
    <header>

    </header>
    <main>
        <h1>社員登録確認</h1>
        <form action="" method="POST">
            <div>
                お名前: <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div>
                ユーザー名: <?php echo htmlspecialchars($emp_user_name, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="button">
                <a href="add_emp.php">戻る</a>　<button type="submit">Send</button>
            </div>
        </form>
    </main>
    <footer>

    </footer>
</body>
</html>