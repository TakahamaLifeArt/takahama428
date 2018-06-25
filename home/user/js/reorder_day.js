/**
 * 追加注文リスト
 *
 */
$(function(){
	
	$.extend({
		sum: {},
		tax: 0,
		pack: 0,
		delidate: '',
		delitime: 0,
		setStorage: function (key, data) {
			/**
			 * 追加注文データを登録更新
			 * @param {string} key データオブジェクトのキー(redesign, reitem, resum, redeli)のいずれか
			 * @param {object} data 登録データの連想配列（一部でも可）
			 * @return {object} 登録後のデータの連装配列
			 */
			var sess = sessionStorage,
				keys = {
					'redesign': '追加デザイン',
					'reitem': '追加アイテム',
					'resum': '追加注文見積り',
					'redeli': '追加注文のお届け先'
				}
			if (keys.hasOwnProperty(key)===false) {
				$.msgbox('登録する項目が見つかりませんでした。');
				return;
			}
			if (data && Object.keys(data).length>0) {
				sess.setItem(key, JSON.stringify(data));
			} else {
				$.msgbox('項目[ '+keys[key]+' ] のデータが不正です。');
			}
			return data;
		},
		getStorage: function (key) {
			/**
			 *	追加注文データを取得
			 *	@param {string} key 取得するデータのキーを指定、未指定（偽となる0,"",null,undefined,false）で全てのデータ
			 *	@return 全データの場合は {key:[], key:[], ...}
			 * 			キー指定の場合は対応する値の連装配列
			 *			キーまたはデータが存在しない場合はnull
			 *			{}の場合は{@code null}を返す
			 */
			var sess = sessionStorage,
				store = {},
				keyCode;

			if (!key) {
				for (keyCode in sess) {
					if (sess.hasOwnProperty(keyCode)) {
						store[keyCode] = JSON.parse(sess.getItem(keyCode));
					}
				}
			} else {
				store = JSON.parse(sess.getItem(key));
				if (Object.keys(Object(store)).length===0) {
					store = null;
				}
			}

			return store;
		},
		init: function(){
			var i = 0,
				items = $.getStorage('reitem'),
				tr = '',
				td1 = [],
				perone = 0;
			
			// 初期化
			sessionStorage.removeItem('redeli');
			$.sum = $.getStorage('resum');
			$.sum.pack = 0;
			$.sum.express = 0;
			
			// 追加商品のリスト生成
			/*
			 * item: {デザインID: {アイテムID: {
			 *								code:アイテムコード,
			 *								name:アイテム名,
			 *								posId:絵型ID,
			 *								cateId:カテゴリID,
			 *								rangeId:枚数レンジID
			 *								screenId:同版分類ID
			 * 								color: [{
			 *										master:マスターID,
			 *										vol: {サイズ名: {amount:枚数, cost:単価, id:サイズID}, ...},
			 *										code: カラーコード,
			 *										name: カラー名
			 *										}, {}]
			 * 								},
			 *					  アイテムID: {}
			 *					 }
			 *		 }
			 */
			Object.keys(items).forEach(function(designId){
				Object.keys(items[designId]).forEach(function (itemId) {
					var row1 = 0,
						td2 = [],
						colorCount = this[itemId]['color'].length,
						itemName = this[itemId]['name'],
						itemCode = this[itemId]['code'];

					for (i=0; i<colorCount; i++) {
						var t = 0,
							row2 = 0,
							td3 = [],
							colorName = this[itemId]['color'][i]['name'];
						Object.keys(this[itemId]['color'][i]['vol']).forEach(function (sizeName) {
							var amount = this[sizeName]['amount'];
							td3.push('<td>'+sizeName+'</td><td>'+amount+'枚</td>');
							row2++;
						}, this[itemId]['color'][i]['vol']);

						td2.push('<td rowspan="'+row2+'">'+colorName+'</td>' + td3[0]);
						for (t=1; t<row2; t++) {
							td2.push(td3[t]);
						}

						row1 += row2;
					}

					td1.push('<td rowspan="'+row1+'">'+itemCode+'<br>'+itemName+'</td>' + td2[0]);
					for (i=1; i<td2.length; i++) {
						td1.push(td2[i]);
					}

				}, items[designId]);
			});
			for (i=0; i<td1.length; i++) {
				tr += '<tr>'+td1[i]+'</tr>';
			}
			$('#order_item').append(tr);
			
			// 日付表示の初期化
			$('.deli_date span').text('-');
			
			// 量販単価適用の表記
			if ($.sum.volume >= 150) {
				$('#discount_notice').removeClass('hidden');
			} else {
				$('#discount_notice').addClass('hidden');
			}

			// 見積り金額
			$.api(['taxes'], 'GET', function (r) {
				$.tax = r/100; 
				$.estimate();
				perone = Math.ceil($.sum.total / $.sum.volume);
				$('#estimation .total_p span').text($.sum.total.toLocaleString('ja-JP'));
				$('#estimation .solo_p span').text(perone.toLocaleString('ja-JP'));
			});
		},
		estimate: function(){
			var transport = $('#transport').is(':checked')? 2: 1,
				subTotal = $.sum.item + $.sum.print + $.sum.pack,
				perone = 0,
				expressRatio = 0,
				expressInfo = '',
				expressError = '',
				d = [];
			
			$.delidate = $('#datepick').datepickCalendar('getDate');
			if($.delidate){
				// ISO-8601書式でtimestamp
				timestamp = Date.parse($.delidate+"T00:00:00+09:00") / 1000;	// 日付のみの場合UTCタイムゾーンとなるため(ES5)
				$.api(['deliveries', timestamp], 'GET', function(workday){
					// 袋詰め作業で1日必要かどうか
					if($.pack==50 && $.sum.volume>9){
						workday--;
					}

					// 配達日数
					workday -= (transport-1);

					if (workday<1) {
						expressError = '製作日数が足りません！';
					} else if(workday==1) {
						// 当日仕上げは対応しないため
						expressError = '製作日数が足りません！';
					}

					switch(workday) {
						case 1:	expressRatio = 10;
							expressInfo = '当日仕上げ';
							break;
						case 2:	expressRatio = 5;
							expressInfo = '翌日仕上げ';
							break;
						case 3:	expressRatio = 3;
							expressInfo = '２日仕上げ';
							break;
					}

					// 特急の場合の注釈
					if (expressInfo!='' && expressError=='') {
						$('#express_info').removeClass('hidden').children('em').text(expressInfo);
					} else {
						$('#express_info').addClass('hidden');
					}
					
					// 製作日数不足の場合
					if (expressError!=='') {
						// メッセージ
						$.msgbox(expressError);

						// 納期表示の初期化
						$('.deli_date span').text('-');
						$.delidate = '';
						
						// 特急料金
						$.sum.express = 0;
					} else {
						// 納期表示
						d = $.delidate.split('-');
						$('.deli_date span').each(function(idx){
							$(this).text(d[(idx+1)]);
						});
						
						// 特急料金
						$.sum.express = Math.ceil((subTotal*expressRatio)/10);
					}
					
					subTotal += $.sum.express;
					$.sum.carriage = subTotal<30000 && subTotal>0 ? 700 : 0;
					subTotal += $.sum.carriage;
					$.sum.tax = Math.floor(subTotal * $.tax);
					$.sum.total = Math.floor(subTotal * (1+$.tax));
					perone = Math.ceil($.sum.total / $.sum.volume);
					$('#estimation .total_p span').text($.sum.total.toLocaleString('ja-JP'));
					$('#estimation .solo_p span').text(perone.toLocaleString('ja-JP'));
				});
			} else {
				// 日付表示の初期化
				$('.deli_date span').text('-');
				
				$.sum.express = 0;
				$.sum.carriage = subTotal<30000 && subTotal>0 ? 700 : 0;
				subTotal += $.sum.carriage;
				$.sum.tax = Math.floor(subTotal * $.tax);
				$.sum.total = Math.floor(subTotal * (1+$.tax));
				perone = Math.ceil($.sum.total / $.sum.volume);
				$('#estimation .total_p span').text($.sum.total.toLocaleString('ja-JP'));
				$('#estimation .solo_p span').text(perone.toLocaleString('ja-JP'));
			}
			
			$.sum = $.setStorage('resum', $.sum);
		}
	});
	
	
	// 袋詰め
	$('#pack input[name="pack"]').on('change', function(){
		$.pack = $(this).val();
		$.sum.pack = $.pack * $.sum.volume;
		$.estimate();
	});
	
	
	// 配送日数が２日以上かかる場合
	$('#transport').on('click', function(){
		$.estimate();
	});
	
	
	// お届け日指定
	$('#datepick').datepickCalendar({
		disableBeforeDate: '+1day',
		onSelect: function(dateText){
			var data = {'delidate': dateText};
			var d = dateText.split('-');
			$('.deli_date span').each(function(idx){
				$(this).text(d[(idx+1)]);
			});
			$.estimate();
		}
	});
	
	
	// お届け時間指定
	$('#deliverytime').on('change', function(){
		$.delitime = $(this).val();
	})
	
	
	$('#confirmation').on('click', function(){
		var orderId = $(this).data('orderId'),
			zipcode = $('#zipcode').text(),
			addr0 = $('#addr0').text(),
			addr1 = $('#addr1').text(),
			message = $('#message').val(),
			defZipcode = '',
			defAddr0 = '',
			defAddr1 = '';
		
		// お届け先を別に指定
		if ($('#collapseExample').hasClass('show')) {
			defZipcode = $('#deli_zipcode').val().trim();
			defAddr0 = $('#deli_addr0').val().trim();
			defAddr1 = $('#deli_addr1').val() + $('#deli_addr2').val().trim();
			
			if (defZipcode=='' && defAddr0=='' && defAddr1=='') {
				// 未記入の場合は現住所を使用
			} else if (defZipcode=='' || defAddr0=='' || defAddr1=='') {
				$.msgbox('お届け先の変更内容を確認ください');
				return;
			} else {
				// 別のお届け先を適用
				zipcode = defZipcode;
				addr0 = defAddr0;
				addr1 = defAddr1;
			}
		}
		
		// 見積り
		$.setStorage('resum', $.sum);
		
		// お届け先
		$.setStorage('redeli', {'zipcode':zipcode, 'addr0':addr0, 'addr1':addr1, 'message':message, 'pack':$.pack, 'delidate':$.delidate, 'delitime':$.delitime});
		
		// 確認ページへ
		window.location.href = './reorder_final.php?oi='+orderId;
	});
	
	
	// initialize
	$.init();
	
});