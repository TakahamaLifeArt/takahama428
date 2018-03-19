<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

// 受注番号
if(isset($_GET['oi'])){
	$orderId = $_GET['oi'];
} else {
	jump('./order_history.php');
}

// 会員情報
$member = new TlaMember($me['id']);
$rank = $member->getRankRatio();
$rankName = $member->getRankName();

// 製作状況
$idx = 0;
$params = array($me['id'], 0);
$conndb = new Conndb(_API);
$d = $conndb->getProgress($params);
for($i=0; $i<count($d); $i++){
	if($d[$i]['orderid']!=$orderId) continue;
	$idx = $i;
	$orderDate = $d[$i]['schedule2'];
	$delidate = explode('-', $d[$i]['schedule4']);
	$status = $d[$i]['progressname'];
	$contactNumber = $d[$i]['contact_number'];
}
$aryFin = explode(',', $d[$idx]['fin_print']);
if(!$d[$idx]['fin_1']){
	$prog[0] = '';
} else if(min($aryFin)==0){
	$prog[0] = 'current';
}else{
	$prog[0] = 'current';
	$prog[1] = 'current';
}
if ($d[$i]['shipped']==2) {
	$prog[0] = 'current';
	$prog[1] = 'current';
	$prog[2] = 'current';
}
$progress = '<li class="current"><em><span class="sp_area">受付完了</span></em></li>';
$progress .= '<li class="current"><em><span class="sp_area">デザイン制作</span></em></li>';
$progress .= '<li class="'.$prog[0].'"><em><span class="sp_area">プリント開始</span></em></li>';
$progress .= '<li class="'.$prog[1].'"><em><span class="sp_area">発送準備</span></em></li>';
$progress .= '<li class="'.$prog[2].'"><em><span class="sp_area">発送完了</span></em></li>';

// アイテム情報
$d = $conndb->getOroderHistory($me['id']);
for($i=0; $i<count($d); $i++){
	if($d[$i]['orderid']!=$orderId) continue;
	$idx = $i;
	$itemPrice = 0;
	$payment = $d[$i]['payment'];
	$orderAmount = $d[$i]['order_amount'];
	$grandTotal = $d[$i]['estimated'];
	$perone = ceil($d[$i]['estimated'] / $orderAmount);
	$salesTax = $d[$i]['salestax'];
	//$credit = $d[$i]['creditfee'];
	$base = ($d[$i]['basefee']!=$grandTotal)? $d[$i]['basefee']: 0;
	foreach($d[$i]['itemlist'] as $itemname=>$info){
		foreach($info as $color=>$val){
			$items .= '<tbody>';

			$row = '';
			$cols = count($val);
			for($t=1; $t<$cols; $t++){
				$price = $val[$t]['cost'] * $val[$t]['volume'];
				$itemPrice += $price;
				$row .= '<tr>';
				$row .= '<td>'.$val[$t]['size'].'</td>';
				$row .= '<td>'.number_format($val[$t]['cost']).'円</td>';
				$row .= '<td>'.number_format($val[$t]['volume']).'枚</td>';
				$row .= '<td>'.number_format($price).'円</td>';
				$row .= '</tr>';
			}

			$price = $val[0]['cost'] * $val[0]['volume'];
			$itemPrice += $price;
			$items .= '<tr>';
			$items .= '<td rowspan="'.$cols.'">';
			$items .= '<div class="item_name_color">';
			$items .= '<p>'.strtoupper($val[0]['itemcode']).'</p>';
			$items .= '<p>'.$itemname.'</p>';
			$items .= '<img src="'._IMG_PSS.'items/list/'.$val[0]['categorykey'].'/'.$val[0]['itemcode'].'/'.$val[0]['itemcode'].'_'.$val[0]['colorcode'].'.jpg">';
			$items .= '<p>'.$color.'</p>';
			$items .= '</div>';
			$items .= '</td>';
			$items .= '<td>'.$val[0]['size'].'</td>';
			$items .= '<td>'.number_format($val[0]['cost']).'円</td>';
			$items .= '<td>'.number_format($val[0]['volume']).'枚</td>';
			$items .= '<td>'.number_format($price).'円</td>';
			$items .= '</tr>';
			$items .= $row;

			$items .= '</tbody>';
		}
	}
}

