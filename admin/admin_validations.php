<?php

function user_name_validation($name) {
    if (!preg_match('/\A[a-z0-9]{5,50}\z/ui', $name)) {
        return 'ユーザー名は5文字以上50文字以下の英数字を入力してください';
    } else {
        require('dbconnect.php');
        $user = $db->prepare('SELECT * FROM admin WHERE user_name=?');
        $user->execute(array(
            $name
        ));
        $check_user = $user->fetch();
        if (!$check_user == false) {
            return 'そのユーザー名使用できません';
        }
    }
}

function password_validation($password, $name) {
    if (strpos($password, " ")) {
        return 'パスワードにスペースは入力できません';
    } elseif (strlen($password) < 8) {
        return 'パスワードは8文字以上で入力してください';
    } elseif ($password == $name) {
        return 'ユーザー名とパスワードを同じ値には設定できません';
    }
}

function confirmation_validation($confirmation, $password) {
    if ($password !== $confirmation) {
        return 'パスワードと確認用パスワードが一致しません';
    }
}
