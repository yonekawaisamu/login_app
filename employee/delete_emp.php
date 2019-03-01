<?php
//削除した社員一覧作成予定
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
    <title>Page Title</title>
</head>
<body>
    <header>
        <a href="/login_app/admin/index.php">index</a>
        <a href="/login_app/employee/search.php">社員一覧</a>
    </header>
    <?php while($emp = $emps->fetch()): ?>
        <ul>
            <li><a href="./show.php?id=<?php echo $emp['id'] ?>"><?php echo htmlspecialchars($emp['last_name'] . ' ' . $emp['first_name'], ENT_QUOTES, 'UTF-8'); ?></a></li>
        </ul>
    <?php endwhile; ?>
</body>
</html>