<?php

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


if(!$suspend){
	$dom = HtmlDomParser::file_get_html('top.html');
}else{
	$dom = HtmlDomParser::file_get_html('suspend.html');
}
print $dom;




