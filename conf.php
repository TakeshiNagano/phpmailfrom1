<?php

define('ADNAME','株式会社サンライズグランドフーズ');
define('ADMINMAIL','sunrise-gf@sunrise-com.co.jp');
define('ADMINMAILTITLE','お問い合わせがありました｜株式会社サンライズグランドフーズ');//管理者へ送られるメール題名
define('REPLYMAILTITLE','お問い合わせありがとうございます');//返信メール題名
define('CONFTABLE', 1); //0=div 1=table 確認画面の問い合わせ内容表示方法
define('REPLYMAILCONTENT', 1); // 0=問い合わせ返信メールで問い合わせ内容非表示 1=問い合わせ返信メールで問い合わせ内容表示
//以下gmailで送信、SMTPサーバを使って送信など
define('SMTP', false); //smtpを使って送信の場合true、通常false
define('MAILHOST', 'ham1001.secure.ne.jp'); //cpi smtp
define('SMTPAUTH', true);
define('SMTPUSER', 'sunrise-gf@sunrise-com.co.jp');
define('SMTPPASW', '');
define('SMTPSEC', 'ssl'); //'tls','ssl',false
define('SMTPPORT', 465); //cpi 465


$mailhead = <<< EOF
このメールは自動送信でお送りしています。
WEBサイトのお問い合わせより送信がありました。

EOF;
define('MAILHEAD', $mailhead);

$reply = <<< EOF

株式会社サンライズグランドフーズに
お問い合わせをいただき、誠にありがとうございます。
担当者より、あらためてご連絡させていただきますので、
今しばらくお待ちください。

EOF;
define('REPLYMAIL', $reply);

$mailfoot = <<< EOF


=======================================
株式会社サンライズグランドフーズ

〒438-0804　静岡県磐田市加茂146-1
TEL：0538-36-2511　FAX：0538-36-2201 
Mail：sunrise-gf@sunrise-com.co.jp
https://sunrise-gf.jp/
=======================================
EOF;
define('MAILFOOT', $mailfoot);