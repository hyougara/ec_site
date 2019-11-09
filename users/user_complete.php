<?php
session_start();
session_regenerate_id(true);

////ログイン確認
//if (! isset($_SESSION['auth'])) {
//    $_SESSION['not_login'] = 'ログインしてください！';
//    header('Location: login.php');
//}
?>

<!DOCTYPE html>
<html>
  <body>
    <h1>管理者登録（編集）完了</h1>
    名前:
    <?php echo $_SESSION["name"]; ?><br>
    email:
    <?php echo $_SESSION["email"]; ?><br>
    パスワード:非表示<br />
    <a href="user_register.php">ユーザー登録画面へ</a><br />
    <?php $_SESSION = array(); ?>
  </body>
</html>
