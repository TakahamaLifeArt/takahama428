/*
*	Takahama Life Art
*	an urgent order
*/

$(function(){
	'use strict';
	
	/**
	 * Polyfill
	 */
	if (!Array.prototype.reduce) {
		Array.prototype.reduce = function reduce(accumulator) {
			if (this === null || this === undefined) throw new TypeError("Object is null or undefined");

			var i = 0,
				l = this.length >> 0,
				curr;

			if (typeof accumulator !== "function") // ES5 : "If IsCallable(callbackfn) is false, throw a TypeError exception."
				throw new TypeError("First argument is not callable");

			if (arguments.length < 2) {
				if (l === 0) throw new TypeError("Array length is 0 and no second argument");
				curr = this[0];
				i = 1; // start accumulating at the second element
			} else
				curr = arguments[1];

			while (i < l) {
				if (i in this) curr = accumulator.call(undefined, curr, this[i], i, this);
				++i;
			}

			return curr;
		};
	}


	/**
	 * 入力項目の検証
	 */
	eMailer.onValidate('#sendmail', function () {
		/**
		 * 注文枚数
		 * 複数Inputの内一つ以上の入力が必須
		 * 合計1枚以上
		 * ---
		 * @return {bool} 妥当であれば{@code true}を返す
		 */
		var minimumNumber = 1; // 最低枚数
		
		$('#size_table').find('textarea').val('');
		if ($.makeArray($('#size_table input[type="number"]')).reduce(function (prev, curr, index, ary) {
			// 注文商品
			var val = curr.value-0,
				textarea = '',
				text = '',
				info = '';
			if (val>0) {
				textarea = $(curr).closest('tr').children('td:first').children('textarea')
				info = textarea.val();
				info += "サイズ：　 "+ $(curr).attr('name') +"\n";
				info += "　　枚数：　 " + val + " 枚\n";
				info += "-------------------------\n\n　　";
				textarea.val(info);
			}
			return prev + val;
		}, 0) < minimumNumber) {
			$('#size_table').find('textarea').val("　枚数：　 0 枚\n-------------------------\n\n");
			$.msgbox('ご注文枚数をご入力ください');
			return false;
		}

		// 自動入力ではchange event が発生しないため
		eMailer.onChanged('#size_table textarea');
		eMailer.onChanged('#addr0');
		eMailer.onChanged('#addr1');

		// 都道府県
		if ($('#addr0').val().trim() !== '') {
			$('#addr0')[0].classList.remove('is_invalid');
		} else {
			$('#addr0')[0].classList.add('is_invalid');
			$.msgbox('都道府県の入力は必須です');
			return false;
		}

		// 市区町村
		if ($('#addr1').val().trim() !== '') {
			$('#addr1')[0].classList.remove('is_invalid');
		} else {
			$('#addr1')[0].classList.add('is_invalid');
			$.msgbox('市区町村の入力は必須です');
			return false;
		}

		// 希望納期
		if ($('#datepick').is('.is_invalid')) {
			$.msgbox('ご希望納期をご指定ください');
			return false;
		}

		// プリント位置と色数
		if(!$.chkInks()){
			$.msgbox("プリントする位置とデザインの色数を指定してください。");
			return false;
		}
		$.updatePosition();
		
		return true;
	});


	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		window.location.href = '/contact/thanks.php?title=expresstoday';
	});


	// カレンダー
	$('#datepick').datepickCalendar({
		disableBeforeDate: '+1day',
		onSelect: function(dateText){
			let elem = document.querySelector('#datepick');
			
			if (dateText) {
				elem.classList.remove('is_invalid');
			} else {
				elem.classList.add('is_invalid');
			}
			eMailer.onChanged('#datepick');
		}
	});
	
	
	$.extend({
//		sendmail_check:function(f){
//			if(f.customername.value.trim()==""){
//				$.msgbox("お名前を入力してください。");
//				return false;
//			}
//			if(f.ruby.value.trim()==""){
//				$.msgbox("フリガナを入力してください。");
//				return false;
//			}
//			if(f.addr0.value.trim()=="" || f.addr1.value.trim()==""){
//				$.msgbox("ご住所を入力してください。");
//				return false;
//			}
//			if(!$.check_email(f.email.value)){
//				return false;
//			}
//			if(f.tel.value.trim()==""){
//				$.msgbox("お電話番号を入力してください。");
//				return false;
//			}
//			if(f.deliveryday.value.trim()==""){
//				$.msgbox("ご希望納期を入力してください。");
//				return false;
//			}
//			if(f.Free_001.value.trim()==0 && f.S_001.value.trim()==0 && f.M_001.value.trim()==0 && f.L_001.value.trim()==0 && f.XL_001.value.trim()==0 && f.S_005.value.trim()==0 && f.M_005.value.trim()==0 && f.L_005.value.trim()==0 && f.XL_005.value.trim()==0){
//				$.msgbox("枚数を入力してください。");
//				return false;
//			}
//			if(!$.chkInks()){
//				$.msgbox("プリントする位置とデザインの色数を指定してください。");
//				return false;
//
//			}
//			if(f.tel.value.trim()==""){
//				$.msgbox("お電話番号を入力してください。");
//				return false;
//			}
//
//			$.updatePosition();	// プリント位置と色数の指定内容を更新
//			f.submit();
//		},
//		add_attach:function(my){
//			var new_row = '<tr><th>&nbsp;</th><td>&nbsp;</td>';
//			new_row += '<td><input type="file" name="attachfile[]" /><ins class="abort">取消</ins></td></tr>';
//			$(my).closest('tr').before(new_row);
//		},
		showPrintPosition: function(){
			/**
			 * プリント位置画像（絵型）とインク色数指定のタグ生成
			 */
			var isResult = false;
			$.when(
				$.ajax({url:'/php_libs/orders.php', async:true, type:'POST', dataType:'text', data:{'act':'orderposition', 'itemid':4, 'catid':1}}),
				$.ajax({url:'/php_libs/orders.php', async:true, type:'POST', dataType:'text', data:{'act':'orderposition', 'itemid':167, 'catid':8}})
			).then(function(r1,r2){
				isResult = true;
				var val1 = r1[0].split('|')[0];
				var val2 = r2[0].split('|')[0];
				$('#pos_wrap').html(val1+val2);
				$.setPrintposEvent();
			});
			return isResult;
		},
		setPrintposEvent: function(){
			/**
			 * プリント位置画像のロールオーバーとクリックイベント
			 * 複数指定可、クリックで指定を切替
			 */
			if($('#pos_wrap').children('div').attr('class').split('_')[1]==46) return;		// プリントなし商品
			$('#pos_wrap .posimg').each( function(){
				$(this).children('img:not(:nth-child(1))').each(function() {
					var postfix = '_on';
					var img = $(this);
					var posid = img.parent().parent().attr('class').split('_')[1];
					var src = img.attr('src');
					var src_on = src.substr(0, src.lastIndexOf('.'))
					+ postfix
					+ src.substring(src.lastIndexOf('.'));
					$('<img>').attr('src', src_on);
					img.hover(
						function() {
							img.not('.cur').attr('src', src_on);
						},
						function() {
							img.not('.cur').attr('src', src);
						}
					).click( function(e){
						var tbl = img.parent().next().children('table');
						var tbody = tbl.children('tbody');
						var base = tbl.children('caption').text();
						if(img.is('.cur')){
							img.attr('src', src).removeClass('cur');
							tbody.find('tr.pos-'+img.attr('class')).remove();
						}else{
							var posname = img.attr('alt');
							var tr = '<tr class="pos-'+img.attr('class')+'">';
							tr += '<th>'+posname+'</th>';
							tr += '<td colspan="2"><select><option value="0" selected="selected">選択してください</option>';
							tr += '<option value="1">1色</option><option value="2">2色</option><option value="3">3色</option>';
							tr += '<option value="9">4色以上</option></select></td>';
							tr += '</tr>';
							img.attr('src', src_on).addClass('cur');
							tbody.append(tr);
							var added = tbody.children('tr:last');
						}
					});
				});
			});

			$('#pos_wrap .inkbox').each( function(){
				var posimg = $(this).prev('.posimg');
				var img = '';
				$('table tbody tr', this).each( function(){
					var posname = $(this).find('th:first').text();
					if(posname==""){
						$(this).remove();
						return true;
					}
					img = posimg.children('img[alt="'+posname+'"]');
					$(this).attr('class', 'pos-'+img.attr('class'));
					img.attr('src',img.attr('src').replace(/.png/,'_on.png')).addClass('cur');
				});
			});
		},
		chkInks: function(){
			/**
			 * インク指定の確認
			 */
			var isInks = false;
			if($('#pos_wrap .inkbox select').length>0 && !$('#noprint').prop('checked')){
				$('#pos_wrap .inkbox select').each( function(){
					if($(this).val()!=0){
						isInks = true;
						return false;	// break;
					}
				});
			}else{
				isInks = true;	// プリントなし商品はインク指定もなし
			}
			return isInks;
		},
		updatePosition: function(){
			/**
			 * プリント位置とインク色数
			 */
			var box = $('#pos_wrap div[class^=ppid_]');
			var noprint = $('#noprint').is(':checked')? 1: 0;
			var html = "";

			if(noprint==1){
				html = 'プリントなし';
			}else{
				box.each( function(){
					$('.inkbox table tbody tr', this).each( function(){
						var select = $(this).find('select'),
							ink = 0,
							posname = '';
						
						if (select.val()==0) return true;		// continue
						html += $(this).children('th:first').text() + ": " + select.find('option:selected').text() + "\n　　";
					});
				});
			}
			$("#pos_info textarea").val(html);
			eMailer.onChanged('#pos_info textarea');
		}
	});
	
	
	// 添付ファイルの追加
//	$('#enq_table .add_attachfile').on('click', function(e){ $.add_attach(e.target) });
	
	
	// 追加した添付ファイルを削除
//	$('#enq_table').on('click', '.abort', function(){
//		$(this).closest('tr').remove();
//	});
	
	
	// メール送信
//	$('#sendmail').click( function(){
//		$.sendmail_check(document.forms.express_form);
//	});
	
	
	// init
	$.showPrintPosition();
});
