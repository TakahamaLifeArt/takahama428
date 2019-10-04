/**
 * Order form
 * log
 * 2017-10-21	Created
 * 2018-05-16	イメ画選択、後払い、納期選択の仕様変更
 * 2019-01-08	アップロード仕様変更
 * 2019-04-05	お届け先住所を追加
 */
$(function () {
	'use strict';
	
	// Tap or Click
	(function($, window) {
		"use strict";

		var RANGE = 5,
			events = ["click", "touchstart", "touchmove", "touchend"],
			handlers = {
				click: function(e) {
					if(e.target === e.currentTarget) {
						e.preventDefault();
					}
				},
				touchstart: function(e) {
					this.jQueryTap.touched = true;
					this.jQueryTap.startX = e.touches[0].pageX;
					this.jQueryTap.startY = e.touches[0].pageY;
				},
				touchmove: function(e) {
					if(!this.jQueryTap.touched) {
						return;
					}

					if(Math.abs(e.touches[0].pageX - this.jQueryTap.startX) > RANGE ||
					   Math.abs(e.touches[0].pageY - this.jQueryTap.startY) > RANGE) {
						this.jQueryTap.touched = false;
					}
				},
				touchend: function(e) {
					if(!this.jQueryTap.touched) {
						return;
					}

					this.jQueryTap.touched = false;
					$.event.dispatch.call(this, $.Event("TAP_EVENT", {
						originalEvent: e,
						target: e.target,
						pageX: e.changedTouches[0].pageX,
						pageY: e.changedTouches[0].pageY
					}));
				}
			};

		$.event.special.TAP_EVENT = "ontouchend" in window? {
			setup: function() {
				var thisObj = this;

				if(!this.jQueryTap) {
					Object.defineProperty(this, "jQueryTap", {value: {}});
				}
				$.each(events, function(i, ev) {
					thisObj.addEventListener(ev, handlers[ev], false);
				});
			},
			teardown: function() {
				var thisObj = this;

				$.each(events, function(i, ev) {
					thisObj.removeEventListener(ev, handlers[ev], false);
				});
			}
		}: {
			bindType: "click",
			delegateType: "click"
		};

		$.fn.TAP_EVENT = function(data, fn) {
			return arguments.length > 0? this.on("TAP_EVENT", null, data, fn): this.trigger("TAP_EVENT");
		};
	})(jQuery, this);
	
	/**
	 * Cart data
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
	 *			},
	 *			デザインID(id_インデックス): {}
	 *	}
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
	 *					 },
	 *		  デザインID: {}
	 *		 }
	 * attach: [ファイル名, ...]
	 * option: {publish:割引率, student:割引率, pack:単価, payment:bank|cod|credit|cash|later_payment, delidate:希望納期, delitime:配達時間指定, transport:1|2, 
	 *			note_design, designkey_text note_user, imega:0|1}
	 * sum: {item:商品代, print:プリント代, volume:注文枚数, tax:消費税額, total:見積合計, mass:0 通常単価 | 1 量販単価}
	 * detail: {discountfee, discountname, packfee, packname, carriage, codfee, paymentfee, expressfee, expressname, rankname, delitimename}
	 * user: {id:, email:, name:, ruby:, zipcode:, addr0:, addr1:, addr2:, tel:, rank:}
	 *----------
	 *
	 * Current selection
	 * design: {デザインID: {Cart dataと同じ}}
	 * designId: デザインID（id_インデックス:カートにあるデザインパターン | ０:新規デザインパターン）
	 *						プリントなしはインデックス０、それ以外は１以上
	 * category: {カテゴリID: {code:カテゴリーコード, name:カテゴリー名}
	 * item: {デザインID: {Cart dataと同じ}}
	 * itemId: アイテムID
	 * posId: プリントポジションID
	 *----------
	 *
	 * Category master
	 * categories: {カテゴリーID: {id, code, name}, ...}
	 */


	/**
	 * new jQuery instance methods
	 * input, textarea, select タグ限定
	 *----------
	 * applyChange オプションの変更を適用する
	 */
	$.fn.extend({
		applyChange: function(callback) {
			/**
			 * 割引、袋詰め、支払い方法、お届け先（配達日数判定用）
			 * name属性にStorageのキー
			 * value属性にStorageの値
			 */
			var tag = this[0].tagName.toLowerCase();
			if (tag!=='input' && tag!=='textarea' && tag!=='select') return this;
			this.on('change', function() {
				var self = $(this),
					data = {},
					type = self.attr('type'),
					name = self.attr('name'),
					val = type!='checkbox'? self.val(): 
							self.prop('checked')? self.val(): 
							name=='transport'? 1: 0;

				data[name] = val;
				$.setStorage('option', data);
				if (Object.prototype.toString.call(callback)==='[object Function]') callback();
				$.estimate();
			});
			return this;
		}
	});


	// 戻るボタン
	$('.step_prev').on("click", function(){
		var self = $(this);
		if (self.is('.cart')) {
			// カート
			$.prev(0);
		} else if ($(this).is('.customer')) {
			// 顧客情報ページ
			$.prev(2);
		} else if ($(this).is('.conf_user')) {
			// 顧客情報の確認ページ
			$.isLogin().then(
				function(me) {
					if (me) {
						$.prev(3);	// ログイン時
					} else {
						$.prev(1);	// 始めての方
					}
				},
				function() {
					$.prev(2);		// ログイン状態が未確認
				}
			);
		} else {
			$.prev(1);
		}
	});
	
	/**
	 * Step1 - 1
	 * カテゴリ選択でアイテム一覧表示
	 * -----
	 * {@code $.curr.cateogry}指定ありで且つ同じカテゴリーを選択した場合は即ページ遷移、違うカテゴリーの場合は{@code $.curr.cateogry}を再設定
	 * {@code $.curr.item}を初期化
	 * {@code $.curr.designId}が未指定の場合は{@code $.curr.design}を初期化
	 * アイテムを生成してページ遷移
	 */
	$('.top3_inner, .other_inner').on("click", function () {
		var len,
			categoryId = $(this).data('categoryId'),
			args = ['categories', categoryId],
			tags,
			currCategory = Object.keys($.curr.category);

		// ソートのセレクターを初期化
		$('#sort').val('popular');
		
		if (currCategory.length !== 0) {
			if (currCategory[0]==categoryId) {
				// ページ遷移
				$.next();
				return;
			}
			$.curr.category = {};
		}
		
		// カテゴリー選択状態を再設定
		$.curr.category = {};
		$.curr.category[categoryId] = {
			"code": $.categories[categoryId]['code'],
			"name": $.categories[categoryId]['name']
		};
		
		// アイテムの選択状態を初期化
		$.curr.item = {};
		$.curr.itemId = 0;
		
		// 新規デザインパターン
		if ($.curr.designId=='') {
			$.curr.designId = '0';
			$.curr.design[$.curr.designId] = {};
		}
		
		if (categoryId == 4) {
			// スポーツウェアのみ例外
			tags = [73];
			args[1] = 0;
		}

		// 絞り込みの条件表示を初期化
		$('#tag').text('');
		
		$.api(args, 'GET', showItem, tags).then(function(){
			// ページ遷移
			$.next();
		});
	});


	// アイテム一覧タグ生成
	var showItem = function (r) {
		var lsTop = '',
			lsOther = '',
			i = 0,
			len = r.length,
			lenTop3 = Math.min(r.length, 3),
			suffix = '',
			code = '',
			folder = '',
			size = '',
			categoryId = Object.keys($.curr.category)[0];

		for (i = 0; i < lenTop3; i++) {
			code = r[i]['item_code'];
			folder = r[i]['category_key'];
			size = r[i]['sizename_from'] + '&#65374;' + r[i]['sizename_to'];

			if ((code.indexOf('p-') == 0 && r[i]['i_color_code'] == "") || code == 'ss-9999' || code == 'ss-9999-96') {
				suffix = '_style_0';
			} else {
				suffix = '_' + r[i]['i_color_code'];
			}
			if (r[i]['sizename_from'] == r[i]['sizename_to']) {
				size = r[i]['sizename_from'];
			}
			
			lsTop += '<div class="listitems col-12 col-sm-4" data-maker-id="' + r[i]['maker_id'] + '" id="itemid_' + r[i]['item_id'] + '_' + r[i]['pos_id'] + '_' + r[i]['range_id'] + '_' + r[i]['screen_id'] + '">';
				lsTop += '<div class="row">';
					lsTop += '<div class="item_img_wrap col-6 col-sm-12">';
						lsTop += '<div class="item_image_s">';
							lsTop += '<img class="rankno" src="../img/index/no'+(i+1)+'.png" width="60" height="34" alt="No'+(i+1)+'">';
							lsTop += '<img class="item_pic" src="' + IMG_PATH + 'items/list/' + folder + '/' + code + '/' + code + suffix + '.jpg" width="90%" height="auto" alt="">';
						lsTop += '</div>';
					lsTop += '</div>';
					lsTop += '<div class="item_info_wrap col-6 col-sm-12">';
						lsTop += '<p class="point_s">' + r[i]['i_caption'] + '</p>';
						lsTop += '<div class="item_name_s">';
							lsTop += '<p class="item_name_kata">' + code.toUpperCase() + '</p>';
							lsTop += '<p class="item_name_name">' + r[i]['item_name'] + '</p>';
							lsTop += '<p class="item_price"><span>' + r[i]['cost'] + '</span>円&#65374;</p>';
						lsTop += '</div>';
						lsTop += '<div class="item_info_cs">';
							lsTop += '<ul>';
								lsTop += '<li class="item_color"><span>' + r[i]['colors'] + '</span>色</li>';
								lsTop += '<li class="item_size">' + size + '</li>';
							lsTop += '</ul>';
						lsTop += '</div>';
					lsTop += '</div>';
				lsTop += '</div>';
				lsTop += '<div class="item_review">';
					lsTop += '<a class="show_review" href="/itemreviews/?item=' + r[i]['item_id'] + '" target="_blank">レビューを見る（' + r[i]['reviews'] + '件）</a>';
				lsTop += '</div>';
			lsTop += '</div>';
		}
		$('.listitems_top3').html(lsTop);
		
		for (i = 3; i < len; i++) {
			code = r[i]['item_code'];
			folder = r[i]['category_key'];
			size = r[i]['sizename_from'] + '&#65374;' + r[i]['sizename_to'];

			if ((code.indexOf('p-') == 0 && r[i]['i_color_code'] == "") || code == 'ss-9999' || code == 'ss-9999-96') {
				suffix = '_style_0';
			} else {
				suffix = '_' + r[i]['i_color_code'];
			}
			if (r[i]['sizename_from'] == r[i]['sizename_to']) {
				size = r[i]['sizename_from'];
			}
			
			lsOther += '<div class="listitems col-12 col-sm-3" data-maker-id="' + r[i]['maker_id'] + '" id="itemid_' + r[i]['item_id'] + '_' + r[i]['pos_id'] + '_' + r[i]['range_id'] + '_' + r[i]['screen_id'] + '">';
				lsOther += '<div class="row">';
					lsOther += '<div class="item_img_wrap col-6 col-sm-12">';
						lsOther += '<div class="item_image_s">';
							lsOther += '<img class="item_pic" src="' + IMG_PATH + 'items/list/' + folder + '/' + code + '/' + code + suffix + '.jpg" width="90%" height="auto" alt="">';
						lsOther += '</div>';
					lsOther += '</div>';
					lsOther += '<div class="item_info_wrap col-6 col-sm-12">';
						lsOther += '<p class="point_s">' + r[i]['i_caption'] + '</p>';
						lsOther += '<div class="item_name_s">';
							lsOther += '<p class="item_name_kata">' + code.toUpperCase() + '</p>';
							lsOther += '<p class="item_name_name">' + r[i]['item_name'] + '</p>';
							lsOther += '<p class="item_price"><span>' + r[i]['cost'] + '</span>円&#65374;</p>';
						lsOther += '</div>';
						lsOther += '<div class="item_info_cs">';
							lsOther += '<ul>';
								lsOther += '<li class="item_color"><span>' + r[i]['colors'] + '</span>色</li>';
								lsOther += '<li class="item_size">' + size + '</li>';
							lsOther += '</ul>';
						lsOther += '</div>';
					lsOther += '</div>';
				lsOther += '</div>';
				lsOther += '<div class="item_review">';
					lsOther += '<a class="show_review" href="/itemreviews/?item=' + r[i]['item_id'] + '" target="_blank">レビューを見る（' + r[i]['reviews'] + '件）</a>';
				lsOther += '</div>';
			lsOther += '</div>';
		}
		$('.listitems_other').html(lsOther);
		$('#category_name').text($.curr.category[categoryId]['name']);
		$('#item_count').text('（' + len + 'アイテム）');
	};


	// 絞り込み条件を表示
	$('#modal_search').on("click", function () {
		var categoryId = Object.keys($.curr.category)[0],
			args = ['itemtags'],
			tags = [];
		
		if (categoryId == 4) {
			// スポーツウェアのみ例外
			tags = [73];
		} else {
			args.push(categoryId);
		}
		
		$('#tag span').each(function(){
			tags.push($(this).attr('class').split('_')[1]);
		});
		
		// アイテムタグのチェックボックス生成とリセットボタンのイベントハンドラー
		$.api(args, 'GET', function (r) {
			var list = '',
				categoryTag = '',
				sceneTag = '',
				silhouetteTag = '',
				materialTag = '',
				clothTag = '',
				sizeTag = '',
				categoryId = Object.keys($.curr.category)[0],
				tags = [];

			$('#tag span').each(function(){
				tags.push($(this).attr('class').split('_')[1]);
			});

			r.forEach(function (val, idx, ary) {
				switch (val.tag_type) {
					case 2:
						sceneTag += '<p><label><input type="checkbox" value="' + val.tag_id + '_' + val.tagtype_key + '"';
						if (tags.indexOf(String(val.tag_id)) != -1) sceneTag += ' checked';
						sceneTag += '>' + val.tag_name + '</label></p>';
						break;
					case 3:
						silhouetteTag += '<p><label><input type="checkbox" value="' + val.tag_id + '_' + val.tagtype_key + '"';
						if (tags.indexOf(String(val.tag_id)) != -1) silhouetteTag += ' checked';
						silhouetteTag += '>' + val.tag_name + '</label></p>';
						break;
					case 4:
						materialTag += '<p><label><input type="checkbox" value="' + val.tag_id + '_' + val.tagtype_key + '"';
						if (tags.indexOf(String(val.tag_id)) != -1) materialTag += ' checked';
						materialTag += '>' + val.tag_name + '</label></p>';
						break;
					case 5:
						clothTag += '<p><label><input type="checkbox" value="' + val.tag_id + '_' + val.tagtype_key + '"';
						if (tags.indexOf(String(val.tag_id)) != -1) clothTag += ' checked';
						clothTag += '>' + val.tag_name + '</label></p>';
						break;
					case 6:
						sizeTag += '<p><label><input type="checkbox" value="' + val.tag_id + '_' + val.tagtype_key + '"';
						if (tags.indexOf(String(val.tag_id)) != -1) sizeTag += ' checked';
						sizeTag += '>' + val.tag_name + '</label></p>';
						break;
				}
			});
			list += silhouetteTag !== '' ? '<div class="tag_list"><h3 class="tag_list_title">シルエット</h3>' + silhouetteTag + '</div><hr>' : "";
			list += materialTag !== '' ? '<div class="tag_list"><h3 class="tag_list_title">素材</h3>' + materialTag + '</div><hr>' : "";
			list += clothTag !== '' ? '<div class="tag_list"><h3 class="tag_list_title">生地</h3>' + clothTag + '</div><hr>' : "";
			list += sizeTag !== '' ? '<div class="tag_list"><h3 class="tag_list_title">サイズ</h3>' + sizeTag + '</div><hr>' : "";

			// スポーツウェアのみ例外
			list += (categoryId == 4 && sceneTag !== '') ? '<div class="tag_list"><div class="tag_list_title">シーン</div>' + sceneTag + '</div><hr>' : "";

			$.dialogBox(list, '条件検索', 'リセット', null).then(function(){
				var categoryId = Object.keys($.curr.category)[0],
					args = ['categories', categoryId],
					tags = [];

				$('#tag span').remove();
				if (categoryId == 4) {
					// スポーツウェアのみ例外
					tags = [73];
					args[1] = 0;
				}
				$.api(args, 'GET', showItem, tags);
			});
		}, tags);
	});


	// 絞り込み条件のチェックボックス
	$('#msgbox').on('change', '[type="checkbox"]', function () {
		var self = $(this),
			categoryId = Object.keys($.curr.category)[0],
			val = self.val().split('_')[0],
			name = self.parent().text(),
			args = ['categories', categoryId],
			tags = [];
		
		if (categoryId == 4) {
			// スポーツウェアのみ例外
			tags = [73];
			args[1] = 0;
		}
		
		if (self.prop('checked')) {
			$('#tag').append('<span class="tag_'+val+'">'+name+'</span>');
		} else {
			$('#tag .tag_'+val).remove();
		}
		
		$('#tag span').each(function(){
			tags.push($(this).attr('class').split('_')[1]);
		});
		
		$.api(args, 'GET', showItem, tags);
		
		$('#msgbox').modal('hide');
	});


	// アイテム一覧の並び替え
	$('#sort').on('change', function(){
		var self = $(this),
			categoryId = Object.keys($.curr.category)[0],
			sort = self.val(),
			args = ['categories', categoryId],
			tags = [];

		if (categoryId == 4) {
			// スポーツウェアのみ例外
			tags = [73];
			args[1] = 0;
		}
		
		args.push(sort);

		$('#tag span').each(function(){
			tags.push($(this).attr('class').split('_')[1]);
		});

		$.api(args, 'GET', showItem, tags);
	});


	/**
	 * Step1 -2
	 * アイテム選択でカラー、サイズ、枚数選択を表示
	 */
	$('.listitems_top3, .listitems_other').on("click", '.listitems', function(e){ 
		// レビューを見る場合
		if (e.target.className=='show_review') return;
		
		// 選択したアイテム情報
		var self = $(e.currentTarget),
			data = self.attr('id').split('_');	// [ , item_id, position_id, ranage_id, screen_id]
		data.push(self.find('.item_name_kata').text().toLowerCase());
		data.push(self.find('.item_name_name').text());
		
		setItemDetail(data).then(function(){
			$.next();	// ページ遷移
		});
	});


	/**
	 * 選択されたアイテムの詳細情報の設定とタグ生成
	 * @param itemInfo	Step1でアイテム指定の場合はアイテム情報の配列{@code [ , item_id, position_id, ranage_id, screen_id, item_code, item_name]}
	 *					カートでアイテム変更の場合は未指定
	 * -----
	 * Step1で{@code $.curr.itemId}と同じアイテムを選択した場合は即ページ遷移
	 * Step1で{@code $.curr.itemId}と違うアイテムを選択した場合はカート内のアイテムを確認
	 * 		カート内の当該デザインパターンに既にあるアイテムの場合は指定内容を再現し{@code $.curr.design}を更新
	 * 		カート内の当該デザインパターンには無いアイテムの場合は{@code $.curr.item}を再設定
	 * 			同じ絵型があれば{@code $.curr.design}を更新、無ければ{@code $.curr.design}を初期化
	 * カートの変更の場合は「戻る」ボタンを非表示
	 */
	var setItemDetail = function (itemInfo) {
		var i = 0,
			d = $.Deferred(),
			p = $.Deferred().resolve().promise(),
			categoryId = Object.keys($.curr.category)[0],
			pane = $('#item_info .pane:first'),
			designs = $.getStorage('design'),
			items = $.getStorage('item'),
			currItem = {},
			posId = 1,
			itemCode = '',
			itemName = '',
			colors = {},
			colorCount = 1,
			clone;

		// 追加カラー用タグを削除
		$('#item_info .pane:gt(0)').remove();
		
		if (!itemInfo) {
			// カートの変更
			currItem = items[$.curr.designId][$.curr.itemId];
			itemCode = currItem.code.toLowerCase();
			itemName = currItem.name;
			colors = currItem.color;
			colorCount = Object.keys(colors).length;
			
			// 選択しているカラー数分コピー
			for (i=0; i<colorCount-1; i++) {
				clone = pane.clone();
				
				// サイズテーブルと枚数小計を初期化
				clone.find('.size_table input[type="number"]').val(0);
				clone.find('.cur_amount').text('0');
				
				// 削除ボタン
				clone.prepend('<button class="del_item_color btn btn-outline-danger waves-effect del_btn_2">カラーを削除</button>');
				clone.insertBefore('#item_info .btn_box');
			}

			// コピー後に要素集合を再設定
			pane = $('#item_info .pane');
		} else {
			// Step1 で指定
			posId = itemInfo[2];
			itemCode = itemInfo[5];
			itemName = itemInfo[6];
			
			if ($.curr.itemId != itemInfo[1]) {
				// アイテムが変更された場合は選択状態を再設定
				$.curr.itemId = itemInfo[1];
				$.curr.item[$.curr.designId] = {};
				
				if ($.curr.designId==0 || !items.hasOwnProperty($.curr.designId) || !items[$.curr.designId].hasOwnProperty($.curr.itemId)) {
					// まだカートに入れていないアイテム、または別のデザインパターンでアイテム追加
					$.curr.item[$.curr.designId][$.curr.itemId] = {
						"code": itemCode,
						"name": itemName,
						"posId": posId,
						"rangeId": itemInfo[3],
						"screenId": itemInfo[4],
						"cateId": categoryId,
						"color": {}
					};
					$.curr.design[$.curr.designId] = {};

					// 違うアイテムで同じ絵型がカートにあればプリントの選択情報を設定
					if ($.curr.designId!=0 && items.hasOwnProperty($.curr.designId) && designs[$.curr.designId].hasOwnProperty(posId)) {
						$.curr.design[$.curr.designId][posId] = designs[$.curr.designId][posId];
					}
				} else {
					// カートの同じデザインパターンに既にあるアイテムの場合
					currItem = items[$.curr.designId][$.curr.itemId];
					colors = currItem.color;
					colorCount = Object.keys(colors).length;
					$.curr.item[$.curr.designId][$.curr.itemId] = currItem;
					
					// 選択しているカラー数分コピー
					for (i=0; i<colorCount-1; i++) {
						clone = pane.clone();

						// サイズテーブルと枚数小計を初期化
						clone.find('.size_table input[type="number"]').val(0);
						clone.find('.cur_amount').text('0');

						// 削除ボタン
						clone.prepend('<button class="del_item_color btn btn-outline-danger waves-effect del_btn_2">カラーを削除</button>');

						clone.insertBefore('#item_info .btn_box');
					}
					
					// コピー後に要素集合を再設定
					pane = $('#item_info .pane');
					
					// プリントの選択情報を設定
					$.curr.design[$.curr.designId] = {};
					$.curr.design[$.curr.designId][posId] = designs[$.curr.designId][posId];
				}

			} else {
				// ページ遷移
				return d.resolve().promise();
			}
		}

		// サムネイルと商品写真のタグ生成
		for (i=0; i<colorCount; i++) {
			p = p.then(function(idx){
				var def = $.Deferred(),
					cartData = '',
					target = pane,
					index = Object.keys(colors).length > 0 ? Object.keys(colors)[idx]: idx;
				
				if (colors.hasOwnProperty(index)) {
					cartData = colors[index];
					target = $(pane[index]);
				}
				$.api(['items', $.curr.itemId, 'colors'], 'GET', function (r) {
					var thumb = '',
						categoryCode = r[0]['category'],
						colorName = !cartData? '': cartData['name'],
						colorCode = !cartData? '': cartData['code'];
					
					// 現在選択中のカテゴリーIDを再設定（サムネイルのパスは商品カテゴリーに依存しないため）
					Object.keys($.categories).forEach(function(id, index){
						if ($.categories[id]['code']==categoryCode) {
							$.curr.item[$.curr.designId][$.curr.itemId]['cateId'] = id;
							return;
						}
					});
					
					r.forEach(function (val, index) {
						thumb += '<li';
						if ((!colorCode && index===0) || colorCode==val.code) thumb += ' class="nowimg"';
						thumb += '><img alt="' + val.code + '" title="' + val.name + '" src="' + IMG_PATH + 'items/list/' + categoryCode + '/' + itemCode + '/' + itemCode + '_' + val.code + '.jpg"></li>';
					});

					colorName = colorName || r[0]['name'];
					colorCode = colorCode || r[0]['code'];
					target.find('.color_sele_thumb').html(thumb);
					target.find('.item_image_big img').attr({
						'width': 300,
						'alt': colorCode,
						'src': IMG_PATH + 'items/' + categoryCode + '/' + itemCode + '/' + itemCode + '_' + colorCode + '.jpg'
					})
					target.find('.item_name').text(itemName);
					target.find('.note_color').text(colorName);
					target.find('.num_of_color').text(r.length);
					
					def.resolve(target, colorCode, cartData);
				});
				
				return def.promise();
			}.bind(null, i)).then(function (pane, colorCode, data) {
				// サイズと単価データを取得して枚数指定テーブルを生成
				return $.api(['items', $.curr.itemId, 'sizes', colorCode], 'GET', showSize(pane, data.vol));
			});
		}
		
		// 戻るボタンの表示切り替え
		p.then(function(){
			if (!itemInfo) {
				// カートの変更の場合
				$('#item_info').next('.transition_wrap').children('.step_prev').addClass('hidden');
			} else {
				$('#item_info').next('.transition_wrap').children('.step_prev').removeClass('hidden');
			}
			
			d.resolve();
		});
		
		return d.promise();
	}
	
	
	/**
	 * サイズテーブルを生成
	 * @param pane サイズテーブルを表示する親要素
	 * @param data サイズ毎の枚数 {サイズ名: {'amount': 枚数}}、または未指定で初めてのカラー
	 */
	var showSize = function (pane, data) {
		return function (r) {
			var volume = !data? {}: data,
				pre_sizeid = 0,
				cost = 0,	// 単価
				amount = 0,	// 枚数
				sum = 0,	// カラー毎の小計
				tot = 0,	// 合計枚数
				size_head = '',
				size_stock = '', // 在庫
				size_body = '',
				size_table = '';

			r.forEach(function (val, idx, ary) {
				if (volume.hasOwnProperty(val.name)) {
					amount = volume[val.name]['amount'];
				} else {
					amount = 0;
				}
				
				sum += amount;
				if (idx == 0) {
					pre_sizeid = val['id'];
					cost = val['cost'];
					size_head = '<th></th><th>' + val['name'] + '</th>';
					if (null == (val['stock'])) {
						size_stock = '<td></td><td class="stocking"></td>';
					}
					else{
						size_stock = '<td></td><td class="stocking">残り' + val['stock'] +'枚</td>';
					}
					size_body = '<th>1枚単価<span class="inter">' + val['cost'].toLocaleString('ja-JP') + '</span> 円</th><td class="size_' + val['id'] + '_' + val['name'] + '_' + val['cost'] + '"><input id="size_' + val['id'] + '" type="number" value="' + amount + '" min="0"></td>';
				} else if (cost != val['cost'] || (val['id'] > (++pre_sizeid) && val['id'] > 10)) { // 単価が違うかまたは、サイズ160以下を除きサイズが連続していない
					size_table += '<tr class="heading">' + size_head + '</tr>';
					size_table += '<tr>' + size_stock + '</tr>';
					size_table += '<tr>' + size_body + '<td>枚</td></tr>';

					pre_sizeid = val['id'];
					cost = val['cost'];
					size_head = '<th></th><th>' + val['name'] + '</th>';
					if (null == (val['stock'])) {
						size_stock = '<td></td><td class="stocking"></td>';
					}
					else{
					size_stock = '<td></td><td class="stocking">残り' + val['stock'] +'枚</td>';
					}
					size_body = '<th>1枚単価<span class="inter">' + val['cost'].toLocaleString('ja-JP') + '</span> 円</th><td class="size_' + val['id'] + '_' + val['name'] + '_' + val['cost'] + '"><input id="size_' + val['id'] + '" type="number" value="' + amount + '" min="0"></td>';
				} else {
					pre_sizeid = val['id'];
					size_head += '<th>' + val['name'] + '</th>';
					if (null == (val['stock'])) {
						size_stock = '<td></td><td class="stocking"></td>';
					}
					else{
					size_stock += '<td class="stocking">残り' + val['stock'] +'枚</td>';
					}
					size_body += '<td class="size_' + val['id'] + '_' + val['name'] + '_' + val['cost'] + '"><input id="size_' + val['id'] + '" type="number" value="' + amount + '" min="0"></td>';
				}
			});
		
			
			size_table += '<tr class="heading">' + size_head + '</tr>';
			size_table += '<tr>' + size_stock + '</tr>';
			size_table += '<tr>' + size_body + '<td>枚</td></tr>';
			pane.find('.size_table tbody').html(size_table);
			
			var sizeTable = pane.find('.size_table')[0];
			sizeTable.setAttribute('masterid', r[0]["master_id"]);
			pane.find('.size_table').siblings('.btmline').children('.cur_amount').text(sum.toLocaleString('ja-JP'));
			
			// 合計枚数を更新
			$('#item_info').children('.pane').each(function () {
				tot += $(this).find('.cur_amount').text().replace(',','') - 0;
			});
			$('#tot_amount').text(tot.toLocaleString('ja-JP'));
		};
		
	};


	
	// サムネイルの変更で商品写真とサイズテーブルの変更
	$('#item_info').on("click", '.color_sele_thumb li img', function () {
		if ($(this).parent().is('.nowimg')) return;
		
		var colors = $.curr.item[$.curr.designId][$.curr.itemId]['color'],
			self = $(this),
			pane = self.closest('.pane'),
			src = self.attr('src').replace('\/list\/', '/'),
			colorCode = self.attr('alt'),
			colorName = self.attr('title'),
			data;	// サイズ毎の枚数 {サイズ名: {'amount': 枚数}}

		self.parent().addClass('nowimg').siblings('li.nowimg').removeClass('nowimg');
		pane.find('.item_image_big img').attr({
			'src': src,
			'alt': colorCode
		});
		pane.find('.note_color').html(colorName);

		// サイズ毎の枚数の指定済みデータを取得
		for (let i in colors) {
			if (colorCode==colors[i]['code']) {
				data = colors[i]['vol'];
				break;
			}
		}
		
		$.api(['items', $.curr.itemId, 'sizes', colorCode], 'GET', showSize(pane, data));
	});


	// 別のアイテムカラーを追加
	$('#add_item_color').on("click", function () {
		var clone = $('#item_info .pane:first').clone();

		// サイズテーブルと枚数小計を初期化
		clone.find('.size_table input[type="number"]').val(0);
		clone.find('.cur_amount').text('0');

		// 削除ボタンを追加
		clone.prepend('<button class="del_item_color btn btn-outline-danger waves-effect del_btn_2">カラーを削除</button>');

		clone.insertBefore($(this).parent('.btn_box'));
	});


	// アイテムカラーを削除
	$('#item_info').on("click", '.del_item_color', function () {
		$(this).closest('.pane').slideUp('normal', function () {
			var totAmount = 0;
			
			$(this).remove();
			
			// 枚数合計を再計算
			$('#item_info .pane').each(function () {
				totAmount += $(this).find('.size_table').siblings('.btmline').children('.cur_amount').text().replace(',','')-0;
			});
			
			$('#tot_amount').text(totAmount.toLocaleString('ja-JP'));
		});
	});


	// 枚数の変更
	$('#item_info').on('change', '.pane .size_table tbody tr:not(".heading") td[class*="size_"] input', function () {
		var amount = 0,
			tot = 0,
			tbl = $(this).closest('.size_table');
		
		$.check_NaN(this);
		$(this).blur();

		// 小計
		tbl.find('tbody tr:not(".heading") td[class*="size_"] input').each(function () {
			amount += $(this).val() - 0;
		});
		tbl.siblings('.btmline').children('.cur_amount').text(amount.toLocaleString('ja-JP'));

		// 合計
		$('#item_info').children('.pane').each(function () {
			tot += $(this).find('.cur_amount').text().replace(',','') - 0;
		});
		$('#tot_amount').text(tot.toLocaleString('ja-JP'));
	});


	/**
	 * アイテムカラー別にサイズ毎の枚数の選択情報を更新
	 * @return {bool} 枚数の指定が０枚若しくはカラー指定に重複がある場合{@code false}、それ以外は{@code true}を返す
	 */
	var isDetailOfCurrentItem = function() {
		var tot = 0,				// 枚数小計
			isDuplicate = false;	// カラー指定の重複チェック
		
		// 当該アイテムのカラー指定を初期化
		$.curr.item[$.curr.designId][$.curr.itemId]['color'] = {};

		// カラー毎に更新
		$('#item_info .pane').each(function () {
			var self = $(this),
				colorCode = self.find('.item_image_big').children('img').attr('alt'),
				colorName = self.find('.note_color').text(),
				colors = $.curr.item[$.curr.designId][$.curr.itemId]['color'],
				len = Object.keys(colors).length,
				hash = {},
				sizeTable = self.find('.size_table')[0],
				masterId = sizeTable.getAttribute('masterid');
			
			// サイズ毎の枚数をチェック {サイズ名: 枚数}
			self.find('.size_table').find('tbody tr:not(".heading") td[class*="size_"] input').each(function () {
				var amount = $(this).val() - 0;
				if (amount == 0) return true; // continue
				var param = $(this).parent().attr('class').split('_');	//[, id, name, cost]
				hash[param[2]] = {'amount':amount, 'cost':param[3], 'id':param[1]};
				tot += amount;
			});

			if (Object.keys(hash).length !== 0) {
				// カラーの重複チェック
				for (let i in colors) {
					if (colors[i]['code']==colorCode) {
						isDuplicate = true;
						return false; // break;
					}
				}
				$.curr.item[$.curr.designId][$.curr.itemId]['color'][len] = {};
				$.curr.item[$.curr.designId][$.curr.itemId]['color'][len]['master'] = masterId;
				$.curr.item[$.curr.designId][$.curr.itemId]['color'][len]['code'] = colorCode;
				$.curr.item[$.curr.designId][$.curr.itemId]['color'][len]['name'] = colorName;
				$.curr.item[$.curr.designId][$.curr.itemId]['color'][len]['vol'] = hash; // ex: {M: {amount:10, cost:500, id:sizeID}, ...};
			}
		});
		if (isDuplicate === true) {
			$.msgbox('カラーの指定が重複しています。');
			return false;
		} else if (tot === 0) {
			$.msgbox('枚数をご指定ください。');
			return false;
		}
		return true;
	}


	/**
	 * Step2
	 * プリント指定へ
	 * -----
	 * カートの変更の場合はプリント指定を通らずカートへ遷移
	 */
	$('#goto_printing').on("click", function(){
		var self = $('#goto_printing');
		if (self.next('.step_prev').is('.hidden')) {
			showPrinting(1).then(function(){
				$.resetCart();
			}).then(function(){
				self.next('.step_prev').removeClass('hidden');
				$.next(2);	// カートへ遷移
			});
		} else {
			showPrinting(0).then(function(){
				$.next();	// プリント指定ページ遷移
			});
		}
	});
	
	
	/**
	 * プリント指定ページの生成
	 * @param isCart	0: 通常フロー、それ以外:カートの変更
	 */
	var showPrinting = function (isCart) {
		var d = $.Deferred(),
			p = $.Deferred().resolve().promise(),
			designs = {},
			target;

		// カラー・枚数の指定内容を更新
		if (!isDetailOfCurrentItem()) {
			return d.reject().promise();
		}
		
		// プリントなしのアイテムが既にカートにある場合は、プリント有無の変更不可
//		designs = $.getStorage('design');
//		if (designs && (designs.hasOwnProperty('id_0') || designs.hasOwnProperty($.curr.designId))) {
//			$('#noprint').closest('.form-group_top').addClass('invisible');
//		} else {
//			$('#noprint').closest('.form-group_top').removeClass('invisible');
//		}
//		
		// プリント指定の表示切り替え
		if ($.curr.designId!='id_0') {
			$('#noprint').prop('checked', false);
			$('#printing .pane').show();
			$('#add_print').show();
		} else {
			$('#noprint').prop('checked', true);
			$('#printing .pane').hide();
			$('#add_print').hide();
		}
		
		// プリント指定ページへ
		if (Object.keys($.curr.design[$.curr.designId]).length===0 || isCart!==1) {
			// 初めてのStep通過と戻るボタンでアイテム変更している、またはカート内の変更以外の場合
			
			// プリント指定項目と金額表示を初期化
			$('#printing .pane:gt(0)').remove();
			$('#printing .pane').find('.print_selector').val('recommend');
			$('#printing .pane').find('.ink').val(['1']);
			$('#printing .pane').find('.print_cond').addClass('hidden').find('input').val(['0']);
			$('#printing').find('.price_box_2').addClass('hidden');
			$('#printing .pane').find('.print_cond_note').addClass('hidden');
			$('#printing .pos, #printing .area').html('');
			$('.price_box .total_p span, price_box .solo_p span').text('0');
			
			p.then(function(){
				var def = $.Deferred();
				
				// 絵型とプリント箇所のタグを生成
				$.api(['items', $.curr.itemId, 'printpatterns'], 'GET', function(r){
					var i = 0,
						designData = [],
						pane = $('#printing .pane:first'),
						list = '',
						face = '',
						area = '',
						attrId = 0,			// プリント箇所指定の要素ID
						designCount = 0,
						clone,
						len = 0;

					$.curr.posId = r[0]['id'];
					
					if ($.curr.designId!=0 && Object.keys($.curr.design[$.curr.designId]).length) {
						// カートの変更、または戻るボタンでアイテムに変更がない場合
						
						Object.keys($.curr.design[$.curr.designId][$.curr.posId]).forEach(function(face){
							Object.keys(this[face]).forEach(function(idx){
								designData[designCount] = {'face':face,
														   'area':this[idx]['area'],
														   'size':this[idx]['size'], 
														   'option':this[idx]['option'], 
														   'method':this[idx]['method'], 
														   'printable':this[idx]['printable'], 
														   'ink':this[idx]['ink'],
														   'idx':idx
														  };
								designCount++;

								if (designCount===1) return;

								clone = pane.clone(true);
								clone.data('idx',idx);

								// 削除ボタンを表示
								clone.children('.btn_box').children('.del_print_area').removeClass('hidden');

								pane.after(clone);
							}, this[face]);
						}, $.curr.design[$.curr.designId][$.curr.posId]);

						// コピー後に要素集合を再設定
						target = $('#printing .pane');
					} else {
						// 初めてのStep通過、または戻るボタンでアイテムに変更あり
						// デザイン選択情報の初期設定
						designCount++;
						target = pane;
					}

					// プリント面指定毎のHTMLタグを生成
					len = designData.length;
					for (i=0; i<designCount; i++) {
						// プリント面
						list = '';
						r.forEach(function (val, idx, ary) {
							if (val.front) {
								face = 'front';
								list += '<div class="print_position"><label><img src="' + IMG_PATH + 'printpattern/' + val.category + '/' + val.item + '/base_front.svg">';
								list += '<p>前面</p><input type="radio" name="face[]" class="face" value="front"';
								if (len===0 || designData[i]['face']=='front') list += ' checked';
								list += '></label></div>';
							}
							if (val.back) {
								face = face || 'back';
								list += '<div class="print_position"><label><img src="' + IMG_PATH + 'printpattern/' + val.category + '/' + val.item + '/base_back.svg">';
								list += '<p>背面</p><input type="radio" name="face[]" class="face" value="back"';
								if ((len>0 && designData[i]['face']=='back') || face == 'back') list += ' checked';
								list += '></label></div>';

							}
							if (val.side) {
								face = face || 'side';
								list += '<div class="print_position"><label><img src="' + IMG_PATH + 'printpattern/' + val.category + '/' + val.item + '/base_side.svg">';
								list += '<p>側面</p><input type="radio" name="face[]" class="face" value="side"';
								if ((len>0 && designData[i]['face']=='side') || face == 'side') list += ' checked';
								list += '></label></div>';
							}
						});
						$(target[i]).find('.pos').html(list);

						// プリント箇所名を設定
						if (r[0].front) $('#printing .pos .face[value="front"]').data('pos', r[0]['front']);
						if (r[0].back) $('#printing .pos .face[value="back"]').data('pos', r[0]['back']);
						if (r[0].side) $('#printing .pos .face[value="side"]').data('pos', r[0]['side']);

						// プリント箇所のセレクトタグ
						if (len>0) face = designData[i]['face'];
						if (face) {
							list = '<img alt="' + face + '" src="' + IMG_PATH + 'printpattern/' + r[0].category + '/' + r[0].item + '/base_' + face + '.svg">';
							list += '<p class="pos_selector_wrap"><select class="down_cond">';
							r[0][face].split(',').forEach(function (pos, idx) {
								list += '<option value="' + pos + '"';
								if ((designData.length>0 && designData[i]['area']==pos) || idx === 0) {
									list += ' selected';
									area = pos;
								}
								list += '>' + pos + '</option>';
							});
							list += '</select></p>';
						} else {
							// プリント不可の商品
							list = '<img alt="' + face + '" src="' + IMG_PATH + 'printpattern/misc/noprint/base_front.svg">';
							list += '<p class="pos_selector_wrap">';
							list += '<select class="down_cond"><option value="" selected>プリントなし</option></select>';
							list += '</p>';
						}
						$(target[i]).find('.area').html(list);
						
						// インク色数、カートの変更の場合のみ
						if (len>0) {
							$(target[i]).find('.ink').val([designData[i]['ink']]);
						}
					}

					// 初めてのStep、または戻るボタンでアイテム変更
					if ($.curr.designId==0 || Object.keys($.curr.design[$.curr.designId]).length===0) {
						// デザイン選択情報の初期設定
						$.curr.design[$.curr.designId] = {};
						$.curr.design[$.curr.designId][$.curr.posId] = {};
						$.curr.design[$.curr.designId][$.curr.posId][face] = {
							'0': {
								'area': area,
								'size': 0,
								'option': 0,
								'method': 'recommend',
								'printable': [],
								'ink': 1
							}
						};
					}

					def.resolve(designData, designCount);
				});
				
				return def.promise();
			}).then(function(designData, designCount){
				var def = $.Deferred();
				
				// プリント方法選択のセレクトタグを生成
				$.api(['items', $.curr.itemId, 'details'], 'GET', function (r) {
					var i = 0,
						pane = $('#printing .pane:first'),
						face = Object.keys($.curr.design[$.curr.designId][$.curr.posId])[0],
						list = '',
						len = designData.length,
						method = 'recommend';

					for(i=0; i<designCount; i++){
						
						// カートの変更の場合のみ
						if (len>0) {
							method = designData[i]['method'];

							// プリントサイズ
							switch (method) {
								case 'recommend':
									$(target[i]).find('.price_box_2').removeClass('hidden');
									break;
								case 'silk':
									if (Object.keys($.curr.category)[0] == 8) {
										$(target[i]).find('.silk_towel').removeClass('hidden').find('.design_size').val([designData[i]['size']]);
									} else {
										$(target[i]).find('.silk').removeClass('hidden').find('.design_size').val([designData[i]['size']]);
									}
									break;
								case 'digit':
								case 'inkjet':
								case 'cutting':
									$(target[i]).find('.other_print').removeClass('hidden').find('.design_size').val([designData[i]['size']]);
									break;
								case 'emb':
									$(target[i]).find('.embroidery').removeClass('hidden').find('.design_size').val([designData[i]['size']]);
									$(target[i]).find('.embroidery').find('.design_opt').val([designData[i]['option']]);
									break;
							}
						}
						
						// カッティングと刺繍のみ対応の商品の場合は、「おまかせ」なし
						if (r[0]['silk'] == 1 || r[0]['digit'] == 1 || r[0]['inkjet'] == 1) {
							list = '<p><select class="print_selector down_cond">';
							list += '<option class="recommend" value="recommend"';
							if (method=='recommend') {
								list += ' selected';
								$(target[i]).find('.price_box_2').removeClass('hidden');
							}
							list += '>おまかせ</option>';
							if (r[0]['silk'] == 1) {
								list += '<option class="silk" value="silk"';
								if (method=='silk') list += ' selected';
								list += '>シルクスクリーン</option>';
							}
							if (r[0]['digit'] == 1) {
								list += '<option class="digit" value="digit"';
								if (method=='digit') list += ' selected';
								list += '>デジタル転写</option>';
							}
							if (r[0]['inkjet'] == 1) {
								list += '<option class="inkjet" value="inkjet"';
								if (method=='inkjet') list += ' selected';
								list += '>インクジェット</option>';
							}
							if (r[0]['cutting'] == 1) {
								list += '<option class="cutting" value="cutting"';
								if (method=='cutting') list += ' selected';
								list += '>カッティング</option>';
							}
							if (r[0]['emb'] == 1) {
								list += '<option class="emb" value="emb"';
								if (method=='emb') list += ' selected';
								list += '>刺繍</option>';
							}
						} else {
							list = '<p><select class="print_selector down_cond">';
							if (r[0]['cutting'] == 1) {
								list += '<option class="cutting" value="cutting"';
								if (method=='cutting' || r[0]['emb'] != 1) {
									list += ' selected';
									$.curr.design[$.curr.designId][$.curr.posId][face]['0']['method'] = 'cutting';
									
									// オプション選択表示
									$(target[i]).find('.other_print').removeClass('hidden');
									$(target[i]).find('.print_cond_note').removeClass('hidden');
								}
								list += '>カッティング</option>';
							}
							if (r[0]['emb'] == 1) {
								list += '<option class="emb" value="emb"';
								if (method=='emb' || r[0]['cutting'] != 1) {
									list += ' selected';
									$.curr.design[$.curr.designId][$.curr.posId][face]['0']['method'] = 'emb';
									
									// オプション選択表示
									$(target[i]).find('.embroidery').removeClass('hidden');
									$(target[i]).find('.print_cond_note').removeClass('hidden');
								}
								list += '>刺繍</option>';
							}
						}
						list += '</select></p>';
						$(target[i]).find('.method').html(list);

					}
					
					// おまかせプリントのメッセージ
//					$('#printing .print_selector').each(function(){
//						if ($(this).val() == 'recommend') {
//							$('#printing').find('.price_box_2').removeClass('hidden');
//							return false;	// break
//						}
//					});
					
					// 初めてのStep、または戻るボタンでアイテム変更
					if ($.curr.designId==0 || $.curr.design[$.curr.designId][$.curr.posId][face]['0']['printable'].length===0){
						// 対応するプリント方法を設定[silk, digit, inkjet] 1:対応する、0:未対応
						$.curr.design[$.curr.designId][$.curr.posId][face]['0']['printable'] = [r[0]['silk'],r[0]['digit'],r[0]['inkjet']];
					}
					
					def.resolve(designData, designCount);
				});
				
				return def.promise();
			}).then(function(designData, designCount){
				// 見積もり再計算
				var i = 0,
					def = $.Deferred(),
					pro = $.Deferred().resolve().promise(),
					len = designData.length,
					index,
					face;
				
				if (len>0) {
					// カートの変更
//					for(i=0; i<designCount; i++){
//						pro = pro.then(function(idx){
//							var face = designData[idx]['face'],
//								index = $(target[idx]).data('idx');
//							return $.printCharge($.curr, [face, index]).then(function(printFee){
//								$.showPrintPrice($(target[idx]), printFee, $.carriage);
//							});
//						}.bind(null, i));
//					}
//					pro = pro.then(function(){
//						def.resolve();
//					});
					$.printCharge($.curr).then(function(printFee){
						$.showPrintPrice($('#printing'), printFee, $.carriage);
						def.resolve();
					});
				} else {
					// 初めてのStep通過
//					face = Object.keys($.curr.design[$.curr.designId][$.curr.posId])[0];
//					index = $(target[0]).data('idx');
					$.printCharge($.curr).then(function(printFee){
						$.showPrintPrice($('#printing'), printFee, $.carriage);
						def.resolve();
					});
				}
				
				return def.promise();
			}).then(function(){
				d.resolve();
			});
		} else {
			// 戻るボタンで再通過の場合
			
			// 見積もり再計算
			$.printCharge($.curr).then(function(printFee){
				$.showPrintPrice($('#printing'), printFee, $.carriage);
				d.resolve();
			});
			
//			$('#printing .pane').each(function(){
//				var self = $(this),
//					index = self.data('idx'),
//					face = self.find('.area img').attr('alt');
//				
//				p = p.then(
//					function(self, face, index){
//						return $.printCharge($.curr, [face, index]).then(
//							function(printFee){
//								$.showPrintPrice(self, printFee, $.carriage);
//							});
//					}.bind(null, self, face, index));
//			});
//			p.then(function(){
//				d.resolve();
//			});
		}
		
		return d.promise();
	};


	// プリントなしチェック
	$('#noprint').on('click', function () {
		var val = $(this).prop('checked') ? 0 : 1,
			p = $.Deferred().resolve().promise();

		// 選択項目の表示・非表示を切り替え
		if (val !== 1) {
			$('#printing .pane').hide();
			$('#add_print').hide();
			$('#printing .price_box').hide();
		} else {
			$('#printing .pane').show();
			$('#add_print').show();
			$('#printing .price_box').show();

			// 見積もり再計算
			$.printCharge($.curr).then(function(printFee){
				$.showPrintPrice($('#printing'), printFee, $.carriage);
			});
			
//			$('#printing .pane').each(function(){
//				var self = $(this),
//					index = self.data('idx'),
//					face = self.find('.area img').attr('alt');
//
//				p = p.then(function(self, face, index){
//					return $.printCharge($.curr, [face, index]);
//				}.bind(null, self, face, index)).then(function(printFee){
//					$.showPrintPrice(self, printFee, $.carriage);
//				});
//			});
		}
	});


	// プリント面変更
	$('#printing').on('change', '.pane .face', function () {
		var face = $(this).val(),
			pane = $(this).closest('.pane'),
			index = pane.data('idx'),
			img = pane.find('.area img'),
			preFace = img.attr('alt'),
			src = img.attr('src'),
			obj = $.curr.design[$.curr.designId][$.curr.posId],
			tmp = JSON.stringify(obj[preFace][index]),
			list = '',
			area = '',
			newIndex = 0;

		// プリント面の指定状況を確認
		if (obj.hasOwnProperty(face)) {
			// 既に指定されているプリント面あり
			if (Object.keys(obj[face]).length == 2) {
				$.msgbox('同じ面に指定できる箇所は２箇所までとなっています。<br>３箇所以上をご希望の方はご相談ください。');
				$(this).val([preFace]);
				return;
			}
			newIndex = Math.max.apply(null, Object.keys(obj[face])) + 1;
		} else {
			// 新規のプリント面
			obj[face] = {};
		}
		
		// データ属性値を更新
		pane.data('idx', newIndex);

		// ファイル名変更
		src = src.replace('base_' + preFace + '.svg', 'base_' + face + '.svg');
		img.attr({
			'src': src,
			'alt': face
		});

		// プリント箇所のセレクターを変更
		list += '<select class="down_cond">';
		$(this).data('pos').split(',').forEach(function (pos, idx) {
			list += '<option value="' + pos + '"';
			if (idx === 0) {
				list += ' selected';
				area = pos;
			}
			list += '>' + pos + '</option>';
		});
		list += '</select>';
		img.closest('.pane').find('.pos_selector_wrap').html(list);

		// デザイン選択情報を更新
		obj[face][newIndex] = JSON.parse(tmp);
		obj[face][newIndex]['area'] = area;
		delete $.curr.design[$.curr.designId][$.curr.posId][preFace][index];	// deleteは参照先は削除されない
		if (Object.keys(obj[preFace]).length == 0) {
			delete $.curr.design[$.curr.designId][$.curr.posId][preFace];		// deleteは参照先は削除されない
		}
		
		// 見積もり再計算
		$.printCharge($.curr).then(function(printFee){
			$.showPrintPrice($('#printing'), printFee, $.carriage);
		});
	});


	// プリント箇所を変更
	$('#printing').on('change', '.pane .pos_selector_wrap select', function () {
		var self = $(this),
			area = self.val(),
			pane = self.closest('.pane'),
			index = pane.data('idx'),
			face = pane.find('.area img').attr('alt'),
			obj = $.curr.design[$.curr.designId][$.curr.posId][face],
			isExist = false;

		// デザイン選択情報を更新
		Object.keys(obj).forEach(function(idx){
			if (obj[idx]['area']==area) {
				$.msgbox(area+'はすでに選択されています');
				self.val(obj[index]['area']);
				isExist = true;
				return;
			}
		});

		if (isExist===false) {
			obj[index]['area'] = area;
			
			// 見積もり再計算
			$.printCharge($.curr).then(function(printFee){
				$.showPrintPrice($('#printing'), printFee, $.carriage);
			});
		}
	});


	// 色数変更
	$('#printing').on('change', '.pane .ink', function () {
		var self = $(this),
			ink = self.val(),
			pane = self.closest('.pane'),
			index = pane.data('idx'),
			face = pane.find('.area img').attr('alt'),
			obj = $.curr.design[$.curr.designId][$.curr.posId][face][index];

		// 刺繍の場合はオプション確認
		if (obj['method'] == 'emb' && obj['option'] == 1) {
			if (ink > 1) {
				$.msgbox('刺繍のネームは、１色のみの対応となっております。');
				pane.find('.ink[value="1"]').prop('checked', true);
			}
		}

		// デザイン選択情報を更新
		obj['ink'] = ink;

		// 見積もり再計算
		$.printCharge($.curr).then(function(printFee){
			$.showPrintPrice($('#printing'), printFee, $.carriage);
		});
	});


	// プリント方法を変更
	$('#printing').on('change', '.print_selector', function () {
		var val = $(this).val(),
			pane = $(this).closest('.pane'),
			index = pane.data('idx'),
			face = pane.find('.area img').attr('alt'),
			obj = $.curr.design[$.curr.designId][$.curr.posId][face][index];

		// 初期化
		pane.find('.print_cond').addClass('hidden').find('input').val(['0']);
		pane.find('.price_box_2').addClass('hidden');
		pane.find('.print_cond_note').removeClass('hidden');
		
		// 表示切り替え
		switch (val) {
			case 'recommend':
				pane.find('.price_box_2').removeClass('hidden');
				pane.find('.print_cond_note').addClass('hidden');
				break;
			case 'silk':
				if (Object.keys($.curr.category)[0] == 8) {
					pane.find('.silk_towel').removeClass('hidden');
				} else {
					pane.find('.silk').removeClass('hidden');
				}
				break;
			case 'digit':
			case 'inkjet':
			case 'cutting':
				pane.find('.other_print').removeClass('hidden');
				break;
			case 'emb':
				pane.find('.embroidery').removeClass('hidden');
				break;
		}

		// デザイン選択情報を更新
		obj['size'] = 0;
		obj['option'] = 0;
		obj['method'] = val;

		// おまかせプリントのメッセージ
//		$('#printing .print_selector').each(function(){
//			if ($(this).val() == 'recommend') {
//				$('#printing').find('.price_box_2').removeClass('hidden');
//				return false;	// break
//			}
//		});
		
		// 見積もり再計算
		$.printCharge($.curr).then(function(printFee){
			$.showPrintPrice($('#printing'), printFee, $.carriage);
		});
	});


	// プリントサイズ変更
	$('#printing').on('change', '.pane .design_size', function () {
		var pane = $(this).closest('.pane'),
			index = pane.data('idx'),
			face = pane.find('.area img').attr('alt');

		// デザイン選択情報を更新
		$.curr.design[$.curr.designId][$.curr.posId][face][index]['size'] = $(this).val();

		// 見積もり再計算
		$.printCharge($.curr).then(function(printFee){
			$.showPrintPrice($('#printing'), printFee, $.carriage);
		});
	});


	// プリントオプション変更（刺繍）
	$('#printing').on('change', '.pane .design_opt', function () {
		var pane = $(this).closest('.pane'),
			index = pane.data('idx'),
			face = pane.find('.area img').attr('alt');

		if ($(this).val()==1 && pane.find('.ink:checked').val() > 1) {
			$.msgbox('刺繍のネームは、１色のみの対応となっております。');
			pane.find('.design_opt[value="0"]').prop('checked', true);
			return;
		}
		
		// デザイン選択情報を更新
		$.curr.design[$.curr.designId][$.curr.posId][face][index]['option'] = $(this).val();

		// 見積もり再計算
		$.printCharge($.curr).then(function(printFee){
			$.showPrintPrice($('#printing'), printFee, $.carriage);
		});
	});


	// プリント箇所を追加（２箇所まで可）
	$('#printing').on("click", '.pane .btn_box .add_print_area', function(){
		var pane = $(this).closest('.pane'),
			face = pane.find('.area img').attr('alt'),
			obj = $.curr.design[$.curr.designId][$.curr.posId],
			aryFace = Object.keys(obj[face]),
			index = +aryFace[0],
			area = obj[face][index]['area'],
			clone = '';

		$(this).blur();

		// プリント面の指定状況を確認
		if (aryFace.length == 2) {
			$.msgbox('同じ面に指定できる箇所は２箇所までとなっています。<br>３箇所以上をご希望の方はご相談ください。');
			return;
		}

		clone = pane.clone(true);

		// デザイン選択情報を更新
		if (Math.max.apply(null, obj[face][0]['printable'])==0) {
			// おまかせが不可の場合
			obj[face][++index] = [].concat(obj[face][aryFace[0]])[0];
		} else {
			obj[face][++index] = {
				'area': area,
				'size': 0,
				'option': 0,
				'method': 'recommend',
				'printable': obj[face][aryFace[0]]['printable'],
				'ink': 1
			};
			
			// プリント方法をおまかせに指定
			clone.find('.print_selector').val('recommend');
			clone.find('.price_box_2').removeClass('hidden');
			
			// プリントサイズ選択を非表示
			clone.find('.print_cond_note').addClass('hidden');

			// プリントサイズの初期化と非表示
			clone.find('.print_cond').addClass('hidden').find('input').val(['0']);

			// インク色数を初期化
			clone.find('.ink').val(['1']);
		}

		// プリント指定のdata属性値を設定
		clone.data('idx', index);

		// プリント箇所をコピー元と違う箇所に設定
		pane.find('.area').children('.pos_selector_wrap').children('select').children('option').each(function(){
			var areaName = $(this).val();
			if (areaName!=area) {
				obj[face][index]['area'] = areaName;
				clone.find('.area').children('.pos_selector_wrap').children('select').val(areaName);
				return false;	// break;
			}
		});
		
		// 削除ボタンを表示
		clone.children('.btn_box').children('.del_print_area').removeClass('hidden');

		// 要素を追加
		pane.after(clone);

		// おまかせプリントのメッセージ
//		$('#printing .print_selector').each(function(){
//			if ($(this).val() == 'recommend') {
//				$('#printing').find('.price_box_2').removeClass('hidden');
//				return false;	// break
//			}
//		});
		
		// 見積もり再計算
		$.printCharge($.curr).then(function(printFee){
			$.showPrintPrice($('#printing'), printFee, $.carriage);
		});
	});
	
	
	// プリント箇所を削除
	$('#printing').on("click", '.pane .btn_box .del_print_area', function () {
		var pane = $(this).closest('.pane'),
			index = pane.data('idx'),
			face = pane.find('.area img').attr('alt'),
			obj = $.curr.design[$.curr.designId][$.curr.posId][face];
		pane.slideUp('normal', function () {
			$(this).remove();
			delete $.curr.design[$.curr.designId][$.curr.posId][face][index];	// deleteは参照先は削除されない
			if (Object.keys(obj).length == 0) {
				delete $.curr.design[$.curr.designId][$.curr.posId][face];	// deleteは参照先は削除されない
			}
			
			// 見積もり再計算
			$.printCharge($.curr).then(function(printFee){
				$.showPrintPrice($('#printing'), printFee, $.carriage);
			});
		});
	});
	
	
	/**
	 * Step3
	 * カートに入れる
	 */
	$('#goto_cart').on("click", function(){
		var i = 0,
			newId = 0,
			ids = [],
			sum = $.getStorage('sum'),
			orderItem = {},	// {'price':商品金額合計 'amount':注文総枚数}
			designs = {},
			items = {},
			dummy = {};

		// デザインIDの付け替え
		if ($.curr.designId==0) {
			designs = $.getStorage("design");
			if ($('#noprint').prop('checked')) {
				$.curr.designId = 'id_0';	// プリントなし
			} else if (designs !== null) {
				Object.keys(designs).forEach(function(designId){
					ids.push(designId.split('_')[1]);
				});
				newId = Math.max.apply(null, ids) + 1;
				$.curr.designId = 'id_' + newId;
			} else {
				$.curr.designId = 'id_1';	// 新規デザインパターン
			}
			dummy = JSON.stringify($.curr.design[0]);
			$.curr.design[$.curr.designId] = JSON.parse(dummy);
			delete $.curr.design[0];
			
			// アイテムのデザインIDを変更
			dummy = JSON.stringify($.curr.item[0]);
			$.curr.item[$.curr.designId] = JSON.parse(dummy);
			delete $.curr.item[0];
		}
		
		// 選択中のアイテムとカート内のデータをマージ
		designs = $.setStorage('design', $.curr.design);
		items = $.setStorage('item', $.curr.item);
		
		// 注文商品の金額と枚数を更新
		orderItem = $.itemPrice(items);
		sum = $.setStorage('sum', {'item': orderItem.price, 'volume': orderItem.amount});
		
		// 量販単価の判定と処理の分岐
		if (sum.volume > 149 && sum.mass!=1) {
			sum.mass = 1;		// 量販単価
		} else if (sum.volume < 150 && sum.mass!=0){
			sum.mass = 0;		// 通常単価
		} else {
			setCart(designs, items).then(function(totPrintFee){
				// プリント代合計を更新
				sum.print = totPrintFee;
				sum = $.setStorage('sum', sum);
				
				// 見積もり詳細
				return $.estimate();
			}).then(function(){
				$('#goto_cart').next('.step_prev').removeClass('hidden');
				$.next();	// ページ遷移
			});
			return;
		}
		
		// 商品単価の確認
		$.when(
			$.setMassCost(items, sum.volume)
		).then(function(items){
			items = $.setStorage('item', items);
			
			// 注文商品の金額と枚数及び量販単価の適用判定を更新
			orderItem = $.itemPrice(items);
			sum.item = orderItem.price;
			sum.volume = orderItem.amount;
			sum = $.setStorage('sum', sum);
			
			return setCart(designs, items);
		}).then(function(totPrintFee){
			// プリント代合計を更新
			sum.print = totPrintFee;
			sum = $.setStorage('sum', sum);
			
			// 見積もり詳細
			return $.estimate();
		}).then(function(){
			$('#goto_cart').next('.step_prev').removeClass('hidden');
			$.next();	// ページ遷移
		});
	});
	
	
	/**
	 * 非同期
	 * カートのタグ生成とプリント代合計
	 * @param designs
	 * @param items
	 * @return プリント代の合計（Promise object）
	 */
	var setCart = function(designs, items) {
		var i = 0,
			d = $.Deferred(),
			p= $.Deferred().resolve().promise(),
			designPattern = [],	// 有効なデザインIDの配列
			printNumber = {},	// プリント情報で絵型毎に表示するプリントNo.
			num = 0,			// プリントNo.
			cartBox = '',
			designCount = 0,	// デザインパターンの数
			designIndex = 0,	// .cart_box要素のインデックス
			grandTotal = 0,		// 全てのデザインパターンのアイテム代とプリント代の合計（税抜）
			totPrintFee = 0,	// プリント代合計額（税抜）
			sum = $.getStorage('sum');

		// 初期化
		$('#cart .cart_box:gt(0)').remove();
		cartBox = $('#cart .cart_box');
		cartBox.children('.item_wrap:gt(0)').remove();

		// カラー毎の表示要素を初期化
		cartBox.children('.item_wrap').children('.color_diff').children('.item_info_order:gt(0)').remove();
		cartBox.children('.item_wrap').children('.color_diff').children('.item_info_order').children('.size_count').find('tr:gt(0)').remove();

		// プリント情報を初期化
		cartBox.children('.item_info_order_2').children('.print_info').find('tr').remove();

		// 金額を初期化
		cartBox.children('.cart_price_min').find('span').text('0');
//		cartBox.next('.price_box').children('.total_p').children('span').text('0');
		$('#estimation .total_p span, #estimation .solo_p span').text('0');

		// デザインパターン毎の表示要素を作成
		designPattern = Object.keys(designs);
		designCount = designPattern.length;
		for (i=0; i<designCount-1; i++) {
			if (Object.keys(designs[designPattern[i]]).length==0) continue;
			cartBox.clone().insertBefore('#manuscript');
		}

		// コピー後に要素集合を再設定
		cartBox = $('#cart .cart_box');

		// デザインパターン毎にタグを生成
		Object.keys(designs).forEach(function(designId){
			if (Object.keys(designs[designId]).length==0) return;
			
			var printInfo = '',
				printName = {'silk':'シルクスクリーン',
							 'digit':'デジタルコピー転写',
							 'inkjet':'インクジェット',
							 'cutting':'カッティング',
							 'emb':'刺繍',
							 'recommend':'おまかせ'
							},
				printSize = {'silk':['通常','ジャンボ','スーパージャンボ'],
							 'digit':['大','中','小'],
							 'inkjet':['大','中','小'],
							 'cutting':['大','中','小'],
							 'emb':['大','中','小','極小'],
							 'recommend':['']},
				printOption = { 'emb':['デザイン','ネーム']};

			printNumber[designId] = {};
			
			// プリント情報
			Object.keys(designs[designId]).forEach(function(posId){
				
				// プリントなし
				if (designId=='id_0') {
					printInfo = 'プリントなし';
					return;
				}
				
				printNumber[designId][posId] = ++num;
				printInfo += '<tr><th colspan="3">No.'+num+'</th></tr>';
				
				Object.keys(this[posId]).forEach(function(face){
					Object.keys(this[face]).forEach(function(idx){
						var info = this[idx],
							optName = printOption.hasOwnProperty(info.method)? '<br>'+printOption[info.method][info.option]: '',
							inkCount = info.ink==4? '4色以上': info.ink+'色';
						
						printInfo += '<tr class="line_col"><td class="line_lft">位置</td><td class="line_lft">色数</td><td>プリント方法</td></tr><tr><td class="line_lft">'+info.area+'</td><td class="line_lft">'+inkCount+'</td>' +
							'<td>'+printName[info.method]+'<br>'+printSize[info.method][info.size]+optName+'</td></tr>';
					}, this[face]);
				}, this[posId]);
			}, designs[designId]);
			
			$(cartBox[designIndex]).children('.item_info_order_2').children('.print_info').children('tbody').html(printInfo);

			// .cart_boxにデザインIDを設定
			$(cartBox[designIndex]).data('designId', designId);

			p = p.then(function(designId, designIndex){
				var itemWrap = $(cartBox[designIndex]).children('.item_wrap'),
					itemCount = Object.keys(items[designId]).length,	// アイテム種類の数
					itemIndex = 0,										// アイテムの表示要素のインデックス
					orderItem = $.itemPrice(items, designId),
					itemAmount = orderItem.amount,						// 当該デザインパターンに対応するアイテム数
					itemCost = orderItem.price,							// 当該デザインパターンに対応するアイテム単価の小計
					priceMin = $(cartBox[designIndex]).children('.cart_price_min'),
					argsPrint = {'design': {}, 'item':{}};

				// アイテム毎の表示要素を作成
				for (i=0; i<itemCount-1; i++) {
					itemWrap.clone().insertAfter(itemWrap);
				}

				// コピー後に要素集合を再設定
				itemWrap = $(cartBox[designIndex]).children('.item_wrap');

				// アイテム別に集計
				Object.keys(items[designId]).forEach(function (itemId) {
					var itemInfo = $(itemWrap[itemIndex]).children('.color_diff').children('.item_info_order'),
						colorCount = Object.keys(this[itemId]['color']).length,
						categoryCode = $.categories[this[itemId]['cateId']]['code'],
						itemCode = this[itemId]['code'],
						itemName = this[itemId]['name'];

					// .item_wrapにアイテムIDを設定
					$(itemWrap[itemIndex]).data('itemId', itemId);
					
					// アイテム名とアイテムコード
					$(itemWrap[itemIndex]).children('.item_name_box').find('.code').text(itemCode);
					$(itemWrap[itemIndex]).children('.item_name_box').find('.name').text(itemName);
					
					// プリント変更ボタンのテキストにNo.を表記
					$(itemWrap[itemIndex]).find('.alter_print').text('プリントNo.'+printNumber[designId][this[itemId]['posId']]+'の変更');
					if (printNumber[designId][this[itemId]['posId']]) {
						$(itemWrap[itemIndex]).find('.item_info_order_3').show();
					} else {
						// プリントなしは「プリント変更」ボタン非表示
						$(itemWrap[itemIndex]).find('.item_info_order_3').hide();
					}
					

					// カラー毎の表示要素を作成
					for (i=0; i<colorCount-1; i++) {
						itemInfo.clone().insertAfter(itemInfo);
					}

					// コピー後に要素集合を再設定
					itemInfo = $(itemWrap[itemIndex]).children('.color_diff').children('.item_info_order');

					// アイテムカラー別に集計
					let itemInfoNumber = 0;
					for (let i in this[itemId]['color']) {
						var colorName = this[itemId]['color'][i]['name'],
							colorCode = this[itemId]['color'][i]['code'],
							sizeCount = '';

						Object.keys(this[itemId]['color'][i]['vol']).forEach(function (sizeName) {
							sizeCount += '<tr>';
							sizeCount += '<td class="line_lft">'+sizeName+'</td>';
							sizeCount += '<td>'+this[sizeName]['amount']+'枚</td>';
							sizeCount += '</tr>';

						}, this[itemId]['color'][i]['vol']);

						// サイズと枚数
						$(itemInfo[itemInfoNumber]).children('.size_count').children('tbody').append(sizeCount);
						// アイテムカラー名
						$(itemInfo[itemInfoNumber]).children('.item_color_cart').children('.color_name').text(colorName);
						// サムネイル
						$(itemInfo[itemInfoNumber]).children('.item_color_cart').children('.thumb').attr('src', IMG_PATH + 'items/list/' + categoryCode + '/' + itemCode + '/' + itemCode + '_' + colorCode + '.jpg');
						
						itemInfoNumber++;
					}

					itemIndex++;

				}, items[designId]);

				// 当該デザインパターンのプリント代を取得
				argsPrint.design[designId] = designs[designId];
				argsPrint.item[designId] = items[designId];
				return $.printCharge(argsPrint).then(function(printFee){
					// プリント代を集計
					var price = 0;

					// プリント方法ごとのプリント代を集計
					Object.keys(printFee.price).forEach(function(method){
						price += this[method]-0;
					}, printFee.price);

					// プリント代総合計
					totPrintFee += price;

					// アイテム別の枚数と小計（税抜）を表示
					price += itemCost;
					priceMin.children('p:eq(0)').children('span').text(itemAmount);
					priceMin.children('p:eq(1)').children('span').text(price.toLocaleString('ja-JP'));

					// 全てのデザインパターンのアイテム代とプリント代の合計（税抜）
					grandTotal += price;
					
					// インクジェットの濃淡色の違いによりプリント代が違う旨を表示
					if ($.inkjetNotice(printFee)) {
						$('#cart .price_box .inkjet_notice').prop('hidden', false);
					} else {
						$('#cart .price_box .inkjet_notice').prop('hidden', true);
					}
				});
			}.bind(null, designId, designIndex));

			designIndex++;
		}, designs);

		p.then(function(){
			var price = Math.floor(grandTotal * (1 + $.tax)),	// 税込金額
				sum = $.getStorage('sum');

			// 全てのデザインパターンのアイテム代とプリント代の合計（税込）
//			$('#cart > .price_box .total_p span').text(price.toLocaleString('ja-JP'));

			// 大口注文割引の表示切り替え
//			if (sum.mass!=1) {
//				$('#cart > .price_box p:eq(1)').addClass('hidden');
//			} else {
//				$('#cart > .price_box p:eq(1)').removeClass('hidden');
//			}

			// プリント代合計を返す
			d.resolve(totPrintFee);
		});
		return d.promise();
	};
	
	
	/**
	 * カートページの集計と再表示
	 * @return Promise object
	 * -----
	 * 注文枚数が０の場合Step1へ
	 * 選択中の商品とカートのデータと合わせて再計算
	 */
	$.extend({
		resetCart: function() {
			var d = $.Deferred(),
				designs = $.getStorage('design'),
				items = $.getStorage('item'),
				orderItem = $.itemPrice(items),
				sum = {};

			if ($.curr.designId!=='') {
				if (Object.keys($.curr.design[$.curr.designId]).length!==0) {
					designs = $.setStorage('design', $.curr.design);
					items = $.setStorage('item', $.curr.item);
					orderItem = $.itemPrice(items);
				}

				sum = $.setStorage('sum', {'item': orderItem.price, 'volume': orderItem.amount});

				if (orderItem.amount==0) {
					$.setStorage('sum', {'item':0, 'volume':0, 'mass':0, 'print':0, 'tax':0, 'total':0});
					$.estimate().then(function(){
						d.reject();
					});
				} else {
					// 量販単価の適用を判定
					if (sum.volume > 149 && sum.mass!=1) {
						sum.mass = 1;		// 量販単価
					} else if (sum.volume < 150 && sum.mass==1){
						sum.mass = 0;		// 通常単価
					}

					// 商品単価を確認してカート表示を更新
					$.when(
						$.setMassCost(items, sum.volume)
					).then(function(items){
						items = $.setStorage('item', items);
						
						// 注文商品の金額と枚数及び量販単価の適用判定を更新
						orderItem = $.itemPrice(items);
						sum.item = orderItem.price;
						sum.volume = orderItem.amount;
						sum = $.setStorage('sum', sum);
						
						return setCart(designs, items);
					}).then(function(totPrintFee){
						// プリント代合計を更新
						sum.print = totPrintFee;
						sum = $.setStorage('sum', sum);

						// 見積もり詳細
						return $.estimate();
					}).then(function(){
						d.resolve();
					});
				}
			} else {
				$.setStorage('sum', {'item':0, 'volume':0, 'mass':0, 'print':0, 'tax':0, 'total':0});
				$.estimate().then(function(){
					d.reject();
				});
			}

			return d.promise();
		}
	});
	
	
	/**
	 * カート情報を変更する際の注文データ{@code $.curr}の指定
	 * @param self ボタン要素のjQuery object {@code $(this)}
	 * @param itemId アイテムを指定、別アイテムを選ぶ場合は未指定
	 */
	var setCurrent = function(self, itemId){
		var designId = self.closest('.cart_box').data('designId'),
			designs = $.getStorage('design'),
			items = $.getStorage('item'),
			posId = 0,
			categoryId = itemId? items[designId][itemId]['cateId']: 0;

		// 現在の選択状態を設定
		$.curr.designId = designId;
		$.curr.itemId = itemId;

		// デザインとアイテムの現在の指定情報を再設定
		$.curr.design = {};
		$.curr.design[designId] = {};

		$.curr.item = {};
		$.curr.item[designId] = {};
		
		if (categoryId) {
			// アイテム、絵型、カテゴリを設定
			
			$.curr.item[designId][itemId] = items[designId][itemId];
			$.curr.posId = items[designId][itemId]['posId'];
			$.curr.category = {};
			$.curr.category[categoryId] = {
				"code": $.categories[categoryId]['code'],
				"name": $.categories[categoryId]['name']
			};
			
			// 当該デザインパターンに対応するアイテムの絵型情報を設定
			Object.keys(designs[designId]).forEach(function(posId){
				if (posId==$.curr.posId) {
					$.curr.design[designId][posId] = designs[designId][posId];
					return;
				}
			});

		} else {
			// カテゴリーが未指定の場合
			$.curr.posId = 0;
			$.curr.category = {};
		}
	}
	
	
	// アイテムのカラー、サイズ、枚数を編集
	$('#cart').on("click", '.cart_box .ch_btn', function(){
		var self = $(this),
			itemId = self.closest('.item_wrap').data('itemId');
		
		// 現在の選択情報を更新
		setCurrent(self, itemId);
		
		// カラー、サイズ、枚数の選択ページへ
		setItemDetail().then(function(){
			$.prev(2);	// ページ遷移
		});
	});
	
	
	// アイテムカラーを削除
	$('#cart').on("click", '.cart_box .del_btn', function(){
		var self = $(this),
			cartBox = self.closest('.cart_box'),
			itemWrap = self.closest('.item_wrap'),
			designId = cartBox.data('designId'),
			itemId = itemWrap.data('itemId'),
			itemName = itemWrap.children('.item_name_box').find('.name').text(),
			colorName = self.closest('.item_info_order').find('.color_name').text();
		
		$.dialogBox('<p>'+itemName+'<br>カラー： '+colorName+'<br>を全て削除します。よろしいですか？', 'カラー削除', 'OK', 'Cancel').then(function(designId, itemId, colorName){
			var items = $.getStorage('item'),
				designs,
				sum = {},
				len = Object.keys(items[designId][itemId]['color']).length,
				target = {},
				orderItem = {},
				totAmount = 0;

			// 削除するアイテムを確定
			target[designId] = {};
			if (len>1) {
				for (let i in items[designId][itemId]['color']) {
					if (items[designId][itemId]['color'][i]['name']==colorName) {
						target[designId][itemId] = {'color': {'name':colorName}};

						// Step1の表示更新
						$('#item_info .pane').each(function(){
							if (colorName == $(this).find('.note_color').text()) {
								$(this).remove();
								return false;
							}
						});

						// 一番最初の.pane の削除ボタンを非表示
						if ($('#item_info .pane:first').children('.del_item_color').length) {
							$('#item_info .pane:first').children('.del_item_color').remove();
						}

						// 枚数合計を再計算
						$('#item_info .pane').each(function () {
							totAmount += $(this).find('.size_table').siblings('.btmline').children('.cur_amount').text().replace(',','')-0;
						});

						$('#tot_amount').text(totAmount.toLocaleString('ja-JP'));
						break;
					}
				}
			} else {
				target[designId][itemId] = {};
			}

			// カートデータを削除して現在情報を更新
			$.curr.item = {};
			$.curr.design = {};
			items = $.removeStorage('item', target);
			designs = $.getStorage('design');
			if (Object.keys(items).length==0) {
				// 全てのアイテムを削除
				$.curr.designId = '';
				$.curr.category = {};
				$.curr.item = {};
				$.curr.design = {};
			} else {
				$.curr.designId = Object.keys(items)[0];
				$.curr.item[$.curr.designId] = items[$.curr.designId];
				$.curr.design[$.curr.designId] = designs[$.curr.designId];
			}

			// カート再表示
			$.resetCart().fail(function(){
				// カートに商品がない場合
				$.prev(0);
			});

		}.bind(null, designId, itemId, colorName));
	});
	
	
	// プリントの変更
	$('#cart').on("click", '.cart_box .alter_print', function(){
		var self = $(this),
			itemId = self.closest('.item_wrap').data('itemId');

		// 現在の選択情報を更新
		setCurrent(self, itemId);
		
		// プリント指定へ
		showPrinting(2).then(function(){
			$('#goto_cart').next('.step_prev').addClass('hidden');
			$.prev();	// ページ遷移
		});
	});
	
	
	// 同じデザインで別アイテムを追加
	$('#cart').on("click", '.cart_box .add_item', function(){
		setCurrent($(this));	// 現在の選択情報を更新
		$.prev(0);	// Step1 カテゴリー選択へ
	});
	
	
	// 別デザインで追加
	$('#add_design').on("click", function(){
		var designs = $.getStorage('design'),
			items = $.getStorage('item'),
			designId = '0';
//			maxId = Object.keys(designs).reduce(function(prev, curr){ 
//				return prev.split('_')[1]-0 < curr.split('_')[1]-0 ? curr: prev; }).split('_')[1]-0,
//			designId = 'id_'+(maxId+1);

		// 現在の選択状態を更新
		$.curr.designId = designId;
		$.curr.itemId = 0;

		$.curr.design = {};
		$.curr.design[designId] = {};

		$.curr.item = {};
		$.curr.item[designId] = {};

		$.curr.posId = 0;
		$.curr.category = {};

		$.prev(0);	// Step1 カテゴリー選択へ
	});
	
	
	// イメ画作成の選択
	$('#imega input').applyChange(function(){
		var date = '',
			sum = $.getStorage('sum');
		
		if ($('#imega input[name="imega"]:checked').val()==1) {
			if ($('#pack input[name="pack"]:checked').val()==50 && sum.volume>9) {
				date = '+6day';
			} else {
				date = '+5day';
			}
			$('#imega_ahead').removeClass('hidden');
		} else {
			if ($('#pack input[name="pack"]:checked').val()==50 && sum.volume>9) {
				date = '+3day';
			} else {
				date = '+2day';
			}
			$('#imega_ahead').addClass('hidden');
		}
		$('#datepick').datepickCalendar({
			minDate: date
		});
	});
	
	
	// 割引
	$('#discount input').applyChange(function(){
		if ($('#discount input[name="student"]').is(':checked') && $('#discount input[name="school"]').val()!=='') {
			$('#payment input[value="later_payment"]').prop('disabled', true);
			if ($('#payment input[name="payment"]:checked').val()==='later_payment') {
				$('#payment input[name="payment"]').val(['bank']);
			}
		} else {
			$('#payment input[value="later_payment"]').prop('disabled', false);
		}
	});
	
	
	// 袋詰め
	$('#pack input').applyChange(function(){
		var date = '',
			sum = $.getStorage('sum');
		
		if ($('#pack input[name="pack"]:checked').val()==50 && sum.volume>9) {
			if ($('#imega input[name="imega"]:checked').val()==1) {
				date = '+6day';
			} else {
				date = '+3day';
			}
		} else {
			if ($('#imega input[name="imega"]:checked').val()==1) {
				date = '+5day';
			} else {
				date = '+2day';
			}
		}
		$('#datepick').datepickCalendar({
			minDate: date
		});
	});
	
	
	// 支払い方法
	$('#payment input').applyChange();
	
	
	// お届け先
	$('#transport').applyChange();
	
	
	// お届け時間
	$('#deliverytime').applyChange();
	
	
	// デザインについての要望
	$('#note_design').applyChange();
	
	// デザインキー
	$('#designkey_text').applyChange();
	
	// ご意見・ご要望
	$('#note_user').applyChange();
	
	// デザイン掲載の承諾
	$('#published input').applyChange();
	
	// 会員と初めての方の表示切り替え
	$('#goto_customer').on("click", function(){
		// 学割の場合の学校名は必須
		if ($('#discount input[name="student"]').is(':checked') && $('#discount input[name="school"]').val()=='') {
			$.msgbox('学校名を入力してください');
			return;
		}
		
		// イメ画作成の選択は必須
		if (!$('#imega input[name="imega"]').is(':checked')) {
			$.msgbox('イメージ画像についてご指定ください');
			return;
		}
		
		// 納期指定
		let selectDate = $('#datepick').datepickCalendar('getDate'),
			selectMonth = $('#delivery .deli_date span:eq(0)').text();
		if (!selectDate || selectMonth==='-') {
			// 2018-07-18 必須確認を一時廃止
//			$.msgbox('納期をご指定ください');
//			return;
		}
		
		$('#customer > div').addClass('hidden');
		
		// ログイン状態を確認してページ遷移
		$.isLogin().then(
			function(me) {
				if (me) {
					// ログイン済みは顧客情報の確認へ
					$('#conf_deli_wrap').addClass('hidden');
					$('#member_shipping').removeClass('hidden');
					$.next(3);
				} else {
					// 始めての方
					$('#conf_deli_wrap').removeClass('hidden');
					$('#member_shipping').addClass('hidden');
					$.next();
				}
			},
			function() {
				$.next();		// ログイン状態が未確認
			}
		);
	});
	
	
	/**
	 * Step3 - 1
	 * 会員ログイン画面へ
	 */
	$('#goto_member').on("click", function(){
		$('#customer .member').removeClass('hidden');
		$('#customer .first_time').addClass('hidden');
		$('#customer .member input[type!="hidden"]').val('');
		
		// ページ遷移
		$.next();
	});
	
	
	// ログイン
	$('#login_btn').on("click", function(){
		$.login();
	});
	
	
	/**
	 * お届け先をご住所と同じにする
	 * 会員の方
	 */
	$('#same_as_member').on('click', function(){
//		if ($(this).is(':checked')) {
			$('#conf_deli_destination span').text($('#conf_customername span').text());
			$('#conf_deli_zipcode span').text($('#conf_zipcode span').text());
			$('#conf_deli_addr0 span').text($('#conf_addr0 span').text());
			$('#conf_deli_addr1 span').text($('#conf_addr1 span').text());
			$('#conf_deli_addr2 span').text($('#conf_addr2 span').text());
			$('#conf_deli_tel span').text($('#conf_tel span').text());
			
			$('#mem_deli_destination').val($('#conf_customername span').text());
			$('#mem_deli_zipcode').val($('#conf_zipcode span').text());
			$('#mem_deli_addr0').val($('#conf_addr0 span').text());
			$('#mem_deli_addr1').val($('#conf_addr1 span').text());
			$('#mem_deli_addr2').val($('#conf_addr2 span').text());
			$('#mem_deli_tel').val($('#conf_tel span').text());
//		} else {
//			$('#conf_deli_destination span').text('');
//			$('#conf_deli_zipcode span').text('');
//			$('#conf_deli_addr0 span').text('');
//			$('#conf_deli_addr1 span').text('');
//			$('#conf_deli_addr2 span').text('');
//			
//			$('#member_shipping input[type="text"]').val('');
//		}
	});
	
	
	// パスワード再発行
	$('#resend_pass').on("click", function(){
		var email = $('#login_email').val().trim(),
			msg = '<p>'+email+'宛にパスワードを再発行いたします。</p>';

		if (email == '') {
			$.msgbox('メールアドレスを入力してください');
			return;
		}
		
		// 再発行処理
		$.api(['users', email], 'GET', null).then(function(r){
			// メールアドレスの登録状況を確認
			if (r===true) {
				return $.dialogBox(msg, '<h3>パスワード再発行</h3>', 'OK', 'Cancel');
			} else {
				$.msgbox('このメールアドレスは登録されていません');
				return $.Deferred.reject().promise;
			}
		}).then(function(){
			// パスワード再設定
			return $.api(['users', 'pass', email], 'POST', null)
		}).then(function(pass){
			// メール送信
			if (pass!='') {
				document.forms.pass.newpass.value = pass;
				eMailer.onChanged('#newpass');
				$('#sendmail').click();
			} else {
				$.msgbox('Error: パスワードの設定ができませんでした');
			}
			
		});
	});
	
	eMailer.onComplete('#sendmail', function(){
		let email = $('#login_email').val();
		document.forms.pass.newpass.value = '';
		$.msgbox('<p>'+email+'宛にパスワードを再発行いたしました</p>');
	});
	
	/**
	 * Step3 - 1
	 * 始めての方は顧客情報登録へ
	 */
	$('#goto_firsttime').on("click", function(){
		$('#customer .member').addClass('hidden');
		$('#customer .first_time').removeClass('hidden');
		$('#customer .first_time input').val('');
		
		$('#conf_deli_wrap').removeClass('hidden');
		$('#member_shipping').addClass('hidden');

		// ページ遷移
		$.next();
	});
	
	
	/**
	 * お届け先をご住所と同じにする
	 * 初めての方
	 */
	$('#same_as_first').on('click', function(){
//		if ($(this).is(':checked')) {
			$('#deli_destination').val($('#customername').val());
			$('#deli_zipcode').val($('#zipcode').val());
			$('#deli_addr0').val($('#addr0').val());
			$('#deli_addr1').val($('#addr1').val());
			$('#deli_addr2').val($('#addr2').val());
			$('#deli_tel').val($('#tel').val());
//		}
	});
	
	
	// 顧客情報の入力内容を確認
	$('#confirm_customer').on("click", function(){
		$.confirmUser().then(function(){
			var data = {};

			data.rank = 0;
			data.email = $('#email').val();
			data.name = $('#customername').val();
			data.ruby = $('#customerruby').val();
			data.tel = $('#tel').val().replace(/[-]/gi,'');
			data.zipcode = $('#zipcode').val();
			data.addr0 = $('#addr0').val();
			data.addr1 = $('#addr1').val();
			data.addr2 = $('#addr2').val();
			data.destination = $('#deli_destination').val();
			data.delizipcode = $('#deli_zipcode').val();
			data.deliaddr0 = $('#deli_addr0').val();
			data.deliaddr1 = $('#deli_addr1').val();
			data.deliaddr2 = $('#deli_addr2').val();
			data.delitel = $('#deli_tel').val().replace(/[-]/gi,'');
			$.setStorage('user', data);

			// 顧客情報の入力内容を確認
			$.showUserConfirmation(1);
		});
	});
	
	
	// 注文情報の最終確認へ
	$('#confirm_order').on("click", function(){
		// 会員の場合にお届け先の入力チェック
		if ($('#conf_deli_wrap').is('.hidden')) {
			let memZipcode = $('#mem_deli_zipcode').val();
			
//				required = [],
//				required_list = '';
//			if ($('#mem_deli_destination').val().trim() == '') required.push('<li>お届け先の宛名</li>');
//			if ($('#mem_deli_addr1').val().trim() == '') required.push('<li>お届け先住所</li>');
//			required_list = '<ul class="msg">' + required.toString().replace(/,/g, '') + '</ul>';
//			if (required.length > 0) {
//				$.msgbox("必須項目の入力をご確認ください。<hr>" + required_list);
//				return;
//			} else {
				memZipcode = $.zip_mask(memZipcode);
				$('#mem_deli_zipcode').val(memZipcode);
				
				$('#conf_deli_destination span').text($('#mem_deli_destination').val());
				$('#conf_deli_zipcode span').text(memZipcode);
				$('#conf_deli_addr0 span').text($('#mem_deli_addr0').val());
				$('#conf_deli_addr1 span').text($('#mem_deli_addr1').val());
				$('#conf_deli_addr2 span').text($('#mem_deli_addr2').val());
				$('#conf_deli_tel span').text(eMailer.formatPhone($('#mem_deli_tel').val(), 'JP'));
//			}
			
			// 会員のお届け先情報を登録
			let user = $.getStorage('user');
			user.destination = $('#mem_deli_destination').val();
			user.delizipcode = $('#mem_deli_zipcode').val();
			user.deliaddr0 = $('#mem_deli_addr0').val();
			user.deliaddr1 = $('#mem_deli_addr1').val();
			user.deliaddr2 = $('#mem_deli_addr2').val();
			user.delitel = $('#mem_deli_tel').val().replace(/[-]/gi,'')
			$.setStorage('user', user);
		}
		
		var i = 0,
			f = document.forms.orderform,
			stores = ['design', 'item', 'sum', 'detail', 'option'],
			keys = ['designs', 'items', 'sum', 'details', 'opts'],
			z = {'designs':{}, 'items':{}, 'sum':{}, 'details':{}, 'opts':{}},
			tbl = $('#confirm_order .final_detail'),
			tmpTr = '<tr class="border_t"><td rowspan="1"><div class="item_name_color"><p class="code"></p><p class="name"></p><img src="" width="30%"><p class="color"></p></div></td>' + 
			'<td class="size"></td><td class="cost"></td><td><span class="amount"></span>枚</td><td><span class="price"></span>円</td></tr>',
			tbody,
			tr,
			base = 0,
			perone = 0,
			printFee = 0,
			subTotal = 0,
			orderItem = {},
			paymentName = {'bank':'銀行振込', 'cod':'代金引換', 'credit':'カード決済', 'later_payment':'後払い'},
			printName = {'silk':'シルクスクリーン',
						 'digit':'デジタルコピー転写',
						 'inkjet':'インクジェット',
						 'cutting':'カッティング',
						 'emb':'刺繍',
						 'recommend':'おまかせ'
						},
			printSize = {'silk':['通常','ジャンボ','スーパージャンボ'],
						 'digit':['大','中','小'],
						 'inkjet':['大','中','小'],
						 'cutting':['大','中','小'],
						 'emb':['大','中','小','極小'],
						 'recommend':['']},
			printOption = { 'emb':['デザイン','ネーム']},
			faceName = {'front': '前', 'back': '後', 'side': '横'},
			sampleImage = ['作成しない', '作成する'],
			address = '',
			shipping = '',
			filename = '',	// アップロードファイル名
			attach = "";	// アップロードファイルのセッション情報
		
		// アップロードファイルの確認と表示
		attach = $.getStorage('attach');
		if (attach) {
			$.each(attach, function(id, fileName){
				filename += '<p>'+fileName+'</p>';
			});
			
			f['attach'].value = JSON.stringify(attach);
		} else {
			filename = "<p>なし</p>";
			f['attach'].value = '';
		}
		$('#design_file').html(filename);

		$.estimate().then(function(){
			
			// 注文メールフォームを更新
			$('#orderfom input:not(.ticket)').remove();
			stores.forEach(function(key, index){
				z[keys[index]] = $.getStorage(key);
				f[key].value = JSON.stringify(z[keys[index]]);
			});

			// 注文アイテムの枚数と金額
			orderItem = $.itemPrice(z.items);

			// 税込合計と１枚あたり金額
			base = z.sum.total - z.sum.tax;
			perone = Math.floor(z.sum.total/z.sum.volume);

			// アイテムとプリント
			tbl.children('tbody').remove();
			Object.keys(z.designs).forEach(function(designId, designIdx){
				var printTr = '',
					printInfo = '';
				
				// ヘッダー
				tbl.append('<tbody><tr class="tabl_ttl border_t"><td>アイテム/カラー</td><td>サイズ</td><td>単価</td><td>枚数</td><td>金額</td></tr></tbody>');
				
				if (designId==='id_0') {
					printTr += '<tr class="border_t"><td colspan="5" class="pb-2"><p>プリント方法: なし</p></td></tr>';
				} else {
					Object.keys(z.designs[designId]).forEach(function(posId){
						Object.keys(this[posId]).forEach(function(face){
							Object.keys(this[face]).forEach(function(cur){
								var optName = printOption.hasOwnProperty(this[cur]['method'])? '<span class="pl-3">オプション: ' + printOption[this[cur]['method']][this[cur]['option']] + '</span><br>': '',
									inkCount = this[cur]['ink']==4? '4色以上': this[cur]['ink']+'色';
								
								printInfo += '<p>';
								printInfo += '<span class="pl-3">プリント方法: ' + printName[this[cur]['method']] + '<br>サイズ: '+printSize[this[cur]['method']][this[cur]['size']]+optName + '</span>';
								printInfo += '<span class="pl-3">プリント箇所: ' + faceName[face] + '</span>';
								printInfo += '<span class="pl-3">インク色数: ' + inkCount + '</span>';
								printInfo += '</p>';
							}, this[face]);
						}, this[posId]);
					}, z.designs[designId]);
					printTr += '<tr class="border_t"><td colspan="5" class="pb-2">'+printInfo+'</td></tr>';
				}
				
				Object.keys(z.items[designId]).forEach(function (itemId) {
					var cateId = this[itemId]['cateId'],
						categoryCode = $.categories[cateId]['code'],
						itemCode = this[itemId]['code'],
						itemName = this[itemId]['name'],
						basePath = IMG_PATH + 'items/list/' + categoryCode + '/' + itemCode + '/' + itemCode + '_',
						colorCode = '',
						colorName = '',
						vol,
						clone,
						rows = 0;

					// カラー毎にtbodyを生成
					for (let i in this[itemId]['color']) {
						tbl.append('<tbody>'+tmpTr+'</tbody>');
						tbody = tbl.children('tbody:last');
						tr = tbody.children('tr');

						vol = this[itemId]['color'][i]['vol'];
						colorName = this[itemId]['color'][i]['name'];
						colorCode = this[itemId]['color'][i]['code'];

						tr.find('.code').text(itemCode);
						tr.find('.name').text(itemName);
						tr.find('.color').text(colorName);
						tr.find('img').prop('src', basePath+colorCode+'.jpg');

						// サイズ毎にtrを生成
						rows = 0;
						Object.keys(vol).forEach(function(sizeName, idx){
							var amount = vol[sizeName]['amount']-0,
								cost = vol[sizeName]['cost']-0,
								price = cost * amount;

							if (idx>0) {
								tr.clone().appendTo(tbody);
								if (idx===1) tbody.find('tr:last td:first').remove();
								tr = tbody.find('tr:last');
							}

							tr.find('.size').text(sizeName);
							tr.find('.cost').text(cost.toLocaleString('ja-JP'));
							tr.find('.amount').text(amount);
							tr.find('.price').text(price.toLocaleString('ja-JP'));
							rows++;
						});
						tbody.find('tr:first td:first').attr('rowspan', rows);
					}
				}, z.items[designId]);
				
				// デザイン毎のプリント方法
				$(tbody).append(printTr);
				
			});

			// プリント代合計
			$.printCharge({'design':z.designs, 'item':z.items}).then(function(print){
				Object.keys(print.price).forEach(function(method){
					printFee += this[method]-0;
				}, print.price);
				$('#final_printfee').text(printFee.toLocaleString('ja-JP'));

				// 注文枚数とアイテムとプリント代の合計
				subTotal = (orderItem.price-0) + printFee;
				$('#order_amount').text((orderItem.amount).toLocaleString('ja-JP'));
				$('#sub_total').text(subTotal.toLocaleString('ja-JP'));
			});

			// プリント情報
			tbody = $('#confirm_order .print_info_final tbody');
			tbody.children('tr:gt(0)').remove();

			$('#discount_fee').text((z.details.discountfee).toLocaleString('ja-JP'));
//			$('#rank_name').text(z.details.rankname);
//			$('#rank_fee').text((z.details.rankfee).toLocaleString('ja-JP'));
			$('#carriage').text((z.details.carriage).toLocaleString('ja-JP'));
			if (z.details.expressfee!=0) {
				$('#expressfee_wrap').removeClass('hidden');
			} else {
				$('#expressfee_wrap').addClass('hidden');
			}
			$('#express_fee').text((z.details.expressfee).toLocaleString('ja-JP'));
			$('#base_price').text(base.toLocaleString('ja-JP'));
			$('#salestax').text((z.sum.tax).toLocaleString('ja-JP'));
			$('#total_estimation').text((z.sum.total).toLocaleString('ja-JP'));
			$('#perone').text(perone.toLocaleString('ja-JP'));

			// 特急と学割不可の表記（2018-01-31 併用可）
//			if (z.details.discountname.indexOf('学割')!==false && z.details.expressfee>0) {
//				$('#discount_notice').prop('hidden', false);
//				$('#discount_name').html(z.details.discountname + '<p><span class="red_mark">※</span>特急料金適用時の学割はご利用できません</p>');
//			} else {
//				$('#discount_notice').prop('hidden', true);
//				$('#discount_name').text(z.details.discountname);
//			}
			$('#discount_name').text(z.details.discountname);
			$('#pack_name').text(z.details.packname);
			$('#payment_name').text(paymentName[z.opts.payment]);
			$('#delivery_date').text(z.opts.delidate);
			$('#delivery_time').text(z.details.delitimename);
			$('#sample_image').text(sampleImage[z.opts.imega]);

			$('#final_email').text($('#conf_email').text());
			$('#final_customername').text($('#conf_customername').text());
			$('#final_customerruby').text($('#conf_customerruby').text());
			$('#final_tel').text(eMailer.formatPhone($('#conf_tel').text(), 'JP'));
			address = $('#conf_zipcode').text() + ' ' + $('#conf_addr0').text() + $('#conf_addr1').text() + $('#conf_addr2').text();
			$('#final_address').text(address);
			shipping = $('#conf_deli_destination').text() + '<br>' + $('#conf_deli_zipcode').text() + ' ' + $('#conf_deli_addr0').text() + $('#conf_deli_addr1').text() + $('#conf_deli_addr2').text();
			$('#final_shipping').html(shipping);
			$('#final_deli_tel').html(eMailer.formatPhone($('#conf_deli_tel span').text(), 'JP'));
			$('#final_note_design').text($('#note_design').val());
			$('#final_designkey_text').text($('#designkey_text').val());
			$('#final_note_user').text($('#note_user').val());
		}).then(function(){
			$.next();
		});
	});
	
	
	// ご利用規約に同意
	$('#agree').on('change', function(){
		if ($(this).prop('checked')) {
			$('#order').prop('disabled', false);
		} else {
			$('#order').prop('disabled', true);
		}
	});
	
	
	// 注文する
	$('#order').on("click", function(){
		var u = $.getStorage('user'),
			o = $.getStorage('option'),
			f = document.forms.orderform;
		if (!$('#agree').prop('checked')) {
			$.msgbox('ご利用規約ご確認の上、【...同意しました】をチェックしてください。');
		} else {
			u.pass = $('#pass').val();
			f.user.value = JSON.stringify(u);
			f.option.value = JSON.stringify(o);
			sessionStorage.removeItem('attach');
			sessionStorage.removeItem('dl_token');
			f.submit();
		}
	});


	/**
	 * 初期設定
	 *----------
	 * カテゴリーマスター
	 * 消費税
	 * プリント指定のdata属性の設定
	 * ページ遷移プラグイン
	 * 指定項目を初期化
	 * カートデータ（見積もり、オプション）
	 */
	;(function () {
		var i = 0, d,
			args = [],
			tags,
			designs,
			items,
			opt,
			sum = $.getStorage('sum'),
			attach,
			file,
			qs = {},
			li = '',
			date = '+1day';
		
		// プリント指定のdata属性値を設定
		$('#printing .pane:first').data('idx', 0);
		
		// ページ遷移の設定
		$.initPageTransition('.contents', '.step');
		
		// プリントなし
		$('#noprint').prop('checked',false);
		
		// 割引の初期化
		$('#discount input[type="checkbox"]').prop('checked',false);
		$('#discount input[name="school"]').val('');
		
		// イメ画指定のコメント非表示
		$('#imega_ahead').addClass('hidden');
		
		// 袋詰め
		$('#pack input[name="pack"]').val(['0']);
		
		// 支払い方法
		$('#payment input[name="payment"]').val(['bank']);
		
		// お届け時間
		$('#deliverytime').val(0);
		
		// ご要望
		$('textarea').val('');
		
		// 利用規約の同意チェック
		$('#agree').prop('checked', false);
		
		// デザインとアイテム
		designs = $.getStorage('design');
		items = $.getStorage('item');
		if (designs) {
			Object.keys(designs).forEach(function(designId){
				if (Object.keys(designs[designId]).length===0) {
					delete designs[designId];
					
					if (Object.keys(items[designId]).length!==0) {
						delete items[designId];
					}
				}
			});
			if (Object.keys(designs).length===0) {
				sessionStorage.removeItem('design');
				sessionStorage.removeItem('item');
			}
		}
		
		// ユーザー
		if (!sessionStorage.hasOwnProperty('user')) {
			$.removeStorage('user', {'id':0, 'rank':0});
		}
		
		// オプション指定
		if (!sessionStorage.hasOwnProperty('option')) {
			$.removeStorage('option', {'publish':0, 'published':0, 'student':0, 'pack':0, 'payment':'bank', 'delidate':'', 'delitime':0, 'express':0, 'transport':1, 'school':'', 'note_design':'', 'designkey_text':'','note_user':'', 'imega':0});
		} else {
			opt = $.getStorage('option');
			
			// ホームページやSNSでデザインを掲載
			if (opt.published != 0) {
				$('#published [name="published"][value="'+opt.published+'"]').prop('checked', true);
			}
			
			// 学割
			if (opt.student != 0) {
				$('#discount [name="student"]').prop('checked', true);
			}
			
			// 学校名
			if (opt.school != 0) {
				$('#discount [name="school"]').val(opt.school);
			}
			
			// 写真掲載割
			if (opt.publish != '') {
				$('#discount [name="publish"]').prop('checked', true);
			}
			
			// イメ画指定
			$('#imega [name="imega"][value="'+opt.imega+'"]').prop('checked', true);
			
			// 袋詰め
//			if (opt.pack != 0) {
				$('#pack [name="pack"][value="'+opt.pack+'"]').prop('checked', true);
//			}
			
			// 支払い方法
			$('#payment [name="payment"][value="'+opt.payment+'"]').prop('checked', true);
			
			// お届け希望日
			if (opt.delidate != '') {
				d = opt.delidate.split('-');
				$('#delivery .deli_date span').each(function(idx){
					$(this).text(d[(idx+1)]);
				});
			}
			
			// お届けに２日かかるかどうか
			if (opt.transport != 1) {
				$('#transport').prop('checked', true);
			}
			
			// 配達時間指定
			$('#deliverytime').val(opt.delitime);
			
			// デザインに関するご要望
			if (opt.note_design != '') {
				$('#note_design').val(opt.note_design);
			}
			
			// デザインキー
			if (opt.designkey_text != '') {
				$('#designkey_text').val(opt.designkey_text);
			}

			
			// ご意見・ご要望
			if (opt.note_user != '') {
				$('#note_user').val(opt.note_user);
			}
		}
		
		// カレンダー
		if (opt) {
			if (opt.imega==1) {
				if (opt.pack==50 && sum.volume>9) {
					date = '+6day';
				} else {
					date = '+5day';
				}
				$('#imega_ahead').removeClass('hidden');
			} else {
				if (opt.pack==50 && sum.volume>9) {
					date = '+3day';
				} else {
					date = '+2day';
				}
				$('#imega_ahead').addClass('hidden');
			}
		}
		$('#datepick').datepickCalendar({
			minDate: date,
			pick: (opt && opt.delidate)? opt.delidate: '',
			onSelect: function(dateText){
				var data = {'delidate': dateText},
					d = dateText===''? ['','-','-']: dateText.split('-');
				$('#delivery .deli_date span').each(function(idx){
					$(this).text(d[(idx+1)]);
				});
				$.setStorage('option', data);
				$.estimate();
			},
			holiday: [{'from':'2018-12-27', 'to':'2019-01-04'}]
		});
		
		// 見積もり詳細
		if (!sessionStorage.hasOwnProperty('detail')) {
			$.removeStorage('detail', {'discountfee':0, 'discountname':'', 'packfee':0, 'packname':'', 'carriage':0, 'codfee':0, 'paymentFee':0, 'expressfee':0, 'expressname':'', 'rankname':''});
		}
		
		// 合計値
//		sessionStorage.removeItem('sum');
		
		// 他のページからの遷移でカートを表示
		function showCart() {
			$.curr.designId = 0;
			$.curr.category = {};
			$.curr.item = {'0':{}};
			$.curr.design = {'0':{}};

			$.resetCart().then(
				function(){
					$.next(4);	// カートへ
				},
				function(){
					$.msgbox('カートに商品はありません。');
				}
			).then(function(){
				// カテゴリ一覧フェードイン
				$('#categories .fade').addClass('in');
			});
		}
		
		// クエリストリングを取得
		qs = $.queryString.parse();
		
		// カテゴリーマスター
		$.api(['categories', 0], 'GET', function (r) {
			r.forEach(function (val, idx, ary) {
				$.categories[val.id] = val;
			});
		}).then(function(){
			// 消費税率
			$.api(['taxes'], 'GET', function (r) { $.tax = r/100; });
		}).then(function(){
			if (Object.keys($.categories).length===0) {
				$.msgbox('ネットワークの通信エラーが発生しています。<br>恐れ入りますが、再読み込みをおこなってください。');
			} else {
				
				// カート再計算
				$.estimate();
				
				// 他のページから遷移してきた場合
				if (Object.keys(qs).length>0 && _UPDATED==1) {
					// カートを見るボタンによる表示
					showCart();
				} else if (_UPDATED==3 && _ITEM_ID) {
					// アイテム詳細ページのその場で見積もりからの遷移
					showCart();
				} else if (_UPDATED==2 && _ITEM_ID) {
					// アイテム詳細ページからの遷移
					$.curr.designId = '0';
					$.curr.itemId = 0;
					$.curr.category = {};
					$.curr.category[_CATEGORY_ID] = {
						"code": $.categories[_CATEGORY_ID]['code'],
						"name": $.categories[_CATEGORY_ID]['name']
					};
					$.curr.item = {'0':{}};
					$.curr.design = {'0':{}};

					args = ['categories', _CATEGORY_ID];
					if (_CATEGORY_ID == 4) {
						// スポーツウェアのみ例外
						tags = [73];
						args[1] = 0;
					}

					// 絞り込みの条件表示を初期化
					$('#tag').text('');

					// アイテム一覧ページを生成
					$.api(args, 'GET', showItem, tags).then(function(){
						// アイテムの基本情報を取得
						return $.api(['items', _ITEM_ID], 'GET', null);
					}).then(function(rec){
						var r = rec[0],
							data = ['', _ITEM_ID, r.position_id, r.volumerange_id, r.silkscreen_id, r.code, r.name];

						// カラー、サイズ、枚数の選択ページを生成
						return setItemDetail(data);
					}).then(function(){
						$.next(2);
						
						// カテゴリ一覧フェードイン
						$('#categories .fade').addClass('in');
					});
				} else {
					// カテゴリ一覧フェードイン
					$('#categories .fade').addClass('in');
				}
			}
		});

		// アップロードファイルの再表示
		attach = $.getStorage('attach');
		if (Object.keys(Object(attach)).length > 0) {
			$.each(attach, function(upid, fileName){
				li += '<tr><td>'+fileName+'</td><td><button class="btn btn-outline-danger btn-sm del" data-upid="'+upid+'">削除</button></td></tr>';
			});
			$('.upload_list tbody').html(li);
		}
		
		// 選択ファイルの表示クリア
		if ($('.upload_form .filenames').length > 0) {
			document.querySelector('.upload_form .input-group .filenames').value = '';
		}

	})();

});
