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
				   
-------------------------------------------------------------- */

require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';

if( isset($_POST['mail_subject'],$_POST['mail_contents'],$_POST['sendto'],$_POST['reply']) ){
	$mailer = new ViaMailer();
	if(isset($_POST['attach'])){
		$attach = $_POST['attach'];
	}else{
		$attach = null;
	}
	$res = $mailer->send($_POST['mail_subject'],$_POST['mail_contents'],$_POST['sendto'],$_POST['reply'],$attach);
	$res = serialize($res);
	echo $res;
}else{
	echo "";
	exit();
}

class ViaMailer{

	public function __construct(){}
	
	
	/**
	*	メール送信
	*	@mail_subject	題名
	*	@mail_contents	メール本文
	*	@sendto[]		送信先のメールアドレスの配列
	*	@reply			_ORDER_EMAILへの返信の有無　0:なし（default）　1:あり
	*	@attach[]		添付ファイル情報[{file,name,type},{},...]
	*	
	*	返り値			[SUCCESS]:送信成功 , [送信できなかったアドレス, ...]:送信失敗
	*/
	public function send($mail_subject, $mail_contents, $sendto, $reply=0, $attach=null){
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
		
		if(!empty($attach)){ 		// 添付ファイルがあり
			$header .= "Content-Type: multipart/mixed;\n";
			$header .= "\tboundary=\"$boundary\"\n";
			$msg .= "This is a multi-part message in MIME format.\n\n";
			$msg .= "--$boundary\n";
			$msg .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$msg .= "Content-Transfer-Encoding: 7bit\n\n";
		}else{												// 添付ファイルなし
			$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$header .= "Content-Transfer-Encoding: 7bit\n";
		}
		
		$msg .= mb_convert_encoding($mail_contents,"JIS","UTF-8");	// ここで注文情報をエンコードして設定
		
		if(!empty($attach)){		// 添付ファイル情報
			for($i=0; $i<count($attach); $i++){
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
		$subject  = mb_encode_mimeheader($mail_subject,"JIS");
		
		// メール送信
		$res = array();
		if($reply) $sendto[] = _ORDER_EMAIL;	// order@ への返信用
		for($i=0; $i<count($sendto); $i++){
			if(strpos($sendto[$i], "@")===false){
				$res[] = $sendto[$i];
				continue;
			}
			if(!mail($sendto[$i], $subject, $msg, $header)){
				$res[] = $sendto[$i];	// 失敗したアドレス
			}
		}
		
		if(empty($res)) $res[] = 'SUCCESS';
		
		return $res;
	}

}
?>
