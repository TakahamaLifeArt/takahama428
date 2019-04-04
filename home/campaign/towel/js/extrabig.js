/**
 * Send mail
 * log
 * 2018-01-31 created
 * 2019-03-14 アップロード機能を実装
 * 2019-04-01 HTML5 validation
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
		 * 郵便番号からの自動入力を反映
		 */
		eMailer.onChanged('#addr0');
		eMailer.onChanged('#addr1');
		
		/**
		 * アップロードファイル
		 */
		let dlToken = JSON.parse(sessionStorage.getItem('dl_token')),
			downURL = document.getElementById('deownload_link'),
			store = $.getSessStorage('attach'),
			fileName = '';

		if (Object.keys(Object(store)).length > 0) {
			$.each(store, function(upid, fName){
				fileName += fName + "\r\n";		// form submission value
			});
		}
		document.getElementById('filename').value = fileName;
		eMailer.onChanged('#filename');

		downURL.value = '';
		if (Object.keys(Object(dlToken)).length > 0) {
			downURL.value = 'https://www.alesteq.com/itemmanager/files/uploader/' + dlToken[0];
		}
		eMailer.onChanged('#deownload_link');

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


	/**
	 * submit
	 */
	$('#send_button').on('click', function() {
		
		// HTML5 validation の前に必須項目を全て検証
		let isInvalid = false;
		$('.e-mailer :input:visible[required="required"]').removeClass('placeholder_error');
		$('.e-mailer :input:visible[required="required"]').each(function () {
			if (!this.validity.valid) {
				$(this).focus();
				$(this).addClass('placeholder_error');
				//				$(this).attr("placeholder", this.validationMessage).addClass('placeholder_error');
				//				$(this).val('');
				isInvalid = true;
				//				return false;
			}
		});
		if (isInvalid) {
			$.msgbox("必須項目の入力を、ご確認ください。");
			return;
		}
		
		$('#sendmail').click();
	});

	// Edgeのみ進捗バーの表示を切り替える
	if (window.navigator.userAgent.toLowerCase().indexOf('edge') !== -1) {
		$('#file-uploader .progress').css('position', 'relative').append('<div class="edge_progress">アップロード中...</div>')
	}
});
