<?php
	$current_folder = $_REQUEST['fonttype'];
	$web_path = './designcreator/fonts/img/'.$current_folder.'/';
	
	$html = '<div id="fontlist_wrapper">';
	$html .= '<div id="fontlist_title">';
	//$html .= '<div id="close_palette" class="closeModalBox" onclick="font.close(false)"></div>';
	$html .= '<h2><img alt="フォントファミリー" src="./designcreator/img/font_family_title.png" /></h2>';
	$html .= '<div class="clear"></div></div>';
	
	$root_path = dirname(__FILE__).'/../fonts/img/'.$current_folder;
	if (is_dir($root_path)) {
	  	if ($root_dh = opendir($root_path)) {
	    	while (($file = readdir($root_dh)) !== false) {
	      		if ($file == "." || $file == ".." || $file == ".DS_Store") continue;
	      	
	      		if(is_dir($root_path."/".$file)){
		      		$path = $root_path."/".$file;
		      		$folder = $file;
		      		if ($dh = opendir($path)) {
	    				while (($file = readdir($dh)) !== false) {
	    					if( $file == "." || $file == ".." || $file == ".DS_Store" || is_dir($path."/".$file) ) continue;
	    					$fullpath = $path."/".$file;
	    					if( !getimagesize($fullpath, $info) ) continue;
						    $extension = substr(strrchr($file, '.'), 0);
							$filename = basename($fullpath, $extension);
							$font_path = $current_folder.'/'.$folder.'/'.$filename;
							$html .= '<div class="list" onclick="font.change(\''.$font_path.'\')"><img title="'.$filename.'" alt="'.$filename.'" src="'.$web_path.$folder.'/'.$file.'" /></div>';
	    				}
	    				closedir($dh);
	    			}
		      	}else{
		      		$fullpath = $root_path."/".$file;
	    			if( !getimagesize($fullpath, $info) ) continue;
					$extension = substr(strrchr($file, '.'), 0);
					$filename = basename($fullpath, $extension);
					$font_path = $current_folder.'/'.$filename;
					$html.= '<div class="list" onclick="font.change(\''.$font_path.'\')"><img title="'.$filename.'" alt="'.$filename.'" src="'.$web_path.$file.'" /></div>';
		    	}
	        
	      	}
	      	closedir($root_dh);
	      	
	    } else die ("Cannot open directory:  $root_path");
	} else die ("Path is not a directory:  $root_path");
	
	$html .= '</div>';
	echo $html;
?>
