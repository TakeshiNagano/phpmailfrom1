<?php
ini_set('display_errors', 'Off');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require('vendor/autoload.php');

use Paquettg\HtmlParser\HtmlParser;

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
	$dom = new HtmlParser();
	$dom->loadFromFile('top.html');
}else{
	$dom = new HtmlParser();
	$dom->loadFromFile('suspend.html');
}
print $dom;



