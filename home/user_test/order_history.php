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
		<meta name="Description" content="タカハマライフアートのマイページ、ご注文履歴画面です。こちらから、お客様がご注文された、オリジナルTシャツの履歴を確認することができます。また、追加注文もこちらのページから移動することができます。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>ご注文履歴 | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/order_history.css" />
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
							<h1>ご注文履歴</h1>
						</div>
					</div>
				</div>
				<div class="button_fld">
					<div class="button_gr">
						<a href="order_detail.php" class="btn rd_sq_button">
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
						<a href="reorder.php" class="btn rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/my_img.png" class="btn_img" width="100px">
							</div>
							<div class="grdil">
								<div class="txtarea">
									<p class="txt_btn">注文番号：36422</p>
									<p class="txt_btn">注文日：2017-09-18</p>
								</div>
								<div class="btnfld">
									<button type="button" class="btn btn-info">追加・再注文</button>
								</div>
							</div>
						</a>
					</div>
					<div class="button_gr">
						<a href="reorder.php" class="btn rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/my_img.png" class="btn_img" width="100px">
							</div>
							<div class="grdil">
								<div class="txtarea">
									<p class="txt_btn">注文番号：36422</p>
									<p class="txt_btn">注文日：2017-09-18</p>
								</div>
								<div class="btnfld">
									<button type="button" class="btn btn-info">追加・再注文</button>
								</div>
							</div>
						</a>
					</div>
					<div class="button_gr">
						<a href="reorder.php" class="btn rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/my_img.png" class="btn_img" width="100px">
							</div>
							<div class="grdil">
								<div class="txtarea">
									<p class="txt_btn">注文番号：36422</p>
									<p class="txt_btn">注文日：2017-09-18</p>
								</div>
								<div class="btnfld">
									<button type="button" class="btn btn-info">追加・再注文</button>
								</div>
							</div>
						</a>
					</div>
					<div class="button_gr">
						<a href="reorder.php" class="btn rd_sq_button">
							<div class="imgblk">
								<img src="/user_test/img/my_img.png" class="btn_img" width="100px">
							</div>
							<div class="grdil">
								<div class="txtarea">
									<p class="txt_btn">注文番号：36422</p>
									<p class="txt_btn">注文日：2017-09-18</p>
								</div>
								<div class="btnfld">
									<button type="button" class="btn btn-info">追加・再注文</button>
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="bottom_btn">
					<button class="btn add_btn" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-angle-down mr-1" aria-hidden="true"></i>もっと見る</button>
				</div>
				<div class="collapse" id="collapseExample">
					<div class="button_fld">
						<div class="button_gr">
							<a href="order_detail.php" class="btn rd_sq_button">
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
							<a href="reorder.php" class="btn rd_sq_button">
								<div class="imgblk">
									<img src="/user_test/img/my_img.png" class="btn_img" width="100px">
								</div>
								<div class="grdil">
									<div class="txtarea">
										<p class="txt_btn">注文番号：36422</p>
										<p class="txt_btn">注文日：2017-09-18</p>
									</div>
									<div class="btnfld">
										<button type="button" class="btn btn-info">追加・再注文</button>
									</div>
								</div>
							</a>
						</div>
						<div class="button_gr">
							<a href="reorder.php" class="btn rd_sq_button">
								<div class="imgblk">
									<img src="/user_test/img/my_img.png" class="btn_img" width="100px">
								</div>
								<div class="grdil">
									<div class="txtarea">
										<p class="txt_btn">注文番号：36422</p>
										<p class="txt_btn">注文日：2017-09-18</p>
									</div>
									<div class="btnfld">
										<button type="button" class="btn btn-info">追加・再注文</button>
									</div>
								</div>
							</a>
						</div>
						<div class="button_gr">
							<a href="reorder.php" class="btn rd_sq_button">
								<div class="imgblk">
									<img src="/user_test/img/my_img.png" class="btn_img" width="100px">
								</div>
								<div class="grdil">
									<div class="txtarea">
										<p class="txt_btn">注文番号：36422</p>
										<p class="txt_btn">注文日：2017-09-18</p>
									</div>
									<div class="btnfld">
										<button type="button" class="btn btn-info">追加・再注文</button>
									</div>
								</div>
							</a>
						</div>
						<div class="button_gr">
							<a href="reorder.php" class="btn rd_sq_button">
								<div class="imgblk">
									<img src="/user_test/img/my_img.png" class="btn_img" width="100px">
								</div>
								<div class="grdil">
									<div class="txtarea">
										<p class="txt_btn">注文番号：36422</p>
										<p class="txt_btn">注文日：2017-09-18</p>
									</div>
									<div class="btnfld">
										<button type="button" class="btn btn-info">追加・再注文</button>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
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
		<script type="text/javascript" src="./js/account.js"></script>
		<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	</body>

	</html>
