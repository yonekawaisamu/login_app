<?php

function hurigana_validation($last, $first) {
    if (!preg_match('/\A[ぁ-んー]{1,50}\z/u', $last) || !preg_match('/\A[ぁ-ん]{1,50}\z/u', $first)) {
        return 'ふりがなは、ひらがな５０文字以下で入力してください';
    }
}

function last_first_validation($last_name, $first_name) {
    if ($last_name == "" || $first_name == "") {
        return 'お名前を入力してください';
    } elseif (mb_strlen($last_name) > 50 || mb_strlen($first_name) > 50) {
        return 'お名前は５０文字以下で入力してください';
    }
}

function emp_user_name_validation($name) {
    if (!preg_match('/\A[a-z0-9]{5,50}\z/ui', $name)) {
        return 'ユーザー名は5文字以上50文字以下の英数字を入力してください';
    } else {
        require('dbconnect.php');
        $user = $db->prepare('SELECT * FROM employees WHERE emp_user_name=?');
        $user->execute(array(
            $name
        ));
        $check_user = $user->fetch();
        if (!$check_user == false) {
            return 'そのユーザー名使用できません';
        }
    }
}