/**
 * Order form
 * log
 * 2017-10-21	Created
 * 2018-05-16	 イメ画選択、後払い、納期選択の仕様変更
 */
$(function(){
	const NO_PRINT_RATE = 1.1;	// プリントなしで商品単価を10％UPし1円単位以下を切り上げる
	
	// SessionStorage initialize
	if (('sessionStorage' in window) && (window.sessionStorage !== null)) {
		// available
	} else {
		alert("ご使用のブラウザのSessionStorageを有効にしてください。");
	}

	// polyfill
	if (!Array.prototype.reduce) {
		Array.prototype.reduce = function reduce(accumulator){
			if (this===null || this===undefined) throw new TypeError("Object is null or undefined");

			var i = 0, l = this.length >> 0, curr;

			if(typeof accumulator !== "function") // ES5 : "If IsCallable(callbackfn) is false, throw a TypeError exception."
				throw new TypeError("First argument is not callable");

			if(arguments.length < 2) {
				if (l === 0) throw new TypeError("Array length is 0 and no second argument");
				curr = this[0];
				i = 1; // start accumulating at the second element
			}
			else
				curr = arguments[1];

			while (i < l) {
				if(i in this) curr = accumulator.call(undefined, curr, this[i], i, this);
				++i;
			}

			return curr;
		};
	}

	if (!Function.prototype.bind) {
		Function.prototype.bind = function (oThis) {
			if (typeof this !== "function") {
				// closest thing possible to the ECMAScript 5
				// internal IsCallable function
				throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
			}

			var aArgs = Array.prototype.slice.call(arguments, 1), 
				fToBind = this, 
				fNOP = function () {},
				fBound = function () {
					return fToBind.apply(this instanceof fNOP && oThis
										 ? this
										 : oThis,
										 aArgs.concat(Array.prototype.slice.call(arguments)));
				};

			fNOP.prototype = this.prototype;
			fBound.prototype = new fNOP();

			return fBound;
		};
	}


	/**
	 * カートに入れる前の選択中の商品データ
	 * categories 商品カテゴリーのマスターデータ
	 * curr
	 *		designId デザインID
	 *		posId	 絵型ID
	 *		itemId	 アイテムID
	 *		design	 プリント情報
	 *		cateogry カテゴリー情報
	 *		item	 アイテム情報
	 *		isModifyCart カートの変更
	 *----------
	 * カート内の注文データ
	 * clearStorage 初期化
	 * removeStorage 削除
	 * getStorage 取得
	 * setStorage 登録
	 * mergeStorage カートのデータと選択中データをマージ
	 */
	$.extend({
		tax: 0,		// 消費税率
		carriage: 700,
		categories: {},	// {カテゴリID: {id, code, name}, ...}
		curr: {
			designId: '',
			posId: 0,
			itemId: 0,
			design: {},
			category: {},
			item: {}
		},
		clearStorage: function () {
			/**
			 * カートの全データを初期化
			 */
			sessionStorage.clear();
		},
		removeStorage: function(key, data){
			/**
			 * カートのデータを削除
			 * @param {string} key データオブジェクトのキー
			 * @param {object} data 
			 *					design {designId: {絵型ID: {絵型面: {'area': 箇所名}}}}
			 *					item {designId: {itemId: {'color': {'name':カラー名, 'code':カラーコード}}}}
			 *					attach, option, sum, user, detail は全削除の上、データ指定があれば指定内容を設定
			 * @return {object} 削除後のデータの連装配列
			 */
			var i = 0,
				sess = sessionStorage,
				store = $.getStorage(key),
				isExist = false,
				sub = {},
				currDesignId = 0,
				currItemId = 0,
				currPosId = 0;

			switch (key) {
				case 'design':
					if (!data) return null;
					currDesignId = Object.keys(data)[0];
					if (Object.keys(data[currDesignId]).length==0) {
						// プリント指定がない場合は当該デザインパターンを全削除
						delete store[currDesignId];
					}
					break;
				case 'item':
					if (!data) return null;
					currDesignId = Object.keys(data)[0];
					currItemId = Object.keys(data[currDesignId])[0];

					if (Object.keys(data[currDesignId][currItemId]).length==0) {
						currPosId = store[currDesignId][currItemId]['posId'];

						// カラー指定がない場合は当該アイテムを全削除
						delete store[currDesignId][currItemId];
					} else {
						// カラーを削除
						for (let i in store[currDesignId][currItemId]['color']) {
							if (store[currDesignId][currItemId]['color'][i]['name']==data[currDesignId][currItemId]['color']['name']) {
								delete store[currDesignId][currItemId]['color'][i];
								break;
							}
						}

						// 全てのカラーが削除された場合は当該アイテムを全削除
						if (Object.keys(store[currDesignId][currItemId]['color']).length==0) {
							currPosId = store[currDesignId][currItemId]['posId'];
							delete store[currDesignId][currItemId];
						}
					}

					if (Object.keys(store[currDesignId]).length==0) {
						// 当該デザインパターンに対応するアイテムが無い場合
						delete store[currDesignId];
						sub[currDesignId] = {};
						$.removeStorage('design', sub);
					} else if (currPosId!=0) {
						// 当該アイテムが全て削除された場合
						Object.keys(store[currDesignId]).forEach(function(itemId){
							if (this[itemId]['posId']==currPosId) {
								isExist = true;
								return;
							}
						}, store[currDesignId]);

						/// 当該デザインパターン内に同じ絵型が使用されていなければプリント情報も削除
						if (isExist===false) {
							sub = $.getStorage('design');
							delete sub[currDesignId][currPosId];
							sess.setItem('design', JSON.stringify(sub));
						}
					}
					break;
				case 'attach':
				case 'option':
				case 'sum':
				case 'user':
				case 'detail':
					store = !data? {}: data;
					break;
			}

			// update storage
			sess.setItem(key, JSON.stringify(store));

			return store;
		},
		getStorage: function (key) {
			/**
			 *	カートのデータを取得
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
		setStorage: function (key, data) {
			/**
			 * カートのデータを登録更新
			 * @param {string} key 以下のいずれか。データオブジェクトのキー
			 *					design
			 *					item
			 *					attach
			 *					option
			 *					sum
			 *					user
			 *					detail
			 * @param {object} data 登録データの連想配列（一部でも可）
			 * @return {object} 登録後のデータの連装配列
			 */
			var sess = sessionStorage,
				keys = {
					'design': 'デザイン',
					'item': 'アイテム',
					'attach': '入稿データ',
					'option': 'オプション',
					'sum': '見積金額',
					'user': '顧客情報',
					'detail': '見積詳細'
				}
			if (keys.hasOwnProperty(key)===false) {
				$.msgbox('登録する項目が見つかりませんでした。');
				return;
			}
			if (data && Object.keys(data).length>0) {
				data = $.mergeStorage(key, data);
				sess.setItem(key, JSON.stringify(data));
			} else {
				$.msgbox('項目[ '+keys[key]+' ] のデータが不正です。');
			}
			return data;
		},
		mergeStorage: function(key, data){
			/**
			 * カートのデータに現在撰択中のデータを結合したオブジェクトを返す
			 * designとitem以外は個別のデータ項目毎に更新可
			 * @param {string} key 以下のいずれか。データオブジェクトのキー
			 *					design
			 *					item
			 *					attach
			 *					option
			 *					sum
			 *					user
			 *					detail
			 * @param {object} data 登録データの連想配列
			 * @param {bool} update true(default)はカートを更新する、falseはカートを更新しない。
			 * @return {object} 結合されたオブジェクトを返す
			 *					{@code data}が空の場合は{@code null}を返す
			 */
			var i = 0,
				sess = sessionStorage,
				store = $.getStorage(key),
				currDesignId = 0,
				currItemId = 0,
				currPosId = 0,
				isExist = false,
				isExistColor = false;

			if (!data) return null;
			if (store !== null) {
				switch (key) {
					case 'design':
						// 当該デザインの有無（選択中の絵型が対象）
						currDesignId = Object.keys(data)[0];
						Object.keys(store).forEach(function(designId){
							if (currDesignId!=designId) return;

							// 同じ絵型IDの有無
							currPosId = Object.keys(data[designId])[0];
							if (!currPosId) {
								isExist = true;
								return;
							}
							Object.keys(store[designId]).forEach(function(posId){
								if (currPosId!=posId) return;

								// 同じ絵型IDの商品がカートある場合、絵型面毎の指定内容を更新
								this[posId] = data[designId][posId];
								isExist = true;
								return;
							}, store[designId]);

							// 当該デザインで未登録の絵型
							if (isExist===false) {
								store[designId][currPosId] = data[designId][currPosId];
								isExist = true;
							}
							return;
						});

						// 未登録のデザイン
						if (isExist===false) {
							store[currDesignId] = data[currDesignId];
						}
						break;
					case 'item':
						// 当該デザインの有無（選択中の絵型が対象）
						currDesignId = Object.keys(data)[0];
						Object.keys(store).forEach(function(designId){
							if (currDesignId!=designId) return;

							// 同じアイテムIDの有無
							currItemId = Object.keys(data[designId])[0];
							if (!currItemId) {
								isExist = true;
								return;
							}
							Object.keys(store[designId]).forEach(function(itemId){
								if (currItemId!=itemId) return;

								// 同じアイテムIDの商品がカートにある場合、カラー指定とサイズ毎の枚数指定を上書き
								this[itemId]['color'] = data[designId][itemId]['color'];

								isExist = true;
								return;
							}, store[designId]);

							// 当該デザインで未登録のアイテム
							if (isExist===false && currItemId) {
								store[designId][currItemId] = data[designId][currItemId];
								isExist = true;
							}
							return;
						});

						// 未登録のデザイン
						if (isExist===false) {
							store[currDesignId] = data[currDesignId];
						}
						break;
					case 'attach':
					case 'option':
					case 'sum':
					case 'user':
					case 'detail':
						Object.keys(data).forEach(function(k){
							store[k] = data[k];
						});
				}
			} else {
				store = data;
			}

			return store;
		}
	});


	/**
	 * 商品データ、プリント代、見積もり計算
	 * getStar			レビューの星マーク算出
	 * resetPrice		プリント箇所の金額表示を初期化
	 * showPrintPrice	プリント箇所のプリント代計算結果をタグに表示
	 * setMassCost		量販単価の適用判断とstorageの単価更新
	 * itemPrice		商品代と枚数を算出
	 * printCharge		プリント代を算出
	 * estimate			見積もり計算
	 * -----
	 * extend method
	 * inkjetNotice		インクジェットの場合の表記
	 */
	$.extend({
		getStar: function (args) {
			var r = '';
			if (args < 0.5) {
				r = 'star00';
			} else if (args >= 0.5 && args < 1) {
				r = 'star05';
			} else if (args >= 1 && args < 1.5) {
				r = 'star10';
			} else if (args >= 1.5 && args < 2) {
				r = 'star15';
			} else if (args >= 2 && args < 2.5) {
				r = 'star20';
			} else if (args >= 2.5 && args < 3) {
				r = 'star25';
			} else if (args >= 3 && args < 3.5) {
				r = 'star30';
			} else if (args >= 3.5 && args < 4) {
				r = 'star35';
			} else if (args >= 4 && args < 4.5) {
				r = 'star40';
			} else if (args >= 4.5 && args < 5) {
				r = 'star45';
			} else {
				r = 'star50';
			}
			return r;
		},
		resetPrintPrice: function(pane) {
			/**
			 * プリント指定の金額表示を初期化
			 * @param pane 計算結果を表示する親要素
			 */
			pane.find('.price_box .total_p span').text('0');
			pane.find('.price_box .solo_p span').text('0');
			pane.find('.price_box_2 .print_re span').text('');
		},
		showPrintPrice: function(pane, obj) {
			/**
			 * 同期
			 * プリント指定で当該プリント箇所の見積額を表示
			 * @param pane				計算結果を表示する親要素
			 * @param obj	{price: プリント代 {silk, digit, inkjet, cutting, emb} }
			 *				{recommend: おまかせプリントの場合のプリント名の配列 }
			 * @param arguments[2]	追加金額（送料など）
			 */
			var orderItem = $.itemPrice($.curr.item, $.curr.designId, $.curr.itemId),
				price = orderItem.price-0,
				amount = orderItem.amount-0,
				perone = 0;

			$.resetPrintPrice(pane);
			Object.keys(obj.price).forEach(function(method){
				price += this[method]-0;
			}, obj.price);
			if (arguments.length > 2 && price > 0 && price < 30000) {
				price += arguments[2] - 0;
			}
			price = Math.floor(price * (1 + $.tax));
			perone = amount==0? 0: Math.ceil(price / amount);
			pane.find('.price_box .total_p span').text(price.toLocaleString('ja-JP'));
			pane.find('.price_box .solo_p span').text(perone.toLocaleString('ja-JP'));
			if (obj.recommend.length>0){
				var n = 0;
				pane.find('.price_box_2').each(function(){
					if ($(this).is('.hidden')) return true;	// continue
					$(this).find('.print_re span').text(obj.recommend[n++]);
				});
//				pane.find('.price_box_2 .print_re span').text(obj.recommend.join('、'));
			}
			
			// インクジェットの濃淡色の違いによりプリント代が違う旨を表示
			if ($.inkjetNotice(obj)) {
				pane.find('.price_box .inkjet_notice, .price_box_2 .inkjet_notice').prop('hidden', false);
			} else {
				pane.find('.price_box .inkjet_notice, .price_box_2 .inkjet_notice').prop('hidden', true);
			}
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
					var i = 0;
					for (i in this[itemId]['color']) {
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

					for (i in this[itemId]['color']) {
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
				price = {'total':0, 'silk':0, 'digit':0, 'inkjet':0, 'cutting':0, 'emb':0, 'recommend':0};	// 各プリント方法のプリント代

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
						'emb': {},
						'recommend': {'printable':['silk', 'digit', 'inkjet']}
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
						volumeRange = {}; // {枚数レンジID: {itemId:{カラー名:枚数, ...} }

					// 同じ絵型のアイテムの枚数を枚数レンジ別で集計
					Object.keys(items[designId]).forEach(function (itemId) {
						// 当該絵型を使用しているアイテム
						if (this[itemId]['posId'] == posId) {
							if (!volumeRange.hasOwnProperty(this[itemId]['rangeId'])) {
								// 初めての枚数レンジIDの場合
								volumeRange[this[itemId]['rangeId']] = {};
								volumeRange[this[itemId]['rangeId']][itemId] ={};
							}

							// 枚数レンジ別のアイテム毎
							for (let i in this[itemId]['color']) {
								let colorName = this[itemId]['color'][i]['name'];
								volumeRange[items[designId][itemId]['rangeId']][itemId][colorName] = 0;
								Object.keys(this[itemId]['color'][i]['vol']).forEach(function (sizeName) {
									volumeRange[items[designId][itemId]['rangeId']][itemId][colorName] += this[sizeName]['amount'] - 0;
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
								
								// 対応可能なプリント名
								for (i=0; i<this[attrId]['printable'].length; i++){
									if (this[attrId]['printable'][i]!=1) continue;
									printable.push(recommendType[i]);
								}
								
								// 同じ条件の全てのアイテムに共通で対応できるプリント方法のみを設定
								printMethod[method]['printable'] = printMethod[method]['printable'].filter(function(val){
									return printable.indexOf(val)!=-1
								});
							}

							// 他の絵型で同じ条件指定のプリント方法が既にあるかどうか
							if (printMethod[method].hasOwnProperty(param)) {

								// 既にある場合、当該プリント方法で設定済みの枚数レンジの有無をチェック
								Object.keys(volumeRange).forEach(function (rangeId) {
									
									// 新しい枚数レンジの場合
									if (!printMethod[method][param].hasOwnProperty(rangeId)) {
										printMethod[method][param][rangeId] = {};
									}
									
									// 枚数を追加
									Object.keys(this[rangeId]).forEach(function (itemId) {
										printMethod[method][param][rangeId][itemId] = this[itemId];
									}, this[rangeId]);
								}, volumeRange);

								if (method == 'silk' || method == 'recommend') {
									// 同じ同版分類のアイテムがプリント済みかどうか
									Object.keys(screenGroup).forEach(function (id) {
										if (!repeatSilk[param].hasOwnProperty(id)) {
											repeatSilk[param][id] = screenGroup[id];
										}
									});
								}
							} else {
								// 同じ条件指定のプリント方法を初めて指定
								printMethod[method][param] = {};
								Object.keys(volumeRange).forEach(function (rangeId) {
									printMethod[method][param][rangeId] = {};
									Object.keys(this[rangeId]).forEach(function (itemId) {
										printMethod[method][param][rangeId][itemId] = this[itemId];
									}, this[rangeId]);
								}, volumeRange);

								// シルクの場合は同版分類を設定
								if (method == 'silk' || method == 'recommend') {
									repeatSilk[param] = {};
									Object.keys(screenGroup).forEach(function (screenId) {
										repeatSilk[param][screenId] = screenGroup[screenId];
									});
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
						printable = this[method]['printable'].concat();
					}

					/*
					 * 条件指定毎に計算
					 * @cond area_size_ink_option
					 */
					Object.keys(this[method]).forEach(function (cond) {

						if (cond=='printable') return;

						var param = cond.split('_');	// [area, size, ink, option]

						// 枚数レンジ毎
						Object.keys(this[cond]).forEach(function(rangeId){
							
							// this[cond]をthisとしてbind
							p = p.then(function(cond){
								var jsonData = JSON.stringify({
									'amount': 0,
									'items': this[rangeId],	// アイテムIDをキーにしたカラー毎の枚数の連装配列
									'size': param[1],
									'ink': param[2],
									'option': param[3],
									'repeat': {'silk':repeatSilk[cond], 'digit':0, 'emb':0},
									'printable': printable
								});
								
								return $.api(['printcharges', method], 'GET', function(r){
									if (method=='silk') {
										// シルクの場合は同版分類IDをチェック
										Object.keys(r.plates).forEach(function(samePlateId){
											if (repeatSilk[cond].hasOwnProperty(samePlateId)) {
												repeatSilk[cond][samePlateId] = 2;
											}
										});
									} else if (method=='recommend' && recommendPrint.indexOf(r.method)<0) {
										// おまかせの場合に適用されたプリント名
										recommendPrint.push(recommendName[r.method]);
									}

									// プリント代を合計
									price[method] += r.tot;
								}, jsonData);
							}.bind(this, cond));
							
						}, this[cond]);
					}, this[method]);
				}, printMethod);
			}, designs);

			p.then(function(){ d.resolve({'price':price, 'recommend':recommendPrint}); });
			return d.promise();
		},
		estimate: function() {
			/**
			 * 見積もり計算
			 *	p1  商品代＋プリント代＋インク色替代	（インク色変代はWeb適用外）
			 *	p2  割引金額						対象：p1
			 *	p3  値引金額	（Web適用外）
			 *	p4  特急料金（２日仕上げと翌日仕上げ）	対象：p1+p2+p7+p9+p10
			 *	p5  送料							対象：p1+p2+p3+p7+p9+p10+p11
			 *	p6  特別送料（超速便、タイム便）
			 *	p7  デザイン代	（Web適用外）
			 * 	p8  代引き手数料
			 *	p9  袋詰め代
			 *	p10 袋代
			 *	p11 追加料金	（Web適用外）
			 * 	p12 コンビニ手数料	（Web適用外）
			 *----------
			 * option: {publish:割引率, student:割引率, pack:単価, payment:bank|cod|credit|cash|later_payment, delidate:希望納期, delitime:配達時間指定, 
			 *			express:0|1|2, transport:1|2, note_design, note_user, imega:0|1}
			 * detail: {discountfee, discountname, packfee, packname, carriage, codfee, paymentfee, expressfee, expressname, rankname, delitimename}
			 * sum: {item:商品代, print:プリント代, volume:注文枚数, tax:消費税額, total:見積合計, mass:0 通常単価 | 1 量販単価}
			 */
			var d = $.Deferred(),
				opt = $.getStorage('option'),
				sum = $.getStorage('sum'),
				user = $.getStorage('user'),
				items = $.getStorage('item'),
				noPrintItem = $.itemPrice(items, 'id_0'),
				orderAmount = 0,
				subTotal = 0,
				tmpFee = 0,
				discountName = [],
				discountRatio = 0,
				discountStudentRatio = 0,
				discount = 0,
				rankName = {'3':'ブロンズ', '5':'シルバー', '7':'ゴールド'},
				rankFee = 0,
				expressRatio = 0,
				expressFee = 0,
				expressError = '',
				expressInfo = '',
				expressDesc = '',
				packFee = 0,
				paymentFee = 0,
				packName = {0:'まとめて包装', 10:'袋を同封', 50:'個別包装'},
				deliTime = ['', '午前中', '12:00-14:00', '14:00-16:00', '16:00-18:00', '18:00-20:00', '19:00-21:00'],
				timestamp = 0;

			// 特急の注釈を初期化
			$('#express_info').addClass('hidden').children('em').text('');
			$('#express_info .express_ratio').text('');

			if(sum) {
				// 小計
				if (sum.hasOwnProperty('item') && sum.hasOwnProperty('print')) {
					subTotal = sum.item + sum.print;
				}
				
				// 注文枚数
				orderAmount = sum.volume;
				
				// 袋詰め代
				packFee = opt.pack * sum.volume;
			} else {
				sum = {};
			}
			
			// プリントなしは割引不可のためアイテム代を除外する
			subTotal -= noPrintItem.price;

			if (subTotal > 0) {
				// 学割、ただし学校名の入力があること
				if (opt.hasOwnProperty('school') && opt.school.trim()!=='' && opt.student!=0) {
					discountStudentRatio = opt.student-0;
					discountRatio += discountStudentRatio;
					discountName.push('学割');
				}

				// 写真掲載割
				if (opt.publish!=0) {
					discountRatio += opt.publish-0;
					discountName.push('写真掲載割');
				}

				// 顧客ランク割
				if (user.rank!=0) {
					rankFee = -1 * Math.ceil((subTotal * user.rank)/100);
					discountName.push(rankName[user.rank]+'会員割');
				}
			}

			// 割引合計額
			discount = -1 * Math.ceil((subTotal * discountRatio)/100) + (rankFee);

			// 納期指定あり
			if(opt.delidate){
				// ISO-8601書式でtimestamp
				timestamp = Date.parse(opt.delidate+"T00:00:00+09:00") / 1000;	// 日付のみの場合UTCタイムゾーンとなるため(ES5)
				$.api(['deliveries', timestamp], 'GET', function(workday) {
					// 袋詰め作業で1日必要かどうか
					if (opt.pack==50 && orderAmount>9) {
						workday--;
					}

					// イメ画作成に要する３営業日
					if (opt.imega==1) {
						workday -= 3;
					}

					// 配達日数
					workday -= (opt.transport-1);

					if (workday<1) {
						expressError = '製作日数が足りません！';
					} else if(workday==1) {
						// 当日仕上げは対応しないため
						expressError = '製作日数が足りません！';
					} else if (opt.pack==50 && orderAmount>9 && workday<=3) {
						// 袋詰めありの場合は特急不可のため
						expressError = '製作日数が足りません！';
					} else if (opt.imega==1 && workday<=3) {
						// イメ画作成ありの場合は特急不可のため
						// 2018-07-03 特急可に仕様変更
//						expressError = '製作日数が足りません！';
					}

					if (expressError==='') {
						switch(workday) {
							case 1:	expressRatio = 10;
								expressInfo = '当日仕上げ';
								expressDesc = '通常料金 x 2倍';
								break;
							case 2:	expressRatio = 5;
								expressInfo = '翌日仕上げ';
								expressDesc = '通常料金 x 1.5倍';
								break;
							case 3:	expressRatio = 3;
								expressInfo = '２日仕上げ';
								expressDesc = '通常料金 x 1.3倍';
								break;
						}

						// 特急料金適用
						if (expressRatio>0) {

							// 特急の場合は学割不可のため割引を再計算（2018-01-31 併用可）
	//						if (discountStudentRatio>0) {
	//							discount = -1 * Math.ceil((subTotal * (discountRatio-discountStudentRatio))/100) + (rankFee);
	//						}

							// 特急料金の計算対象項目はアイテム代、プリント代、割引、袋詰め代、インク色替え代（Web未使用）
							tmpFee = (subTotal + discount + packFee + noPrintItem.price);
							expressFee = Math.ceil((tmpFee * expressRatio) / 10);

							// 注釈
							$('#express_info').removeClass('hidden').children('em').text(expressInfo);
							$('#express_info .express_ratio').text(expressDesc);
						}

					} else {
						// 製作日数不足の場合
						$.msgbox(expressError);

						// 日付表示の初期化と登録更新
						$('#datepick').datepickCalendar('setDate', '');
						$('#delivery .deli_date span').text('-');
						opt.delidate = '';
						opt = $.setStorage('option', opt);
					}
				}).then(function(){
					setOption();
				});
			} else {
				setOption();
			}

			// オプション指定の処理
			function setOption() {
				var detail = $.getStorage('detail'),
					total = 0,
					salesTax = 0,
					perone = 0,
					carriage = 0,
					codFee = opt.payment=='cod' ? 800 : 0,
					paymentFee = opt.payment=='later_payment' ? 300 : 0;

				// 割引、袋詰め、プリントなしアイテム代を合算
				subTotal += (discount + packFee + noPrintItem.price);

				// 送料は30000以上で無料
				carriage = subTotal<30000 && subTotal>0 ? 700 : 0;

				// 見積もり詳細を更新
				detail.discountfee = discount;
				detail.discountname = discountName.join(', ');
				detail.packfee = packFee;
				detail.packname = packName[opt.pack];
				detail.carriage = carriage;
				detail.codfee = codFee;
				detail.paymentFee = paymentFee;
				detail.expressfee = expressFee;
				detail.expressname = expressInfo;
//				detail.rankfee = rankFee;
				detail.rankname = rankName[user.rank];
				detail.delitimename = deliTime[opt.delitime]
				detail = $.setStorage('detail', detail);

				// 見積もり合計を表示
				subTotal += (expressFee + carriage + codFee + paymentFee);
				salesTax = Math.floor(subTotal * $.tax);	// 消費税額
				total = Math.floor(subTotal * (1+$.tax));	// 見積もり総額（税込）
				perone = Math.ceil(total / orderAmount);
				$('#estimation .total_p span').text(total.toLocaleString('ja-JP'));
				$('#estimation .solo_p span').text(perone.toLocaleString('ja-JP'));

				// 見積もり合計を更新
				sum.total = total;
				sum.tax = salesTax;
				sum.volume = orderAmount;
				sum = $.setStorage('sum', sum);

				// ヘッダーのメニューを更新
				$('#cart_total').text(sum.total.toLocaleString('ja-JP'));
				$('#cart_amount').text(orderAmount.toLocaleString('ja-JP'));

				d.resolve();
			}

			return d.promise();
		},
		inkjetNotice: function (obj) {
			/**
			 * インクジェットの場合にメッセージの表示切り替え
			 * 呼び出し元
			 * $.showPrintPrice()
			 * order.js -> setCart()
			 * @param {obj} $.printChargeの返り値
			 * @return {bool} インクジェットを使用している場合に{@code true}を返す
			 */
			return (obj.price.inkjet > 0 || obj.recommend.indexOf('インクジェット') > -1);
		},
	});


	/**
	 * ユーザー情報
	 * isLogin			ログイン状態を確認
	 * login			ログイン
	 * getSalesVolume	売上高を取得
	 * isExistEmail		メールアドレスの登録状況を確認
	 * validEmail		メールアドレスの妥当性チェック
	 * confirmUser		メールアドレスの登録状況と必須項目の入力確認
	 * showUserConfirmation	初めての方の顧客情報の確認ページ表示
	 * mbConvert		全角の英数記号を半角に変換
	 * isPhone			電話番号の検証
	 */
	$.extend({
		isLogin: function () {
			return $.ajax({
				url: 'user_login.php',
				type: 'post',
				data: {
					'getcustomer': 'true'
				},
				dataType: 'json',
				async: true,
				timeout: 5000
			}).done(function (me) {
				var data;
				if (me.id) {
					// ログインしている場合は確認ページを更新
					$('#conf_email span').text(me.email);
					$('#conf_customername span').text(me.customername);
					$('#conf_customerruby span').text(me.customerruby);
					$('#conf_tel span').text(me.tel);
					$('#conf_zipcode span').text(me.zipcode);
					$('#conf_addr0 span').text(me.addr0);
					$('#conf_addr1 span').text(me.addr1);
					$('#conf_addr2 span').text(me.addr2);

					$.getSalesVolume(me.id).then(function(u){
						var rank = 0,	// 会員割引の割引率
							sales = 0;
						if (u.length>0) sales = u[0]['total_price'];
						if (sales>300000) {
							rank = 7;
						} else if(sales>150000) {
							rank = 5;
						} else if(sales>80000) {
							rank = 3;
						}
						data = {
							'id':me.id,
							'rank':rank,
							'email':me.email,
							'name':me.customername,
							'ruby':me.customerruby,
							'tel':me.tel,
							'zipcode':me.zipcode,
							'addr0':me.addr0,
							'addr1':me.addr1,
							'addr2':me.addr2
						}
						$.setStorage('user', data);
					});
				} else {
					$.removeStorage('user', {'id':0, 'rank':0});
				}
			}).fail(function (xhr, status, error) {
				$.removeStorage('user', {'id':0, 'rank':0});
			});
		},
		login: function(){
			var email = $.mbConvert($('#login_email').val().trim()),
				pass = $.mbConvert($('#login_pass').val().trim());

			//ログイン処理
			$.ajax({
				url: 'user_login.php',
				type: 'get',
				dataType: 'json',
				async: true,
				timeout: 5000,
				data: {
					'login': 'true',
					'email': email,
					'pass': pass
				}
			}).done(function (me) {
				var sum,
					data;
				if (me.length != 0) {
					if (typeof me.id == 'undefined') {
						$.removeStorage('user', {'id':0, 'rank':0});
						$.msgbox(me.error, 'ログインできませんでした');
					} else {
						// ヘッダーのメニューを更新
						sum = $.getStorage('sum');
						$('#signin_state').text('ログアウト');
						$('#signin_name').text(me.customername + ' 様');
						$('#mypage_button').text('マイページ');
						$('#signout').prop('hidden', false);
						$('#cart_total').text((sum.total).toLocaleString('ja-JP'));
						$('#cart_amount').text((sum.volume).toLocaleString('ja-JP'));

						// 顧客情報の確認ページへ
						$('#conf_email span').text(me.email);
						$('#conf_customername span').text(me.customername);
						$('#conf_customerruby span').text(me.customerruby);
						$('#conf_tel span').text(me.tel);
						$('#conf_zipcode span').text(me.zipcode);
						$('#conf_addr0 span').text(me.addr0);
						$('#conf_addr1 span').text(me.addr1);
						$('#conf_addr2 span').text(me.addr2);

						$.getSalesVolume(me.id).then(function(u){
							var rank = 0,	// 会員割引の割引率
								sales = 0;
							if (u.length>0) sales = u[0]['total_price'];
							if (sales>300000) {
								rank = 7;
							} else if(sales>150000) {
								rank = 5;
							} else if(sales>80000) {
								rank = 3;
							}
							data = {
								'id':me.id,
								'rank':rank,
								'email':me.email,
								'name':me.customername,
								'ruby':me.customerruby,
								'tel':me.tel,
								'zipcode':me.zipcode,
								'addr0':me.addr0,
								'addr1':me.addr1,
								'addr2':me.addr2
							}
							$.setStorage('user', data);
						});
						
						$.next();
					}
				}
			}).fail(function (xhr, status, error) {
				$.removeStorage('user', {'id':0, 'rank':0});
				$.msgbox("Error: " + error + "<br>会員情報を取得できませんでした。");
			});
		},
		getSalesVolume: function(id) {
			/**
			 * ユーザーの売上高
			 * @param id ユーザーID
			 * @return 顧客情報の配列
			 */
			return $.api(['users', id, 'sales'], 'GET', null);
		},
		isExistEmail: function(email) {
			/**
			 * メールアドレスの登録状況
			 * @param email
			 * @return {bool} 登録があれば{@code true}
			 */
			return $.api(['users', email], 'GET', null);
		},
		validEmail: function(email){
			/**
			 * メールアドレスの妥当性チェック
			 * @param email
			 * @return {bool} 妥当があれば{@code true}
			 */

			var res = false;
			
			if(email.trim()=="" || !email.match(/@/)){
				$.msgbox('メールアドレスではありません。');
				return false;
			}

			/*	RFC2822 addr_spec 準拠パターン							*/
			/*	atom       = {[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+};		*/
			/*  dot_atom   = {$atom(?:\.$atom)*};						*/
			/*  quoted     = {"(?:\\[^\r\n]|[^\\"])*"};					*/
			/*  local      = {(?:$dot_atom|$quoted)};					*/
			/*  domain_lit = {\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\]};		*/
			/*  domain     = {(?:$dot_atom|$domain_lit)};				*/
			/*  addr_spec  = {$local\@$domain};							*/
			$.ajax({
				url:'/php_libs/checkDNS.php', async:false, type:'POST', dataType:'text', timeout:5000, data:{'email': email}
			}).done(function(r){
				if(r){
					if( email.match(/^(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+))*)|(?:"(?:\\[^\r\n]|[^\\"])*")))\@(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+))*)|(?:\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\])))$/)){
						//$.msgbox('OK!\n確認メールを送信してください。');
						res = true;
					}else{
						$.msgbox('メールアドレスを確認してください。');
					}
				}else{
					$.msgbox('@マークより後を確認してください。');
				}
			}).fail(function(xhr, status, error){
				alert("Error: "+error+"<br>通信エラーです。");
			});
			return res;

		},
		confirmUser: function () {
			/**
			 * メールアドレスの登録状況と必須項目の入力確認
			 * @return promise object
			 */
			var d = $.Deferred(),
				email = $.mbConvert($('#email').val().trim()),
				pass = $.mbConvert($('#pass').val().trim()),
				pass_conf = $.mbConvert($('#pass_conf').val().trim()),
				tel = $.mbConvert($('#tel').val().trim()),
				zipcode = $('#zipcode').val();

			if (!$.validEmail(email)) {
				return d.reject().promise();
			}

			$.isExistEmail(email).then(function(r){
				var msg = '',
					required = [],
					required_list = '';
				
				if (r === true) {
					msg += '<h1>登録済みのメールアドレスです！</h1>';
					msg += '<p>E-mail：　' + email + '</p>';
					msg += '<p>ログインしてください。</p>';
					
					$.dialogBox(msg, '', 'OK', 'Cancel').then(
						function(){
							// 会員ログインフォーム表示
							$('#customer .first_time').addClass('hidden');
							$('#customer .member').removeClass('hidden');
							$('#customer .member input').val('');
							$.setScrollTop();
						},
						function(){
							// 何もしない
						}
					);
					d.reject();
				} else {
					// 必須項目チェック
					if (pass == '') {
						required.push('<li>パスワード</li>');
					} else if (pass.match(/\W/)) {
						required.push('<li>パスワードは半角英数</li>');
					}
					if (pass_conf == '' || pass!=pass_conf) {
						required.push('<li>パスワード確認</li>');
					} else if (pass.match(/\W/)) {
						required.push('<li>パスワード確認は半角英数</li>');
					}
					if ($('#customername').val().trim() == '') required.push('<li>お名前</li>');
					if ($('#customerruby').val().trim() == '') required.push('<li>フリガナ</li>');
					if (!$.isPhone(tel)) required.push('<li>お電話番号</li>');
					if ($('#addr1').val().trim() == '') required.push('<li>ご住所</li>');
					required_list = '<ul class="msg">' + required.toString().replace(/,/g, '') + '</ul>';
					if (required.length > 0) {
						$.msgbox("必須項目の入力をご確認ください。<hr>" + required_list);
						d.reject();
					} else {
						$('#email').val(email);
						$('#pass').val(pass);
						$('#tel').val(tel.replace(/[−.*━.*‐.*―.*－.*\-.*ー.*\-]/gi,'-'));
						$('#zipcode').val($.zip_mask(zipcode));
						d.resolve();
					}
				}
			});
			
			return d.promise();
		},
		showUserConfirmation: function(page){
			/**
			 * 初めての方の顧客情報の確認ページ
			 * @param {int} page 現在のページから何ページ先かを指定する
			 */
			$('#email, #pass, #customername, #customerruby, #tel, #zipcode, #addr0, #addr1, #addr2').each(function () {
				var self = $(this),
					val = self.val(),
					id = self.attr('id');
				$('#conf_'+id+' span').text(self.val());
			});

			// ページ遷移
			$.next(page);
		},
		mbConvert: function (args){
			/**
			 * 全角の英数記号を半角に変換
			 * 文字コードを65248(0xFEE0)シフト
			 * @param {string} args 返還する文字列
			 * @return 半角に返還した文字列
			 */
			var res = args.replace(/[！-～]/g, function(s) {
				return String.fromCharCode(s.charCodeAt(0)-0xFEE0);
			});
			return res.replace(/”/g, "\"")
				.replace(/’/g, "'")
				.replace(/‘/g, "`")
				.replace(/￥/g, "\\")
				.replace(/　/g, " ")
				.replace(/〜/g, "~");
		},
		isPhone: function(args) {
			/**
			 * 電話番号の検証
			 * 半角数字、ハイフンなしの桁数（10桁か11桁）
			 * @param args 検証する電話番号
			 * @return {bool} true:妥当、false:不正
			 */
			var tel = $.mbConvert(args),
				tel = tel.replace(/[−.*━.*‐.*―.*－.*\-.*ー.*\-]/gi,'-'),
				tel = tel.replace(/[-]/gi,'');
			if (!tel.match(/^(0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/)) {
				return false;
			} else {
				return true;
			}
		}
	});


	/**
	 * PageTransition
	 * initPageTransition ページング要素の設定とクラスのインスタンス生成
	 * prev 前のページへ
	 * next 次のページへ
	 * setScrollTop ページトップへスクロール
	 */
	$.extend({
		orderFlow: {},
		initPageTransition: function (wrapper, child) {
			var $main = $(wrapper);
			var $child = $main.children(child);
			$.orderFlow = new PageTransitions($main, $child);
			$.orderFlow.init();
		},
		prev: function (page) {
			/**
			 * 前のページに戻る
			 * @param {int} page 現在位置から戻るページ数、指定なしは１
			 *					０は先頭ページ
			 */
			var page = String(page).replace(/[^\d]/g, ''),
				page = page || 1,
				prevPage = page==0? 0: $.orderFlow.current - (page-0),
				args = {
					"animation": 2,
					"showPage": prevPage
				};
			$.orderFlow.nextPage(args);
			$.setScrollTop(0);
		},
		next: function (page) {
			/**
			 * 次のページに進む
			 * @param {int} page 現在位置から進むページ数、指定なしは１
			 */
			var page = String(page).replace(/[^\d]/g, ''),
				page = page || 1,
				nextPage = $.orderFlow.current + (page-0),
				args = {
					"animation": 1,
					"showPage": nextPage
				};
			$.orderFlow.nextPage(args);
			$.setScrollTop(0);
		},
		setScrollTop: function(offset){
			/**
			 * ページスクロール
			 * @param {int} offset ページトップからのピクセル値、０でページトップ
			 */
			var target = $('body')[0].scrollTop!=0? 'body' : 'html';
			$(target)[0].scrollTop = offset;
		}
	});


	/**
	 * クエリストリングの処理
	 * queryString.parse		連想配列に変換
	 * queryString.stringify	連想配列をクエリストリングに変換
	 */
	$.extend({
		queryString : {
			parse: function(text, sep, eq, isDecode) {
			/**
			 * @param {string} text クエリストリング、未指定は現在のクエリストリング
			 * @param {string} sep {@code &} それ以外も可
			 * @param {string} eq {@code =} それ以外も可
			 * @param {bool} isDecode URIエンコードの有無
			 * @return {object}
			 */
				var decode = (isDecode) ? decodeURIComponent : function(a) { return a; };
			 	text = text || location.search.substr(1);
				sep = sep || '&';
				eq = eq || '=';

				if (!text) return {};

				return text.split(sep).reduce(function(obj, v) {
					var pair = v.split(eq);
					
					obj[pair[0]] = decode(pair[1]);
					return obj;
				}, {});
			},
			stringify: function(value, sep, eq, isEncode) {
			/**
			 * @param {array} value 連想配列
			 * @param {string} sep {@code &} それ以外も可
			 * @param {string} eq {@code =} それ以外も可
			 * @param {bool} isDecode URIエンコードの有無
			 * @return {string} query string
			 */
				var encode = (isEncode) ? encodeURIComponent : function(a) { return a; };
				sep = sep || '&';
				eq = eq || '=';
				
				return Object.keys(value).map(function(key) {
					return key + eq + encode(value[key]);
				}).join(sep);
			}
		}
	});
})