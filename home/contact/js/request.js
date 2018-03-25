/*
*	Takahama Life Art
*	request module
*/
	
$(function(){

	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		window.location.href = '/contact/thanks.php?title=request';
	});

	// 郵便番号
	$('#zipcode').on('change', function () {
		eMailer.onChanged('#addr0');
		eMailer.onChanged('#addr1');
	});

	// お名前にフォーカス
	document.forms.request_form.customername.focus();
	
});
