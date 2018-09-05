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
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="タカハマライフアートのマイページ、追加注文時の納期確認画面です。ご希望のお届け日に間違いがないか、ご確認お願いいたします。">
	<meta name="keywords" content="オリジナル,tシャツ,メンバー">
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>追加・再注文フォーム - お申し込みリスト | タカハマライフアート</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/flick/jquery-ui.css">
	<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/reorder_day.css" />
	
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
				<h2>お申し込みリスト</h2>

				<table class="order_list">
					<tbody id="order_item">
						<tr class="tabl_ttl">
							<td>アイテム</td>
							<td>カラー</td>
							<td>サイズ</td>
							<td>枚数</td>
						</tr>
					</tbody>
				</table>

				<div class="block">
					<table class="order_list cust">
						<thead>
							<tr class="tabl_ttl">
								<th scope="col" colspan="2">お客様情報</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>お名前</td>
								<td><?php echo $me['customername']; ?>&nbsp;様</td>
							</tr>
							<tr>
								<td>フリガナ</td>
								<td><?php echo $me['customerruby']; ?>&nbsp;様</td>
							</tr>
							<tr>
								<td>電話番号</td>
								<td><?php echo $me['tel']; ?></td>
							</tr>
							<tr>
								<td>メールアドレス</td>
								<td><?php echo $me['email']; ?>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="block">
					<div class="list_ttl">袋詰め</div>
					<div class="date_sel">
						<p class="note"><span class="red_mark">※</span>個別包装10枚以上で制作日数にプラス1日いただきます。</p>
						<div id="pack" class="form-group flexwrap">
							<div class="print_position">
								<label>
									<img src="/order/img/flow/sp_order_cart_packing_01.jpg">
									<p>まとめて包装 (<span class="red_txt">無料</span>)</p>
									<input type="radio" value="0" name="pack" checked>
								</label>
							</div>
							<div class="print_position">
								<label>
									<img src="/order/img/flow/sp_order_cart_packing_02.jpg">
									<p>個別包装 (<span class="red_txt">50円/1枚</span>)</p>
									<input type="radio" value="50" name="pack">
								</label>
							</div>
							<div class="print_position">
								<label>
									<img src="/order/img/flow/sp_order_cart_packing_03.jpg">
									<p>個別袋を同封 (<span class="red_txt">10円/1枚</span>)</p>
									<input type="radio" value="10" name="pack">
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="block">
					<div class="list_ttl">お届け希望日</div>
					<div class="date_sel">
						<div id="datepick"></div>
						<p id="express_info" class="note hidden"><span class="red_mark">※</span>特急料金がかかります。<em>(翌日仕上げ)</em></p>
						<input type="checkbox" value="2" name="transport" id="transport">
						<label for="transport">お届け先が、北海道、九州、沖縄、東京離島、島根隠岐郡のいずれかとなる場合はチェックして下さい。</label>
						<p class="time_sel">お時間帯の指定</p>
						<div class="form-group">
							<div class="btn-group">
								<select id="deliverytime" class="down_cond">
									<option value="0" selected="">指定なし</option>
									<option value="1">午前中</option>
									<option value="3">14:00-16:00</option>
									<option value="4">16:00-18:00</option>
									<option value="5">18:00-20:00</option>
									<option value="6">19:00-21:00</option>
								</select>
							</div>
						</div>
						<div class="deli_date">
							ご希望納期：<span>-</span>月<span>-</span>日
						</div>
					</div>
				</div>

				<div class="block">
					<table class="order_list cust">
						<thead>
							<tr class="tabl_ttl">
								<th scope="col" colspan="2">登録済みのご住所</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>郵便番号</td>
								<td>〒<span id="zipcode"><?php echo $me['zipcode']; ?></span></td>
							</tr>
							<tr>
								<td>住所1 (都道府県)</td>
								<td id="addr0"><?php echo $me['addr0']; ?></td>
							</tr>
							<tr>
								<td>住所2 (市区町村番地)</td>
								<td id="addr1"><?php echo $me['addr1'].$me['addr2']; ?></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="add_cha block">
					<button class="btn add_btn" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-angle-down mr-1" aria-hidden="true"></i>別のご住所に送りたい方</button>
				</div>

				<div class="collapse" id="collapseExample">

					<div class="cha_addr">
						<ul>
							<li>
								<p>〒<input type="text" name="zipcode" class="zipcode" id="deli_zipcode" onChange="AjaxZip3.zip2addr(this,'','addr0','addr1');" placeholder="郵便番号" /></p>
								<p><input type="text" name="addr0" id="deli_addr0" placeholder="都道府県" maxlength="4" /></p>
								<p><input type="text" name="addr1" id="deli_addr1" placeholder="葛飾区西新小岩1-23-456" maxlength="56"></p>
								<p><input type="text" name="addr2" id="deli_addr2" placeholder="マンション・ビル名" maxlength="32"></p>
							</li>
						</ul>
					</div>
				</div>

				<div class="block">
					<div class="list_ttl">メッセージ</div>
					<textarea class="mes" id="message" placeholder="プリント色を変更"></textarea>
				</div>

				<div class="price_box" id="estimation">
					<p style="margin: 0 auto; text-align:  center;font-weight: bold;">アイテムの料金で、プリント代は含まれておりません。<br>合計金額は内容を確認後、メールにてお見積もりをお送りいたします。</p>
					
					<div class="delete-for-now">
					<p class="total_p">合計：<span>0</span>円(税込)</p>
					<p class="solo_p">1枚あたり: <span>0</span>円(税込)</p>
					</div>
					
					<button class="btn btn-info" id="confirmation" data-order-id="<?php echo $orderId; ?>">お申し込み内容を確認</button>
					<p id="discount_notice" class="note hidden"><span class="red_mark">※</span>大口注文割引きが適用されました。</p>
					<p class="note"><span class="red_mark">※</span>条件によって値段が変わる場合がございます。</p>
				</div>

			</section>

			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./reorder_form.php?oi=<?php echo $orderId; ?>"><div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div></a>
			</div>
		</div>
	</div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script src="https://doozor.bitbucket.io/calendar/datepick_calendar.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
	<script src="//ajaxzip3.github.io/ajaxzip3.js"></script>
	<script src="/common/js/api.js"></script>
	<script src="./js/reorder_day.js"></script>
</body>

</html>
