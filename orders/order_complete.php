<?php
session_start();
session_regenerate_id(true);
require_once '../common/function.php';// for user-defined function

$cart = $_SESSION['cart'];
unset($_SESSION['cart']);

$session = sanitize($_SESSION); // HTMLエスケープ
foreach ($cart as $key =>$val) {
    $cart[$key] = sanitize($val);
}

try {
    $db = dbConnect();
    $db->beginTransaction();

    // 商品登録
    $user_id = intval($session['user_id']);
    $address = $session['address'];
    $sum_price = intval($session['sum_price']);
    $sql = "INSERT INTO orders(id, user_id, address, sum_price, status, payment)VALUES(NULL, :user_id, :address, :sum_price, 1, 1)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':sum_price', $sum_price, PDO::PARAM_INT);
    $stmt->execute();

    // 直前にinsertしたIDを取得
    $sql = "SELECT LAST_INSERT_ID()";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // order_detailsテーブルへ登録
    $order_id = $result['LAST_INSERT_ID()'];
    foreach ($cart as $val) {
        $product_id = intval($val['id']);
        $amount = intval($val['amount']);
        $sql = "INSERT INTO order_details(id, order_id, product_id, amount)VALUES(NULL, :order_id, :product_id, :amount)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->execute();
    }
    $db->commit();
    unset($_SESSION['sum_price']);
    unset($_SESSION['address']);
} catch (PDOException $e) {
    $db->rollBack();
    echo "接続失敗:" .$e->getMessage(). "\n";
} finally {
    $db = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>カート一覧</title>
</head>
<body>
    <h1>注文確定</h1>
    <p>注文を受付けました！</p>
    <a href="../products/product_list.php">商品一覧へ</a><br />
</body>
</html>
