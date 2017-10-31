/*
*	Takahama Life Art
*	
*	post form module
*	charset euc-jp
*	log:	2014-03-14 created
*
*/
	
$(function(){

	jQuery.extend({
		sendmail_check:function(my){
			var f = my.form;
			if(f.message.value.trim()==""){
				$.msgbox("�����Ȥ����Ϥ��Ƥ���������");
				return false;
			}
			
			if(f.mode.value=="confirm"){
				f.mode.value = 'send';
				my.value = '������';
				$('input[type="text"], :radio[name=repeater], textarea, .txt, label', f).addClass('confirmation');
				$('input[name="subtitle[]"]:checked').each( function(){
					var txt = $(this).closest('td').children('.txt');
					var chk = txt.html()==""? $(this).val(): ',��'+$(this).val();
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
				$('input[type="reset"]').hide();
				$('.title_confirmation, .msg, #goback').show();
			}else if(f.mode.value=="send"){
				f.submit();
			}
		},
		add_attach:function(id){
			var new_row = '<tr><th>��Ƽ̿�</th><td>&nbsp;</td>';
			new_row += '<td><input type="file" name="attachfile[]" /><ins class="abort">�߼��</ins></td></tr>';
			$('#'+id+' tbody tr:last').before(new_row);
		}	
	});
	
	
	// �ɲä���ź�եե��������
	$('.abort').on('click', function(){
    	$(this).closest('tr').remove();
    });
	
	
	// ��ǧ���̤����ܥ���
	$('#goback').click( function(){
		var f = $(this).closest('form').get(0);
		f.mode.value = 'confirm';
		$('#sendmail').val('�������Ƥγ�ǧ');
		$('input[type="text"], :radio[name=repeater], textarea, .txt, label', f).removeClass('confirmation');
		$('.txt', f).html('');
		$('input[type="reset"]').show();
		$('.title_confirmation, .msg, #goback').hide();
	});
	
	
	// �����Ȥ˥ե�������
	document.forms.form1.message.focus();
	$.scrollto($('body'));
	
});
