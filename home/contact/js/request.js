/**
 * Takahama Life Art
 * request module
 * log
 * 2018-11-02 郵便番号検索後の住所変更イベント発火を修正
 */
	
$(function(){

	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		window.location.href = '/contact/thanks.php?title=request';
	});

	eMailer.onValidate('#sendmail', function(){
		// 郵便番号からの自動入力を反映
		eMailer.onChanged('#addr0');
		eMailer.onChanged('#addr1');
		
		return true;
	});

	// お名前にフォーカス
	document.forms.request_form.customername.focus();
	
});
