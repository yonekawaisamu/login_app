<?php
require('dbconnect.php');
$error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POSTでuser_name,statusが正しく送られていたらtrue？
    if (isset($_POST['user_name']) && isset($_POST['status'])) {
        $name = $_POST['user_name'];
        $status = $_POST['status'];
        $flag = 0;
        setcookie('status', '', time() - 3600, '/');
        setcookie('status', $status);
        $_COOKIE['status'] = $status;
        $emp_state = $db->prepare('SELECT * FROM employees WHERE emp_user_name=? AND emp_delete_flag=?');
        $emp_state->execute(array(
            $name,
            $flag
        ));
        // 社員が存在したらtrue
        if ($emp = $emp_state->fetch()) {
            switch ($status) {
                case 1: //statusが退勤の場合
                    $search_in_time = $db->prepare('SELECT * FROM time_record WHERE employee_id=? AND date=? AND status="0"');
                    $search_in_time->execute(array(
                        $emp['id'],
                        date('Y-m-d'),
                    ));
                    $in_record = $search_in_time->fetch();
                    $search_out_time = $db->prepare('SELECT * FROM time_record WHERE employee_id=? AND date=? AND status="1"');
                    $search_out_time->execute(array(
                        $emp['id'],
                        date('Y-m-d'),
                    ));
                    $out_record = $search_out_time->fetch();
                    if ($in_record == false) {
                        $error = 'code3';
                    } elseif ($out_record != false) {
                        $error = 'code4';
                    } else {
                        $add_state = $db->prepare('INSERT INTO time_record SET employee_id=?, date=?, time=?, status=?');
                        $add_state->execute(array(
                            $emp['id'],
                            date('Y-m-d'),
                            date('H:i:s'),
                            $status
                        ));
                    }
                    break;
                case 0: //statusが出勤の場合
                    $time = $db->prepare('SELECT * FROM time_record WHERE employee_id=? AND date=? AND status=?');
                    $time->execute(array(
                        $emp['id'],
                        date('Y-m-d'),
                        $status
                    ));
                    if (!$record = $time->fetch()) {
                        $add_state = $db->prepare('INSERT INTO time_record SET employee_id=?, date=?, time=?, status=?');
                        $add_state->execute(array(
                            $emp['id'],
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
    <meta charset="utf-8">
    <title>出退勤打刻ページ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./css/time_record.css">
</head>
<body link="#337ab7" vlink="#337ab7">
    <header>
        <a href="/login_app/admin/sign_in.php">管理者ログイン</a>
        <a href="/login_app/admin/sign_up.php">管理者登録</a>
    </header>

    <div class="wrapper">
    <div class="left">
        <div>
            <h1 id="time"></h1>
        <div>
        <div>
            <form action="" method="POST">
                <div>
                    <?php if (isset($_COOKIE['status'])): ?>
                        <input type="radio" class="radio" name="status" value="0" <?php echo $_COOKIE['status'] == 0 ? 'checked' : ''; ?>>出勤
                        <input type="radio" class="radio" name="status" value="1" <?php echo $_COOKIE['status'] == 1 ? 'checked' : ''; ?>>退勤
                    <?php else: ?>
                        <input type="radio" class="radio" name="status" value="0" checked>出勤
                        <input type="radio" class="radio" name="status" value="1">退勤
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
    </div>

    <div class="right">
        <div class="time_records">
            <table>
                <?php while ($t = $time_records->fetch()): ?>
                    <?php $status = $t['status'] == 0 ? '出勤' : '退勤' ?>
                    <tr>
                        <td><?php echo $t['last_name'] . ' ' . $t['first_name']; ?></td>
                        <td><?php echo $t['date']. ' ' . $t['time']; ?></td>
                        <td id="status" class="in"><?php echo $status; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
       </div>
    </div>
    </div>

    <script>
        let today          = new Date();
        let number         = today.getDay();
        let jp_day_of_week = ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"];
        
        time(jp_day_of_week[number]);
        function time(day_of_week){
            let now = new Date();
            document.getElementById("time").innerHTML = now.toLocaleString() + ' ' + day_of_week;
        }
        setInterval('time(jp_day_of_week[number])',1000);
    </script>

    <?php if ($error == 'code1'): ?>
        <script>alert('ユーザー名に一致する社員が存在しません');</script>
    <?php elseif ($error == 'code2'): ?>
        <script>alert('本日は既に「出勤」を打刻済みです');</script>
    <?php elseif ($error == 'code3'): ?>
        <script>alert('本日の「出勤」を打刻していません');</script>
    <?php elseif ($error == 'code4'): ?>
        <script>alert('本日は既に「退勤」を打刻済みです');</script>
    <?php endif; ?>
</body>
</html>