/**
*	DESIGN CREATOR MODULE
*
*/

	/**************************************************************
	*		外部ソースの読込
	***************************************************************/
	$.ajaxSetup({scriptCharset:'utf-8'});
//	$.getScript('./designcreator/js/creator_imgeditor.js');
	$.getScript('./designcreator/js/fonts/fontnames.js');


	/**************************************************************
	*		プロパティの設定
	***************************************************************/
	var editorID = 0;
	var selectedFont = 		'クーパー';
	var selectedFontPath = 	'pop/C018016D';
	var item = {'item_name':'ヘビーウェイトＴシャツ',
				'item_code':'085-cvt',
				'color_code':'011',
				'color_name':'ピンク',
				'category_name':'Ｔシャツ',
				'category_key':'t-shirts'
				};
	var img_width = 400;
	var img_height = 400;
	var img_path = './designcreator/img/items/';
	var curr_drag_content = '';
	var curr_accordion = "";

	
	jQuery.extend({
		addFigure:function(args){
		/*
		*	金額の桁区切り
		*	@arg		対象の値
		*
		*	return		桁区切りした文字列
		*/
			var str = new String(args);
			str = str.replace(/[０-９]/g, function(m){
						var a = "０１２３４５６７８９";
						var r = a.indexOf(m);
						return r==-1? m: r;
					});
			str -= 0;
			var num = new String(str);
			if( num.match(/^[-]?\d+(\.\d+)?/) ){
				while(num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1,$2")));
			}else{
				num = "0";
			}
			return num;
		},
		check_NaN:function(my){
		/*
		*	自然数かどうかの確認
		*	@my			Object
		*
		*	return		自然数でない場合に0を返す、第二引数があれば、自然数以外のときの返り値として使用
		*/
			var err = arguments.length>1? arguments[1]: 0;
			var str = my.value.trim().replace(/[０-９]/g, function(m){
						var a = "０１２３４５６７８９";
						var r = a.indexOf(m);
						return r==-1? m: r;
					});
			my.value = (str.match(/^\d+$/))? str-0: err;
			return my.value;
		},
		zoomdata: {
			'width': 0,
			'height': 0,
			'canvas_width': 180
		},
		zoomFor: function(my, w, h, value){
			// var canvas = my.children();
			var canvas = my;
			var ratio = value/100;
			var old_w = $.zoomdata.width;
			var old_h = $.zoomdata.height;
			$.zoomdata.width = Math.round(w*ratio);
			$.zoomdata.height = Math.round(h*ratio);
			
			var t = old_h==0? 20: (h-$.zoomdata.height)/2 + 20;
			var l = ($.zoomdata.canvas_width-$.zoomdata.width)/2;
			
			canvas.css({'width':$.zoomdata.width+'px', 'height':$.zoomdata.height+'px', 'top':t+'px', 'left':l+'px'});
		}
	});


	(function($) {
		var cache = [];
	  	// Arguments are image paths relative to the current page.
		$.preLoadImages = function() {
			var args_len = arguments[0].length;
			for (var i = args_len; i--;) {
				var cacheImage = document.createElement('img');
				cacheImage.src = arguments[0][i];
				cache.push(cacheImage);
			}
		}
	})(jQuery)



	/**************************************************************
	*		DOM ready event listener
	***************************************************************/
	$(function($) {

		// preload
		var preload_hash = preload_images.split(',');
		$.preLoadImages(preload_hash);
		
		
		// set current content area
		curr_drag_content = $('#drag_cont');
		curr_accordion = $('#accordion_wrap');

		// item color slider
		$( "#slider1" ).accessNews({
			headline : "item_color_slider",
			speed : "slow",
			slideBy : 15
		});

		// click event for IE
//		if(jQuery.browser.msie){
//			$('input[type="text"]', '#control_area').live('keypress', function(evt){
//				var charCode=(evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
//				if ( Number(charCode) == 13 || Number(charCode) == 3) {
//					$(this).change();
//				}
//			});
//		}
		
		$('input[id^=ratio_]', '#accordion_wrap').focus( function(){
			$(this).addClass('act');
		}).blur( function(){
			$(this).removeClass('act');
		});
		
		// show ink palette
		$('.font_color, .ink_name', '#accordion_wrap').click( function(){
			inkcolor.show();
		});
		
		// show font list
		$('.fontfamily', '#accordion_wrap').change( function(){
			font.show(this);
		});
		
		// apply text
		$('#apply').click( function(){
			apply(0);
		});

		
		// add click event to the "add text" link
/*
		$('#add_text').bind('click', function(){addEditor();});
*/

		// add click event to the "add image" link
/*
		$('#add_image').bind('click', function() {
			DesignCreator.screenOverlay(true);
			var y = $(document).scrollTop() - 321;
			$('#glass_wrapper').css({'visibility': 'visible', 'margin-top': y+'px'})
			.animate({ 'width': '460px', 'height': '672px' }, 500, function(){
				var f=document.forms.uploaderform;
				f.imageid.value = 'id'+editorID;
				var posID = '0';
				if(curr_drag_content.attr('id').match(/_back$/)){
					posID = '1';
				}
				f.posid.value = posID;
				f.fileframe.value = 'true';
				var dd = new Date();
				f.latesttime.value = dd.getTime();
			});
		});
*/


		// click overlay
		$('#overlay').click( function() {
			if($('#glass_wrapper:visible').length>0){
				hide_uploader(false);
			}else if($('#helppane:visible').length>0) {
				$('#helppane').fadeOut('normal', function(){ DesignCreator.screenOverlay(false); });
			}
		});

		// uploader close button
/*
		$('#uploader_close').bind('click', function(){hide_uploader(false);});
*/

		// view cart
/*
		$('#viewcart').click( function(){
			document.forms['addcart'].addcart.value = 'false';
			DesignCreator.compose(document.forms['addcart']);
		});
*/

		// add to cart
/*
		$('.addcart_button').hover(
			function(){ $(this).attr('src','./img/addcart_hover.png'); },
			function(){ $(this).attr('src','./img/addcart.png') }
		);
		$('.addcart_button').bind('click', function(){
			if($('#drag_cont').children().length == 0 && $('#drag_cont_back').children().length == 0){
				msgbox.alert('プリントするデザインがありません。');
				return;
			}

			var f = document.forms['addcart'];
			var amount = $.check_NaN(f.order_amount);

			if(amount==0){
				msgbox('数量を指定してください。');
				return;
			}

			f.addcart.value = 'true';
			DesignCreator.compose(f);

		});
*/

		// order amount
/*
		$('#order_amount').keypress(function(e) {
			var code = typeof(e.charCode) != 'undefined' ? e.charCode : e.keyCode;
			if (   !e.ctrlKey 				// Ctrl+?
				&& !e.altKey 				// Alt+?
				&& code != 0 				// ?
				&& code != 8 				// BACKSPACE
				&& code != 9 				// TAB
				&& code != 13 				// Enter
				&& (code < 48 || code > 57)) // 0..9
				e.preventDefault();

			 if(code == 13 || code == 3) $(this).change();

		}).focus( function(){
			var c = this.value;
		  	this.value = c.replace(/,/g, '');
			var self = this;
			setTimeout(function() {
				$(self).select();
			}, 10);
		}).bind('change', function(){
			var f = document.forms['addcart'];
			var amount = $.check_NaN(f.order_amount);
			var price = 2700;
			if($('#drag_cont').children().length > 0 && $('#drag_cont_back').children().length > 0) price = 3700;
			f.order_price.value = $.addFigure(price*amount);
		});
*/

		// about size
/*
		$('#help_size').click(function(){
			DesignCreator.screenOverlay(true);
			var y = $(document).scrollTop() - 280;
			var x = $(document).scrollLeft() - 230;
			$('#helppane').load('./txt/aboutsize.txt', function(){$(this).css({'margin-top':y+'px', 'margin-left':x+'px'}).fadeIn('slow');});
		});
*/
		
		// about item
/*
		$('#help_item').click(function(){
			DesignCreator.screenOverlay(true);
			var y = $(document).scrollTop() - 280;
			var x = $(document).scrollLeft() - 230;
			$('#helppane').load('./txt/aboutitem.txt', function(){$(this).css({'margin-top':y+'px', 'margin-left':x+'px'}).fadeIn('slow');});
		});
*/
		
		// about print
/*
		$('#help_print').click(function(){
			DesignCreator.screenOverlay(true);
			var y = $(document).scrollTop() - 280;
			var x = $(document).scrollLeft() - 230;
			$('#helppane').load('./txt/aboutprint.txt', function(){$(this).css({'margin-top':y+'px', 'margin-left':x+'px'}).fadeIn('slow');});
		});
*/

		// display an image for item
/*
		$('#show_item').click(function(){
			if($(this).hasClass('curr')) return;
			$(this).siblings('.curr').removeClass('curr');
			$(this).addClass('curr');
			$('#designloader').hide();
			$('#imgloader, #front_side, #back_side').fadeIn();
		});
*/

		// display an image only for designs
/*
		$('#show_design').click(function(){
			var my = $(this);
			if(my.hasClass('curr')) return;

			$.post('./php_libs/sess_write.php', {'act':'sort'}, function(res){
				if(!res || res.charCodeAt(0)==65279){
					DesignCreator.progressBar(false);
					return;
				}

				var position = "&position[]=";
				if(curr_drag_content.attr('id').match(/_back$/)){
					position += '1';
				}else{
					position += '0';
				}
				var postStr = 'download=true&scalewidth=310&scaleheight=400'+position;
				$.ajax({url: './php_libs/compo.php',
					type: 'POST',
					dataType: 'text',
					data: postStr,
 					async: true,
 					success: function(r){
 						if(r!=""){
							my.siblings('.curr').removeClass('curr');
							my.addClass('curr');
							var filename = r.substring(r.lastIndexOf('/')+1, r.length);
							filename = './user/guest/data/'+filename;
							var backimage = $('#imgloader > img').eq(1).attr('src');
							var loader = $('#designloader');
							loader.children('#display_design').attr({'src':filename});
							loader.children('#back_design').attr({'src':backimage});
							$('#imgloader, #front_side, #back_side').hide();

							loader.fadeIn();
						}
						DesignCreator.progressBar(false);
					}
				});
			});
			DesignCreator.progressBar(true);
		});
*/

		// reverse image
/*
		$('#front_side').click(function(){
			if($(this).css('width')=='60px'){
				var src = img_path + item['category_key'] + "/" + item['item_code'] + "_back/" + item['item_code'] + "_" + item['color_code'] + ".jpg";
				$(this).animate({'width':'80px', 'height':'25px', 'lineHeight':'25px'},250);
				$('#back_side').animate({'width':'60px', 'height':'19px', 'lineHeight':'19px', 'left':'102px'},250);
				curr_drag_content = $('#drag_cont');
				curr_accordion = $('#accordion_wrap');
				$('#drag_cont_back').hide();
				$('#drag_cont').show();
				$('#accordion_wrap_back').hide();
				$('#accordion_wrap').show();
				src = img_path + item['category_key'] + "/" + item['item_code'] + "/" + item['item_code'] + "_" + item['color_code'] + ".jpg";
				$('#imgloader > img').eq(1).attr('src', src).fadeIn('slow');
				$('#imgloader > img').eq(3).fadeOut('slow');
			}
		});
		$('#back_side').click(function(){
			if($(this).css('width')=='60px'){
				var src = img_path + item['category_key'] + "/" + item['item_code'] + "_back/" + item['item_code'] + "_" + item['color_code'] + ".jpg";
				$('#front_side').animate({'width':'60px', 'height':'19px', 'lineHeight':'19px'},250);
				$(this).animate({'width':'80px', 'height':'25px', 'lineHeight':'25px', 'left':'82px'},250);
				curr_drag_content = $('#drag_cont_back');
				curr_accordion = $('#accordion_wrap_back');
				$('#drag_cont').hide();
				$('#drag_cont_back').show();
				$('#accordion_wrap').hide();
				$('#accordion_wrap_back').show();
				$('#imgloader > img').eq(3).attr('src', src).fadeIn('slow');
				$('#imgloader > img').eq(1).fadeOut('slow');
			}
		});
*/

		// download image
/*
		$('#download_design').click(function() {
			if(editorID == 0 || $('#help_message_wrapper:visible').length>0){
				msgbox.alert('ダウンロードするイメージはありません。');
				return;
			}

			$.post('./php_libs/sess_write.php', {'act':'sort'}, function(res){
				if(!res || res.charCodeAt(0)==65279){
					DesignCreator.progressBar(false);
					return;
				}

				var position = "&position[]=";
				if(curr_drag_content.attr('id').match(/_back$/)){
					position += '1';
				}else{
					position += '0';
				}
				var itemimage = $('#imgloader > img:visible').attr('src');
				itemimage = itemimage.slice(2);
				var postStr = 'download=true'+position+'&itemimage='+itemimage;

				$.ajax({url: './php_libs/compo.php',
					type: 'POST',
					dataType: 'text',
					data: postStr,
 					async: true,
 					success: function(r){
 						if(r==""){
							DesignCreator.progressBar(false);
							return;
						}
						var filename = r.substring(r.lastIndexOf('/')+1, r.length);
						var f = document.forms.downloader;
						f.downloadfile.value = r;
						$("#dialog").dialog({ bgiframe: true, draggable: true, resizable: false, autoOpen: false, width: 400, modal: true, title: '確　認',	position:['center',200],
							open:function(){
								$('#dialog').html(
								'<h1 style="font-size: 150%;">Takahama Life Art</h1><em style="color:#ff6631;">-SELF DESIGN-</em><br/><hr />'+
								'<img alt="ダウンロードファイル" src="user/guest/data/'+filename+'" widht="40" height="40" style="float:left;margin-right:20px;vertical-align:middle" />'+
								'<p style="margin-top:20px;line-height:1.5;">'+filename+'<br />をダウンロードします。よろしいですか？</p>')
								.parent().hide().fadeIn('slow');
							},
							hide:'swing',
							close:function(){
								$(this).dialog('destroy');
								DesignCreator.progressBar(false);
							},
							buttons:{
								'OK':function(){
									$(this).parent().fadeOut('slow', function(){
										f.submit();
										f.downloadfile.value = "";
										$('#dialog').dialog('close');
									});
								},
								'Cancel':function(){
									$(this).parent().fadeOut('slow', function(){ $('#dialog').dialog('close'); });
								}
							}
						}).dialog('open');

					}
				});
			});
			DesignCreator.progressBar(true);
		});
*/


		// construct contents
		DesignCreator.init();
		
		
		// rearrangement designs
/*		
		DesignCreator.resetDesignImages();
*/

	});




	/**************************************************************
	*		アップロードウィンドウを閉じる
	*/
	function hide_uploader(){
		var images = arguments[0]? arguments: false;
		$('#glass_wrapper').animate({ 'width': 0, 'height': 0 }, 500, function(){
			$(this).css('visibility', 'hidden');
			var f = document.forms.uploaderform;
			var reload = f.latesttime.value==""? true: false;
			f.reset();
			f.latesttime.value="";
			DesignCreator.screenOverlay(false);
			if(images && !reload) uploaded(images);
		});
	}


	/********************************************************
	*		アップロードされた画像の表示とエディターの生成
	*/
	function uploaded(images){
		var img_path = 	images[0];
		var org_w = 	images[1];
		var org_h = 	images[2];
		var ID = 		editorID;
		var accordion = curr_accordion;
		var position = '0';
		if(curr_accordion.attr('id').match(/_back$/)){
			position = '1';
		}
		var param = {'ID':ID, 'pos':position, 'src':images[0], 'width':images[1], 'height':images[2]};
		if(arguments.length==2){
			ID = arguments[1]['ID'];
			accordion = arguments[1]['pos']=='0'? $('#accordion_wrap'): $('#accordion_wrap_back');
			for(var key in arguments[1]){
				param[key] = arguments[1][key];
			}
		}

		var toggle_id = 		"toggle_" + ID;
		var toggle_text_id = 	"tt_" + ID;
		var element_id = 		"editelement_" + ID;
		var image_id = 			"prn_" + ID;

		editorID = parseInt(ID,10)+1;

		$('#help_message_wrapper').hide();

		// トッグルヘッダに表示する画像のサイズを取得
	 	var src_w=org_w, src_h=org_h;
	 	if(src_h > 82){
			src_w *= 82/src_h;
			src_h = 82;
			if(src_w > 170){
				src_h *= 170/src_w;
				src_w = 170;
			}
		}else if(src_w > 170){
			src_h *= 170/src_w;
			src_w = 170;
		}
		var org_verticalalign = Math.floor((82-src_h)/2);
		var w = org_w*(23/org_h);  // 23 is height of a toggle_header.


		// create slide editor
		accordion.prepend('<div id="'+element_id+'"></div>');
		$('#'+element_id).css({'display':'none', 'clear':'both'}).html(
			'<div class="toggle_inner">' +
				'<div class="arrow_boad_wrapper">' +
					'<img class="arrow_boad" alt="" src="./img/creator/ctrl/move_bg.png" />' +
					'<div class="edge_top_wrapper">' +
						'<img class="edge_top" alt="" src="./img/creator/ctrl/edge-vert.png" onmousedown="clickvert(this,' + ID + ')" onmouseup="oververt(this)" onmouseout="blurvert(this)" onmouseover="oververt(this)" />' +
					'</div>' +
					'<div class="edge_bottom_wrapper">' +
						'<img class="edge_bottom" alt="" src="./img/creator/ctrl/edge-vert.png" onmousedown="clickvert(this,' + ID + ')" onmouseup="oververt(this)" onmouseout="blurvert(this)" onmouseover="oververt(this)" />' +
					'</div>' +
					'<div class="edge_left_wrapper">' +
						'<img class="edge_left" alt="" src="./img/creator/ctrl/edge-hor.png" onmousedown="clickhor(this,' + ID + ')" onmouseup="overhor(this)" onmouseout="blurhor(this)" onmouseover="overhor(this)" />' +
					'</div>' +
					'<div class="edge_right_wrapper">' +
						'<img class="edge_right" alt="" src="./img/creator/ctrl/edge-hor.png" onmousedown="clickhor(this,' + ID + ')" onmouseup="overhor(this)" onmouseout="blurhor(this)" onmouseover="overhor(this)" />' +
					'</div>' +
					'<div class="arrow_center_wrapper">' +
						'<img class="arrow_center" alt="" src="./img/creator/ctrl/center.png" onmousedown="clickcenter(this,' + ID + ')" onmouseup="overcenter(this)" onmouseout="blurcenter(this)" onmouseover="overcenter(this)" />' +
					'</div>' +
					'<div class="arrow_up_wrapper">' +
						'<img class="arrow_up" alt="" src="./img/creator/ctrl/straight.png" onmousedown="clickarrow(this,' + ID + ')" onmouseup="overarrow(this)" onmouseout="blurarrow(this)" onmouseover="overarrow(this)" />' +
					'</div>' +
					'<div class="arrow_right_wrapper">' +
						'<img class="arrow_right" alt="" src="./img/creator/ctrl/straight.png" onmousedown="clickarrow(this,' + ID + ')" onmouseup="overarrow(this)" onmouseout="blurarrow(this)" onmouseover="overarrow(this)" />' +
					'</div>' +
					'<div class="arrow_down_wrapper">' +
						'<img class="arrow_down" alt="" src="./img/creator/ctrl/straight.png" onmousedown="clickarrow(this,' + ID + ')" onmouseup="overarrow(this)" onmouseout="blurarrow(this)" onmouseover="overarrow(this)" />' +
					'</div>' +
					'<div class="arrow_left_wrapper">' +
						'<img class="arrow_left" alt="" src="./img/creator/ctrl/straight.png" onmousedown="clickarrow(this,' + ID + ')" onmouseup="overarrow(this)" onmouseout="blurarrow(this)" onmouseover="overarrow(this)" />' +
					'</div>' +
				'</div>' +
				'<div class="view_image">' +
					'<img alt="" src="' + img_path + '" style="margin: ' + org_verticalalign + 'px auto; width:'+ src_w +'px; height:'+ src_h +'px;" />' +
				'</div>' +
				'<div class="image_controlbox">' +
					'<p>' +
						'<label for="degree_'+ID+'">画像の回転&nbsp;:</label>' +
						'<input type="text" id="degree_'+ID+'" size="4" value="0" />&deg;'+
					'</p>' +
					'<div id="rotate_'+ID+'"></div>' +
					'<p>' +
						'<label for="ratio_'+ID+'">画像のズーム&nbsp;:</label>' +
						'<input type="text" id="ratio_'+ID+'" size="4" value="100" />%' +
					'</p>' +
					'<div id="zoom_'+ID+'"></div>' +
					'<p class="comment"><span>※</span> 画像はマウスのドラッグでも移動できます。</p>' + 
				'</div>' +
			'</div>'
		);


		// create toggle header
		accordion.prepend('<div id="'+toggle_id+'" class="toggle_header"></div>');
		$('#'+toggle_id).html(
			'<p><img src="' + img_path + '" width="' + w + 'px" height="23px" /></span></p>' +
			'<div class="close_btn" onclick="deleteEditor(this, '+ID+')">削除</div>'
		);


		$('#'+toggle_id).bind('click', function(){ toggleMe(ID); });

		if(arguments.length==1){
			toggleMe(ID);
		}

		createDesignImage(param);

	}



	/***************************************************
	*		テキストエディターの生成
	*/
	function addEditor(){
		var ID = (arguments[0] || editorID);
		var toggle_id = 		"toggle_" + ID;
		var toggle_text_id = 	"tt_" + ID;
		var element_id = 		"editelement_" + ID;
		var editor_id = 		"editform_" + ID;
		var slider = 			"slider_" + ID;
		var knob =				"knob_" + ID;
		var accordion = 		curr_accordion;
		if(arguments.length==2) accordion = arguments[1]=='0'? $('#accordion_wrap'): $('#accordion_wrap_back');

		editorID = parseInt(ID,10)+1;

		$('#help_message_wrapper').hide();

		// create slide editor
		accordion.prepend('<div id="'+element_id+'"></div>');
		$('#'+element_id).css({'display':'none', 'clear':'both'}).html(
			'<div class="toggle_inner">' +
				'<form action="" id="' + editor_id + '" name="'+editor_id+'" onsubmit="return false;">' +
					'<div class="arrow_boad_wrapper">' +
						'<img class="arrow_boad" alt="" src="./img/creator/ctrl/move_bg.png" />' +
						'<div class="edge_top_wrapper">' +
							'<img class="edge_top" alt="" src="./img/creator/ctrl/edge-vert.png" onmousedown="clickvert(this,' + ID + ')" onmouseup="oververt(this)" onmouseout="blurvert(this)" onmouseover="oververt(this)" />' +
						'</div>' +
						'<div class="edge_bottom_wrapper">' +
							'<img class="edge_bottom" alt="" src="./img/creator/ctrl/edge-vert.png" onmousedown="clickvert(this,' + ID + ')" onmouseup="oververt(this)" onmouseout="blurvert(this)" onmouseover="oververt(this)" />' +
						'</div>' +
						'<div class="edge_left_wrapper">' +
							'<img class="edge_left" alt="" src="./img/creator/ctrl/edge-hor.png" onmousedown="clickhor(this,' + ID + ')" onmouseup="overhor(this)" onmouseout="blurhor(this)" onmouseover="overhor(this)" />' +
						'</div>' +
						'<div class="edge_right_wrapper">' +
							'<img class="edge_right" alt="" src="./img/creator/ctrl/edge-hor.png"	onmousedown="clickhor(this,' + ID + ')" onmouseup="overhor(this)" onmouseout="blurhor(this)" onmouseover="overhor(this)" />' +
						'</div>' +
						'<div class="arrow_center_wrapper">' +
							'<img class="arrow_center" alt="" src="./img/creator/ctrl/center.png" onmousedown="clickcenter(this,' + ID + ')" onmouseup="overcenter(this)" onmouseout="blurcenter(this)" onmouseover="overcenter(this)" />' +
						'</div>' +
						'<div class="arrow_up_wrapper">' +
							'<img class="arrow_up" alt="" src="./img/creator/ctrl/straight.png" onmousedown="clickarrow(this,' + ID + ')" onmouseup="overarrow(this)" onmouseout="blurarrow(this)" onmouseover="overarrow(this)" />' +
						'</div>' +
						'<div class="arrow_right_wrapper">' +
							'<img class="arrow_right" alt="" src="./img/creator/ctrl/straight.png" onmousedown="clickarrow(this,' + ID + ')" onmouseup="overarrow(this)" onmouseout="blurarrow(this)" onmouseover="overarrow(this)" />' +
						'</div>' +
						'<div class="arrow_down_wrapper">' +
							'<img class="arrow_down" alt="" src="./img/creator/ctrl/straight.png" onmousedown="clickarrow(this,' + ID + ')" onmouseup="overarrow(this)" onmouseout="blurarrow(this)" onmouseover="overarrow(this)" />' +
						'</div>' +
						'<div class="arrow_left_wrapper">' +
							'<img class="arrow_left" alt="" src="./img/creator/ctrl/straight.png" onmousedown="clickarrow(this,' + ID + ')" onmouseup="overarrow(this)" onmouseout="blurarrow(this)" onmouseover="overarrow(this)" />' +
						'</div>' +
					'</div>' +

					'<div class="leftside"><textarea id="printtext_'+ID+'" class="printtext" name="printtext" rows="2" cols="20"></textarea>' +

					'<div class="text_sliderbox">' +
						'<p>' +
							'<label for="degree_'+ID+'">文字の回転&nbsp;:</label>' +
							'<input type="text" id="degree_'+ID+'" size="4" value="0" />&deg;'+
						'</p>' +
						'<div id="rotate_'+ID+'"></div>' +
						'<p>' +
							'<label for="ratio_'+ID+'">文字のズーム&nbsp;:</label>' +
							'<input type="text" id="ratio_'+ID+'" size="4" value="100" />%' +
						'</p>' +
						'<div id="zoom_'+ID+'"></div>' +
					'</div></div>' +

					'<table class="text_controlbox" cellpadding="0" cellspacing="1">' +
						'<tr>' +
							'<td class="label rightside">書き方 : </td>' +
							'<td><select name="direction" size="1" onchange="font.apply(' + ID + ')">' +
									'<option value="horizontal" selected="selected">横書き</option>' +
									'<option value="vertical">縦書き</option>' +
								'</select>' +
							'</td>' +
							'<td colspan="2"></td>' +
						'</tr>' +
						'<tr>' +
							'<td class="label">フォント : </td>' +
							'<td colspan="3"><select class="fontfamily" name="fontfamily" size="1" onchange="font.show(this)">' +
									'<option value="ja/DFGOTC" selected="selected">極太ゴシック</option>' +
									'<optgroup label="和文フォント">' +
										'<option value="ja">和　基本</option>' +
										'<option value="jawa">和　純和風</option>' +
										'<option value="japop">和　ポップ</option>' +
										'<option value="jaetc">和　その他</option>' +
									'</optgroup>' +
									'<optgroup label="英文フォント">' +
										'<option value="en">英　基本</option>' +
										'<option value="art">英　アート</option>' +
										'<option value="impact">英　インパクト</option>' +
										'<option value="pop">英　ポップ</option>' +
										'<option value="sport">英　スポーツ</option>' +
										'<option value="etc">英　その他</option>' +
									'</optgroup>' +
								'</select>' +
							'</td>' +
						'</tr>' +
						'<tr>' +
							'<td class="label">色 : </td>' +
							'<td colspan="2"><input type="text" readonly="readonly" id="fontcolor_'+ID+'" class="font_color" name="fontcolor" onclick="inkcolor.show(this)" />' +
							'<span id="ink_name_'+ID+'" class="ink_name">ブラック</span></td>' +
							'<td class="rightside"><input type="button" value="更新" onclick="apply(' + ID + '); return false;" /></td>' +
						'</tr>' +
					'</table>' +
				'</form>' +
			'</div>'
		);

		// create toggler header
		accordion.prepend('<div id="'+toggle_id+'" class="toggle_header" onclick="toggleMe('+ID+')"></div>');
		$('#'+toggle_id).html(
			'<p><span id ="' + toggle_text_id + '">文字</span> の編集</p>' +
			'<div class="close_btn" onclick="deleteEditor(this, '+ID+')">削除</div>'
		);

		if(arguments.length==0){
			toggleMe(ID);
		}

	}


	/***************************************************
	*		テキスト画像の編集
	*/
	function apply(ID){
		var f = '#editform_'+ID;
		var text = $('#printtext_'+ID).val();
		if(text==""){
			$('#prn_'+ID).remove();
			$('#zoom_'+ID).slider('destroy');
			$('#ratio_'+ID).val('100');
			return;
		}
		
		// $("#tt_"+ID).text(text.replace(/(\r\n|\n)/g, "").substring(0, 6));
		var font = $('select[name="fontfamily"]', f).val();
		//var size = $('select[name="fontsize"]', f).val();
		var rgb = new RGBColor($('.font_color', f).css('backgroundColor'));
		var color = rgb.toHex().substr(1);
		var inkname = $('#ink_name_'+ID).text();
		var direct = 'horizontal';		// $('select[name="direction"]', f).val();
		var position = '0';
	/*	
		if(curr_drag_content.attr('id').match(/_back$/)){
			position = '1';
		}
	*/
		var postdata = { 'font': font,
						 'text': text,
						 'id': ID,
						 'color': color,
						 'inkname': inkname,
						 'direct': direct,
						 'position': position
						};		
		$.post('./designcreator/php_libs/mk_text.php', postdata,
			function(r){
				var data = r.split(",");
				if(data[0]=="error"){
					$.msgbox(data[1]);
					return;
				}
				
				createDesignImage({'ID':ID, 'pos':position, 'src':data[0], 'width':data[1], 'height':data[2]});
			}
		);

	}


	/***************************************************
	*		画像の表示とイベント設定
	*/
	function createDesignImage(param){
		var ID = 0;		// param['ID'];
		var image_id = 'prn_'+ID;
		
		var img_exists = ($('#'+image_id).length > 0)? true: false;
		var position = param['pos']=='0'? $('#drag_cont'): $('#drag_cont_back');

		var newImg = param['src'] + '?'+(new Date()).getTime();
		var myWidth = param['width']-0;
		var myHeight = param['height']-0;

		var filename = param['src'].substring(param['src'].lastIndexOf('/')+1);
		// var myTop = (param['top'] || 0);
		// var myLeft = (param['left'] || Math.round(60-(Math.sqrt(myWidth*myWidth+myHeight*myHeight)/2)));
		var myZoom = (param['zoom'] || 100);
		// var myDegree = (param['degree'] || 0);
		var myZID = 1;		// (param['zid'] || 1);

		// var degree = $("#degree_"+ID);
		var ratio = $("#ratio_"+ID);
		var my = "";
		
		if(img_exists){
			my = $('#'+image_id);
			// var preHeight 	= parseInt(my.css('height'),10);
			// var diagonal 	= Math.floor(Math.sqrt(myWidth*myWidth+myHeight*myHeight));
			var adjust 		= 0;	// (preHeight-diagonal)/2;
			// myTop = parseInt(my.css('top'),10) + adjust;
			// myLeft = parseInt(my.css('left'),10) + adjust;
			// myDegree = degree.val();
			myZoom = ratio.val();
			myZID = (my.css('z-index') || 1);

			my.remove();
			$("#zoom_"+ID).slider('destroy');
			ratio.val(100);
			
			// $("#rotate_"+ID).slider('destroy');
		}

		position.append('<img id="'+image_id+'" class="drgElement" src="'+newImg+'" style="width:'+myWidth+'px; height:'+myHeight+';" />');
		my = $('#'+image_id);
		
		// ズーム機能の設定
		$("#zoom_"+ID).slider({
			value: myZoom,
			min: 10,
			max: 200,
			step: 1,
			stop: function(event, ui){ $.post('./designcreator/php_libs/sess_write.php', {'act':'set', 'id':ID, 'zoom':ui.value}); },
			slide: function(event, ui) {
				ratio.val(ui.value);
				$.zoomFor(my, myWidth, myHeight, ui.value);
			}
		});
		var zoomHandle = $("#zoom_"+ID+" > a");
		ratio.change(function(){
			var value = $(this).val();
			value = (isNaN(value) || value=='')? '100': (value<10)? 10: (value>200)? 200: parseInt(value, 10);
			$(this).val(value);
			$.zoomFor(my, myWidth, myHeight, value);
			var position = (value-10)/1.9;
			zoomHandle.css('left', position+'%');
			$.post('./designcreator/php_libs/sess_write.php', {'act':'set', 'id':ID, 'zoom':value});
		});
			
		ratio.change();
		my.show('normal');	
				
		return;
		
		
		
		// 画像の回転機能を設定
		var limit = 0;
		// var rIMG = $('#'+image_id).rotate({ angle:myDegree });
		var timer = setInterval( function(){
			if( $('[id="'+image_id+'"]', '#dragarea_wrapper').length>0 ){

				var my = $('#'+image_id);
				var _obj = my.children();
				var w = myWidth;
				var h = myHeight;
				
				
				// ズームの設定用スライダー
/*
				if(jQuery.browser.msie){
					w = myWidth;
					h = myHeight;
				}
				else{
					w = h = Math.round(Math.sqrt(myWidth*myWidth + myHeight*myHeight));
				}

				zoomFor(my, w, h, 100);

				_obj.addClass('drgHandle')
					.hover(
						function(){
							$(this).css('border', '1px solid #d8d8d8');
						},
						function(){
							$(this).css('border', '1px solid transparent');
						}
					);
				my.draggable({ containment:'#dragarea_wrapper', scroll:false, handle:'.drgHandle',
					stop:function(){
							var new_top = parseInt($(this).css('top'), 10);
							var new_left = parseInt($(this).css('left'), 10);
							$.post('./php_libs/sess_write.php', {'act':'move', 'id':ID, 'top':new_top, 'left':new_left});
						} }).mousedown(function(){
											if(!$("#toggle_"+ID).hasClass('toggle_active')) toggleMe(ID);
										})
						.css({'top':myTop+'px', 'left':myLeft+'px', 'zIndex':myZID});
*/


				$("#zoom_"+ID).slider({
					value: myZoom,
					min: 10,
					max: 200,
					step: 1,
					stop: function(event, ui){ $.post('./designcreator/php_libs/sess_write.php', {'act':'set', 'id':ID, 'zoom':ui.value}); },
					slide: function(event, ui) {
						ratio.val(ui.value);
						zoomFor($('#'+image_id), w, h, ui.value);
					}
				});
				var zoomHandle = $("#zoom_"+ID+" > a");
				ratio.val(myZoom).change(
					function(){
						var value = $(this).val();
						value = (isNaN(value) || value=='')? '100': (value<10)? 10: (value>200)? 200: parseInt(value, 10);
						$(this).val(value);
						zoomFor(my, w, h, value);
						var position = (value-10)/1.9;
						zoomHandle.css('left', position+'%');
						$.post('./designcreator/php_libs/sess_write.php', {'act':'set', 'id':ID, 'zoom':value});
					}
				);

				// 回転角度の設定用スライダー
/*
				$("#rotate_"+ID).slider({
					value: myDegree,
					min: -180,
					max: 180,
					step: 1,
					stop: function(event, ui){ $.post('./php_libs/sess_write.php', {'act':'set', 'id':ID, 'degree':ui.value} ); },
					slide: function(event, ui) {
						degree.val(ui.value);
						$('#'+image_id).rotateAnimation(ui.value);
					}
				});
				var rotateHandle = $("#rotate_"+ID+" > a");
				degree.val(myDegree).change(
					function(){
						var value = $(this).val();
						value = (isNaN(value) || value=='')? '0': (value<-180)? -180: (value>180)? 180: parseInt(value, 10);
						$(this).val(value);
						rIMG[0].rotateAnimation(value);
						var position = (value+180)/3.6;
						rotateHandle.css('left', position+'%');
						$.post('./php_libs/sess_write.php', {'act':'set', 'id':ID, 'degree':value});
					}
				);
*/

				if(!img_exists && typeof param['top']=="undefined") DesignCreator.resetZIndex(ID);
				ratio.change();
				// degree.change();
				my.show('normal');
				// $('#order_amount').change();
				clearTimeout(timer);
			}else if(limit++>20){
				clearTimeout(timer);
			}
		}, 250);

	}


	/********************************************************
	*		画像のズーム
	*/
	function zoomFor(my, w, h, value){
		// var canvas = my.children();
		var canvas = my;
		var ratio = value/100;
		var canvas_width = 120;

		var new_w = Math.round(w*ratio);
		var new_h = Math.round(h*ratio);
		var old_w = Math.round(my.css('width').slice(0,-2)-0);
		var old_h = Math.round(my.css('height').slice(0,-2)-0);
		var t = old_h==0? 10: (h+old_h-new_h)/2 + 10;
		var l = old_w==0? 0: (canvas_width-w+old_w-new_w)/2;
		
		if (jQuery.browser.msie){
			canvas.css({'width':new_w+'px', 'height':new_h+'px', 'top':t+'px', 'left':l+'px'});
			//var across = Math.sqrt(w*w+h*h);
			//my.width(across).height(across);
		}else{
			canvas.css({'width':new_w+'px', 'height':new_h+'px', 'top':t+'px', 'left':l+'px'});
		}
	}


	/***************************************************
	*		デザインイメージの位置を変更
	*/
	function moveimage(obj, ID){	// move to
		var image = $('#prn_'+ID);
		var my_top = parseInt(image.css('top'), 10);
		var my_left = parseInt(image.css('left'), 10);
		var my_width = parseInt(image.css('width'), 10);
		var my_height = parseInt(image.css('height'), 10);
		var new_top = my_top;
		var new_left = my_left;
		var i = 5;
		var direction = $(obj).attr('class');
		switch(direction){
			case 'arrow_up':		new_top = my_top-i;
									break;
			case 'arrow_left':		var new_left = my_left-i;
									break;
			case 'arrow_right':		new_left = my_left+i;
									break;
			case 'arrow_center':	new_top = (154 - my_height)/2;
									new_left = (120 - my_width)/2;
									break;
			case 'arrow_down':		new_top = my_top+i;
									break;
			case 'edge_top':		new_top = -50;
									break;
			case 'edge_bottom':		new_top = 204-my_height;
									break;
			case 'edge_left':		new_left = -90;
									break;
			case 'edge_right':		new_left = 210-my_width;
									break;

		}
		Math.round(new_top);
		Math.round(new_left);
		new_top = new_top<-50? -50: Math.min(new_top, 204-my_height);
		new_left = new_left<-90? -90: Math.min(new_left, 210-my_width);

		image.css({ 'top': new_top+'px', 'left': new_left+'px' });
		$.post('./php_libs/sess_write.php', {'act':'move', 'id':ID, 'top':new_top, 'left':new_left});
	}



	/***************************************************
	*		エディターの削除
	*/
	function deleteEditor(my, ID){
		var toggle = $(my).parent();
		var editor = toggle.next();

		editor.remove();
		toggle.remove();

		if($('#prn_'+ID).length){
			$.post('./php_libs/sess_write.php', {'act':'remove', 'id':ID, 'design':0});
		}
		$('#prn_'+ID).remove();
		if($('#accordion_wrap').children().length==0 && $('#accordion_wrap_back').children().length==0){
			$('#help_message_wrapper').show();
		}
		if($('#accordion_wrap').children().length==0){
			$('#drag_cont').html('');
			$.post('./php_libs/sess_write.php', {'act':'remove', 'id':'0', 'design':1});
		}
		if($('#accordion_wrap_back').children().length==0){
			$('#drag_cont_back').html('');
			$.post('./php_libs/sess_write.php', {'act':'remove', 'id':'1', 'design':1});
		}
		$('#order_amount').change();
	}


	/***************************************************
	*		エディターの選択(accordion slider)
	*/
	function toggleMe(ID){
		var toggler = $('#toggle_'+ID);

		if(!toggler.hasClass('toggle_active')){
			DesignCreator.resetZIndex(ID);
		}
		toggler.toggleClass('toggle_active').next('div[id^="editelement_"]')
			   .slideToggle('slow', function(){$('#printtext_'+ID).focus();} ).siblings('div[id^="editelement_"]:visible').slideUp('slow')
			   .prev().removeClass('toggle_active').find('p').removeClass('arrow_active');
		toggler.find('p').toggleClass('arrow_active');

	}




	/***************************************************
	*		コンテンツ（イメージ）の生成とメソッド
	*/
	var DesignCreator = {
		init:function(){
		/*
			if(cur_colorcode!="") item['color_code'] = cur_colorcode;
			if(cur_colorname!="") item['color_name'] = cur_colorname;
		*/
			var filename = item['item_code'] + "_" + item['color_code'] + ".jpg";
			var img_src = img_path + item['category_key'] + "/" + item['item_code'] + "/" + filename;
			var loadarea = $('#imgloader');

			// back image
			loadarea.prepend('<img id="img_3" src="./designcreator/img/blank.gif" />');
			$('#img_3').css({ 	'position': 'absolute',
  									'top': '0px', 'left': '0px',
				  				'width': img_width+'px', 'height': img_height+'px',
				  				'margin': '0px auto',
				  				'display': 'none'
							});
			loadarea.prepend('<img id="img_4" src="./designcreator/img/blank.gif" />');
			$('#img_4').css({ 	'position': 'absolute',
  									'top': '0px', 'left': '0px',
				  				'width': img_width+'px', 'height': img_height+'px',
				  				'margin': '0px auto',
				  				'display': 'none'
							});

			// front image
			loadarea.prepend('<img id="img_1" src="' + img_src + '" />');
			$('#img_1').css({	'position': 'absolute',
  									'top': '0px', 'left': '0px',
					  			'width': img_width+'px', 'height': img_height+'px',
					  			'margin': '0px auto'
							});
			loadarea.prepend('<img id="img_2" src="./designcreator/img/blank.gif" />');
			$('#img_2').css({ 	'position': 'absolute',
  									'top': '0px','left': '0px',
				  				'width': img_width+'px', 'height': img_height+'px',
				  				'margin': '0px auto',
				  				'display': 'none'
							});

			// $('#order_amount').val(cur_amount).change();
			
			$('#item_label').text(item['item_name']);
			$('#current_color_name').text(item['color_name']);
			$('#c'+item['color_code']+' > .checkcolor_wrapper > img', '#slider1').css('top', '-65px');
			
			// if(cur_sizeid!="") $('#order_size').val(cur_sizeid);
			
			apply(0);

		},
		resetZIndex:function(ID){
			var my = $('#prn_'+ID);
			var drg = new Array();
			$('#dragarea_wrapper .drgElement').each(function() {
				drg.push($(this));
			});
			if((parseInt(my.css('zIndex'),10) || 1) <= drg.length){
				drg.sort(function(a,b) {
					return (parseInt(a.css("zIndex"),10) || 1) - (parseInt(b.css("zIndex"),10) || 1);
				});
				for(var i=0; i<drg.length; i++) {
					drg[i].css('zIndex', i+1);
				}
				my.css('zIndex', drg.length+1);

				$.post('./designcreator/php_libs/sess_write.php', {'act':'zindex', 'id':ID});
			}
		},
		resetDesignImages:function(){
			$.post('./designcreator/php_libs/sess_write.php', {'act':'getsess'}, function(res){
				if(!res || res.charCodeAt(0)==65279) return;

				temp_images = res.split(";");
				var design_images = new Array();
				for(var i=0; i<temp_images.length; i++){
					design_images[i] = temp_images[i].split(",");
				}

				var ID = 0;

				for(var i=0; i<design_images.length; i++){
					ID =			design_images[i][0];
					var top = 		design_images[i][1];
					var left = 		design_images[i][2];
					var zoom = 		design_images[i][3];
					var degree = 	design_images[i][4];
					var zID = 		design_images[i][5];
					var type = 		design_images[i][6];
					var path = 		design_images[i][7];
					var width = 	design_images[i][8];
					var height = 	design_images[i][9];
					var size = 		design_images[i][10];
					var font = 		design_images[i][11];
					var color = 	'#'+design_images[i][12];
					var inkname = 	design_images[i][13];
					var direct = 	design_images[i][14];
					var text = 		design_images[i][15];
					var position =	design_images[i][16];

					if(type=="img"){
						var images = new Array(path, width, height);
						var styles = new Object({ 'ID':ID,
												  'top':top,
						  						  'left':left,
												  'zoom':zoom,
												  'degree':degree,
												  'zid':zID,
												  'pos':position
												});
						uploaded(images, styles);
					}else{
						addEditor(ID, position);
						var f = $('#editform_'+ID);
						$('#printtext_'+ID).val(text).focus();
						var label = text.replace(/(\r\n|\n)/g, "");
						$("#tt_"+ID).text(label.substring(0, 6));
						$('select[name="fontsize"]', f).val(size);
						$('.font_color', f).css('backgroundColor', color);
						$('#ink_name_'+ID).text(inkname);
						var fontfamily = $('select[name="fontfamily"]', f).get(0).options[0];
						fontfamily.value = font;
						fontfamily.text = FontNames[font.substring(font.lastIndexOf("/")+1)];
						fontfamily.selected = true;
						$('select[name="direction"]', f).val(direct);
						createDesignImage({ 'ID':ID,
											'src':path,
											'width':width,
											'height':height,
											'top':top,
											'left':left,
											'zoom':zoom,
											'degree':degree,
											'zid':zID,
											'pos':position
											});
					}
				}

				toggleMe(ID);

			});
		},
		compose:function(f){
			$.post('./designcreator/php_libs/sess_write.php', {'act':'sort'}, function(res){
				if(!res || res.charCodeAt(0)==65279){
					DesignCreator.progressBar(false);
					f.submit();
				}else{
					var postStr = 'download=true&scalewidth=310&scaleheight=400&position[]=0&position[]=1';
					$.ajax({url: './php_libs/compo.php',
						type: 'POST',
						dataType: 'text',
						data: postStr,
	 					async: true,
	 					success: function(r){
 							DesignCreator.progressBar(false);
 							if(r==""){
 								$.msgbox('エラーが発生した為デザインが作成できませんでした。');
 								return;
 							}
							f.submit();
						}
					});
				}
			});
			DesignCreator.progressBar(true);
		},
		progressBar: function(mode){
			if(mode){
				DesignCreator.screenOverlay(mode);
				var body_y = $(document).scrollTop() -30;
				$('#loading_inner').css({'margin-top':body_y+'px'}).show();
			}else{
				$('#loading_inner').hide('normal', function(){ DesignCreator.screenOverlay(mode); });
			}
		},
		screenOverlay: function(mode){
			var body_w = $(document).width();
			var body_h = $(document).height();
			if(mode){
				$('#overlay').css({ 'width': body_w+'px',
									'height': body_h+'px',
									'opacity': 0.5}).show();
			}else{
				$('#overlay').css({ 'width': '0px',	'height': '0px'}).hide();
			}
		},
		hide_popup:function(){
			$('#helppane').fadeOut('normal', function(){
				DesignCreator.screenOverlay(false);
				$(this).html('');
			});
		}
	}




	/***************************************************
	*		インクカラーの変更
	*/
	var inkcolor = {
		show:function(){	// show inkcolor palette
			$('#colorpalette').load('./designcreator/txt/inkcolor_palette.txt', function(){
				$.msgbox($(this).html());
			});
		},
		close:function(){	// hide inkcolor palette
			// var ID = curr_accordion.find('div[class*="toggle_active"]').attr('ID');
			// ID = ID.substring(ID.lastIndexOf('_')+1);
			var ID = 0;
			if(arguments.length>0){
				$('#fontcolor_'+ID).css('background-color', arguments[0]['color']);
				$('#ink_name_'+ID).text(arguments[0]['ink']);
			}
			if($('#prn_'+ID).length>0) apply(ID);
			$('#colorpalette').animate({ 'height': '0px', 'opacity':0 }, 250, function(){ $(this).html("");
				$('#msgbox').modal('hide');
				$('#printtext_'+ID).focus();
			});
		},
		change:function(my){	// apply ink color
			var hex = $(my).css('background-color');
			var inkname = my.childNodes[0].firstChild.nodeValue;
			this.close({'color':hex, 'ink':inkname});
		}
	}



	/***************************************************
	*		フォントの変更
	*/
	var font = {
		show:function(my){	// show font list
			var fonttype = my.options[my.selectedIndex].value;
			$('select[name="fontfamily"]').val(selectedFontPath);
			$('#fontpalette').load('./designcreator/php_libs/get_fontlist.php', {'fonttype': fonttype}, function(){
				$.msgbox($(this).html());
			});
		},
		close:function(fontname){	// hide font list
			//var ID = curr_accordion.find('div[class*="toggle_active"]').attr('ID');
			//ID = ID.substring(ID.lastIndexOf('_')+1);
			var ID = 0;
			var f = "#editform_" + ID;
//			$('#msgbox').modal('hide');
			$('#fontpalette').animate({'height':'0px'}, 250, function(){
				$('#fontpalette').html("");
				if(fontname){
					if( fontname.match(/\ja/) ){
						selectedFontPath = fontname;
					}else{
						var isAscii = true;
						var str = $('#printtext_'+ID).val();
						for(var i=0; i<str.length; i++){
							if(escape(str.charAt(i)).length>3){
								isAscii = false;
								fontname = false;
								$.msgbox('英字フォントでは半角英数のみ使用できます。');
								break;
							}
						}
						if(isAscii){
							selectedFontPath = fontname;
							$('select[name="direction"]', f).val('horizontal');
						}
					}
				}
				selectedFont = FontNames[selectedFontPath.substring(selectedFontPath.lastIndexOf("/")+1)];
				var font = $('select[name="fontfamily"]', f).get(0).options[0];
				font.value = selectedFontPath;
				font.text = selectedFont;
				font.selected = true;
				$('#printtext_'+ID).focus();
				if($('#prn_'+ID).length>0 && fontname) {
					$('#msgbox').modal('hide');
					apply(ID);
				}
			});
		},
		change:function(fontname){	// apply font
			this.close(fontname);
		},
		apply:function(ID){			// change direction
			var f = "#editform_" + ID;
			var fontpath = $('select[name="fontfamily"]', f).get(0).options[0].value;
			if(!fontpath.match(/^\ja/)){
				$('select[name="direction"]', f).val('horizontal');
				$.msgbox('英字フォントでの縦書きは出来ません。');
			}else{
				apply(ID);
			}
		}
	}



	/***************************************************
	*		アイテムカラーの変更
	*/
	var itemcolor = {
		info:function(my){
			var check = $(my).find('img');
			var colorname = check.attr('alt');
			$('#current_color_name').text(colorname);
			if(parseInt(check.css('top')) > -60) check.css('top', '-45px');
		},
		undo:function(my){
			$('#current_color_name').text(item['color_name']);
			var check = $(my).find('img');
			if(parseInt(check.css('top')) > -60) check.css('top', '-25px');
		},
		change:function(my, id){
			if(item['color_code'] != id){
				var check = $(my).find('img');
				$('#c'+item['color_code']+' div img', '#slider1').css('top', '-25px');
				item['color_code'] = id;
				item['color_name'] = check.attr('alt');
				
				/*
				if(id>=700){
					item['item_code'] = '137-rss';
					item['item_name'] = 'ラグランＴシャツ';
					$('#item_label').text('ラグランＴシャツ');
					document.forms.addcart.item_code.value = '137-rss';
				}else{
					item['item_code'] = '085-cvt';
					item['item_name'] = 'ヘビーウェイトＴシャツ';
					$('#item_label').text('ヘビーウェイトＴシャツ');
					document.forms.addcart.item_code.value = '085-cvt';
				}
				*/
				
				check.css('top', '-65px');
				$('#color_code').val(id);
				var old_index = 1;
				var new_index = 0;
				
				/*
				if($('#reverse_button').text().match(/back$/)){
					old_index = 1;
					new_index = 0;
				}else{
					old_index = 3;
					new_index = 2
				}
				*/
				
				filename = item['item_code'] + "_" + item['color_code'] + ".jpg";
				img_src = img_path + item['category_key'] + "/" + item['item_code'] + "/" + filename;
				var old_image = $('#imgloader > img').eq(old_index);
				var new_image = $('#imgloader > img').eq(new_index);
				new_image.attr({'src':img_src}).show('fast', function(){
					old_image.fadeOut('slow', function(){ $(this).after(new_image); });
				});
				

				/* 制限サイズの適用
				if( id=='001' || id=='003' || id=='005' || id=='010' || id=='014' ||
					id=='015' || id=='025' || id=='031' || id=='032' || id=='034' ){

					if($('#order_size').children('option:last-child').text()!="4L")
						$('#order_size').append('<option value="23">4L</option>');
				}else{
					if($('#order_size').val()=='23') $('#order_size').val('18');
					$('#order_size').children('option[value="23"]').remove();
				}
				*/
			}

		}
	}



	/***************************************************
	*		メッセージボックス
	*/
/*
	var msgbox = {
		alert:function(message){
			$("#dialog").dialog({ bgiframe: true, draggable: true, resizable: false, autoOpen: false, width: 400,
				modal: true,
				title: '確　認',
				position:['center',200],
				open:function(){
					$('#dialog').html(
					'<h1 style="font-size: 150%;">Takahama Life Art</h1><em style="color:#ff6631;">-SELF DESIGN-</em><br/><hr />'+
					'<img alt="error message" src="./img/crystal/Crystal_Clear_app_error.png" widht="48" height="48" style="float:left;margin-right:20px;vertical-align:middle" />'+
					'<p style="margin:20px 0px;line-height:1.5;">'+message+'</p>')
					.parent().hide().fadeIn('slow');
				},
				hide:'swing',
				close:function(){ $(this).dialog('destroy'); },
				buttons:{ 'OK':function(){ $(this).parent().fadeOut('slow', function(){ $('#dialog').dialog('close'); });  } }
			}).dialog('open');
		}
	}
*/
