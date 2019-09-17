<?php
/*------------------------------------------------------------

	File_name	: via_maler.php
	Description	: 他ドメインからメールデータの転送を受けて送信
				  受注システム
					documents/sendmail.php
					documents/shipmentmail.php
					documents/sendmail_imag.php
				  takahamalifeart.comの自動送信で使用
					cgi-bin/mail/arrival.php
					cgi-bin/mail/exwmail.php
					cgi-bin/mail/pendingorder.php
					cgi-bin/mail/startjob.php

	Charset		: utf-8
	Log
	2013.03.06 created
	2015.04.25 添付ファイルに対応
	2018.03.02 ver2.0 送信先を一つ、返り値をJSON形式
				TODO: interface
	2018.08.27 ver2.1 返信メールが送信できなかった時のExceptionを廃止
-------------------------------------------------------------- */

require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';

if (isset($_POST['mail_subject'], $_POST['mail_contents'], $_POST['sendto'], $_POST['reply']) ) {
	$mailer = new ViaMailer($_POST['mail_subject'], $_POST['mail_contents'], $_POST['reply_head']);
	if (isset($_POST['attach']) && !empty($_POST['attach'])) {
		$attach = $_POST['attach'];
	} else {
		$attach = array();
	}
	if (isset($_REQUEST['ver']) && $_REQUEST['ver']==2) {
		if (is_string($attach)) $attach = json_decode($attach, true);
		if (!is_array($attach)) $attach = array();
		$res = $mailer->send2($_POST['sendto'], $_POST['reply'], $attach);
	} else {
		$res = $mailer->send($_POST['sendto'], $_POST['reply'], $attach);
		$res = serialize($res);
	}
	echo $res;
} else {
	echo "";
	exit();
}

class ViaMailer {

	private $_subject = '';		// 件名
	private $_body = '';		// 本文
	private $_replyHead = '';	// 前文（返信用）
	
	
	/**
	 * コンストラクタ
	 * @param {string} subject 件名
	 * @param {string} body 本文
	 * @param {string} replyHead 返信用の前文
	 */
	public function __construct($subject, $body, $replyHead)
	{
		$this->_subject = $subject;
		$this->_body = $body;
		$this->_replyHead = $replyHead;
	}
	
	
	/**
	 * メール送信
	 * @param {array} sendto[] 送信先のメールアドレスの配列
	 * @param {int} reply _ORDER_EMAILへの返信の有無　0:なし（default）　1:あり
	 * @param {array} attach[] 添付ファイル情報[{file,name,type},{},...]
	 * @return {array} 成功は[SUCCESS] , 失敗は[送信できなかったアドレス, ...]
	 */
	public function send(array $sendto, int $reply=0, array $attach=array()): array
	{
		mb_language("japanese");
		mb_internal_encoding("UTF-8");
		
		$msg = "";											// 送信文
		$boundary = md5(uniqid(rand())); 					// バウンダリー文字（メールメッセージと添付ファイルの境界とする文字列を設定）
		
		$fromname = "タカハマライフアート";
		$from = mb_encode_mimeheader($fromname,"JIS")."<"._INFO_EMAIL.">";
		$header = "From: $from\n";
		$header .= "Reply-To: $from\n";
		$header .= "X-Mailer: PHP/".phpversion()."\n";
		$header .= "MIME-version: 1.0\n";
		
		if (!empty($attach)) { 		// 添付ファイルがあり
			$header .= "Content-Type: multipart/mixed;\n";
			$header .= "\tboundary=\"$boundary\"\n";
			$msg .= "This is a multi-part message in MIME format.\n\n";
			$msg .= "--$boundary\n";
			$msg .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$msg .= "Content-Transfer-Encoding: 7bit\n\n";
		} else {												// 添付ファイルなし
			$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$header .= "Content-Transfer-Encoding: 7bit\n";
		}
		
		$msg .= mb_convert_encoding($this->_body,"JIS","UTF-8");	// ここで注文情報をエンコードして設定
		
		if (!empty($attach)) {		// 添付ファイル情報
			for ($i=0; $i<count($attach); $i++) {
				$msg .= "\n\n--$boundary\n";
				$msg .= "Content-Type: " . $attach[$i]['type'] . ";\n";
				$msg .= "\tname=\"".$attach[$i]['name']."\"\n";
				$msg .= "Content-Transfer-Encoding: base64\n";
				$msg .= "Content-Disposition: attachment;\n";
				$msg .= "\tfilename=\"".$attach[$i]['name']."\"\n\n";
				$msg .= $attach[$i]['file']."\n";
			}
			$msg .= "--$boundary--";
		}
		
		// 件名のマルチバイトをエンコード
		$subject  = mb_encode_mimeheader($this->_subject,"JIS");
		
		// メール送信
		$res = array();
		if ($reply) $sendto[] = _ORDER_EMAIL;	// order@ への返信用
		for ($i=0; $i<count($sendto); $i++) {
			if (strpos($sendto[$i], "@")===false) {
				$res[] = $sendto[$i];
				continue;
			}
			if (!mail($sendto[$i], $subject, $msg, $header)) {
				$res[] = $sendto[$i];	// 失敗したアドレス
			}
		}
		
		if (empty($res)) $res[] = 'SUCCESS';
		
		return $res;
	}


