<?php
session_start();
require('dbconnect.php');
require('EmployeeClass.php');

if (!isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csv = null;
    $serach_date = filter_input(INPUT_POST, 'y_m');
    $emp_id = filter_input(INPUT_POST, 'id');
    $state = $db->prepare('SELECT * FROM time_record WHERE DATE_FORMAT(date, "%Y-%m")=? AND employee_id=? ORDER BY date ASC');
    $state->execute(array(
        $serach_date,
        $emp_id
    ));
    $datas = $state->fetchAll();
    
    $emp_state = $db->prepare('SELECT * FROM employees WHERE id=?');
    $emp_state->execute(array($emp_id));
    $emp_record = $emp_state->fetch();
    $emp = new Employee($emp_record['id'], $emp_record['last_hurigana'], $emp_record['first_hurigana'], $emp_record['last_name'], $emp_record['first_name'], $emp_record['emp_user_name'], $emp_record['emp_delete_flag']);
    $csv = '"氏名","日付","打刻時間","状態"' . "\n";
    $csv_name = $emp->getLast() . $emp->getFirst() . '-' . '出退勤'. '-' . $serach_date . '.' . 'csv';
    
    function add_double($x) {
        return '"' . $x . '"';
    }

    foreach ($datas as $data) {
        $status = $data['status'] == 0 ? '出勤' : '退勤';
        $csv .= add_double($emp->getName()) . ',' . add_double($data['date']) . ',' . add_double($data['time']) . ',' . $status . "\n";
    }

    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=$csv_name");
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 jul 1997 05:00:00 GMT');
    echo mb_convert_encoding($csv, "SJIS", "UTF-8");
    return;
}
?>