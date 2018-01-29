/*
	message box
	log:
	2018-01-11 created

*/

$(function(){
	
	/**
	 * サイズと枚数入力ポップアップ
	 */
	$('#reorder_item').on('click', '.add_order_item', function(){
		var addButton = $(this),
			body = addButton.closest('tbody'),
			itemId = addButton.data('itemid'),
			colorCode = addButton.data('colorcode'),
			sizeData = {};
		
		// 枚数指定の状態を取得
		body.children('tr:gt(0):not(:last)').each(function(){
			var tr = $(this),
				sizeId = tr.data('size'),
				sizeName = tr.children('td:eq(0)').text(),
				cost = tr.children('td:eq(1)').children('span').text(),
				amount = tr.children('td:eq(2)').children('span').text();
			sizeData[sizeName] = {'id':sizeId, 'amount':amount, 'cost':cost};
		});
		
		$.api(['items', itemId, 'sizes', colorCode], 'GET', null).then(function(r){
			var d = $.Deferred(),
				pre_sizeid = 0,
				cost = 0,
				amount = 0,
				size_head = '',
				size_body = '',
				size_table = '';

			r.forEach(function (val, idx, ary) {
				if (sizeData.hasOwnProperty(val.name)) {
					amount = sizeData[val.name]['amount'];
				} else {
					amount = 0;
				}

				if (idx == 0) {
					pre_sizeid = val['id'];
					cost = val['cost'];
					size_head = '<th></th><th>' + val['name'] + '</th>';
					size_body = '<th>1枚単価<span class="inter">' + val['cost'].toLocaleString('ja-JP') + '</span> 円</th><td class="size_' + val['id'] + '_' + val['name'] + '_' + val['cost'] + '"><input id="size_' + val['id'] + '" type="number" value="' + amount + '" min="0" max="999"></td>';
				} else if (cost != val['cost'] || (val['id'] > (++pre_sizeid) && val['id'] > 10)) { // 単価が違うかまたは、サイズ160以下を除きサイズが連続していない
					size_table += '<tbody>';
					size_table += '<tr class="heading">' + size_head + '</tr>';
					size_table += '<tr class="data">' + size_body + '</tr>';
					size_table += '</tbody>';

					pre_sizeid = val['id'];
					cost = val['cost'];
					size_head = '<th></th><th>' + val['name'] + '</th>';
					size_body = '<th>1枚単価<span class="inter">' + val['cost'].toLocaleString('ja-JP') + '</span> 円</th><td class="size_' + val['id'] + '_' + val['name'] + '_' + val['cost'] + '"><input id="size_' + val['id'] + '" type="number" value="' + amount + '" min="0" max="999"></td>';
				} else {
					pre_sizeid = val['id'];
					size_head += '<th>' + val['name'] + '</th>';
					size_body += '<td class="size_' + val['id'] + '_' + val['name'] + '_' + val['cost'] + '"><input id="size_' + val['id'] + '" type="number" value="' + amount + '" min="0" max="999"></td>';
				}
			});
			size_table += '<tbody>';
			size_table += '<tr class="heading">' + size_head + '</tr>';
			size_table += '<tr class="data">' + size_body + '</tr>';
			size_table += '</tbody>';
			
			return d.resolve(size_table).promise();
		}).then(function(tbody){
			var msg = '<table class="size_table">' + tbody + '</table>';
			$.dialogBox(msg, '<h2>追加するサイズと枚数</h2>', '決定する', '').then(function(){
				var tr = '',
					itemCount = 0;
				
				// 初期化
				body.children('tr:gt(0):not(:last)').remove();
				
				// HTMLタグ生成
				$('#msgbox .modal-body').find('.size_table tbody').each(function(){
					var i = 0,
						len = 0,
						price = 0,
						items = [];
					$(this).find('.data td').each(function(){
						var data = $(this).attr('class').split('_'),	// ['size', id, サイズ名, 単価]
							amount = parseInt($(this).children('input').val(), 10);
						
						if (isNaN(amount)===false && amount > 0) {
							items.push({'id':data[1], 'name':data[2], 'cost':data[3], 'amount':amount});
						}
					});
					
					len = items.length;
					if (len > 0) {
						for (i=0; i<len; i++) {
							price = items[i]['cost'] * items[i]['amount'];
							tr += '<tr data-size="'+ items[i]['id'] +'">';
							tr += '<td>' + items[i]['name'] + '</td>';
							tr += '<td><span>' + items[i]['cost'] + '</span>円</td>';
							tr += '<td><span>' + items[i]['amount'] + '</span>枚</td>';
							tr += '<td><span>' + price + '</span>円</td>';
							tr += '</tr>';
						}
					}
					itemCount += len;
				});
				
				// 再描画
				addButton.closest('tr').before(tr);
				body.children('tr:eq(0)').children('td:eq(0)').attr('rowspan', itemCount+2);
				
				// 見積り計算
				$.calc();
			});
		});
	});



});
