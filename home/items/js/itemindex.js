//2018.8.30 条件レイアウト変更にともない更新
$(function () {

	// 条件で探すのチェック
	$('.tag_list_item').on('change', '[type="checkbox"]', function () {
		if ($(this).is(':checked')) {
			var f = document.forms.form_tag_search;
			var tag = this.value.split('_'); // tagid_tagtypekey
			$(f).append('<input type="hidden" name="' + tag[1] + '[]" value="' + tag[0] + '">');
			f.submit();
		} else {
			var tag_id = this.value.split("_")[0];
			$("#form_tag_search input[value=" + tag_id + "]").remove();
			document.forms.form_tag_search.submit();
		}
	});


	// 並び替え
	$('#sort').on('click', 'p', function () {
		if ($(this).is('.act')) return;
		$('#sort p').removeClass('act');
		$(this).addClass('act');
		$('#container_2 .listitems').data('ScrollPagination').restart();
	});


	// スクロールで指定位置に来たら次のコンテンツを読み込む
	$('#container_2 .listitems').ScrollPagination({
		adjustHeight: ($('.des_txt').height() + $('footer').height() + 1000),
		offsetPoint: 12
	});

});
