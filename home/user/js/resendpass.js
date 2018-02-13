/*
	Resnd Password
	charset UTF-8
	2018-01-12 created
*/

$(function(){
	
	$.extend({
		sendData: {},
		sendMail: function(){
			$.ajax({
				url: '/user/php_libs/sendMail.php',
				type: 'POST',
				dataType: 'json',
				async: true,
				timeout: 5000,
				data: $.sendData
			}).done(function(r){
				if (r.send == 'success') {
					$.msgbox('<p>'+$.sendData.tpl-sendto+'宛にパスワードを再発行いたしました</p>');
					$('#token').val('');
				} else {
					$.msgbox('メールの送信ができませんでした。');
				}
			}).fail(function(xhr, status, error){
				alert("Error: "+error+"<br>通信エラーです。");

			});
		}
	});
	
	
	/********************************
	*	仮パスワード送信
	*/
	$('#send').click( function(){
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
				$.sendData = {};
				$.sendData['tpl-subject'] = 'パスワードを再発行いたしました';
				$.sendData['tpl-sendto'] = email;
				$.sendData['tpl-title'] = 'パスワード再発行';
				$.sendData['tpl-summary'] = "いつもご利用いただき、誠にありがとうございます。\n新しいパスワードを発行いたしました。";
				$.sendData['tpl-0_password'] = pass;
				$.sendMail();
			} else {
				$.msgbox('Error: パスワードの設定ができませんでした');
			}

		});
	});
	
	
	/********************************
	*	フォーカス
	*/
	document.forms.pass.email.focus();
});
