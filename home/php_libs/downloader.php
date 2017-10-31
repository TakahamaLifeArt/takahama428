<?php
/**
 * File downloader
 *
 * useing
 * design/template_illust.php
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';

if(isset($_POST['downloadfile'], $_POST['act'])){
	if($_POST['act']=='download'){
		$target = _DOC_ROOT.$_POST['downloadfile'];
		if(!is_file($target)){
			header("Location: "._DOMAIN);
		}else{
			Downloader::done($target);
		}
	}
}


class Downloader {

	public function __construct() {}
	
	
	/**
	 * ダウンロード開始
	 * @param {string} path_file ダウンロードするファイルのパス
	 * @return {boolean} 成功：true,  失敗：false
	 * @throws {string} ファイルが存在しない場合
	 */
	public static function done(string $path_file){
		try {
			/* ファイルの存在確認 */
			if (!file_exists($path_file)) {
				// return false;
				// die("Error: File(".$path_file.") does not exist");

				throw new Exception("Error: File(".$path_file.") does not exist");
			}
			/* オープンできるか確認 */
			if (!($fp = fopen($path_file, "r"))) {
				return false;
				// die("Error: Cannot open the file(".$path_file.")");
			}
			fclose($fp);
			/* ファイルサイズの確認 */
			if (($content_length = filesize($path_file)) == 0) {
				return false;
				// die("Error: File size is 0.(".$path_file.")");
			}
			/* ダウンロード用のHTTPヘッダ送信 */
			header("Content-Disposition: attachment; filename=\"".basename($path_file)."\"");
			header("Content-Length: ".$content_length);
			header("Content-Type: application/octet-stream");
			/* ファイルを読んで出力 */
			if (!readfile($path_file)) {
				return false;
				// die("Cannot read the file(".$path_file.")");
			}
		} catch(Exception $ex) {
			$msg = $ex->getMessage();
			return $msg;
		}
		return true;
	}
}
?>