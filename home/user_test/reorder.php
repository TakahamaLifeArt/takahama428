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
		<title>ご注文内容 | タカハマライフアート</title>
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
							<h1>ご注文内容</h1>
						</div>
					</div>
				</div>
				<div class="num_data">
					<p>注文番号 : <span>36422</span></p>
					<p>注文日 : <span>2017/09/12</span></p>
				</div>

				<div class="final_confir">
					<div class="item_info_final">
						<table class="final_detail">
							<tbody>
								<tr class="tabl_ttl">
									<td>アイテム/カラー</td>
									<td>サイズ</td>
									<td>単価</td>
									<td>枚数</td>
									<td>金額</td>
								</tr>
								<tr>
									<td rowspan="3">
										<div class="item_name_color">
											<p>5001</p>
											<p>5.6オンスハイクオリティーTシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>オレンジ</p>
										</div>
									</td>
									<td>160</td>
									<td>5,000</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>S</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>M</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>

								<tr>
									<td rowspan="3">
										<div class="item_name_color">
											<p>5001</p>
											<p>5.6オンスハイクオリティーTシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>ブルー</p>
										</div>
									</td>
									<td>160</td>
									<td>5,000</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>S</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>M</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr class="tabl_ttl">
									<td>アイテム/カラー</td>
									<td>サイズ</td>
									<td>単価</td>
									<td>枚数</td>
									<td>金額</td>
								</tr>
								<tr>
									<td rowspan="3">
										<div class="item_name_color">
											<p>5910</p>
											<p>4.1オンス ドライアスレチックポロシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>オレンジ</p>
										</div>
									</td>
									<td>160</td>
									<td>5,000</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>S</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>M</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr class="tabl_ttl">
									<td>アイテム/カラー</td>
									<td>サイズ</td>
									<td>単価</td>
									<td>枚数</td>
									<td>金額</td>
								</tr>
								<tr>
									<td rowspan="3">
										<div class="item_name_color">
											<p>5010</p>
											<p>5.6オンスロングスリーブTシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>オレンジ</p>
										</div>
									</td>
									<td>160</td>
									<td>5,000</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>S</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>M</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
							</tbody>
						</table>

					</div>

					<div class="item_info_final_2">
						<table class="print_info_final">
							<tbody>
								<tr class="tabl_ttl_2">
									<td class="print_total">プリント代</td>
									<td class="print_total_p">88,888円</td>
								</tr>
								<tr class="tabl_txt">
									<td class="p_posi">前</td>
									<td>2色</td>
									<td>シルクスクリーン(通常サイズ)</td>
									<td></td>
								</tr>
								<tr class="tabl_txt">
									<td class="p_posi">右胸</td>
									<td>2色</td>
									<td>シルクスクリーン(ジャンボサイズ)</td>
									<td></td>
								</tr>
								<tr class="tabl_txt">
									<td class="p_posi">左胸</td>
									<td>2色</td>
									<td>シルクスクリーン(ジャンボサイズ)</td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="subtotal">
						<p>小計<span class="inter">30</span>枚<span class="inter_2">8,888,888</span>円</p>
					</div>
				</div>
				
				
				<div class="final_confir">
					<div class="item_info_final">
						<table class="final_detail">
							<tbody>
								<tr class="tabl_ttl">
									<td>アイテム/カラー</td>
									<td>サイズ</td>
									<td>単価</td>
									<td>枚数</td>
									<td>金額</td>
								</tr>
								<tr>
									<td rowspan="3">
										<div class="item_name_color">
											<p>5001</p>
											<p>5.6オンスハイクオリティーTシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>オレンジ</p>
										</div>
									</td>
									<td>160</td>
									<td>5,000</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>S</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>M</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>

								<tr>
									<td rowspan="3">
										<div class="item_name_color">
											<p>5001</p>
											<p>5.6オンスハイクオリティーTシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>ブルー</p>
										</div>
									</td>
									<td>160</td>
									<td>5,000</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>S</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>M</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>

								<tr class="tabl_ttl">
									<td>アイテム/カラー</td>
									<td>サイズ</td>
									<td>単価</td>
									<td>枚数</td>
									<td>金額</td>
								</tr>
								<tr>
									<td rowspan="3">
										<div class="item_name_color">
											<p>5910</p>
											<p>4.1オンス ドライアスレチックポロシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>オレンジ</p>
										</div>
									</td>
									<td>160</td>
									<td>5,000</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>S</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>M</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>

								<tr class="tabl_ttl">
									<td>アイテム/カラー</td>
									<td>サイズ</td>
									<td>単価</td>
									<td>枚数</td>
									<td>金額</td>
								</tr>
								<tr>
									<td rowspan="3">
										<div class="item_name_color">
											<p>5010</p>
											<p>5.6オンスロングスリーブTシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>オレンジ</p>
										</div>
									</td>
									<td>160</td>
									<td>5,000</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>S</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
								<tr>
									<td>M</td>
									<td>5,600</td>
									<td>10枚</td>
									<td>88,888円</td>
								</tr>
							</tbody>
						</table>

					</div>

					<div class="item_info_final_2">
						<table class="print_info_final">
							<tbody>
								<tr class="tabl_ttl_2">
									<td class="print_total">プリント代</td>
									<td class="print_total_p">88,888円</td>
								</tr>
								<tr class="tabl_txt">
									<td class="p_posi">前</td>
									<td>4色</td>
									<td>デジタル転写</td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="subtotal">
						<p>小計<span class="inter">30</span>枚<span class="inter_2">8,888,888</span>円</p>
					</div>
				</div>

				<div class="final_confir">
					<div class="item_info_final_2">
						<table class="discount_t">
							<tbody>
								<tr>
									<td>割引</td>
									<td></td>
									<td class="txt_righ"><span class="red_txt">-8,888円</span></td>
								</tr>
								<tr>
									<td>ブロンズ会員割</td>
									<td></td>
									<td class="txt_righ"><span class="red_txt">-8,888円</span></td>
								</tr>
								<tr>
									<td>送料</td>
									<td class="note"><span class="red_mark">※</span>30,000円以上で送料無料</td>
									<td class="txt_righ">888円</td>
								</tr>
								<tr>
									<td>計</td>
									<td></td>
									<td class="txt_righ">8,888,888円</td>
								</tr>
								<tr>
									<td>消費税</td>
									<td></td>
									<td class="txt_righ">8,888円</td>
								</tr>
								<tr class="bold_t">
									<td>お見積もり合計</td>
									<td></td>
									<td class="big_total txt_righ">8,888,888円</td>
								</tr>
								<tr class="bold_t">
									<td>1枚あたり</td>
									<td></td>
									<td class="txt_righ">88,888円</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="caution">
					<h2>同じアイテムで追加・再注文する</h2>
					<a href="reorder_test3.php" class="btn_or btn">追加・再注文フォームへ</a>
					<p><span class="red_txt">※</span>別のアイテムでご注文をご希望の場合は新規注文扱いとなりますので、<a href="/order/">お申し込みページ</a>へお進みください。</p>
				</div>
			<a href="./my_menu.php" class="next_btn">マイページTOPへ戻る</a>
			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./order_history.php"><div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div></a>
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
