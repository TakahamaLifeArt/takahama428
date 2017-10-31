<?php
/**
 * Send mail
 * charset "UTF-8"
 * @author (c) 2014 ks.desk@gmail.com
 * log	2017-10-14 created
 */
declare(strict_types=1);
namespace package\mail;
require_once dirname(__FILE__).'/Property.php';
class Mailer extends Property {

	private $_fromEncode = "UTF-8";
	private $_fromName = _MY_NAME;
	private $_sendFrom = _INFO_EMAIL;
	private $_mailInfo = array();	// 送信データ
	private $_mailBody = '';	// 本文
	private $_mailHead = '';	// 前文（返信用）
	private $_mailExtra = '';	// その他の記載
	
	
	/**
	 * コンストラクタ
	 * @fromEndoce {string} send関数の引数の文字コード
	 * @mailInfo {array} 送信データ{code: name=>value}
	 */
	public function __construct(string $fromEncod="", array $mailInfo=array()) {
		parent::__construct();
		if (!empty($fromEncod)) {
			$this->_fromEncode = strtoupper($fromEncod);
		}
		
		// POSTデータあり
		if (!empty($mailInfo)) {
			$this->_mailInfo['title'] = $mailInfo['tpl-title'];
			if (isset($mailInfo['tpl-head'])) $this->_mailInfo['head'] = $mailInfo['tpl-head'];
			if (isset($mailInfo['tpl-extra'])) $this->_mailInfo['extra'] = $mailInfo['tpl-extra'];
			
			// tpl-インデックス_name属性値 => 項目名
			foreach ($mailInfo as $key=>$val) {
				if (strpos($key, 'tpl-')!==0) continue;
				$keys = explode('_', $key);
				if (count($keys)!=2) continue;
				$idx = explode('-', $keys[0])[1];
				$tmp[$idx] = array(
									'lbl'=>$val,
									'txt'=>$mailInfo[$keys[1]],
									);
			}
			for ($i=0; $i<count($tmp); $i++) {
				$this->_mailInfo[$tmp[$i]['lbl']] = $tmp[$i]['txt'];
			}
			$this->setMailBody($this->_mailInfo);
		}
	}
	
	
	/**
	 * 送信者の名前を設定
	 * @args {string} 送信者名
	 */
	public function setFromName(string $args) {
		if (!empty($args)) {
			$this->_fromName = $args;
		}
	}
	
	
	/**
	 * 送信者のメールアドレスを設定
	 * @args {string} メールアドレス
	 */
	public function setSendFrom(string $args) {
		if (!empty($args)) {
			$this->_sendFrom = $args;
		}
	}
	
	
	/**
	 * 本文生成
	 * @args {array}	項目名をキーにした値の配列
	 */
	public function setMailBody(array $args) {
		if ($this->_fromEncode != 'UTF-8') {
			mb_convert_variables('UTF-8', $this->_fromEncode, $args);
			$body = "【　".$args['title']."　】\n";
			$body .= "Date: ".date('Y-m-d H:i:s')."\n\n";
			foreach ($args as $key=>$val) {
				if ($key=='title' || $key=='head' || $key=='extra') continue;
				$body .= "■".mb_convert_encoding($key, 'UTF-8', $this->_fromEncode)."：　".$val."\n\n";
			}
		} else {
			$body = "【　".$args['title']."　】\n";
			$body .= "Date: ".date('Y-m-d H:i:s')."\n\n";
			foreach ($args as $key=>$val) {
				if ($key=='title' || $key=='head' || $key=='extra') continue;
				$body .= "■".$key."：　".$val."\n\n";
			}
		}
		
		$this->_mailBody = $body;
		
		// 返信メールの前文を設定
		if (isset($args['head'])) {
			$this->_mailHead = $args['head']."\n\n";
		}
		
		// その他の記載
		if (isset($args['extra'])) {
			$this->_mailExtra = "\n\n".$args['extra'];
		}
	}
	
	/**
	 * メール送信
	 * @mailSubject {string}	件名
	 * @sendTo {array}			送信先のメールアドレスの配列
	 * @replyTo {string}		返信の有無　"":なし（default）　返信先のメールアドレス:あり
	 * @attach {array}			添付ファイル情報[{file,name,type},{},...]
	 *
	 * @return	成功：[SUCCESS] , 失敗：[送信できなかったアドレス, ...]
	 */
	public function send(string $mailSubject, array $sendTo, string $replyTo="", array $attach=array()): array {
		mb_language("japanese");
		mb_internal_encoding("UTF-8");
		
		$msg = "";						// 送信文
		$boundary = md5(uniqid()); 		// バウンダリー文字（メールメッセージと添付ファイルの境界とする文字列を設定）
		
		$from = mb_encode_mimeheader($this->_fromName,"JIS")."<".$this->_sendFrom.">";
		$header = "From: $from\n";
		$header .= "Reply-To: $from\n";
		$header .= "X-Mailer: PHP/".phpversion()."\n";
		$header .= "MIME-version: 1.0\n";
		
		if(!empty($attach)){ 			// 添付ファイルがあり
			$header .= "Content-Type: multipart/mixed;\n";
			$header .= "\tboundary=\"$boundary\"\n";
			$msg .= "This is a multi-part message in MIME format.\n\n";
			$msg .= "--$boundary\n";
			$msg .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$msg .= "Content-Transfer-Encoding: 7bit\n\n";
		}else{							// 添付ファイルなし
			$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$header .= "Content-Transfer-Encoding: 7bit\n";
		}
		
		$msg .= mb_convert_encoding($this->_mailBody.$this->_mailExtra, "JIS", "UTF-8");	// ここで本文をエンコード
		
		if(!empty($attach)){			// 添付ファイル情報
			for($i=0; $i<count($attach); $i++){
				$msg .= "\n\n--$boundary\n";
				$msg .= "Content-Type: " . $attach[$i]["type"] . ";\n";
				$msg .= "\tname=\"".$attach[$i]["name"]."\"\n";
				$msg .= "Content-Transfer-Encoding: base64\n";
				$msg .= "Content-Disposition: attachment;\n";
				$msg .= "\tfilename=\"".$attach[$i]["name"]."\"\n\n";
				$msg .= $attach[$i]["file"]."\n";
			}
			$msg .= "--".$boundary."--";
		}
		
		// 件名のマルチバイトをエンコード
		$mailSubject = mb_convert_encoding($mailSubject, "JIS", $this->_fromEncode);
		$subject  = mb_encode_mimeheader($mailSubject,"JIS");
		
		// メール送信
		$res = array();
		for($i=0; $i<count($sendTo); $i++){
			if(strpos($sendTo[$i], "@")===false){
				$res[] = $sendTo[$i];
				continue;
			}
			if(!mail($sendTo[$i], $subject, $msg, $header)){
				$res[] = $sendTo[$i];	// 失敗したアドレス
			}
		}
		
		// 返信
		if(!empty($replyTo)) {
			$msg = mb_convert_encoding($this->_mailHead.$this->_mailBody.$this->_mailExtra.$this->_mailFoot, "JIS", "UTF-8");
			if(!mail($replyTo, $subject, $msg, $header)){
				$res[] = $replyTo;	// 失敗したアドレス
			}
		}
		
		if(empty($res)) $res[] = "SUCCESS";
		
		return $res;
	}

}
?>
