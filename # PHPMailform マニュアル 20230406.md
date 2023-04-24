# PHPMailform マニュアル　20230410
## 動作環境
* プログラム言語PHP　バージョン7以上
* 文字コード　UTF-8
* suspend.txtファイル書き込み権限
## 機能
* javascriptによる入力チェック クラス名で設定（v-nameなど）
* 画像認証（数字のみ）なくても動作します。
* サスペンド機能　サスペンド時、suspend.html表示
* **
## 使い方
### 設定項目
#### conf.php
* define('ADNAME','株式会社');
メール送信元名設定「株式会社」の部分を書き換えてください。
* define('ADMINMAIL','test@suimu.net')
メール送信元メールアドレス「test@suimu.net」の部分を書き換えてください。
* define('ADMINMAILTITLE','お問い合わせ'); 管理者へ送られるメール題名
* define('REPLYMAILTITLE','お問い合わせありがとうございます。'); お客様への返信メール題名
* define('CONFTABLE', 1); 確認画面をtableで表示する場合は1　divで表示する場合0
* define('REPLYMAILCONTENT', 1);　0=問い合わせ返信メールで問い合わせ内容非表示  1=問い合わせ返信メールで問い合わせ内容表示
* define('SMTP', false); smtpを使って送信の場合true、通常false
* define('MAILHOST', 'ham1001.secure.ne.jp'); smtpサーバ（メールサーバ）URL
* define('SMTPAUTH', true); メール送信時にユーザ名（ID）とパスワードが必要な場合true 通常true
* define('SMTPUSER', 't-nagano@shinkibizpro.co.jp'); メール送信時ユーザ名（ID）
* define('SMTPPASW', ''); メール送信時パスワード(メールアカウントのパスワード)
* define('SMTPSEC', 'ssl'); 暗号方式　ssl,tls,false(無しの場合)　googleの場合tls
* define('SMTPPORT', 465); ポート番号　googleの場合587
```PHP
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
```
「メール前半」、「返信メール」、「メール後半 メール署名」部分を適宜置き換えてください。改行はメール文面に反映されます。
問い合わせ入力情報は、メール前半とメール後半の間、返信メールの場合返信メールの後に表示されます。
define('REPLYMAILCONTENT', 0);の場合は表示されません。
***
### top.html
メールフォームの最初に表示されるHTMLファイル
実際にはindex.phpから呼び出されて表示される。
```
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/validate.js"></script>
```
上記の部分で、フォームの値チェック機能を呼び出しています。
```
<div id="formerror"></div><!--エラー表示の為必要　id="formerror"-->
```
システムでのエラーチェック（画像認証等）のエラー表示場所、他の場所に移動、削除も可
```
<form action="confirm.php" method="post" name="form" enctype="multipart/form-data" id="form">
```
フォームタグ変更不可、class等の追加は可能
```
        <input type="hidden" name="name_name" value="お名前">
        <input type="hidden" name="furigana_name" value="ふりがな">
        <input type="hidden" name="email_name" value="メールアドレス">  
        <input type="hidden" name="tel_name" value="電話番号">
        <input type="hidden" name="sex_name" value="性別">
        <input type="hidden" name="shopname_name" value="店舗名">
        <input type="hidden" name="item_name" value="お問い合わせ項目">
        <input type="hidden" name="item2_name" value="お問い合わせ項目2">
        <input type="hidden" name="docs_name" value="資料">
        <input type="hidden" name="docs1_name" value="資料2">
        <input type="hidden" name="content_name" value="お問い合わせ内容">
        <input type="hidden" name="other_name" value="その他">
        <input type="hidden" name="consent_name" value="同意">
        <input type="hidden" name="attachment_name" value="ファイル">
```
必須入力項目関係なく、各入力項目の項目名を確認画面、メール文面、エラーで表示するために必要。
「メールアドレス確認」などは不要。 hiddenタグのnameは入力項目のname
と語尾に_nameが必ず必要 hiddenタグがない、欠けている場合エラーで動きません。
```
<input type="text" name="name" placeholder="例）山田太郎" value="" class="v-name">
```
お名前必須項目の場合、`name="name"` 入力チェックのため`class="v-name"`が必要
```
<input type="text" name="furigana" placeholder="例）やまだたろう" value="" class="v-kana">
```
ふりがな入力欄は`name="furigana"` 必須の場合は`class="v-kana"`が必要、チェックはひらがなのみ
```
<input type="text" name="email" placeholder="例）guest@example.com" value="" class="v-email">
```
メールアドレス入力欄がない場合システムは動きません。`class="v-email"`が必要
```
<input type="text" name="email_conf" placeholder="例）guest@example.com" value="" class="v-emailconf">
```
メールアドレス確認欄がない場合システムは動きません。`class="v-emailconf"`が必要
```
<input type="text" name="tel" placeholder="例）0000000000" value="" class="v-tel">
```
電話番号は必須の場合`class="v-tel"`が必要　ハイフン有り無両方チェック可
```
<input type="radio" name="sex" value="男性" class="v-radio"> 男性
<input type="radio" name="sex" value="女性" class="v-radio"> 女性
```
ラジオで必須入力の場合`class="v-radio"`が必要
```
<input type="text" name="shopname" placeholder="例）姫路支店" value="" class="v-text">
```
お名前以外の必須チェックは`class="v-text"を設置
```
<select name="item" class="v-select">
	<option value="">お問い合わせ項目を選択してください</option>
	<option value="ご質問・お問い合わせ">ご質問・お問い合わせ</option>
	<option value="ご意見・ご感想">ご意見・ご感想</option>
