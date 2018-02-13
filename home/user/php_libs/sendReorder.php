<?php
/**
 * 追加注文メール送信
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/package/mail/Mailer.php';
use package\mail\Mailer;

if(isset($_REQUEST['orderid'])) {
	$user = $_REQUEST['user'];
	$deli = $_REQUEST['deli'];
	$item = json_decode($_REQUEST['item'], true);
	$orderId = $_REQUEST['orderid'];
	
	$subject = '追加注文のお申し込み【takahama428】';

	$items = array();
	$totAmount = 0;
	for($i=0; $i<count($item); $i++){
		$items[] = array(
			"アイテム" => $item[$i]['name'],
			"カラー" => $item[$i]['color'],
			"サイズ" => $item[$i]['size'],
			"枚数" => $item[$i]['amount']." 枚",
		);
		
		$totAmount += $item[$i]['amount'];
	}
	
	// メール本文用の配列
	$args = array(
		"元受注No." => $orderId,
		"part-user" => "お客様情報",
		"顧客ID" => $user['number'],
		"お名前" => $user['name']." 様",
		"E-Mail" => $user['email'],
		"TEL" => $user['tel'],
		"お届け先" => "〒".$deli['zipcode']." ".$deli['addr'],
		"ご希望納期" => $deli['delidate'],
		"配達時間" => $deli['delitime'],
		"袋詰め" => $deli['pack'],
		"part-orderitem" => "注文商品",
		"array" => $items,
		"合計枚数" => $totAmount,
		"part-message" => "",
		"メッセージ" => $deli['message'],
	);
	
	// prefixを付ける
	$idx = 0;
	foreach ($args as $key=>$val ) {
		$mailBody['tpl-'.($idx++).'_'.$key] = $val;
	}
	
	// タイトルと返信用の前文
	$mailBody['tpl-title'] = "追加注文のお申し込み";
	$mailBody['tpl-replyhead'] = "このたびは、タカハマライフアートをご利用いただき誠にありがとうございます。";
	
	// メール送信
	$mail = new Mailer('', $mailBody);
	$res = $mail->send($subject, _ORDER_EMAIL, $user['email']);
	
	// 送信結果をJSON出力
	header("Content-Type: text/javascript; charset=utf-8");
	echo json_encode($res);
}

?>