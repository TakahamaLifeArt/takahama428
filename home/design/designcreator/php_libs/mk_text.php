<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';

	/*
	*	ダミー画像の生成
	*	charset UTF-8
	*
	*	log		: 2013-11-14 極太ゴシックを全角に統一
	
	*/
	$adjust_size = 0.2;
	$size = 140; //$_POST['size'];
	$font = $_POST['font'];
	$message = $_POST['text'];
	$print_id = $_POST['id'];
	$color = $_POST['color'];
	$direct = $_POST['direct'];
	
	$margin_X = 10;
	$margin_Y = 5;
	
	$pane_width = 180;	// ダミー画像のデザイン表示幅（px）
	
	$latin=			"([\x20-\x7E])";
	$han_katakana=	"(\x8E[\xA6-\xDF])";
	$hiragana=		"(\xA5[\xA1-\xF6])";
	$katakana=		"(\xA4[\xA1-\xF3])";
	$kanji=			"([\xB0-\xF4])";
	$kakko=			"\xA1[\xCA-\xDB]$";
	$kutouten=		"(\xA1\xA2$|\xA1\xA3$)";
			
	if( !preg_match("/^ja/", $font) ){
		if(strlen($message) != mb_strlen($message, 'utf-8')){
			echo 'error,英字フォントでは半角英数のみ使用できます。';
			exit;
		}
	}else{
		// 和文フォントの半角英数を全角に変換
		$message = mb_convert_kana($message, 'ASKV', 'utf-8');
	}
	
	$font_path = dirname(__FILE__).'/../fonts/'.$font;
		
	$tempfile_path = $font_path.'.*';
	foreach (glob("$tempfile_path") as $tempfile) {
		$font_path = $tempfile;
		break;
	}

	if($direct=='vertical'){
		$max_len = 0;
		$lines = preg_split("/\n/", $message);
		for($c=0; $c<count($lines); $c++){
			$last_chr = mb_substr($lines[$c],-1);
			if( ord($last_chr) == 10 || ord($last_chr) == 13){ 
				$max_len = max( $max_len, mb_strlen($lines[$c], 'utf-8')-1 ); 
			}else{ 
				$max_len = max( $max_len, mb_strlen($lines[$c], 'utf-8') ); 
			}
		}
		
		$bbox = imagettfbbox($size, 0, $font_path, '東');
		$w_1 = $bbox[2]-$bbox[6];
		$h_1 = $bbox[3]-$bbox[7];
		$text_w = ($w_1 + $margin_X*6) * $c + $margin_X*2;
		$text_h = (($h_1 + $margin_Y*8) * $max_len) + $margin_Y*2;
		$im = imagecreatetruecolor($text_w,$text_h);
		imageantialias($im, true);
		$transcolor = imagecolorallocatealpha($im, 255, 255, 255, 127);
		imagefill($im, 0, 0, $transcolor);
		
		$r = intval(substr($color, 0, 2),16);
		$g = intval(substr($color, 2, 2),16);
		$b = intval(substr($color, 4, 2),16);
		$text_color = ImageColorAllocate($im, $r, $g, $b);
		
		$line_width = $w_1 + $margin_X*6;
		$x_width = $text_w - $line_width + $margin_X;
		$y = $h_1 + $margin_Y;
		
		
		for ( $i=0; $i<mb_strlen($message, 'utf-8'); $i++ ) {
			$str = mb_convert_encoding(mb_substr($message, $i, 1, 'utf-8'), 'euc-jp', 'auto');
			$text = mb_substr($message, $i, 1, 'utf-8');;
			
			if( $str=="\n" ){
				$x_width -= $line_width;
				$y = $h_1 + $margin_Y;
				continue;
			}else{
				if( preg_match("/$latin|$han_katakana/", $str) ){
					$bbox = imagettfbbox($size, 0, $font_path, $text);
					$w_1 = $bbox[2]-$bbox[6];
					$x = $x_width + ($line_width-$w_1)/2;
					imagettftext($im, $size, 0, $x, $y, $text_color, $font_path, $text);
				}else{
					if( preg_match("/$kakko/", $str) ){
						$x = $x_width + $margin_X / 3;
						imagettftext($im, $size, 270, $x, $y-$size-$margin_Y, $text_color, $font_path, $text);
					}elseif( preg_match("/$kutouten/", $str) ){
							$x = $x_width + $line_width;
							imagettftext($im, $size, 180, $x, $y-$margin_Y, $text_color, $font_path, $text);
					}else{
						if( preg_match("/$kanji|$hiragana|$katakana/", $str) ){
							$x = $x_width - $margin_X*2;
							imagettftext($im, $size, 0, $x, $y, $text_color, $font_path, $text);
						}else{
							$x = $x_width + $margin_X*2;
							imagettftext($im, $size, 0, $x, $y, $text_color, $font_path, $text);
						}
						
						
					}
				}
				$y += ($h_1 + $margin_Y*8);
			}
		}
		
	}else{
		$margin_Y *= 4;
		$bbox = imagettfbbox($size, 0, $font_path, 'jkJ');
		$h_DescendingLine = ($bbox[3]-$bbox[7]) + $margin_Y;
		$lines = preg_split("/\n/", $message);
		for($c=0; $c<count($lines); $c++){
			$line[$c] = mb_convert_encoding($lines[$c], 'utf-8', 'auto' );
			$bbox = imagettfbbox($size, 0, $font_path, $line[$c]);
			$text_h += max( $h_DescendingLine, ($bbox[3]-$bbox[7]) ) + $margin_Y;
		}
		$text = mb_convert_encoding($message, 'utf-8', 'auto' );
		$bbox = imagettfbbox($size, 0, $font_path, $text);
		$text_w = ($bbox[2]-$bbox[6])+$margin_X*4;
		if( !preg_match("/^ja/", $font) ){
			$bbox = imagettfbbox($size, 0, $font_path, 'a');
			$w_ItalicMargin = ($bbox[2]-$bbox[6])/2;
			$text_w += $w_ItalicMargin;
		}
		$im = imagecreatetruecolor($text_w,$text_h);
		imageantialias($im, true);
		$transcolor = imagecolorallocatealpha($im, 255, 255, 255, 127);
		imagefill($im, 0, 0, $transcolor);
		$r = intval(substr($color, 0, 2),16);
		$g = intval(substr($color, 2, 2),16);
		$b = intval(substr($color, 4, 2),16);
		$text_color = ImageColorAllocate($im, $r, $g, $b);
	  	
	  	$temp_filename = 'tmp'.$print_id.'_'.md5(session_id()).'.png';
		$temp_fullpath = _DOC_ROOT._GUEST_IMAGE_PATH.$temp_filename;
	
	  	if( preg_match("/^ja/", $font) ){
	  		for($c=0; $c<count($line); $c++){
				$bbox = imagettfbbox($size, 0, $font_path, $line[$c]);
		  		$y += max( $h_DescendingLine, ($bbox[3]-$bbox[7]) ) - $margin_Y;
			  	imagettftext($im, $size, 0, 2, $y, $text_color, $font_path, $line[$c]);
			  	$y += $margin_Y;
			}
		}else{
			for($c=0; $c<count($line); $c++){
				$bbox = imagettfbbox($size, 0, $font_path, $line[$c]);
		  		$y += max( $h_DescendingLine, ($bbox[3]-$bbox[7]) ) - ($margin_Y+$h/3);
			  	imagettftext($im, $size, 0, $w_ItalicMargin, $y, $text_color, $font_path, $line[$c]);
			  	$y += ($margin_Y+$h/3);
			}
		}
		
		
	}
	
	$temp_filename = 'tmp'.$print_id.'_'.md5(session_id()).'.png';
	$temp_fullpath = _DOC_ROOT._GUEST_IMAGE_PATH.$temp_filename;
	  	
	imagealphablending($im,false);
	imagesavealpha($im,true);
	imagepng($im, $temp_fullpath);
	imagedestroy($im);
	
	
	$text_w *= $adjust_size;
	$text_h *= $adjust_size;
	if(isset($_SESSION['imagefile'][$print_id])){
	
		// 古い画像を破棄
		// 2017-11-20 session_regenerate_id(true) を行わないため破棄しない
//		$old_image = _DOC_ROOT.$_SESSION['imagefile'][$print_id]['path'];
//		if(is_file($old_image)){
//			unlink($old_image);
//		}
		// セッションを更新
		$_SESSION['imagefile'][$print_id]['id'] = $print_id;
		$_SESSION['imagefile'][$print_id]['path'] = _GUEST_IMAGE_PATH.$temp_filename;
		$_SESSION['imagefile'][$print_id]['top'] = 20;	// 初期値
		//$_SESSION['imagefile'][$print_id]['left'] = round((_LIMIT_MAX_WIDTH-sqrt($text_w*$text_w+$text_h*$text_h))/2);
		$_SESSION['imagefile'][$print_id]['left'] = round(($pane_width-$text_w)/2);	// 中央寄せ
		$_SESSION['imagefile'][$print_id]['zoom'] = 100;
		$_SESSION['imagefile'][$print_id]['degree'] = 0;
		$_SESSION['imagefile'][$print_id]['width'] = $text_w;
		$_SESSION['imagefile'][$print_id]['height'] = $text_h;
		$_SESSION['imagefile'][$print_id]['type'] = "txt";
		$_SESSION['imagefile'][$print_id]['zid'] = 1;
		$_SESSION['imagefile'][$print_id]['text'] = $message;
		$_SESSION['imagefile'][$print_id]['size'] = $size;
		$_SESSION['imagefile'][$print_id]['font'] = $font;
		$_SESSION['imagefile'][$print_id]['color'] = $color;
		$_SESSION['imagefile'][$print_id]['inkname'] = $_POST['inkname'];
		$_SESSION['imagefile'][$print_id]['direct'] = $direct;
		$_SESSION['imagefile'][$print_id]['position'] = $_POST['position'];
	}else{
		$data['id'] = $print_id;
		$data['path'] = _GUEST_IMAGE_PATH.$temp_filename;
		$data['top'] = 20;	// 初期値
		//$data['left'] = round((_LIMIT_MAX_WIDTH-sqrt($text_w*$text_w+$text_h*$text_h))/2);
		$data['left'] = round(($pane_width-$text_w)/2);	// 中央寄せ
		$data['zoom'] = 100;
		$data['degree'] = 0;
		$data['width'] = $text_w;
		$data['height'] = $text_h;
		$data['type'] = "txt";
		$data['zid'] = 1;
		$data['text'] = $message;
		$data['size'] = $size;
		$data['font'] = $font;
		$data['color'] = $color;
		$data['inkname'] = $_POST['inkname'];
		$data['direct'] = $direct;
		$data['position'] = $_POST['position'];
				
		$_SESSION['imagefile'][$print_id] = $data;
	}
	
	echo _DOMAIN.'/'._GUEST_IMAGE_PATH.$temp_filename.','.$text_w.','.$text_h;
	
	exit;

?>
