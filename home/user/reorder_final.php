<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';


// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('./login.php');
}

// 受注番号
$orderId = 0;
if(isset($_GET['oi'])){
	$orderId = $_GET['oi'];
} else {
	jump('./order_history.php');
}

// 会員情報
$member = new TlaMember($me['id']);
$rank = $member->getRankRatio();
$rankName = $member->getRankName();
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
		.delete-for-now {display:none;}
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
				<tbody id="order_item">
					
				</tbody>
			</table>
			
			<div class="delete-for-now">
			<div class="price_box">
				<p class="result">合計：￥<span id="total">0</span> (税込)</p>
				<p class="one">1枚あたり：￥<span id="perone">0</span> (税込)</p>
				<p id="discount_notice" class="fontred hidden"><span>※</span>大口注文割引きが適用されました。</p>
				<p><small><span>※</span>デザインや条件が変わると値段が変わる場合もございます。</small></p>
				<p><small> <span>※</span>割引は適用できません</small></p>
				</div></div>

			<table class="order_list cust">
				<thead>
					<tr class="tabl_ttl">
						<th scope="col" colspan="2">お客様情報</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>お名前</td>
						<td><span id="customer_name" data-number="<?php echo $me['customer_num']; ?>"><?php echo $me['customername']; ?></span>&nbsp;様</td>
					</tr>
					<tr>
						<td>フリガナ</td>
						<td><?php echo $me['customerruby']; ?>&nbsp;様</td>
					</tr>
					<tr>
						<td>電話番号</td>
						<td id="tel"><?php echo $me['tel']; ?></td>
					</tr>
					<tr>
						<td>メールアドレス</td>
						<td id="email"><?php echo $me['email']; ?></td>
					</tr>
					<tr>
						<td>お届け希望日</td>
						<td id="delidate"></td>
					</tr>
					<tr>
						<td>お届け時間帯</td>
						<td id="delitime"></td>
					</tr>
					<tr>
						<td>袋詰め</td>
						<td id="pack"></td>
					</tr>
					<tr>
						<td>お届け先</td>
						<td>〒<span id="zipcode" class="pr-3"></span><span id="addr"></span></td>
					</tr>
					<tr>
						<td>メッセージ</td>
						<td id="message"></td>
					</tr>
				</tbody>
			</table>
			</section>

			<button type="button" id="send" class="btn btn-info">この内容で送信</button>
			
			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./reorder_day.php?oi=<?php echo $orderId; ?>"><div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div></a>
			</div>
		</div>
	</div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script src="/common/js/api.js"></script>
	<script src="./js/reorder_final.js"></script>
</body>

</html>
