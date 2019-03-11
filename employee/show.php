<?php
require('dbconnect.php');
require('EmployeeClass.php');
require('emp_validations.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}

if (isset($_GET['id']) || isset($_POST['id'])) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $id = $_POST['id'];
            $errors = array();
            $error =  hurigana_validation($_POST['last_hurigana'], $_POST['first_hurigana']);
            if (isset($error)) {
                $errors['hurigana'] = $error;
            }
            
            $error = last_first_validation($_POST['last_name'], $_POST['first_name']);
            if (isset($error)) {
                $errors['last_first'] = $error;
            }
            if (empty($errors)) {
                $flag = isset($_POST['flag']) && $_POST['flag'] == 1 ? 1 : 0;
                $update_emp = $db->prepare('UPDATE employees SET last_name=?, first_name=?, emp_delete_flag=? WHERE id=?');
                $update_emp->execute(array(
                    $_POST['last_name'],
                    $_POST['first_name'],
                    $flag,
                    $id
                ));
                //削除が選択された場合は、移動
                if ($flag == 1) {
                    header('Location: /login_app/employee/emp_delete.php');
                    exit();
                }
            }
            break;
        case 'GET':
            $id = $_GET['id'];
            break;
        default:
            break;
    }
    
    //社員検索
    $emp_state = $db->prepare('SELECT * FROM employees where id=?');
    $emp_state->execute(array($id));
    $emp_record = $emp_state->fetch();
    //Userインスタンス作成
    if (isset($emp_record)) {
        $emp = new Employee($emp_record['id'], $emp_record['last_hurigana'], $emp_record['first_hurigana'], $emp_record['last_name'], $emp_record['first_name'], $emp_record['emp_user_name'], $emp_record['emp_delete_flag']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>社員編集</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/show.css">
</head>
<body link="#337ab7" vlink="#337ab7">
    <header>
        <a href="/login_app/admin/index.php">管理者トップ画面</a>
    </header>

    <div class="main">
        <a href="/login_app/employee/search.php">社員検索</a>
        <h1>社員編集</h1>

        <?php if (!empty($errors)): ?>
            <ul class="validation_error">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (isset($emp_record)): ?> 
            <table>
                <tr>
                    <td class="table-right">現在のふりがな:</td>
                    <td class="table-left"><?php echo $emp->getHurigana(); ?></td>
                </tr>
                <tr>
                    <td class="table-right">現在のお名前:</td>
                    <td class="table-left"><?php echo $emp->getName(); ?></td>
                </tr>
                <tr>
                    <td class="table-right">ユーザー名:</td>
                    <td class="table-left"><?php echo $emp->getUserName(); ?></td>
                </tr>
                <form action="" method="POST">
                    <?php if ($emp->getFlag() == 0): ?>
                        <!-- 社員が生きている場合 -->
                        <tr>
                            <td class="table-right">ふりがな変更:</td>
                            <td><input type="text" name="last_hurigana" maxlength="50" placeholder="せい" class="name" required value="<?php echo $emp->getLastH(); ?>"></td>
                            <td><input type="text" name="first_hurigana" maxlength="50" placeholder="めい" class="name" required value="<?php echo $emp->getFirstH(); ?>"></td>
                        </tr>
                        <tr>
                            <td class="table-right">お名前変更:</td>
                            <td><input type="text" name="last_name" maxlength="50" placeholder="姓" class="name" required value="<?php echo $emp->getLast(); ?>"></td>
                            <td><input type="text" name="first_name" maxlength="50" placeholder="名" class="name" required value="<?php echo $emp->getFirst(); ?>"></td>
                        </tr>
                        <tr>
                            <td class="table-right">社員削除:</td>
                            <td><input type="checkbox" class="check" name="flag" value="1"></td>
                            <td></td>
                        </tr>
                    <?php elseif ($emp->getFlag() == 1): ?>
                            <!-- 社員が削除済みの場合 -->
                            <input type="hidden" name="last_hurigana" value="<?php echo $emp->getLastH(); ?>">
                            <input type="hidden" name="first_hurigana" value="<?php echo $emp->getFirstH(); ?>">
                            <input type="hidden" name="last_name" value="<?php echo $emp->getLast(); ?>">
                            <input type="hidden" name="first_name" value="<?php echo $emp->getFirst(); ?>">
                            <input type="hidden" name="flag" value="0">
                    <?php endif; ?>
                    <input type="hidden" name="id" value="<?php echo $emp->getId(); ?>">
                    <tr>
                        <td class="table-right"></td>
                        <td><button type="submit"><?php echo $emp->getFlag() == 0 ? '更新' : '社員を復元'; ?></button></td>
                        <td></td>
                    </tr>
                </form>
            </table>
            <hr>
            <div>
                <h2><?php echo $emp->getName(); ?>さんの出退勤履歴を検索</h2>
                <p>表示したい年月を指定してください</p>
                <form action="time_record_history.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $emp->getId(); ?>">
                    <input type="month" name="y_m" value="<?php echo date("Y-m"); ?>" required>
                    <input type="submit" value="検索">
                </form>
            </div>
        <?php endif ?>
    </div>
</body>
</html>