<?php
/*
* 	ファイルをダウンロード
*/
	require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
	if(isset($_POST['downloadfile'], $_POST['act'])){
		if($_POST['act']=="dummyimage"){
			//$webpath = _GUEST_IMAGE_PATH.basename($_POST['downloadfile']);
			$webpath = $_POST['downloadfile'];
		
			if(is_file($webpath)){
				download_file($webpath);
				if(realpath($webpath)) unlink($webpath);
			}
		}elseif($_POST['act']=="done"){
			$res = webpathFor($_POST['downloadfile']);
			
			if(!$res){
				header("Location: "._DOMAIN);
				$webpath = $_POST['downloadfile'];
			}else{
				list($webpath, $webpath2) = $res;
			}
			
		}elseif($_POST['act']=='downloader'){
			if(!is_file($_POST['downloadfile'])){
				header("Location: "._DOMAIN);
			}
			download_file($_POST['downloadfile']);
			if(realpath($_POST['downloadfile'])){
				unlink($_POST['downloadfile']);
			}
		}
	}else{
		//header("Location: "._DOMAIN);
	}
	
	
	/*
	*	ダウンロードファイルのパスを生成
	*	@file		ドキュメントルートからのフルパス
	*
	*	return		[デザイン画像のパス, イメ画のパス]
	*/
	function webpathFor($file){
		$filename = basename($file);
		$webpath = _GUEST_IMAGE_PATH.$filename;
		
		$filename2 = 'sub_'.$filename;
		$webpath2 = _GUEST_IMAGE_PATH.$filename2;
		
		$res = array($webpath, $webpath2);
		if(!file_exists(_DOC_ROOT.$webpath) || !file_exists(_DOC_ROOT.$webpath2)) $res = false;
		
		return $res;
	}
	
	
	/*
	*	ダウンロードを実行
	*/
	function download_file($path_file){
		/* ファイルの存在確認 */
		if (!file_exists($path_file)) {
			//return false;
			die("Error: File(".$path_file.") does not exist");
		}
		/* オープンできるか確認 */
		if (!($fp = fopen($path_file, "r"))) {
			//return false;
			die("Error: Cannot open the file(".$path_file.")");
		}
		fclose($fp);
		/* ファイルサイズの確認 */
		if (($content_length = filesize($path_file)) == 0) {
			//return false;
			die("Error: File size is 0.(".$path_file.")");
		}
		/* ダウンロード用のHTTPヘッダ送信 */
		header("Content-Disposition: attachment; filename=\"".basename($path_file)."\"");
		header("Content-Length: ".$content_length);
		header("Content-Type: application/octet-stream");
		/* ファイルを読んで出力 */
		if (!readfile($path_file)) {
			//return false;
			die("Cannot read the file(".$path_file.")");
		}
		
		return true;
	}
?>