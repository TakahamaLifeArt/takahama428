/**
 * 追加注文フォーム
 *
 */
$(function(){
	
	$.extend({
		tax: 0,
		orderId: $('#add_list').data('orderId'),
		orderAmount: 0,
		items: {},
		designs: {},
		setStorage: function (key, data) {
			/**
			 * 追加注文データを登録更新
			 * @param {string} key 以下のいずれか。データオブジェクトのキー
			 *					redesign
			 *					reitem
			 *					resum
			 * @param {object} data 登録データの連想配列（一部でも可）
			 * @return {object} 登録後のデータの連装配列
			 */
			var sess = sessionStorage,
				keys = {
					'redesign': '追加デザイン',
					'reitem': '追加アイテム',
					'resum': '追加注文見積り'
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
		init: function(){
			// 消費税
			$.api(['taxes'], 'GET', function (r) { $.tax = r/100; });
			
			// 初期化
			sessionStorage.removeItem('redesign');
			sessionStorage.removeItem('reitem');
			sessionStorage.removeItem('resum');
			$.items[$.orderId] = {};
			$.designs[$.orderId] = {};
			
			// アイテム情報
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
			$('#reorder_item tbody').each(function(){
				var self = $(this),
					addButton = self.find('.add_order_item'),
					colorCode = addButton.data('colorcode'),
					colorName = self.find('.color_code').text(),
					itemId = addButton.data('itemid'),
					item = self.find('.item_name_color'),
					masterId = item.data('master');
				
				if ($.items[$.orderId].hasOwnProperty(itemId)) {
					$.items[$.orderId][itemId]['color'].push({'master':masterId, 'vol':{}, 'code':colorCode, 'name':colorName});
				} else {
					$.items[$.orderId][itemId] = {
						'code' : self.find('.item_code').text().toLowerCase(),
						'name' : self.find('.item_name').text(),
						'posId' : item.data('pos'),
						'cateId' : item.data('category'),
						'rangeId' : item.data('range'),
						'screenId' : item.data('screen'),
						'color' : [{'master':masterId, 'vol':{}, 'code':colorCode, 'name':colorName}]
					}
				}
			});
			
			// プリント情報
			/**
			 * design: {
			 *			デザインID(id_インデックス): {絵型ID: {
			 *												front|back|side 絵型面: {
			 *																		表示要素のインデックス: {
			 *																			area:箇所名, 
			 *																			size:0|1|2 大中小, 
			 *																			option:0|1 インクジェット(淡|濃)と刺繍(origin|name)のオプション, 
			 *																			method:プリント方法, 
			 *																			printable:対応しているプリント方法
			 *																			ink:色数
			 *																		},
			 *																		表示要素のインデックス: {}
			 *												},
			 *												front|back|side 絵型面: {}
			 *										},
			 *										絵型ID: {}
			 *			}
			 *	}
			 */
			$('.print_info_final tbody .tabl_txt').each(function(){
				var idx = 0,
					self = $(this),
					pos = self.data('prnPos'),
					face = self.data('prnFace'),
					area = self.find('.p_posi').text(),
					ink = self.find('.ink').text();
					
				if (!$.designs[$.orderId].hasOwnProperty(pos)) {
					$.designs[$.orderId][pos] = {};
					$.designs[$.orderId][pos][face] = {0: {}};
					idx = 0;
				} else {
					$.designs[$.orderId][pos][face] = {1: {}};
					idx = 1;
				}
				$.designs[$.orderId][pos][face][idx] = {
					'area' : area,
					'size' : self.data('prnSize'),
					'option' : self.data('prnOption'),
					'method' : self.data('prnMethod'),
					'printable' : [],
					'ink' : ink
				}
			});
		},
		calc: function () {
			/**
			 * 見積もり計算
			 */
			var printFee = 0,
				subTotal = 0;
				item = {};
			
			// 注文商品の枚数
			$.orderAmount = 0;
			
			// カラー情報を初期化
			Object.keys($.items[$.orderId]).forEach(function (itemId) {
				this[itemId]['color'] = [];
			}, $.items[$.orderId]);
			
			// サイズ毎の枚数指定
			$('#reorder_item tbody').each(function(){
				var body = $(this),
					addButton = body.children('tr:last').find('.add_order_item'),
					itemId = addButton.data('itemid'),
					colorCode = addButton.data('colorcode'),
					colorName = body.find('.color_code').text(),
					masterId = body.find('.item_name_color').data('master'),
					sizeData = {};
				
				// 指定内容を取得
				$(this).children('tr:gt(0):not(:last)').each(function(){
					var tr = $(this),
						sizeId = tr.data('size'),
						sizeName = tr.children('td:eq(0)').text(),
						cost = tr.children('td:eq(1)').children('span').text() - 0,
						amount = tr.children('td:eq(2)').children('span').text() - 0;
					
					sizeData[sizeName] = {'id':sizeId, 'amount':amount, 'cost':cost};
					$.orderAmount += amount;
				});
				
				// 再設定
				if (Object.keys(sizeData).length>0) {
					$.items[$.orderId][itemId]['color'].push({'master':masterId, 'vol':sizeData, 'code':colorCode, 'name':colorName});
				}
			});
			
			// 見積り計算
			$.when(
				// 量販単価適用の判定
				$.setMassCost($.items, $.orderAmount)
			).then(function(items){
				// 商品代
				item = $.itemPrice(items);
				
				// プリント代計算
				return $.printCharge({'item':$.items, 'design':$.designs});
			}).then(function(print){
				var rank = 0,
					rankFee = 0,
					carriage = 0,
					base = 0,
					tax = 0,
					estimation = 0,
					perone = 0;
				
				// プリント方法ごとのプリント代を集計
				Object.keys(print.price).forEach(function(method){
					printFee += this[method]-0;
				}, print.price);
				
				// プリント代
				$('#print_fee').text(printFee.toLocaleString('ja-JP'));
				
				// 小計
				subTotal = printFee + item.price;
				$('#item_amount').text(item.amount.toLocaleString('ja-JP'));
				$('#item_fee').text(subTotal.toLocaleString('ja-JP'));
				
				// 会員割
				if ($('#rank_fee').length===1) {
					rank = $('#rank_fee').data('rank') - 0;
					rankFee = -1 * Math.ceil((subTotal * rank)/100);
					$('#rank_fee').text(rankFee.toLocaleString('ja-JP'));
				}
				
				// 送料
				carriage = subTotal<30000 && subTotal>0 ? 700 : 0;
				$('#carriage').text(carriage.toLocaleString('ja-JP'));
				
				// 計
				base = subTotal + rankFee + carriage;
				$('#base').text(base.toLocaleString('ja-JP'));
				
				// 消費税
				tax = Math.floor(base * $.tax);
				$('#tax').text(tax.toLocaleString('ja-JP'));
				
				// 見積り合計
				estimation = Math.floor(base * (1+$.tax));
				$('#estimation').text(estimation.toLocaleString('ja-JP'));
				
				// １枚あたり
				perone = Math.ceil(estimation / $.orderAmount);
				$('#perone').text(perone.toLocaleString('ja-JP'));
				
				// 量販単価適用の表記
				if ($.orderAmount >= 150) {
					$('.subtotal .note').removeClass('hidden');
				} else {
					$('.subtotal .note').addClass('hidden');
				}
				
				// Storageを更新
				$.setStorage('reitem', $.items);
				$.setStorage('redesign', $.designs);
				$.setStorage('resum', {'item':item.price, 'print':printFee, 'carriage':carriage, 'pack':0, 'express':0, 'volume':item.amount, 'tax':tax, 'total':estimation});
			});
		},
		setMassCost: function (items, amount) {
			/**
			 * 非同期
			 * 量販単価の適用判断とstorageの単価更新
			 * @param items 商品情報の連装配列 {デザインID:商品情報, ...}
			 * @param amount 注文枚数
			 * @return Promise object
			 */
			var d = $.Deferred(),
				p = $.Deferred().resolve().promise();

			Object.keys(items).forEach(function(designId){
				Object.keys(items[designId]).forEach(function (itemId) {
					var i = 0,
						len = this[itemId]['color'].length;
					for (i=0; i<len; i++) {
						p = p.then(function(idx, colors){
							return $.api(['items', itemId, 'costs', colors[idx]['code']], 'GET', function(r){
								var costOf = {};
								r.forEach(function(obj){
									costOf[obj.name] = obj.cost;
								});
								Object.keys(colors[idx]['vol']).forEach(function (sizeName) {
									this[sizeName]['cost'] = costOf[sizeName];
								}, colors[idx]['vol']);
							}, amount);
						}.bind(null, i, this[itemId]['color']));
					}
				}, items[designId]);
			});
			p.then(function(){ d.resolve(items); });
			return d.promise();
		},
		itemPrice: function (items, targetDesignId, targetItemId) {
			/**
			 * 同期
			 * 商品単価
			 * @param items				注文商品情報の連装配列 {デザインID:商品情報, ...}
			 * @param targetDesignId	デザインID、nullで指定なし
			 * @param targetItemId		アイテムID、nullで指定なし
			 * @return {price:金額, amount:枚数}
			 */
			var i = 0,
				cost = 0,
				totAmount = 0,
				totPrice = 0;

			if (!items || Object.keys(items).length==0) return {'price':0, 'amount':0};

			Object.keys(items).forEach(function(designId){
				// デザインID指定がある場合
				if (targetDesignId && targetDesignId!=designId) return;

				Object.keys(items[designId]).forEach(function (itemId) {
					// アイテムID指定がある場合
					if (targetItemId && targetItemId!=itemId) return;

					for (i=0; i<this[itemId]['color'].length; i++) {
						Object.keys(this[itemId]['color'][i]['vol']).forEach(function (sizeName) {
							cost = this[sizeName]['cost']-0;

							// プリントなしは10%UPして１円単位以下を切り上げ
							if (designId == 'id_0' || $('#noprint').prop('checked')) {
								cost = Math.ceil((cost * NO_PRINT_RATE)/10) * 10;
							}

							totAmount += (this[sizeName]['amount']-0);
							totPrice += (this[sizeName]['amount']-0) * cost;
						}, this[itemId]['color'][i]['vol']);
					}
				}, items[designId]);
			});
			return {'price':totPrice, 'amount':totAmount};
		},
		printCharge: function (data, target) {
			/**
			 * 非同期
			 * 各プリント方法の指定条件（area, size, ink, option）毎にプリント代を算出
			 * 計算対象の指定がある場合は、対象のみのプリント代
			 * @param data		注文情報の連装配列 {'design':プリント情報, 'item':商品情報}
			 * @param target	計算対象の[プリント面, 箇所のインデックス]、指定なしは全て
			 * @return {'price':price, 'recommend':recommendPrint}
			 */
			var target = !target? []: Array.isArray(target)? target: [],
				i = 0,
				d = $.Deferred(),
				p = $.Deferred().resolve().promise(),
				designs = data.design,
				items = data.item,
				recommendPrint = [],	// おまかせプリントで適用されたプリント名
				price = {'total':0, 'silk':0, 'digit':0, 'inkjet':0, 'cutting':0, 'embroidery':0, 'recommend':0};	// 各プリント方法のプリント代

			// デザインパターン毎
			Object.keys(designs).forEach(function(designId){
				var recommendType = ['silk', 'digit', 'inkjet'],
					recommendName = {'silk':'シルクスクリーン', 'digit':'デジタルコピー転写', 'inkjet':'インクジェット'},
					repeatSilk = {}, 		// {シルクのプリント条件: {同版分類ID: 0|2, ...}} 0:版代計上する、2:版代計上しない
					printMethod = {			// 各プリント方法のプリント代計算用パラメータ、おまかせプリントは対応プリント方法の配列も設定
						'silk': {},
						'digit': {},
						'inkjet': {},
						'cutting': {},
						'embroidery': {},
						'recommend': {}
					};

				// プリントなしの場合
				if (designId=='id_0' || (designId==0 && $('#noprint').prop('checked'))) {
					//					d.resolve({'price':price, 'recommend':[]});
					//					return d.promise();

					return;
				}

				// 絵型ID毎
				Object.keys(this[designId]).forEach(function (posId) {
					var screenGroup = {}, // {同版分類ID: 0|2} 0:版代計上する、2:版代計上しない
						volumeRange = {}; // {枚数レンジID: {itemId:枚数, ...} }

					// 同じ絵型のアイテムの枚数を枚数レンジ別で集計
					Object.keys(items[designId]).forEach(function (itemId) {
						// 当該絵型を使用しているアイテム
						if (this[itemId]['posId'] == posId) {
							if (!volumeRange.hasOwnProperty(this[itemId]['rangeId'])) {
								// 初めての枚数レンジIDの場合
								volumeRange[this[itemId]['rangeId']] = {};
								volumeRange[this[itemId]['rangeId']][itemId] = 0;
							}

							// 枚数レンジ別のアイテム毎
							for (i=0; i<this[itemId]['color'].length; i++) {
								Object.keys(this[itemId]['color'][i]['vol']).forEach(function (sizeName) {
									volumeRange[items[designId][itemId]['rangeId']][itemId] += this[sizeName]['amount'] - 0;
								}, this[itemId]['color'][i]['vol']);
							}

							// シルクの同版分類チェック
							screenGroup[this[itemId]['screenId']] = 0;
						}
					}, items[designId]);

					// プリント方法、プリント面、箇所名、プリントサイズ、インク数、オプションそれぞれの指定内容別で枚数レンジ毎の枚数を集計
					Object.keys(this[posId]).forEach(function (face) { // プリント面

						// ターゲット指定あり
						if (target.length>0) {
							if (target[0]!=face) return;
						}
						Object.keys(this[face]).forEach(function (attrId) { // プリント箇所

							// ターゲット指定あり
							if (target.length>0) {
								if (target[1]!=attrId) return;
							}

							var method = this[attrId]['method'],
								param = this[attrId]['area'] + '_' + this[attrId]['size'] + '_' + this[attrId]['ink'] + '_' + this[attrId]['option'],
								printable = [];

							/*
							 * おまかせプリントの場合
							 * area : プリント箇所_インク数別
							 * printable: 対応可能なプリント方法
							 */
							if (method==='recommend') {
								param = this[attrId]['area'] + '_0_' + this[attrId]['ink']+'_0';
								for (i=0; i<this[attrId]['printable'].length; i++){
									if (this[attrId]['printable'][i]!=1) continue;
									printable.push(recommendType[i]);
								}
							}

							// 他の絵型で同じ条件指定のプリント方法が既にあるかどうか
							if (printMethod[method].hasOwnProperty(param)) {

								// 既にある場合、当該プリント方法で設定済みの枚数レンジの有無をチェック
								Object.keys(volumeRange).forEach(function (rangeId) {
									if (!printMethod[method][param].hasOwnProperty(rangeId)) {
										// 新しい枚数レンジ
										printMethod[method][param][rangeId] = volumeRange[rangeId];
									} else {
										// 同じ枚数レンジがあればアイテム毎の枚数を追加
										Object.keys(this[rangeId]).forEach(function (itemId) {
											printMethod[method][param][rangeId][itemId] = this[itemId];
										}, this[rangeId]);
									}
								}, volumeRange);

								if (method == 'silk' || method == 'recommend') {
									// 同じ同版分類のアイテムがプリント済みかどうか
									Object.keys(screenGroup).forEach(function (id) {
										if (!repeatSilk[param].hasOwnProperty(id)) {
											repeatSilk[param][id] = screenGroup[id];
										}
									});


								}
								if (method == 'recommend') {
									// おまかせで対応するプリント方法を更新
									printMethod[method]['printable'] = printMethod[method]['printable'].filter(function(val){
										return printable.indexOf(val)!=-1
									});
								}
							} else {
								// 同じ条件指定のプリント方法を初めて指定
								printMethod[method][param] = volumeRange;

								// シルクの場合は同版分類を設定
								if (method == 'silk' || method == 'recommend') {
									repeatSilk[param] = screenGroup;
								}

								// おまかせで対応するプリント方法を設定
								if (method == 'recommend') {
									printMethod[method]['printable'] = printable;
								}
							}
						}, this[face]);
					}, this[posId]);
				}, this[designId]);


				// プリント代計算
				Object.keys(printMethod).forEach(function (method) {
					// プリント指定なし
					if (Object.keys(this[method]).length === 0) return;

					var printable = [];		// おまかせプリントで適用するプリント方法を指定 [silk, digit, inkjet]

					if (method=='recommend') {
						printable = this[method]['printable'];
					}

					/*
					 * 条件指定毎に計算
					 * @cond area_size_ink_option
					 */
					Object.keys(this[method]).forEach(function (cond) {

						if (cond=='printable') return;

						var param = cond.split('_'),// [area, size, ink, option]
							samePlateId,			// シルクの同版分類ID
							rangeCount = Object.keys(this[cond]).length,	// 同じプリント条件の枚数レンジIDの数
							repeatStatus = {};

						for (i=0; i<rangeCount; i++) {
							p = p.then(function(cond, idx, param, printable){
								var rangeId = Object.keys(printMethod[method][cond])[idx],	// 枚数レンジID
									itemids = printMethod[method][cond][rangeId],			// アイテムIDをキーにした枚数の連装配列
									amount = 0;	// 計算対象の合計枚数

								// 計算対象の枚数を計算
								Object.keys(itemids).forEach(function(id){
									amount += itemids[id]-0;
								});

								return $.api(['printcharges', method], 'GET', function(r){
									if (method=='silk') {
										// シルクの場合は同版分類をチェック
										for (samePlateId in r.plates) {
											if (repeatSilk[cond].hasOwnProperty(samePlateId)) {
												repeatSilk[cond][samePlateId] = 2;
											}
										}
									} else if (method=='recommend' && recommendPrint.indexOf(r.method)<0) {
										// おまかせの場合に適用されたプリント名
										recommendPrint.push(recommendName[r.method]);
									}

									// プリント代を合計
									price[method] += r.tot;
								}, JSON.stringify({
									'amount': 0,
									'items': itemids,
									'size': param[1],
									'ink': param[2],
									'option': param[3],
									'repeat': {'silk': repeatSilk[cond], 'digit': 0},
									'printable': printable
								}));
							}.bind(null, cond, i, param, printable));
						}

					}, this[method]);
				}, printMethod);
			}, designs);

			p.then(function(){ d.resolve({'price':price, 'recommend':recommendPrint}); });
			return d.promise();
		}
	});
	
	//
	$('#add_list').on('click', function(){
		if ($.orderAmount==0) {
			$.msgbox('追加する商品をご指定ください');
			return;
		}
		
		// 追加商品情報を更新
		Object.keys($.items[$.orderId]).forEach(function (itemId) {
			if (this[itemId]['color'].length==0) {
				delete $.items[$.orderId][itemId];
			}
		}, $.items[$.orderId]);
		$.setStorage('reitem', $.items);
		
		window.location.href = './reorder_day.php?oi='+$.orderId;
	});
	
	// initialize
	$.init();
	
	// IE
	var userAgent = window.navigator.userAgent.toLowerCase();
	if (userAgent.indexOf('msie') != -1 || userAgent.indexOf('trident') != -1) {
		$.msgbox('ただいま、ご使用のブラウザではサービスのご利用が出来ませんので、お問い合わせをご利用くださいませ。<br><a href="/contact/">お問い合わせはこちら</a>');
	}
});