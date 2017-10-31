/*
*	Takahama Life Art
*	request module
*/
	
$(function(){

	jQuery.extend({
		sendmail_check:function(e){
			var f = document.forms.request_form;
			if(!$.check_email(f.email.value)){
				return false;
			}
			if(f.customername.value.trim()==""){
				$.msgbox("お名前を入力してください。");
				return false;
			}
			if(f.message.value.trim()==""){
				$.msgbox("メッセージを入力してください。");
				return false;
			}
			if(f.addr0.value.trim()==""){
				$.msgbox("ご住所を入力してください。");
				return false;
			}
			
			f.submit();
		}
	});
	
	$('#sendmail').on('click', function(){
		$.sendmail_check();
	});
	
	// フォームのエンターキーを無効にする
	$('form').on("keypress", "input:not(.allow_submit)", function(e) {
		if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
			return false;
		} else {
			return true;
		}
	});
	
	// お名前にフォーカス
	document.forms.request_form.customername.focus();
	
});
