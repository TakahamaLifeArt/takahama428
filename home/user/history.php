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
        <meta name="description" content="">
        <meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
        <meta property="og:url" content="https://www.takahama428.com/" />
        <meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
        <meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
        <meta property="fb:app_id" content="1605142019732010" />
        <title>ご注文履歴 - TLAメンバーズ | オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico" />
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" media="screen" href="/common/css/printposition_responsive.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="./css/my_history.css" />
        <script type="text/javascript">
            var _CUR_ORDER = <?php echo $orderid?>;

        </script>

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
                    <h1>ご注文履歴</h1>
                </div>

                <table class="form_table" id="history_table">
                    <h2>ご注文一覧</h2>
                    <tbody>
                        <?php echo $data; ?>
                    </tbody>
                </table>

                <h2 class="order_detail">注文明細</h2>

                <?php echo $hoge['estimated']; ?>
                <p class="note tor">注文No.
                    <?php echo $orders_id;?>
                </p>
                <table class="form_table" id="detail_item">
                    <thead>
                    </thead>
                    <tfoot>
                        <?php
						if($base>0){
							echo '<tr class="foot_sub"><th colspan="2"></th><td colspan="2">計</th><td class="base"><ins>'.number_format($base).'</ins> 円</td></tr>';
						}
						if($salesTax>0){
							echo '<tr class="foot_sub"><th colspan="2"></th><td colspan="2">消費税</th><td class="tax"><ins>'.number_format($salesTax).'</ins> 円</td></tr>';
						}
						if($credit>0){
							echo '<tr class="foot_sub"><th colspan="2"></th><td colspan="2">カード手数料</th><td class="credit"><ins>'.number_format($credit).'</ins> 円</td></tr>';
						}
						?>
                            <tr class="foot_total">
                                <th colspan="2"></th>
                                <td colspan="2">合計</td>
                                <td class="tot"><ins><?php echo number_format($curTotal); ?></ins> 円</td>
                            </tr>
                            <tr class="foot_perone">
                                <th colspan="2"></th>
                                <td colspan="2">1枚あたり</td>
                                <td class="per"><ins><?php echo number_format($perone); ?></ins> 円</td>
                            </tr>
                    </tfoot>
                    <tbody>
                        <?php echo $items;?>
                    </tbody>
                </table>

                <table class="form_table" id="detail_print">
                    <caption>プリント情報</caption>
                    <thead>
                        <tr>
                            <th>カテゴリー</th>
                            <th>プリント位置</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $printing;?>
                    </tbody>
                </table>

            </div>
        </div>

        <div id="printform_wrapper"><iframe id="printform" name="printform"></iframe></div>

        <footer class="page-footer">
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
        </footer>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

        <div id="overlay-mask" class="fade"></div>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
        <script type="text/javascript" src="./js/history.js"></script>
    </body>

    </html>
