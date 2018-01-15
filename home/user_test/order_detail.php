<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

// TLA顧客IDを取得
$conndb = new Conndb(_API_U);
$u = $conndb->getUserList($me['id']);
$customerid = $u[0]['id'];

// 注文履歴
$orderid = 0;
if(isset($_POST['oi'])){
	$orderid = $_POST['oi'];
}
$conndb = new Conndb(_API);
$d = $conndb->getOroderHistory($customerid);
for($i=0; $i<count($d); $i++){
	$data .= '<thead><tr><th>注文No.<br>発送日</th><th>アイテム</th><th>枚数</th><th>金額</th></tr></thead>';
	if($d[$i]['orderid']==$orderid) $cur = $i;
	$volume = array();
	$data .= '<tr>';
	$data .= '<td class="toc">';
	$data .= '<form action="'.$_SERVER["SCRIPT_NAME"].'" method="post">';
	$data .= '<span>'.$d[$i]['orderid'].'</span>';
	$data .= '<input type="hidden" name="oi" value="'.$d[$i]['orderid'].'">';
	$data .= '<input type="button" value="明細" class="show_detail" onclick="this.form.submit();">';
	$data .= '</form>';
	$data .='<p>'.$d[$i]['schedule3'].'</p></td>';
	$data .= '<td>';
	foreach($d[$i]['itemlist'] as $itemname=>$val){
		$data .= '<p id="list_itemname">'.$itemname.'</p>';
		$volume[] = $d[$i]['itemamount'][$itemname];
	}
	$data .= '</td>';
	$data .= '<td class="tor">';
	for($t=0; $t<count($volume); $t++){
		$data .= '<p>'.number_format($volume[$t]).'<ins class="small">枚</ins></p>';
	}
	$data .= '</td>';
	$data .= '<td class="tor" rowspan="3">'.number_format($d[$i]['estimated']).'<ins class="small">円</ins></td>';
	$data .= '<tr><th>状況</th><th>追加注文</th><th>印刷</th></tr>';
	$data .= '<tr>';
	if($d[$i]['shipped']==2){
		$data .= '<td class="toc">発送済</td>';
	}else{
		if($d[$i]['progress_id']==4){
			$stat = '製作中<br><a href="progress.php?oi='.$d[$i]['orderid'].'" class="f-small">進行状況<img src="/common/img/dotarrow_right.png" class="anchor_arrow"></a>';
		}else{
			$stat = $d[$i]['progressname'];
		}
		$data .= '<td class="toc">'.$stat.'</td>';
	}
	$data .= '<td class="toc"><a href="repeatorder.php?oi='.$d[$i]['orderid'].'" class="btn-f"  class="f-small">追加注文<img src="/common/img/dotarrow_right.png" class="anchor_arrow"></a></td>';
	$data .= '<td class="toc">';
	$data .= '<input type="button" value="請求書" name="id_'.$d[$i]['orderid'].'" class="btn_bill">';

	/* 廃止
	if($d[$idx]['deposit']==2){
		$data .= '<input type="button" value="領収書" name="id_'.$d[$i]['orderid'].'" class="btn_receipt">';
	}
	*/

	if($d[$i]['shipped']==2){		// 発送済み
		$data .= '<br><input type="button" value="納品書" name="id_'.$d[$i]['orderid'].'" class="btn_invoice">';
	}
	$data .= '</td>';
	$data .= '</tr>';
}

/*
*	注文明細
*	履歴が複数ある場合は最後の注文
*/
$i = isset($cur)? $cur: --$i;
$orders_id = $d[$i]['orderid'];
$subtotal = 0;
$curTotal = $d[$i]['estimated'];
$perone = ceil($d[$i]['estimated'] / $d[$i]['order_amount']);
$salesTax = $d[$i]['salestax'];
$credit = $d[$i]['creditfee'];
$base = ($d[$i]['basefee']!=$curTotal)? $d[$i]['basefee']: 0;
foreach($d[$i]['itemlist'] as $itemname=>$info){
	foreach($info as $color=>$val){
		if($val[0]['itemcode']!=''){
			$thumbName = $val[0]['itemcode'].'_'.$val[0]['colorcode'];
			$folder = $val[0]['categorykey'];
			$thumb = '<img alt="" src="'._IMG_PSS.'items/'.$folder.'/'.$val[0]['itemcode'].'/'.$thumbName.'_s.jpg" height="26" />';
		}else{
			$thumb = '';
		}
		$items .= '<tr>';
		$items .= '<td><p>'.$itemname.'</p>';
		$items .= $thumb.'<span>カラー： '.$color.'</span></td>';
		$size = '';
		$cost = '';
		$vol = '';
		$sub = '';
		for($t=0; $t<count($val); $t++){
			$price = $val[$t]['cost'] * $val[$t]['volume'];
			$size .= '<p>'.$val[$t]['size'].'</p>';
			$cost .= '<p>'.number_format($val[$t]['cost']).'<ins class="small">円</ins></p>';
			$vol .= '<p>'.number_format($val[$t]['volume']).'<ins class="small">枚</ins></p>';
			$sub .= '<p>'.number_format($price).'<ins class="small">円</ins></p>';
			$subtotal += $price;
		}

		$items .= '<td class="toc">'.$size.'</td>';
		$items .= '<td class="tor">'.$cost.'</td>';
		$items .= '<td class="tor">'.$vol.'</td>';
		$items .= '<td class="tor">'.$sub.'</td>';
		$items .= '</tr>';
	}
}
$items .= '<tr><td colspan="3" class="toc">小計</td>';
$items .= '<td class="tor">'.number_format($d[$i]['order_amount']).'<ins class="small">枚</ins></td>';
$items .= '<td class="tor">'.number_format($subtotal).'<ins class="small">円</ins></td></tr>';

