<?php
/*
	領収書のPDF変換と印刷処理
	charset UTF-8
*/
if(isset($_REQUEST['orderid'])){
	$orders_id = htmlspecialchars($_REQUEST['orderid'], ENT_QUOTES);
	require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
	$conndb = new Conndb(_API);
	$data = $conndb->getPrintform($orders_id);
	if(empty($data)) exit('No such printform data exists');
	$output_count = $conndb->setReceiptCount($orders_id);
	if(empty($output_count)) exit('Error receipt count.');

	$orders = 			$data[0];
	$ordertype = 		$orders['ordertype'];
	$company = 			$orders['company'];
	
	// システムに登録データがある場合はそちらを優先
	$customer_name =	$orders['receipt_address'] ?: $orders['customername'];
	$grandTotal = 		$orders['receipt_price'] ?: $orders['estimated'];
	$proviso = 			$orders['payment'] == 'credit' ? 'クレジットカードにてお支払い' : '';
	$proviso = 			$orders['receipt_proviso'] ?: $proviso;

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
	require_once dirname(__FILE__).'/mpdf/mpdf.php';
	$pdf = new mPDF('ja','A5-L');
	$pdf->mirrorMargins = 0;
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

	$pdf->SetFooter($footer, 'O');
	$pdf->SetFooter($footer, 'E');

	$stylesheet = file_get_contents("./css/printer.css");
	$pdf->WriteHTML($stylesheet,1);


	$html = '<div style="height:240mm;">';
	
	// 出力フォーム
	$html .= '
		<div class="heading-receipt" style="font-size:14pt;">領　収　書</div>
		<div class="toright" style="margin:0;">No. '.sprintf('%09d',$orders_id).'-'.$output_count.'</div>
		<div class="toright" style="margin:0;">発行日 '.date('Y-m-d').'</div>

		<p style="margin:0; font-size:13pt;border-bottom:1px solid #000;width:290px;">';
	if($ordertype=="general"){
		$html .= $customer_name;
		if(!empty($company) && empty($orders['receipt_address'])){
			$html .= '<br />';
			$html .= '<span style="font-size:13pt;">'.$company.'　様</span></p>';
		}else{
			$html .= '　様</p>';
		}
	}else{
		$html .= '<span style="font-size:13pt;">'.$customer_name.'　様</span></p>';
	}

	$html .= '
		<div style="width:600px; margin:0 auto 0;">
			<p style="font-size:16pt;font-weight:bold;padding:.5rem 0;text-align:center;background-color:#eee;">
				<span style="font-size:100%;font-weight:bold;color:#000;">&yen;'.number_format($grandTotal).' －</span>
			</p>
			<p class="ml-3">但　<span class="pl-3">' . $proviso . '</span></p>
			<p class="ml-3">上記正に領収いたしました</p>
		</div>
		
		<div class="company_logo">
			<div id="issuer" style="margin-top:-1rem;">
				<p style="font-size:12pt;margin:0;">'.$sender.'</p>
				<p style="margin:0;">〒'.$sender_zipcode.'<br />'.$sender_addr.'<br />TEL： '.$sender_tel.'　　FAX： '.$sender_fax.'<br />E-mail： '.$sender_email.'</p>
				<p class="toright" style="margin:0;">担当： '.$sender_staff.'</p>
			</div>
		</div>
	</div>';

	$pdf->WriteHTML($html);

	$pdf->Output('receipt-'.$orders_id.'.pdf', 'D');

}
?>
