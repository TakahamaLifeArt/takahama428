<?php
/*
	納品書のPDF変換と印刷処理
	charset UTF-8
*/
	if(isset($_REQUEST['orderid'])){
		$orders_id = htmlspecialchars($_REQUEST['orderid'], ENT_QUOTES);
		require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
		$conndb = new Conndb(_API);
		$data = $conndb->getPrintform($orders_id);
		if(empty($data)) exit('No such printform data exists');

		$orders = 			$data[0];
		$curdate = 			$orders['schedule3'];
//		$bill = 			$orders['bill'];	// 1:都度請求、　2:月締め請求
		$bill = 2;	// 固定
		$maintitle = 		$orders['maintitle'];
		$invoicenote = 		$orders['invoicenote'];
		$ordertype = 		$orders['ordertype'];
		$customer_id = 		strtoupper($orders['cstprefix']).$orders['number'];
		$company = 			$orders['company'];
		$customer_name =	$orders['customername'];
		$ic = 				$orders['staffname'];
		$zipcode = 			preg_replace('/^(\d{3})(\d{1,4})$/', '$1-$2', $orders['delizipcode']);
		$deli1 = 			$orders['addr0'].$orders['addr1'];
		$deli2 = 			$orders['addr2'];
		
		// 消費税
		if($ordertype!='general'){
			$_TAX = $orders['taxratio'];
			$_TAX /= 100;
		}else{
			if($orders['salestax']>0){
				$_TAX = $orders['taxratio'];
				$_TAX /= 100;
			}else{
				$_TAX = 0;
			}
		}
		
		// 差出人
		$sender = '有限会社タカハマライフアート';
		$sender_zipcode = '124-0025';
		$sender_addr = '東京都葛飾区西新小岩3-14-26';
		$sender_tel = '03-5670-0787';
		$sender_fax = '03-5670-0730';
		$sender_email = 'info@takahama428.com';
		$sender_staff = $orders['staffname'];;
		
		/* PDF変換 */
		require_once dirname(__FILE__).'/MPDF_6_0/mpdf.php';
		$pdf = new mPDF('ja','A4', '','',15, 15, 16, 6, 9, 0);
		$pdf->mirrorMargins = 0;
		
		$pdf->defaultheaderfontsize = 10;	/* in pts */
		$pdf->defaultheaderfontstyle = I;	/* blank, B, I, or BI */
		
		$header = array(
		'R' => array(
			'content' => 'Page {PAGENO}/{nb}',
			'font-size' => '9'
		),
		'line' => 0,
		);
		
		$pdf->SetHeader($header, 'O');
		$pdf->SetHeader($header, 'E');
		
		/*
		$pdf->defaultfooterfontsize = 12;
		$pdf->defaultfooterfontstyle = B;
		
		$pdf->defaultfooterline = 1;
		$footer = array(
		'C' => array(
			'content' => "Takahama Life Art",
			'font-style' => 'BI',
			'font-size' => '9',
			'color' => '#000000'
		),
		'line' => 0,
		);
		
		
		$header = array(
		'R' => array(
			'content' => 'Page {PAGENO}/{nb}',
			'font-size' => '9'
		),
		'line' => 0,
		);
		
		$pdf->SetHeader($header, 'O');
		$pdf->SetHeader($header, 'E');

		$pdf->SetFooter($footer, 'O');
		$pdf->SetFooter($footer, 'E');
		*/
		
		$stylesheet = file_get_contents("./css/printer.css");
		$pdf->WriteHTML($stylesheet,1);
		
		// 明細
		$details = '<table class="invoice" style="margin:0;border:2px solid #000000;">';

		$productfee = 0;
		$rowcount=0;
		$row = '';
		$tbl = array();
		
		if($ordertype=="general"){
		// 一般
			$tmp = array();
			$count = count($data);
			for($i=0; $i<$count; $i++){
				/* 商品単価を取得
				if($data[$i]['category_id']==0){
					$cost = $data[$i]['price'];
				}else{
					if( ($data[$i]['color_id']==59 && $data[$i]['item_id']!=112) || ($data[$i]['color_id']==42 && ($data[$i]['item_id']==112 || $data[$i]['item_id']==212)) ) $isWhite=1;
					else $isWhite=0;
					$isPrint = $orders['noprint']==0? 1: 0;
					$cost = intval($catalog->getItemPrice($data[$i]['item_id'], $data[$i]['size_id'], $isPrint, $isWhite, $curdate), 10);
				}
				*/
				
				// 同じアイテムで単価が同じものは合算する
				$key = $data[$i]['itemid'].'_'.$data[$i]['cost'];
				
				$subtotal = $data[$i]['cost']*$data[$i]['amount'];
				$tmp[$key]['item_name'] = $data[$i]['item'];
				$tmp[$key]['amount'] += $data[$i]['amount'];
				$tmp[$key]['cost'] = $data[$i]['cost'];
				$tmp[$key]['subtotal'] += $subtotal;
				$tmp[$key]['range'] = $data[$i]['range'];
				
				if($data[$i]['white']==1){
					$tmp[$key]['color'] = 'ホワイト';
				}else{
					$tmp[$key]['color'] = 'カラー';
				}
				/*
				if($data[$i]['size_id']==0){
					$tmp[$key]['size_id'] = null;
				}else{
					$tmp[$key]['size_id'] = $data[$i]['size_id'];
				}
				*/
				
				// 合計を加算
				$tot_amount += $data[$i]['amount'];
				$productfee += $subtotal;
			}
			
			foreach($tmp as $key=>$val){
			/*
				ksort($val['sizelist']);
				$big = end($val['sizelist']);
				$small = reset($val['sizelist']);
				if($big==$small){
					$sizelist = $big;
				}else{
					$sizelist = $small.'-'.$big;
				}
			*/
			
				$sizelist = '';
				if(!is_null($val['range'])){
					$big = end($val['range']);
					$small = reset($val['range']);
					$sizelist = '('.$small.'- '.$big.' '.$val['color'].')';
				}
				$rowcount++;
				$row = '<tr>';
				$row .= '<td style="text-align:left;">'.$val['item_name'].$sizelist.'</td>';
				$row .= '<td>'.number_format($val['amount']).'</td>';
				$row .= '<td>'.number_format($val['cost']).'</td>';
				$row .= '<td>'.number_format($val['subtotal']).'</td></tr>';
				
				$tbl[] = $row;
			}
			
			/*
			$tbl .= '<tr>';
			$tbl .= '<td style="text-align:left;">商品代</td>';
			$tbl .= '<td>'.number_format($orders['order_amount']).'</td>';
			$tbl .= '<td>'.number_format(round($orders['productfee']/$orders['order_amount'])).'</td>';
			$tbl .= '<td>'.number_format($orders['productfee']).'</td></tr>';
				
			$tbl.= '<tr><td colspan="2"></td><th>商品計</th><td><p>'.number_format($tot_amount).' 枚</p></td><td colspan="2"><p style="font-size:120%;">&yen; '.number_format($tot_price).'</p></td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">プリント代</td><td>'.number_format($orders['printfee']).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">インク色替え代</td><td>'.number_format($exchinkfee).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">袋詰め代</td><td>'.number_format($packfee).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">割　引 '.$discount_name.'</td><td>'.number_format($discountfee).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">値　引</td><td>'.number_format($reductionfee).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">特急料金</td><td>'.number_format($expressfee).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">送　料</td><td>'.number_format($carriagefee).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">特別送料</td><td>'.number_format($extracarryfee).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">デザイン料</td><td>'.number_format($designfee).'</td></tr>
					<tr><td></td><td colspan="4" style="text-align:left;">代金引換手数料</td><td>'.number_format($codfee).'</td></tr>';
			*/
			
			
			if($orders['package_yes']==1){
				$pack_yes_fee = 50*$orders['pack_yes_volume'];
				$pack_hash['袋詰め代'] = array('cost'=>50,'amount'=>$orders['pack_yes_volume']);
			}
			if($orders['package_nopack']==1){
				$pack_nopack_fee = 10*$orders['pack_nopack_volume'];
				$pack_hash['袋代'] = array('cost'=>10,'amount'=>$orders['pack_nopack_volume']);
			}
			if(empty($pack_yes_fee) && empty($pack_nopack_fee)){	// 旧タイプに対応
				if($orders['package']=='yes'){
					$pack_yes_fee = $orders['packfee'];
					$pack_hash['袋詰め代'] = array('cost'=>50,'amount'=>$orders['order_amount']);
				}else{
					$pack_nopack_fee = $orders['packfee'];
					$pack_hash['袋詰め代'] = array('cost'=>10,'amount'=>$orders['order_amount']);
				}
			}
			
			$opt = array(
				'プリント代'=>$orders['printfee']+$orders['exchinkfee'],
				'割　引'=>$orders['discountfee'],
				'値　引'=>$orders['reductionfee'],
				'袋詰め代'=>$pack_yes_fee,
				'袋代'=>$pack_nopack_fee,
				'デザイン代'=>$orders['designfee'],
				'特急料金'=>$orders['expressfee'],
				'送　料'=>$orders['carriagefee']+$orders['extracarryfee'],
				'代引手数料'=>$orders['codfee'],
				$orders['additionalname']=>$orders['additionalfee'],
				'カード決済手数料'=>$orders['creditfee'],
				'後払い手数料'=>$orders['paymentfee'],
			);
			
			foreach($opt as $remarks=>$price){
				if(!empty($price)){
					$rowcount++;
					if($remarks=='袋詰め代' || $remarks=='袋代'){
						$row = '<tr><td style="text-align:left;">'.$remarks.'</td><td>'.number_format($pack_hash[$remarks]['amount']).'</td><td>'.$pack_hash[$remarks]['cost'].'</td><td>'.number_format($price).'</td></tr>';
					}else if($remarks=='割　引'){
						$fee = -1 * $price;
						$row = '<tr><td style="text-align:left;">割　引 ('.implode(',', $orders['discount']).')</td><td>1</td><td>- '.number_format($fee).'</td><td>- '.number_format($fee).'</td></tr>';
					}else if($remarks=='値　引'){
						$fee = -1 * $price;
						$row = '<tr><td style="text-align:left;">値　引 ('.$orders['reductionname'].')</td><td>1</td><td>- '.number_format($fee).'</td><td>- '.number_format($fee).'</td></tr>';
					}else if($remarks=='代引手数料'){
						$row = '<tr><td style="text-align:left;">代引手数料</td><td>1</td><td>'.number_format($price).'</td><td>'.number_format($price).'</td></tr>';
					}else{
						$row = '<tr><td style="text-align:left;">'.$remarks.'</td><td>1</td><td>'.number_format($price).'</td><td>'.number_format($price).'</td></tr>';
					}
					
					$tbl[] = $row;
				}
			}
						
			// オプション計
			$optionfee += $orders['printfee']+$orders['exchinkfee']+$orders['packfee']+$orders['discountfee']+$orders['reductionfee']+$orders['expressfee']+$orders['carriagefee']+$orders['designfee']+$orders['codfee']+$orders['paymentfee']+$orders['additionalfee'];

			$total = $productfee + $optionfee;	// 総合計
			$tax = 0;							// 消費税
			
			$details .= '<thead>
					<tr><th>商品名</th><th style="width:60px;">数量</th><th style="width:100px;">単価</th><th style="width:100px;">金額</th></tr>
				</thead>
				
				<tfoot>';
			if($_TAX>0){
				$tax = floor($total*$_TAX);			// 消費税
				$sum = floor($total*(1+$_TAX));		// 見積り合計
				if($orders['creditfee']>0){			// カード決済手数料
					$sum += $orders['creditfee'];
				}
				$details .= '
					<tr><td colspan="4" style="text-align:right; padding-top:5px;">
					<span style="font-size:105%;">税抜額 '.number_format($total).'　　　</span>
					<span style="font-size:105%;">消費税額 '.number_format($tax).'　　　</span>
					<span style="font-size:120%;">合計 '.number_format($sum).'</span></td></tr>
				</tfoot>';
			}else{
				$sum = $total;
				if($orders['creditfee']>0){			// カード決済手数料
					$sum += $orders['creditfee'];
				}
				$details .= '
					<tr><td colspan="4" style="text-align:right; padding-top:5px;">
					<span style="font-size:120%;">合計　'.number_format($sum).'</span></td></tr>
				</tfoot>';
			}
			$details.= '<tbody>';
			
		}else{
		// 業者
			$tmp = array();
			$count = count($data);
			for($i=0; $i<$count; $i++){
				// 商品単価を取得
				$cost = $data[$i]['price'];
				// 同じアイテムで単価が同じものは合算する
				$key = $data[$i]['item_name'].'_'.$cost;
				
				$subtotal = $cost*$data[$i]['amount'];
				if($subtotal==0) continue;
				$tmp[$key]['item_id'] = $data[$i]['item_id'];
				$tmp[$key]['item_name'] = $data[$i]['item_name'];
				$tmp[$key]['amount'] += $data[$i]['amount'];
				$tmp[$key]['cost'] = $cost;
				$tmp[$key]['subtotal'] += $subtotal;
				
				if($data[$i]['item_id']==99999){
					$tmp[$key]['size_name'] = null;
				}else{
					$tmp[$key]['size_name'] = $data[$i]['size_name'];
				}
				
				if($data[$i]['item_id']==0 || $data[$i]['item_id']==99999){
					$tmp[$key]['color'] = null;
				}else{
					$tmp[$key]['color'] = $data[$i]['item_color']!='ホワイト'? 'カラー': 'ホワイト';
				}
				
				// 合計を加算
				$tot_amount += $data[$i]['amount'];
				$productfee += $subtotal;
			}
						
			foreach($tmp as $key=>$val){
				$sizelist = '';
				if(!is_null($val['color'])){
					$res = $catalog->getTableList('sizerange', $val['item_id'], $val['size_name'], $curdate);
					$small = $res['range'][0];
					$big = end($res['range']);
					$sizelist = '('.$small.'- '.$big.' '.$val['color'].')';
				}
				
				$row = '<tr>';
				$row .= '<td style="text-align:left;">'.$val['item_name'].$sizelist.'</td>';
				$row .= '<td>'.number_format($val['amount']).'</td>';
				$row .= '<td>'.number_format($val['cost']).'</td>';
				$row .= '<td>'.number_format($val['subtotal']).'</td></tr>';
				$rowcount++;
				
				$tbl[] = $row;
			}
			
			/*
			for($i=0; $i<$count; $i++){
				$tot_amount += $data[$i]['amount'];
				$subtotal = $data[$i]['price']*$data[$i]['amount'];
				$tot_price += $subtotal;				
			}
			
			$tbl = '<tbody>';
			if($tot_price==0){
				$a=0;
			}else{
				$tbl .= '<tr>';
				$tbl .= '<td style="text-align:left;">商品代</td>';
				$tbl .= '<td>'.number_format($tot_amount).'</td>';
				$tbl .= '<td>'.number_format(round($tot_price/$tot_amount)).'</td>';
				$tbl .= '<td>'.number_format($tot_price).'</td></tr>';
				
				$a=1;
			}
			*/
			
			// 追加行情報
			$result = $DB->db('search', 'estimatedetails', array('orders_id'=>$orders_id));
			if(!empty($result)){
				for($i=0; $i<count($result); $i++){
					if(empty($result[$i]['addprice'])) continue;
					$row = '
						<tr><td style="text-align:left;">'.$result[$i]['addsummary'].'</td>
						<td>'.number_format($result[$i]['addamount']).'</td>
						<td>'.number_format($result[$i]['addcost']).'</td>
						<td>'.number_format($result[$i]['addprice']).'</td></tr>';
				
					// オプション計
					$optionfee += $result[$i]['addprice'];
					$rowcount++;
					
					$tbl[] = $row;
				}
			}
			
			$total = $productfee + $optionfee;	// 小計
			
			$details .= '<thead>
					<tr><th>商品名</th><th style="width:60px;">数量</th><th style="width:100px;">単価</th><th style="width:100px;">金額</th></tr>
				</thead>';
			
			$tax = floor($total*$_TAX);			// 消費税
			$sum = floor($total*(1+$_TAX));		// 見積り合計
			$details .= '
				<tfoot>
					<tr><td colspan="4" style="text-align:right; padding-top:5px;"><span style="font-size:75%;">( '.$maintitle.' )　　　</span>
					<span style="font-size:105%;">税抜額 '.number_format($total).'　　　</span>
					<span style="font-size:105%;">消費税額 '.number_format($tax).'　　　</span>
					<span style="font-size:110%;">合計 '.number_format($sum).'</span></td></tr>
				</tfoot>';
			$details .= '<tbody>';
		}

		// $details.= '<p style="padding:5px;"><ins>　備　考　</ins></p>';

		if($bill==1){
			$title = "納品書／請求書";
		}else{
			$title = "納　品　書";
		}
		$title2 = '<p margin:0;>平素は格別のご高配を賜り、誠にありがとうございます。下記の通り納品申し上げます。</p>';

		// 出力フォーム
		$html_title1 = '<div style="height:450px;">
		<div class="heading1" style="margin:0 auto 20;letter-spacing:5mm;font-size:12pt;">'.$title.'<span class="copy" style="font-size:12pt;">（控）</span></div>';
		
		$html_title2 = '<div style="clear:both; height:450px;">
		<div class="heading1" style="margin:0 auto 20;letter-spacing:5mm;font-size:12pt;">'.$title.'</div>';
		
		$html = '<div class="toright" style="margin:0;">発送日 '.$orders['schedule3'].'　　伝票No. '.sprintf('%09d',$orders_id).'</div>
		<div class="customer_info">
			[ '.$customer_id.' ]
			<p style="margin:0; font-size:13pt;border-bottom:1px solid #000;">';
		if($ordertype=="general"){
			$html .= $customer_name;
			if(!empty($company)){
				$html .= '<br />';
				$html .= '<span style="font-size:13pt;">'.$company.'　様</span></p>';
			}else{
				$html .= '　様</p>';
			}
		}else{
			$html .= '<span style="font-size:13pt;">'.$customer_name.'　御中</span></p>';
		}
		$html .= '<p style="clear:both;margin:0;">〒'.$zipcode.'<br />'.$deli1.'<br />'.$deli2.'</div>';

		$html .= '
		<div style="float:right; width:280px; margin:5px 0px 0px 0px;">
			<p style="font-size:12pt;margin:0;">'.$sender.'</p>
			<p style="margin:0;">〒'.$sender_zipcode.'<br />'.$sender_addr.'<br />TEL： '.$sender_tel.'　　FAX： '.$sender_fax.'<br />E-mail： '.$sender_email.'</p>
			<p class="toright" style="margin:0;">担当： '.$sender_staff.'</p>

		</div>
		<div class="contents">';


		// 出力
		$rowtotal = count($tbl);
		$pageno = 1;
		$rows = 0;
		$nb = ceil($rowtotal/14);
		for($i=0; $i<$rowtotal; $i++){
			if($i%14==0 && $i>0){
				//$doc = $html_title1.$html.$title2.$details.$line.'</tbody></table></div></div>';
				//$pdf->WriteHTML($doc);
				
				//$page = '<div style="float:right; width:100px; text-align:right; font-size:9pt;">Page '.$pageno.'/'.$nb.'</div>';
				$page = '';
				$doc = $page.$html_title2.$html.$title2.$details.$line.'</tbody></table></div></div>';
				$pdf->WriteHTML($doc);
				/*
				$doc = '<div style="margin-top: 0.5em;">';
				$doc .= '<table style="width:100%;"><tbody><tr><td style="width:4em; vertical-align:top; padding:0 1em;">摘要</td>';
				$doc .= '<td style="height:3em; border:1px solid #666; line-height:1.1;">'.nl2br($invoicenote).'</td></tr>';
				$doc .= '</tbody></table></div>';
				$pdf->WriteHTML($doc);
				*/
				$pdf->AddPage();
				$line = '';
				$rows = 0;
				$pageno++;
			}
			$line .= $tbl[$i];
			$rows++;
		}
		
		for($i=$rows; $i<14; $i++){
			$line .= '<tr><td>　</td><td>　</td><td>　</td><td>　</td></tr>';
		}
		
		//$doc = $html_title1.$html.$title2.$details.$line.'</tbody></table></div></div>';
		//$pdf->WriteHTML($doc);

		//$page = '<div style="float:right; width:100px; text-align:right; font-size:9pt; margin-top:30px;">Page '.$pageno.'/'.$nb.'</div>';
		$page = '';
		$doc = $page.$html_title2.$html.$title2.$details.$line.'</tbody></table></div></div>';
		$pdf->WriteHTML($doc);
		
		$doc = '<div style="margin-top: 0.5em;">';
		$doc .= '<table style="width:100%;"><tbody><tr><td style="width:4em; vertical-align:top; padding:0 1em;">摘要</td>';
		$doc .= '<td style="height:3em; border:1px solid #666; line-height:1.1;">'.nl2br($invoicenote).'</td></tr>';
		$doc .= '</tbody></table></div>';
		$pdf->WriteHTML($doc);
				
		$pdf->Output();	
				
					
		/* 2012-02-18 廃止
		if($ordertype=="general"){
			$doc = '<div style="margin-top: 1em;"><table><tbody><tr><td>振込先　三菱東京UFJ銀行　新小岩支店<br />普通預金 3716333　有限会社タカハマライフアート</td>';
			$doc .= '<td>※商品到着後１週間位でお振込みお願いします<br />';
			$doc .= '依頼名の前にコードナンバー<span style="font-size:14pt;font-weight:bold;">'.$orders_id.'</span>を必ずご記入ください</td></tr>';
			$doc .= '</tbody></table></div>';
			$pdf->WriteHTML($doc);
		}
		*/
		
	}

?>
