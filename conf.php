<?php

define('ADNAME','株式会社');
define('ADMINMAIL','test@suimu.net');
define('ADMINMAILTITLE','お問い合わせ');//管理者へ送られるメール題名
define('REPLYMAILTITLE','お問い合わせありがとうございます。');//返信メール題名
define('CONFTABLE', 1); //0=div 1=table 確認画面の問い合わせ内容表示方法
define('REPLYMAILCONTENT', 1); // 0=問い合わせ返信メールで問い合わせ内容非表示 1=問い合わせ返信メールで問い合わせ内容表示

$mailhead = <<< EOF
メール前半
メール前半

EOF;
define('MAILHEAD', $mailhead);

$reply = <<< EOF
返信メール
返信メール

EOF;
define('REPLYMAIL', $reply);

$mailfoot = <<< EOF

メール後半
メール署名
EOF;
define('MAILFOOT', $mailfoot);