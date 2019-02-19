<?php
require('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($search_word = filter_input(INPUT_POST, 'word', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $search_word = '%' . $search_word . '%';
        $records = $db->prepare('SELECT * FROM employees WHERE last_name LIKE ? OR first_name LIKE ? ORDER BY last_name ASC');
        $records->execute(array(
            $search_word,
            $search_word
        ));
    } else {
        $records = $db->query('SELECT * FROM employees ORDER BY last_name ASC');
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>社員検索</title>
</head>
<body>
    <header>
        <a href="/login_app/admin/index.php">index</a>
    </header>
    <main>

        <form action="" method="POST">
            <input type="text" name="word">
            <button type="submit">検索</button>
        </form>

        <ul>
            <?php if (isset($records)): ?>
                <?php while ($emps = $records->fetch()): ?>
                    <li><a href="./show.php?employee_id=<?php echo $emps['id'] ?>"><?php echo htmlspecialchars($emps['last_name'] . ' ' . $emps['first_name']); ?></a></li>
                <?php endwhile; ?>
            <?php endif; ?>
        </ul>
    
    </main>
    <footer>

    </footer>
</body>
</html>