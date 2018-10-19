<?php
/**
 * 追加注文メール送信
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/order/ordermail.php';

if(isset($_REQUEST['orderid']) && !empty($_REQUEST['orderid'])) {
	try {
		$user = $_REQUEST['user'];
		$deli = $_REQUEST['deli'];
		$item = json_decode($_REQUEST['item'], true);
		$orderId = $_REQUEST['orderid'];

		if (empty($deli['zipcode']) || empty($deli['addr'])) {
			throw new Exception('There is no destination address.');
		}

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
		
		if ($totAmount === 0) {
			throw new Exception('There is no item data to order.');
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
	} catch (Exception $e) {
		$res = array('send'=>'error', 'message'=>$e->getMessage());
	}
} else {
	$res = array('send'=>'error', 'message'=>'Could not get original order data.');
}

// 送信結果をJSON出力
header("Content-Type: text/javascript; charset=utf-8");
echo json_encode($res);
?>