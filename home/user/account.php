<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('./login.php');
}

if($_SERVER['REQUEST_METHOD']!='POST'){
	setToken();
}else{
	chkToken();
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
	<title>お客様情報変更 | タカハマライフアート</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
<!--	<link rel="stylesheet" type="text/css" media="screen" href="//doozor.bitbucket.io/email/e-mailform.min.css">-->
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
				<div class="toolbar_inner">
					<div class="pagetitle">
						<h1>お客様情報変更</h1>
					</div>
				</div>
			</div>

			<form class="section e-mailer" name="prof" action="" method="post" enctype="multipart/form-data" onSubmit="return false;">
				<table class="form_table me" id="profile">
					<tfoot>
						<tr>
							<td colspan="2">
								<input type="hidden" name="id" class="update-args e-none" value="<?php echo $me['id'];?>">
								<input type="hidden" name="sendto" value="info@takahama428.com">
								<input type="hidden" name="subject" value="ユーザー情報の変更がありました">
								<input type="hidden" name="title" value="ユーザー情報の変更">
								<p>
									<button type="submit" class="ok_button">更新</button>
									<span class="cancel_button">Cancel</span>
								</p>
								
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th class="ttl_left">
								<h2>お客様情報</h2>
							</th>
						</tr>
						<tr>
							<th>お客様番号</th>
						</tr>
						<tr>
							<td>
								<p><?php echo $me['customer_num']; ?></p>
								<input type="hidden" name="顧客ID" value="<?php echo $me['customer_num']; ?>">
							</td>
						</tr>
						<tr>
							<th>お名前<span class="fontred">※</span></th>
						</tr>
						<tr>
							<td>
								<p><input type="text" name="customername" class="update-args" value="<?php echo $me['customername']; ?>" required></p>
								<ins class="err"></ins>
							</td>
						</tr>
						<tr>
							<th>フリガナ</th>
						</tr>
						<tr>
							<td>
								<p><input type="text" name="customerruby" class="update-args" value="<?php echo $me['customerruby']; ?>"></p>
							</td>
						</tr>
						<tr>
							<th>メールアドレス</th>
						</tr>
						<tr>
							<td>
								<p><?php echo $me['email']; ?></p>
							</td>
						</tr>
						<tr>
							<th>〒郵便番号<span class="fontred">※</span></th>
						</tr>
						<tr>
							<td>
								<p><input type="text" name="zipcode" class="zipcode update-args" value="<?php echo $me['zipcode']; ?>" onChange="AjaxZip3.zip2addr(this,'','addr0','addr1');" required></p>
								<ins class="err"></ins>
							</td>
						</tr>
						<tr>
							<th>都道府県<span class="fontred">※</span></th>
						</tr>
						<tr>
							<td>
								<p><input type="text" name="addr0" class="addr0 update-args" value="<?php echo $me['addr0']; ?>" maxlength="4" required></p>
								<ins class="err"></ins>
							</td>
						</tr>
						<tr>
							<th>住所１<span class="fontred">※</span></th>
						</tr>
						<tr>
							<td>
								<p><input type="text" name="addr1" class="addr1 update-args" value="<?php echo $me['addr1']; ?>" placeholder="市区町村番地" maxlength="56" required></p>
								<ins class="err"></ins>
							</td>
						</tr>
						<tr>
							<th>住所２</th>
						</tr>
						<tr>
							<td>
								<p><input type="text" name="addr2" class="addr1 update-args" value="<?php echo $me['addr2']; ?>" placeholder="建物名号室" maxlength="32"></p>
							</td>
						</tr>
						<tr>
							<th>電話番号<span class="fontred">※</span></th>
						</tr>
						<tr>
							<td>
								<p><input type="text" name="tel" class="update-args" value="<?php echo $me['tel']; ?>" required></p>
								<ins class="err"></ins>
							</td>
						</tr>
					</tbody>
				</table>
			</form>

			<form class="section e-mailer" name="pass" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
				<table class="form_table me" id="pass_table">
					<tfoot>
						<tr>
							<td colspan="2">
								<input type="hidden" name="id" class="update-args e-none" value="<?php echo $me['id'];?>">
								<input type="hidden" name="sendto" value="info@takahama428.com">
								<input type="hidden" name="subject" value="パスワードの変更がありました">
								<input type="hidden" name="title" value="パスワードの変更">
								
								<input type="hidden" name="顧客ID" value="<?php echo $me['customer_num']; ?>">
								<input type="hidden" name="顧客名" value="<?php echo $me['customername']; ?>">
								<p>
									<button type="submit" class="ok_button">更新</button>
									<span class="cancel_button">Cancel</span>
								</p>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th class="ttl_left">
								<h2>パスワードの変更</h2>
							</th>
						</tr>
						<tr>
							<th>パスワード</th>
						</tr>
						<tr>
							<td>
								<p><input type="password" name="password" class="update-args e-none" value="" required></p>
								<ins class="err"></ins>
							</td>
						</tr>
						<tr>
							<th>パスワード確認用</th>
						</tr>
						<tr>
							<td>
								<p><input type="password" name="passconf" class="e-none" value="" required></p>
								<ins class="err"></ins>
							</td>
						</tr>
					</tbody>
				</table>
			</form>

			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./my_menu.php">
					<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left"></i>戻る</div>
				</a>
			</div>
		</div>
	</div>
	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
	<script src="//doozor.bitbucket.io/email/e-mailform.min.js" defer></script>
	<script src="/common/js/api.js"></script>
	<script src="./js/account.js"></script>
</body>

</html>
