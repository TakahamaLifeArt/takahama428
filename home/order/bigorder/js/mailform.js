/*
*	Takahama Life Art
*	bigorder module
*/
	
$(function(){
	/**
	 * 入力項目の検証
	 */
	eMailer.onValidate('#sendmail', function () {
		var isValid = true;

		if($(':checkbox[name^="youto"]:checked').length==0){
			$.msgbox("ご利用の用途をご指定ください。");
			isValid = false;
			return false;
		}

		if(!$(':radio[name="vol"]:checked').val()){
			$.msgbox("製作枚数をご指定ください。");
			isValid = false;
			return false;
		}

		return isValid;
	});

	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		window.location.href = '/contact/thanks.php?title=bigorder';
	});

	// カレンダー
	$('#datepick').datepickCalendar({
		disableBeforeDate: '+1day',
		onSelect: function(dateText){
			eMailer.onChanged('#datepick');
		}
	});
});
