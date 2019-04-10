<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
//require_once dirname(__FILE__).'/UserMail.php';
require_once dirname(__FILE__).'/TlaMember.php';

function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

/**
 * ログイン
 * @param {array} args {'email', 'pass'}
 * @return ログイン失敗の場合{'error'=>エラーメッセージ}
 */
function loginTo($args) {
	try {
		if (!is_array($args)) throw new Exception();
		
		if (empty($args['email']) || empty($args['pass'])) {
			$res = json_encode(array('error' => 'メールアドレスとパスワードは必須です'));
		} else {
			$headers = [
				'X-TLA-Access-Token:'._ACCESS_TOKEN,
				'Origin:'._DOMAIN
			];
			$http = new HTTP('https://takahamalifeart.com/v3/users/'.rawurlencode($args['email']).'/'.$args['pass']);
			$res = $http->request('GET', [], $headers);
			$data = json_decode($res, true);
			if (array_key_exists('error', $data)) {
				$mbErrorMessage = array(
					'This email has not been registered' => 'このメールアドレスは登録されていません',
					'Enter your password' => 'パスワードを入力して下さい。',
					'Not registered yet' => 'メールアドレス（'.$args['email'].'）かパスワードをご確認下さい'
				);
				$res = json_encode(array('error' => $mbErrorMessage[$data['error']] . $data['error']));
			} else {
				$_SESSION['me'] = $data;
				$res = '';
			}
		}
	} catch(Exception $e) {
		$res = json_encode(array('error' => 'システムエラーです'));
	}
	return $res;
}

/**
 * ログイン情報を更新
 * ログインしていない場合は無効
 * @param {int} args ユーザーID
 * @return 更新できた場合は顧客情報を返す
 */
function resetLoginMember($args) {
	try {
		if (empty($args)) throw new Exception();
		if ($_SESSION['me']['id']!=$args) throw new Exception();

		$headers = [
			'X-TLA-Access-Token:'._ACCESS_TOKEN,
			'Origin:'._DOMAIN
		];
		$http = new HTTP('https://takahamalifeart.com/v3/users/'.$args);
		$res = $http->request('GET', [], $headers);
		if (empty($res)) throw new Exception();
		$_SESSION['me'] = json_decode($res, true);
	} catch(Exception $e) {
		$res = '';
	}
	return $res;
}

/**
 * ログイン状態のチェック
 */
function checkLogin() {
//	if( empty($_SESSION['me']) || empty($_SESSION['me']['tla_customer_id']) ){
	if( empty($_SESSION['me']) ){
		$res = false;
	}else{
		$res = $_SESSION['me'];
	}
	return $res;
}

/**
 * 指定先へリダイレクト
 */
function jump($s) {
	header("Location: ".$s);
	exit;
}

/**
 * CSRF対策
 */
function setToken() {
	$token = sha1(uniqid(mt_rand(), true));
	$_SESSION['token'] = $token;
}

function chkToken() {
	if($_SESSION['token']!=$_POST['token']) {
		jump(_DOMAIN);
		exit;
	}
}

/**
 * メールアドレス（addr_spec）チェック
 */
