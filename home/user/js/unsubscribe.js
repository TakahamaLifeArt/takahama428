/*
	Resnd Password
	charset EUC-JP
	2016-08-22	created
*/

$(function(){
	
	/********************************
	*	�ۿ����
	*/
	$('.ok_button').on('click', function(){
		document.forms.myform.submit();
	});
	
	
	/********************************
	*	�ե�������	
	*/
	document.forms.myform.email.focus();
	
	
	/********************************
	*	�ۿ���߽����δ�λ��å�����	
	*/
	if(_SUCCESS){
		document.forms.myform.email.value = '';
		$.msgbox('�����ϥ�T������ؤ���ۿ�����ߤ������ޤ�����');
	}
});
