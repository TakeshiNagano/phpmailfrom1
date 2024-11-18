<?php

define('ADNAME','神姫bizプロデュース株式会社');
define('ADMINMAIL','nagano@suimu.net');
define('ADMINMAILTITLE','お問い合わせがありました｜神姫bizプロデュース株式会社');//管理者へ送られるメール題名
define('REPLYMAILTITLE','お問い合わせありがとうございます');//返信メール題名
define('CONFTABLE', 1); //0=div 1=table 確認画面の問い合わせ内容表示方法
define('REPLYMAILCONTENT', 1); // 0=問い合わせ返信メールで問い合わせ内容非表示 1=問い合わせ返信メールで問い合わせ内容表示
//以下gmailで送信、SMTPサーバを使って送信など
define('SMTP', false); //smtpを使って送信の場合true、通常false
define('MAILHOST', 'ham1001.secure.ne.jp'); //cpi smtp
define('SMTPAUTH', true);
define('SMTPUSER', 'nagano@suimu.net');
define('SMTPPASW', '');
define('SMTPSEC', 'ssl'); //'tls','ssl',false
define('SMTPPORT', 465); //cpi 465


$mailhead = <<< EOF
このメールは自動送信でお送りしています。
WEBサイトのお問い合わせより送信がありました。

EOF;
define('MAILHEAD', $mailhead);

$reply = <<< EOF

神姫bizプロデュース株式会社に
お問い合わせをいただき、誠にありがとうございます。
担当者より、あらためてご連絡させていただきますので、
今しばらくお待ちください。

EOF;
define('REPLYMAIL', $reply);

$mailfoot = <<< EOF


=======================================
神姫bizプロデュース株式会社


=======================================
EOF;
define('MAILFOOT', $mailfoot);