/*
	Register
	charset UTF-8
	2013-06-25	created
*/
/*
	Register
	charset euc-jp
	2016-12-20	modified
*/

$(function(){
	
	/********************************
	*	��Ͽ
	*/
	$('#register_button').click( function(){
		if(!$("#agree").prop('checked')){
			$.msgbox('�����ѵ���򤴳�ǧ����������<br>�������Х����å�������Ƥ���������');
			return;
		}
		document.forms.register.submit();
	});
	
	
	/********************************
	*	Cancel
	*/
	$('#cancel_button').click( function(){
		$('.err').text('');
		document.forms.register.reset();
		document.forms.register.email.focus();
	});
	
	
	/********************************
	*	������ե������ɽ��
	*/
	$('.show_loginform').on('click', function(){
		$.screenOverlay(true);
		$('#loginform_wrapper').fadeIn();
		if(!_FB){
			if(_FIRST_VIEW){
				document.forms.loginform.password.focus();
			}else{
				document.forms.loginform.username.focus();
			}
		}
	});


	/********************************
	*	���������
	*/
	$('#login_button').click( function(){
		var f = document.forms.loginform;
		var email = '';
		var pass = _FB? "": f.password.value.trim();
		var agreed = 0;
		if($('#agreed').length){
			if(!$('#agreed').prop('checked')){
				$.msgbox('�����ѵ���򤴳�ǧ����������<br>�������Х����å�������Ƥ���������');
				return;
			}
			agreed = 1;
			email = $('.username', '#loginform_wrapper').text();
		}else{
			email = f.username.value.trim();
		}
		if(email==""){
			alert('�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������');
			return;
		}
		if(pass=="" && !_FB){
			alert('�ѥ���ɤ����Ϥ��Ƥ���������');
			return;
		}
		
		$.ajax({
			url: 'login.php', async:false, type:'GET', dataType:'json', data:{'login':1, 'email':email, 'pass':pass,'reg_site':'1', 'agreed':agreed, 'fb':_FB}, 
			success:function(r){
				if(r instanceof Array){
					if(r[0]=='ok'){
						var menu = '<div class="menu">';
						menu += '<span>��˥塼</span>';
						menu += '<ul class="pull">';
						menu += '<li><a href="./">�̿�����</a></li>';
						menu += '<li><a href="mypage.php">�ޥ��ڡ���</a></li>';
						menu += '<li><a href="logout.php">��������</a></li>';
						menu += '</ul>';
						menu += '</div>';
						menu += '<p class="uname">�褦���� '+r[1]+' ��</p>';
						$('.toolbar_inner .menu_wrap').html(menu);
						
						$(".toolbar_inner .menu .pull").hover(
							function(){
								var h = $('li', this).length * 48;
								$(this).stop().animate({height:h+'px'},{queue:false,duration:300});
							},
							function(){
								$(this).stop().animate({height:'0px'},{queue:false,duration:300});
							}
						);
						
						document.forms.loginform.reset();
						$('#loginform_wrapper,').fadeOut();
						$.screenOverlay(false);
					}else{
						alert(r[0]);
					}
				}else{
					alert('������Ǥ��ޤ���');
				}
			}
		});
	});


	document.forms.register.email.focus();
	
	
	if(_FIRST_VIEW==0){
	/********************************
	*	�桼������Ͽ��QUO������500��ʬ��ץ쥼���
	*	2013-12-13
	*/
		var msg = '<p class="highlights">�ѣգϥ����ɥץ쥼�����</p><hr>';
		msg += '<p>�������޲����Ͽ���Ƥ��������ȡ����ʤ�500��ʬ��QUO�����ɤ���館�ޤ���</p>';
		msg += '<p class="note"><span>��</span>QUO�����������轻��򤴻��꤯��������</p>';
		$.msgbox(msg);
	}else if(_FIRST_VIEW==1){
	/********************************
	*	TLA���С��ǽ��Ƽ̿������Ѥκݤ����ѵ����Ʊ�դ����
	*/
		$.screenOverlay(true);
		$('#loginform_wrapper').fadeIn();
		if(!_FB){
			document.forms.loginform.password.focus();
		}
	}

});
