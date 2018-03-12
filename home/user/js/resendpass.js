/*
	Resnd Password
	charset UTF-8
	2018-01-12 created
*/

$(function(){
	/********************************
	*	仮パスワード送信
	*/
	$('#validation').click( function(){
		var email = $('#email').val().trim(),
			token = $('#token').val();

		if (token==='') {
			$.msgbox('再送信するにはページを再読み込みしてください');
			return;
		}
		
		if (email == '') {
			$.msgbox('メールアドレスを入力してください');
			return;
		}

		// 再発行処理
		$.api(['users', email], 'GET', null).then(function(r){
			// メールアドレスの登録状況を確認
			if (r===true) {
				return $.Deferred().resolve().promise();
			} else {
				$.msgbox('このメールアドレスは登録されていません');
				return $.Deferred().reject().promise();
			}
		}).then(function(){
			// パスワード再設定
			return $.api(['users', 'pass', email], 'POST', null)
		}).then(function(pass){
			// メール送信
			if (pass!='') {
				let event;
				document.forms.pass.newpass.value = pass;
				if(typeof(Event) === 'function') {
					event = new Event('change');
				}else{
					event = document.createEvent('Event');
					event.initEvent('change', false, true);
				}
				document.forms.pass.newpass.dispatchEvent(event);
				$('#sendmail').click();
			} else {
				$.msgbox('Error: パスワードの設定ができませんでした');
			}

		});
	});
	
	
	eMailer.onComplete('#sendmail', function(){
		let email = $('#email').val();
		document.forms.pass.newpass.value = '';
		$.msgbox('<p>'+email+'宛にパスワードを再発行いたしました</p>');
	});
	
	/********************************
	*	フォーカス
	*/
	document.forms.pass.sendto.focus();
});
