<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

// 未決済注文の指定
if(isset($_GET['oi'])){
	$orders_id = $_GET['oi'];
}

// TLA顧客IDを取得
$conndb = new Conndb(_API_U);
$u = $conndb->getUserList($me['id']);
$customerid = $u[0]['id'];

$conndb = new Conndb(_API);
$d = $conndb->getOroderHistory($customerid);

$idx = -1;
$ls = '';
for($i=0; $i<count($d); $i++){
	if($d[$i]['deposit']==2) continue;	// 未入金とカード決済指定の他は除外

	$ls .= '<li>';
	if( (empty($orders_id) && $idx==-1) || $d[$i]['orderid']==$orders_id){
		$ls .= $d[$i]['schedule2'].' ご注文確定　No.'.$d[$i]['orderid'];
		$idx = $i;
		if(empty($orders_id))$orders_id = $d[$i]['orderid'];
	}else{
		$ls .= '<a href="'.$_SERVER['SCRIPT_NAME'].'?oi='.$d[$i]['orderid'].'">'.$d[$i]['schedule2'].' ご注文確定　No.'.$d[$i]['orderid'].'</a>';
	}
	$ls .= '</li>';
}

if(!empty($ls)){
	$msg = "お支払いが未確定のご注文";
	$ls = '<ol class="orders_list">'.$ls.'</ol>';

	foreach($d[$idx]['itemlist'] as $itemname=>$info){
		foreach($info as $color=>$val){
			if($val[0]['itemcode']!=''){
				$thumbName = $val[0]['itemcode'].'_'.$val[0]['colorcode'];
				$folder = $val[0]['categorykey'];
				$thumb = '<img alt="" src="'._IMG_PSS.'items/'.$folder.'/'.$val[0]['itemcode'].'/'.$thumbName.'_s.jpg" height="26" style="box-shadow:none;"/>';
			}else{
				$thumb = '';
			}
			$items .= '<tr>';
			$items .= '<td>'.$itemname.'<br/ >';
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
	$items .= '<td class="tor">'.number_format($d[$idx]['order_amount']).'<ins class="small">枚</ins></td>';
	$items .= '<td class="tor">'.number_format($subtotal).'<ins class="small">円</ins></td></tr>';

	$discount_fee = $d[$idx]['discountfee'] + $d[$idx]['reductionfee'];
	$print_fee = $d[$idx]['printfee'] + $d[$idx]['exchinkfee'];
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
		if(empty($d[$idx][$charge_key])) continue;
		$items .= '<tr><td colspan="4">'.$charge_name.'</td><td class="tor">'.number_format($d[$idx][$charge_key]).'<ins class="small">円</ins></td></tr>';
	}

	$total = $d[$idx]['estimated'];
	$perone = ceil($total/$d[$idx]['order_amount']);
	$tax = $d[$idx]['salestax'];
	$credit = $d[$idx]['creditfee'];
	$base = ($d[$idx]['basefee']!=$total)? $d[$idx]['basefee']: 0;

	// POST情報
	$redirect_ok = '//www.takahama428.com/user/receive_ok.php';
	$redirect_ng = '//www.takahama428.com/user/receive_ng.php';
	$redirect_can = '//www.takahama428.com'.$_SERVER['SCRIPT_NAME'].'?oi='.$orders_id;
	$redirect = '//www.takahama428.com/user/receive.php';
}else{
	$msg = "お支払いは全て済んでおります。";
	$display = "style='display:none;'";
}
?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="早い！Tシャツでオリジナルを作成するならタカハマへ！タカハマライフアートのログイン画面です。メールアドレスとパスワードを入れてください。マイページからご注文履歴などをご確認することができます。ログインにする為のパスワードをお忘れの方はこちら。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>お支払い状況 | オリジナルTシャツ【タカハマライフアート】</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_credit.css" />
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
						<div class="menu_wrap">
							<?php echo $menu;?>
						</div>
					</div>
				</div>
				<div class="pagetitle">
					<h1>お支払い状況</h1>
				</div>
				<div class="section">
					<h2>
						<?php echo $msg; ?>
					</h2>
					<?php echo $ls; ?>
				</div>
				<div class="cretxt">弊社営業時間外は、クレジットカードお取引の結果が出るまでお時間がかかる場合がございます。 お取引完了と出るまでしばらくお待ちくださいませ。 なお、再度決済を行いますと2重決済になる可能性がございますので、ご注意ください。
				</div>

				<div class="section" <?php echo $display;?>>
					<h2>ご注文情報</h2>

					<p class="note tor">注文No.
						<?php echo $d[$idx]['orderid'];?>
					</p>
					<div class="inner1">
						<table class="form_table">
							<thead>
								<tr>
									<th>商品名 / カラー</th>
									<th>サイズ</th>
									<th>単価</th>
									<th>枚数</th>
									<th>金額</th>
								</tr>
							</thead>
							<tfoot>
								<?php
								if($base>0){
									echo '<tr><th colspan="2"></th><td colspan="2">計</td><td class="tor"><ins>'.number_format($subtotal).'</ins> 円</td></tr>';
								}
								if($tax>0){
									echo '<tr><th colspan="2"></th><td colspan="2">消費税</td><td class="tor"><ins>'.number_format($tax).'</ins> 円</td></tr>';
								}
								if($credit>0){
									echo '<tr><th colspan="2"></th><td colspan="2">カード手数料</td><td class="tor"><ins>'.number_format($credit).'</ins> 円</td></tr>';
								}
								?>
									<tr class="foot_total">
										<th colspan="2"></th>
										<td colspan="2">ご注文金額</td>
										<td class="tot"><ins><?php echo number_format($total);?></ins> 円</td>
									</tr>
									<tr class="foot_perone">
										<th colspan="2"></th>
										<td colspan="2">1枚あたり</td>
										<td class="tor"><ins><?php echo number_format($perone);?></ins> 円</td>
									</tr>
							</tfoot>
							<tbody>
								<?php echo $items;?>
							</tbody>
						</table>
					</div>

					<?php
					if($d[$idx]['payment']=='credit' || $d[$idx]['payment']=='conbi'){
						$now = date('YmdHis');
						$tax = "0";
						$shopId = "9100596916967";
						$shoppass = "h3xy5z6c";
						$crediturl= 'https://p01.mul-pay.jp/link/'.$shopId.'/Multi/Entry';
						$form = '<form action="'.$crediturl.'" method="post">';
						$form .= '<input type="hidden" value="'.$shopId.'" name="ShopID">';
						$form .= '<input type="hidden" value="'.$orders_id.'" name="OrderID">';
						$form .= '<input type="hidden" value="'.$total.'" name="Amount">';
						$form .= '<input type="hidden" value="'.$now.'" name="DateTime">';
						$form .= '<input type="hidden" value="'.$tax.'" name="Tax">';
						//「ショップ ID + “|” +  オーダー ID + “|” +  利用金額   + “|” +  税送料   + “|” +  ショップパスワード   + “|” +  日時情報」
						$password = $shopId . "|" . $orders_id . "|" . $total . "|" .$tax. "|" .$shoppass. "|".$now;
						$password = md5($password);
						$form .= '<input type="hidden" value="' .$password. '" name="ShopPassString">';
						$form .= '<input type="hidden" value="'.$redirect.'" name="RetURL">';
						$form .= '<input type="hidden" value="'.$redirect_can.'" name="CancelURL">';
						//クレジットカード
						if($d[$idx]['payment']=='credit') {
							$form .= '<input type="hidden" value="1" name="UseCredit">';
							$form .= '<input type="hidden" value="CAPTURE" name="JobCd">';
							$form .= '<input type="submit" value="カード決済のお申込はこちらから">';
						} else {
							$form .= '<input type="hidden" value="1" name="UseCvs">';
							$form .= '<input type="hidden" value="有限会社タカハマライフアート" name="ReceiptsDisp11">';
							$form .= '<input type="hidden" value="03-5670-0787" name="ReceiptsDisp12">';
							$form .= '<input type="hidden" value="09:00-18:00" name="ReceiptsDisp13">';
							$form .= '<input type="submit" value="コンビニ決済のお申込はこちらから">';
						}

						/*
				$form = '<form action="https://ec.nicos.co.jp/sitop_pccmn/EC.Entry.Mall.Handler.ashx" method="post">';
				$form .= '<input type="hidden" value="60051580" name="in_kamei_id">';
				$form .= '<input type="hidden" value="00055040" name="in_n">';
				$form .= '<input type="hidden" value="'.$redirect_ok.'" name="in_redirecturl_ok">';
				$form .= '<input type="hidden" value="'.$redirect_ng.'" name="in_redirecturl_ng">';
				$form .= '<input type="hidden" value="'.$redirect_can.'" name="in_redirecturl_can">';
				$form .= '<input type="hidden" value="0" name="in_mallact_kbn">';
				$form .= '<input type="hidden" value="101" name="in_shori_kbn">';
				$form .= '<input type="hidden" value="111" name="in_moushikomi_kbn01">';
				$form .= '<input type="hidden" value="'.$orders_id.'" name="in_chumon_no">';
				$form .= '<input type="hidden" value="'.$total.'" name="in_kingaku">';
*/
						$form .= '</form>';
						echo $form;
					}
					?>
				</div>

				<ul>
					<li class="btn-group"><a class="" href="#" data-featherlight="#fl1">お支払方法について</a></li>
				</ul>

				<div class="lightbox" id="fl1">

					<div class="modal-content">
						<div class="modal_window">
							<h2>メッセージ</h2>
							<h3 class="syousai">銀行振込</h3>
							<hr>
							<p>下記の口座にお振込ください。</p>
							<p>ご希望の納品日より2日前までにお振込をお願い致します。（土日祝は入金確認ができないのでご注意ください）お振込手数料は、お客様のご負担とさせていただいております。</p>
							<dl class="list">
								<dt>銀行名</dt>
								<dd>三菱東京ＵＦＪ銀行</dd>
								<dt>支店名</dt>
								<dd>新小岩支店　744</dd>
								<dt>口座種別</dt>
								<dd>普通</dd>
								<dt>口座番号</dt>
								<dd>3716333</dd>
								<dt>口座名義</dt>
								<dd>ユ）タカハマライフアート</dd>
							</dl>
							<hr><br>
							<h3 class="syousai">代金引換</h3>
							<hr>
							<p>代金引換手数料は1件につき&yen;800（税抜）かかります。 お支払い総額（商品代+送料＋代金引換手数料＋消費税）を配送業者にお支払いください。 お客様のご都合でお支払い件数が複数になった場合、1件につき&yen;800（税抜）を追加させていただきます。
							</p>
							<hr><br>
							<h3 class="syousai">カード決済</h3>
							<hr>
							<p>各種クレジットカードがご利用いただけます。 ご希望の納品日より2日前までにカード決済手続きをお願い致します。 （土日祝は入金確認ができないのでご注意ください）カード決済システム利用料（5%）は、お客様のご負担とさせていただいております。 弊社の「マイページ」＞「お支払い状況」＞「カード決済のお申し込はこちらから」にて決済が可能です。
							</p>
							<center>
								<p><img width="60%" alt="カード種類" src="/order/img/card.png"></p>
							</center>
							<!-- <hr><br><h3 class="syousai">コンビニ決済</h3><hr>
<p>指定のコンビニエンスストアでお支払いが可能です。
支払い番号は送らせていただくメールに記載しております。
支払い手数料（一律&yen;800）はお客様負担とさせていただいております。
弊社の「マイページ」＞「お支払い状況」＞「コンビニ決済のお申し込はこちらから」にて決済が可能です。</p>
<p><img width="100%" alt="カード種類" src="/order/img/konnbini.png"></p> -->
						</div>
					</div>
				</div>
			</div>
		</div>



		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	</body>

	</html>
