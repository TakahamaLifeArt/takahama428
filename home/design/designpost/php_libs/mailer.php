<?php
/*------------------------------------------------------------

	File_name    : maler.php
	Description  : resend password
	Charset      : UTF-8
	Log	    	 : 2011.07.01	created
				   2013.12.12 	お客様写真館のユーザー登録のお知らせを追加
				   
-------------------------------------------------------------- */

require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';

class Mailer{
/*
*	パスワードの発行
*	@newpass
*	@email
*/
	private $info = array();
	
	public function __construct($info=null){
		$this->info = $info;
	}
	
	/**
	*	仮パスワードの送信
	*/
	public function send(){
		try{
			// メール本文
			$mail_info = $this->info['username']."　様\n";
			$mail_info .= "このたびは、タカハマライフアートをご利用いただき誠にありがとうございます。\n\n";
			
			$mail_info .= "パスワードのお問合せを頂きましたので、ご連絡いたします。\n\n";
			$mail_info .= "----------------------------------------\n\n";
			$mail_info .= "新しいパスワード：".$this->info['newpass']."\n\n";
			$mail_info .= "----------------------------------------\n\n";
			
			$mail_info .= "※新しいパスワードの発行により、これまでご利用いただいていたパスワードは使えなくなりました。\n\n";

			$mail_info .= "※新いパスワードは仮のものになりますので、のちほど\n";
			$mail_info .= "『マイページ』より、覚えやすいパスワードに変更されることをおすすめします。\n\n";
			
			$mail_info .= "\n※ご不明な点やお気づきのことがございましたら、ご遠慮なくお問い合わせください。\n";
			$mail_info .= "■営業時間　9:30 - 18:00　　■定休日：　土日祝\n\n";
			$mail_info .= "━ タカハマライフアート ━━━━━━━━━━━━━━━━━━━━━━━\n\n";
			$mail_info .= "　Phone：　　"._OFFICE_TEL."\n";
			$mail_info .= "　E-Mail：　　"._INFO_EMAIL."\n";
			$mail_info .= "　Web site：　"._DOMAIN."/\n";
			$mail_info .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
			
			// 送信処理
			mb_language("japanese");
			$sendto = $this->info['email'];
			$title = $subtitle."パスワードのご連絡";
			$subject = mb_encode_mimeheader(mb_convert_encoding($title,"JIS","UTF-8"));
			$fromname = "タカハマライフアート";
			$from = mb_encode_mimeheader(mb_convert_encoding($fromname,"JIS","UTF-8"))."<"._INFO_EMAIL.">";
			
			$header = "From: $from\n";
			$header .= "Reply-To: $from\n";
			$header .= "X-Mailer: PHP/".phpversion()."\n";
			$header .= "MIME-version: 1.0\n";
			$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$header .= "Content-Transfer-Encoding: 7bit\n";
			
			$msg .= mb_convert_encoding($mail_info,"JIS","UTF-8");	// ここで注文情報をエンコードして設定
			
			// メール送信
			$result = true;
			if(!mail($sendto, $subject, $msg, $header)){
				$result = false;
			}
			
			return $result;
			
		}catch (Exception $e) {
			return false;
		}
	}
	
	
	
	/**
	*	お客様写真館のユーザー登録のお知らせ
	*	QUOカードのプレゼント
	*/
	public function send_registerd(){
		try{
			// メール本文
			$mail_info = "【　お客様写真館ユーザー登録　】\n\n";
			$mail_info .= "■お名前：　".$this->info['customername']." 様\n";
			$mail_info .= "■ご住所：　〒".$this->info['zipcode']." ".$this->info['addr']."\n\n";
			
			$mail_info .= "■E-Mail：　〒".$this->info['email']."\n";
			$mail_info .= "----------------------------------------\n\n";
			
			$mail_info .= "登録日: ".date('Y-m-d H:i:s')."\n\n";
			
			
			// 送信処理
			$sendto = 'takahamaushida@gmail.com';
						
			$subject = "お客様写真館ユーザー登録のお知らせ";	// 件名
			$msg = "";											// 送信文
			$boundary = md5(uniqid(rand())); 					// バウンダリー文字（メールメッセージと添付ファイルの境界とする文字列を設定）
			
			$fromname = "タカハマ428";
			$from = mb_encode_mimeheader(mb_convert_encoding($fromname,"JIS","UTF-8"))."<"._INFO_EMAIL.">";
			$header = "From: $from\n";
			$header .= "Reply-To: $from\n";
			$header .= "X-Mailer: PHP/".phpversion()."\n";
			$header .= "MIME-version: 1.0\n";
			$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$header .= "Content-Transfer-Encoding: 7bit\n";
			
			$msg .= mb_convert_encoding($mail_info,"JIS","UTF-8");	// ここで注文情報をエンコードして設定
			
			// 件名のマルチバイトをエンコード
			$subject  = mb_encode_mimeheader(mb_convert_encoding($subject,"JIS","UTF-8"));
			
			// メール送信
			if(mail($sendto, $subject, $msg, $header)){
				$result = true;
			}else{
				$result = false;
			}
			
			return $result;
			
		}catch (Exception $e) {
			return false;
		}
	}

}
?>
