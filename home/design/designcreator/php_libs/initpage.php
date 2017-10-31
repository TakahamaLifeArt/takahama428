<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';

/*
	if(isset($_REQUEST['itemcode'], $_REQUEST['colorcode'], $_REQUEST['colorname'], $_REQUEST['amount'], $_REQUEST['sizeid'])){
		$item_info = array($_REQUEST['colorcode'], $_REQUEST['colorname'], $_REQUEST['amount'], $_REQUEST['sizeid'], $_REQUEST['itemcode']);
	}
*/
	
	$conn = new Conndb();
	$items = $conn->itemAttr(4);	// 085-cvt
	
	// アイテムカラーのスライダーを生成
	$slider = '<ul>';
	foreach($items['code'] as $imagefilename=>$color_name){
		list($item_code, $color_code) = explode('_', $imagefilename);
		$slider .= '<li><div onmouseover="itemcolor.info(this)" onmouseout="itemcolor.undo(this)" onclick="itemcolor.change(this,\''.$color_code.'\')" id="c'.$color_code.'">';
		$slider .= '<div class="item_'.$color_code.'"></div>';
		$slider .= '<div class="checkcolor_wrapper"><img alt="'.$color_name.'" src="./designcreator/img/creator/check-multi.gif" /></div>';
		$slider .= '</div></li>';
		
		$preloaddata[] = "./designcreator/img/items/t-shirts/085-cvt/".$imagefilename.".jpg";
	}
	$slider .= '</ul>';
	
	
	// 1日以上経った画像ファイルを削除
	$maxlifetime = 60*60*24; // 1日の秒数
	// Garbage::cleaner('', $maxlifetime);
	$img_path = _DOC_ROOT._GUEST_IMAGE_PATH.'tmp0_*';
	foreach (glob($img_path) as $filename) {
		if (filemtime($filename) + $maxlifetime < time()) {
			@unlink($filename);
		}
	}
	clearstatcache();
	
?>