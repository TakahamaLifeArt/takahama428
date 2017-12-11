$(function(){
	// チェックボックスのスタイル
//	$('input', '#item_search').iCheck({
//		checkboxClass: 'icheckbox_flat-orange',
//		radioClass: 'iradio_flat-orange',
//		increaseArea: '10%', // optional
//		cursor: true // optional
//	});
	
	
	// 条件で探すのチェック
	$('.tag_list_item').on('change', '[type="checkbox"]', function(){
		if ($(this).is(':checked')) {
			var f = document.forms.form_tag_search;
			var tag = this.value.split('_');	// tagid_tagtypekey
			$(f).append('<input type="hidden" name="'+tag[1]+'[]" value="'+tag[0]+'">');
			f.submit();
		} else {
			var tag_id = this.value.split("_")[0];
			$("#form_tag_search input[value="+tag_id+"]").remove();
			document.forms.form_tag_search.submit();
		}
	});
//	$('input').on('ifChecked', function(event){
//		var f = document.forms.form_tag_search;
//		f.addtag.value = this.value;	// tagid_tagtypekey
//		f.submit();
//	}).on('ifUnchecked', function(event){
//		var tag_id = this.value.split("_")[0];
//		$("#form_tag_search input[value="+tag_id+"]").remove();
//		document.forms.form_tag_search.submit();
//	});
	
	
	// 並び替え
	$('#sort').on('click', 'p', function(){
		if($(this).is('.act')) return;
		$('#sort p').removeClass('act');
		var sort = $(this).addClass('act').attr('id').split('_');
		var tagid = [];
		$('input:not(.def)', '#form_tag_search').each( function(){
			var v = $(this).val();
			if(v=="") return true;	// continue
			tagid.push(v);
		});
		
		if(typeof $.pagecache.order[sort[2]] == 'undefined'){
			var mode = "category";
			if(_IS_TAG!=0){	// チャンピオン、プーマなどブランドタグによる一覧ページでタグ指定の場合
				mode = "tag";
			}
			
			$.ajax({url:'/php_libs/pageinfo.php', async:false, type:'POST', dataType:'text', 
				data:{'act':'itemtag', 'catid':_CAT_ID, 'tagid':tagid, 'mode':mode, 'priceorder':sort[2]}, 
				success: function(r){
					var dat = r.split('|');
					var list = dat[0];	//.slice(4);	// 先頭の4バイト削除 2014-08-20
					$('.listitems').html(list);
					$.pagecache.order[sort[2]] = list;
					if(dat.length==2){
						$('.number').text(dat[1]);
						$.pagecache.num[sort[2]] = dat[1];
					}else{
						$('.number').text('0');
						$.pagecache.num[sort[2]] = 0;
					}
				}
			});
		}else{
			$('.listitems').html($.pagecache.order[sort[2]]);
			$('.number').text($.pagecache.num[sort[2]]);
		}
	});
	
	
	// 商品一覧のタグと商品数と並び順をキャッシュ
	jQuery.extend({
		pagecache:{
			'num': {},
			'order':{}
		}
	});


	//検索条件表示非表示
//	$("#searchbtn").click(function(){
//		if($("#searchcondition").hasClass("hide")){
//			$("#searchcondition").removeClass("hide");
//			$("#searchcondition_clear").removeClass("hide");
//		}else{
//			$("#searchcondition").addClass("hide");
//			$("#searchcondition_clear").addClass("hide");
//		}
//	});

	//絞り込み表示非表示
//	$("#sortbtn").click(function(){
//		if($("#sort").hasClass("hide")){
//			$("#sort").removeClass("hide");
//		}else{
//			$("#sort").addClass("hide");
//		}
//	});


	// 全ての商品で表示するタグをキャッシュ
	$.pagecache.num[0] = $('.number').html();
	$.pagecache.order['index'] = $('.listitems').html();
	
});