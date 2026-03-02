<?php
ini_set('display_errors', 'Off');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require('vendor/autoload.php');
require_once("conf.php");

use KubAT\PhpSimple\HtmlDomParser;

session_start();

//var_dump($_SESSION);

$errmessage = array();
$recaptchaDebugEnabled = filter_var(getenv('CONTACT_RECAPTCHA_DEBUG') ?: '0', FILTER_VALIDATE_BOOLEAN);
$recaptchaDebugLogPath = getenv('CONTACT_RECAPTCHA_DEBUG_LOG') ?: (__DIR__ . '/recaptcha_debug.log');

function logRecaptchaDebug($message, $context = [])
{
	global $recaptchaDebugEnabled, $recaptchaDebugLogPath;
	if (!$recaptchaDebugEnabled) {
		return;
	}
	$payload = '[recaptcha-debug] ' . date('Y-m-d H:i:s') . ' ' . $message;
	if (!empty($context)) {
		$payload .= ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);
	}
	$payload .= PHP_EOL;
	if (@file_put_contents($recaptchaDebugLogPath, $payload, FILE_APPEND | LOCK_EX) === false) {
		error_log(rtrim($payload));
	}
}

if (isset($_POST['email'])) {
	logRecaptchaDebug('confirm.php POST received', [
		'post_keys' => array_keys($_POST),
		'has_token' => isset($_POST['g-recaptcha-response']) && trim((string) $_POST['g-recaptcha-response']) !== '',
		'token_length' => strlen((string) ($_POST['g-recaptcha-response'] ?? '')),
		'action' => (string) ($_POST['g-recaptcha-action'] ?? ''),
		'remote_ip' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
	]);

	$items = array();
	foreach ($_POST as $key => $val) {
		//var_dump($key);
		//echo '<br>';
		//var_dump($val);
		//echo '<br>';
		preg_match('/(.*)_name/', $key, $date_match);
		if (isset($date_match) && isset($date_match[1])) {
			$items[$date_match[1]] = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
		} elseif (is_array($val)) {
			$_SESSION[$key] =  $val;
		} else {
			$_SESSION[$key] = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
		}
	}
	$_SESSION['items_names'] = $items;

	$honeypotValue = trim($_POST['contact_hp_6f4a2d9e'] ?? '');
	if ($honeypotValue !== '') {
		$errmessage['honeypot'] = "送信に失敗しました。時間をおいて再度お試しください";
	}

	if (RECAPTCHA_ENABLED) {
		//var_dump($_POST);
		$recaptchaToken = trim($_POST['g-recaptcha-response'] ?? '');
		$recaptchaAction = trim($_POST['g-recaptcha-action'] ?? RECAPTCHA_ACTION);
		$remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
		logRecaptchaDebug('recaptcha verify start', [
			'action' => $recaptchaAction,
			'token_length' => strlen($recaptchaToken),
		]);
		if ($recaptchaToken === '') {
			$errmessage['recaptcha'] = "reCAPTCHA認証を完了してください";
			logRecaptchaDebug('recaptcha token missing');
		} else {
			$verified = verifyRecaptchaToken($recaptchaToken, $remoteIp, $recaptchaAction);
			logRecaptchaDebug('recaptcha verify result', $verified);
			if (empty($verified['success'])) {
				error_log('[recaptcha] verify failed: ' . json_encode($verified, JSON_UNESCAPED_UNICODE));
				$errmessage['recaptcha'] = "reCAPTCHA認証に失敗しました。時間をおいて再度お試しください";
			}
		}
	}


	if (!$_POST['name']) {
		$errmessage['name'] = $items['name'] . "を入力してください";
	} elseif (mb_strlen($_POST['name']) > 100) {
		$errmessage['name'] = $items['name'] . "は100文字以内にしてください";
	}



	$mailpattern = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/";

	if (!$_POST['email']) {
		$errmessage['email'] = $items['email'] . "を入力してください";
	} elseif (!preg_match($mailpattern, $_POST['email'])) {
		$errmessage['email'] = $items['email'] . "を正しい形式のメールアドレスにしてください。";
	} elseif ($_POST['email'] != $_POST['email_conf']) {
		$errmessage['email'] = $items['email'] . "とメールアドレス確認が一致していません。";
	} elseif (mb_strlen($_POST['email']) > 100) {
		$errmessage['email'] = $items['email'] . "は100文字以内にしてください";
	}




	if (!empty($errmessage)) {
		//var_dump($errmessage);
		$dom = loadContactTopDom();
		$e = $dom->find('#formerror', 0);
		$form = $dom->find('form', 0);
		$errorhtml = '<ul class="errormessage" id="errors">';
		foreach ($errmessage as $nameval => $message) {
			$errorhtml .= '<li>' . $message . '</li>';
		}
		$errorhtml .= '</ul>';
		$e->innertext = $errorhtml;

		if ($errmessage['name']) {
			$classname = $dom->find('.v-name', 0);
			if ($classname) {
				$classname->outertext = $classname->outertext . '<p class="errors">' . $errmessage['name'] . '</p>';
			}
		}

		if ($errmessage['email']) {
			$classemail = $dom->find('.v-email', 0);
			if ($classemail) {
				$classemail->outertext = $classemail->outertext . '<p class="errors">' . $errmessage['email'] . '</p>';
			}
		}

		if ($errmessage['recaptcha']) {
			$recaptcha = $dom->find('.v-recaptcha', 0);
			if ($recaptcha) {
				$recaptcha->outertext = $recaptcha->outertext . '<p class="errors">' . $errmessage['recaptcha'] . '</p>';
			}
		}


		foreach ($items as $name => $value) {
			$input = $form->find('[name=' . $name . ']', 0);
			if (!$input) {
				//var_dump($name);
				continue;
			}
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
					$input->innertext = $_SESSION[$name];
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
		$dom = HtmlDomParser::file_get_html('confirm.html');
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		$_SESSION["token"] = $token;

		$confirms = $dom->find('#confirms', 0);
		$html = '<input type="hidden" name="token" value="' . $token . '">';
		foreach ($_SESSION['items_names'] as $name => $title) {
			if (!CONFTABLE) {
				if ($name == 'email_conf' || $name == 'consent') {
					continue;
				} elseif ($name == 'docs' || $name == 'docs1' || $name == 'docs2' || $name == 'docs3') {
					$html .= '<div><label>' . $title . '</label><p>';
					foreach ($_SESSION[$name] as $val) {
						$html .= $val . '<br />';
					}
					$html .= '</p></div>';
				} else {
					$html .= '<div><label>' . $title . '</label><p>' . nl2br($_SESSION[$name]) . '</p></div>';
				}
			} else {
				if ($name == 'email_conf' || $name == 'consent') {
					continue;
				} elseif ($name == 'docs' || $name == 'docs1' || $name == 'docs2' || $name == 'docs3') {
					$html .= '<tr><th>' . $title . '</th><td>';
					foreach ($_SESSION[$name] as $val) {
						$html .= $val . '<br />';
					}
					$html .= '</td></tr>';
				} else {
					$html .= '<tr><th>' . $title . '</th><td>' . nl2br($_SESSION[$name]) . '</td></tr>';
				}
			}
		}
		$confirms->innertext = $html;
	}

	print $dom;
}



//var_dump(HtmlDomParser::file_get_html('http://www.google.com/'));

//print $dom;
