<?php
ini_set('display_errors', 'Off');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require('vendor/autoload.php');

use KubAT\PhpSimple\HtmlDomParser;
use Gregwar\Captcha\CaptchaBuilder;
session_start();
$_SESSION = array();
// ファイルを変数に格納
$filename = 'suspend.txt';
//file_put_contents($filename, 0);
 
// fopenでファイルを開く（'r'は読み込みモードで開く）
$fp = fopen($filename, 'r');
$suspend = 0;

if($fp){
// fgetsでファイルを読み込み、変数に格納
$suspend = fgets($fp);
//Var_dump($suspend);
fclose($fp);
}

$valid_referer = SITEURL; // 許可する正当なリファラーのURLを指定
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

if ($referer && strpos($referer, $valid_referer) === false) {
    // リファラーが正当なURLでない場合、アクセスを拒否
    die('Access denied. Invalid referer.');
}

// GETパラメータを取得してセッションに保存
if (isset($_GET['title'])) {
    $_SESSION['title'] = $_GET['title'];  // 'param'のGETパラメータをセッションに保存
}

if(!$suspend){
	$dom = HtmlDomParser::file_get_html('top.html');
}else{
	$dom = HtmlDomParser::file_get_html('suspend.html');
}

if (isset($_SESSION['title'])) {
    // 例として、$domに値を挿入する
    $dom->find('#qtitle')[0]->innertext = $_SESSION['title'].'お問い合わせ';  // DOM内の特定の場所にセッションの値を挿入
}

print $dom;



