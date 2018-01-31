/*
	注文履歴
	Log:	2014-01-17 created
			2017-10-14 scroll機能を更新
			2018-01-15 マイページ改修に伴い請求書の印刷のみ実装
*/

$(function(){
	
	// 請求書
	$('#btn_bill').on('click', function(){
		var ordersId = $(this).data('orderId');
		url = './documents/bill.php?orderid='+ordersId;
		window.open(url,'printform');
		$('#printform').on('load', function(){window.frames['printform'].print();});
	});
	
});
