<?php
require('dbconnect.php');
require('EmployeeClass.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}

if (isset($_GET['id']) || isset($_POST['id'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];

        // 以下代入の条件を考える02/28
        $flag = isset($_POST['flag']) && $_POST['flag'] == 1 ? 1 : 0;

        $update_emp = $db->prepare('UPDATE employees SET last_name=?, first_name=?, emp_delete_flag=? WHERE id=?');
        $update_emp->execute(array(
            $_POST['last_name'],
            $_POST['first_name'],
            $flag,
            $id
        ));

        //削除が選択された場合は、検索ページへ移動
        if ($flag == 1) {
            header('Location: /login_app/employee/search.php');
            exit();
        }
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
        $emp = new Employee($record['id'], $record['last_name'], $record['first_name'], $record['emp_user_name'], $record['emp_delete_flag']);
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
                <?php if ($emp->getFlag() == 0): ?>
                    <!-- 社員が生きている場合 -->
                    <div>
                        <label for="last_name">お名前:</label>
                        <input type="text" name="last_name" maxlength="50" placeholder="姓" class="name" id="last_name" required value="<?php echo $emp->getLast(); ?>"> 
                        <input type="text" name="first_name" maxlength="50" placeholder="名" class="name" required value="<?php echo $emp->getFirst(); ?>">
                    </div>
                    <p>削除する場合、以下にチェックをいれてください。</P>
                    <input type="checkbox" name="flag" value="1">削除
                <?php elseif ($emp->getFlag() == 1): ?>
                    <!-- 社員が削除済みの場合 -->
                    <input type="hidden" name="last_name" value="<?php echo $emp->getLast(); ?>">
                    <input type="hidden" name="first_name" value="<?php echo $emp->getFirst(); ?>">
                    <input type="hidden" name="flag" value="0">
                <?php endif; ?>
            </div>
            <input type="hidden" name="id" value="<?php echo $emp->getId(); ?>">
            <div class="button">
                <button type="submit"><?php echo $emp->getFlag() == 0 ? '更新' : '社員を復元'; ?></button>
            </div>
        </form>
    <?php endif ?>
</body>
</html>