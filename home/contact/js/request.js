/*
*	Takahama Life Art
*	request module
*/
	
$(function(){

	/**
	 * 入力項目の検証
	 */
	eMailer.onValidate('#sendmail', function () {

		/**
		 * 住所
		 * 都道府県と市区町村
		 */
		eMailer.onChanged('#addr0');
		eMailer.onChanged('#addr1');
		
		// 都道府県
		if ($('#addr0').val().trim() !== '') {
			$('#addr0')[0].classList.remove('is_invalid');
		} else {
			$('#addr0')[0].classList.add('is_invalid');
			$.msgbox('都道府県の入力は必須です');
			return false;
		}

		// 市区町村
		if ($('#addr1').val().trim() !== '') {
			$('#addr1')[0].classList.remove('is_invalid');
		} else {
			$('#addr1')[0].classList.add('is_invalid');
			$.msgbox('市区町村の入力は必須です');
			return false;
		}
		
		return true;
	});

	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		window.location.href = '/contact/thanks.php?title=request';
	});

	// お名前にフォーカス
	document.forms.request_form.customername.focus();
	
});
