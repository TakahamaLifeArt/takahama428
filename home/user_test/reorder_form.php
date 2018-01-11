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

			td, th {
				padding: 3px;
				width: 0px;
				height: auto;
			}
			.size_table {
				margin-bottom: 2em;
				border: none;
				width: 100%;
			}
			.size_table .heading {
				float: left;
				width: auto;
				display: block;
				position: relative;
				left: 30px;
				top: 4px;
			}
			.size_table tr {
				width: 87%;
				float: left;
				text-align: right;
			}
			.size_table tr.heading th {
				padding: 15px 0px 7px;
				text-align: left;
				border-bottom: 0px;
				font-size: 1em;
				width: auto;
				height: auto;
				margin-top: 5px;
			}
			.size_table td {
				display: block;
				padding-bottom: 2px;
				border-bottom: 1px dotted #eae7d2;
				font-size: 1rem;
				padding: 0;
				height: auto;
				width: auto;
				margin-bottom: 10px;
			}
			.size_table th {
				display: block;
				padding-top: 10px;
				padding-right: 0px;
				font-size: .9em;
				width: auto;
				text-align: right;
				border-bottom: 1px solid #eae7d2;
				border: none;
			}
			.size_table input[type=number] {
				width: 8em;
				padding: 2px 6px 0 0px;
				text-align: right;
				font-size: 1.1em;
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
									<td>700円</td>
									<td>10枚</td>
									<td>7,000円</td>
								</tr>
								<tr>
									<td>-</td>
									<td>700円</td>
									<td>0枚</td>
									<td>0円</td>
								</tr>

								<tr>
									<td colspan="4">
										<button type="button" class="btn btn-success">追加する</button>
									</td>
								</tr>
								
								<tr>
									<td rowspan="2">
										<div class="item_name_color">
											<p>5001</p>
											<p>5.6オンスハイクオリティーTシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>ブルー</p>
										</div>
									</td>
									<td>-</td>
									<td>700円</td>
									<td>0枚</td>
									<td>0円</td>
								</tr>

								<tr>
									<td colspan="4">
									
										<button type="button" class="btn btn-success">追加する</button>
									</td>
								</tr>
								
								<tr class="tabl_ttl">
									<td>アイテム/カラー</td>
									<td>サイズ</td>
									<td>単価</td>
									<td>枚数</td>
									<td>金額</td>
								</tr>
								<tr>
									<td rowspan="2">
										<div class="item_name_color">
											<p>5910</p>
											<p>4.1オンス ドライアスレチックポロシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>オレンジ</p>
										</div>
									</td>
									<td>-</td>
									<td>700円</td>
									<td>0枚</td>
									<td>0円</td>
								</tr>

								<tr>
									<td colspan="4">
										<button type="button" class="btn btn-success">追加する</button>
									</td>
								</tr>

								<tr class="tabl_ttl">
									<td>アイテム/カラー</td>
									<td>サイズ</td>
									<td>単価</td>
									<td>枚数</td>
									<td>金額</td>
								</tr>
								<tr>
									<td rowspan="2">
										<div class="item_name_color">
											<p>5010</p>
											<p>5.6オンスロングスリーブTシャツ</p>
											<img src="/order/img/demo_4.png">
											<p>オレンジ</p>
										</div>
									</td>
									<td>-</td>
									<td>700円</td>
									<td>0枚</td>
									<td>0円</td>
								</tr>

								<tr>
									<td colspan="4">
										<button type="button" class="btn btn-success">追加する</button>
									</td>
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
								
								<tr class="reorder_btn">
									<td colspan="3">
										<a href="reorder_day2.php"><button type="button" class="btn btn-info">申し込みリストへ追加</button></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="caution">
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
		
		<script>
		$('.btn-success').on('click', function(){
			var msg = '<table class="size_table">';
			msg += '<tbody>';
			msg += '<tr class="heading">';
			msg += '<th></th>';
			msg += '<th>WM</th>';
			msg += '<th>WL</th>';
			msg += '<th>S</th>';
			msg += '<th>M</th>';
			msg += '<th>L</th>';
			msg += '<th>XL</th>';
			msg += '</tr>';
			msg += '<tr>';
			msg += '<th>1枚単価<span class="inter">550</span> 円</th>';
			msg += '<td class="size_30_WM_550"><input id="size_30" type="number" value="0" min="0" max="999"></td>';
			msg += '<td class="size_31_WL_550"><input id="size_31" type="number" value="0" min="0" max="999"></td>';
			msg += '<td class="size_18_S_550"><input id="size_18" type="number" value="0" min="0" max="999"></td>';
			msg += '<td class="size_19_M_550"><input id="size_19" type="number" value="0" min="0" max="999"></td>';
			msg += '<td class="size_20_L_550"><input id="size_20" type="number" value="0" min="0" max="999"></td>';
			msg += '<td class="size_21_XL_550"><input id="size_21" type="number" value="0" min="0" max="999"></td>';
			msg += '<td>枚</td>';
			msg += '</tr>';
			msg += '</tbody>';
			msg += '</table>';

			$.msgbox(msg, '追加するサイズと枚数');
		})
		
		</script>
	</body>

	</html>
