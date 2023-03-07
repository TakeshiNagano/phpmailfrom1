<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use KubAT\PhpSimple\HtmlDomParser;
use Gregwar\Captcha\CaptchaBuilder;
require('vendor/autoload.php');
session_start();

mb_language("japanese");
mb_internal_encoding("UTF-8")

try{
$mail2 = new PHPMailer(true);
$mail2->isSendmail();
$mail2->isHTML(false);
            //Recipients
$mail2->setFrom('nagano@suimu.net');
$mail2->addAddress('nagano@suimu.net');     // Add a recipient

$mail2->Subject = mb_encode_mimeheader('日本語サブジェクト(sendmail)');
//$mail2->Encoding = '7bit';
$mail2->CharSet ='utf-8';

$mailBody =<<< EOL
日本語のメールのテストです。

改行も問題ないと思います。

sendmailで配信しています。
EOL;

$mail2->Body = $mailBody;
$mail2->send();
} catch (Exception $e) {
	var_dump($e);
}




$dom = HtmlDomParser::file_get_html('contact/index.html');
$form = $dom->find('form', 0);
foreach ($form->find("input[name=docs[]]") as $checkbox){
	var_dump($checkbox->value);
}

foreach ($form->find('.docscheck') as $checkbox){
	var_dump($checkbox->value);
}

foreach ($form->find('checkbox') as $checkbox){
	var_dump($checkbox->value);
}