	/**
	 * メール送信 ver2.0
	 * @param {string} sendTo 送信先のメールアドレス
	 * @param {string} replyTo 返信する場合はメールアドレス
	 * @param {array} attach 添付ファイル情報[{file,name,type},{},...]
	 * @return {string} JSON形式、成功：{send: success}, 失敗：{send: error, message: ...}
	 */
	public function send2(string $sendTo, string $replyTo="", array $attach=array()): string
	{
		try {
			if (strpos($sendTo, "@")===false) {
				throw new Exception($sendTo.' is not a valid e-mail address.');
			}

			mb_language("japanese");
			mb_internal_encoding("UTF-8");

			$msg = "";						// 送信文
			$boundary = md5(uniqid()); 		// バウンダリー文字（メールメッセージと添付ファイルの境界とする文字列を設定）

			$from = mb_encode_mimeheader("タカハマライフアート","JIS")."<"._INFO_EMAIL.">";
			$header = "From: $from\n";
			$header .= "Reply-To: $from\n";
			$header .= "X-Mailer: PHP/".phpversion()."\n";
			$header .= "MIME-version: 1.0\n";

			if (!empty($attach)) { 			// 添付ファイルがあり
				$header .= "Content-Type: multipart/mixed;\n";
				$header .= "\tboundary=\"$boundary\"\n";
				$msg .= "This is a multi-part message in MIME format.\n\n";
				$msg .= "--$boundary\n";
				$msg .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
				$msg .= "Content-Transfer-Encoding: 7bit\n\n";
			} else {							// 添付ファイルなし
				$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
				$header .= "Content-Transfer-Encoding: 7bit\n";
			}

			$msg .= mb_convert_encoding($this->_body, "JIS", "UTF-8");	// ここで本文をエンコード

			if (!empty($attach)) {			// 添付ファイル情報
				for ($i=0; $i<count($attach); $i++) {
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
			$subject  = mb_encode_mimeheader($this->_subject,"JIS");

			// メール送信
			if (!mail($sendTo, $subject, $msg, $header)) {
				throw new Exception('Could not send to '.$sendTo);
			}

			// 返信メール
			if (!empty($replyTo)) {
				$msg = mb_convert_encoding($this->_replyHead.$this->_body, "JIS", "UTF-8");
				if (!mail($replyTo, $subject, $msg, $header)) {
//					throw new Exception('Reply to '.$replyTo.' failed.');
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

		return json_encode($res);
	}
}
?>
