<?php
//削除した社員一覧作成予定
require('dbconnect.php');

$emps = $db->query('SELECT * FROM employees WHERE emp_delete_flag=1');

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Page Title</title>
</head>
<body>
    <?php while($emp = $emps->fetch()): ?>
        <?php echo $emp['last_name']; ?>
    <?php endwhile; ?>
</body>
</html>