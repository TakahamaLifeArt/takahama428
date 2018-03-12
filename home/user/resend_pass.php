<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

setToken();
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
					<form name="pass" class="e-mailer" action="" method="post" onsubmit="return false;">
						<table class="form_table me" id="pass_table">
							<p>登録されたメールアドレスに仮パスワードを送信いたします。</p>
							<tfoot>
								<tr>
									<td colspan="2">
										<input type="hidden" name="token" class="e-none" id="token" value="<?php echo $_SESSION['token']; ?>">
										<input type="hidden" name="subject" value="パスワードを再発行いたしました">
										<input type="hidden" name="title" value="パスワード再発行">
										<textarea name="summary" hidden>いつもご利用いただき、誠にありがとうございます。新しいパスワードを発行いたしました。</textarea>
										<button type="button" class="btn btn-info send_pass" id="validation">送信</button>
										<button type="submit" id="sendmail" hidden></button>
									</td>
								</tr>
							</tfoot>
							<tbody>
								<tr>
									<th class="center_posi">メールアドレス</th>
								</tr>
								<tr>
									<td class="center_posi">
										<p><input type="text" name="sendto" id="email" value="<?php echo $_POST['email'];?>"></p>
										<ins class="err"> <?php echo $err['email']; ?></ins>
										<label hidden>パスワード</label>
										<input type="text" name="newpass" id="newpass" value="" hidden>
									</td>
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
		<script src="//doozor.bitbucket.io/email/e-mailform.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
		<script type="text/javascript" src="/common/js/api.js"></script>
		<script type="text/javascript" src="./js/resendpass.js"></script>
	</body>

</html>