$discount_fee = $d[$i]['discountfee'] + $d[$i]['reductionfee'];
$print_fee = $d[$i]['printfee'] + $d[$i]['exchinkfee'];
$items .= '<tr><td colspan="4">プリント代</td><td class="tor">'.number_format($print_fee).'<ins class="small">円</ins></td></tr>';
$items .= '<tr><td colspan="4">割引</td><td class="tor fontred">▲'.number_format($discount_fee).'<ins class="small">円</ins></td></tr>';
$items .= '<tr><td colspan="4">送料</td><td class="tor">'.number_format($d[$i]['carriagefee']).'<ins class="small">円</ins></td></tr>';

$charge = array(
	'expressfee'=>'特急料金',
	'codfee'=>'代引手数料',
	'packfee'=>'袋詰代',
	'designfee'=>'デザイン代',
	'additionalfee'=>$d[$i]['additionalname']
);
foreach($charge as $charge_key=>$charge_name){
	if(empty($d[$i][$charge_key])) continue;
	$items .= '<tr><td colspan="4">'.$charge_name.'</td><td class="tor">'.number_format($d[$i][$charge_key]).'<ins class="small">円</ins></td></tr>';
}



// プリント情報
$printing = '';
$p = $conndb->getDetailsPrint($orders_id);
foreach($p as $category_name=>$val){
	for($i=0; $i<count($val); $i++){
		// 絵型
		$print_pos = '';
		$fp = fopen('../common/'.$val[$i]['area_path'], 'r');
		if($fp){
			flock($fp, LOCK_SH);
			$img = fgets($fp);
			$img = str_replace('src="./img/', 'src="./', $img);
			preg_match('/src=\"(.\/[^\"]*)\"/', $img, $src);
			$src1 = str_replace('./', '', $src[1]);
			$print_pos .= '<img alt="プリント位置" src="'._IMG_PSS.$src1.'" />';	// ボディ画像
			while(!feof($fp)){
				$buffer = fgets($fp);	// プリント位置ごとに処理
				if(strpos($buffer, '"'.$val[$i]['select_key'].'"')!==false){
					$buffer = str_replace('src="./img/', 'src="'._IMG_PSS, $buffer);
					if($val[$i]['category_id']!=99){
						$print_pos .= str_replace('.png', '_on.png', $buffer);
					}else{
						$print_pos .= $buffer;
					}
				}
			}
			flock($fp, LOCK_UN);
		}
		fclose($fp);

		// デザイン
		$design = '';
		if(!empty($val[$i]['design_path'])){
			$design = '<img src="'.$val[$i]['design_path'].'" width="200">';
		}

		$printing .= '<tr>';
		$printing .= '<td>'.$category_name.'</td>';
		$printing .= '<td><div class="pos_wrap">'.$print_pos.'</div></td>';
		//$printing .= '<td>'.$design.'</td>';
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
		<script type="text/javascript">
			var _CUR_ORDER = <?php echo $orderid?>;

		</script>

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
					<p>注文番号 : <span>36422</span></p>
					<p>注文日 : <span>2017/09/12</span></p>
				</div>
				<div class="">
					<nav>
						<ol class="cd-multi-steps text-bottom count">
							<li class="current"><em><span class="sp_area">受付完了</span></em></li>
							<li><em><span class="sp_area">デザイン制作</span></em></li>
							<li><em><span class="sp_area">プリント開始</span></em></li>
							<li><em><span class="sp_area">発送準備</span></em></li>
							<li><em><span class="sp_area">発送完了</span></em></li>
						</ol>
					</nav>
				</div>
				<div class="date_arrival">
					<p class=""><span class="boldtxt">10月27日</span>に到着予定です。</p>
				</div>
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
							<button class="featherlight-close-button featherlight-close" aria-label="Close">閉じる</button>
						</div>
					</div>
				</div>
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
							</tbody>

							<tbody>
								<tr class="border_t">
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
							</tbody>

							<tbody>
								<tr class="border_t">
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
							</tbody>
						</table>
					</div>

					<div class="item_info_final_2">
						<table class="print_info_final">
							<tbody>
								<tr class="tabl_ttl_2">
									<td class="print_total">プリント代</td>

									<td></td>
									<td></td>

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
							</tbody>
						</table>
					</div>
				</div>
				<div class="btnfld">
					<button type="button" class="btn_or btn"><i class="fa fa-file-pdf-o" aria-hidden="true"></i><span class="txtbld">請求書ダウンロード(PDF)</span></button>
				</div>
				<div class="caution">
					<h3 class="txtarea">運送会社に問い合わせる</h3>
					<p>お問合せ番号<span class="bdr_txt">1234-5678-9625</span></p>
					<p>運送業者<span class="bdr_txt">ヤマト運輸</span></p>
					<p>URL<a href="" class="infolink">お問い合わせはこちら</a></p>
				</div>

				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div>
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
