<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';
// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('./login.php');
}

$username = $me['customername'];

// 会員情報
$member = new TlaMember($me['id']);
$rank = $member->getRankRatio();
$rankName = $member->getRankName();
$rankCode = $member->getRankCode();
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="タカハマライフアートのマイページ、メインメニュー画面です。こちらから、確認したい事項にすぐに移動できるので、安心して、オリジナルTシャツの追加注文などができます。是非ご活用ください。。">
	<meta name="keywords" content="オリジナル,tシャツ,メンバー">
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>マイメニュー | タカハマライフアート</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/my_menu.css" />
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
						<h1 id="user_name">
							<?php echo $username;?>様のマイページ</h1>
						<ul>
							<li class="btn-group"><div class="modal_style" data-featherlight="#fl1"><i class="fa fa-question-circle mr-1" aria-hidden="true"></i>マイページとは</div></li>
						</ul>

						<div class="lightbox" id="fl1">

							<div class="modal-content">
								<div class="modal_window">
									<h2>マイページとは</h2>
									<p class="round_btn"><i class="fa fa-user" aria-hidden="true"></i></p>
									<p>タカハマライフアートでご注文いただいたお客様専用の会員ページです。</p>
									<p class="min_space">お客様専用のマイページがご利用いただけるようになります。</p>
									<p class="min_space">マイページで、オリジナルTシャツの作成が便利で安心してできるようになります。</p>
									<h3 class="syousai">マイページで出来ること</h3>
									<div class="sq_bdr">
										<p class="min_space">製作状況がリアルタイムで確認することができます。</p>
										<p class="min_space">今までの注文履歴・注文明細を確認することができ、追加注文が簡単にできます。</p>
										<p class="min_space">請求書・納品書をいつでもダウンロードできます。</p>
										<p class="min_space">クレジットカード決済手続きができます。</p>
									</div>
									<p><span class="fontred">※</span>決済完了後、システム反映までに時間がかかる場合がございます。予めご了承ください。</p>
									<button class="featherlight-close-button featherlight-close" aria-label="Close">閉じる</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="user_rank">
				<?php
				if ($rank>0) {
					echo '<p class="rankname"><img src="/user/img/sp_customer_rank_'.$rankCode.'.png" class="rank_img" width="60px">お客様は<a href="customer.php"><span class="bodtxt">'.$rankName.'会員</span></a>です</p>';
					echo '<p class="txt_btn"><a href="customer.php"><span class="txtbl">お客様会員情報はこちら</span></a></p>';
				}
				?>
			</div>
			<div class="button_fld">
				<div class="button_lef btn">
					<a href="order_history.php" class="rd_sq_button">
						<p class="imgblk"><img src="/user/img/sp_m_top_icon_history.png" class="btn_img" width="60px"></p>
						<p class="txt_btn">ご注文履歴</p>
						<p class="txt_btn"><span>追加・再注文はこちら</span></p>
					</a>
				</div>
				<div class="button_rgt btn">
					<a href="order_list.php" class="rd_sq_button">
						<p class="imgblk"><img src="/user/img/sp_m_top_icon_pay.png" class="btn_img" width="60px"></p>
						<p class="txt_btn">お支払い・制作状況</p>
					</a>
				</div>
			</div>
				<div class="button_fld">
					<div class="button_lef btn">
					<a href="account.php" class="rd_sq_button">
						<p class="imgblk"><img src="/user/img/sp_m_top_icon_change.png" class="btn_img" width="60px"></p>
						<p class="txt_btn">お客様情報変更</p>
					</a>
				</div>
					<div class="button_rgt btn">
					<a href="my_img.php" class="rd_sq_button">
						<p class="imgblk"><img src="/user/img/sp_m_top_icon_image.png" class="btn_img" width="60px"></p>
						<p class="txt_btn">イメージ画像</p>
					</a>
				</div>
				</div>

			</div>
		<div class="btnfld">
			<a href="./logout.php" class="next_btn"><i class="fa fa-sign-out" aria-hidden="true"></i>ログアウト</a>
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
