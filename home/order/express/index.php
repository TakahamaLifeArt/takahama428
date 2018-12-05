<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
$conn = new Conndb();
$fin = json_decode($conn->delidate(0, array(1,2,3,4)), true);

$_version = time();
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
	<title>お急ぎの方へ【即日発送】 ｜ オリジナルTシャツ【タカハマライフアート】</title>
	<link rel="shortcut icon" href="/icon/favicon.ico">
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" href="/common/css/printposition_responsive.css" media="screen" />
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

			<div id="delivery_date"><img src="img/express_top.jpg" alt="最短当日届きます！お急ぎの方へ" width="100%" class="upimg"><img src="img/sp_express_top.jpg" alt="最短当日届きます！お急ぎの方へ" width="100%" class="dwnimg">
			</div>

			<div id="delivery_date_wrapper">
				<div class="delivery_date1">
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
														<p>
															<?php echo $fin[0]['Month'];?>
														</p>
													</td>
													<td style="width: 15px">/</td>
													<td class="dt">
														<p>
															<?php echo $fin[0]['Day'];?>
														</p>
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
						<h2>厳選した人気アイテムで即日対応！</h2>
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
							<p class="textc"><span class="red_new">※</span>詳しくはスタッフまでお問い合わせください。</p>
						</div>
						<div class="blocktxt4">
							<p class="txt3">3.料金</p>
							<p class="textc">お客様のご希望お届け日に迅速に対応する為に特急料金がかかります。</p>
							<p class="text">通常料金：<span class="circleLineDouble">&yen;</span><span class="texorg2">&times;2</span></p>
						</div>
					</div>
					<div class="btnarea">
						<a href="#overtime" class="method_button">当日特急のお問い合わせはこちら</a>
					</div>
				</div>
				<div class="delivery_date1">
					<p class="texorg">「早く届けてほしい」に答える！</p>
					<h2>お客様のご都合に合わせた3つのプラン</h2>
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
														<p>
															<?php echo $fin[1]['Month'];?>
														</p>
													</td>
													<td style="width: 15px">/</td>
													<td class="dt">
														<p>
															<?php echo $fin[1]['Day'];?>
														</p>
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
															<p>
																<?php echo $fin[2]['Month'];?>
															</p>
														</td>
														<td style="width: 15px">/</td>
														<td class="dt">
															<p>
																<?php echo $fin[2]['Day'];?>
															</p>
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
															<?php echo $fin[3]['Month'];?>
														</p>
													</td>
													<td style="width: 15px">/</td>
													<td class="dt">
														<p class="date">
															<?php echo $fin[3]['Day'];?>
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
				<div class="delivery_date1">
					<p class="texorg">お見積りご希望の方へ！</p>
					<h2>オリジナルTシャツの見積もりについて</h2>
					<p class="textc">お見積もりには以下の情報が必須となります。</p>
					<div class="blockgrp4">
						<div class="blocktxt5">
							<p class="text">希望納期</p>
							<img src="img/sp_hurry_link_01.jpg" alt="納期" width="100%" alt="納期カレンダー">
						</div>
						<div class="blocktxt5">
							<p class="text">アイテム</p>
							<img src="img/sp_hurry_link_02.png" alt="納期" width="100%" alt="Tシャツとポロシャツとパーカー">
						</div>
						<div class="blocktxt5">
							<p class="text">デザインデータ</p>
							<img src="img/sp_hurry_link_03.png" alt="納期" width="100%" alt="イラストレーターとフォトショップと手描きイラストと画像データ">
						</div>
						<div class="blocktxt5">
							<p class="text">お客様情報</p>
							<img src="img/sp_hurry_link_04.jpg" alt="納期" width="100%" alt="PCで作業するお客様">
						</div>
					</div>
				</div>
				<div class="delivery_date1">
					<p class="texorg">タカハマライフアートが早い理由！</p>
					<h2>即日対応ができる理由</h2>
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
						<a href="/order/order_entrace.php" class="order_btn"><img src="/delivery/img/deli/sp_go_icon.png" width="20%;">お申し込み</a>
						<img class="bubble_img" src="/delivery/img/deli/sp_go_min.png" width="100%;">
					</div>
				</div>

				<div class="inner">
					<h2 id="overtime">当日特急専用　お申し込みフォーム</h2>
					<div class="bdrline">
						<p class="lbl">■データを添付や詳細を下記にて記載して、お問い合わせ下さい。</p>
						<p class="point">「※」 は必須です。</p>
						<form name="express_form" class="e-mailer" method="post">
							<table id="enq_table">
								<tbody>
									<tr>
										<th><label>お名前</label></th>
										<td class="point">※</td>
										<td>
											<input name="customername" type="text" value="" required>
										</td>
									</tr>
									<tr>
										<th><label>フリガナ</label></th>
										<td class="point">※</td>
										<td>
											<input name="ruby" type="text" value="" required>
										</td>
									</tr>
									<tr>
										<th><label>ご住所</label></th>
										<td class="point">※</td>
										<td>
											<p><input name="zipcode" id="zipcode" class="forZip p-zip" type="text" required></p>
											<p><input name="addr0" id="addr0" class="p-region" type="text" placeholder="都道府県" maxlength="4" required></p>
											<p><input name="addr1" id="addr1" class="p-locality p-street-address" type="text" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" required></p>
											<p><input name="addr2" id="addr2" type="text" placeholder="文字数は全角16文字、半角32文字です" maxlength="32"></p>
										</td>
									</tr>
									<tr>
										<th><label>メールアドレス</label></th>
										<td class="point">※</td>
										<td>
											<input name="email" type="email" required>
										</td>
									</tr>
									<tr>
										<th><label>電話番号</label></th>
										<td class="point">※</td>
										<td>
											<input name="tel" type="tel" required>
										</td>
									</tr>
									<tr>
										<th><label>ご希望納期</label></th>
										<td class="point">※</td>
										<td><input type="text" size="14" name="deliveryday" id="datepick" value="" required></td>
									</tr>
									<tr>
										<th><label>メッセージ</label></th>
										<td>&nbsp;</td>
										<td>
											<textarea name="message" id="message" cols="40" rows="7"></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="3" class="comment">デザインデータなどのファイルを送信される方は、こちらから添付できます。</td>
									</tr>
									<tr>
										<th><label>添付ファイル</label></th>
										<td>&nbsp;</td>
										<td><input type="file" name="attachfile" multiple></td>
									</tr>
