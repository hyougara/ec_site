<?php
    session_start();
    session_regenerate_id(true);
    require_once '../common/function.php';// for user-defined function

    if (! isset($_SESSION['auth'])) {
        $_SESSION['not_login'] = 'ログインしてください！';
        header('Location: login.php');
    }

    // ログアウト処理
    $_SESSION = array();

    if (isset($_COOKIE['PHPSESSID'])) {
        setcookie('PHPSESSID', '', time()-42000, '/');
    }
    session_destroy();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ログアウト</title>
  </head>
  <body>
  <h1>ログアウトしました</h1>
  <a href="login.php">ログイン画面へ</a>
  </body>
</html>
