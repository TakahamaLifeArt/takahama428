<?php
/**
 * メール送信
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/package/mail/Mailer.php';
use package\mail\Mailer;

if (isset($_REQUEST['tpl-sendto'], $_REQUEST['tpl-subject'])) {
	$mailBody = $_REQUEST;
	$mailHeader = array(
		'tpl-sendto' => '',
		'tpl-subject' => '',
		'tpl-replyto' => '',
		'tpl-encode' => '',
	);

	foreach ($_REQUEST as $key=>$val) {
		if (isset($mailHeader[$key])) {
			$mailHeader[$key] = $val;
			unset($mailBody[$key]);
		}
	}

	$mail = new Mailer($mailHeader['tpl-encode'], $mailBody);
	$res = $mail->send($mailHeader['tpl-subject'], $mailHeader['tpl-sendto'], $mailHeader['tpl-replyto']);

	header("Content-Type: text/javascript; charset=utf-8");
	echo json_encode($res);
}
?>