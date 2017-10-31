<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';


function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

/*
*	指定先へリダイレクト
*/
function jump($s) {
	header("Location: ".$s);
	exit;
}

/*
*	CSRF対策
*/
function setToken() {
	$token = sha1(uniqid(mt_rand(), true));
	$_SESSION['token'] = $token;
}

function chkToken() {
	if($_SESSION['token']!=$_POST['token']) {
		jump('/');
		exit;
	}
}

/*
*	メールアドレス（addr_spec）チェック
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
*	パスワード変更
*	@args	['userid','pass']
*
*	return	error text
*/
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
	
	/*
	if(empty($err)){
		$conndb = new Conndb();
		$res = $conndb->updatePass($args);
		if(!$res) $err['pass'] = '通信エラー';
	}
	*/
	return $err;
}
?>