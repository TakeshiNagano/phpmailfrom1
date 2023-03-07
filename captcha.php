<?php

require('vendor/autoload.php');

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

	header('Content-type: image/jpeg');
	$phraseBuilder = new PhraseBuilder(5, '0123456789');
     $builder = new CaptchaBuilder(null, $phraseBuilder);
	 $builder->build()->output();
	 session_start();
	 $_SESSION['captcha'] = $builder->getPhrase();