<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('./login.php');
}

// 会員情報
$member = new TlaMember($me['id']);
$rank = $member->getRankRatio();
$rankName = $member->getRankName();
$rankCode = $member->getRankCode();
$addition = $member->priceForBeNext();
$nextRank = $member->nextRankName();
$number = $me['cstprefix'].sprintf('%06d', $me['number']);
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
	<title>会員特典 | タカハマライフアート</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/customer.css" />
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
						<h1 id="user_name">会員特典</h1>
					</div>
				</div>
			</div>

			<div class="user_rank">
				<div class="user_mark">
					<?php
					if ($rank>0) {
						echo '<img src="/user/img/sp_customer_rank_'.$rankCode.'.png" class="rank_image" width="100%">';
					}
					?>
				</div>
				<div class="user_det">
				<?php
					if ($rank>0) {
						echo '<div class="total_fee">合計金額から</div>';
						echo '<div class="off">'.$rank.'％OFF</div>';
						echo '<p class="costomer_rank"> お客様は<a href=""><span class="bodtxt">'.$rankName.'会員</span></a>です</p>';
					}
					if ($rank!=7) {
						echo '<p>あと'.number_format($addition).'円分のご購入で'.$nextRank.'会員にランクアップ</p>';
					}
					echo '<div class="modal_style" data-featherlight="#fl1"><i class="fa fa-question-circle mr-1" aria-hidden="true"></i>お得な会員特典を見る</div>';
					?>
				</div>
			</div>
			<div class="lightbox" id="fl1">
				<div class="modal-content">
					<div class="modal_window">
						<h2>会員ランク特典一覧</h2>
						<div class="sq_bdr">
							<div class="min_img"><img src="./img/sp_customer_rank_gold.png" width="100%"></div>
							<div class="min_space">
								<h3 class="gold">ゴールド会員</h3>
								<p>ご注文金額から7%OFF！全て送料無料！</p>
								<p>ご購入金額、合計30万円以上のお客様限定！</p>
							</div>
						</div>
						<div class="bdrline"></div>
						<div class="sq_bdr">
							<div class="min_img"><img src="./img/sp_customer_rank_silver.png" width="100%"></div>
							<div class="min_space">
								<h3 class="silver">シルバー会員</h3>
								<p>ご注文金額から5%OFF！全て送料無料！</p>
								<p>ご購入金額、合計15万円以上のお客様限定！</p>
							</div>
						</div>
						<div class="bdrline"></div>
						<div class="sq_bdr">
							<div class="min_img"><img src="./img/sp_customer_rank_bronze.png" width="100%"></div>
							<div class="min_space">
								<h3 class="bronze">ブロンズ会員</h3>
								<p>ご注文金額から3%OFF！全て送料無料！</p>
								<p>ご購入金額、合計8万円以上のお客様限定！</p>
							</div>
						</div>
						<div class="bdrline"></div>
						<button class="featherlight-close-button featherlight-close" aria-label="Close">閉じる</button>
					</div>
				</div>
			</div>
			<table border="1" rules="rows" class="table table-bordered">
				<h2>会員情報</h2>
				<thead>
					<tr>
						<th scope="col" colspan="2">お客様情報</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>お客様番号</th>
						<td><?php echo $me['customer_num']; ?></td>
					</tr>
					<tr>
						<th>お名前</th>
						<td><?php echo $me['customername']; ?></td>
					</tr>
					<tr>
						<th>フリガナ</th>
						<td><?php echo $me['customerruby']; ?></td>
					</tr>
					<tr>
						<th>電話番号</th>
						<td><?php echo $me['tel']; ?></td>
					</tr>
					<tr>
						<th>メールアドレス</th>
						<td><?php echo $me['email']; ?></td>
					</tr>
					<tr>
						<th>パスワード</th>
						<td>＊＊＊＊＊＊＊＊</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-bordered">
				<thead>
					<tr>
						<th scope="col" colspan="2">住所</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>〒郵便番号</th>
						<td><?php echo $me['zipcode']; ?></td>
					</tr>
					<tr>
						<th>住所１（都道府県）</th>
						<td><?php echo $me['addr0']; ?></td>
					</tr>
					<tr>
						<th>住所２（市区町村番地）</th>
						<td><?php echo $me['addr1'].$me['addr2']; ?></td>
					</tr>
				</tbody>
			</table>
			<a class="btn_or btn" href="./account.php">編集する</a>
			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./my_menu.php">
					<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div>
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
	<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
</body>

</html>
