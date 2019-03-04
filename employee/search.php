<?php
require('dbconnect.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}
//検索機能
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($search_word = filter_input(INPUT_POST, 'word', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $search_word = str_replace(array(" ", "　"), "", $search_word);
        $search_word = '%' . $search_word . '%';
        $records = $db->prepare('SELECT * FROM employees WHERE emp_delete_flag=0 AND CONCAT(last_name, first_name) LIKE ? OR last_name LIKE ? OR first_name LIKE ? ORDER BY last_name DESC');
        $records->execute(array(
            $search_word,
            $search_word,
            $search_word
        ));
    } else {
        $records = $db->query('SELECT * FROM employees WHERE emp_delete_flag=0 ORDER BY last_name DESC');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>社員検索</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/search.css">
</head>
<body>
    <header>
        <a href="/login_app/admin/index.php">管理者トップ画面</a>
    </header>
    <div class="main">
        <a href="/login_app/employee/emp_delete.php">削除した社員一覧</a>
        <h1>社員検索</h1>
        <div class="form">
            <form action="" method="POST">
                <input type="text" name="word" placeholder="社員名" autofocus>
                <button type="submit">検索</button>
            </form>
        </div>
        <div class="list">
            <ul>
                <?php if (isset($records)): ?>
                    <?php while ($emps = $records->fetch()): ?>
                        <li><a href="./show.php?id=<?php echo $emps['id'] ?>"><?php echo htmlspecialchars($emps['last_name'] . ' ' . $emps['first_name'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endwhile; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <footer>
    </footer>
</body>
</html>