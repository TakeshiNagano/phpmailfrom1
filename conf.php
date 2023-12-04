<?php

define('ADNAME','山陽自動車教習所');
define('ADMINMAIL','t-murakami@shinkibizpro.co.jp');
define('ADMINMAILTITLE','WEBサイトから仮入所申込みがありました');//管理者へ送られるメール題名
define('REPLYMAILTITLE','仮入所申込ありがとうございます｜山陽自動車教習所');//返信メール題名
define('CONFTABLE', 1); //0=div 1=table 確認画面の問い合わせ内容表示方法
define('REPLYMAILCONTENT', 1); // 0=問い合わせ返信メールで問い合わせ内容非表示 1=問い合わせ返信メールで問い合わせ内容表示
//以下gmailで送信、SMTPサーバを使って送信など
define('SMTP', false); //smtpを使って送信の場合true、通常false
define('MAILHOST', 'ham1001.secure.ne.jp'); //cpi smtp
define('SMTPAUTH', true);
define('SMTPUSER', 'info@a-connectgr.com');
define('SMTPPASW', '');
define('SMTPSEC', 'ssl'); //'tls','ssl',false
define('SMTPPORT', 465); //cpi 465


$mailhead = <<< EOF
このメールは自動送信でお送りしています。
WEBサイトから仮入所申込みより送信がありました。

EOF;
define('MAILHEAD', $mailhead);

$reply = <<< EOF

この度は、山陽自動車教習所お仮入所申込をいただき、誠にありがとうございます。
あらためてご連絡させていただきますので、
今しばらくお待ちください。

EOF;
define('REPLYMAIL', $reply);

$mailfoot = <<< EOF


=======================================
山陽自動車教習所

〒670-0947 兵庫県姫路市北条字川原前986
営業時間　月～金　8:40～20:40 ／ 土日　8:40～17:30 
フリーダイヤル 0120-23-2087
=======================================
EOF;
define('MAILFOOT', $mailfoot);