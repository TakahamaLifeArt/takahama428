/**
*	Takahama Life Art
*	レビューページ
*	charset euc-jp
*/

$(function(){
	
	// 並び替え
	$('#cond_sortby').change( function(){
		var sort = $(this).val();
		location.href = 'http://www.takahama428.com/itemreviews/index.php?sort='+sort+'&item='+$("#item").val();
	});
});