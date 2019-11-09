<?php
session_start();
session_regenerate_id(true);
require_once '../common/function.php';// for user-defined function

// カートに追加機能
if (isset($_POST['add_cart'])) {
    // カートが空の場合の処理
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
        // 商品IDと数量をセッションに代入
        $ary = array('id' => $_POST['pro_id'], 'amount' => $_POST['amount']);
        $_SESSION['cart'][] = $ary;
        header('Location: product_list.php');
    } else {
        // カートに同じ商品が入っているか確認
        foreach ($_SESSION['cart'] as $key => $val) {
            if ($val['id'] == $_POST['pro_id']) {
                $_SESSION['cart'][$key]['amount'] += $_POST['amount'];
                header('Location: product_list.php');
                exit();
            }
        }
        $ary = array('id' => $_POST['pro_id'], 'amount' => $_POST['amount']);
        $_SESSION['cart'][] = $ary;
        header('Location: product_list.php');
    }
}

$db = dbConnect();
$sql = "SELECT * FROM products WHERE id = {$_POST['id']}";
//var_dump($sql);
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
//echo '<pre>';
//var_dump($result);
//echo '</pre>';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>商品詳細</title>
</head>
<body>
    <h1>商品詳細</h1>
    <p>商品名：<?php echo $result['name']; ?></p>
    <p>お値段：¥<?php echo $result['price']; ?></p>
    <p>紹介文</P>
    <?php echo $result['introduction']; ?><br />
    <?php
    if ($result['image']) {
        echo '<img src="../img/'.$result['image'].'">';
    } else {
        echo '<img src="../img/no_image.png">';
    }
    ?>
    <form action="" method="POST" style="margin:20px;">
        <input type="hidden" name="pro_id" value="<?php echo $result['id']; ?>">
        <span>数量：</span><input type="number" name="amount" value="1" min="1">
        <input type="submit" name="add_cart" value="カートに入れる">
    </form>
    <a href="../products/product_list.php">戻る</a><br />
</body>
</html>
