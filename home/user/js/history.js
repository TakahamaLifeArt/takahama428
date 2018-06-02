/*
	注文履歴
	Log:	2014-01-17 created
			2017-10-14 scroll機能を更新
			2018-01-15 マイページ改修に伴い請求書の印刷のみ実装
			2018-03-16 納品書のダウンロード機能を追加
			2018-05-31 請求書の振込期日の算出を更新
*/

$(function(){
	
	// 請求書
	$('#btn_bill').on('click', function(){
		var ordersId = $(this).data('orderId'),
			shipment = $(this).data('shipment'),
			d = new Date(shipment);
		
		d.setDate(d.getDate() - 1);
		var base = Math.floor(d.getTime() / 1000),
			param = {
				'basesec': base,
				'workday': [0],
				'transport': 1,
				'extraday': 0
			};
		$.api(['delivery'], 'GET', function (r) {
			var url = './documents/bill.php?orderid='+ordersId+'&m='+r[0]['Month']+'&d='+r[0]['Day']+'&w='+r[0]['Weekname'];
			window.open(url,'printform');
			$('#printform').on('load', function(){window.frames['printform'].print();});
		}, param);
	});
	
	// 納品書
	$('#btn_invoice').on('click', function(){
		var ordersId = $(this).data('orderId');
		url = './documents/invoice.php?orderid='+ordersId;
		window.open(url,'printform');
		$('#printform').on('load', function(){window.frames['printform'].print();});
	});
});
