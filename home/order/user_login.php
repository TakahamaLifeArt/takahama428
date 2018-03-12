<?php
/**
 * ユーザーログイン処理
 * -----
 * ログイン
 * ログイン状態の確認
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/http.php';

if (isset($_REQUEST['login'])) {
	// ログイン
	$args = array($_REQUEST['email']);
	
	if (empty($_REQUEST['email']) || empty($_REQUEST['pass'])) {
		$res = json_encode(array('error' => 'メールアドレスとパスワードは必須です'));
	} else {
		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		$http = new HTTP('https://takahamalifeart.com/v3/users/'.$_REQUEST['email'].'/'.$_REQUEST['pass']);
		$res = $http->request('GET', [], $headers);
		$data = json_decode($res, true);
		if (array_key_exists('error', $data)) {
			$mbErrorMessage = array(
				'This email has not been registered' => 'このメールアドレスは登録されていません',
				'Enter your password' => 'パスワードを入力して下さい。',
				'Not registered yet' => 'メールアドレス（'.$_REQUEST['email'].'）かパスワードをご確認下さい'
			);
			$res = json_encode(array('error' => $mbErrorMessage[$data['error']]));
		} else {
			$_SESSION['me'] = $data;
		}
	}
	header("Content-Type: text/javascript; charset=utf-8");
	echo $res;
	
} else if (isset($_REQUEST['getcustomer'])) {
	// ログイン状態の確認
	$me = $_SESSION['me'];
	if (!$me) {
		$res = json_encode("");
	} else {
		$res = json_encode($_SESSION['me']);
	}
	header("Content-Type: text/javascript; charset=utf-8");
	echo $res;
	
}

?>