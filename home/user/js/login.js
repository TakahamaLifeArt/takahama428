/*
	ログイン
	2013-08-06	created
*/

$(function(){
	
	/********************************
	*	ログイン処理
	*/
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

});
