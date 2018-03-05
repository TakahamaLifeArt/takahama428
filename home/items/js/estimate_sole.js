/**
 * Takahama Life Art
 * 見積　（単一商品）
 * log
 * 2018-03-05 アイテム一覧ページ「その場で見積もり」の仕様変更
 */

$(function(){

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
	 * 全ての画像の読込みを完了してから処理を実行させる
	 */
	$.fn.imagesLoaded = function(callback){
		var elems = this.filter('img'),
			len = elems.length,
			blank = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";

		elems.bind('load.imgloaded',function(){
			if(--len <= 0 && this.src !== blank){
				elems.unbind('load.imgloaded');
				callback.call(elems,this);
			}
		}).each(function(){
			// cached images don't fire load sometimes, so we reset src.
			if (this.complete || this.complete === undefined){
				var src = this.src;
				// webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
				// data uri bypasses webkit log warning (thx doug jones)
				this.src = blank;
				this.src = src;
			}
		});

		return this;
	};


	// カラーとサイズ毎の枚数指定のdata属性値を設定
	$('#item_info .pane:first').data('idx', 0);


	// 枚数の変更
	$('#item_info').on('change', '.pane table tbody tr:not(".heading") td[class*="size_"] input', function () {
		var tot = 0,
			subTot = 0,
			tbl = $(this).closest('table'),
			masterId = tbl.data('masterId'),
			hash = {},
			p = $.Deferred().resolve().promise();

		if (!$.hasDuplicated()) {
			$(this).val(0);
			return;
		}

		// サイズ毎の枚数をチェック {サイズ名: 枚数}
		tbl.find('tbody tr:not(".heading") td[class*="size_"] input').each(function () {
			var amount = $(this).val() - 0;
			if (amount == 0) return true; // continue
			var param = $(this).parent().attr('class').split('_');	//[, id, name, cost]
			hash[param[2]] = {'amount':amount, 'cost':param[3], 'id':param[1]};
			subTot += amount;
		});

		tbl.siblings('.btmline').children('.cur_amount').text(subTot.toLocaleString('ja-JP'));

		// 合計枚数を更新
		$('#item_info').children('.pane').each(function () {
			tot += $(this).find('.cur_amount').text() - 0;
		});
		$('#tot_amount').text(tot.toLocaleString('ja-JP'));

		// 選択データを更新
		$.each($.curr.item[$.curr.designId][$.curr.itemId]['color'], function(index, val){
			if (masterId != val.master) return true;
			$.curr.item[$.curr.designId][$.curr.itemId]['color'][index]['vol'] = hash;	// ex: {M: {amount:10, cost:500, id:sizeID}, ...};
		});

		// 見積もり再計算
		$('#printing .pane').each(function(){
			var self = $(this),
				index = self.data('idx'),
				face = self.find('.area img').attr('alt');

			p = p.then(function(self, face, index){
				return $.printCharge($.curr, [face, index]);
			}.bind(null, self, face, index)).then(function(printFee){
				$.showPrintPrice(self, printFee);
			});
		});
	});


	// 別のアイテムカラーを追加
	$('#add_item_color').on("TAP_EVENT", function () {
		var pane = $('#item_info .pane:first'),
			clone = pane.clone(true),
			newIndex = 0,
			colorCode = pane.find('.color_sele_thumb li.nowimg img').attr('alt'),
			colorName = pane.find('.note_color').text(),
			masterId = pane.find('table').data('masterId');

		// データ属性値を設定
		$('#item_info .pane').each(function() {
			newIndex = Math.max(newIndex, ($(this).data('idx')-0));
		});
		clone.data('idx', ++newIndex);

		// サイズテーブルと枚数小計を初期化
		clone.find('table input[type="number"]').val(0);
		clone.find('.cur_amount').text('0');

		// 削除ボタンを追加
		clone.prepend('<button class="del_item_color btn btn-outline-danger waves-effect del_btn_2">カラーを削除</button>');

		clone.insertBefore($(this).parent('.btn_box'));

		// 選択データを更新
		$.curr.item[$.curr.designId][$.curr.itemId]['color'][newIndex] = {};
		$.curr.item[$.curr.designId][$.curr.itemId]['color'][newIndex]['master'] = masterId;
		$.curr.item[$.curr.designId][$.curr.itemId]['color'][newIndex]['code'] = colorCode;
		$.curr.item[$.curr.designId][$.curr.itemId]['color'][newIndex]['name'] = colorName;
		$.curr.item[$.curr.designId][$.curr.itemId]['color'][newIndex]['vol'] = {};
	});


	// アイテムカラーを削除
	$('#item_info').on("TAP_EVENT", '.del_item_color', function () {
		var pane = $(this).closest('.pane'),
			idx = pane.data('idx'),
			p = $.Deferred().resolve().promise();

		pane.slideUp('normal', function () {
			var tot = 0;

			$(this).remove();

			// 選択データを更新
			delete $.curr.item[$.curr.designId][$.curr.itemId]['color'][idx];

			// 合計枚数を更新
			$('#item_info').children('.pane').each(function () {
				tot += $(this).find('.cur_amount').text() - 0;
			});
			$('#tot_amount').text(tot.toLocaleString('ja-JP'));

			// 見積もり再計算
			$('#printing .pane').each(function(){
				var self = $(this),
					index = self.data('idx'),
					face = self.find('.area img').attr('alt');

				p = p.then(function(self, face, index){
					return $.printCharge($.curr, [face, index]);
				}.bind(null, self, face, index)).then(function(printFee){
					$.showPrintPrice(self, printFee);
				});
			});
		});
	});


	// プリント指定項目と金額表示を初期化
	$('#printing .pane:gt(0)').remove();
	$('#printing .pane').find('.print_selector').val('recommend');
	$('#printing .pane').find('.ink').val(['1']);
	$('#printing .pane').find('.print_cond').addClass('hidden').find('input').val(['0']);
	$('#printing .pane').find('.price_box_2').addClass('hidden');
	$('#printing .pane').find('.print_cond_note').addClass('hidden');
	$('.price_box .total_p span, price_box .solo_p span').text('0');


	// プリント指定のdata属性値を設定
	$('#printing .pane:first').data('idx', 0);


	// プリントなし
	$('#noprint').on('TAP_EVENT', function () {
		var val = $(this).prop('checked') ? 0 : 1,
			p = $.Deferred().resolve().promise();

		// 選択項目の表示・非表示を切り替え
		if (val !== 1) {
			$('#printing .pane').hide();
			$('#add_print').hide();
		} else {
			$('#printing .pane').show();
			$('#add_print').show();

			// 見積もり再計算
			$('#printing .pane').each(function(){
				var self = $(this),
					index = self.data('idx'),
					face = self.find('.area img').attr('alt');

				p = p.then(function(self, face, index){
					return $.printCharge($.curr, [face, index]);
				}.bind(null, self, face, index)).then(function(printFee){
					$.showPrintPrice(self, printFee);
				});
			});
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
		$.printCharge($.curr, [face, newIndex]).then(function(printFee){
			$.showPrintPrice(pane, printFee);
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
			$.printCharge($.curr, [face, index]).then(function(printFee){
				$.showPrintPrice(pane, printFee);
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
		$.printCharge($.curr, [face, index]).then(function(printFee){
			$.showPrintPrice(pane, printFee);
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

		// 見積もり再計算
		$.printCharge($.curr, [face, index]).then(function(printFee){
			$.showPrintPrice(pane, printFee);
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
		$.printCharge($.curr, [face, index]).then(function(printFee){
			$.showPrintPrice(pane, printFee);
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
		$.printCharge($.curr, [face, index]).then(function(printFee){
			$.showPrintPrice(pane, printFee);
		});
	});


	//	プリント方法のダイアログ
	$('.print_link').on("TAP_EVENT", function () {
		var msg = '';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">シルクスクリーン</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/silk.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">人気NO.1プリント！版画のように、職人が一回一回 手刷りでプリントしていきます。</p>';
		msg += '<div class="print_rec">';
		msg += '<p class=""><img src="/order/img/sp_order_print_clothes.png" width="18px">おすすめ枚数：20枚以上</p>';
		msg += '<p class=""><img src="/order/img/sp_order_print_color.png" width="18px">おすすめ色数：3色以内</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">インクジェット</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/inc.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">何色使っても料金は変わらずに一枚から作成できます。 手触りがよく、柔らかい風合いに仕上がります。</p>';
		msg += '<div class="print_rec">';
		msg += '<p class=""><img src="/order/img/sp_order_print_clothes.png" width="18px">おすすめ枚数：1~10枚</p>';
		msg += '<p class=""><img src="/order/img/sp_order_print_color.png" width="18px">おすすめ色数：フルカラー</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">デジタル転写</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/digi.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">シートに印刷をし、スタンプのように熱で圧着させるプリント方法です。 色の発色が良く、 グラデーションなど細やかな柄の再現に優れています。</p>';
		msg += '<div class="print_rec">';
		msg += '<p class=""><img src="/order/img/sp_order_print_clothes.png" width="18px">おすすめ枚数：30 枚以上</p>';
		msg += '<p class=""><img src="/order/img/sp_order_print_color.png" width="18px">おすすめ色数：フルカラー</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">カッティングプリント</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/cut.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">背番号やゼッケンへのプリントに適しています。一枚一枚デザイン を変更できるので、チームユニフォームのように背番号や名前を 一枚一枚変更したい方におすすめです。</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<div class="print_type">';
		msg += '<h3 class="print_ttl">刺繍</h3>';
		msg += '<div class="btns">';
		msg += '<p class="print_img"><img src="/order/img/emb.gif" width="100%"></p>';
		msg += '<div>';
		msg += '<p class="print_unit">糸を使って、布地の表面に文字や絵を表現します。 耐久性にも 優れ、 色落ち、高級感ある表現ができるので、企業 や店舗のユニフォームにおすすめです。</p>';
		msg += '</div>';
		msg += '</div>';
		msg += '</div>';
		msg += '<button class="pop_btn_close btn waves-effect waves-light" data-dismiss="modal"><i class="fa fa-times-circle mr-1" aria-hidden="true"></i>閉じる</button>';
		$.msgbox(msg, '<h2>プリント方法の説明</h2>');
	});


	jQuery.extend({
		focusNumber: function(my) {
			if ($(my).val()=="0") {
				$(my).val("");
			}
		},
		blurNumber: function(my) {
			if ($(my).val()=="") {
				$(my).val("0");
			}
		},
		changeThumb: function(my) {
		/**
		 * サムネイルの変更
		 */
			var colorCode = my.attr("alt"),
				colorName = my.attr("title"),
				src = my.attr('src').replace('\/list\/', '/'),
				tmp = {},
				pane = my.closest('.pane'),
				currentIdx = pane.data('idx')-0,
				colors = [],
				len = 0,
				isDuplicate = false;

			// カラーの重複チェック
			if ($.hasDuplicated(colorName)===false) return;

			$.curr.item[$.curr.designId][$.curr.itemId]['color'][currentIdx]['code'] = colorCode;
			$.curr.item[$.curr.designId][$.curr.itemId]['color'][currentIdx]['name'] = colorName;

			pane.find(".note_color").html(colorName);
			my.parent().addClass('nowimg').siblings('li.nowimg').removeClass('nowimg');
			pane.find('.item_image_big img').attr({
				'src': src,
				'alt': colorCode
			});

			// 枚数の指定内容を保持
			pane.find('table tbody tr:odd td').each( function(index){
				var sizeid = $(this).attr('class').split('_')[1];
				tmp[sizeid] = $(this).find('input.forNum').val();
			});

			// サイズテーブル更新
			$.showSizeform(_ITEM_ID, colorCode, tmp, currentIdx).then(function(){
				// 見積もり再計算
				var p = $.Deferred().resolve().promise();

				$('#printing .pane').each(function(){
					var self = $(this),
						index = self.data('idx'),
						face = self.find('.area img').attr('alt');

					p = p.then(function(self, face, index){
						return $.printCharge($.curr, [face, index]);
					}.bind(null, self, face, index)).then(function(printFee){
						$.showPrintPrice(self, printFee);
					});
				});
			});
		},
		showSizeform: function(itemId, colorCode, volume, currentIdx) {
		/**
		 * サイズごとの枚数入力フォーム
		 * @itemId			アイテムID
		 * @colorCode		アイテムカラーコード
		 * @volume			サイズIDをキーにした枚数のハッシュ
		 * @currentIdx		ラッパー（.pane）のdata属性値
		 */
			var d = $.Deferred();
			
			$.getJSON($.TLA.api+'?callback=?', {'act':'sizeprice', 'itemid':itemId, 'colorcode':colorCode, 'output':'jsonp'}, function(r){
				var pre_sizeid = 0,
					cost = 0,
					amount = 0,
					size_head = '',
					size_body = '',
					sum = 0,
					tot = 0,
					size_table = '',
					hash = {};
				
				$.each(r, function(key, val){
					if(typeof volume[val.id]=='undefined'){
						amount = 0;
					}else{
						amount = volume[val.id]-0;
						if (amount!=0) {
							hash[val['name']] = {'amount':amount, 'cost':val['cost'], 'id':val['id']};
						}
					}
					sum += amount;
					if(key==0){
						pre_sizeid = val['id'];
						cost = val['cost'];
						size_head = '<th></th><th>'+val['name']+'</th>';
						size_body = '<th data-label="1枚単価">'+(val['cost']).toLocaleString('ja-JP')+' 円</th><td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'" data-label="'+val['name']+'">';
						size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onblur="$.blurNumber(this);" /></td>';
					}else if(cost != val['cost'] || (val['id']>(++pre_sizeid) && val['id']>10)){	// 単価が違うかまたは、サイズ160以下を除きサイズが連続していない
						size_table += '<tr class="heading">'+size_head+'</tr>';
						size_table += '<tr>'+size_body+'</tr>';
						
						pre_sizeid = val['id'];
						cost = val['cost'];
						size_head = '<th></th><th>'+val['name']+'</th>';
						size_body = '<th data-label="1枚単価">'+(val['cost']).toLocaleString('ja-JP')+' 円</th><td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'" data-label="'+val['name']+'">';
						size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onblur="$.blurNumber(this);" /></td>';
					}else{
						pre_sizeid = val['id'];
						size_head += '<th>'+val['name']+'</th>';
						size_body += '<td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'" data-label="'+val['name']+'">';
						size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onblur="$.blurNumber(this);" /></td>';
					}
				});
				size_table += '<tr class="heading">'+size_head+'</tr>';
				size_table += '<tr>'+size_body+'</tr>';
				
				$('#item_info .pane').each(function(index, pane){
					var idx = $(pane).data('idx')-0,
						tbl = $(pane).find('table');

					if (idx === currentIdx) {
						tbl.data('masterId', r[0]['master_id']);
						tbl.find('tbody').html(size_table);
						tbl.siblings('.btmline').children('.cur_amount').text(sum.toLocaleString('ja-JP'));
						return false;
					}
				});
				
				// 合計枚数を更新
				$('#item_info').children('.pane').each(function () {
					tot += $(this).find('.cur_amount').text() - 0;
				});
				$('#tot_amount').text(tot.toLocaleString('ja-JP'));
				
				$.curr.item[$.curr.designId][itemId]['color'][currentIdx]['master'] = r[0]['master_id'];
				$.curr.item[$.curr.designId][itemId]['color'][currentIdx]['vol'] = hash;	// ex: {M: {amount:10, cost:500, id:sizeID}, ...};
				
//				$.addOrder(false);
				d.resolve();
			});
			return d.promise();
		},
		hasDuplicated: function(curColor=null) {
		/**
		 * アイテムカラー指定の重複確認
		 * @param {string} curColor 確認するカラーを指定する場合
		 * @return {bool} 重複している場合{@code false}、そうでない場合{@code true}を返す
		 */
			var colors = {},
				isDuplicate = false;
			
			$('#item_info .pane').each(function(){
				var colorName = $(this).find('.note_color').text();

				if (curColor) {
					if (curColor == colorName) {
						isDuplicate = true;
						return false;
					}
				} else {
					if (colors.hasOwnProperty(colorName)) {
						isDuplicate = true;
						return false;
					} else {
						colors[colorName] = true;
					}
				}
			});

			if (isDuplicate === true) {
				$.msgbox('カラーの指定が重複しています。');
				return false;
			}
			
			return true;
		}
	});


	/**
	 * 注文フォームへ遷移
	 */
	$('#btnOrder, #btnOrder_up').click( function(){
		var f = $(this).closest("form")[0],
			func = function(){
				if (f.update.value == 3) {
					// その場で見積もりから遷移
					var i = 0,
						newId = 0,
						ids = [],
						sum = $.getStorage('sum'),
						orderItem = {},	// {'price':商品金額合計 'amount':注文総枚数}
						designs = {},
						items = {},
						dummy = {},
						amount = 0;

					// 注文枚数の確認
					$('#item_info .pane').each(function(){
						amount += $(this).find('.cur_amount').text()-0;
					});
					if (amount == 0) {
						$.msgbox('枚数をご指定ください。');
						return;
					}
					
					// デザインIDの付け替え
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
					
					// 選択中のアイテムとカート内のデータをマージ
					designs = $.setStorage('design', $.curr.design);
					items = $.setStorage('item', $.curr.item);
				}
				f.submit();
			};
		
		// メーカー「ザナックス」の場合にポップアップ
		if($(this).hasClass('popup')){
			$.confbox.show(
				'<h3 class="fontred">★要確認</h3>'+
				'<div style="padding:0.5em;"><p>'+
					'このアイテムはメーカーの在庫状況が不安定な為<br>'+
					'お申し込みフォームからご指定頂きました枚数の在庫確認を行った後<br>'+
					'弊社から「在庫有無・納期」のご連絡をさせて頂きます。<br>'+
					'メーカに在庫が無い場合は受注生産となり、納期を2~3週間頂く場合がございます。'+
				'</p>'+
				'<p class="note" style="margin-bottom:1em;"><span>※</span>在庫状況によっては、ご希望に添えない場合がございます。</p>'+
				'<p style="margin-bottom:1em;">大変ご不便おかけしますが、何卒宜しくお願い致します。</p>'+
				'<p>お急ぎの方はお電話でのお問い合わせをお願いします。</p>'+
				'<address>0120-130-428</address></div>', 
				function(){
					if($.confbox.result.data){
						func();
					}else{
						// Do nothing.
					}
				}
			);
		}else{
			func();
		}
	});

	/**
	 * その場で見積もりのカラー変更
	 */
	$(".color_sele_thumb li img").on('click', function(){
		$.changeThumb($(this));
	});


	// ページ初期化
	$.api(['categories', 0], 'GET', function (r) {
		// カテゴリーのマスターデータを取得
		r.forEach(function (val, idx, ary) {
			$.categories[val.id] = val;
		});
	}).then(function(){
		// 消費税率を設定
		return $.api(['taxes'], 'GET', function (r) { $.tax = r/100; });
	}).then(function(){
		// デフォルト値を設定
		return $.api(['items', _ITEM_ID], 'GET', function(rec){
			var r = rec[0],
				self = $('#item_info .pane:first'),
				colorCode = self.find('.color_sele_thumb li.nowimg img').attr('alt'),
				colorName = self.find('.note_color').text(),
				masterId = self.find('table').data('masterId'),
				len = 0,
				hash = {};

			$.curr.designId = '0';
			$.curr.itemId = _ITEM_ID;
			$.curr.posId = r.position_id;
			$.curr.category = {};
			$.curr.category[_CAT_ID] = {
				"code": $.categories[_CAT_ID]['code'],
				"name": $.categories[_CAT_ID]['name']
			};

			$.curr.design = {'0':{}};

			$.curr.item = {'0':{}};
			$.curr.item[$.curr.designId][$.curr.itemId] = {
				"code": r.code,
				"name": r.name,
				"posId": r.position_id,
				"rangeId": r.volumerange_id,
				"screenId": r.silkscreen_id,
				"cateId": _CAT_ID,
				"color": {}
			};
			$.curr.item[$.curr.designId][$.curr.itemId]['color'][len] = {};
			$.curr.item[$.curr.designId][$.curr.itemId]['color'][len]['master'] = masterId;
			$.curr.item[$.curr.designId][$.curr.itemId]['color'][len]['code'] = colorCode;
			$.curr.item[$.curr.designId][$.curr.itemId]['color'][len]['name'] = colorName;
			$.curr.item[$.curr.designId][$.curr.itemId]['color'][len]['vol'] = hash; // ex: {M: {amount:10, cost:500, id:sizeID}, ...};

			// サムネイルのロード完了後にサイズテーブルを表示
			$.showSizeform(_ITEM_ID, colorCode, [], 0).then(function(){
				$('#color_thumb li img').imagesLoaded(function(){$('#item_colors').fadeIn();});
			});
		});
	}).then(function(){
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

			// 初めてのStep通過、または戻るボタン
			designCount++;
			target = pane;

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
		// プリント方法選択のセレクトタグを生成
		$.api(['items', $.curr.itemId, 'details'], 'GET', function (r) {
			var i = 0,
				pane = $('#printing .pane:first'),
				face = Object.keys($.curr.design[$.curr.designId][$.curr.posId])[0],
				list = '',
				len = designData.length,
				method = 'recommend';

			for(i=0; i<designCount; i++){

				// カッティングと刺繍のみ対応の商品の場合は、「おまかせ」なし
				if (r[0]['silk'] == 1 || r[0]['digit'] == 1 || r[0]['inkjet'] == 1) {
					list = '<p><select class="print_selector down_cond">';
					list += '<option value="recommend"';
					if (method=='recommend') {
						list += ' selected';
						$(target[i]).find('.price_box_2').removeClass('hidden');	// 初めてのStepはおまかせプリントが初期値
					}
					list += '>おまかせ</option>';
					if (r[0]['silk'] == 1) {
						list += '<option value="silk"';
						if (method=='silk') list += ' selected';
						list += '>シルクスクリーン</option>';
					}
					if (r[0]['digit'] == 1) {
						list += '<option value="digit"';
						if (method=='digit') list += ' selected';
						list += '>デジタル転写</option>';
					}
					if (r[0]['inkjet'] == 1) {
						list += '<option value="inkjet"';
						if (method=='inkjet') list += ' selected';
						list += '>インクジェット</option>';
					}
					if (r[0]['cutting'] == 1) {
						list += '<option value="cutting"';
						if (method=='cutting') list += ' selected';
						list += '>カッティング</option>';
					}
					if (r[0]['emb'] == 1) {
						list += '<option value="emb"';
						if (method=='emb') list += ' selected';
						list += '>刺繍</option>';
					}
				} else {
					list = '<p><select class="print_selector down_cond">';
					if (r[0]['cutting'] == 1) {
						list += '<option value="cutting"';
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
						list += '<option value="emb"';
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

			// 対応するプリント方法を設定[silk, digit, inkjet] 1:対応する、0:未対応
			$.curr.design[$.curr.designId][$.curr.posId][face]['0']['printable'] = [r[0]['silk'],r[0]['digit'],r[0]['inkjet']];
		});
	});
});
