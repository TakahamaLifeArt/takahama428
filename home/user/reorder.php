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

// 注文情報
$itemPrice = 0;
$conndb = new Conndb(_API);
$d = $conndb->getOroderHistory($me['id'], $orderId);
for($i=0; $i<count($d); $i++){
	$idx = $i;
	$orderAmount = $d[$i]['order_amount'];
	$grandTotal = $d[$i]['estimated'];
	$perone = ceil($d[$i]['estimated'] / $orderAmount);
	$salesTax = $d[$i]['salestax'];
	//$credit = $d[$i]['creditfee'];
	$base = ($d[$i]['basefee']!=$grandTotal)? $d[$i]['basefee']: 0;
	$orderDate = $d[$i]['schedule2'];
	$shipDate = $d[$i]['schedule3'];
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
			$inkCount = '4色以上';
		} else {
			$inkCount = $val[$i]['ink']+'色';
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
	<link rel="stylesheet" type="text/css" media="screen" href="./css/my_reorder_2.css" />
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
				<p>注文番号 : <span><?php echo $orderId; ?></span></p>
				<p>注文日 : <span><?php echo $orderDate; ?></span></p>
			</div>

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
								<td class="print_total">プリント代</td>
								<td class="print_total_p">
									<?php echo number_format($printFee); ?>円</td>
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
									<td class="txt_righ">
										<?php echo number_format($carriage); ?>円</td>
								</tr>
								<tr>
									<td>計</td>
									<td></td>
									<td class="txt_righ">
										<?php echo number_format($base); ?>円</td>
								</tr>
								<tr>
									<td>消費税</td>
									<td></td>
									<td class="txt_righ">
										<?php echo number_format($salesTax); ?>円</td>
								</tr>
								<tr class="bold_t">
									<td>お見積もり合計</td>
									<td></td>
									<td class="big_total txt_righ">
										<?php echo number_format($grandTotal); ?>円</td>
								</tr>
								<tr class="bold_t">
									<td>1枚あたり</td>
									<td></td>
									<td class="txt_righ">
										<?php echo number_format($perone); ?>円</td>
								</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="btn_fld_a">
				<div class="btnfld_download">
					<button class="btn_gr btn btn_hidden" id="btn_bill" data-order-id="<?php echo $orderId; ?>" data-shipment="<?php echo $shipDate; ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i><span class="txtbld"><span style="font-size: 1.2rem;margin-right: .2rem;">請求書</span>ダウンロード(PDF)</span></button>
					<button class="btn_gr btn" id="btn_invoice" data-order-id="<?php echo $orderId; ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i><span class="txtbld"><span style="font-size: 1.2rem;margin-right: .2rem;">納品書</span>ダウンロード(PDF)</span></button>
				</div>

				<div class="caution">
					<h2>同じアイテムで追加・再注文する</h2>
					<a href="reorder_form.php?oi=<?php echo $orderId; ?>" class="btn_or btn">追加・再注文フォームへ</a>
					<p><span class="red_txt">※</span>別のデザインでご注文をご希望の場合は新規注文扱いとなりますので、<a href="/order/">お申し込みページ</a>へお進みください。</p>
				</div>

				<a href="./my_menu.php" class="next_btn">マイページTOPへ戻る</a>

				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<a href="./order_history.php">
						<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div>
					</a>
				</div>
			</div>
		</div>

		<div id="printform_wrapper"><iframe id="printform" name="printform"></iframe></div>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="/common/js/api.js"></script>
		<script type="text/javascript" src="./js/history.js"></script>
	</div>
</body>

</html>
