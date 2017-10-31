/**
 *	Takahama Life Art
 *	マイページの追加注文フォーム
 */

$(function(){
	
	/***************************************************
	 *		全ての画像の読込みを完了してから処理を実行させる
	 */
	$.fn.imagesLoaded = function(callback){
		var elems = this.filter('img'),
			len = elems.length,
			blank = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";

		elems.bind('load.imgloaded',function(){
			if (--len <= 0 && this.src !== blank) {
				elems.unbind('load.imgloaded');
				callback.call(elems,this);
			}
		}).each(function(){
			// cached images don't fire load sometimes, so we reset src.
			if (this.complete || this.complete === undefined) {
				var src = this.src;
				// webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
				// data uri bypasses webkit log warning (thx doug jones)
				this.src = blank;
				this.src = src;
			}
		});
		return this;
	};
	
	
	// フォームのエンターキーを無効にする
	$('form').on("keypress", "input:not(.allow_submit)", function(e) {
		if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
			return false;
		} else {
			return true;
		}
	});
	
	
	jQuery.extend({
		curitemid: 0,
		init: function(){
			$.curitemid = $('#item_selector').val();
			if ($.curitemid==0) {
				$('.item_colors', '#price_wrap').removeClass('throbber').fadeOut();
				$('.color_thumb').show();
			} else {
				$('.color_thumb li img').imagesLoaded( function(){
					$('.color_thumb').show();
					$('.item_colors', '#price_wrap').removeClass('throbber');
				});
			}
			$.showSizeform($.curitemid, '', []);
		},
		changeThumb: function(my){
		/**
		 * サムネイルの変更
		 */
			var colorcode = my.attr("alt");
			var colorname = my.attr("title");
			var src = my.attr('src');
			$("#notes_color").html(colorname);
			$(".color_thumb li.nowimg").removeClass("nowimg");
			my.parent().addClass("nowimg");
			
			// カレントカラーの写真を更新
			src = src.replace(/_s/,'');
			$('#item_image img').attr('src', src);
			
			// カラーの変更でサイズテーブルを書き換える際に枚数を引継ぐ
			var tmp = {};
			/* 廃止
			$('#price_wrap table tbody tr:odd td').each( function(index){
				var sizeid = $(this).attr('class').split('_')[1];
				tmp[sizeid] = $(this).find('input.forNum').val();
			});
			*/
			$.showSizeform($.curitemid, colorcode, tmp);
		},
		focusNumber: function(my){
			if ($(my).val()=="0") {
				$(my).val("");
			}
		},
		showSizeform: function(itemid, colorcode, volume){
		/**
		 * サイズごとの枚数入力フォーム
		 * @itemid			アイテムID
		 * @colorcode		アイテムカラーコード
		 * @volume			サイズIDをキーにした枚数のハッシュ
		 */
			if (itemid==0) {
				$('.item_colors', '#price_wrap').removeClass('throbber');
				itemname = $('#item_selector option:selected').text();
				$.msgbox(itemname+"の取扱が中止となっております。\n詳細は弊社スタッフまでお問合せください。");
				$('#price_wrap').hide();
				
//				$('#price_wrap h2').text('2.枚数をご指定ください。');
//				$('#price_wrap table caption').text('(1) 枚数');
//				var size_body = '<th>枚数</th><td class="size_0_未定_0"><input type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" /> 枚</td>';
//				var size_table = '<tr>'+size_body+'</tr>';
//				$('table:first tbody', '#price_wrap').html(size_table);
			} else {
				$('#price_wrap').show();
				$('#price_wrap h2').text('2.カラーとサイズごとの枚数をご指定ください。');
				$('#price_wrap table caption').text('(2) サイズと枚数');
				$.getJSON($.TLA.api+'?callback=?', {'act':'sizeprice', 'itemid':itemid, 'colorcode':colorcode, 'output':'jsonp'}, function(r){
					var pre_sizeid = 0;
					var cost = 0;
					var amount = 0;
					var size_head = '';
					var size_body = '';
					var sum = 0;
					var size_table = '';
					$.each(r, function(key, val){
						if (typeof volume[val.id]=='undefined') {
							amount = 0;
						} else {
							amount = volume[val.id]-0;
						}
						sum += amount;
						if (key==0) {
							pre_sizeid = val['id'];
							cost = val['cost'];
							size_head = '<th></th><th>'+val['name']+'</th>';
							size_body = '<th>'+val['cost'].toLocaleString('ja-JP')+' 円</th><td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'">';
							size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum"  onfocus="$.focusNumber(this);"/></td>';
						} else if (cost != val['cost'] || (val['id']>(++pre_sizeid) && val['id']>10)) {	// 単価が違うかまたは、サイズ160以下を除きサイズが連続していない
							size_table += '<tr class="heading">'+size_head+'</tr>';
							size_table += '<tr>'+size_body+'</tr>';
							
							pre_sizeid = val['id'];
							cost = val['cost'];
							size_head = '<th></th><th>'+val['name']+'</th>';
							size_body = '<th>'+val['cost'].toLocaleString('ja-JP')+' 円</th><td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'">';
							size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum"  onfocus="$.focusNumber(this);"/></td>';
						} else {
							pre_sizeid = val['id'];
							size_head += '<th>'+val['name']+'</th>';
							size_body += '<td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'">';
							size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum"  onfocus="$.focusNumber(this);"/></td>';
						}
					});
					size_table += '<tr class="heading">'+size_head+'</tr>';
					size_table += '<tr>'+size_body+'</tr>';
					$('table:first tbody', '#price_wrap').html(size_table);
					
					//$.addOrder(false);
				}).fail(function(xhr, status, error){
					console.log("エラー：" + error);
					console.log("テキスト：" + xhr.statusText);
				});
			}
		},
		changeItem: function(){
		/**
		 * 商品の変更
		 */
			$.curitemid = $('#item_selector').val();
			$('.color_thumb, .num_of_color, #notes_color').html('');
			$('#item_image').hide();
			$('table:first tbody', '#price_wrap').html('');
			
			// 取扱中止などから商品情報が取得できない場合
			if ($.curitemid==0) {
				$('.thumb_wrap', '#price_wrap').slideUp();
				$.showSizeform($.curitemid, '', []);
				return;
			}
			
			$('.thumb_wrap', '#price_wrap').slideDown().find('.item_colors').addClass('throbber');
			$('.color_thumb').fadeOut('fast', function(e){
				$.getJSON($.TLA.api+'?callback=?', {'act':'itemattr', 'itemid':$.curitemid, 'output':'jsonp'}, function(r){
					var color_count = 0;
					var thumbs = '';
					var categorykey = '';
					var itemcode = '';
					var itemname = '';
					var curcolorcode = '';
					var curcolorname = '';
					var path = '';
					var src = '';
					$.each(r.category, function(cat, catname){
						categorykey = cat;
					});
					$.each(r.name, function(itemcode, itemname){
						path = categorykey+'/'+itemcode;
					});
					
					// 取扱中止などから商品情報が取得できない場合
					if (!categorykey) {
						$('.item_colors', '#price_wrap').removeClass('throbber');
						itemname = $('#item_selector option:selected').text();
						$.msgbox(itemname+"の取扱が中止となっております。\n詳細は弊社スタッフまでお問合せください。");
						$('#price_wrap').hide();
						return;
					}
					
					$.each(r.code, function(code, colorname){
						color_count++;
						var colorcode = code.split('_')[1];
						thumbs += '<li';
						if (color_count==1) {
							curcolorname = colorname;
							curcolorcode = colorcode;
							thumbs += ' class="nowimg"';
							src = _IMG_PSS+'items/'+path+'/'+code+'.jpg';
						}
						thumbs += '><img alt="'+colorcode+'" title="'+colorname+'" src="'+_IMG_PSS+'items/'+path+'/'+code+'_s.jpg" /></li>';
					});
					
					$('#item_image img').attr('src',src).parent().show();
					$('.color_thumb').html(thumbs);
					$('.num_of_color').text(color_count);
					$('#notes_color').text(curcolorname);
					$('.color_thumb li img').imagesLoaded( function(){
						$('.color_thumb').show();
						$('.item_colors', '#price_wrap').removeClass('throbber');
					});
					$.showSizeform($.curitemid, curcolorcode, []);
					//$.showPrintPosition();
				});
			});
		},
		addList: function(e){
		/**
		 * 申込リストに追加
		 * 同じ商品が見積もりテーブルにある場合、同じサイズは上書する
		 */
			var item_name = $('#item_selector option:selected').text();
			var item_id = $("#item_selector").val();
			var color_name = $('#notes_color').text();
			var size = [];
			var vol = [];
			var a = 0;
			
			if (color_name=="") {
				$.msgbox('商品の指定ができません。');
				return;
			}
			
			$('#price_wrap table tbody tr td').each( function(){
				var v = $(this).children('input.forNum').val()-0;
				if (v==0) return true;
				size[a] = $(this).attr('class').split('_')[2];
				vol[a] = v;
				a++;
			});
			
			if (a==0) {
				$.msgbox('枚数をご指定ください。');
				return;
			}
			
			var isExist = false;
			for (var t=0; t<size.length; t++) {
				isExist = false;
				$('#detail_item tbody tr').each( function(){
					var itemname = $('td:eq(0)', this).text();
					var colorname = $('td:eq(1)', this).text();
					var sizename = $('td:eq(2)', this).text();
					if ( itemname==item_name && colorname==color_name) {
						if (sizename==size[t]) {
							$('td:eq(3)', this).text(vol[t]);
							isExist = true;
							return false;	// break;
						}
					}
				});
				if (!isExist) {
					$('#detail_item tbody').append('<tr><td>'+item_name+'</td><td>'+color_name+'</td><td class="toc">'+size[t]+'</td><td class="tor">'+vol[t]+'</td><td class="toc"><input type="button" value="削除" onclick="$.delOrder(this);"></td></tr>');
				}
			}
			
			var tot = 0;
			$('#detail_item tbody tr').each( function(){
				tot += $('td:eq(3)', this).text()-0;
			});
			$('#detail_item tfoot tr .tot ins').text(tot);
		},
		delOrder: function(my){
		/**
		 * 見積テーブルから商品を削除
		 */
			var row = $(my).closest('tr');
			row.remove();
			var tot = 0;
			$('#detail_item tbody tr').each( function(){
				tot += $('td:eq(3)', this).text()-0;
			});
			$('#detail_item tfoot tr .tot ins').text(tot);
		},
		add_attach:function(id){
			var new_row = '<tr><th>添付ファイル</th><td>&nbsp;</td>';
			new_row += '<td><input type="file" name="attachfile[]" /><ins class="abort">×取消</ins></td></tr>';
			$('#'+id+' tbody').append(new_row);
		},
		sendmail_check:function(f){
		/**
		 * メールフォームの必須項目チェックとメール送信
		 * @f	document.forms.フォームのname属性値
		 */
			if (!$.check_email(f.email.value)) {
				return false;
			}
			if (f.customername.value.trim()=="") {
				$.msgbox("お名前を入力してください。");
				return false;
			}
			var ls = '';
			var tot = 0;
			$('#detail_item tbody tr').each( function(){
				var itemname = $('td:eq(0)', this).text();
				var colorname = $('td:eq(1)', this).text();
				var sizename = $('td:eq(2)', this).text();
				var amount = $('td:eq(3)', this).text()-0;
				tot += amount;
				
				ls += '<input type="hidden" name="itemname[]" value="'+itemname+'">';
				ls += '<input type="hidden" name="itemsize[]" value="'+sizename+'">';
				ls += '<input type="hidden" name="color[]" value="'+colorname+'">';
				ls += '<input type="hidden" name="amount[]" value="'+amount+'">';
			});
			
			if (tot==0) {
				$.msgbox("商品情報がありません。ご確認ください。");
				return false;
			}
			
			$('#estimate_detail').html(ls);
			
			f.submit();
		},
	});
	
	
	/**
	 * 申し込みメール送信
	 */
	$('#sendmail').on('click', function(){
		$.sendmail_check(document.forms.contact_form);
	});
	
	
	/**
	 * 追加した添付ファイルを削除
	 */
	$('#enq_table').on('click', '.abort', function(){
		$(this).closest('tr').remove();
	});
	
	
	/**
	 * 申込リストに追加
	 */
	$('#addList').on('click', function(){
		$.addList();
	});
	
	
	/**
	 * change thumbnails
	 */
	$(".color_thumb li img").on('click', function(){
		$.changeThumb($(this));
	});
	
	
	/**
	 * calendar
	 */
	$(".datepicker").datepicker();
	
	
	/**
	 * 別のお届け先を指定
	 */
	$('#deli').on('change', function(){
		if($(this).is(':checked')){
			$('#deli_wrap').show();
		}else{
			$('#deli_wrap').find('input[type="text"]').val('');
			$('#deli_wrap').hide();
		}
	});
	
	
	/* initialize */
	$.init();
	
});
