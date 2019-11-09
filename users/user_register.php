<?php
session_start();
session_regenerate_id(true);

require_once '../common/function.php';// for user-defined function

//ログイン確認
//if (! isset($_SESSION['auth'])) {
//    $_SESSION['not_login'] = 'ログインしてください！';
//    header('Location: login.php');
//}

$_SESSION = array();
$errors = array();

if(isset($_POST['submit'])){
    $post = sanitize($_POST);// HTML escape
    var_dump($post['pass']);
    //exit;

    if ($post['name'] === '') {
        $errors['name'] = "氏名が入力されていません。";
    }

    if($post['email'] === '') {
        $errors['mail'] = "メールアドレスが入力されていません。";
    }

    if($post['address'] === '') {
        $errors['address'] = "住所が入力されていません。";
    }

    if($post['pass'] === '') {
        $errors['pass'] = "パスワードが入力されていません。";
    }

    if($post['pass2'] === '') {
        $errors['pass2'] = "パスワード(再入力)が入力されていません。";
    }

    if ($_POST['pass'] !== '' && $_POST['pass2'] !== '' && $_POST['pass'] !== $_POST['pass2']) {
        $errors['pass_wrong'] = 'パスワードが一致していません。';
    } else {
        //var_dump($post['pass']);
        //exit();
        $pass = password_hash($post['pass'], PASSWORD_DEFAULT);
        $_SESSION['pass'] = $pass;
    }

    if(empty($errors)){
        $_SESSION['name'] = $post['name'];
        $_SESSION['email'] = $post['email'];
        $_SESSION['address'] = $post['address'];
        //var_dump($_SESSION);
        //exit();
        header('Location: user_confirm.php');
    }
}
?>

<!DOCTYPE html>
<html>
<body>
    <h1>ユーザー登録</h1>
    <?php
        echo "<ul>";
        foreach ($errors as $message) {
            echo "<li>";
            echo $message;
            echo "</li>";
        }
        echo "</ul>";
    ?>
    <form action="" method="POST">
        名前<br>
        <input type="text" name="name" value="<?php
            if (isset($_POST['name'])){ echo $_POST['name']; }?>"><br><br>
        email<br>
        <input type="text" name="email" value="<?php
        if (isset($_POST['email'])) { echo $_POST['email']; }?>"><br><br>
        住所<br>
        <input type="text" name="address" value="<?php
        if (isset($_POST['address'])) { echo $_POST['address']; }?>"><br><br>
        パスワード<br>
        <input type="password" name="pass"><br><br>
        パスワード(再入力)<br>
        <input type="password" name="pass2"><br><br>
        <input type="submit" value="確認" name="submit">
    </form>
</body>
</html>
