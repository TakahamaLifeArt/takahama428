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

		eMailer.onChanged('#size_table textarea');

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


	// 郵便番号
	$('#zipcode').on('change', function () {
		eMailer.onChanged('#addr0');
		eMailer.onChanged('#addr1');
	});


	$.extend({
		showPrintPosition: function(){
			/**
			 * プリント位置画像（絵型）とインク色数指定のタグ生成
			 */
			var isResult = false;
			$.when(
				$.ajax({url:'/php_libs/pageinfo.php', async:true, type:'POST', dataType:'text', data:{'act':'position', 'itemid':4, 'express':true}}),
				$.ajax({url:'/php_libs/pageinfo.php', async:true, type:'POST', dataType:'text', data:{'act':'position', 'itemid':167, 'express':true}})
			).then(function(r1,r2){
				isResult = true;
//				var val1 = r1[0].split('|')[0];
//				var val2 = r2[0].split('|')[0];
				$('#pos_wrap').html(r1[0]+r2[0]);
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
	
	// init
	$.showPrintPosition();
	
});
