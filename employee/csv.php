<?php
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=test.csv');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 jul 1997 05:00:00 GMT');
require('dbconnect.php');
require('EmployeeClass.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csv = null;
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
    $emp = new Employee($emp_record['id'], $emp_record['last_name'], $emp_record['first_name'], $emp_record['emp_user_name'], $emp_record['emp_delete_flag']);

    $datas = $state->fetchAll();
    $csv = '"氏名","日付","打刻時間","状態"' . "\n";

    foreach ($datas as $data) {
        $status = $data['status'] == 0 ? '出勤' : '退勤';
        $csv .= '"' . $emp->getName() . '","' . $data['date'] . '","' . $data['time'] . '","' . $status . '"' . "\n";
    }

    echo $csv;
    return;
}