<!--                                    new-->
                                    
                                    <tr>
										<th><label>ファイル転送<br>サービス</label></th>
										<td>&nbsp;</td>
										<td>
										
											<div class="fileupload-buttonbar">
												<div class="">
													<!-- The fileinput-button span is used to style the file input field as button -->
                                                    
                                                    
<!--
													<span class="btn btn-new btn-success fileinput-button fade in">
														<i class="fa fa-plus" aria-hidden="true"></i>
														<span>ファイルを選択...</span>
														<input type="file" name="files[]" class="e-none" multiple>
													</span>
-->
													<!--
<button type="submit" class="btn btn-primary start fade e-none" hidden>
<i class="fa fa-cloud-upload" aria-hidden="true"></i>
<span>アップロード</span>
</button>
-->

													<!-- The global file processing state -->
													<span class="fileupload-process"></span>
												</div>
												<p class="note">ファイルアップロードできない場合は、下記のファイル転送サービスをご利用ください。</p>
												<div id="upload_link" class="modal_style_line">
													<!--												<i class="fa fa-question-circle mr-1" aria-hidden="true"></i>-->

													<svg aria-hidden="true" data-prefix="fas" style="width: 13px;" data-icon="question-circle" class="svg-inline--fa fa-question-circle fa-w-16 mr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zM262.655 90c-54.497 0-89.255 22.957-116.549 63.758-3.536 5.286-2.353 12.415 2.715 16.258l34.699 26.31c5.205 3.947 12.621 3.008 16.665-2.122 17.864-22.658 30.113-35.797 57.303-35.797 20.429 0 45.698 13.148 45.698 32.958 0 14.976-12.363 22.667-32.534 33.976C247.128 238.528 216 254.941 216 296v4c0 6.627 5.373 12 12 12h56c6.627 0 12-5.373 12-12v-1.333c0-28.462 83.186-29.647 83.186-106.667 0-58.002-60.165-102-116.531-102zM256 338c-25.365 0-46 20.635-46 46 0 25.364 20.635 46 46 46s46-20.636 46-46c0-25.365-20.635-46-46-46z"></path></svg>

													ファイル転送サービス
												</div>



											</div>




										
										</td>
									</tr>
                                    
