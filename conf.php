<?php

define('ADNAME','株式会社ビズプロ');
define('ADMINMAIL','t-murakami@shinkibizpro.co.jp');
define('ADMINMAILTITLE','WEBサイトからのお問い合わせがあります');
define('REPLYMAILTITLE','お問い合わせありがとうございます。');


$mailhead = <<< EOF
このメールは自動送信でお送りしています。
WEBサイトのお問い合わせより送信がありました。



EOF;
define('MAILHEAD', $mailhead);

$reply = <<< EOF
この度は、株式会社ビズプロにお問い合わせをいただき、誠にありがとうございます。
担当者より、あらためてご連絡させていただきますので、
今しばらくお待ちください。



EOF;
define('REPLYMAIL', $reply);

$mailfoot = <<< EOF

----------------------------------------------------------
株式会社ビズプロ

----------------------------------------------------------
EOF;

define('MAILFOOT', $mailfoot);