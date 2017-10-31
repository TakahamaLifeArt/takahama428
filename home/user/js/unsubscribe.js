/*
	Resnd Password
	charset EUC-JP
	2016-08-22	created
*/

$(function(){
	
	/********************************
	*	配信停止
	*/
	$('.ok_button').on('click', function(){
		document.forms.myform.submit();
	});
	
	
	/********************************
	*	フォーカス	
	*/
	document.forms.myform.email.focus();
	
	
	/********************************
	*	配信停止処理の完了メッセージ	
	*/
	if(_SUCCESS){
		document.forms.myform.email.value = '';
		$.msgbox('タカハマTシャツ便りの配信を停止いたしました。');
	}
});