function isValidEmailFormat($email, $supportPeculiarFormat = true){
    $wsp              = '[\x20\x09]'; // 半角空白と水平タブ
    $vchar            = '[\x21-\x7e]'; // ASCIIコードの ! から ~ まで
    $quoted_pair      = "\\\\(?:{$vchar}|{$wsp})"; // \ を前につけた quoted-pair 形式なら \ と " が使用できる
    $qtext            = '[\x21\x23-\x5b\x5d-\x7e]'; // $vchar から \ と " を抜いたもの。\x22 は " , \x5c は \
    $qcontent         = "(?:{$qtext}|{$quoted_pair})"; // quoted-string 形式の条件分岐
    $quoted_string    = "\"{$qcontent}+\""; // " で 囲まれた quoted-string 形式。
    $atext            = '[a-zA-Z0-9!#$%&\'*+\-\/\=?^_`{|}~]'; // 通常、メールアドレスに使用出来る文字
    $dot_atom         = "{$atext}+(?:[.]{$atext}+)*"; // ドットが連続しない RFC 準拠形式をループ展開で構築
    $local_part       = "(?:{$dot_atom}|{$quoted_string})"; // local-part は dot-atom 形式 または quoted-string 形式のどちらか
    // ドメイン部分の判定強化
    $alnum            = '[a-zA-Z0-9]'; // domain は先頭英数字
    $sub_domain       = "{$alnum}+(?:-{$alnum}+)*"; // hyphenated alnum をループ展開で構築
    $domain           = "(?:{$sub_domain})+(?:[.](?:{$sub_domain})+)+"; // ハイフンとドットが連続しないように $sub_domain をループ展開
    $addr_spec        = "{$local_part}[@]{$domain}"; // 合成
    // 昔の携帯電話メールアドレス用
    $dot_atom_loose   = "{$atext}+(?:[.]|{$atext})*"; // 連続したドットと @ の直前のドットを許容する
    $local_part_loose = $dot_atom_loose; // 昔の携帯電話メールアドレスで quoted-string 形式なんてあるわけない。たぶん。
    $addr_spec_loose  = "{$local_part_loose}[@]{$domain}"; // 合成
    // 昔の携帯電話メールアドレスの形式をサポートするかで使う正規表現を変える
    if($supportPeculiarFormat){
        $regexp = $addr_spec_loose;
    }else{
        $regexp = $addr_spec;
    }
    // \A は常に文字列の先頭にマッチする。\z は常に文字列の末尾にマッチする。
    if(preg_match("/\A{$regexp}\z/", $email)){
        return true;
    }else{
        return false;
    }
}



/*
*	ユーザー更新
*	@args	POSTデータ
*
*	return	error text
*
function update_user($args){
	$conndb = new Conndb(_API_U);
	
	// エラーチェック
	$err = array();
	if(empty($args['uname'])){
		$err['uname'] = 'ユーザーネームを入力して下さい。';
	}
	
	if(empty($err)){
		// DBに更新
		if(isset($args['profile'], $args['userid'])){
			$res = $conndb->updateUser($args);
		}
		if($res){
			if(isset($args['profile'], $args['userid'])){
				$u = $conndb->getUserList($args['userid']);
				$_SESSION['me'] = array_merge($_SESSION['me'],array(
					'id'=>$u[0]['id'],
				  'customername'=>$u[0]['customername'],
				  'email'=>$u[0]['email'],
					'customerruby'=>$u[0]['customerruby']
				  //'number'=>$u[0]['number']
				));
			}
		}else{
			return $res;
		}
	}
	
	return $err;
}
*/


/*
*	パスワード変更
*	@args	['userid','pass']
*
*	return	error text
*
function update_pass($args){
	// trim
	foreach($args as $key=>&$val){
		$val = trim(mb_convert_kana($val,"s", "utf-8"));
	}
	
	// エラーチェック
	if(empty($args['pass'])){
		$err['pass'] = 'パスワードを入力して下さい。';
	}else if(!preg_match("/^[a-zA-Z0-9]+$/", $args['pass'])){
		$err['pass'] = '使用できる文字は半角英数のみです。';
	}else if(strlen($args['pass'])<8 || strlen($args['pass'])>32){
		$err['pass'] = '8文字以上32文字以内で指定してください。';
	}else if($args['pass']!=$args['passconf']){
		$err['passconf'] = 'パスワードの確認が合っていません。';
	}
	
	
	if(empty($err)){
		$conndb = new Conndb(_API_U);
		$res = $conndb->updatePass($args);
		if(!$res) $err['pass'] = '通信エラー';
	}
	
	return $err;
}
*/

