/**
*	Takahama Life Art
*	アイテムレビュー
*/

$(function(){
	
	// 並び替え
	$('#cond_sortby').change( function(){
		var sort = $(this).val();
		location.href = '/itemreviews/?sort='+sort+'&item='+$("#item").val();
	});
});