<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// 設定ファイル読み込み
require_once __DIR__ . '/conf.php';

// 検証結果用
$result = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['g-recaptcha-response'] ?? '';
    $action = 'recaptcha_check';

    if ($token) {
        // verifyRecaptchaToken関数は conf.php で定義されている前提
        $result = verifyRecaptchaToken($token, $_SERVER['REMOTE_ADDR'], $action);
        $message = $result['success'] ? '認証成功！' : '認証失敗...';
    } else {
        $message = 'トークンが送信されていません。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>reCAPTCHA Check</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .status-ok {
            color: green;
            font-weight: bold;
        }

        .status-ng {
            color: red;
            font-weight: bold;
        }

        .box {
            border: 1px solid #ccc;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        pre {
            background: #f5f5f5;
            padding: 0.5rem;
            overflow-x: auto;
        }
    </style>
    <!-- reCAPTCHA Script -->
    <?php if (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY): ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?= htmlspecialchars(RECAPTCHA_SITE_KEY) ?>"></script>
    <?php endif; ?>
</head>

<body>
    <h1>reCAPTCHA 設定チェッカー</h1>

    <div class="box">
        <h2>1. 設定ファイルの読み込み状況 (conf.php)</h2>
        <table>
            <tr>
                <th>定数</th>
                <th>設定値</th>
                <th>状態</th>
            </tr>
            <tr>
                <td>RECAPTCHA_ENABLED</td>
                <td>
                    <?= defined('RECAPTCHA_ENABLED') ? (RECAPTCHA_ENABLED ? 'true' : 'false') : '未定義' ?>
                </td>
                <td>
                    <?= (defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED) ? '<span class="status-ok">有効</span>' : '<span class="status-ng">無効</span>' ?>
                </td>
            </tr>
            <tr>
                <td>RECAPTCHA_SITE_KEY</td>
                <td>
                    <?= defined('RECAPTCHA_SITE_KEY') ? htmlspecialchars(RECAPTCHA_SITE_KEY) : '未定義' ?>
                </td>
                <td>
                    <?= (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY) ? '<span class="status-ok">OK</span>' : '<span class="status-ng">空欄</span>' ?>
                </td>
            </tr>
            <tr>
                <td>RECAPTCHA_SECRET_KEY</td>
                <td>
                    <?= defined('RECAPTCHA_SECRET_KEY') ? (RECAPTCHA_SECRET_KEY ? substr(RECAPTCHA_SECRET_KEY, 0, 5) . '...' : '') : '未定義' ?>
                </td>
                <td>
                    <?= (defined('RECAPTCHA_SECRET_KEY') && RECAPTCHA_SECRET_KEY) ? '<span class="status-ok">OK</span>' : '<span class="status-ng">空欄</span>' ?>
                </td>
            </tr>
            <tr>
                <td>RECAPTCHA_MIN_SCORE</td>
                <td>
                    <?= defined('RECAPTCHA_MIN_SCORE') ? RECAPTCHA_MIN_SCORE : '未定義' ?>
                </td>
                <td>-</td>
            </tr>
        </table>
    </div>

    <div class="box">
        <h2>2. 動作確認テスト (v3)</h2>
        <?php if (!defined('RECAPTCHA_SITE_KEY') || !RECAPTCHA_SITE_KEY): ?>
            <p class="status-ng">Site Key が設定されていないためテストできません。</p>
        <?php else: ?>
            <p>ボタンを押すと、Googleへトークンを要求し、サーバーサイドで検証を行います。</p>
            <form method="post" id="check-form">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                <button type="button" onclick="runCheck()">検証実行</button>
            </form>

            <script>
                function runCheck() {
                    grecaptcha.ready(function () {
                        grecaptcha.execute('<?= RECAPTCHA_SITE_KEY ?>', { action: 'recaptcha_check' }).then(function (token) {
                            document.getElementById('g-recaptcha-response').value = token;
                            document.getElementById('check-form').submit();
                        });
                    });
                }
            </script>
        <?php endif; ?>
    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="box">
            <h2>3. 検証結果</h2>
            <p><strong>メッセージ:</strong>
                <?= htmlspecialchars($message) ?>
            </p>
            <?php if ($result): ?>
                <h3>詳細ダンプ:</h3>
                <pre><?= htmlspecialchars(print_r($result, true)) ?></pre>

                <?php if (!empty($result['success'])): ?>
                    <p class="status-ok">判定結果: 成功 (Score:
                        <?= htmlspecialchars($result['score']) ?>)
                    </p>
                <?php else: ?>
                    <p class="status-ng">判定結果: 失敗 (Reason:
                        <?= htmlspecialchars($result['reason'] ?? 'unknown') ?>)
                    </p>
                    <p>
                        <strong>考えられる原因:</strong><br>
                        - Site Key と Secret Key のペアが間違っている<br>
                        - ドメイン (kobekko-rokko.jp) が Google Admin Console に登録されていない<br>
                        - ローカル環境 (localhost) で実行している (localhostも登録が必要)
                    </p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <p><a href="index.php">お問い合わせフォームに戻る</a></p>
</body>

</html>