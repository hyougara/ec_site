<?php
session_start();
session_regenerate_id(true);
require_once '../common/function.php';// for user-defined function

$order_data = $_SESSION['cart'];
$errors = [];

try {
    $db = dbConnect();
    foreach ($order_data as $val) {
        $sql = "SELECT * FROM products WHERE id = {$val['id']}";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // DBから取得したデータに数量データを加える
        $result['amount'] = $val['amount'];
        $compiled_result[] = $result;
    }
} catch (PDOException $e) {
    echo "接続失敗:" .$e->getMessage(). "\n";
} finally {
    $db = null;
}

// 配送先のバリデーション
if (isset($_POST['fix_order'])) {
    $post = sanitize($_POST);// HTML escape
    if ($post['address'] === '') {
        $errors['address'] = "配送先を入力してください。";
    }

    if (empty($errors)) {
        $_SESSION['address'] = $post['address'];
        //$_SESSION['cart'][0][id] = "<h1>aaa</h1>";
        header('Location: order_complete.php');
    }
}
?>

<!DOCTYPE html>
<html>
<body>
    <h1>注文確認画面</h1>
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
        </tr>
        <?php
        foreach($compiled_result as $row){
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
            <td><?php echo $row['amount']; ?></td>
        </tr>
        <?php
        }
        ?>
    </table><br />
    <h3>合計金額：¥<?php echo $_SESSION['sum_price']; ?></h3>
    <form action="" method="POST">
        <label>配送先</label>
        <input type="text" name="address" style="width:200px;" value="<?php echo $_SESSION['user_address']; ?>"><br />
        <button type="submit" name="fix_order" style="margin-top:10px;">注文を確定する</button>
    </form>
    <a href="../products/product_list.php">戻る</a><br />
    <a href="../auth/logout.php">ログアウト</a>
</body>
</html>
