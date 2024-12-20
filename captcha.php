<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
session_start();

// 画像サイズ
$imgWidth = 120;
$imgHeight = 40;

// 背景画像の生成
$image = imagecreatetruecolor($imgWidth, $imgHeight);
$bgColor = imagecolorallocate($image, 255, 255, 255);
imagefilledrectangle($image, 0, 0, $imgWidth, $imgHeight, $bgColor);

// 認証コードの生成
$code = '';
$charSet = '0123456789'; // 使用する文字集合
$len = strlen($charSet) - 1;
for ($i = 0; $i < 5; $i++) {
    $randChar = $charSet[rand(0, $len)];
    $code .= $randChar;
}
$_SESSION['captcha'] = $code;

// 認証コードの描画
$fontFile = __DIR__ .'/OpenSans-Regular.ttf'; // フォントファイルのパス
$fontColor = imagecolorallocate($image, 0, 0, 0);
$fontSize = 15;
$x = (int)($imgWidth / 10); // 明示的にint型にキャスト
$y = (int)($imgHeight / 2 + $fontSize / 2); // 明示的にint型にキャスト
for ($i = 0; $i < 5; $i++) {
    $angle = rand(-15, 15);
    imagettftext($image, $fontSize, $angle, $x, $y, $fontColor, $fontFile, $code[$i]);
    $x += (int)($imgWidth / 5); // 明示的にint型にキャスト
}

// ノイズの描画
$noiseColor = imagecolorallocate($image, 0, 0, 0);
for ($i = 0; $i < 300; $i++) {
    $x = rand(0, $imgWidth);
    $y = rand(0, $imgHeight);
    imagesetpixel($image, $x, $y, $noiseColor);
}

for ($i = 0; $i < 10; $i++) {
    $line_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
    imageline($image, rand(0, $imgWidth), rand(0, $imgHeight), rand(0, $imgWidth), rand(0, $imgHeight), $line_color);
}

// 画像の出力
header('Content-type: image/png');
imagepng($image);

// 画像の解放
imagedestroy($image);
?>
