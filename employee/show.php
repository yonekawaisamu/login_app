<?php
require('dbconnect.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: sign_in.php');
    exit();
}

if ($id = filter_input(INPUT_GET, 'id')) {
    //社員検索
    $emp_record = $db->prepare('SELECT * FROM employees where id=?');
    $emp_record->execute(array($id));
    $emp = $emp_record->fetch();

    //出退勤履歴取得
    $time_record = $db->prepare('SELECT * FROM time_record where employee_id=? ORDER BY date DESC, time DESC');
    $time_record->execute(array($id));
    var_dump($time_record->fetch());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>show</title>
</head>
<body>
    <h1>showです</h1>
    <!-- 社員名編集、社員削除、出退勤履歴、を追加する -->
    <?php if (isset($emp)): ?>
        <?php echo $emp['last_name'] ?>
        <?php echo $emp['first_name'] ?>
    <?php endif ?>
</body>
</html>