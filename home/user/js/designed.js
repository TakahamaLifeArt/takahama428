/*
	イメージ画像
	2017-03-13	created
*/


$(function(){
	
	/********************************
	*	ダウンロードのボタンをクリック
	*
	*/
	$('#designed_wrap').on('click', '.download_btn', function(){
		var href =$(this).attr('name');
		var a = window.open(href);
	});

});
