/*
 *	Takahama Life Art
 *	deliveryday module
 */


$(function () {

	jQuery.extend({
		todayIs: "",
		calc_delivery: function () {
			/*
			 *	注文確定日からお届け日を計算
			 */
			$('#datepicker_deliday').val('');
			var curDate = $('#datepicker_firmorder').datepickCalendar('getDate');
			if (!curDate) return;
			base = Date.parse(curDate) / 1000;
			var transport = $('#destination option:selected').data('destination');
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
					dt = new Date(r[0]);
					var d = dt.getFullYear() + "-" + ("00" + (dt.getMonth() + 1)).slice(-2) + "-" + ("00" + dt.getDate()).slice(-2);
					$('#datepicker_deliday').val(d);
				}
			});
		}
	});

	/* calendar */
	$('#datepicker_firmorder').datepickCalendar({
		onSelect: function(dateText){
			$.calc_delivery();
		}
	});


	$('#destination').change(function () {
		if ($('#datepicker_firmorder').datepickCalendar('getDate') != "") {
			$.calc_delivery();
		}
	});
});
