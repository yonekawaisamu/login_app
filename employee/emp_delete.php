<?php
//削除した社員一覧
require('dbconnect.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}
$emps = $db->query('SELECT * FROM employees WHERE emp_delete_flag=1');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>削除した社員一覧</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/emp_delete.css">
</head>
<body>
    <header>
        <a href="/login_app/admin/index.php">管理者トップ画面</a>
    </header>
    <div class="main">
        <a href="/login_app/employee/search.php">社員検索</a>
        <h1>削除した社員一覧</h1>
        <div class="list">
            <ul>
                <?php while($emp = $emps->fetch()): ?>
                        <li><a href="./show.php?id=<?php echo $emp['id'] ?>"><?php echo htmlspecialchars($emp['last_name'] . ' ' . $emp['first_name'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</body>
</html>