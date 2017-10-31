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
	*	登録
	*/
	$('#register_button').click( function(){
		if(!$("#agree").prop('checked')){
			$.msgbox('ご利用規約をご確認いただき、<br>よろしければチェックを入れてください。');
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
	*	ログインフォームを表示
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
	*	ログイン処理
	*/
	$('#login_button').click( function(){
		var f = document.forms.loginform;
		var email = '';
		var pass = _FB? "": f.password.value.trim();
		var agreed = 0;
		if($('#agreed').length){
			if(!$('#agreed').prop('checked')){
				$.msgbox('ご利用規約をご確認いただき、<br>よろしければチェックを入れてください。');
				return;
			}
			agreed = 1;
			email = $('.username', '#loginform_wrapper').text();
		}else{
			email = f.username.value.trim();
		}
		if(email==""){
			alert('メールアドレスを入力してください。');
			return;
		}
		if(pass=="" && !_FB){
			alert('パスワードを入力してください。');
			return;
		}
		
		$.ajax({
			url: 'login.php', async:false, type:'GET', dataType:'json', data:{'login':1, 'email':email, 'pass':pass,'reg_site':'1', 'agreed':agreed, 'fb':_FB}, 
			success:function(r){
				if(r instanceof Array){
					if(r[0]=='ok'){
						var menu = '<div class="menu">';
						menu += '<span>メニュー</span>';
						menu += '<ul class="pull">';
						menu += '<li><a href="./">写真一覧</a></li>';
						menu += '<li><a href="mypage.php">マイページ</a></li>';
						menu += '<li><a href="logout.php">ログアウト</a></li>';
						menu += '</ul>';
						menu += '</div>';
						menu += '<p class="uname">ようこそ '+r[1]+' 様</p>';
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
					alert('ログインできません。');
				}
			}
		});
	});


	document.forms.register.email.focus();
	
	
	if(_FIRST_VIEW==0){
	/********************************
	*	ユーザー登録でQUOカード500円分をプレゼント
	*	2013-12-13
	*/
		var msg = '<p class="highlights">ＱＵＯカードプレゼント中</p><hr>';
		msg += '<p>ただいま会員登録していただくと、もれなく500円分のQUOカードがもらえます！</p>';
		msg += '<p class="note"><span>※</span>QUOカード送付先住所をご指定ください。</p>';
		$.msgbox(msg);
	}else if(_FIRST_VIEW==1){
	/********************************
	*	TLAメンバーで初めて写真館利用の際に利用規約の同意を求める
	*/
		$.screenOverlay(true);
		$('#loginform_wrapper').fadeIn();
		if(!_FB){
			document.forms.loginform.password.focus();
		}
	}

});
