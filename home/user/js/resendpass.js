/*
	Resnd Password
	charset UTF-8
	2013-06-06	created
*/

$(function(){
	
	/********************************
	*	仮パスワード送信
	*/
	$('.ok_button', '#pass_table').click( function(){
		document.forms.pass.submit();
	});
	
	
	/********************************
	*	フォーカス
	*/
	document.forms.pass.email.focus();
});
