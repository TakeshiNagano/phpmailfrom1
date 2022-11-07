<?php
require_once('simple_html_dom.php');

session_start();

$dom = file_get_html('contact/index.html');
if($_SESSION['name'] || $_SESSION['email']){
	$items = $_SESSION['items_names'];
	$form = $dom->find('form', 0);
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
}

print $dom;


