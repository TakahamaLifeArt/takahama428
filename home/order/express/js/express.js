/*
*	Takahama Life Art
*	an urgent order
*/

	
$(function(){
	    
	jQuery.extend({
		sendmail_check:function(f){
			if(f.customername.value.trim()==""){
				$.msgbox("お名前を入力してください。");
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
			if(f.ruby.value.trim()==""){
				$.msgbox("フリガナを入力してください。");
				return false;
			}

			if(!$.check_email(f.email.value)){
				return false;
			}
			
			f.submit();
		},
		add_attach:function(my){
			var new_row = '<tr><th>&nbsp;</th><td>&nbsp;</td>';
			new_row += '<td><input type="file" name="attachfile[]" /><ins class="abort">取消</ins></td></tr>';
			$(my).closest('tr').before(new_row);
		},
		getDeliverydays: function(base, target_id){
			base = Date.parse( base )/1000;	
			var transport = 1;
			if( $('#transport').is(':checked') ){
				transport = 2;
			}
			var postData = {'act':'deliverydays', 'base':base, 'transport':transport };
			$.ajax({ url:'/../../php_libs/orders.php', type:'post', dataType:'json', async:true, data:postData, 
				success:function(r){
					for(var i=1; i<4; i++){
			    		var targetSec = Date.parse(r[i]);
			    		var dt = new Date();
			    		dt.setTime(targetSec);
			    		$('#'+target_id[i]+' .dt:eq(0) p').text( dt.getMonth() + 1 );
			    		$('#'+target_id[i]+' .dt:eq(1) p').text( dt.getDate() );
			    	}
				} 
			});
		},
		init: function(){
			var dt = new Date();
			var curdate = dt.getFullYear() + "/" + (dt.getMonth() + 1) + "/" + dt.getDate();
			$.getDeliverydays(curdate, ["","result_date2","result_date3","result_date4"]);
/*			document.forms.express_form.customername.focus();*/
		}
	});
	
	
	/* calendar */
	$("#datepicker").datepicker({
		beforeShowDay: function(date){
			var weeks = date.getDay();
			var texts = "";
			if(weeks == 0) texts = "休日";
			var YY = date.getFullYear();
			var MM = date.getMonth() + 1;
			var DD = date.getDate();
			var currDate = YY + "/" + MM + "/" + DD;
			var datesec = Date.parse(currDate)/1000;
			if(!$.TLA.holidayInfo[YY+"_"+MM]){
				$.TLA.holidayInfo[YY+"_"+MM] = new Array();
				$.ajax({url: '/php_libs/checkHoliday.php',
						type: 'GET',
						dataType: 'text',
						data: {'datesec':datesec},
						async: false,
						success: function(r){
							if(r!=""){
								var info = r.split(',');
								for(var i=0; i<info.length; i++){
									$.TLA.holidayInfo[YY+"_"+MM][info[i]] = info[i];
								}
							}
						}
					   });
			}
			if($.TLA.holidayInfo[YY+"_"+MM][DD] && weeks!=6) weeks = 0;
			if(weeks == 0) return [true, 'days_red', texts];
			else if(weeks == 6) return [true, 'days_blue'];
			return [true];
		}
	});
	
	
	// 添付ファイルの追加
	$('.add_attachfile', '#express_table').click( function(e){ $.add_attach(e.target) });
	
	
	// 追加した添付ファイルを削除
	$('#express_table').on('click', '.abort', function(){
    	$(this).closest('tr').remove();
    });
	
	
	// メール送信
	$('#sendbtn').click( function(){
		$.sendmail_check(document.forms.express_form);
	});
	
	
	$.init();
	
});
