<?php

define('ADNAME','株式会社桂スチール');
define('ADMINMAIL','toiawase@katsura-steel.co.jp');
define('ADMINMAILTITLE','お問い合わせがありました');//管理者へ送られるメール題名
define('REPLYMAILTITLE','お問い合わせありがとうございます｜株式会社桂スチール');//返信メール題名
define('CONFTABLE', 1); //0=div 1=table 確認画面の問い合わせ内容表示方法
define('REPLYMAILCONTENT', 1); // 0=問い合わせ返信メールで問い合わせ内容非表示 1=問い合わせ返信メールで問い合わせ内容表示
define('SITEURL','https://www.katsura-steel.co.jp/');

function getCc() {
    return ['', '']; // 必要に応じてメールアドレスを設定
}

function getBcc() {
    return []; // 必要に応じてメールアドレスを設定
}

//以下gmailで送信、SMTPサーバを使って送信など
define('SMTP', true); //smtpを使って送信の場合true、通常false
define('MAILHOST', 'c15ide2h.mwprem.net'); //cpi smtp
define('SMTPAUTH', true);
define('SMTPUSER', 'toiawase@katsura-steel.co.jp');
define('SMTPPASW', 'toitoiA1');
define('SMTPSEC', 'tls'); //'tls','ssl',false
define('SMTPPORT', 587); //cpi 465

define('RECAPTCHA_SITE_KEY',  '6LecrekrAAAAAHmcHoMfjtjSH7ExRtScdW3ZYeeh');   // 例: 6Lcxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
define('RECAPTCHA_SECRET_KEY','6LecrekrAAAAALjDx5SNcWCLR2WTimfQK3UuR4OO'); // 例: 6Lcxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
define('RECAPTCHA_MIN_SCORE', 0.5); // スパム閾値（必要に応じて調整）

$mailhead = <<< EOF
このメールは自動送信でお送りしています。
WEBサイトのお問い合わせより送信がありました。

EOF;
define('MAILHEAD', $mailhead);

$reply = <<< EOF

株式会社桂スチールに
お問い合わせをいただき、誠にありがとうございます。
担当者より、あらためてご連絡させていただきますので、
今しばらくお待ちください。

EOF;
define('REPLYMAIL', $reply);

$mailfoot = <<< EOF


=======================================
株式会社桂スチール

【鉄構事業本部】
〒705-0132 岡山県備前市三石200番地
TEL.0869-62-2312 FAX.0869-62-2313

https://www.katsura-steel.co.jp/
=======================================
EOF;
define('MAILFOOT', $mailfoot);