$printFee = $d[$idx]['printfee'] + $d[$idx]['exchinkfee'];
$subTotal = $printFee + $itemPrice;
$rankFee = ceil(($subTotal * $rank)/100);
$discountFee = $d[$idx]['discountfee'] + $d[$idx]['reductionfee'];
$carriage = $d[$idx]['carriagefee'];

// 支払い方法の説明の非表示を設定
$paymentCode = array(
	'wiretransfer' => 'hidden="hiddeh"',
	'credit' => 'hidden="hiddeh"',
	'cod' => 'hidden="hiddeh"'
);
$paymentCode[$payment] = '';


// プリント情報
$printMethod = array(
	'silk'=>'シルクスクリーン',
	'digit'=>'デジタルコピー転写',
	'inkjet'=>'インクジェット',
	'cutthing'=>'カッティング',
	'embroidery'=>'刺繍'
);
$printSize = array(
	'silk'=>array('通常','ジャンボ','スーパージャンボ'),
	'digit'=>array('大','中','小'),
	'inkjet'=>array('大','中','小'),
	'cutting'=>array('大','中','小'),
	'embroidery'=>array('大','中','小'),
);
$printing = '';
$p = $conndb->getDetailsPrint($orderId);
foreach($p as $category_name=>$val){
	for($i=0; $i<count($val); $i++){
		if ($val[$i]['method']!='silk') {
			$inkCount = '';
		} else if ($val[$i]['ink']==4) {
			$inkCount = $val[$i]['ink'].'色以上';
		} else {
			$inkCount = $val[$i]['ink'].'色';
		}
		$printing .= '<tr class="tabl_txt">';
		$printing .= '<td class="p_posi">'.$val[$i]['select_name'].'</td>';
		$printing .= '<td>'.$inkCount.'</td>';
		$printing .= '<td>'.$printMethod[$val[$i]['method']].'('.$printSize[$val[$i]['method']][$val[$i]['size']].')</td>';
		$printing .= '<td></td>';
		$printing .= '</tr>';
	}
}
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="タカハマライフアートのマイページ、制作状況の詳細画面です。こちらから、現在制作進行中のオリジナルTシャツの状況を確認することができます。到着予定日などの確認も可能です。">
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
	<link rel="stylesheet" type="text/css" media="screen" href="/common/css/printposition_responsive.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/my_order_detail.css" />
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
						<h1>ご注文内容</h1>
					</div>
				</div>
			</div>
			<div class="num_data">
				<p>注文番号 : <span id="order_id"><?php echo $orderId; ?></span></p>
				<p>注文日 : <span><?php echo $orderDate; ?></span></p>
			</div>
			<div>
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<?php echo $progress; ?>
					</ol>
				</nav>
			</div>
			<div class="date_arrival">
				<p><span class="boldtxt"><?php echo $delidate[1].'月'.$delidate[2].'日'; ?></span>に到着予定です。</p>
			</div>
			
			<aside <?php echo $paymentCode['credit']; ?>>
				<div class="btnfld">
					<div class="pay_txt">お支払方法：カード決済</div>
					<button type="button" class="btn btn-info" data-featherlight="#fl1">カード決済する</button>
				</div>
				<div class="lightbox" id="fl1">
				<div class="modal-content">
					<div class="modal_window">
						<h2><i class="fa fa-exclamation-triangle red_txt_b" aria-hidden="true"></i>クレジットカードの種類にご注意ください</h2>
						<h3 class="syousai">ご利用可能なクレジットカード一覧</h3>
						<div class="sq_bdr">
							<p class="min_space"><img src="./img/sp_pay_credit_01.png" width="100%"></p>
							<p class="min_space"><img src="./img/sp_pay_credit_02.png" width="100%"></p>
							<p class="min_space"><img src="./img/sp_pay_credit_03.png" width="100%"></p>
						</div>
						<div class="sq_bdr">
							<p class="min_space"><img src="./img/sp_pay_credit_04.png" width="100%"></p>
							<p class="min_space"><img src="./img/sp_pay_credit_05.png" width="100%"></p>
							<p class="min_space"><img src="./img/sp_pay_credit_06.png" width="100%"></p>
						</div>
						<p><span class="fontred">※</span>決済完了後、システム反映までに時間がかかる場合がございます。予めご了承ください。</p>
						<?php
						if($payment=='credit'){
							// POST情報
//							$redirect_ok = '//www.takahama428.com/user/receive_ok.php';
//							$redirect_ng = '//www.takahama428.com/user/receive_ng.php';
							$redirect_can = '//www.takahama428.com'.$_SERVER['SCRIPT_NAME'].'?oi='.$orderId;
							$redirect = '//www.takahama428.com'.dirname($_SERVER['SCRIPT_NAME']).'/receive.php';
							
							$now = date('YmdHis');
							$tax = "0";
							$shopId = "9100596916967";
							$shoppass = "h3xy5z6c";
							$crediturl= 'https://p01.mul-pay.jp/link/'.$shopId.'/Multi/Entry';
							$form = '<form action="'.$crediturl.'" method="post">';
							$form .= '<input type="hidden" value="'.$shopId.'" name="ShopID">';
							$form .= '<input type="hidden" value="'.$orderId.'" name="OrderID">';
							$form .= '<input type="hidden" value="'.$grandTotal.'" name="Amount">';
							$form .= '<input type="hidden" value="'.$now.'" name="DateTime">';
							$form .= '<input type="hidden" value="'.$tax.'" name="Tax">';
							//「ショップ ID + “|” +  オーダー ID + “|” +  利用金額   + “|” +  税送料   + “|” +  ショップパスワード   + “|” +  日時情報」
							$password = $shopId . "|" . $orderId . "|" . $grandTotal . "|" .$tax. "|" .$shoppass. "|".$now;
							$password = md5($password);
							$form .= '<input type="hidden" value="' .$password. '" name="ShopPassString">';
							$form .= '<input type="hidden" value="'.$redirect.'" name="RetURL">';
							$form .= '<input type="hidden" value="'.$redirect_can.'" name="CancelURL">';
							//クレジットカード
//							if($d[$idx]['payment']=='credit') {
								$form .= '<input type="hidden" value="1" name="UseCredit">';
								$form .= '<input type="hidden" value="CAPTURE" name="JobCd">';
								$form .= '<input type="submit" value="カード決済のお申込はこちらから">';
//							} else {
//								$form .= '<input type="hidden" value="1" name="UseCvs">';
//								$form .= '<input type="hidden" value="有限会社タカハマライフアート" name="ReceiptsDisp11">';
//								$form .= '<input type="hidden" value="03-5670-0787" name="ReceiptsDisp12">';
//								$form .= '<input type="hidden" value="09:00-18:00" name="ReceiptsDisp13">';
//								$form .= '<input type="submit" value="コンビニ決済のお申込はこちらから">';
//							}
							$form .= '</form>';
							echo $form;
						}
						?>
