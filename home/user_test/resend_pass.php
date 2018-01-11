<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

if($_SERVER['REQUEST_METHOD']!='POST'){
	setToken();
}else{
	chkToken();

	if(isset($_POST['resend'], $_POST['token'], $_POST['email'])){
		$conndb = new Conndb(_API_U);

		$param['email'] = trim(mb_convert_kana($_POST['email'],"s", "utf-8"));
		$args = array($param['email']);

		if(empty($param['email'])){
			$err['email'] = 'メールアドレスを入力して下さい。';
		}else if(!isValidEmailFormat($param['email'])){
			$err['email'] = 'メールアドレスが正しくありません。';
		}else{
			$user = $conndb->checkExistEmail($args);
			$userid = $user['id'];
			if(!$userid) $err['email'] = 'メールアドレスのご登録がありません。';
		}

		if(empty($err)) jump('/design/designpost/transmit.php?ticket='.$_POST['token'].'&u='.$userid);
	}

}

?>
<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="Description" content="オリジナルのTシャツを作るならタカハマライフアートが早い！タカハマライフアートをご利用のお客様へ、パスワードをお忘れですか？対処方法についての、ご案内をいたします。登録されたメールアドレスを入力してください。送信ボタンをクリックした後、登録された連絡先メールアドレス宛にお送り致します。">
		<meta name="keywords" content="オリジナル,tシャツ,パスワード">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>パスワードを忘れた方 ｜ オリジナルTシャツが早い、タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_account.css" />

	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">

			<div class="contents">

				<div class="toolbar">
					<div class="toolbar_inner clearfix">
						<div class="pagetitle">
							<h1>パスワードを忘れた方</h1>
						</div>
					</div>
				</div>
				<div class="section">
					<form name="pass" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onsubmit="return false;">
						<table class="form_table me" id="pass_table">
							<p>登録されたメールアドレスに仮パスワードを送信いたします。</p>
							<tfoot>
								<tr>
									<td colspan="2">
										<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
										<input type="hidden" name="resend" value="1">
										<button type="button" class="btn btn-info send_pass">送信</button>
									</td>
								</tr>
							</tfoot>
							<tbody>
								<tr>
									<th class="center_posi">メールアドレス</th>
								</tr>
								<tr>
									<td class="center_posi"><input type="text" name="email" value="<?php echo $_POST['email'];?>"><br><ins class="err"> <?php echo $err['email']; ?></ins></td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="./js/resendpass.js"></script>
	</body>

</html>
