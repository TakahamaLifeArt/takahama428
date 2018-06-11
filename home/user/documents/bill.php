<?php
/*
	請求書のPDF変換と印刷処理
	charset UTF-8
*/
	if(isset($_REQUEST['orderid'])){
		$orders_id = htmlspecialchars($_REQUEST['orderid'], ENT_QUOTES);
		$month = htmlspecialchars($_REQUEST['m'], ENT_QUOTES);
		$day = htmlspecialchars($_REQUEST['d'], ENT_QUOTES);
		$weekname = htmlspecialchars($_REQUEST['w'], ENT_QUOTES);
		require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
		
		$conndb = new Conndb(_API);
		$data = $conndb->getPrintform($orders_id);
		if(empty($data)) exit('No such printform data exists');
		$orders = 			$data[0];
		$curdate = 			$orders['schedule3'];
		$ordertype = 		$orders['ordertype'];
		$customer_id = 		strtoupper($orders['cstprefix']).$orders['number'];
		$company = 			$orders['company'];
		$customer_name =	$orders['customername'];
		$ic = 				$orders['staffname'];
		$zipcode = 			preg_replace('/^(\d{3})(\d{1,4})$/', '$1-$2', $orders['delizipcode']);
		$deli1 = 			$orders['addr0'].$orders['addr1'];
		$deli2 = 			$orders['addr2'];
		$billnote = 		$orders['billnote'];
		
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
		
		// 別の宛名を指定しいる場合
		if(isset($_REQUEST['altname'])){
			$company = 			"";
			$customer_name =	htmlspecialchars($_REQUEST['altname'], ENT_QUOTES);
			$zipcode = 			htmlspecialchars($_REQUEST['altzipcode'], ENT_QUOTES);
			$deli1 = 			nl2br(htmlspecialchars($_REQUEST['altaddress'], ENT_QUOTES));
			$deli2 = 			"";
		}
		
		// 別の差出人を指定しいる場合
		if(isset($_REQUEST['sendername'])){
			$sender = htmlspecialchars($_REQUEST['sendername'], ENT_QUOTES);
			$sender_zipcode = htmlspecialchars($_REQUEST['senderzipcode'], ENT_QUOTES);
			$sender_addr = htmlspecialchars($_REQUEST['senderaddress'], ENT_QUOTES);
			$sender_tel = htmlspecialchars($_REQUEST['sendertel'], ENT_QUOTES);
			$sender_fax = htmlspecialchars($_REQUEST['senderfax'], ENT_QUOTES);
			$sender_email = htmlspecialchars($_REQUEST['senderemail'], ENT_QUOTES);
			$sender_staff = htmlspecialchars($_REQUEST['senderstaff'], ENT_QUOTES);
		}
		
		
		/* PDF変換 */
		require_once dirname(__FILE__).'/MPDF_6_0/mpdf.php';
		$pdf = new mPDF('ja','A4');
		$pdf->mirrorMargins = 0;
		/*
		$pdf->defaultfooterfontsize = 12;
		$pdf->defaultfooterfontstyle = B;
		*/
		
		$pdf->defaultfooterline = 1;
		$footer = array(
		'C' => array(
			'content' => 'Takahama Life Art',
			'font-style' => 'BI',
			'font-size' => '9',
			'color' => '#aaaaaa'
		),
		'line' => 0,
		);
		
		/*
		$header = array(
		'R' => array(
			'content' => 'No. '.sprintf('%09d',$orders_id),
			'font-size' => '9'
		),
		'line' => 0,
		);
		
		$pdf->SetHeader($header, 'O');
		$pdf->SetHeader($header, 'E');
		*/
		
		$pdf->SetFooter($footer, 'O');
		$pdf->SetFooter($footer, 'E');
		
		$stylesheet = file_get_contents("./css/printer.css");
		$pdf->WriteHTML($stylesheet,1);
		
		
		$html = '<div style="height:240mm;">';
		
		// 明細
		$details = '<table class="estimation" style="margin:20 0 20 0;">';

		if($ordertype=="general"){
		// 一般
			$tot_price = 0;
			$tbl = '<tbody>';
			$count = count($data);
			for($i=0; $i<$count; $i++){
				/* 商品単価を取得
				if($data[$i]['master_id']==0){
					$tmp = explode('_', $data[$i]['stock_number']);
					$data[$i]['item_code'] = $tmp[0];
					$data[$i]['maker_name'] = $data[$i]['maker'];
					$data[$i]['color_name'] = $data[$i]['item_color'];
					$cost = $data[$i]['price'];
				}else{
					if( ($data[$i]['color_id']==59 && $data[$i]['item_id']!=112) || ($data[$i]['color_id']==42 && ($data[$i]['item_id']==112 || $data[$i]['item_id']==212)) ) $isWhite=1;
					else $isWhite=0;
					$isPrint = $orders['noprint']==0? 1: 0;
					$cost = intval($catalog->getItemPrice($data[$i]['item_id'], $data[$i]['size_id'], $isPrint, $isWhite, $curdate), 10);
					$cost = $data[$i]['price'];
				}
				*/
				
				// 商品ごとに小計
				$subtotal = $data[$i]['cost']*$data[$i]['amount'];
				// 合計を加算
				$tot_amount += $data[$i]['amount'];
				$tot_price += $subtotal;

				// 2013-11-01 金額0円の商品も記載
				// if($cost==0) continue;
				
				$id = $i+1;
				$tbl.='<tr><td style="width:25px;text-align:center;">'.$id.'</td><td style="text-align:left;font-size:90%;">'.$data[$i]['item']."<br />色：".$data[$i]['color'].'</td>';
				$tbl.='<td>'.$data[$i]['size'].'</td>';
				
				//$tbl.='<td>'.$data[$i]['price_0'].' - '.$data[$i]['taxratio'].'</td>';
				$tbl.='<td>'.number_format($data[$i]['amount']).'</td>';
				$tbl.='<td>'.number_format($data[$i]['cost']).'</td>';
				$tbl.='<td>'.number_format($subtotal).'</td></tr>';
			}
			
			$pack_mode = array();
			if($orders['package_yes']==1 || $orders['package_no']==1){
				$pack_mode[] = '袋詰め代';
			}
			if($orders['package_nopack']==1){
				$pack_mode[] = '袋代';
			}
			$pack_mode = implode(', ', $pack_mode);
			if(empty($pack_mode)){
				if($orders['package']=='nopack'){
					$pack_mode = '袋代';
				}else{
					$pack_mode = '袋詰め代';
				}
			}
			$tbl.= '<tr><td colspan="2"></td><th>商品計</th><td>'.number_format($tot_amount).' 枚</td><td colspan="2">'.number_format($tot_price).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">プリント代</td><td>'.number_format($orders['printfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">インク色替え代</td><td>'.number_format($orders['exchinkfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">'.$pack_mode.'</td><td>'.number_format($orders['packfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">割　引 ('.implode(',', $orders['discount']).')</td><td>'.number_format($orders['discountfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">値　引 ('.$orders['reductionname'].')</td><td>'.number_format($orders['reductionfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">特急料金</td><td>'.number_format($orders['expressfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">送　料</td><td>'.number_format($orders['carriagefee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">特別送料</td><td>'.number_format($orders['extracarryfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">デザイン料</td><td>'.number_format($orders['designfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">代金引換手数料</td><td>'.number_format($orders['codfee']).'</td></tr>
					<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">後払い手数料</td><td>'.number_format($orders['paymentfee']).'</td></tr>';
			
			if(!empty($orders['additionalfee'])){
				$tbl .= '<tr><td style="border-right:none;"></td><td colspan="4" style="text-align:left;border-left:none;">'.$orders['additionalname'].'</td><td>'.number_format($orders['additionalfee']).'</td></tr>';
			}
			
			// オプション計
			$optionfee += $orders['printfee']+$orders['exchinkfee']+$orders['packfee']+$orders['discountfee']+$orders['reductionfee']+$orders['expressfee']+$orders['carriagefee']+$orders['extracarryfee']+$orders['designfee']+$orders['codfee']+$orders['paymentfee']+$orders['additionalfee'];
			$total = $tot_price + $optionfee;	// 総合計
			
			$details .= '<thead>
					<tr><th>No.</th><th colspan="2">商品名</th><th style="width:100px;">数量</th><th>商品単価</th><th>金額</th></tr>
				</thead>
				<tfoot>';
			if($_TAX>0){
				$tax = floor($total*$_TAX);			// 消費税
				$sum = floor($total*(1+$_TAX));		// 見積り合計
				$details .= '
					<tr><td colspan="4" style="border:none;"></td><th>小　　計</th><td>'.number_format($total).'</td></tr>
					<tr><td colspan="4" style="border:none;"></td><th>消費税額</th><td>'.number_format($tax).'</td></tr>';
				if($orders['payment']=='credit'){
					$sum += $orders['creditfee'];
					$details .= '<tr><td colspan="4" style="border:none;"></td><th>カード手数料</th><td>'.number_format($orders['creditfee']).'</td></tr>';
				}
				$details .= '
					<tr><td colspan="4" style="border:none;"></td><th>合　　計</th><td>'.number_format($sum).'</td></tr>
				</tfoot>';
			}else{
				$sum = $total;					// 見積り合計
				if($orders['payment']=='credit'){
					$sum += $orders['creditfee'];
					$details .= '<tr><td colspan="4" style="border:none;"></td><th>カード手数料</th><td>'.number_format($orders['creditfee']).'</td></tr>';
				}
				$details .= '
					<tr><td colspan="4" style="border:none;"></td><th>合　　計</th><td>'.number_format($sum).'</td></tr>
				</tfoot>';
			}
			
			$details.= '<tbody>'.$tbl;
			$details .= '</tbody></table>';
		}else{
		// 業者
			$tot_price = 0;
			$tbl = '<tbody>';
			$count = count($data);
			for($i=0; $i<$count; $i++){
				$tot_amount += $data[$i]['amount'];
				$subtotal = $data[$i]['price']*$data[$i]['amount'];
				$tot_price += $subtotal;
				
				if($subtotal==0) continue;
				
				$id = $i+1;
				$tbl.='<tr><td style="width:25px;text-align:center;">'.$id.'</td><td style="text-align:left;font-size:90%;">'.$data[$i]['item_name']."<br />色：".$data[$i]['item_color'].'</td>';
				$tbl.='<td>'.$data[$i]['size_name'].'</td>';
				$tbl.='<td>'.number_format($data[$i]['amount']).'</td>';
				$tbl.='<td>'.number_format($data[$i]['price']).'</td>';
				$tbl.='<td>'.number_format($subtotal).'</td></tr>';
			}

			$tbl.= '<tr><td colspan="2"></td><th>商品計</th><td><p>'.number_format($tot_amount).' 枚</p></td><td colspan="2"><p style="font-size:100%;">'.number_format($tot_price).'</p></td></tr>';

			// 追加行情報
			$result = $DB->db('search', 'estimatedetails', array('orders_id'=>$orders_id));
			if(!empty($result)){
				for($i=0; $i<count($result); $i++){
					$tbl .= '
						<tr><td></td><td style="text-align:left;">'.$result[$i]['addsummary'].'</td>
						<td style="text-align:left;">'.$result[$i]['addgroup'].'</td>
						<td>'.number_format($result[$i]['addamount']).'</td>
						<td>'.number_format($result[$i]['addcost']).'</td>
						<td>'.number_format($result[$i]['addprice']).'</td></tr>';
					// オプション計
					$optionfee += $result[$i]['addprice'];
				}
			}
			
			$total = $tot_price + $optionfee;	// 小計
			
			$details .= '<thead>
					<tr><th>No.</th><th colspan="2">商品名</th><th style="width:100px;">数量</th><th>商品単価</th><th>金額</th></tr>
				</thead>';
			
			$tax = floor($total*$_TAX);			// 消費税
			$sum = floor($total*(1+$_TAX));		// 見積り合計
			$details .= '
				<tfoot>
					<tr><td colspan="4" style="border:none;"></td><th>小　　計</th><td>'.number_format($total).'</td></tr>
					<tr><td colspan="4" style="border:none;"></td><th>消費税額</th><td>'.number_format($tax).'</td></tr>
					<tr><td colspan="4" style="border:none;"></td><th>合　　計</th><td>'.number_format($sum).'</td></tr>
				</tfoot>';

			$details .= '<tbody>'.$tbl;
			$details .= '</tbody></table>';
		}

		$details .= '<p style="margin:0;">【　備　考　】</p>';
		$details .= '<div style="padding:0px 5px; border:1px solid #a9a9a9;"><p>'.nl2br($billnote).'</p></div>';
		
		// 出力フォーム
		$html .= '
		<div class="heading1" style="margin:-30 auto 20;letter-spacing:5mm;font-size:12pt;">請　求　書<span class="copy"></span></div>
		<div class="toright" style="margin:0;">請求日 '.$orders['schedule4'].'　　請求No. '.sprintf('%09d',$orders_id).'</div>
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
		$html .= '<p style="both;margin:0;">〒'.$zipcode.'<br />'.$deli1.'<br />'.$deli2.'</div>';

		$html .= '
		<div style="float:right; width:280px; margin:5px 0px 10px 0px;">
			<p style="font-size:12pt;margin:0;">'.$sender.'</p>
			<p style="margin:0;">〒'.$sender_zipcode.'<br />'.$sender_addr.'<br />TEL： '.$sender_tel.'　　FAX： '.$sender_fax.'<br />E-mail： '.$sender_email.'</p>
			<p class="toright" style="margin:0;">担当： '.$sender_staff.'</p>
		</div>

		<div class="contents">';
		
		$html .= '
		<p style="margin:10 0 0 0;">平素は格別のご高配を賜り、誠にありがとうございます。下記の通り御請求申し上げます</p>
		<p style="font-size:14pt;font-weight:bold;margin:0;">御請求金額　<ins style="font-size:100%;font-weight:bold;color:#003f75;">&yen;'.number_format($sum).' －</ins> （消費税含む）</p>';
		
		$html .= $details;
		$html .= '</div></div>';

		$pdf->WriteHTML($html);
		if($ordertype=="general"){
			$doc = '<div style="margin-top:1em;"><table><tbody><tr><td>振込先　三菱東京UFJ銀行　新小岩支店<br />普通預金 3716333　有限会社タカハマライフアート</td>';
			$doc .= '<td>※ご入金確認後の発送となりますので、'.$month.'月'.$day.'日（'.$weekname.'）午前中までに<br />';
			$doc .= '弊社指定口座にお振込みをお願いいたします。<br />';
			$doc .= '依頼名の前にコードナンバー<span style="font-size:14pt;font-weight:bold;">'.$customer_id.'</span>を必ずご記入ください。</td></tr>';
			$doc .= '</tbody></table></div>';
			$pdf->WriteHTML($doc);
		}
		
		$pdf->Output('bill-'.$orders_id.'.pdf', 'D');
		
	}
?>
