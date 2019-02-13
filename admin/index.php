<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: sign_in.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>管理者設定画面</title>
</head>
<body>
    <header>

    </header>
    <main>
        <ul>
            <li><a href="/login_app/employee/add_emp.php">社員追加</a></li>
            <li><a href="/login_app/employee/list_emp.php">社員一覧</a></li>
            <li><a href="sign_out.php">ログアウト</a></li>
        </ul>
    </main>
    <footer>

    </footer>
</body>
</html>