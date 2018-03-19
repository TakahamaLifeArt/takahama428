/*
*	Takahama Life Art
*	contacts module
*/

$(function(){
	/**
	 * 入力項目の検証
	 */
	eMailer.onValidate('#sendmail', function () {

		/**
		 * Check the upload file
		 * Return true if All uploads are completed. false otherwise.
		 */
		if ($('#fileupload-table').data('uploaded') != 1) {
			$.msgbox('アップロードされていないフィルがあります');
			return false;
		}

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
	
});
