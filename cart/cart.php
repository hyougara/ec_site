<?php
session_start();
session_regenerate_id(true);
require_once '../common/function.php';// for user-defined function

$compiled_result = array();
$result = array();
$errors = array();

if (empty($_SESSION['cart'])) {
    $errors['cart'] = "カートに商品が入っていません";
} else {
    $cart = $_SESSION['cart'];
    try {
        $db = dbConnect();
        foreach ($cart as $val) {
            $sql = "SELECT * FROM products WHERE id = {$val['id']}";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // DBから取得した商品データに注文数量データを加える
            $result['amount'] = $val['amount'];
            $compiled_result[] = $result;
        }
    } catch (PDOException $e) {
        echo "接続失敗:" .$e->getMessage(). "\n";
    } finally {
        $db = null;
    }
}

// カート内商品削除機能
if (isset($_POST['delete'])) {
    $del_id = $_POST['pro_id'];

    $target = $_SESSION['cart'];
    foreach ($target as $key => $val) {
        if ($val['id'] == $del_id) {
            unset($target[$key]);
            var_dump($target);
        }
    }
    $result = array_values($target);
    $_SESSION['cart'] = $result;
    header('Location: cart.php');
}

// 注文数量変更機能
if (isset($_POST['amount_change'])) {
    $changed_amount = $_POST['amount'];
    $pro_id = $_POST['pro_id'];
    var_dump($changed_amount);
    var_dump($pro_id);
    foreach ($_SESSION['cart'] as $key => $val) {
        if ($val['id'] == $pro_id) {
            $_SESSION['cart'][$key]['amount'] = $changed_amount;
            header('Location: cart.php');
        }
    }
}

// カートを空にする処理
if (isset($_POST['empty_cart'])) {
    unset($_SESSION['cart']);
    header('Location: cart.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>カート一覧</title>
    </head>
    <body>
    <h2>カート内商品</h2>
    <?php
        echo "<ul>";
        foreach ($errors as $message) {
            echo '<li style="color:red;">';
            echo $message;
            echo "</li>";
        }
        echo "</ul>";
    ?>
    <table border="1">
        <tr>
            <th>商品名</th>
            <th>画像</th>
            <th style="width:300px;">紹介文</th>
            <th>値段</th>
            <th>数量</th>
            <th>削除</th>
        </tr>
        <?php
        $sum_price = 0;
        foreach($compiled_result as $row){
                $calc_result = $row['price'] * $row['amount'];
                $sum_price += $calc_result;
        ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td>
            <?php
            if ($row['image']) {
                echo '<img src="../img/'.$row['image'].'" style="width:200px; height:200px;">';
            } else {
                echo '<img src="../img/no_image.png" style="width:200px; height:200px;">';
            }
            ?>
            </td>
            <td><?php echo $row['introduction']; ?></td>
            <td><?php echo '¥'.$row['price']; ?></td>
            <td><form action="" method="POST">
                <input type="hidden" name="pro_id" value="<?php echo $row['id'] ?>">
                <input type="number" name="amount" value="<?php echo $row['amount']; ?>" min="1" style="width:70px;">
                <input type="submit" name="amount_change" value="数量変更">
            </form></td>
            <td><form action="" method="POST">
                <input type="hidden" name="pro_id" value="<?php echo $row['id'] ?>">
                <button type="submit" name="delete">削除する</button>
            </form></td>
        </tr>
        <?php
        }
        $_SESSION['sum_price'] = $sum_price;
        ?>
    </table><br />
    <h3>合計金額：¥<?php echo $sum_price; ?></h3>
    <form action="" method="POST">
        <button type="submit" name="empty_cart">カートを空にする</button>
    </form>
    <?php
    if (empty($errors)) {
    ?>
        <a href="../orders/order_confirm.php">購入手続きへ</a><br />
    <?php
    }
    ?>
    <a href="../products/product_list.php">戻る</a><br />
    <a href="../auth/logout.php">ログアウト</a>
    </body>
</html>
