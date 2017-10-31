
$(document).ready(function() {
//    $('a[rel*=lightbox]').lightBox({
//        overlayOpacity:0.6,
//		imageLoading:'../common/js/lightbox/lightbox-ico-loading.gif',
//		imageBtnClose:'../common/js/lightbox/lightbox-btn-close.gif',
//		imageBtnPrev:'../common/js/lightbox/lightbox-btn-prev.gif',
//		imageBtnNext:'../common/js/lightbox/lightbox-btn-next.gif'
//	});
	
	$(".tbl_design_sample img").click( function(){
		var src = $(this).attr("src").replace(/\/inkjet\//, "/inkjet/popup/");
		$.msgbox('<img src="'+src+'">', 800);
	});
});
