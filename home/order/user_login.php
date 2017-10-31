<?php
/**
 * 顧客情報
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';

// ログイン処理
if (isset($_REQUEST['login'])) {
	$args = array($_REQUEST['email']);
	$conn = new Conndb(_API_U);

	// エラーチェック
	if (empty($_REQUEST['email'])) {
		$err = 'メールアドレスを入力して下さい。';
	} else if (!$conn->checkExistEmail($args)) {
		$err = 'このメールアドレスは登録されていません。';
	} else if (empty($_REQUEST['pass'])) {
		$err = 'パスワードを入力して下さい。';
	} else {
		$args = array('email'=>$_REQUEST['email'], 'pass'=>$_REQUEST['pass']);
		$me = $conn->getUser($args);
		if (!$me) {
			$err = "メールアドレス（".$_REQUEST['email']."）かパスワードが認識できません。ご確認下さい。";
		}
	}
	
	if (empty($err)) {
		$_SESSION['me'] = $me;
		//注文画面でログインした場合、注文情報にもセッションを設定する必要がある
		$_SESSION['orders']['customer']['member'] = $me['id'];
		$_SESSION['orders']['customer']['customername'] = $me['customername'];
		$_SESSION['orders']['customer']['customerruby'] = $me['customerruby'];
		$_SESSION['orders']['customer']['email'] = $me['email'];
		$_SESSION['orders']['customer']['tel'] = $me['tel'];
		$_SESSION['orders']['customer']['zipcode'] = $me['zipcode'];
		$_SESSION['orders']['customer']['addr0'] = $me['addr0'];
		$_SESSION['orders']['customer']['addr1'] = $me['addr1'];
		$_SESSION['orders']['customer']['addr2'] = $me['addr2'];
		$res = json_encode($me);
	} else {
		$res = json_encode($err);
	}
	header("Content-Type: text/javascript; charset=utf-8");
	echo $res;
}

// ログインしている顧客のデータ取得処理
if (isset($_REQUEST['getcustomer'])) {
	// ログイン状態のチェック
	$me = $_SESSION['me'];
	if (!$me) {
		$res = json_encode("");
	} else {
		// 届け先を取得し、セッションに置く
		$conn = new Conndb();
		//お届け先情報を設定
		$deli = $conn->getDeliveryList($me['id']);
		$_SESSION['me']['delivery'] = $deli;
		$res = json_encode($_SESSION['me']);
	}
	header("Content-Type: text/javascript; charset=utf-8");
	echo $res;
}

?>