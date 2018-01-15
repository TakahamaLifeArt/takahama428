<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/package/mail/Mailer.php';
use package\mail\Mailer;

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
jump('login.php');
}

$conndb = new Conndb(_API_U);



// ユーザー情報を設定
$u = $conndb->getUserList($me['id']);
$username = $me['customername'];
$userkana = $me['customerruby'];
$email = $u[0]['email'];

//お届け先情報を再度取得
$deli = $conndb->getDeli($me['id']);
?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="タカハマライフアートのマイページ、イメージ画像詳細の画面です。こちらのページから、オリジナルTシャツのイメージ画像を拡大して確認することができます。追加注文もこちらの画面から可能です。">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>イメージ画像 詳細 | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_img.css" />
	</head>
		<style>
			.lightbox {
				display: none;
			}

		</style>
	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">

				<div class="toolbar">
					<div class="toolbar_inner">
						<div class="pagetitle">
							<h1>イメージ画像</h1>
						</div>
					</div>
				</div>
				<div class="num_data">
					<p>注文番号 : <span>36422</span></p>
					<p>注文日 : <span>2017/09/12</span></p>
				</div>
				<div class="flex_inner_2"  data-featherlight="#fl3">
					<img src="/user_test/img/my_img.png" width="100%">
				</div>
				<div class="lightbox" id="fl3">
					<div class="modal-content">
						<div class="modal_window">
							<img src="/user_test/img/my_img.png" width="100%">
						</div>
					</div>
				</div>

				<button type="button" class="btn_or btn">ダウンロード</button>
				<a href="/user_test/reorder.php"><button type="button" class="btn btn-info line-hi">注文情報を確認する<br>追加・再注文はこちら</button></a>

				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div>
				</div>
			</div>

			<a href="" class="next_btn">マイページTOPへ戻る</a>

		</div>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script type="text/javascript" src="./js/account.js"></script>
		<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	</body>

	</html>
