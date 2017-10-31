/*
	common library
	2013-06-06	created
*/

$(function(){
	
	// トリム
	String.prototype.trim = function(){ return this.replace(/^[\s　]+|[\s　]+$/g, ''); };
	
	
	/***************************************************
	*	ライブラリ拡張
	*/
	jQuery.extend({
		screenOverlay: function(mode){
			var doc_w = $(document).width();
			var doc_h = $(document).height();
			if($('#overlay').length==0){
				$('html body').append('<div id="overlay" style="display:none;"></div>');
			}
			if(mode){
				if($('body').height()<doc_h){
					$h = (doc_h*2)+'px';
				}else{
					$h = 'auto';
				}
				$('#overlay').css({ 'width':'100%',
									'height':$h,
									'opacity':0.5}).show();
				if(arguments.length>1){
					$('#loadingbar').css({'top': doc_h/2+'px', 'left': doc_w/2-150+'px'}).show();
				}
			}else{
				if($('#loadingbar:visible').length>0) $('#loadingbar').hide();
				$('#overlay').css({'width':'0px', 'height':'auto'}).hide("1000");
			}
		},
		scrollto: function(target){
		/*
		*	指定位置にスクロール
		*	@target		jQuery オブジェクト
		*	第二引数	コールバック関数
		*/
			var fnc = null;
			if(arguments.length>1 && typeof arguments[1]=="function") fnc = arguments[1];	// 第二引数があれば、コールバック関数として使用 
			var targetOffset = target.offset().top;
//			$($.browser.opera ? document.compatMode == 'BackCompat' ? 'body' : 'html' :'html,body')
			$('html,body').animate({scrollTop: targetOffset}, 500, 'easeQuart', fnc);
        },
		msgbox: function(msg){
		/*
		*	メッセージボックス
		*	@msg		表示するメッセージ文
		*	@arguments	タイトルを指定、指定なしの場合は「メッセージ」
		*/
			var title = arguments.length==2? arguments[1]: 'メッセージ';
			$('#msgbox').off('show.bs.modal').on('show.bs.modal', {'message': msg, 'title':title}, function (e) {
				$('.modal-footer').hide();
				$('#msgbox .modal-title').html(e.data.title);
				$('#msgbox .modal-body p').html(e.data.message);
			});
			$('#msgbox').modal('show');
    	},
		confbox: {
		/*
		*	確認ボックス
		*	@msg		表示するメッセージ文
		*	@fn			callback ボタンが押された後の処理　OK:true, Cancel:false
		*/
			show: function(msg, fn){
				$.confbox.result.data = false;
				$('#msgbox').off('show.bs.modal').on('show.bs.modal', {'message': msg}, function (e) {
					$('.modal-footer').show();
					$('#msgbox .modal-body p').html(e.data.message);
					$(this).one('click', '.is-ok', function(){
						$.confbox.result.data = true;
					});
					$(this).one('click', '.is-cancel', function(){
						$.confbox.result.data = false;
					});
				});
				$('#msgbox').off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					fn();
				});
				$('#msgbox').modal('show');
			},
			result: {
				'data':false
			}
		}
	});
	
	
	/********************************
	*	スムーススクロール
	*/
		$('a[href*="#"]').on('click', function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var $target = $(this.hash);
            $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
            if ($target.length) {
                var targetOffset = $target.offset().top;
//                $($.browser.opera ? document.compatMode == 'BackCompat' ? 'body' : 'html' :'html,body')
									$('html,body').animate({scrollTop: targetOffset}, 1000, 'easeOutExpo');
                return false;
            }
        }
    });
   
   
   /***************************************************
	*		スムーススクロール
	*/
	jQuery.extend( jQuery.easing,
	{
		def: 'easeOutQuad',
		swing: function (x, t, b, c, d) {
			//alert(jQuery.easing.default);
			return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
		},
		easeInQuad: function (x, t, b, c, d) {
			return c*(t/=d)*t + b;
		},
		easeOutQuad: function (x, t, b, c, d) {
			return -c *(t/=d)*(t-2) + b;
		},
		easeInExpo: function (x, t, b, c, d) {
			return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
		},
		easeOutExpo: function (x, t, b, c, d) {
			return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
		},
		easeInOutExpo: function (x, t, b, c, d) {
			if (t==0) return b;
			if (t==d) return b+c;
			if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
			return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
		},
		easeInSine: function (x, t, b, c, d) {
			return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
		},
		easeOutSine: function (x, t, b, c, d) {
			return c * Math.sin(t/d * (Math.PI/2)) + b;
		},
		easeQuart: function (x, t, b, c, d) {
			return -c * ((t=t/d-1)*t*t*t - 1) + b;
		}
	});
	
	
	/********************************
	*	プルダウンメニュー
	*	height is 30 * row
	*/
	$(".toolbar_inner .menu .pull").hover(
		function(){
			var h = $('li', this).length * 49;
			$(this).stop().animate({height:h+'px'},{queue:false,duration:300});
		},
		function(){
			$(this).stop().animate({height:'48px'},{queue:false,duration:300});
		}
	);
	
	
	/********************************
	*	オーバーレイを閉じる
	*/
	$('#overlay, .close_form').on('click', function(){
		if($('#loginform_wrapper').is(':visible')){
			document.forms.loginform.reset();
			$('#loginform_wrapper').fadeOut();
		}else if($('#postform_wrapper').is(':visible')){
			document.forms.postform.reset();
			$('#postform_wrapper').fadeOut();
		}
		$.screenOverlay(false);
	});

});
