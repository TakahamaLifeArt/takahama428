/**
 * Send mail
 * log
 * 2018-01-31 created
 */
$(function () {
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
		 * 必須項目の検証
		 */
		var minimumNumber = 10; // 申し込み最低枚数

		/**
		 * Check the upload file
		 * Return true if All uploads are completed. false otherwise.
		 */
		if ($('#fileupload-table').data('uploaded') != 1) {
			$.msgbox('アップロードされていないフィルがあります');
			return false;
		}

		/**
		 * 枚数
		 * 複数Inputの内一つ以上の入力が必須
		 * 合計10枚以上
		 * ---
		 * @return {bool} 妥当であれば{@code true}を返す
		 */
		if ($.makeArray($('#fileupload .e-mailer-group input[type="number"]')).reduce(function (prev, curr, index, ary) {
				return prev + (curr.value - 0);
			}, 0) < minimumNumber) {
			$.msgbox('ご注文は' + minimumNumber + '枚以上からとなっております');
			return false;
		}

		/**
		 * 住所
		 * 都道府県と市区町村
		 */
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

		return true;
	});
	
	/**
	 * 送信完了時の処理
	 */
	eMailer.onComplete('#sendmail', function(){
		window.location.href = '/campaign/towel/thanks.php';
	});


	// カレンダー
	$('#datepick').datepickCalendar({
		altField: '#delidate',
		disableBeforeDate: '+1day',
		onSelect: function(dateText){
			let elem = document.querySelector('#delidate');

			if (dateText) {
				elem.classList.remove('is_invalid');
			} else {
				elem.classList.add('is_invalid');
			}
			eMailer.onChanged('#delidate');
		}
	});


	// 郵便番号
	$('#zipcode').on('change', function () {
		if ($(this).val().trim() !== '') {
			$('#addr0, #addr1').removeClass('is_invalid');
		}
	});
});
