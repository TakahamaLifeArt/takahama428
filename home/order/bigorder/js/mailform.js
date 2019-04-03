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

		// アップロードファイル
		let dlToken = JSON.parse(sessionStorage.getItem('dl_token')),
			downURL = document.getElementById('deownload_link'),
			store = $.getSessStorage('attach'),
			fileName = '';

		if (Object.keys(Object(store)).length > 0) {
			$.each(store, function(upid, fName){
				fileName += fName + "\r\n";		// form submission value
			});
		}
		document.getElementById('filename').value = fileName;
		eMailer.onChanged('#filename');

		downURL.value = '';
		if (Object.keys(Object(dlToken)).length > 0) {
			downURL.value = 'https://www.alesteq.com/itemmanager/files/uploader/' + dlToken[0];
		}
		eMailer.onChanged('#deownload_link');
		
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
		},
		holiday: [{'from':'2018-12-27', 'to':'2019-01-04'}]

	});
});
