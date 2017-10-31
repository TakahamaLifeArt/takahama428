/*
	注文履歴
	Log:	2014-01-17	created
			2017-10-14	scroll機能を更新
*/

$(function(){
	
	/********************************
	*	受注No.指定の場合
	*/
	if(_CUR_ORDER!=0){
		var fnc = null;	// callback
		var targetOffset = $('.order_detail').offset().top - 135;
		$('html,body,#container').animate({scrollTop: targetOffset}, 500, jQuery.easing.default, fnc);
	}
	
	/********************************
	*	請求書・納品書の印刷
	*/
	$('.btn_bill', '#history_table').click( function(){
		var orders_id = $(this).attr('name').split('_')[1];
		url = './documents/bill.php?orderid='+orders_id;
		window.open(url,'printform');
		$('#printform').on('load', function(){window.frames['printform'].print();});
	});
	
	$('.btn_invoice', '#history_table').click( function(){
		var orders_id = $(this).attr('name').split('_')[1];
		url = './documents/invoice.php?orderid='+orders_id;
		window.open(url,'printform');
		$('#printform').on('load', function(){window.frames['printform'].print();});
	});
	
});
