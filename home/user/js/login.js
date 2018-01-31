/*
	ログイン
	2013-08-06 created
	2018-01-13 エラー処理を更新
*/

$(function(){
	
	// ログイン処理
	$('#login_button').click( function(){
		var f = document.forms.loginform;
		var email = f.email.value.trim();
		var pass = f.pass.value.trim();
		if(email==""){
			$.msgbox('メールアドレスを入力してください。');
			return;
		}
		if(pass==""){
			$.msgbox('パスワードを入力してください。');
			return;
		}
		f.submit();
	});

	// エラー処理
	if (_ERROR) {
		$.msgbox(JSON.parse(_ERROR).error);
	}
	
	document.forms.loginform.email.focus();
});
