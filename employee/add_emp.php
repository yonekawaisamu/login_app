<?php
session_start();
require('emp_validations.php');

if (!isset($_SESSION['id'])) {
    header('Location: /login_app/admin/sign_in.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();
    
    $error = last_first_validation($_POST['last_name'], $_POST['first_name']);
    if (isset($error)) {
        $errors['last_first'] = $error;
    }
    
    $error = emp_user_name_validation($_POST['emp_user_name']);
    if (isset($error)) {
        $errors['emp_user_name'] = $error;
    }
    
    if (empty($errors)) {
        $_SESSION['join'] = $_POST;
        header('Location: check_add_emp.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>社員登録</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/emp_form.css">
</head>
<body>
    <header>
        <a href="/login_app/admin/index.php">管理者トップ画面</a>
    </header>
    <main>
        <h1>社員登録</h1>
        <?php if (!empty($errors)): ?>
            <ul class="validation_error">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="" method="POST">
            <div>
                <label for="last_name">お名前:</label>
                <input type="text" name="last_name" maxlength="50" placeholder="姓" class="name" id="last_name" required> 
                <input type="text" name="first_name" maxlength="50" placeholder="名" class="name" required>
            </div>
            <div>
                <label for="emp_user_name">ユーザー名:</label>
                <input type="text" name="emp_user_name" maxlength="50" id="emp_user_name" required>
            </div>
            <div class="button">
                <button type="submit">確認</button>
            </div>
        </form>
    </main>
    <footer>

    </footer>
</body>
</html>