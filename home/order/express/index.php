<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/orders.php';
$order = new Orders();
$fin = $order->getDelidate();

//$ua=$_SERVER['HTTP_USER_AGENT'];
//if((strpos($ua,' iPhone')!==false)||(strpos($ua,' iPod')!==false)||(strpos($ua,' Android')!==false)) {
//	$txt_SP02 = '<p><a href="tel:0120130428" style="margin-left:0px;font-size:60px;font-weight:bold;">0120-130-428</a></p>';
//	$txt_SP02 .= '<p class="note" style="margin-left:95px;">受付時間：平日10:00-18:00</p>';
//}else{
//	$txt_SP02 = '<img src="img/phoneno.png" width="456" height="104" alt="TEL:0120-130-428 受付時間：平日10:00-18:00"><br>';
//}

// category selector
$data = $order->categoryList();
$category_selector = '<select id="category_selector" name="category">';
$category_selector .= '<option value="" selected="selected">-</option>';
for($i=0; $i<count($data); $i++){
	$categoryName = $data[$i]['name'];
	$category_selector .= '<option value="'.$data[$i]['code'].'" rel="'.$data[$i]['id'].'"';
	$category_selector .= '>'.$categoryName.'</option>';
}
$category_selector .= '</select>';

$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
$_SESSION['ticket'] = $ticket;
?>
<!DOCTYPE html>
<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="早い！当日発送！お客様に細かく対応した４つのプランでオリジナルＴシャツを発送致します。何日発送かがすぐにご確認できるので便利！さらにお急ぎの方は電話いただくか、専用フォームでお問い合わせいただけますと、すぐに対応いたします。ご連絡お待ちしております。" />
		<meta name="keywords" content="オリジナル,tシャツ,早い,当日,即日" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>お急ぎの方へ【即日発送】 ｜ オリジナルTシャツが早い、タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/flick/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="/common/css/jquery.ui.css" media="screen">
		<link rel="stylesheet" type="text/css" href="./css/express.css" media="screen">
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">
				<ul class="pan hidden-sm-down">
					<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
					<li>お急ぎの方へ</li>
				</ul>

				<h1>お急ぎの方へ</h1>

				<div id="delivery_date"><img src="img/express_top.jpg" alt="最短明日お届け" width="100%" class="upimg"><img src="img/sp_express_top.jpg" alt="最短明日お届け" width="100%" class="dwnimg">
				</div>

				<div id="delivery_date_wrapper">
					<div id="delivery_date1">
						<p class="texorg">オリジナルTシャツ業界でNo.1の早さ！</p>
						<h2>最速！当日特急プラン</h2>
						<div class="blockgrp">
							<div class="blocktxt">
								<p class="text">お電話での確認まで完了した場合のお届け日</p>
								<section class="round1">
									<div class="block_sq1">
										<div class="block_sq2">
											<div class="block_sq">
												<p class="textb">お届け日</p>
											</div>
											<div class="block_sq2">
												<table class="bk_table" style="width:130px">
													<tr id="result_date4">
														<td class="dt">
															<p></p>
														</td>
														<td style="width: 15px">/</td>
														<td class="dt">
															<p></p>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</section>
								<p class="text">締切：<span class="red_new">12:00</span></p>
								<p class="text"><span class="red_new">※</span>営業日は月～金曜日です。（土日祝を除く）</p>
							</div>
							<div class="blocktxt">
								<p class="texorg">今日注文して明日着られる！</p>
								<p class="txt2">当日特急プランとは？</p>
								<p class="textc">12時までの注文確定で当日商品を発送する大好評のプランです。お届け先が東京近郊の場合、当日商品を受け取ることも可能です。</p>
								<p class="textc"><span class="red_new">※</span>当日受け取る場合はバイク便または来社引取になります。詳細はスタッフまでお問い合わせください。</p>
							</div>
							<div class="blocktxt">
								<img src="img/sp_hurry_map.jpg" alt="最短お届けエリア" width="100%">
							</div>
						</div>
						
						<div class="blockgrp2_1">
						<p class="texorg">当日特急プラン対応アイテム！</p>
						<p class="txt2">厳選した人気アイテムで対応！</p>
						</div>
						<div class="blockgrp2">
							<div class="blocktxt2">
								<div class="imggrp">
									<p class="text">085-CVT (S～XLサイズ)</p><img src="img/sp_hurry_tshirt.png" alt="085-CVT" width="100%">
									<p class="text">ホワイトorブラック</p>
								</div>
							</div>
							<div class="blocktxt3">
								<div class="imggrp">
									<p class="text">522-FT<br>(フリーサイズ)</p><img src="img/sp_hurry_towel.png" alt="522-FT" width="100%">
									<p class="text">ホワイト</p>
								</div>
							</div>
						</div>
						<div class="blockgrp3">
							<div class="blocktxt4">
								<p class="txt2 textflo">条件</p>
								<p class="txt2">1. 12時までに注文確定!</p>
								<ul>
									<li>
										<p class="textc">データ入稿が完了している</p>
									</li>
									<li>
										<p class="textc">お電話で注文確定している。</p>
									</li>
								</ul>
							</div>
							<div class="blocktxt4">
								<p class="txt3">2. 注文確定後、すぐ入金！</p>
								<p class="textc">商品の発送は入金確認後になりますので、お早めのご入金をお願い致します。</p>
								<p class="textc"><span class="red_new">※</span>詳しくはスタッフまでお問合わせください。</p>
							</div>
							<div class="blocktxt4">
								<p class="txt3">3.料金</p>
								<p class="textc">お客様のご希望お届け日に迅速に対応する為に特急料金がかかります。</p>
								<p class="text">通常料金：<span class="circleLineDouble">&yen;</span><span class="texorg2">&times;2</span></p>
							</div>
						</div>
						<div class="btnarea">
							<a href="#overtime" class="method_button">当日特急のお問合わせはこちら</a>
						</div>
					</div>
					<div id="delivery_date1">
						<p class="texorg">「早く届けてほしい」に答える！</p>
						<h2>他のプラン</h2>
						<div class="time">
							<p class="textc">お電話での確認まで完了した場合のお届け日</p>
							<div class="blockgrp6">
								<div class="block1">
									<section class="round2">
										<div class="block_sq">
											<p class="text">翌日プラン</p>
										</div>
										<div class="block_sq1">
											<div class="block_sq2">
												<table class="bk_table">
													<tr id="result_date3">
														<td class="dt">
															<p></p>
														</td>
														<td style="width: 15px">/</td>
														<td class="dt">
															<p></p>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</section>
								</div>
								<div class="block1">
									<section class="round3">
										<div class="block_sq">
											<p class="text">2日プラン</p>
										</div>
										<div class="block_sq1">
											<div class="block_sq2">
												<div class="block_sq2">
													<table class="bk_table">
														<tr id="result_date2">
															<td class="dt">
																<p></p>
															</td>
															<td style="width: 15px">/</td>
															<td class="dt">
																<p></p>
															</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="block1">
									<section class="round4">
										<div class="block_sq">
											<p class="text">通常3日プラン</p>
										</div>
										<div class="block_sq1">
											<div class="block_sq2">
												<table class="bk_table">
													<tr id="result_date">
														<td class="dt">
															<p class="date">
																<?php echo $fin['Month'];?>
															</p>
														</td>
														<td style="width: 15px">/</td>
														<td class="dt">
															<p class="date">
																<?php echo $fin['Day'];?>
															</p>
														</td>
													</tr>
												</table>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
						<div class="blockgrp">
							<div class="blocktxt">
								<p class="texorg3">今日注文して明後日着られる！</p>
								<p class="text">翌日プラン</p>
								<p class="textc">注文から1営業日でオリジナルウェアを仕上げてお客様にお届けするプランです。お好きな商品を注文できます。</p>
								<div class="blockgrp3">
									<p class="text">締切：<span class="texbl">13:00</span>&emsp;&emsp;&emsp;通常料金：<span class="circleLineDouble">&yen;</span><span class="texorg2">&times;1.5</span></p>
								</div>
							</div>
							<div class="blocktxt">
								<p class="texorg3">今日注文して3日後日着られる！</p>
								<p class="text">2日プラン</p>
								<p class="textc">注文から2営業日でオリジナルウェアを仕上げてお客様にお届けするプランです。お好きな商品を注文できます。</p>
								<div class="blockgrp3">
									<p class="text">締切：<span class="texbl">13:00</span>&emsp;&emsp;&emsp;通常料金：<span class="circleLineDouble">&yen;</span><span class="texorg2">&times;1.3</span></p>
								</div>
							</div>
							<div class="blocktxt">
								<p class="texorg3">今日注文して4日後着られる！</p>
								<p class="text">通常3日プラン</p>
								<p class="textc">弊社なら通常のプランでも3営業日でオリジナルウェアを仕上げてお客様にお届けすることができます。お好きな商品を注文できます。</p>
								<div class="blockgrp3 lastt">
									<p class="text">締切：<span class="texbl">13:00</span></p>
								</div>
							</div>
						</div>
						<p class="textc"><span class="red_new">※</span>通常料金はアイテム＋プリント代です。&emsp;<span class="red_new">※</span>営業日は月～金曜日です。（土日祝を除く）</p>
					</div>
					<div id="delivery_date1">
						<p class="texorg">お見積りご希望の方へ！</p>
						<h2>お見積りについて</h2>
						<p class="textc">お見積もりには以下の情報が必須となります。</p>
						<div class="blockgrp4">
							<div class="blocktxt5">
								<p class="text">希望納期</p>
								<img src="img/sp_hurry_link_01.jpg" alt="納期" width="100%">
							</div>
							<div class="blocktxt5">
								<p class="text">アイテム</p>
								<img src="img/sp_hurry_link_02.png" alt="納期" width="100%">
							</div>
							<div class="blocktxt5">
								<p class="text">デザインデータ</p>
								<img src="img/sp_hurry_link_03.png" alt="納期" width="100%">
							</div>
							<div class="blocktxt5">
								<p class="text">お客様情報</p>
								<img src="img/sp_hurry_link_04.jpg" alt="納期" width="100%">
							</div>
						</div>
					</div>
					<div id="delivery_date1">
						<p class="texorg">タカハマライフアートが早い理由！</p>
						<h2>なぜ早くお届けできるのか？</h2>
						<div class="blockgrp">
							<div class="blocktxt">
								<p class="texorg3">1. 日本一のプリントスピード</p>
								<p class="textc">東京に2つの自社工場があり自社一貫で全ての加工を行っているので、業界でも類を見ない最速の即納対応が可能です。</p>
							</div>
							<div class="blocktxt">
								<p class="texorg3">2. スピード受注</p>
								<p class="textc">電話対応とメールのレスポンスの早さを誇るお客様対応日本一の受注チームが、お客様と同じ目線に立ちサポート致します。</p>
							</div>
							<div class="blocktxt">
								<p class="texorg3">3. スピードに特化したWEBサイト</p>
								<p class="textc">お届け日早わかりシステム、10秒で簡単に見積もりができるシステム等、お急ぎのお客様が何を求めているのかを追求した結果がWEBサイトに反映されています。</p>
							</div>
						</div>
					</div>
					<div class="order_content">
						<div class="order_txt">
							<div class="order_img hidden-sm-down"><img src="/delivery/img/deli/go_pattern.jpg" width="100%;"></div>
							<div class="order_p">初めてでもカンタン！<br>迅速丁寧に対応いたします。</div>
							<div class="order_img hidden-sm-down"><img src="/delivery/img/deli/go_pattern.jpg" width="100%;"></div>
						</div>
						<div class="order_bubble">
							<a href="/order/" class="order_btn"><img src="/delivery/img/deli/sp_go_icon.png" width="20%;">お申し込み</a>
							<img class="bubble_img" src="/delivery/img/deli/sp_go_min.png" width="100%;"></div>
					</div>
					<div id="overtime">
						<h2>お急ぎの方専用フォーム</h2>
						<div id="overtime_form">
							<div id="formwrapper">
								<p><span class="red_new">※は必須入力です。</span><span class="blue_new"><a href="/contact/faxorderform.pdf" target="_blank">FAX注文用紙（PDF）はこちら &#62;</a></span></p>
								<div id="formcontent">
									<form name="express_form" method="post" action="/contact/transmit.php?req=express" enctype="multipart/form-data" onsubmit="return false;">
										<table id="express_table">
											<tbody>
												<tr>
													<th>お名前</th>
													<td class="red_new">※</td>
													<td><input name="customername" type="text" value="" class="customername"></td>
												</tr>
												<tr>
													<th>フリガナ</th>
													<td class="red_new">※</td>
													<td><input name="ruby" type="text" value="" class="ruby"></td>
												</tr>
												<tr>
													<th>電話番号</th>
													<td class="red_new">※</td>
													<td><input name="tel" type="text" class="forPhone" value="" /></td>
												</tr>
												<tr>
													<th>メールアドレス</th>
													<td class="red_new">※</td>
													<td><input name="email" type="text" class="email" value="" /></td>
												</tr>
												<tr>
													<th>ご希望納期</th>
													<td class="red_new">※</td>
													<td><input id="datepicker" type="text" size="14" name="deliveryday" class="forDate" value="" /></td>
												</tr>
												<tr>
													<th>商品カテゴリー</th>
													<td class="point">&nbsp;</td>
													<td>
														<?php echo $category_selector;?>
													</td>
												</tr>
												<tr>
													<th>枚数</th>
													<td class="point">&nbsp;</td>
													<td><input name="amount" type="text" class="number forNum" value="0" />&nbsp;枚</td>
												</tr>
												<tr>
													<th>ご住所</th>
													<td class="red_new">※</td>
													<td>
														<p>〒<input name="zipcode" id="zipcode" class="forZip" type="text" onkeyup="AjaxZip3.zip2addr(this,'','addr0','addr1');" /></p>
														<p><input name="addr0" id="addr0" type="text" placeholder="都道府県" maxlength="4" /></p>
														<p><input name="addr1" id="addr1" type="text" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" class="restrict" /></p>
														<p><input name="addr2" id="addr2" type="text" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></p>
													</td>
												</tr>
												<tr>
													<th>デザインファイル</th>
													<td>&nbsp;</td>
													<td><input type="file" name="attachfile[]" class="file" /></td>
												</tr>
												<tr>
													<th>&nbsp;</th>
													<td>&nbsp;</td>
													<td><input type="button" class="add_attachfile" value="別の添付ファイルを追加"></td>
												</tr>
												<tr>
													<th>プリント情報</th>
													<td class="point">&nbsp;</td>
													<td>
														<span class="small">例)前１ヶ所１色、後右袖１ヶ所２色</span>
														<textarea name="printinfo" class="printinfo" cols="40" rows="5"></textarea>
													</td>
												</tr>
												<tr>
													<th>メッセージ</th>
													<td class="point">&nbsp;</td>
													<td><textarea name="message" class="message" cols="40" rows="5"></textarea></td>
												</tr>
											</tbody>
										</table>

										<input type="hidden" name="ticket" value="<?php echo $ticket; ?>" />
										<input type="hidden" name="title" value="express" />

										<div id="button_holder">
											<p id="sendbtn">送&nbsp;&nbsp;信</p>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<p><img class="bubble_img2" src="img/hurry_tel.jpg" width="300px;"></p>
				</div>
			</div>
		</div>
		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
		<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script type="text/javascript" src="./js/express.js"></script>
	</body>

	</html>
