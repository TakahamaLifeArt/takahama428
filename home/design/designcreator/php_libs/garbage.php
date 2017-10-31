<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';

	class Garbage {
	/*
	*	garbage collect
	*	charset utf-8
	*/
		
		
		/**
		*	garbage collector
		*
		*	@path				file path
		*	@maxlifetime		null: default
		*/
		private static function cleaner($path, $maxlifetime=null){
			/* remove editable file
			$basefile = substr(basename($path, '.png'), 3);
			$img_path = _DOC_ROOT._GUEST_IMAGE_PATH.$basefile.'.*';
			foreach (glob("$img_path") as $file_path) {
				if(exif_imagetype($file_path)){
					unlink($file_path);
				}
			}
			*/
			
			if(!empty($path) && empty($maxlifetime)){
				// remove original file
				$basefile = basename($path);
				$img_path = _DOC_ROOT._GUEST_IMAGE_PATH.$basefile;
				if(is_file($img_path)){
					unlink($img_path);
				}
			}else if(!empty($maxlifetime)){
				$img_path = _DOC_ROOT._GUEST_IMAGE_PATH.'tmp0_*';
				foreach (glob($img_path) as $filename) {
					if (filemtime($filename) + $maxlifetime < time()) {
						@unlink($filename);
					}
				}
				clearstatcache();
			}
		}
		
		
		
		/**
		*	clean it
		*
		*	@id			editor ID or position ID
		* 	@design		remove a design image if true
		*/
		public static function collect($id, $design=false){
			if( $design ){
				$path = $_SESSION['orders']['design'][$id];
				unset($_SESSION['orders']['design'][$id]);
				if(empty($path)) return;
				
				$ext = substr(strrchr($path, '?'), 0);
				if($ext != "") $path = substr($path, 0, strpos($path, $ext));
			}else{
				foreach($_SESSION['imagefile'] as $key=>$val){
					if($val['id']==$id){
						$path = $val['path'];
						unset($_SESSION['imagefile'][$key]);
						break;
					}
				}
			}
			
			self::cleaner($path);
		}
		
		
		
		/**
		*	clean all 
		*
		*	@id			editor ID or position ID
		* 	@design		remove a design image if true
		*/
		public static function CollectAll(){
			self::collect(0, true);
			self::collect(1, true);
			foreach($_SESSION['imagefile'] as $key=>$val){
				$path = $val['path'];
				self::cleaner($path);
			}
			unset($_SESSION['imagefile']);
		}
	}
?>