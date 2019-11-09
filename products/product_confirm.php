<?php
session_start();
session_regenerate_id(true);
require_once '../common/function.php';// for user-defined function

if(isset($_POST['submit'])){
$session = sanitize($_SESSION);// HTML escape

$name = $session['name'];
$price = intval($session['price']);
$intro = $session['intro'];
$image = $session['image'];

try {
    $db = dbConnect();

    $sql = "INSERT INTO products(id, name, price, introduction, image)VALUES(NULL, :name, :price, :intro, :image)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->bindParam(':intro', $intro, PDO::PARAM_STR);
    $stmt->bindParam(':image', $image, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: product_complete.php');
} catch (PDOException $e) {
    echo "接続失敗:" .$e->getMessage(). "\n";
} finally {
    $db = null;
}
}

if(isset($_POST['back'])){
    echo 'back!';
    unlink("../img/".$_SESSION['image']);
    header('Location: product_register.php');
}
?>

<!DOCTYPE html>
<html>
  <body>
    <h1>商品登録(確認)</h1>
    <form action="" method="POST">
      名前:
      <?php echo $_SESSION["name"]; ?><br>
      値段:
      <?php echo $_SESSION["price"]; ?><br>
      紹介文:
      <?php echo $_SESSION["intro"]; ?><br>
      画像:<br />
      <?php
      if (empty($_SESSION['image'])) {
          echo '<img src="../img/no_image.png">';
      } else {
          echo '<img src="../img/'.$_SESSION['image'].'">';
      }
      ?><br />
      <input type="hidden" name="name" value="<?php echo $_SESSION['name']; ?>">
      <input type="hidden" name="price" value="<?php echo $_SESSION["price"]; ?>">
      <input type="hidden" name="intro" value="<?php echo $_SESSION["intro"]; ?>">
      <input type="submit" name="back" value="戻る">
      <input type="submit" value="送信" name="submit">
    </form>
  </body>
</html>
