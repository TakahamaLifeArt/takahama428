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
	private $_mailInfo = array();	// 送信データ
	private $_mailBody = '';		// 本文
	private $_mailTitle = '';		// 本文タイトル
	private $_mailSummary = '';		// 本文概要
	private $_mailReplyHead = '';	// 前文（返信用）
	private $_mailExtra = '';		// その他の記載
	
	
	/**
	 * コンストラクタ
	 * @param {string} fromEncode send関数の引数の文字コード
	 * @param {array} mailInfo 送信データ{name=>value}
	 */
	public function __construct(string $fromEncode="", array $mailInfo=array()) {
		parent::__construct();
		if (!empty($fromEncode)) {
			$this->_fromEncode = strtoupper($fromEncode);
		}

		if (!empty($mailInfo)) {

			// tpl-インデックス_name属性値 => value
			foreach ($mailInfo as $key=>$val) {
				if (strpos($key, 'tpl-')!==0) continue;
				$keys = explode('_', $key);
				if (count($keys)!=2) continue;
				$idx = explode('-', $keys[0])[1];
				$tmp[$idx] = array(
									'lbl'=>$keys[1],
									'val'=>$val,
									);
			}
			$count = max(array_keys($tmp))+1;	// 添字が連続していないため、最大インデックス + 1
			for ($i=0; $i<$count; $i++) {
				if (!isset($tmp[$i])) continue;
				$this->_mailInfo[$tmp[$i]['lbl']] = $tmp[$i]['val'];
			}

			$this->_mailTitle = $mailInfo['tpl-title'];
			if (isset($mailInfo['tpl-summary'])) $this->_mailSummary = $mailInfo['tpl-summary'];
			if (isset($mailInfo['tpl-replyhead'])) $this->_mailReplyHead = $mailInfo['tpl-replyhead']."\n\n";
			if (isset($mailInfo['tpl-extra'])) $this->_mailExtra = "\n\n".$mailInfo['tpl-extra'];
			
			$this->setMailBody($this->_mailInfo);
		}
	}
	
	
	/**
	 * 本文生成
	 * @param {array} args 項目名をキーにした値の配列
	 */
	public function setMailBody(array $args) {
		if ($this->_fromEncode != 'UTF-8') {
			mb_convert_variables('UTF-8', $this->_fromEncode, $args);
			$body = "【　".mb_convert_encoding($this->_mailTitle, 'UTF-8', $this->_fromEncode)."　】\n";
			$body .= "Date: ".date('Y-m-d H:i:s')."\n\n";
			if (!empty($this->_mailSummary)) {
				$body .= mb_convert_encoding($this->_mailSummary, 'UTF-8', $this->_fromEncode)."\n\n";
			}
			foreach ($args as $key=>$val) {
				if ($key=='title' || $key=='head' || $key=='extra' || $key=='summary') continue;
				if ($key=='array') {
					for ($i=0; $i<count($val); $i++) {
						foreach ($val[$i] as $key2=>$val2) {
							$body .= "■".mb_convert_encoding($key2, 'UTF-8', $this->_fromEncode)."： ".mb_convert_encoding($val2, 'UTF-8', $this->_fromEncode)."\n";
						}
						$body .= "--------------------\n\n";
					}
				} else if (strpos($key, 'part-')===0) {
					$body .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
					if (!empty($val)) $body .= "[ ".mb_convert_encoding($val, 'UTF-8', $this->_fromEncode)." ]\n";
				} else {
					$body .= "■".mb_convert_encoding($key, 'UTF-8', $this->_fromEncode)."： ".mb_convert_encoding($val, 'UTF-8', $this->_fromEncode)."\n\n";
				}
			}
		} else {
			$body = "【　".$this->_mailTitle."　】\n";
			$body .= "Date: ".date('Y-m-d H:i:s')."\n\n";
			if (!empty($this->_mailSummary)) {
				$body .= $this->_mailSummary."\n\n";
			}
			foreach ($args as $key=>$val) {
				if ($key=='title' || $key=='head' || $key=='extra' || $key=='summary') continue;
				if ($key=='array') {
					for ($i=0; $i<count($val); $i++) {
						foreach ($val[$i] as $key2=>$val2) {
							$body .= "■".$key2."： ".$val2."\n";
						}
						$body .= "--------------------\n\n";
					}
				} else if (strpos($key, 'part-')===0) {
					$body .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
					if (!empty($val)) $body .= "[ ".$val." ]\n";
				} else {
					$body .= "■".$key."： ".$val."\n\n";
				}
			}
		}
		
		$this->_mailBody = $body;
		
		// 返信メールの前文を設定
		if (isset($args['head'])) {
			$this->_mailReplyHead = $args['head']."\n\n";
		}
		
		// その他の記載
		if (isset($args['extra'])) {
			$this->_mailExtra = "\n\n".$args['extra'];
		}
	}
	
	/**
	 * メール送信
	 * @param {string} mailSubject 件名
	 * @param {string} sendTo 送信先のメールアドレス
	 * @param {string} replyTo 返信する場合はメールアドレス
	 * @param {array} attach 添付ファイル情報[{file,name,type},{},...]
	 * @return {array} 成功：{send: SUCCESS}, 失敗：{send: error, message: ...}
	 */
	public function send(string $mailSubject, string $sendTo, string $replyTo="", array $attach=array()): array {
		try {
			if(strpos($sendTo, "@")===false){
				throw new Exception($sendTo.' is not a valid e-mail address.');
			}
			
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

			$msg .= mb_convert_encoding($this->_mailBody.$this->_mailExtra.$this->_mailFoot, "JIS", "UTF-8");	// ここで本文をエンコード

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
			if(!mail($sendTo, $subject, $msg, $header)){
				throw new Exception('Could not send to '.$sendTo);
			}
			
			// 返信メール
			if(!empty($replyTo)) {
				$msg = mb_convert_encoding($this->_mailReplyHead.$this->_mailBody.$this->_mailExtra.$this->_mailFoot, "JIS", "UTF-8");
				if(!mail($replyTo, $subject, $msg, $header)){
					throw new Exception('Reply to '.$replyTo.' failed.');
				}
			}
			
			$res = array('send'=>'success');
			
		} catch (Exception $e) {
			$msg = $e->getMessage();
			$res = array(
				'send'=>'error',
				'message'=>$msg
			);
		}

		return $res;
	}

}
?>
