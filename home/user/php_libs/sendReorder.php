<?php
/**
 * 追加注文メール送信
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/order/ordermail.php';

if(isset($_REQUEST['orderid'])) {
	$user = $_REQUEST['user'];
	$deli = $_REQUEST['deli'];
	$item = json_decode($_REQUEST['item'], true);
	$orderId = $_REQUEST['orderid'];

	$items = array();
	$totAmount = 0;
	for($i=0; $i<count($item); $i++){
		$items[] = array(
			"name" => $item[$i]['name'],
			"color" => $item[$i]['color'],
			"size" => $item[$i]['size'],
			"amount" => $item[$i]['amount'],
		);

		$totAmount += $item[$i]['amount'];
	}
	
	$args = array(
		"ex-orderid" => $orderId,
		"number" => $user['number'],
		"name" => $user['name'],
		"email" => $user['email'],
		"tel" => $user['tel'],
		"zipcode" => $deli['zipcode'],
		"addr" => $deli['addr'],
		"delidate" => $deli['delidate'],
		"delitime" => $deli['delitime'],
		"pack" => $deli['pack'],
		"item" => $items,
		"order_amount" => $totAmount,
		"message" => $deli['message'],
	);
	
	// メール送信
	$mail = new Ordermail();
	$res = $mail->sendReorder($args);
	
	// 送信結果をJSON出力
	header("Content-Type: text/javascript; charset=utf-8");
	echo json_encode($res);
}

?>