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
			var base = Date.parse(curDate) / 1000,
				transport = $('#destination option:selected').data('destination'),
				param = {
				'basesec': base,
				'workday': [4],
				'transport': transport,
				'extraday': 0
			};
			$.api(['delivery'], 'GET', function (r) {
				var d = r[0]['Year'] + "-" + r[0]['Month'] + "-" + r[0]['Day'];
				$('#datepicker_deliday').val(d);
			}, param);
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
