<?php
require('dbconnect.php');
$error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POSTでuser_name,statusが正しく送られているか？
    if (isset($_POST['user_name']) && isset($_POST['status'])) {
        $name = $_POST['user_name'];
        $status = $_POST['status'];
        setcookie('status', '', time() - 3600, '/');
        setcookie('status', $status);
        $_COOKIE['status'] = $status;

        $flag = 0;
        $emp = $db->prepare('SELECT * FROM employees WHERE emp_user_name=? AND emp_delete_flag=?');
        $emp->execute(array(
            $name,
            $flag
        ));

        // 社員が存在するか？
        if ($user = $emp->fetch()) {
            switch ($status) {
                case 1: //statusが退勤の場合
                    $search_in_time = $db->prepare('SELECT * FROM time_record WHERE employee_id=? AND date=? AND status="0"');
                    $search_in_time->execute(array(
                        $user['id'],
                        date('Y-m-d'),
                    ));
                    $in_record = $search_in_time->fetch();

                    $search_out_time = $db->prepare('SELECT * FROM time_record WHERE employee_id=? AND date=? AND status="1"');
                    $search_out_time->execute(array(
                        $user['id'],
                        date('Y-m-d'),
                    ));
                    $out_record = $search_out_time->fetch();

                    if ($in_record == false) {
                        $error = 'code3';
                    } elseif ($out_record != false) {
                        $error = 'code4';
                    } else {
                        $state = $db->prepare('INSERT INTO time_record SET employee_id=?, date=?, time=?, status=?');
                        $state->execute(array(
                            $user['id'],
                            date('Y-m-d'),
                            date('H:i:s'),
                            $status
                        ));
                    }
                    break;

                case 0: //statusが出勤の場合
                    $time = $db->prepare('SELECT * FROM time_record WHERE employee_id=? AND date=? AND status=?');
                    $time->execute(array(
                        $user['id'],
                        date('Y-m-d'),
                        $status
                    ));

                    if (!$record = $time->fetch()) {
                        $state = $db->prepare('INSERT INTO time_record SET employee_id=?, date=?, time=?, status=?');
                        $state->execute(array(
                            $user['id'],
                            date('Y-m-d'),
                            date('H:i:s'),
                            $status
                        ));
                    } else {
                        $error = 'code2';
                    }
                    break;
            }   
        } else {
            $error = 'code1';
        }
    }
}

$time_records = $db->query('SELECT * FROM time_record, employees WHERE time_record.employee_id=employees.id ORDER BY date DESC, time DESC');

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>TOP</title>
    <link rel="stylesheet" type="text/css" href="./css/time_record.css">
</head>
<body>
    <header>
        <a href="/login_app/admin/sign_in.php">管理者ログイン</a>
        <a href="/login_app/admin/sign_up.php">管理者登録</a>
    </header>

    <?php if ($error == 'code1'): ?>
        <script>alert('ユーザー名に一致する社員が存在しません');</script>
    <?php elseif ($error == 'code2'): ?>
        <script>alert('本日は既に「出勤」を打刻済みです');</script>
    <?php elseif ($error == 'code3'): ?>
        <script>alert('本日の「出勤」を打刻していません');</script>
    <?php elseif ($error == 'code4'): ?>
        <script>alert('本日は既に「退勤」を打刻済みです');</script>
    <?php endif; ?>

    <main>
        <h1 id="time"></h1>
        <div>
            <script>
                time();
                function time(){
                    var now = new Date();
                    document.getElementById("time").innerHTML = now.toLocaleString();
                }
                setInterval('time()',1000);
            </script>

            <form action="" method="POST">
                <div>
                    <?php if (isset($_COOKIE['status'])): ?>
                        <input type="radio" name="status" value="0" <?php echo $_COOKIE['status'] == 0 ? 'checked' : ''; ?>>出勤
                        <input type="radio" name="status" value="1" <?php echo $_COOKIE['status'] == 1 ? 'checked' : ''; ?>>退勤
                    <?php else: ?>
                        <input type="radio" name="status" value="0" checked>出勤
                        <input type="radio" name="status" value="1">退勤
                    <?php endif; ?>
                </div>

                <div>
                    <input type="text" name="user_name" required id="user_name" placeholder="ユーザー名" autocomplete="off" autofocus>
                </div>
                <div class="button">
                    <button type="submit">出勤打刻</button>
                </div>
            </form>
        </div>
    </main>

    <aside>
        <div class="time_records">
            <ul>
                <?php while ($t = $time_records->fetch()): ?>
                    <?php $status = $t['status'] == 0 ? '出勤' : '退勤' ?>
                    <li><?php echo $t['last_name'] . ' ' . $t['first_name'] . ' ' . $t['date'] . ' ' . $t['time'] . ' ' . $status; ?></li>
                <?php endwhile; ?>
            </ul>
       </div>
    </aside>
</body>
</html>