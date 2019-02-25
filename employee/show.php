<?php
require('dbconnect.php');
require('UserClass.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}

if (isset($_GET['id']) || isset($_POST['id'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $update_emp = $db->prepare('UPDATE employees SET last_name=?, first_name=? WHERE id=?');
        $update_emp->execute(array(
            $_POST['last_name'],
            $_POST['first_name'],
            $id
        ));
    }
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $id = $_GET['id'];
    }
    //社員検索
    $emp_record = $db->prepare('SELECT * FROM employees where id=?');
    $emp_record->execute(array($id));
    $record = $emp_record->fetch();

    //Userインスタンス作成
    if (isset($record)) {
        $emp = new User($record['id'], $record['last_name'], $record['first_name'], $record['emp_user_name'], $record['emp_delete_flag']);
    }

    //出退勤履歴取得
    $time_record = $db->prepare('SELECT * FROM time_record where employee_id=? ORDER BY date DESC, time DESC');
    $time_record->execute(array($id));
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>show</title>
</head>
<body>
    <header>
        <a href="/login_app/admin/index.php">index</a>
        <a href="/login_app/employee/search.php">社員一覧</a>
    </header>
    <h1>showです</h1>

    <!-- 社員削除、出退勤履歴、を追加する -->
    <?php if (isset($record)): ?> 
        <p>現在のお名前： <?php echo $emp->getName(); ?></p>
        <p>ユーザー名： <?php echo $emp->getUserName(); ?></p>

        <form action="" method="POST">
            <div>
                <label for="last_name">お名前:</label>
                <input type="text" name="last_name" maxlength="50" placeholder="姓" class="name" id="last_name" 
                required value="<?php echo $emp->getLast(); ?>"> 
                <input type="text" name="first_name" maxlength="50" placeholder="名" class="name" required value="<?php echo $emp->getFirst(); ?>">
            </div>
            <input type="hidden" name="id" value="<?php echo $emp->getId(); ?>">
            <div class="button">
                <button type="submit">更新</button>
            </div>
        </form>
    <?php endif ?>
</body>
</html>