<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if($me){
	jump('./my_menu.php');
}

if(isset($_POST['login']) && empty($_SESSION['me'])){
	// ログイン
	$res = loginTo(array('email'=>$_POST['email'], 'pass'=>$_POST['pass']));
	if(empty($res)) {
		jump('./my_menu.php');
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="タカハマライフアートのマイページ、ログイン画面です。メールアドレスとパスワードを入れてログインすることで過去のオリジナルTシャツ注文、イメージ画像の確認など便利な機能を使うことができます。ログインにする為のパスワードを忘れても安心！すぐに再発行できます。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー,マイページ">
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
						<label>メールアドレス</label>
						<input type="text" value="<?php echo $_POST['email']; ?>" name="email" autofocus>
						<label>パスワード</label>
						<input type="password" value="" name="pass">
						<div class="btn_wrap">
							<div id="login_button"><p><i class="fa fa-sign-in" aria-hidden="true"></i>ログイン</p></div>
						</div>
						<input type="hidden" name="login" value="1">
					</form>
					<div class="resend_pass"><a href="resend_pass.php"><i class="fa fa-question-circle" aria-hidden="true"></i>パスワードを忘れた方はこちらへ</a></div>
				</div>

			</div>
		</div>


		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script>
		var _ERROR = '<?php echo $res; ?>';
		</script>
		<script type="text/javascript" src="./js/login.js"></script>
	</body>

</html>
