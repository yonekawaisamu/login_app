<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: sign_in.php');
    exit();
}

function delete_cookie() {
    setcookie('msg', '', time() - 3600);
    unset($_COOKIE['msg']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>管理者設定画面</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_index.css">
</head>
<body link="#337ab7" vlink="#337ab7">
    <header></header>
    <div class="main">
    <?php if (isset($_COOKIE['msg'])): ?>
        <ul class="msg"><li>管理者を登録しました！</li></ul>
        <?php delete_cookie() ?>
    <?php endif; ?>
        <ul>
            <a href="/login_app/admin/sign_up.php"><div class="link-box"><li>管理者登録</li></div></a>
            <a href="/login_app/employee/add_emp.php"><div class="link-box"><li>社員追加</li></div></a>
            <a href="/login_app/employee/search.php"><div class="link-box"><li>社員検索</li></div></a>
            <a href="sign_out.php"><div class="link-box"><li>ログアウト</li></div></a>
        </ul>
    </div>
    <footer></footer>
</body>
</html>