/**
*	Takahama Life Art
*	��ӥ塼�ڡ���
*	charset euc-jp
*/

$(function(){
	
	// �¤��ؤ�
	$('#cond_sortby').change( function(){
		var sort = $(this).val();
		location.href = 'http://www.takahama428.com/itemreviews/index.php?sort='+sort+'&item='+$("#item").val();
	});
});