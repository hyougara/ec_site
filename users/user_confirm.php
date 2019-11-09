<?php
    session_start();
    session_regenerate_id(true);
    require_once '../common/function.php';// for user-defined function

    //var_dump($_SESSION);
    //exit();
    //ログイン確認
    //if (! isset($_SESSION['auth'])) {
    //    $_SESSION['not_login'] = 'ログインしてください！';
    //    header('Location: login.php');
    //}

    if(isset($_POST['submit'])){
        $session = sanitize($_SESSION);// HTML escape

        $name = $session['name'];
        $email = $session['email'];
        $pass = $_SESSION['pass'];
        $address = $session['address'];

        try {
            $db = dbConnect();

            $sql = "INSERT INTO users(id, name, email, address, password)VALUES(NULL, :name, :email, :address, :pass)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();

            $db = null;

            //セッションのパスワードを消去
            unset($_SESSION['pass']);

            header('Location: user_complete.php');
        } catch (PDOException $e) {
            echo "接続失敗:" .$e->getMessage(). "\n";
        }
    }
?>

<!DOCTYPE html>
<html>
  <body>
    <h1>ユーザー登録(確認)</h1>
    <form action="" method="post">
      名前:
      <?php echo $_SESSION["name"]; ?><br>
      email:
      <?php echo $_SESSION["email"]; ?><br>
      住所:
      <?php echo $_SESSION["address"]; ?><br>
      パスワードは表示しません。<br>
      <input type="button" onclick="history.back()" value="戻る">
      <input type="submit" value="送信" name="submit">
    </form>
  </body>
</html>
