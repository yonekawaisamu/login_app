<?php
require('dbconnect.php');
session_start();

if (!isset($_SESSION['join'])) {
    header('Location: sign_up.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['join'])) {
    $state = $admindb->prepare('INSERT INTO admin SET user_name=?, password=?, created_at=NOW()');
    $state->execute(array(
        $_SESSION['join']['user_name'],
        password_hash($_SESSION['join']['password'], PASSWORD_DEFAULT)
    ));

    unset($_SESSION['join']);

    header('Location: sign_in.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>sign_up確認</title>
    <link rel="stylesheet" type="text/css" href="./css/form.css">
  </head>
  <body>
    <header>
      
    </header>
    <h1>Sign Up Check</h1>

    <form action="" method="post">
      <div>
        Name: <?php echo htmlspecialchars($_SESSION['join']['user_name']) ?>
      </div>
      <div>
        Password: <?php echo 'パスワードは表示されません' ?>
      </div>
      <div class="button">
        <button type="submit">Send</button>
      </div>
    </form>
  </body>
</html>