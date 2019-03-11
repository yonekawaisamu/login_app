<?php
require('dbconnect.php');
require('EmployeeClass.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serach_date = filter_input(INPUT_POST, 'y_m');
    $emp_id = filter_input(INPUT_POST, 'id');
    $state = $db->prepare('SELECT * FROM time_record WHERE DATE_FORMAT(date, "%Y-%m")=? AND employee_id=? ORDER BY date ASC');
    $state->execute(array(
        $serach_date,
        $emp_id
    ));
    
    $emp_state = $db->prepare('SELECT * FROM employees WHERE id=?');
    $emp_state->execute(array($emp_id));
    $emp_record = $emp_state->fetch();
    $emp = new Employee($emp_record['id'], $emp_record['last_hurigana'], $emp_record['first_hurigana'], $emp_record['last_name'], $emp_record['first_name'], $emp_record['emp_user_name'], $emp_record['emp_delete_flag']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body link="#337ab7" vlink="#337ab7">
    <header>
        <a href="./show.php?id=<?php echo $emp->getId(); ?>">戻る</a>
    </header>
    <h1><?php echo $emp->getName(); ?>さんの出退勤履歴</h1>
    <form action="" method="POST">
        <input type="hidden" name="id" value="<?php echo $emp->getId(); ?>">
        <input type="month" name="y_m" value="<?php echo $serach_date; ?>" required>
        <input type="submit" value="検索">
    </form>
    <div>
        <table border="1">
            <thead>
                <tr>
                    <th colspan="3">2019年03月</th>
                </tr>
                <tr>
                    <th>日付</th>
                    <th>打刻時間</th>
                    <th>状態</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($record = $state->fetch()): ?>
                    <tr>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['time']; ?></td>
                        <td><?php echo $record['status'] == 0 ? '出勤' : '退勤'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">社員名：<?php echo $emp->getName(); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <form action="./csv.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $emp->getId(); ?>">
        <input type="hidden" name="y_m" value="<?php echo $serach_date; ?>">
        <input type="submit" value="CSVダウンロード">
    </form>
</body>
</html>