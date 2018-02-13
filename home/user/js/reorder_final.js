/**
 * 追加注文リスト
 *
 */
$(function(){
	
	$.extend({
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
		tax: 0,
		orderId: 0,
		orderItem: [],
		user: {},
		deli: {},
		deliTimeName : {
			"0":"指定なし",
			"1":"午前中",
			"3":"14:00-16:00",
			"4":"16:00-18:00",
			"5":"18:00-20:00",
			"6":"19:00-21:00"
		},
		packing: {
			"0":"まとめて包装 ",
			"10":"個別袋を同封 ",
			"50":"個別包装"
		},
		init: function(){
			var items = $.getStorage('reitem'),
				sum = $.getStorage('resum'),
				perone = Math.ceil(sum.total / sum.volume),
				i = 0,
				tr = '',
				td1 = [];
			
			$.deli = $.getStorage('redeli');
			
			// 見積り金額
			$('#total').text(sum.total.toLocaleString('ja-JP'));
			$('#perone').text(perone.toLocaleString('ja-JP'));
			
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
				// 注文メール用の元受注No.
				$.orderId = designId;
				
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
							
							// 注文メール用の商品情報
							$.orderItem.push({
								'name': itemName,
								'color': colorName,
								'size': sizeName,
								'amount': amount
							});
							
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
			
			// 希望納期
			$('#delidate').text($.deli.delidate);
			
			// 配達時間
			$('#delitime').text($.deliTimeName[$.deli.delitime]);
			
			// お届け先
			$('#zipcode').text($.deli.zipcode);
			$('#addr').text($.deli.addr0 + $.deli.addr1);
			
			// 袋詰め
			$('#pack').text($.packing[$.deli.pack]);
			
			// メッセージ
			$('#message').text($.deli.message.replace(/\r|\n|\r\n/g, '<br>'));
			
			// 量販単価適用の表記
			if (sum.volume >= 150) {
				$('#discount_notice').removeClass('hidden');
			} else {
				$('#discount_notice').addClass('hidden');
			}
		}
	});
	
	
	// 追加注文メール送信
	$('#send').on('click', function(){
		var user = {
				'number': $('#customer_name').data('number'),
				'name': $('#customer_name').text(),
				'email': $('#email').text(),
				'tel': $('#tel').text()
			},
			deli = {
				'zipcode': $.deli.zipcode,
				'addr': $.deli.addr0 + $.deli.addr1,
				'delidate': $.deli.delidate,
				'delitime': $.deliTimeName[$.deli.delitime],
				'pack': $.packing[$.deli.pack],
				'message': $.deli.message
			},
			param = {
				'user': user,
				'deli': deli,
				'item': JSON.stringify($.orderItem),
				'orderid': $.orderId
			};
		
		$.ajax({
			url: '/user/php_libs/sendReorder.php',
			type: 'get',
			dataType: 'json',
			async: true,
			timeout: 5000,
			data: param
		}).done(function(r){
			if (r.send == 'success') {
				window.location.href = './thanks.php';
			} else {
				$.msgbox('Error: メールの送信ができませんでした');
			}

		});
	});
	
	
	// initialize
	$.init();
	
});