/*
*	Takahama Life Art
*	contacts module
*/

$(function(){
	jQuery.extend({
		sendmail_check:function(){
			var f = document.forms.contact_form;
			if(!$.check_email(f.email.value)){
				return false;
			}
			if(f.tel.value.trim()==""){
				$.msgbox("お電話番号を入力してください。");
				return false;
			}
			if (!$.check_phone(f.tel)) {
				$.msgbox("お電話番号をご確認ください。");
				return false;
			}
			if(f.customername.value.trim()==""){
				$.msgbox("お名前を入力してください。");
				return false;
			}
			if(f.ruby.value.trim()==""){
				$.msgbox("フリガナを入力してください。");
				return false;
			}
			
			if(f.mode.value=="confirm"){
				$('#fileupload').data('confirm', 1);
				f.mode.value = 'send';
				$('#sendmail').text('送　信');
				$('input[type="text"], :radio[name=repeater], textarea, .txt, label', f).addClass('confirmation');
				$('input[name="subtitle[]"]:checked').each( function(){
					var txt = $(this).closest('td').children('.txt');
					var chk = txt.html()==""? $(this).val(): ',　'+$(this).val();
					txt.append(chk);
				});
				$(':radio[name=repeater]:checked', f).each( function(){
					var v = $(this).val();
					$(this).closest('td').children('.txt').html(v);
				});
				$('input[type="text"]', f).each( function(){
					var v = $(this).val().replace('/(\r\n|\n|\r)/g', '<br>');
					$(this).prev().html(v);
				});
				var $textarea = $('textarea', f);
				var v = $textarea.val().replace(/(\r\n|\n|\r)/g, "<br>");
				$textarea.prev().html(v);
				$('.title_confirmation, .msg').show();
				$('#goback').prop('hidden', false);
				
				$('#fileupload-table tbody .template-download .delete').removeClass('in');
				$('#fileupload .fileupload-buttonbar .fileinput-button').removeClass('in');
				$('#fileupload .fileupload-buttonbar .start').click();
			}else if(f.mode.value=="send"){
				f.submit();
			}
		},
		add_attach:function(id){
			var new_row = '<tr><td><div id="table_left">添付ファイル</div>';
			new_row += '<div id="table_right"><input type="file" name="attachfile[]"></div><ins class="abort">×取消</ins></td></tr>';
			$('#'+id+' tbody tr:last').before(new_row);
			// 追加した添付ファイルを削除
			$('.abort').on('click', function(){
				$(this).closest('tr').remove();
			});
		}
	});
	
	$('#sendmail').on('click', function(){
		$.sendmail_check();
	});
	
	
	// 追加した添付ファイルを削除
	$('.abort').on('click', function(){
		$(this).closest('tr').remove();
	});
	
	
	// 確認画面の戻るボタン
	$('#goback').click( function(){
		var f = document.forms.contact_form;
		f.mode.value = 'confirm';
		$('#sendmail').text('入力内容の確認');
		$('input[type="text"], :radio[name=repeater], textarea, .txt, label', f).removeClass('confirmation');
		$('.txt', f).html('');
		$('.title_confirmation, .msg').hide();
		$('#goback').prop('hidden', true);
		
		$('#fileupload-table tbody .template-download .delete').addClass('in');
		$('#fileupload .fileupload-buttonbar .fileinput-button').addClass('in');
	});

	
	/* calendar */
	if(typeof(datepicker)=='function'){
		$("#datepicker").datepicker();
	}
	
	
	// 添付ファイルの追加
	$('.add_attachfile', '#express_table').click( function(e){ $.add_attach(e.target) });
	
	
	// お名前にフォーカス
	document.forms.contact_form.customername.focus();
	
});