<!--
						<form action="https://p01.mul-pay.jp/link/9100596916967/Multi/Entry" method="post">
							<input type="hidden" value="9100596916967" name="ShopID">
							<input type="hidden" value="27168" name="OrderID">
							<input type="hidden" value="242132" name="Amount">
							<input type="hidden" value="20171214141451" name="DateTime">
							<input type="hidden" value="0" name="Tax">
							<input type="hidden" value="78dd71ae08b14abc6e67543eac8de877" name="ShopPassString">
							<input type="hidden" value="//www.takahama428.com/user/receive.php" name="RetURL">
							<input type="hidden" value="//www.takahama428.com/user/credit.php?oi=27168" name="CancelURL">
							<input type="hidden" value="1" name="UseCredit">
							<input type="hidden" value="CAPTURE" name="JobCd">
							<input type="submit" value="カード決済のお申込はこちらから">
						</form>
-->
						<button class="featherlight-close-button featherlight-close" aria-label="Close">閉じる</button>
					</div>
				</div>
			</div>
			</aside>
			<aside <?php echo $paymentCode['wiretransfer']; ?>>
				<div class="btnfld">
					<div class="pay_txt">お支払方法：銀行振込</div>
					<button type="button" class="btn btn-info" data-featherlight="#fl2">ご利用方法を確認する</button>
				</div>
				<div class="lightbox" id="fl2">
				<div class="modal-content">
					<div class="modal_window">
						<h2>銀行振込</h2>
						<p>振込先口座は下記の内容をご確認ください。</p>
						<table border="1" cellspacing="0" cellpadding="5" class="tbarea">
							<tr>
								<th bgcolor="#eceae8">銀行名</th>
								<td>三菱東京ＵＦＪ銀行</td>
							</tr>
							<tr>
								<th bgcolor="#eceae8">支店名</th>
								<td>新小岩支店　744</td>
							</tr>
							<tr>
								<th bgcolor="#eceae8">口座種別</th>
								<td>普通</td>
							</tr>
							<tr>
								<th bgcolor="#eceae8">口座番号</th>
								<td>3716333</td>
							</tr>
							<tr>
								<th bgcolor="#eceae8">口座名義</th>
								<td>ユ）タカハマライフアート</td>
							</tr>
						</table>
						<div class="pay_bdr">
							<h4 class=""><i class="fa fa-exclamation-triangle red_txt_b" aria-hidden="true"></i>ご注意</h4>
							<p class="min_space">ご希望の納品日より2日前までにお振込をお願い致します。</p>
							<p class="min_space">（土日祝は入金確認が翌営業日の確認となりますのでご注意ください）</p>
							<p class="min_space">お振込手数料は、お客様のご負担とさせていただいております。</p>
						</div>
						<button class="featherlight-close-button featherlight-close" aria-label="Close">閉じる</button>
					</div>
				</div>
			</div>
			</aside>
			<aside <?php echo $paymentCode['cod']; ?>>
				<div class="btnfld">
					<div class="pay_txt">お支払方法：代金引換</div>
					<button type="button" class="btn btn-info" data-featherlight="#fl3">ご利用方法を確認する</button>
				</div>
				<div class="lightbox" id="fl3">
				<div class="modal-content">
					<div class="modal_window">
						<h2>代金引換</h2>
						<p>商品と引換に代金を配送業者に預ける支払サービスです。</p>
						<p>代金引換手数料は1件につき¥800（税抜）かかります。お支払い総額（商品代+送料＋代金引換手数料＋消費税）を配送業者にお支払いください。</p>
						<div class="pay_bdr">
							<h4 class=""><i class="fa fa-exclamation-triangle red_txt_b" aria-hidden="true"></i>ご注意</h4>
							<p class="min_space">お客様のご都合でお支払い件数が複数になった場合、1件につき¥800（税抜）を追加させていただきます。あらかじめご了承下さい。</p>
						</div>
						<p><span class="fontred">※</span>お支払い期日を過ぎた後、再三の催促・督促にもかかわらず、何のご連絡もなくお支払いのないお客様は、法的手段を含め対応させて頂きます。
							この場合に発生する手数料等の諸費用、法的手続きにかかった諸費用のすべてを、未払い代金に加算して請求致します。</p>
						<button class="featherlight-close-button featherlight-close" aria-label="Close">閉じる</button>
					</div>
				</div>
			</div>
			</aside>
			
			<div class="final_confir">
				<div class="item_info_final">
					<table class="final_detail">
						<thead>
							<tr class="tabl_ttl">
								<td>アイテム/カラー</td>
								<td>サイズ</td>
								<td>単価</td>
								<td>枚数</td>
								<td>金額</td>
							</tr>
						</thead>
						<?php echo $items; ?>
					</table>
				</div>

				<div class="item_info_final_2">
					<table class="print_info_final">
						<tbody>
							<tr class="tabl_ttl_2">
								<td colspan="3" class="print_total">プリント代</td>
								<td class="print_total_p"><?php echo number_format($printFee); ?>円</td>
							</tr>
							<?php echo $printing; ?>
						</tbody>
					</table>
				</div>

				<div class="subtotal">
					<p>小計<span class="inter"><?php echo number_format($orderAmount); ?></span>枚<span class="inter_2"><?php echo number_format($subTotal); ?></span>円</p>
				</div>
			</div>

			<div class="final_confir">
				<div class="item_info_final_2">
					<table class="discount_t">
						<tbody>
							<tr>
								<td>割引</td>
								<td></td>
								<td class="txt_righ"><span class="red_txt"><?php echo number_format($discountFee); ?>円</span></td>
							</tr>
							<?php
							if ($rank!=0 && strtotime($orderDate)>strtotime(_START_RANKING)) {
								$tr = '<tr>';
								$tr .= '<td>'.$rankName.'会員割</td>';
								$tr .= '<td></td>';
								$tr .= '<td class="txt_righ"><span class="red_txt">-'.number_format($rankFee).'円</span></td>';
								$tr .= '</tr>';
								echo $tr;
							}
							?>
							<tr>
								<td>送料</td>
								<td class="note"><span class="red_mark">※</span>30,000円以上で送料無料</td>
								<td class="txt_righ"><?php echo number_format($carriage); ?>円</td>
							</tr>
							<tr>
								<td>計</td>
								<td></td>
								<td class="txt_righ"><?php echo number_format($base); ?>円</td>
							</tr>
							<tr>
								<td>消費税</td>
								<td></td>
								<td class="txt_righ"><?php echo number_format($salesTax); ?>円</td>
							</tr>
							<tr class="bold_t">
								<td>お見積もり合計</td>
								<td></td>
								<td class="big_total txt_righ"><?php echo number_format($grandTotal); ?>円</td>
							</tr>
							<tr class="bold_t">
								<td>1枚あたり</td>
								<td></td>
								<td class="txt_righ"><?php echo number_format($perone); ?>円</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="btnfld_download">
				<button class="btn_or btn" id="btn_bill" data-order-id="<?php echo $orderId; ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i><span class="txtbld"><span style="font-size: 1.2rem;margin-right: .2rem;">請求書</span>ダウンロード(PDF)</span></button>
				<button class="btn_or btn" id="btn_invoice" data-order-id="<?php echo $orderId; ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i><span class="txtbld"><span style="font-size: 1.2rem;margin-right: .2rem;">納品書</span>ダウンロード(PDF)</span></button>
			</div>

			<div class="caution">
				<h3 class="txtarea">運送会社に問い合わせる</h3>
				<p>お問合せ番号<span class="bdr_txt"><?php echo $contactNumber; ?></span></p>
				<p>運送業者<span class="bdr_txt">ヤマト運輸</span></p>
				<p>URL<a href="https://toi.kuronekoyamato.co.jp/cgi-bin/tneko" class="infolink">お問い合わせはこちら</a></p>
			</div>

			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./order_list.php"><div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div></a>
			</div>
		</div>
		<a href="my_menu.php" class="next_btn">マイページTOPへ戻る</a>
	</div>

	<div id="printform_wrapper"><iframe id="printform" name="printform"></iframe></div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script type="text/javascript" src="./js/history.js"></script>
	<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
</body>

</html>
