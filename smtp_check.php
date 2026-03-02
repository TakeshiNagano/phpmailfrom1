<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/conf.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function maskSecret($value)
{
    $value = (string) $value;
    if ($value === '') {
        return '(empty)';
    }
    if (strlen($value) <= 4) {
        return str_repeat('*', strlen($value));
    }
    return substr($value, 0, 2) . str_repeat('*', strlen($value) - 4) . substr($value, -2);
}

function buildMailer(&$smtpDebugLines)
{
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->setLanguage('ja', __DIR__ . '/vendor/phpmailer/phpmailer/language/');
    $mail->Timeout = 20;

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function ($str, $level) use (&$smtpDebugLines) {
        $smtpDebugLines[] = "[L{$level}] {$str}";
    };

    if (SMTP) {
        $mail->isSMTP();
        $mail->Host = MAILHOST;
        $mail->SMTPAuth = SMTPAUTH;
        $mail->Username = SMTPUSER;
        $mail->Password = SMTPPASW;
        if (SMTPSEC) {
            $mail->SMTPSecure = SMTPSEC;
        }
        $mail->Port = SMTPPORT;
    } else {
        $mail->isSendmail();
    }

    return $mail;
}

$result = null;
$smtpDebugLines = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? 'connect';
    $mail = null;

    try {
        $mail = buildMailer($smtpDebugLines);

        if (!SMTP) {
            $result = [
                'ok' => false,
                'title' => 'SMTPが無効です',
                'message' => 'conf.php で SMTP=false になっています。現在は sendmail モードです。',
            ];
        } elseif ($mode === 'send') {
            $mail->Sender = ADMINMAIL;
            $mail->From = ADMINMAIL;
            $mail->FromName = ADNAME;
            $mail->addAddress(ADMINMAIL, ADNAME);
            $mail->Subject = '[SMTP CHECK] ' . date('Y-m-d H:i:s');
            $mail->Body = "SMTPテストメールです。\n送信時刻: " . date('Y-m-d H:i:s');
            $mail->isHTML(false);
            $mail->send();
            $result = [
                'ok' => true,
                'title' => 'テスト送信成功',
                'message' => 'ADMINMAIL (' . ADMINMAIL . ') への送信に成功しました。',
            ];
        } else {
            $connected = $mail->smtpConnect();
            if (!$connected) {
                throw new Exception('SMTP接続に失敗しました。');
            }
            $mail->smtpClose();
            $result = [
                'ok' => true,
                'title' => '接続成功',
                'message' => 'SMTPサーバーへの接続と認証に成功しました。',
            ];
        }
    } catch (\Throwable $e) {
        $result = [
            'ok' => false,
            'title' => 'チェック失敗',
            'message' => $e->getMessage(),
            'error_info' => ($mail instanceof PHPMailer) ? $mail->ErrorInfo : '',
        ];
    } finally {
        if ($mail instanceof PHPMailer) {
            try {
                $mail->smtpClose();
            } catch (\Throwable $ignored) {
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMTP Check</title>
    <style>
        body { font-family: sans-serif; max-width: 960px; margin: 24px auto; padding: 0 16px; line-height: 1.6; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
        .ok { color: #0a7a2f; font-weight: bold; }
        .ng { color: #c00; font-weight: bold; }
        .panel { border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin-bottom: 16px; background: #fafafa; }
        pre { background: #111; color: #ddd; padding: 12px; border-radius: 8px; overflow-x: auto; }
        button { padding: 10px 16px; margin-right: 8px; }
    </style>
</head>
<body>
    <h1>SMTP 設定チェッカー</h1>
    <p>このページは <code>conf.php</code> の設定値を使って SMTP 接続/送信可否を確認します。</p>

    <div class="panel">
        <h2>現在の設定</h2>
        <table>
            <tr><th>SMTP</th><td><?= SMTP ? 'true' : 'false' ?></td></tr>
            <tr><th>MAILHOST</th><td><?= htmlspecialchars((string) MAILHOST, ENT_QUOTES, 'UTF-8') ?></td></tr>
            <tr><th>SMTPPORT</th><td><?= (int) SMTPPORT ?></td></tr>
            <tr><th>SMTPSEC</th><td><?= htmlspecialchars((string) SMTPSEC, ENT_QUOTES, 'UTF-8') ?: '(none)' ?></td></tr>
            <tr><th>SMTPAUTH</th><td><?= SMTPAUTH ? 'true' : 'false' ?></td></tr>
            <tr><th>SMTPUSER</th><td><?= htmlspecialchars((string) SMTPUSER, ENT_QUOTES, 'UTF-8') ?></td></tr>
            <tr><th>SMTPPASW</th><td><?= htmlspecialchars(maskSecret((string) SMTPPASW), ENT_QUOTES, 'UTF-8') ?></td></tr>
            <tr><th>ADMINMAIL</th><td><?= htmlspecialchars((string) ADMINMAIL, ENT_QUOTES, 'UTF-8') ?></td></tr>
        </table>
    </div>

    <div class="panel">
        <h2>実行</h2>
        <form method="post">
            <button type="submit" name="mode" value="connect">接続チェック</button>
            <button type="submit" name="mode" value="send">ADMINMAILへテスト送信</button>
        </form>
    </div>

    <?php if ($result !== null): ?>
        <div class="panel">
            <h2>結果</h2>
            <p class="<?= $result['ok'] ? 'ok' : 'ng' ?>"><?= htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><?= htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php if (!empty($result['error_info'])): ?>
                <p><strong>PHPMailer ErrorInfo:</strong> <?= htmlspecialchars((string) $result['error_info'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($smtpDebugLines)): ?>
        <div class="panel">
            <h2>SMTPデバッグログ</h2>
            <pre><?= htmlspecialchars(implode("\n", $smtpDebugLines), ENT_QUOTES, 'UTF-8') ?></pre>
        </div>
    <?php endif; ?>
</body>
</html>
