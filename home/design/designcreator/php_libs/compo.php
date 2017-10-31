<?php
	ini_set('memory_limit', '128M');
	require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
	//require_once dirname(__FILE__).'/mk_image.php';
/**
* 	画像を合成して、ファイルパスを返す
*
*	position	[0]表 [1]裏
**/

	$upload_dir = _DOC_ROOT._GUEST_IMAGE_PATH;
	
	if( isset($_POST['download'], $_SESSION['imagefile'], $_POST['position']) ){
		if(isset($_POST['scalewidth'], $_POST['scaleheight'])){
			$pane_w = $_POST['scalewidth'];
			$pane_h = $_POST['scaleheight'];
		}else{
			// 200dpi 27*35cm
			$pane_w = 2126;
			$pane_h = 2756;
		}
		try{
			// display width by PC
			$dsp_w = 180;
			
			// a reduced scale of the text image
			$reduced = 0.2;
			
			// scale for actual print
			$scale = $pane_w/$dsp_w;
			
			// base pane for composite
			$pane = ImageCreateTrueColor($pane_w, $pane_h);
			imagealphablending($pane,true);
			imagesavealpha($pane,true);
			$transcolor = imagecolorallocatealpha($pane, 255, 255, 255, 127);
			imagefill($pane, 0, 0, $transcolor);
			
			for($p=0; $p<count($_POST['position']); $p++){
				$position_id = $_POST['position'][$p];
				// base pane for composite
				$pane = ImageCreateTrueColor($pane_w, $pane_h);
				imagealphablending($pane,false);
				imagesavealpha($pane,true);
				$transcolor = imagecolorallocatealpha($pane, 255, 255, 255, 127);
				imagefill($pane, 0, 0, $transcolor);
				$src="";
				foreach($_SESSION['imagefile'] as $key=>$val){
					if($val['position']!=$position_id) continue;

					// original image path
					if($val['type']=='img'){
						$src = $val['orgpath'];
					}else{
						$src = _DOC_ROOT.$val['path'];
					}
					if(!realpath($src)) continue;
					
					// create image
					list($org_w,$org_h,$imgtype)=getimagesize($src);
					switch($imgtype){
						case IMAGETYPE_GIF:		$img = ImageCreateFromGIF($src);
												break;
						case IMAGETYPE_JPEG: 	$img = ImageCreateFromJPEG($src);
												break;
						case IMAGETYPE_PNG: 	$img = ImageCreateFromPNG($src);
												break;
						default:				$img = '';
												break;
					}
					if($img=='') continue;
					
					// scale for PC display
					$adjust_PC = $val['width'] / $org_w;
					
					// rotation
					if($val['degree']!=0){
						$degree = -1*$val['degree'];
						$rot_transcolor = imagecolorallocatealpha($img, 255, 255, 255, 127);
						$img = imagerotate($img, $degree, $rot_transcolor);
						// 回転後の画像のサイズ
						$org_w = imagesx($img);
						$org_h = imagesy($img);
					}	
					
					$src_w = $org_w;
					$src_h = $org_h;
					$pc_w = $val['width'];
					$pc_h = $val['height'];
					
					// zoom
					if($val['zoom']!=100){
						$ratio = $val['zoom']/100;
						$src_w *= $ratio;
						$src_h *= $ratio;
						$pc_w  *= $ratio;
						$pc_h  *= $ratio;
					}
					
					// adjust size
					if($val['type']=='img'){
						$src_w *= $adjust_PC;
						$src_h *= $adjust_PC;
					}else{
						$src_w *= $reduced;
						$src_h *= $reduced;
					}
					$dst_w = $src_w * $scale;
					$dst_h = $src_h * $scale;
					
					// top and left position
					/*
					$across = sqrt($pc_w*$pc_w + $pc_h*$pc_h);
					$x = ($val['left'] + ($across-$src_w)/2) * $scale;
					$y = ($val['top'] + ($across-$src_h)/2) * $scale;
					*/
					$x = $val['left'] * $scale;
					$y = $val['top'] * $scale;
					
					imageCopyResampled($pane, $img, $x, $y, 0, 0, $dst_w, $dst_h, $org_w, $org_h);
				}
				
				if($src!=""){
					$filename = md5(session_id().mt_rand()).$position_id;
					$download_path = $upload_dir.$filename.'.png';
					ImagePNG($pane, $download_path);
					
					if(isset($_POST['itemimage'])){
						$item = ImageCreateFromJPEG(_DOC_ROOT.$_POST['itemimage']);
						//imageCopyResampled($item, $pane, 175, 125, 0, 0, 150, 192, 2126, 2756);
						imageCopyResampled($item, $pane, 137, 108, 0, 0, 225, 288, 225, 288);
						$download_path = $upload_dir.'sub_'.$filename.'.png';
						ImagePNG($item, $download_path);
						@imagedestroy($item);
					}

					@imagedestroy($img);
					@imagedestroy($pane);
					
					/*
					$src = $_SESSION['orders']['design'][$position_id];
					$ext = substr(strrchr($src, '?'), 0);
					if($ext != "") $src = substr($src, 0, strpos($src, $ext));
						
					// remove file
					$basefile = basename($src, '.png');
					$img_path = _DOC_ROOT._GUEST_IMAGE_PATH.$basefile.'.*';
					foreach (glob("$img_path") as $file_path) {
						if(exif_imagetype($file_path)){
							unlink($file_path);
						}
					}
					$_SESSION['orders']['design'][$position_id] = $filename.'.png';
					*/
					
				}else{
					unset($_SESSION['orders']['design'][$position_id]);
					$download_path = "";
				}
				
				if($p!=0) $result .= ','; 
				$result .= $download_path;
			}
			
		}catch (Exception $e) {
			$result = "";
			$result_msg = $e->getMessage();
		}
					
		echo $result;
	}

?>