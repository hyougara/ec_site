<?php
session_start();
session_regenerate_id(true);
require_once '../common/function.php';// for user-defined function

try {
    $db = dbConnect();
    $sql = "SELECT * FROM products";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchall(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "接続失敗:" .$e->getMessage(). "\n";
} finally {
    $db = null;
}
?>

<!DOCTYPE html>
<html>
    <head>
      <meta charset="UTF-8">
      <title>商品一覧</title>
    </head>
    <body>
    <h2>商品一覧ページ</h2>
    <table border="1">
      <tr>
        <th>商品名</th>
        <th>値段</th>
        <th style="width:300px;">紹介文</th>
        <th>詳細</th>
      </tr>
      <?php
      foreach($result as $row){
      ?>
      <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo '¥'.$row['price']; ?></td>
        <td><?php echo $row['introduction']; ?></td>
        <td><form action="product_detail.php" method="POST"><button type="submit" name="id" value="<?php echo $row['id']; ?>">詳しく見る</button></form></td>
      </tr>
      <?php
      }
      ?>
    </table>
    <a href="../cart/cart.php">カートを見る</a><br />
    <a href="../auth/logout.php">ログアウト</a>
    </body>
</html>
