/*
	Resnd Password
	charset UTF-8
	2018-01-12 created
*/

$(function(){
	
	$.extend({
		sendResetPass: function(email, pass){
			/**
			 * パスワード再発行メール
			 * @param email
			 * @param pass
			 */
			$.ajax({
				url: '/order/user_login.php',
				type: 'get',
				dataType: 'json',
				async: true,
				timeout: 5000,
				data: {
					'sendmail': 'true',
					'email': email,
					'pass': pass
				}
			}).done(function(r){
				if (r[0] == 'SUCCESS') {
					$.msgbox('<p>'+email+'宛にパスワードを再発行いたしました</p>');
				} else {
					$.msgbox('メールの送信ができませんでした。<hr><br>送信先メールアドレス：<br>'+r.join('<br>'));
				}

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
				$.sendResetPass(email, pass);
				$('#token').val('');
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
