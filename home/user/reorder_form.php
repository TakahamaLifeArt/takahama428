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
//	if($d[$i]['orderid']!=$orderId) continue;
	$idx = $i;
	$orderAmount = $d[$i]['order_amount'];
	$grandTotal = $d[$i]['estimated'];
	$perone = ceil($d[$i]['estimated'] / $orderAmount);
	$salesTax = $d[$i]['salestax'];
	//$credit = $d[$i]['creditfee'];
	$base = ($d[$i]['basefee']!=$grandTotal)? $d[$i]['basefee']: 0;
	$orderDate = $d[$i]['schedule2'];
	foreach($d[$i]['itemlist'] as $itemname=>$info){
		foreach($info as $color=>$val){
			$items .= '<tbody>';
			
			$items .= '<tr>';
			$items .= '<td rowspan="2">';
			$items .= '<div class="item_name_color"';
			$items .= ' data-master="'.$val[0]['master_id'].'"';
			$items .= ' data-category="'.$val[0]['category_id'].'"';
			$items .= ' data-pos="'.$val[0]['printposition_id'].'"';
			$items .= ' data-range="'.$val[0]['item_group1_id'].'"';
			$items .= ' data-screen="'.$val[0]['item_group2_id'].'"';
			$items .= '>';
			$items .= '<p class="item_code">'.strtoupper($val[0]['itemcode']).'</p>';
			$items .= '<p class="item_name">'.$itemname.'</p>';
			$items .= '<img src="'._IMG_PSS.'items/list/'.$val[0]['categorykey'].'/'.$val[0]['itemcode'].'/'.$val[0]['itemcode'].'_'.$val[0]['colorcode'].'.jpg">';
			$items .= '<p class="color_code">'.$color.'</p>';
			$items .= '</div>';
			$items .= '</td>';
			$items .= '<td colspan="4" class="is-hidden"></td>';
			$items .= '</tr>';
			
			$items .= '<tr>';
			$items .= '<td colspan="4">';
			$items .= '<button type="button" class="btn btn-success add_order_item" data-itemid="'.$val[0]['itemid'].'" data-colorcode="'.$val[0]['colorcode'].'">追加する</button>';
			$items .= '</td>';
			$items .= '</tr>';
			
			$items .= '</tbody>';
		}
	}
}

//$printFee = $d[$idx]['printfee'] + $d[$idx]['exchinkfee'];
//$subTotal = $printFee + $itemPrice;
//$rankFee = ceil(($subTotal * $rank)/100);
//$discountFee = $d[$idx]['discountfee'] + $d[$idx]['reductionfee'] - $rankFee;	// 会員割引は別途記載のため
//$carriage = $d[$idx]['carriagefee'];


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
			$inkCount = '<span class="ink"></span>';
		} else if ($val[$i]['ink']==4) {
			$inkCount = '<span class="ink">'.$val[$i]['ink'].'</span>色以上';
		} else {
			$inkCount = '<span class="ink">'.$val[$i]['ink'].'</span>色';
		}
		$printing .= '<tr class="tabl_txt"';
		$printing .= ' data-prn-pos="'.$val[$i]['printposition_id'].'"';
		$printing .= ' data-prn-face="'.$val[$i]['select_key'].'"';
		$printing .= ' data-prn-size="'.$val[$i]['size'].'"';
		$printing .= ' data-prn-option="'.$val[$i]['option'].'"';
		$printing .= ' data-prn-method="'.$val[$i]['method'].'"';
		$printing .= '>';
		$printing .= '<td class="p_posi">'.$val[$i]['select_name'].'</td>';
		$printing .= '<td><span class="ink">'.$inkCount.'</td>';
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
	<link rel="stylesheet" type="text/css" media="screen" href="./css/size_table.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/my_reorder.css" />
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
					<table class="final_detail" id="reorder_item">
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
								<td class="print_total_p"><span id="print_fee">0</span>円</td>
							</tr>
							<?php echo $printing; ?>
						</tbody>
					</table>
				</div>

				<div class="subtotal">
					<p>小計<span class="inter" id="item_amount">0</span>枚<span class="inter_2" id="item_fee">0</span>円</p>
					<p class="note fontred hidden">※ 大口注文割引きが適用されました。</p>
				</div>
			</div>

			<div class="final_confir">
				<div class="item_info_final_2">
					<table class="discount_t">
						<tbody>
							<tr>
								<td>割引</td>
								<td></td>
								<td class="txt_righ"><span class="red_txt"><span>0</span>円</span></td>
							</tr>
							<?php
							if ($rank!=0 && strtotime($orderDate)>strtotime(_START_RANKING)) {
								$tr = '<tr>';
								$tr .= '<td>'.$rankName.'会員割</td>';
								$tr .= '<td></td>';
								$tr .= '<td class="txt_righ"><span class="red_txt"><span id="rank_fee" data-rank="'.$rank.'">0</span>円</span></td>';
								$tr .= '</tr>';
								echo $tr;
							}
							?>
							<tr>
								<td>送料</td>
								<td class="note"><span class="red_mark">※</span>30,000円以上で送料無料</td>
								<td class="txt_righ"><span id="carriage">0</span>円</td>
							</tr>
							<tr>
								<td>計</td>
								<td></td>
								<td class="txt_righ"><span id="base">0</span>円</td>
							</tr>
							<tr>
								<td>消費税</td>
								<td></td>
								<td class="txt_righ"><span id="tax">0</span>円</td>
							</tr>
							<tr class="bold_t">
								<td>お見積もり合計</td>
								<td></td>
								<td class="big_total txt_righ"><span id="estimation">0</span>円</td>
							</tr>
							<tr class="bold_t">
								<td>1枚あたり</td>
								<td></td>
								<td class="txt_righ"><span id="perone">0</span>円</td>
							</tr>

							<tr class="reorder_btn">
								<td colspan="3">
									<button id="add_list" data-order-id="<?php echo $orderId; ?>" class="btn btn-info">申し込みリストへ追加</button>
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
	<script type="text/javascript" src="/common/js/api.js"></script>
	<script type="text/javascript" src="./js/messagebox.js"></script>
	<script type="text/javascript" src="./js/reorder.js"></script>

</body>

</html>
