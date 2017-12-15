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
		<meta name="Description" content="タカハマライフアートのマイページ、制作状況一覧画面です。こちらから、現在制作進行中のオリジナルTシャツの状況を確認することができます。宅配便の荷物追跡サービスとも連携しているので、いつ届くか確認したいお客様は是非ご活用ください。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>お支払い・製作状況 | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/order_list.css" />
		<style>
			.lightbox {
				display: none;
			}

		</style>

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
							<h1>お支払い・制作状況</h1>
						</div>
					</div>
				</div>
				<div class="button_fld">
					<div class="button_gr">
						<a href="" class="rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/sp_history_image_noimage.jpg" class="btn_img" width="100px">
							</div>
							<div class="grdil">
							<div class="txtarea">
								<p class="txt_btn">注文番号：36422</p>
								<p class="txt_btn">注文日：2017-09-18</p>
							</div>
							<div class="txtgrp">
								<p class="txt_btn"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>未決済</p>
								<p class="txt_btn"><img src="/user_test/img/sp_m_history_progress_icon.png" class="btn_img" width="30px">デザイン制作中</p>
							</div>
							</div>
						</a>
					</div>
					<div class="button_gr">
						<a href="" class="rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/sp_history_image_noimage.jpg" class="btn_img" width="100px">
							</div>
							<div class="grdil">
							<div class="txtarea">
								<p class="txt_btn">注文番号：36422</p>
								<p class="txt_btn">注文日：2017-09-18</p>
							</div>
							<div class="txtgrp">
								<p class="txt_btn"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>未決済</p>
								<p class="txt_btn"><img src="/user_test/img/sp_m_history_progress_icon.png" class="btn_img" width="30px">ザイン制作中</p>
							</div>
							</div>
						</a>
					</div>
					<div class="button_gr">
						<a href="" class="rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/sp_history_image_noimage.jpg" class="btn_img" width="100px">
							</div>
							<div class="grdil">
							<div class="txtarea">
								<p class="txt_btn">注文番号：36422</p>
								<p class="txt_btn">注文日：2017-09-18</p>
							</div>
							<div class="txtgrp">
								<p class="txt_btn"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>未決済</p>
								<p class="txt_btn"><img src="/user_test/img/sp_m_history_progress_icon.png" class="btn_img" width="30px">デザイン制作中</p>
							</div>
							</div>
						</a>
					</div>
					<div class="button_gr">
						<a href="" class="rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/sp_history_image_noimage.jpg" class="btn_img" width="100px">
							</div>
							<div class="grdil">
							<div class="txtarea">
								<p class="txt_btn">注文番号：36422</p>
								<p class="txt_btn">注文日：2017-09-18</p>
							</div>
							<div class="txtgrp">
								<p class="txt_btn"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>未決済</p>
								<p class="txt_btn"><img src="/user_test/img/sp_m_history_progress_icon.png" class="btn_img" width="30px">デザイン制作中</p>
							</div>
							</div>
						</a>
					</div>
					<div class="button_gr">
						<a href="" class="rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/sp_history_image_noimage.jpg" class="btn_img" width="100px">
							</div>
							<div class="grdil">
							<div class="txtarea">
								<p class="txt_btn">注文番号：36422</p>
								<p class="txt_btn">注文日：2017-09-18</p>
							</div>
							<div class="txtgrp">
								<p class="txt_btn"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>未決済</p>
								<p class="txt_btn"><img src="/user_test/img/sp_m_history_progress_icon.png" class="btn_img" width="30px">デザイン制作中</p>
							</div>
							</div>
						</a>
					</div>
					<a href="" class="next_btn"><i class="fa fa-angle-down" aria-hidden="true"></i>もっと見る</a>
			</div>
				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left"></i>戻る</div>
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
				<script type="text/javascript" src="./js/account.js"></script>
				<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	</body>

	</html>
