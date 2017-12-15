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
		<meta name="Description" content="タカハマライフアートのマイページ、追加注文時のアイテムカテゴリー選択画面です。こちらを選んでいただくと、更に詳細なアイテム一覧画面が表示されます。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>追加・再注文フォーム  | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_reorder.css" />
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
					<div class="toolbar_inner">
						<div class="pagetitle">
							<h1>追加・再注文フォーム</h1>
						</div>
					</div>
				</div>

				<section>
					<h2>アイテムカテゴリを選択</h2>

						<div class="category_sel">
							<section>
								<div class="catego_box">
									<div class="item_top3 row">
										<div class="top3_inner col-12 col-sm-4">
											<p class="item_txt_o">Tシャツ</p>
											<img class="top3_o first_item" src="/common/img/global/item/sp_item_01.png" width="100%">
										</div>
										<div class="top3_inner col-6 col-sm-4">
											<p class="item_txt_o">ポロシャツ</p>
											<img class="top3_o" src="/common/img/global/item/sp_item_02.png" width="100%">
										</div>
										<div class="top3_inner col-6 col-sm-4">
											<p class="item_txt_o">タオル</p>
											<img class="top3_o" src="/common/img/global/item/sp_item_03.png" width="100%">
										</div>
									</div>
									<div class="item_other row">
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">スウェット</p>
											<img class="item_under" src="/common/img/global/item/sp_item_04.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">スポーツ</p>
											<img class="item_under" src="/common/img/global/item/sp_item_sports.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">長袖Tシャツ</p>
											<img class="item_under" src="/common/img/global/item/sp_item_longt.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">ブルゾン</p>
											<img class="item_under" src="/common/img/global/item/sp_item_05.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">レディース</p>
											<img class="item_under" src="/common/img/global/item/sp_item_lady.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">バッグ</p>
											<img class="item_under" src="/common/img/global/item/sp_item_bag.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">エプロン</p>
											<img class="item_under" src="/common/img/global/item/sp_item_07.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">ベビー</p>
											<img class="item_under" src="/common/img/global/item/sp_item_baby.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">つなぎ</p>
											<img class="item_under" src="/common/img/global/item/sp_item_08.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">記念品</p>
											<img class="item_under" src="/common/img/global/item/sp_item_11.png" width="100%">
										</div>
										<div class="other_inner col-6 col-sm-3">
											<p class="item_txt_o">キャップ</p>
											<img class="item_under" src="/common/img/global/item/sp_item_12.png" width="100%">
										</div>
									</div>
								</div>
							</section>
						</div>

					</section>

					<div class="transition_wrap d-flex justify-content-between align-items-center">
						<div class="step_prev hoverable waves-effect">
							<i class="fa fa-chevron-left mr-1"></i>戻る
						</div>
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
