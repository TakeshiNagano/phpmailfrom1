<?php
require_once('qdmail.php');
require_once('simple_html_dom.php');
require_once("conf.php");



session_start();

$items = $_SESSION['items_names'];

$errmessage = array();

if (!$_POST['token']) {
  $errmessage['token'] = "不正な動作が行われました。ブラウザで戻るボタンが押されたか、二重送信が行われました。";
} elseif ($_POST['token'] != $_SESSION['token']) {
  $errmessage['token'] = "不正な動作が行われました。ブラウザで戻るボタンが押されたか、二重送信が行われました。";
}

if (!$_SESSION['name'] || !$_SESSION['email'] || !$_SESSION['content']) {
  $errmessage['token'] = "申し訳ありません送信できませんでした。時間が経ちすぎた可能性があります。";
}

//$errmessage['token'] = "申し訳ありません送信できませんでした。時間が経ちすぎた可能性があります。";

if (!empty($errmessage)) {
  //var_dump($errmessage);
  $dom = file_get_html('contact/index.html');
  $e = $dom->find('#formerror', 0);
  $form = $dom->find('form', 0);
  $errorhtml = '<ul class="errormessage" id="errors">';
  foreach ($errmessage as $nameval => $message) {
    $errorhtml .= '<li>' . $message . '</li>';
  }
  $errorhtml .= '</ul>';
  $e->innertext = $errorhtml;

  foreach ($items as $name => $value) {
    $input = $form->find('[name=' . $name . ']', 0);
    if ($name == 'docs' || $name == 'docs1' || $name == 'docs2' || $name == 'docs3') {
      //$input = $dom->find('[name='. $name . '[]]');
      $tag = 'checkboxes';
    } else {
      $tag = $input->nodename();
      if ($tag == 'input' && $input->getAttribute("type") == 'radio') {
        $tag = 'radio';
      }
      if ($tag == 'input' && $input->getAttribute("type") == 'checkbox') {
        $tag = 'checkbox';
      }
    }
    //var_dump($input->getAttribute("type"));


    //var_dump($name.':'.$tag.'<br />');

    switch ($tag) {
      case 'textarea':
        $input->innertext = $_SESSION['content'];
        break;

      case 'select':
        $options = $input->find('option');
        if ($input->getAttribute("multiple")) {
          $option_list = $form->find('select[name=' . $name . '[]]', 0)->find('option');
          foreach ($option_list as $option) {
            $selected = null;
            if (is_array($item['value'])) {
              foreach ($item['value'] as $val) {
                if ($option->value === $val) {
                  $selected = (strlen($val) > 0) ? 'selected' : null;
                  continue;
                }
              }
            }
            $option->selected = $selected;
          }
        } else {
          $option_elem = $input->find('option[selected], option[selected=selected]', 0);
          if ($option_elem !== null) {
            $option_elem->selected = null;
          }
          $input->find('option[value=' . $_SESSION[$name] . ']', 0)->selected = 'selected';
        }
        break;

      case 'radio':

        foreach ($form->find('input[name=' . $name . ']') as $radio) {
          //var_dump($radio->checked);
          if ($radio->value === $_SESSION[$name]) {
            $radio->checked = 'checked';
          } else if ($radio->checked !== null) {
            $radio->checked = null;
          }
        }
        break;

      case 'checkbox':
        $checked = (strlen($_SESSION[$name]) > 0) ? 'checked' : null;
        $input->checked = $checked;
        break;

      case 'checkboxes':
        //var_dump($_SESSION[$name]);
        $checkbox_list = $form->find('.' . $name . 'check');
        //var_dump($checkbox_list);
        foreach ($checkbox_list as $checkbox) {
          $checked = null;
          //var_dump($checkbox->value);
          if (is_array($_SESSION[$name])) {
            foreach ($_SESSION[$name] as $val) {
              //var_dump($val);
              if ($checkbox->value === $val) {
                $checked = (strlen($val) > 0) ? 'checked' : null;
                continue;
              }
            }
          }
          $checkbox->checked = $checked;
        }
        break;
      default:
        $input->value = $_SESSION[$name];
    }
  }
} else {


  //エラーメッセージ用日本語言語ファイルを読み込む場合（25行目の指定も必要）
  //require 'vendor/phpmailer/phpmailer/language/phpmailer.lang-ja.php';

  //言語、内部エンコーディングを指定
  mb_language("japanese");
  mb_internal_encoding("UTF-8");

  // インスタンスを生成（引数に true を指定して例外 Exception を有効に）
  $mail = new Qdmail();

  //日本語用設定
  //$mail->CharSet = "iso-2022-jp";
  //$mail->Encoding = "7bit";
  //$mail->CharSet = "utf-8";

  //エラーメッセージ用言語ファイルを使用する場合に指定
  //$mail->setLanguage('ja', 'vendor/phpmailer/phpmailer/language/');

  //$mail->isSendmail();
  //$mail->isSMTP(false);
  //$mail->isHTML(false); 


  //問い合わせメール送信

  try {
    //サーバの設定
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;  // デバグの出力を有効に（テスト環境での検証用）
    // $mail->isSMTP();   // SMTP を使用
    // $mail->Host       = 'mail.example.com';  // SMTP サーバーを指定
    // $mail->SMTPAuth   = true;   // SMTP authentication を有効に
    // $mail->Username   = 'info@example.com';  // SMTP ユーザ名
    // $mail->Password   = 'password';  // SMTP パスワード
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // 暗号化を有効に
    // $mail->Port       = 465;  // TCP ポートを指定



    //$mail->From = ADMINMAIL;
    //$mail->FromName = ADNAME;

    //$mail->from(ADMINMAIL, ADNAME);
    //$mail->to(ADMINMAIL, ADNAME);
    //$mail->replyto(ADMINMAIL, ADNAME);

    //コンテンツ設定
    //$mail->isHTML(false);   // HTML形式を指定
    //$mail->subject = ADMINMAILTITLE;

    $body =  MAILHEAD;
    foreach ($items as $name => $title) {
      if ($name == 'email_conf' || $name == 'consent') {
        continue;
      } elseif ($name == 'docs' || $name == 'docs1' || $name == 'docs2' || $name == 'docs3') {
        $body .= $title . ' : ';
        foreach ($_SESSION[$name] as $val) {
          $body .= $val . PHP_EOL;
        }
        //$body .= PHP_EOL;
      } elseif($name == 'content') {
        $body .= $title . ' : '  . PHP_EOL . $_SESSION[$name] . PHP_EOL;
      } else {
        $body .= $title . ' : ' . $_SESSION[$name] . PHP_EOL;
      }
    }
    $body .=  MAILFOOT;

    //var_dump($body);  
    //$mail->text  = $body;
    //$mail->AltBody = $body;
    $mail->lineFeed("\n");
    $mail->easyText(
      array(ADMINMAIL, ADNAME),
      ADMINMAILTITLE,
      $body,
      array(ADMINMAIL, ADNAME)
    );  //送信
    //$mail->qd_send_mail( 'text', array(ADMINMAIL, ADNAME), ADMINMAILTITLE, 本文,　From情報（＆追加ヘッダー）, 添付ファイル指定　);
    //var_dump($mail);
    //var_dump($mail->ErrorInfo);

  } catch (\Throwable $ex) {
    //エラー（例外：Exception）が発生した場合
    
    $log_time = date('Y-m-d H:i:s');
    //error_log('[' . $log_time . '] メール送信に失敗しました。' . PHP_EOL, 1, ADMINMAIL, $body);
    error_log('[' . $log_time . '] mailsend error' . PHP_EOL, 0, $ex->getMessage().$mail->ErrorInfo);

    $dom = HtmlDomParser::file_get_html('contact/index.html');
    $e = $dom->find('#formerror', 0);
    $form = $dom->find('form', 0);
    $errorhtml = '<ul class="errormessage" id="errors">';
    $errorhtml .= '<li>申し訳ありません。メール送信に失敗しました。最初からやり直してください</li>';
    $errorhtml .= '</ul>';
    $e->innertext = $errorhtml;
    print $dom;
    exit;
  }

  //返信メール送信

  $mail = new Qdmail();

  try {
    //サーバの設定
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;  // デバグの出力を有効に（テスト環境での検証用）
    // $mail->isSMTP();   // SMTP を使用
    // $mail->Host       = 'mail.example.com';  // SMTP サーバーを指定
    // $mail->SMTPAuth   = true;   // SMTP authentication を有効に
    // $mail->Username   = 'info@example.com';  // SMTP ユーザ名
    // $mail->Password   = 'password';  // SMTP パスワード
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // 暗号化を有効に
    // $mail->Port       = 465;  // TCP ポートを指定



    //$mail->from(ADMINMAIL, ADNAME);

    //$mail->to($_SESSION['email'], $_SESSION['name']);
    //$mail->replyto(ADMINMAIL, ADNAME);

    //コンテンツ設定
    //$mail->isHTML(false);   // HTML形式を指定
    //$mail->subject = REPLYMAILTITLE;

    $body2 =  $_SESSION['name'] . '様' . PHP_EOL;
    $body2 .= REPLYMAIL;
    //$body2 .= PHP_EOL;
    foreach ($items as $name => $title) {
      if ($name == 'email_conf' || $name == 'consent') {
        continue;
      } elseif ($name == 'docs' || $name == 'docs1' || $name == 'docs2' || $name == 'docs3') {
        $body2 .= $title . ' : ';
        foreach ($_SESSION[$name] as $val) {
          $body2 .= $val . PHP_EOL;
        }
        //$body .= PHP_EOL;
      } elseif($name == 'content') {
        $body2 .= $title . ' : '  . PHP_EOL . $_SESSION[$name] . PHP_EOL;
      } else {
        $body2 .= $title . ' : ' . $_SESSION[$name] . PHP_EOL;
      }
    }
    //$body2 .= PHP_EOL;
    $body2 .=  MAILFOOT;

    //$mail->Body  = 'メッセージ'; 
    //var_dump($body); 
    //$mail->text = $body;
    $mail->lineFeed("\n");
    $mail->easyText(
      array($_SESSION['email'], $_SESSION['name'] . '様'),
      REPLYMAILTITLE,
      $body2,
      array(ADMINMAIL, ADNAME)
    );

  } catch (\Throwable $ex) {
    //var_dump($ex);
    //var_dump($mail->ErrorInfo);
  
    //エラー（例外：Exception）が発生した場合
    $log_time = date('Y-m-d H:i:s');
    //error_log('[' . $log_time . '] メール送信に失敗しました。' . PHP_EOL, 1, ADMINMAIL, $body);
    error_log('[' . $log_time . '] reply error' . PHP_EOL, 0, $e->getMessage());

    $dom = file_get_html('contact/index.html');
    $e = $dom->find('#formerror', 0);
    $form = $dom->find('form', 0);
    $errorhtml = '<ul class="errormessage" id="errors">';
    $errorhtml .= '<li>申し訳ありません。返信メール送信に失敗しました。最初からやり直してください</li>';
    $errorhtml .= '</ul>';
    $e->innertext = $errorhtml;
    print $dom;
    exit;
  }

  $_SESSION = array();
  $dom = file_get_html('contact/thanks.html');
  //print $dom;

}

print $dom;
