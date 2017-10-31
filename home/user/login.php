<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/user/php_libs/funcs.php';

// ログインしている場合はTOPへ
$me = checkLogin();
if($me){
	jump('history.php');
}

if(isset($_REQUEST['login']) && empty($_SESSION['me'])){
	$args = array($_REQUEST['email']);
	$conndb = new Conndb(_API_U);

	// エラーチェック
	if(empty($_REQUEST['email'])) {
		$err = 'メールアドレスを入力して下さい。';
	}else if(!$conndb->checkExistEmail($args)) {
		$err = "このメールアドレスは登録されていません。";
	}else if(empty($_REQUEST['pass'])) {
		$err = 'パスワードを入力して下さい。';
	}else{
		$args = array('email'=>$_REQUEST['email'], 'pass'=>$_REQUEST['pass']);
		$me = $conndb->getUser($args);
		if(!$me){
			$err = "メールアドレスかパスワードが認識できません。ご確認下さい。";
		}
	}

	if(empty($err)){
		// セッションハイジャック対策
		session_regenerate_id(true);

		// ログイン状態を保持
		if($_REQUEST['save']) {
			//setcoocie(session_name(), sesion_id(), time()+60*60*24*7);
		}

		$_SESSION['me'] = $me;
		jump('history.php');
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="早い！Tシャツでオリジナルを作成するならタカハマへ！タカハマライフアートのログイン画面です。メールアドレスとパスワードを入れてください。マイページからご注文履歴などをご確認することができます。ログインにする為のパスワードをお忘れの方はこちら。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>ログイン ｜ オリジナルTシャツが早い、タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/login.css" />

	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">

				<div class="toolbar">
					<div class="toolbar_inner clearfix">
						<h1>ログイン</h1>
					</div>
				</div>

				<div id="loginform_wrapper" class="section">
					<form class="form_m" name="loginform" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onsubmit="return false;">
						<div class="close_form"></div>
						<label>メールアドレス</label>
						<input type="text" value="" name="email" autofocus />
						<label>パスワード</label>
						<input type="password" value="" name="pass" />
						<div class="resend_pass"><a href="resend_pass.php">パスワードを忘れた方はこちらへ</a></div>
						<div class="btn_wrap">
							<div id="login_button"><p>ログイン</p></div>
							<p style="display:none;"><a href="register.php">ユーザー登録</a></p>
						</div>
						<input type="hidden" name="login" value="1">
						<input type="hidden" name="reg_site" value="1">
					</form>
				</div>

				<p class="txtttl"><span class="red_txt">※</span>メールアドレスを変更したい方はまずは弊社までご連絡ください。</p>
				<p class="txtttl">info@takahama428.com</p>
				<p class="txtttl">タカハマライフアートサポートチーム</p>
			</div>
		</div>


		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="./js/login.js"></script>
	</body>

</html>