</select>
```
プルダウンの必須チェックは`class="v-select"`を設置
```
<input type="checkbox" name="docs[]" value="資料A" class="v-check">資料A
<input type="checkbox" name="docs[]" value="資料B" class="v-check">資料B
<input type="checkbox" name="docs[]" value="資料C" class="v-check">資料C
```
チェックボックスの場合、nameはdocs[],docs1[],docs2[],docs3[]を使って下さい。必須チェックは`class="v-check"`を設置
```
<textarea name="content" rows="5" placeholder="お問合せ内容を入力" class="v-body"></textarea>
```
お問い合わせテキストエリアの必須チェックは`class="v-body"`
```
<textarea name="other" rows="5" placeholder="その他内容を入力" class="v-textarea"></textarea>
```
テキストエリアの必須チェックは`class="v-textarea"`
```
<img src="captcha.php" width="150" height="40" title="画像認証" id="captcha_img">
<input type="text" name="captcha_val" placeholder="画像に表示されている数字を入力してください。" value="" class="v-captcha">
```
画像認証を外す場合、上記タグを削除してください
```
<input type="file" name="attachment" class="v-file">
```
ファイルの必須チェックは`class="v-file"`
#### エラー表示
上記の必須チェックを設置すると入力値が正しくない場合
入力タグの下にエラーが表示されます。
```
<p class="error-info">お名前を入力してください。</p>
```
***
### confirm.html
```
<div id="confirms"><!-- id="confirms"が必要 -->
	<div>
		<label>お名前</label>
		<p>お名前</p>
	</div>
 </div>
```
確認画面をdivで表示
```
<table id="confirms" class="com_table mB30"> <!-- id="confirms"が必要 -->
</table>
```
確認画面をtableで表示
***
### suspend.txt
suspend.txtファイルに1が記載されているとき、top.htmlが読み込まれずsuspend.htmlが読み込まれる。suspend.txtファイルがない、ファイルはあるが記載がなく改行もない場合、top.htmlが読み込まれる

### SMTPを使ったメール送信
googleのsmtp（メール）を使う場合、2段階認証プロセスを有効にしてアプリ パスワードが必要
conf.phpの設定が必要です。