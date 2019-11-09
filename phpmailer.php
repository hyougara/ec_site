<?php
mb_language("japanese");
mb_internal_encoding("UTF-8");

//ソースを全部読み込ませる
//パスは自分がPHPMailerをインストールした場所で
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';
require './PHPMailer-master/src/POP3.php';
require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/OAuth.php';
require './PHPMailer-master/language/phpmailer.lang-ja.php';

// PHPMailerの使用宣言
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//SMTPの設定
$mailer = new PHPMailer();//インスタンス生成
$mailer->IsSMTP();//SMTPを作成
$mailer->Host = 'smtp.gmail.com';//Gmailを使う場合の設定
$mailer->CharSet = 'utf-8';//文字セット
$mailer->SMTPAuth = TRUE;//SMTP認証を有効にする
$mailer->Username = 'XXXXXX@gmail.com'; // Gmailのユーザー名
$mailer->Password = 'XXXXXXXXXXX'; // Gmailのパスワード
$mailer->IsHTML(false);
$mailer->SMTPSecure = 'tls';//SSLも使用可
$mailer->Port = 587;//tlsは587でOK
$mailer->SMTPDebug = 2;//2は詳細デバッグ1は簡易デバッグ本番はコメントアウト

//メール本体
$to = "sample@sample.co.jp";// 送信先
$mailer->From     = 'XXXXXX@gmail.com'; //差出人の設定
$mailer->SetFrom('XXXXXX@gmail.com');
$mailer->FromName = mb_convert_encoding("差し出し人名","UTF-8","AUTO");
$mailer->Subject  = mb_convert_encoding("メールのタイトル","UTF-8","AUTO");
$mailer->Body     = mb_convert_encoding("メール本文","UTF-8","AUTO");
$mailer->AddAddress($to); // To宛先

//送信する
if($mailer->Send()){}
else{
    echo "送信に失敗しました" . $mailer->ErrorInfo;
}
?>
