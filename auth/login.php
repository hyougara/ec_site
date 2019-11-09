<?php
session_start();
session_regenerate_id(true);
require_once '../common/function.php';// for user-defined function

if (isset($_POST['submit'])) {
    $_SESSION = array();
    $post = sanitize($_POST);// HTML escape

    if ($_POST['email'] === '') {
        $errors['email'] = 'メールアドレスが入力されていません。';
    }

    if ($_POST['pass'] === '') {
        $errors['pass'] = 'パスワードが入力されていません。';
    }

    if (empty($errors)) {
        $_SESSION['email'] = $post['email'];
        $_SESSION['pass'] = $post['pass'];
        header('Location: login_check.php');
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>ユーザーログイン</title>
  </head>
  <body>
    <h1>ユーザーログイン</h1>
    <?php
    if (isset($_SESSION['not_login'])) {
      echo "<p style='color:red'>".$_SESSION['not_login']."</p>";
      unset($_SESSION['not_login']);
    }

    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }

    if (isset($errors)) {
      echo "<ul>";
      foreach ($errors as $message) {
          echo "<li style='color:red'>";
          echo $message;
          echo "</li>";
      }
      echo "</ul>";
      unset($errors);
    }
    ?>
    <form action="" method="POST" style="margin-bottom:20px;">
      email<br>
      <input type="text" name="email" value="<?php
      if (isset($post['email'])) { echo $post['email']; }?>"><br><br>
      パスワード<br>
      <input type="password" name="pass"><br><br>
      <input type="submit" value="ログイン" name="submit">
    </form>
    <a href="../users/user_register.php">ユーザー新規登録</a>
  </body>
</html>
