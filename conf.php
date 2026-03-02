<?php

define('ADNAME', 'こべっこあそびひろば・六甲アイランド');
define('ADMINMAIL', '@shinkibizpro.co.jp');
define('ADMINMAILTITLE', 'お問い合わせがありました');//管理者へ送られるメール題名
define('REPLYMAILTITLE', 'お問い合わせありがとうございます｜こべっこあそびひろば・六甲アイランド');//返信メール題名
define('CONFTABLE', 1); //0=div 1=table 確認画面の問い合わせ内容表示方法
define('REPLYMAILCONTENT', 1); // 0=問い合わせ返信メールで問い合わせ内容非表示 1=問い合わせ返信メールで問い合わせ内容表示
define('SITEURL', 'https://rokko.rsvsys.jp');



function getCc()
{
    return ['', '']; // 必要に応じてメールアドレスを設定
}

function getBcc()
{
    return []; // 必要に応じてメールアドレスを設定
}

//以下gmailで送信、SMTPサーバを使って送信など
define('SMTP', true); //smtpを使って送信の場合true
define('MAILHOST', 'ham1001.secure.ne.jp'); //cpi smtp
define('SMTPAUTH', true);
define('SMTPUSER', 't-murakami@shinkibizpro.co.jp');
define('SMTPPASW', '');

$smtpSecure = 'ssl'; //'tls','ssl',false
if ($smtpSecure === 'false' || $smtpSecure === 'none' || $smtpSecure === '0') {
    $smtpSecure = '';
}
define('SMTPSEC', $smtpSecure);
define('SMTPPORT', 465); //cpi 465

define('RECAPTCHA_SITE_KEY', '');
define('RECAPTCHA_SECRET_KEY', '');

// キーが設定されていない場合は強制的に無効化する
$recaptchaEnabled = (RECAPTCHA_SITE_KEY !== '' && RECAPTCHA_SECRET_KEY !== '');
define('RECAPTCHA_ENABLED', $recaptchaEnabled);

define('RECAPTCHA_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify');
define('RECAPTCHA_ACTION', 'contact_submit');
$recaptchaMinScore = 0.5;
if ($recaptchaMinScore < 0 || $recaptchaMinScore > 1) {
    $recaptchaMinScore = 0.5;
}
define('RECAPTCHA_MIN_SCORE', $recaptchaMinScore);

function loadContactTopDom()
{
    $dom = \KubAT\PhpSimple\HtmlDomParser::file_get_html(__DIR__ . '/top.html');
    if (!$dom) {
        return \KubAT\PhpSimple\HtmlDomParser::file_get_html('top.html');
    }

    $form = $dom->find('#form', 0);
    if ($form) {
        if (RECAPTCHA_ENABLED) {
            $form->attr['data-recaptcha-enabled'] = '1';
            $form->attr['data-recaptcha-site-key'] = RECAPTCHA_SITE_KEY;
            $form->attr['data-recaptcha-action'] = RECAPTCHA_ACTION;
        } else {
            unset($form->attr['data-recaptcha-enabled']);
            unset($form->attr['data-recaptcha-site-key']);
            unset($form->attr['data-recaptcha-action']);
        }
    }

    $recaptchaScript = $dom->find('#recaptcha-api-script', 0);
    if ($recaptchaScript) {
        if (RECAPTCHA_ENABLED) {
            $src = $recaptchaScript->getAttribute('src');
            $recaptchaScript->attr['src'] = str_replace('__RECAPTCHA_SITE_KEY__', RECAPTCHA_SITE_KEY, $src);
        } else {
            $recaptchaScript->outertext = '';
        }
    }

    $recaptchaWrap = $dom->find('.v-recaptcha', 0);
    if ($recaptchaWrap) {
        if (RECAPTCHA_ENABLED) {
            $recaptchaSiteKey = $recaptchaWrap->find('input[name="g-recaptcha-site-key"]', 0);
            if ($recaptchaSiteKey) {
                $recaptchaSiteKey->attr['value'] = RECAPTCHA_SITE_KEY;
            }
            $recaptchaAction = $recaptchaWrap->find('input[name="g-recaptcha-action"]', 0);
            if ($recaptchaAction) {
                $recaptchaAction->attr['value'] = RECAPTCHA_ACTION;
            }
        } else {
            $recaptchaWrap->outertext = '';
        }
    }

    // validate.js キャッシュ対策
    $validateScript = $dom->find('script[src="js/validate.js"]', 0);
    if ($validateScript) {
        $validateScript->src = 'js/validate.js?v=' . time();
    }

    return $dom;
}

function verifyRecaptchaToken($token, $remoteIp = '', $action = '')
{
    if (!RECAPTCHA_ENABLED) {
        return ['success' => true, 'score' => 1.0, 'action' => $action];
    }
    if ($token === '') {
        return ['success' => false, 'reason' => 'missing-token'];
    }

    $payload = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $token,
    ];
    if ($remoteIp !== '') {
        $payload['remoteip'] = $remoteIp;
    }

    if (function_exists('curl_init')) {
        $ch = curl_init(RECAPTCHA_VERIFY_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
    } else {
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($payload),
                'timeout' => 10,
            ],
        ];
        $response = @file_get_contents(RECAPTCHA_VERIFY_URL, false, stream_context_create($options));
    }

    if ($response === false) {
        return ['success' => false, 'reason' => 'request-failed'];
    }

    $decoded = json_decode($response, true);
    if (empty($decoded['success'])) {
        return ['success' => false, 'reason' => 'verify-failed', 'error-codes' => $decoded['error-codes'] ?? []];
    }

    $tokenAction = (string) ($decoded['action'] ?? '');
    $score = isset($decoded['score']) ? (float) $decoded['score'] : 0.0;
    if ($action !== '' && $tokenAction !== '' && $tokenAction !== $action) {
        return ['success' => false, 'reason' => 'action-mismatch', 'score' => $score, 'action' => $tokenAction];
    }
    if (isset($decoded['score']) && $score < RECAPTCHA_MIN_SCORE) {
        return ['success' => false, 'reason' => 'low-score', 'score' => $score, 'action' => $tokenAction];
    }

    return ['success' => true, 'score' => $score, 'action' => $tokenAction];
}


$mailhead = <<<EOF
このメールは自動送信でお送りしています。
WEBサイトのお問い合わせより送信がありました。

EOF;
define('MAILHEAD', $mailhead);

$reply = <<<EOF

こべっこあそびひろば・六甲アイランドに
お問い合わせをいただき、誠にありがとうございます。
担当者より、あらためてご連絡させていただきますので、
今しばらくお待ちください。

EOF;
define('REPLYMAIL', $reply);

$mailfoot = <<<EOF


=======================================
こべっこあそびひろば・六甲アイランド
（指定管理者：神姫チャイルドランド共同事業体）

〒658-0032　兵庫県神戸市東灘区向洋町中２丁目９-１
神戸ファッションプラザ 3F
電話番号078-855-5731
開館 9時30分から閉館 17時
定休日：木曜日（木曜が祝日の場合は次の平日が休所）
　　　　および年末年始（12月29日から1月3日）
=======================================
EOF;
define('MAILFOOT', $mailfoot);
