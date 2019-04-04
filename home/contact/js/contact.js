/*
 *	Takahama Life Art
 *	contacts module
 *
 * log
 * 2019-03-14 アップロード機能を実装
 * 2019-04-01 HTML5 validation
 */

$(function(){
	/**
	 * 入力項目の検証
	 */
	eMailer.onValidate('#sendmail', function () {
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

		return true;
	});

	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		window.location.href = '/contact/thanks.php?title=info';
	});

	// お名前にフォーカス
	document.forms.contact_form.customername.focus();
	
	/**
	 * submit
	 */
	$('#send_button').on('click', function() {
		
		// HTML5 validation の前に必須項目を全て検証
		let isInvalid = false;
		$('.e-mailer :input:visible[required="required"]').removeClass('placeholder_error');
		$('.e-mailer :input:visible[required="required"]').each(function () {
			if (!this.validity.valid) {
				$(this).focus();
				$(this).addClass('placeholder_error');
				//				$(this).attr("placeholder", this.validationMessage).addClass('placeholder_error');
				//				$(this).val('');
				isInvalid = true;
				//				return false;
			}
		});
		if (isInvalid) {
			$.msgbox("必須項目の入力を、ご確認ください。");
			return;
		}
		
		$('#sendmail').click();
	});
	
	// Edgeのみ進捗バーの表示を切り替える
	if (window.navigator.userAgent.toLowerCase().indexOf('edge') !== -1) {
		$('#file-uploader .progress').css('position', 'relative').append('<div class="edge_progress">アップロード中...</div>')
	}

});
