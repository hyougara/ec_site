<?php
session_start();
session_regenerate_id(true);
require_once '../common/function.php';// for user-defined function

$session = sanitize($_SESSION);// HTML escape
$email = $session['email'];
$pass = $session['pass'];

try {
    $db = dbConnect();

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $result) {
        $_SESSION['error'] = 'メールかパスワードが間違っています。１';
        header('Location: login.php');
    } elseif ($result && password_verify($pass, $result['password'])) {
        $_SESSION['auth'] = true;
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['user_address'] = $result['address'];
        unset($_SESSION['pass']);
        header('Location: ../products/product_list.php');
    } else {
        $_SESSION['error'] = 'メールかパスワードが間違っています。';
        header('Location: login.php');
    }
} catch (PDOException $e) {
    echo "接続失敗:" .$e->getMessage(). "\n";
} finally {
    $db = null;
}
