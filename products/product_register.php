<?php
session_start();
session_regenerate_id(true);

require_once '../common/function.php';// for user-defined function

$accept_file_type = "image/jpeg, image/png, image/gif, image/bmp";

if (! empty($_POST)) {
    $name = $_POST['name'];
    $price = intval($_POST['price']);
    $intro = $_POST['intro'];
} elseif (! empty($_SESSION)) {
    $name = $_SESSION['name'];
    $price = intval($_SESSION['price']);
    $intro = $_SESSION['intro'];
}

$_SESSION = array();
$errors = array();

if(isset($_POST['submit'])){
    $post = sanitize($_POST);// HTML escape
    $img_file_name = e($_FILES['image_file']['name']);
    $img_file_size = $_FILES['image_file']['size'];
    $uploads_dir = '/var/www/html/ec_site/img';

    if ($post['name'] === '') {
        $errors['name'] = "氏名が入力されていません。";
    }

    if($post['price'] === '') {
        $errors['price'] = "値段が入力されていません。";
    } elseif ($post['price'] <= 0) {
        $errors['price'] = "値段が0円以下になっています。";
    }

    if($post['intro'] === '') {
        $errors['intro'] = "紹介文が入力されていません。";
    }

    if($img_file_size > 20000000) {
        $errors['file_size'] = '画像データは2MB以内を使用してください';
    }

    if(empty($errors)){
        $_SESSION['name'] = $post['name'];
        $_SESSION['price'] = $post['price'];
        $_SESSION['intro'] = $post['intro'];
        $_SESSION['image'] = $img_file_name;
        if ($img_file_size > 0) {
            move_uploaded_file($_FILES['image_file']['tmp_name'] , "$uploads_dir/$img_file_name" );
        }
        header('Location: product_confirm.php');
    }
}
?>

<!DOCTYPE html>
<html>
<body>
    <h1>商品登録</h1>
    <?php
        echo "<ul>";
        foreach ($errors as $message) {
            echo "<li>";
            echo $message;
            echo "</li>";
        }
        echo "</ul>";
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        名前<br>
        <input type="text" name="name" value="<?php
            if (isset($name)) { echo $name; } ?>"><br><br>
        値段<br>
        <input type="number" name="price" value="<?php
        if (isset($price)) { echo intval($price); }?>"><br><br>
        紹介文<br>
        <textarea name="intro"><?php if (isset($intro)) { echo $intro; } ?></textarea><br />
        <input type="file" name="image_file" style="margin:20px 0px" accept="<?php echo $accept_file_type; ?>"><br />
        <input type="submit" value="確認" name="submit">
    </form>
</body>
</html>
