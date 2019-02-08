<?php
require('dbconnect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $state = $admindb->prepare('SELECT * FROM admin WHERE user_name=?');
  $state->execute(array(
    $_POST['user_name']
  ));
  $user = $state->fetch();

  $errors = array();
  if ($user && password_verify($_POST['password'], $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    header('Location: top.html');
    exit();
  } else {
    $errors['sign_in'] = 'ユーザー名またはパスワードが間違っています';
  }
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>sign_in</title>
    <link rel="stylesheet" type="text/css" href="./css/form.css">
  </head>
  <body>
    <header>
        
    </header>
    <h1>Sign In</h1>

    <?php if (!empty($errors)): ?>
      <ul class="validation_error">
        <?php foreach ($errors as $error): ?>
          <li><?php echo $error; ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form action="" method="post">
      <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="user_name" minlength="6">
      </div>
      <div>
        <label for="pass">Password:</label>
        <input type="password" id="pass" name="password" minlength="8">
      </div>
      <div class="button">
        <button type="submit">Send</button>
      </div>
    </form>
  </body>
</html>