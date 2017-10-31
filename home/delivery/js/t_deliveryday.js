/*
 *	Takahama Life Art
 *	deliveryday module
 */


$(function () {

	jQuery.extend({
		todayIs: "",
//		init: function(){
//			var dt = new Date();
//			$.todayIs = Date.parse(dt.getFullYear() + "/" + (dt.getMonth() + 1) + "/" + dt.getDate()) / 1000;
//		},
		calc_delivery: function () {
			/*
			 *	注文確定日からお届け日を計算
			 */
//			$('.cal_date').hide();
			$('#datepicker_deliday').val('');
			var curDate = $("#datepicker_firmorder").datepicker('getDate');
			if (!curDate) return;
			base = Date.parse(curDate) / 1000;
			var transport = $('#destination option:selected').data('destination');
			//			if( $('#transport').is(':checked') ){
			//				transport = 2;
			//			}
			var postData = {
				'act': 'deliverydays',
				'base': base,
				'transport': transport,
				'mode': 'simple'
			};
			$.ajax({
				url: '/php_libs/orders.php',
				type: 'post',
				dataType: 'json',
				async: true,
				data: postData,
				success: function (r) {
					// r[通常納期, 2日仕上げ, 翌日仕上げ, 当日仕上げ, 注文確定日]
//					var $target = $('#cal_date_r ul');
//					var dt = new Date();
//					for (var i = 0, t = 3; i < 4; i++, t--) {
//						var targetSec = Date.parse(r[t]);
//						dt.setTime(targetSec);
//						$target.children('li:eq(' + i + ')').find('.mm').text(dt.getMonth() + 1);
//						$target.children('li:eq(' + i + ')').find('.dd').text(dt.getDate());
//					}
//					$('#cal_date_r .heading4 span').text(r[4]);
					dt = new Date(r[0]);
					var d = dt.getFullYear() + "-" + ("00" + (dt.getMonth() + 1)).slice(-2) + "-" + ("00" + dt.getDate()).slice(-2);
					$('#datepicker_deliday').val(d);
//					$('#arrow img').attr('src', 'img/b3.png');
//					$('#cal_date_r').show();
				}
			});
		}
	});

	/* calendar */
	$("#datepicker_firmorder").datepicker({
		beforeShowDay: function(date){
			var isEnable = true;
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
				$.ajax({
					url: '/php_libs/checkHoliday.php',
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
//			if (datesec < $.todayIs) isEnable = false;
			if($.TLA.holidayInfo[YY+"_"+MM][DD] && weeks!=6) weeks = 0;
			if(weeks == 0) return [isEnable, 'days_red', texts];
			else if(weeks == 6) return [isEnable, 'days_blue'];
			return [isEnable];
		},
		onSelect: function(dateText, inst){
			$.calc_delivery();
		},
		onClose: function(dateText, inst){
			var yy, mm, dd;
			if(dateText.match(/^(\d{4})[\/-]([01]?\d{1})[\/-]([0123]?\d{1})$/)){
				var res = dateText.split(/[\/-]/);
				yy = res[0]-0;
				mm = res[1]-0;
				dd = res[2]-0;
			}
			var dt = new Date(yy, mm-1, dd);
			if(!(yy==dt.getFullYear() && mm-1==dt.getMonth() && dd==dt.getDate())){
				$('#datepicker_deliday').val('');
				$(this).datepicker('setDate', "");
			}
		}
	});


	/* 日付を指定して納期確認
	$('#btnFirmorder').click(function () {
		if ($("#datepicker_firmorder").datepicker('getDate') == "") {
			alert('注文確定日を指定して下さい');
		} else {
			$.calc_delivery();
		}
	});
	*/

	$('#destination').change(function () {
		if ($("#datepicker_firmorder").datepicker('getDate') != "") {
			$.calc_delivery();
		}
	});

//	$.init();
});
