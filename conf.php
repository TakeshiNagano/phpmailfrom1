<?php

define('ADNAME','株式会社');
define('ADMINMAIL','test@suimu.net');
define('ADMINMAILTITLE','お問い合わせ');
define('REPLYMAILTITLE','お問い合わせありがとうございます。');


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