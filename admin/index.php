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
    <link rel="stylesheet" type="text/css" href="../css/admin_index.css">
</head>
<body>
    <header>

    </header>
    <div class="main">
        <ul>
            <a href="/login_app/employee/add_emp.php"><div class="link-box"><li>社員追加</li></div></a>
            <a href="/login_app/employee/search.php"><div class="link-box"><li>社員検索</li></div></a>
            <a href="sign_out.php"><div class="link-box"><li>ログアウト</li></div></a>
        </ul>
    </div>
    <footer>

    </footer>
</body>
</html>