/*
*	お客様住所・電話変更
*	@args	['userid','zipcode','addr0','addr1','addr2']
*
*	return	error text
*
function update_addr($args){
	// trim
	foreach($args as $key=>&$val){
		$val = trim($val);
	}
	// エラーチェック
	if(empty($args['zipcode'])){
		$err['zipcode'] = '郵便番号を入力してください。';
	}else if(empty($args['addr0'])){
		$err['addr0'] = '都道府県を入力してください。';
	}else if(empty($args['addr1'])){
		$err['addr1'] = '市/区を入力してください。';
	}else if(empty($args['addr2'])){
		$err['addr2'] = 'アドレスを入力してください。';
	}else if(!preg_match("/^[0-9]{3}[-]?[0-9]{0,4}$/", $args['zipcode'])){
		$err['zipcode'] = '郵便番号をチェックしてください。';
	}else if(empty($args['tel'])){
		$err['tel'] = '電話番号を入力してください。';
	}
else if(!preg_match("(0\d{1,4}-|\(0\d{1,4}\) ?)?\d{1,4}-\d{4}", $args['tel'])){
		$err['tel'] = '電話番号をチェックしてください。';
	}

	
	if(empty($err)){
		$conndb = new Conndb(_API_U);
		$res = $conndb->updateAddr($args);
		if(!$res) $err['addr'] = '通信エラー';
		$u = $conndb->getUserList($args['userid']);
		$_SESSION['me'] = array_merge($_SESSION['me'],array(
			'zipcode'=>$u[0]['zipcode'],
		  'addr0'=>$u[0]['addr0'],
		  'addr1'=>$u[0]['addr1'],
			'addr2'=>$u[0]['addr2'],
		  'tel'=>$u[0]['tel']
		));
		
	}

	
	return $err;
}
*/

/*
*	お届け先変更
*	@args	['userid','zipcode','addr0','addr1','addr2']
*
*	return	error text
*
function update_deli($args, $delId){
	// trim
	foreach($args as $key=>&$val){
		$val = trim($val);
	}
	// エラーチェック
	if(empty($args['organization'])){
		$err[$delId.'_organization'] = 'お届き先を入力してください。';
	}else if(empty($args['delizipcode'])){
		$err[$delId.'_delizipcode'] = '郵便番号を入力してください。';
	}else if(empty($args['deliaddr0'])){
		$err[$delId.'_deliaddr0'] = '都道府県を入力してください。';
	}else if(empty($args['deliaddr1'])){
		$err[$delId.'_deliaddr1'] = '住所1を入力してください。';
	}else if(empty($args['deliaddr2'])){
		$err[$delId.'_deliaddr2'] = '住所2を入力してください。';
	}else if(!preg_match("/^[0-9]{3}[-]?[0-9]{0,4}$/", $args['delizipcode'])){
		$err[$delId.'_delizipcode'] = '郵便番号をチェックしてください。';
	}else if(empty($args['delitel'])){
		$err[$delId.'_delitel'] = '電話番号を入力してください。';
	}

else if(!preg_match("(0\d{1,4}-|\(0\d{1,4}\) ?)?\d{1,4}-\d{4}", $args['tel'])){
		$err['tel'] = '電話番号をチェックしてください。';
	}

	
	if(empty($err)){
		$conndb = new Conndb(_API_U);
		$res = $conndb->updateDeli($args);
		if(!$res){
			$err[$delId.'_deliaddr'] = '通信エラー';
		}else{
			$u = $conndb->getUserList($args['userid']);
			$_SESSION['me']['delivery'] = array_merge($_SESSION['me'],array(
				'organization'=>$u[0]['organization'],
				'delizipcode'=>$u[0]['delizipcode'],
			  'deliaddr0'=>$u[0]['deliaddr0'],
			  'deliaddr1'=>$u[0]['deliaddr1'],
				'deliaddr2'=>$u[0]['deliaddr2'],
				'deliaddr3'=>$u[0]['deliaddr3'],
				'deliaddr4'=>$u[0]['deliaddr4'],
			  'delitel'=>$u[0]['delitel']
			));
		}
	}
	
	return $err;
}
*/
?>