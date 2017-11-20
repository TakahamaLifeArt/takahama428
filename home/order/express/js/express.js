/*
*	Takahama Life Art
*	an urgent order
*/

$(function(){
	
	$.extend({
		sendmail_check:function(f){
			if(f.customername.value.trim()==""){
				$.msgbox("お名前を入力してください。");
				return false;
			}
			if(f.ruby.value.trim()==""){
				$.msgbox("フリガナを入力してください。");
				return false;
			}
			if(f.addr0.value.trim()=="" || f.addr1.value.trim()==""){
				$.msgbox("ご住所を入力してください。");
				return false;
			}
			if(!$.check_email(f.email.value)){
				return false;
			}
			if(f.tel.value.trim()==""){
				$.msgbox("お電話番号を入力してください。");
				return false;
			}
			if(f.deliveryday.value.trim()==""){
				$.msgbox("ご希望納期を入力してください。");
				return false;
			}
			if(f.Free_001.value.trim()==0 && f.S_001.value.trim()==0 && f.M_001.value.trim()==0 && f.L_001.value.trim()==0 && f.XL_001.value.trim()==0 && f.S_005.value.trim()==0 && f.M_005.value.trim()==0 && f.L_005.value.trim()==0 && f.XL_005.value.trim()==0){
				$.msgbox("枚数を入力してください。");
				return false;
			}
			if(!$.chkInks()){
				$.msgbox("プリントする位置とデザインの色数を指定してください。");
				return false;

			}
			if(f.tel.value.trim()==""){
				$.msgbox("お電話番号を入力してください。");
				return false;
			}

			$.updatePosition();	// プリント位置と色数の指定内容を更新
			f.submit();
		},
		add_attach:function(my){
			var new_row = '<tr><th>&nbsp;</th><td>&nbsp;</td>';
			new_row += '<td><input type="file" name="attachfile[]" /><ins class="abort">取消</ins></td></tr>';
			$(my).closest('tr').before(new_row);
		},
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
			var ink = 0;
			var posname = "";
			var html = "";

			if(noprint==1){
				html = '<input type="hidden" name="noprint" value="1">';
			}else{
				box.each( function(){
					$('.inkbox table tbody tr', this).each( function(){
						ink = $(this).find('select').val();
						if(ink==0) return true;	// continue
						posname = $(this).children('th:first').text();
						html += '<input type="hidden" name="printpos[]" value="'+posname+'_'+ink+'">';
					});
				});
			}
			$("#pos_info").html(html);
		}
	});
	
	
	// 添付ファイルの追加
	$('#enq_table .add_attachfile').on('click', function(e){ $.add_attach(e.target) });
	
	
	// 追加した添付ファイルを削除
	$('#enq_table').on('click', '.abort', function(){
		$(this).closest('tr').remove();
	});
	
	
	// メール送信
	$('#sendmail').click( function(){
		$.sendmail_check(document.forms.express_form);
	});
	
	
	// init
	$.showPrintPosition();
});
