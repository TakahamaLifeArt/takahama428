/*
*	Takahama Life Art
*	イラレテンプレートページの入稿フォーム
*/

$(function(){
	/*
	 * ファイルのダウンロード
	 */
	$('#container .section .box').on('click', function(){
		var f = document.forms.downloader;
		var filename = $(this).attr('alt');
		f.downloadfile.value = "design/illust/tmp_"+filename+".ai";
		f.submit();
	});
});
