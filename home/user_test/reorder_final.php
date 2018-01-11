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
		<meta name="Description" content="タカハマライフアートのマイページ、追加注文時の最終確認画面です。入力にお間違えがないか、ご確認お願いいたします。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>追加・再注文フォーム - 送信画面 | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/reorder_final.css" />
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
							<h1 id="user_name">追加・再注文フォーム</h1>
						</div>
					</div>
				</div>

			<section>
				<h2>お申し込みリストの内容を送信いたします。</h2>
				
				<table class="order_list">
					<thead>
						<tr class="tabl_ttl">
							<th>アイテム</th>
							<th>カラー</th>
							<th>サイズ</th>
							<th>枚数</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td rowspan="4">5001<br>5.6オンスハイクオリティーＴシャツ</td>
							<td rowspan="2">オレンジ</td>
							<td>S</td>
							<td>10枚</td>
						</tr>
						<tr>
							<td>M</td>
							<td>10枚</td>
						</tr>
						<tr>
							<td rowspan="2">グリーン</td>
							<td>S</td>
							<td>10枚</td>
						</tr>
					</tbody>
				</table>
				<div class="price_box">
					<p class="result">合計：￥10,4999 (税込)</p>
					<p class="one">1枚あたり：￥2,213 (税込)</p>
					<p><small><span>※</span>デザインや条件が変わると値段が変わる場合もございます。</small></p>
					<p><small> <span>※</span>割引は適用できません</small></p>
				</div>
				
				<table class="order_list cust">
					<thead>
						<tr class="tabl_ttl">
							<th scope="col" colspan="2">お客様情報</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>お名前</td>
							<td>高濱&nbsp;太郎&nbsp;様</td>
						</tr>
						<tr>
							<td>フリガナ</td>
							<td>タカハマ&nbsp;タロウ&nbsp;様</td>
						</tr>
						<tr>
							<td>電話番号</td>
							<td>03-5670-0787</td>
						</tr>
						<tr>
							<td>メールアドレス</td>
							<td>aaa@gmail.com</tr>
						<tr>
							<td>お届け希望日</td>
							<td><time timedate="2017-11-10">11月10日</time></td>
						</tr>
						<tr>
							<td>お届け先</td>
							<td>〒124-0024&nbsp;東京都葛飾区西新小岩3-14-26</td>
						</tr>
						<tr>
							<td>メッセージ</td>
							<td>プリント色を変更</td>
						</tr>
					</tbody>
				</table>
				</section>
				
				<a href="/user_test/thanks.php"><button type="button" class="btn btn-info">この内容で送信</button></a>
				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div>
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