<!--                                    new-->
                                    
								</tbody>
							</table>

							<p class="lbl">■<label>カラーとサイズ</label></p>
							<p class="select_cs">サイズの中に必要な枚数を選択ください(複数可)</p>

							<table id="size_table">
								<thead>
									<tr>
										<th>色</th>
										<th>S (&yen;480)</th>
										<th>M (&yen;480)</th>
										<th>L (&yen;480)</th>
										<th>XL (&yen;480)</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<img src="<?php echo _IMG_PSS?>/items/list/t-shirts/085-cvt/085-cvt_001.jpg" width="100" alt="085-cvtヘビーウェイトTシャツの白">
											<input type="hidden" name="part-whitetshirts" value="Tシャツ白">
											<textarea hidden></textarea>
										</td>
										<td><input type="number" value="0" min="0" step="1" name="S" class="e-none">枚</td>
										<td><input type="number" value="0" min="0" step="1" name="M" class="e-none">枚</td>
										<td><input type="number" value="0" min="0" step="1" name="L" class="e-none">枚</td>
										<td><input type="number" value="0" min="0" step="1" name="XL" class="e-none">枚</td>
									</tr>
									<tr>
										<th>色</th>
										<th>S (&yen;560)</th>
										<th>M (&yen;560)</th>
										<th>L (&yen;560)</th>
										<th>XL (&yen;560)</th>
									</tr>
									<tr>
										<td>
											<img src="<?php echo _IMG_PSS?>/items/list/t-shirts/085-cvt/085-cvt_005.jpg" width="100" data-color="ブラック" alt="085-cvtヘビーウェイトTシャツの黒">
											<input type="hidden" name="part-whitetshirts" value="Tシャツ黒">
											<textarea hidden></textarea>
										</td>
										<td><input type="number" value="0" min="0" step="1" name="S" class="e-none">枚</td>
										<td><input type="number" value="0" min="0" step="1" name="M" class="e-none">枚</td>
										<td><input type="number" value="0" min="0" step="1" name="L" class="e-none">枚</td>
										<td><input type="number" value="0" min="0" step="1" name="XL" class="e-none">枚</td>
									</tr>
									<tr>
										<th>色</th>
										<th>Free (&yen;380)</th>
										<th colspan="3"></th>
									</tr>
									<tr>
										<td>
											<img src="<?php echo _IMG_PSS?>/items/list/towel/522-ft/522-ft_001.jpg" width="100" data-color="ホワイト" alt="522-FTフェイスタオルの白">
											<input type="hidden" name="part-whitetshirts" value="タオル白">
											<textarea hidden></textarea>
										</td>
										<td><input type="number" value="0" min="0" step="1" name="Free" class="e-none">枚</td>
										<td colspan="3"></td>
									</tr>
								</tbody>
							</table>

							<p class="lbl">■<label>プリントする位置とデザインの色数を指定してください</label></p>
							<div class="noprint_wrap">
								<p><label><input type="checkbox" name="noprint" id="noprint" value="1"> プリントなしで購入する</label></p>
								<p class="note"><span>※</span>プリントなしの場合1割増しになります。</p>
							</div>
							<div id="pos_wrap"></div>
							<div id="pos_info"><textarea hidden></textarea></div>
							<div class="button_area">
								<p class="msg">入力内容をご確認の上、よろしければ[ 送信 ]ボタンを押してください。</p>
<!--								<div id="validation" class="order_btn">送信</div>-->
								
								<input type="hidden" name="sendto" value="<?php echo _INFO_EMAIL;?>">
								<input type="hidden" name="subject" value="当日特急プラン">
								<input type="hidden" name="title" value="当日特急プラン">
								<input type="hidden" name="replyto" value="email">
								<input type="hidden" name="replyhead" value="このたびは、タカハマライフアートをご利用いただき誠にありがとうございます。">
								<button type="submit" id="sendmail" class="order_btn">送信</button>
							</div>
						</form>
					</div>
				</div>
				<div>
					<p class="ptxt4">手描きのイラストや、デザインをプリントアウトしたものなどの場合は、下記の宛先までお送りください。</p>
					<p class="ptxt3"><a href="/contact/faxorderform.pdf" target="_blank">手描き注文書(PDF)はこちら<img src="./img/img_01.png" width="40px" height="40px"></a></p>
					<p class="ptxt">FAX: 03-5670-0730 (受付時間：24時間受信)</p>
					<p class="ptxt4"><span class="kome">※</span>家庭用は画質が低い為、複合機やコンビニからをオススメします。</p>
				</div>
			</div>
		</div>
	</div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>
	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

    
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
    <script src="./js/dialog.js"></script>
	<script src="https://doozor.bitbucket.io/email/e-mailform.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
	<script src="https://doozor.bitbucket.io/calendar/datepick_calendar.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
	<script type="text/javascript" src="./js/express.js?v=<?php echo $_version;?>"></script>
    

</body>

</html>
