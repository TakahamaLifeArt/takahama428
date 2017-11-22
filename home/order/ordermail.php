<?php
/*------------------------------------------------------------

	File_name	: ordermail.php
	Description	: takahama428 web site send order mail class
	Hash		: Session data
					$_SESSION['orders']['items'];		商品
					$_SESSION['orders']['attach'];		添付ファイル（廃止）
					$_SESSION['orders']['customer'];	ユーザー
					$_SESSION['orders']['options'];		オプション
					$_SESSION['orders']['sum'];			合計値（商品代、プリント代、枚数）
	Charset		: utf-8
	Log			: 2011.03.26 created
				  2012.03.14 プリント情報の本文生成を更新
				  2014.02.04 都道府県を分ける
				  2014.05.12 支払方法にカード決済を追加
				  2014.08.13 特急料金の有無を追加
				  2017-05-25 プリント代計算の仕様変更によるプリント情報の更新
				  2017-06-16 デザインサイズの指定を廃止して「大」で固定
				  2017-09-12 デザインファイルのメール添付を廃止
				  2017-11-07 注文データの登録処理を更新
				  2017-11-10 日本語のアップロードファイル名のエスケープ処理を回避

-------------------------------------------------------------- */
require_once dirname(__FILE__).'/../php_libs/http.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';

class Ordermail extends Conndb{

	/**
	 * 注文メール本文を生成
	 * @param {array} uploadfilename アップロードしたデザインファイルのパス
	 * @return {boolean} true:成功
	 *					 false:失敗
	 */
	public function send($uploadfilename) {
		try {
			mb_internal_encoding("UTF-8");
			
			// 受注システムに注文データを登録
			$uploadURL = array();
			if (!empty($uploadfilename)) {
//				for($a=0; $a<count($uploadfilename); $a++){
//					$tmpDir = _MEMBER_IMAGE_PATH."files/".basename(dirname($uploadfilename[$a], 1))."/";
//					$fname = rawurldecode(basename($uploadfilename[$a]));
//					$uploadURL[] = $tmpDir.$fname;
//				}
//				$uploadDir = _MEMBER_IMAGE_PATH."files/".basename(dirname($uploadfilename[0], 1));
				$uploadDir = basename(dirname($uploadfilename[0], 1));
			}
			$hash = $this->insertOrderToDB($uploadDir);
			$order_id = $hash['orderid'];
			
			
			// 本文生成
			$items = $_SESSION['orders']['items'];
			$attach = null;
			$user = $_SESSION['orders']['customer'];
			$opts = $_SESSION['orders']['options'];
			$sum = $_SESSION['orders']['sum'];
						
			$order_info = "☆━━━━━━━━【　お申し込み内容　】━━━━━━━━☆\n\n";
			
			$order_info .= "┏━━━━━━━━┓\n";
			$order_info .= "◆　　ご希望納期\n";
			$order_info .= "┗━━━━━━━━┛\n";
			if(empty($opts['deliveryday'])){
				$order_info .= "◇　納期指定なし\n";
			}else{
				$order_info .= "◇　納期　：　".$opts['deliveryday']."\n";
			}
			
			
			if(!empty($opts['expressInfo'])){
				$order_info .= "◇　".$opts['expressInfo']."：　特急料金あり\n";
			}
			
			$delitime = array(
				'なし',
				'午前中', 
				'12:00-14:00',
				'14:00-16:00',
				'16:00-18:00',
				'18:00-20:00',
				'19:00-21:00'
			);
			$order_info .= "◇　配達時間指定　：　".$delitime[$opts['deliverytime']]."\n\n";
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
			
			$order_info .= "┏━━━━━━━┓\n";
			$order_info .= "◆　　商品情報\n";
			$order_info .= "┗━━━━━━━┛\n";
			
/*
*	['items'][category_id]['category_key']
*			  			  ['category_name']
*		   	  			  ['item'][id]['code']
*					   			  	  ['name']
*							      	  ['color'][code]['name']
*											 		 ['size'][sizeid]['sizename']
*											  						 ['amount']
*																	 ['cost']
*/
			$attach_info = array();
			foreach($items as $catid=>$v1){
				foreach($v1['item'] as $itemid=>$v2){
					$item_name = $v2['code']." ".$v2['name'];
					$order_info .= "◆アイテム：　".$item_name."\n";
					$posid = $v2['posid'];
					foreach($v2['color'] as $colorcode=>$v3){
						$color_name = $v3['name'];
						$order_info .= "◇カラー：　".$colorcode." ".$color_name."\n";
						$order_info .= "◇サイズと枚数\n";
						$subtotal = 0;
						foreach($v3['size'] as $sizeid=>$v4){
							if(empty($v4['amount'])) continue;
							$order_info .= $v4['sizename']."　：　".$v4['amount']."枚\n";
						}
						$order_info .= "--------------------\n";
					}
					$order_info .= "\n\n";
/*
*	プリント位置
*	['items'][category_id]['item'][id]['name']
*									  ['posid']
*									  ['design'][base][0]['posname']
*												   		 ['ink']
*/
					$attach_info[$posid]['design'] = $v2['design'];
					$attach_info[$posid]['item'][] = $item_name;
					
				}
			}
			$order_info .= "◆枚数合計：　".number_format($sum['amount'])." 枚\n";
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			

/*
*	$attach_info
*	[posid]['item'][アイテム名, ...]
*		   ['design'][base][0]['posname']
*					   		  ['ink']
*							  ['img']['file']
*									 ['name']
*									 ['type']
*/
			$order_info .= "┏━━━━━━━━━┓\n";
			$order_info .= "◆　　プリント情報\n";
			$order_info .= "┗━━━━━━━━━┛\n";
//			$sizeName = array('大', '中', '小');
			$printName = array('silk'=>'シルク','digit'=>'デジタル転写','inkjet'=>'インクジェット');
			foreach($attach_info as $posid=>$a1){
				$order_item = "◇アイテム：　".implode('、', $a1['item'])."\n";
				$printinfo = '';
				foreach($a1['design'] as $base=>$a2){
					
					$tmp = "";
					for($i=0; $i<count($a2); $i++){
						if($a2[$i]['ink']==0) continue;
						if($a2[$i]['ink']>=9) $ink = "フルカラー\n";
						else $ink = $a2[$i]['ink']."色\n";
						$tmp = $a2[$i]['posname']."　".$ink;
						// $printinfo .= "◇プリント方法：　".$printName[$a2[$i]['printing']]."\n";
						$printinfo .= "◇プリント位置：　".$base."\n";
//						$printinfo .= "◇デザインサイズ：　".$sizeName[$a2[$i]['areasize']]."\n";
						$printinfo .= "◇デザインの色数：　".$tmp."\n";
					}
				}
				if($printinfo!=""){
					$order_info .= $order_item.$printinfo;
					$order_info .= "------------------------------------------\n\n";
				}else{
					$order_info .= $order_item."プリントなし\n";
					$order_info .= "------------------------------------------\n\n";
				}
			}
			
			if(empty($opts['pack'])){
				$order_info .= "◇たたみ・袋詰め：　希望しない\n";
			}else if($opts['pack']==1){
				$order_info .= "◇たたみ・袋詰め：　希望する\n";
			}else{
				$order_info .= "◇たたみ・袋詰め：　袋のみ\n";
			}
			
			//$order_info .= "◇デザインの入稿方法：　".$opts['ms']."\n\n";
			//$order_info .= "◇プリントカラー：　\n".$opts['note_printcolor']."\n\n";
			//$order_info .= "◇文字入力の確認：　\n".$opts['note_write']."\n\n";
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			
			$order_info .= "┏━━━━━┓\n";
			$order_info .= "◆　　割引\n";
			$order_info .= "┗━━━━━┛\n";
			
			// 学割
			if(!empty($opts['student'])){
				switch($opts['student']){
					case '3':	$discountname[] = "学割";
								break;
					case '5':	$discountname[] = "2クラス割";
								break;
					case '7':	$discountname[] = "3クラス割";
								break;
				}
			}
			
			// ブログ割
			if(!empty($opts['blog'])){
				$discountname[] = "ブログ割";
			}
			
			// イラレ割
			if(!empty($opts['illust'])){
				$discountname[] = "イラレ割";
			}
			
			// 紹介割
			if(!empty($opts['intro'])){
				$discountname[] = "紹介割";
			}
			
			if(empty($discountname)){
				$order_info .= "◇割引：　なし\n";
			}else{
				$order_info .= "◇割引：　".implode(', ', $discountname)."\n";
			}
			
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			
			
			$order_info .= "┏━━━━━━━━┓\n";
			$order_info .= "◆　　お客様情報\n";
			$order_info .= "┗━━━━━━━━┛\n";
			$order_info .= "◇お名前：　".$user['customername']."　様\n";
			$order_info .= "◇フリガナ：　".$user['customerruby']."　様\n";
			$order_info .= "◇ご住所：　〒".$user['zipcode']."\n";
			$order_info .= "　　　　　　　　".$user['addr0']."\n";
			$order_info .= "　　　　　　　　".$user['addr1']."\n";
			$order_info .= "　　　　　　　　".$user['addr2']."\n";
			$order_info .= "◇TEL：　".$user['tel']."\n";
			$order_info .= "◇E-Mail：　".$user['email']."\n";
			$order_info .= "------------------------------------------\n\n";
			
//			$order_info .= "◇弊社ご利用について：　";
//			if($user['repeater']==1){
//				$order_info .= "初めてのご利用\n\n";
//			}else if($user['repeater']==2){
//				$order_info .= "以前にも注文したことがある\n\n";
//			}else{
//				$order_info .= "-\n\n";
//			}
			
			/*
			$attr = array('','法人','学生','個人');
			$order_info .= "◇お客様について：　".$attr[$opts['attr']]."\n";
			
			$purpose = array('', 
				'文化祭・体育祭（クラス・サークル・企業）',
				'スポーツ・ダンスユニフォーム（部活・サークルなど）',
				'スタッフユニフォーム・制服',
				'販売・販促品',
				'プレゼント・記念品',
				'個人用'
			);
			$tmp = array();
			if(!empty($opts['purpose'])){
				for($i=0; $i<count($opts['purpose']); $i++){
					if($opts['purpose'][$i]==1){
						$tmp[] = $purpose[$i];
					}
				}
			}
			$tmp[] = $opts['purpose_text'];
			$order_info .= "◇ご使用用途：　".implode(', ',$tmp)."\n";
			
			$media = array('', 
				'Yahoo検索','Google検索','その他検索エンジン',
				'Tシャツ、ファッション関連サイト','バナー広告',
				'カタログ・チラシ等','友人・知人の紹介'
			);
			$tmp = array();
			if(!empty($opts['media'])){
				for($i=0; $i<count($opts['media']); $i++){
					if($opts['media'][$i]==1){
						$tmp[] = $media[$i];
					}
				}
			}
			$tmp[] = $opts['media_text'];
			$order_info .= "◇何でお知りになったか：　".implode(', ',$tmp)."\n";
			*/
			
			if(empty($opts['publish'])){
				$order_info .= "◇デザイン掲載：　掲載可\n\n";
			}else{
				$order_info .= "◇デザイン掲載：　掲載不可\n\n";
			}
			$order_info .= "◇デザインについてのご要望など：\n";
			if(empty($user['note_design'])){
				$order_info .= "なし\n";
			}else{
				$order_info .= $user['note_design']."\n";
			}
			$order_info .= "------------------------------------------\n\n";
			
			$order_info .= "◇刺繍をご希望の場合：\n";
			if(empty($user['note_printmethod'])){
				$order_info .= "なし\n";
			}else{
				$order_info .= $user['note_printmethod']."\n";
			}
			$order_info .= "------------------------------------------\n\n";
			
			$order_info .= "◇プリントするデザインの色：\n";
			$order_info .= $user['note_printcolor']."\n";
			$order_info .= "------------------------------------------\n\n";
			
			$payment = array("銀行振込","代金引換","現金でお支払い（工場でお受取）","カード決済","コンビニ決済");
			$order_info .= "◇お支払方法：　".$payment[$opts['payment']]."\n\n";
			
			$order_info .= "◇ご要望・ご質問など：\n";
			if(empty($user['comment'])){
				$order_info .= "なし\n\n";
			}else{
				$order_info .= $user['comment']."\n\n";
			}
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n";

			$order_info .= "┏━━━━━━━━━━━┓\n";
			$order_info .= "◆　　デザインファイル\n";
			$order_info .= "┗━━━━━━━━━━━┛\n";
			if (empty($uploadfilename)) {
				$order_info .= "なし\n";
				$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
				$order_info_admin = "";
				$order_info_user = "";
			} else {
//				for ($a=0; $a<count($hash['designfile']); $a++) {
//					$order_info_admin .= "◇ファイル名：　"._ORDER_DOMAIN."/system/attachfile/".$order_id."/".$hash['designfile'][$a]."\n\n";
//				}
				for ($b=0; $b<count($uploadfilename); $b++) {
					$fname = basename($uploadfilename[$b]);
					$order_info_user .= "◇ファイル名：　".rawurldecode($fname)."\n";
					$order_info_admin .= "◇ファイル名：　"._ORDER_DOMAIN."/system/attachfile/".$order_id."/".$fname."\n\n";
				}
				if (empty($order_id)) {
					$order_info_admin .= "\n===  Error  ===\n";
					$order_info_admin .= "\n◇ 注文データの送信中にエラーが発生しています。\n";
					$order_info_admin .= "\n===\n\n";
				} else if ($hash['designfile']==false) {
					$order_info_admin .= "\n===  Error  ===\n";
					$order_info_admin .= "\n◇ 転送できなかったデザインファイルがあります。\n";
					$order_info_admin .= "\n-- 元ファイル（".$b."個）\n";
					$order_info_admin .= "◇コード：　".basename(dirname($uploadfilename[$b], 1))."\n";
//					for ($b=0; $b<count($uploadfilename); $b++) {
//						$fname = rawurldecode(basename($uploadfilename[$b]));
//						$order_info_admin .= "◇ファイル名：　".$fname."\n";
//					}
					$order_info_admin .= "\n===\n\n";
				}
				$order_info_admin .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
				$order_info_user .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			}
			$addition = array($order_info_admin, $order_info_user);
			
			/*
			$order_info .= "┏━━━━━━━┓\n";
			$order_info .= "◆　　お届け先\n";
			$order_info .= "┗━━━━━━━┛\n";
			if(!empty($user['deli'])){
				$order_info .= "◇宛名：　".$user['organization']."　様\n";
				$order_info .= "◇ご住所：　〒".$user['delizipcode']."\n";
				$order_info .= "　　　　　　　　　".$user['deliaddr1']." ".$info['deliaddr2']."\n";
			}else{
				$order_info .= "（上記ご連絡先と同じ場所にお届けする）\n";
			}
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n";
			*/
			
			/* 2013-11-25 廃止
			if(empty($user['payment'])){
				$order_info .= "┏━━━━━━━┓\n";
				$order_info .= "◆　　お振込先\n";
				$order_info .= "┗━━━━━━━┛\n";
				$order_info .= "振込口座：　三菱東京ＵＦＪ銀行\n";
				$order_info .= "新小岩支店744　普通 3716333\n";
				$order_info .= "口座名義：　ユ）タカハマライフアート\n";
				$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n";
				$order_info .= "※お振込み手数料は、お客様のご負担とさせて頂いております。\n\n";
			}
			*/
			
			// send mail
			$res = $this->send_mail($order_info, $user['customername'], $user['email'], $attach, $addition);
			if (!$res) {
				throw new Exception();
			}
			
			return $res;
			
		}catch (Exception $e) {
			return false;
		}
	}

	
	/**
	 * メール送信
	 * @param {string} mail_text	顧客情報と注文内容
	 * @param {string} name			お客様の名前
	 * @param {string} to			返信先のメールアドレス
	 * @param {array} attach		添付ファイル情報
	 * @param {array} addition		本文への追加[注文メール, 顧客への返信]
	 * @return {boolean} true:送信成功 , false:送信失敗
	 */
	protected function send_mail($mail_text, $name, $to, $attach, $addition){
		mb_language("japanese");
		mb_internal_encoding("UTF-8");
		$sendto = _ORDER_EMAIL;						// 送信先
		$suffix = "【takahama428】"; 				// 件名の後ろに付加するテキスト
		$subject = "お申し込み".$suffix;			// 件名
		$msg = "";									// 送信文
		$boundary = md5(uniqid(rand())); 			// バウンダリー文字（メールメッセージと添付ファイルの境界とする文字列を設定）
		
		$fromname = "タカハマ428";
		$from = mb_encode_mimeheader($fromname, "JIS")."<".$sendto.">";
		
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
		
		// ここで本文をエンコードして設定
		$msg .= mb_convert_encoding("お名前：　".$name."　様\n".$mail_text.$addition[0], "JIS","UTF-8");
		
		if(!empty($attach)){		// 添付ファイル情報
			for($i=0; $i<count($attach); $i++){

				$msg_chunk_split = chunk_split($attach[$i]['img']['file']);
				$msg .= "\n\n--$boundary\n";
				$msg .= "Content-Type: " . $attach[$i]['img']['type'] . ";\n";
				$msg .= "\tname=\"".$attach[$i]['img']['name']."\"\n";
				$msg .= "Content-Transfer-Encoding: base64\n";
				$msg .= "Content-Disposition: attachment;\n";
				$msg .= "\tfilename=\"".$attach[$i]['img']['name']."\"\n\n";
				$msg .= $msg_chunk_split."\n";
			}
			$msg .= "--$boundary--";
		}
		
		// 件名のマルチバイトをエンコード
		$subject = mb_encode_mimeheader($subject, "JIS");
				
		// メール送信
		if(mail($sendto, $subject, $msg, $header)){
			
			// 自動返信メール
			$sendto = $to;
			$subject = 'お申し込みありがとうございます';
			$subject = mb_encode_mimeheader($subject,"JIS");
			$from = _INFO_EMAIL;
			$fromname = "タカハマライフアート";
			$from = mb_encode_mimeheader($fromname,"JIS")."<".$from.">";
			
			$header = "From: $from\n";
			$header .= "Reply-To: $from\n";
			$header .= "X-Mailer: PHP/".phpversion()."\n";
			$header .= "MIME-version: 1.0\n";
			$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$header .= "Content-Transfer-Encoding: 7bit\n";
			
			$msg = $name."　様\n";
			$msg .= "このたびは、タカハマライフアートをご利用いただき誠にありがとうございます。\n";
			$msg .= "お申し込みを承りました。\n";
			$msg .= "このメールはお申し込みいただいたお客様へ、内容確認の自動返信となっております。\n\n";
			
			$msg .= "《現時点ではご注文は確定しておりません》\n\n";
			$msg .= "お申し込みいただいた内容でのお見積メールを改めてお送りいたしますので、お見積メール到着をお待ち下さい。\n";
			$msg .= "お見積メールは営業時間内で順次お送りしておりますが、お急ぎの場合、また、なかなか届かない場合には、\n";
			$msg .= "お手数ですが、フリーダイヤル"._TOLL_FREE."までご連絡ください。\n";
			$msg .= "（営業時間：平日10：00～18：00 ※お急ぎの場合でも営業時間内での対応となります。予めご了承下さい。）\n\n\n";
			
			$msg .= "《お支払いにつきまして》\n\n";
			$msg .= "最終打ち合わせが終了し、ご注文が確定いたしましたら、ご注文内容の「確認メール」をお送りいたします。\n";
			$msg .= "間違いが無いかご確認の上、確認メールに記載の方法でお支払いください。\n\n";
			$msg .= "引き続き、どうぞよろしくお願いいたします。\n\n";
			
			$msg .= $mail_text.$addition[1];
			
			// 臨時の告知文を挿入
			//$msg .= _EXTRA_NOTICE;

			$msg .= _NOTICE_HOLIDAY;
			$msg .= "\n";
			$msg .= _EXTRA_NOTICE;
			$msg .= "\n";

			$msg .= "\n※ご不明な点やお気づきのことがございましたら、ご遠慮なくお問い合わせください。\n";
			$msg .= "┏━━━━━━━━━━━━━━━━━━━\n";
			$msg .= "┃タカハマライフアート\n";
			$msg .= "┃　Phone：　　"._OFFICE_TEL."\n";
			$msg .= "┃　E-Mail：　　"._INFO_EMAIL."\n";
			$msg .= "┃　Web site：　"._DOMAIN."/\n";
			$msg .= "┗━━━━━━━━━━━━━━━━━━━\n";
			
			$msg = mb_convert_encoding($msg,"JIS","UTF-8");
			
			$res = mail($sendto, $subject, $msg, $header);
			
			return $res;	// 成功
		
		}else{
			return false;	// 失敗
		}
		
	}

	/**
	 * 受注システムに登録
	 * @param {string} uploadDir アップロードしたデザインファイルのディレクトリ
	 * @return {array} {orderid=>受注No. , designfile=>true|false}
	 */
	private function insertOrderToDB($uploadDir){
		$items = $_SESSION['orders']['items'];
		$user = $_SESSION['orders']['customer'];
		$opts = $_SESSION['orders']['options'];
		$sum = $_SESSION['orders']['sum'];

		// 顧客情報
		$customer_id = "";
		//新規顧客の場合
		if(empty($user['member'])){
			$data1 = array(
				"number"=>"","cstprefix"=>"k","customer_id"=>"",
				"customerruby"=>$user['customerruby'],"companyruby"=>"","customername"=>$user['customername'],"company"=>"",
				"tel"=>$user['tel'],"mobile"=>"","fax"=>"","email"=>$user['email'],"password"=>$user['pass'],"mobmail"=>"",
				"bill"=>"1","cutofday"=>"20","cyclebilling"=>"1","paymentday"=>"31","remittancecharge"=>"1",
				"zipcode"=>$user['zipcode'],"addr0"=>$user['addr0'],"addr1"=>$user['addr1'],"addr2"=>$user['addr2'],"addr3"=>"","addr4"=>"","reg_site"=>_SITE
			);
		} else {
			//ログインした顧客の場合
			$customer_id = $user['member'];
			$data1 = array(
				"customer_id"=>$customer_id,
				"customerruby"=>$user['customerruby'],"customername"=>$user['customername'],
				"tel"=>$user['tel'],"email"=>$user['email'],
				"zipcode"=>$user['zipcode'],"addr0"=>$user['addr0'],"addr1"=>$user['addr1'],"addr2"=>$user['addr2'],"reg_site"=>_SITE
			);
		}

		// お届け先情報
		$data2 = array("customer_id"=>$customer_id, "delivery_customer"=>$user['delivery_customer']);

		// 受注情報

		$discount = "";
		// ブログ割
		if(empty($opts['blog'])){
			$discount = "blog0";
		}else{
			$discount = "blog1";
		}
		$discount .= ",";
		// イラレ割
		if(empty($opts['illust'])){
			$discount .= "illust0";
		}else{
			$discount .= "illust1";
		}

		// 学割
		switch($opts['student']){
			case '3':	$discount1 = "student";
						break;
			case '5':	$discount1 = "team2";
						break;
			case '7':	$discount1 = "team3";
						break;
			default: 	$discount1 = "";
		}

		// 紹介割
		if(!empty($opts['intro'])){
			$discount2 = "introduce";
		}
		// 支払方法
		$payment = array("wiretransfer","cod","cash","credit","conbi");

		// 消費税
		$tax = parent::getSalesTax();
		$tax /= 100;

		// 見積
		$basefee = $sum["itemprice"] + $sum["printprice"] + $sum["optionfee"];
		$salestax = floor(basefee*$tax);
		$total = floor(basefee*(1+$tax));
		$credit = 0;
		if($sum["payment"]==3){
			$credit = ceil($total*_CREDIT_RATE);
			$total += $credit;
		}
		$perone = ceil($total/$sum['amount']);
		
		// コメント欄
		$comment[] = $user['note_design'];
		$comment[] = $user['note_printcolor'];
		$comment[] = $user['note_printmethod'];
		$comment[] = $user['comment'];
		$strComment = implode("\n", $comment);
		
		$field3 = array(
		"id","reception","destination","order_comment","paymentdate",
		"exchink_count","exchthread_count","deliverytime","manuscriptdate","invoicenote","billnote",
		"contact_number",
		"additionalname","extradiscountname","boxnumber","handover","factory",
		"destcount","ordertype","schedule1","schedule2","schedule3","schedule4",

		"arrival","carriage","check_amount","noprint","design","manuscript",
		"discount1","discount2","reduction","reductionname","freeshipping","payment",

		"phase","budget","deliver","purpose","designcharge","job","free_printfee",
		"free_discount","additionalfee","extradiscount","rakuhan","completionimage",
		"staffdiscount","maintitle","customer_id","estimated","order_amount",

		"purpose_text","reuse","applyto","repeater",
		"package_no",
		"package_nopack",
		"pack_nopack_volume",
		"package_yes",
		"pack_yes_volume",
		"discount","media","bill",

		"productfee","printfee","silkprintfee","colorprintfee","digitprintfee",
		"inkjetprintfee","cuttingprintfee","embroideryprintfee","exchinkfee","additionalfee","packfee",

		"expressfee","discountfee","reductionfee","carriagefee","extracarryfee",
		"designfee","codfee","basefee","salestax","creditfee", "conbifee","repeatdesign","allrepeat");

		$data3 = array
		("","0","0",$strComment,"",
		"0","0",$opts['deliverytime'],"","","",
		"",
		"","","0","0","0",
		"1","general","","","",$opts['deliveryday'],

		"0","normal",$sum['amount'],$opts['noprint'],"","",
		$discount1,$discount2,"0","","0",$payment[$opts['payment']],

		"accept","0","2","","0","その他","0",
		"0","0","","0","0",
		"0","",$customer_id,$total,$sum['amount'],

		"","0","0","0",
		empty($opts['pack'])? 1: 0,
		$opts['pack']!=2? 0: 1,
		$opts['pack']!=2? 0: $sum['amount'],
		$opts['pack']!=1? 0: 1,
		$opts['pack']!=1? 0: $sum['amount'],
		$discount,"","",

		$sum["itemprice"],$sum["printprice"],"0","0","0",
		"0","0","0","0","0",$sum["pack"],

		$sum["expressfee"],$sum["discount"],"0",$sum["carriage"],"0",
		"0",$sum["codfee"],$basefee,$salestax,$credit, $sum["conbifee"],"0","0");

		$field4 = array("master_id","choice","plateis","size_id","amount","item_cost","item_printfee","item_printone","item_id","item_name","stock_number","maker","size_name","item_color","price");
		$field5 = array();
		$field6 = array("category_id","printposition_id","subprice");
		$field7 = array("areaid", "print_id", "area_name", "area_path", "origin", "ink_count", "print_type","areasize_from", "areasize_to", "areasize_id", "print_option", "jumbo_plate", "design_plate","design_type","design_size", "repeat_check", "silkmethod");
		$field8 = array("areaid", "area_id", "selective_key", "selective_name");

		//注文商品
		$data4 = array();
		$data5 = array();
		//商品カテゴリーごとのプリント情報
		//data6
		$orderprint = array();
		$isExistOrderPrint = array();	// 同じカテゴリで且つ同じプリントポジションIDの有無をチェック
		//data7
		$orderarea = array();
		//data8
		$orderselectivearea = array();


		$attach_info = array();
		$idx6 = 0;
		$idx7 = 0;
		$idx8 = 0;
		$origin = 1;
		foreach($items as $catid=>$v1){
			foreach($v1['item'] as $itemid=>$v2){
				$posid = $v2['posid'];
				$orderprintTemp =$catid."|".$posid."|0";
				if (array_key_exists($catid.'-'.$posid, $isExistOrderPrint)===false) {
					array_push($orderprint , $orderprintTemp);
					$isExistOrderPrint[$catid.'-'.$posid] = true;
				}

				foreach($v2['color'] as $colorcode=>$v3){
					foreach($v3['size'] as $sizeid=>$v4){
						if(empty($v4['amount'])) continue;
						$tempData4 = $v3['master_id']."|1|1|".$sizeid."|".$v4['amount']."|".$v4['cost']."|0|0|".$itemid."||||||" ;
						array_push($data4, $tempData4);
					}
			  	}
				
				foreach($v2['design'] as $base=>$a2){
					if ($idx7>0) $origin = 0;
					for($i=0; $i<count($a2); $i++){
						if ($a2[$i]['ink']==0 && $opts['noprint']==0) continue;
						if ($opts['noprint']==1 && $i>0) continue;
						if (empty($a2[$i]['areakey']) || empty($a2[$i]['categorytype']) || empty($a2[$i]['itemtype'])) continue;
						$sizeFrom = $a2[$i]['printing']!='silk'? 0: 35;
						$sizeTo = $a2[$i]['printing']!='silk'? 0: 27;
						$areasize = 0;	// 大で固定
						$ink = 0;
						if ($a2[$i]['printing']=='silk') {
							$ink = $a2[$i]['ink']==9 ? "4" : $a2[$i]['ink'];
						}
						$tempData7 = "0|".$idx6."|". $a2[$i]['areakey']."|".$a2[$i]['categorytype']."/".$a2[$i]['itemtype']."|".$origin."|".$ink."|".$a2[$i]['printing']."|".$sizeFrom."|".$sizeTo."|".$areasize."|0|0|1|".(empty($opts['illust'])? "": "イラレ")."||0|1";
						array_push($orderarea , $tempData7);
						if($a2[$i]['ink']>0){
							$data8[$idx8]['area_id'] = $idx7;
							$data8[$idx8]['selective_key'] = $a2[$i]['poskey'];
							$data8[$idx8]['selective_name'] = $a2[$i]['posname'];
							$idx8++;
							$tempData8 ="0|".$idx7."|".$a2[$i]['poskey']."|".$a2[$i]['posname'];
							array_push($orderselectivearea , $tempData8);
						}
						$idx7++;
						if($opts['noprint']==1){
							break 2;
						}
					}
				}
				$idx6++;
			}
		}
		$field9 = array("inkid", "area_id", "ink_name", "ink_code", "ink_position");
		$orderink = array();
		$field10= array("exchid","ink_id","exchink_name","exchink_code","exchink_volume");
		$exchink = array();
		$field12 = array();
		$data12 = array();

		// アップロードファイル
		if (!empty($uploadDir)) {
			$upDir = $uploadDir;
		}

		// hash 1
		$data3 = $this->hash1($field3, $data3);
		$data12 = $this->hash1($field12, $data12);

		// hash 2
		$data4 = $this->hash2($field4, $data4);
		$data5 = $this->hash2($field5, $data5);
		$data6 = $this->hash2($field6, $orderprint);
		$data7 = $this->hash2($field7, $orderarea);
		$data8 = $this->hash2($field8, $orderselectivearea);
		$data9 = $this->hash2($field9, $orderink);
		$data10 = $this->hash2($field10, $exchink);

		$data = array($data1,$data2,$data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data12,$upDir,_SITE);
		
		// 受注システムに登録
		$orders = new WebOrder();
		$res = $orders->db('insert', 'order', $data);
		
		return $res;
	}
	
	/**
	 * POSTされたデータから配列を生成
	 * @param {array} fld フィールド名
	 * @param {array} dat データ
	 * @return {array} フィールド名をキーにしたハッシュ
	 */
	public function hash1($fld, $dat){
		for($i=0; $i<count($fld); $i++){
			if(empty($fld[$i]) || !isset($dat[$i])) continue;
			$hash[$fld[$i]] = $dat[$i];
		}
		return $hash;
	}

	/**
	 * 複数のレコードに対応
	 * @param {array} fld フィールド名
	 * @param {string} dat データ [data|data|... , ]
	 * @return {array} フィールド名をキーにしたハッシュ
	 */
	public function hash2($fld, $dat){
		for($i=0; $i<count($dat); $i++){
			if(empty($dat[$i])) continue;
			$tmp = explode("|", $dat[$i]);
			for($c=0; $c<count($fld); $c++){
				$hash[$i][$fld[$c]] = $tmp[$c];
			}
		}
		return $hash;
	}
}


/**
 * 2017-11-17 未使用
 */
class Design {

	/**
	 * フロントエンドから注文のデザイン画像ファイルを受注システムに転送
	 * @oaram {int} order_id 受注No.
	 * @param {string} filedir アップロードされたファイルのあるディレクトリのパス
	 * @return {array} 転送後のアップロードファイル名
	 */
	public function saveDesFile($order_id, $filedir){
		$path = $_SERVER['DOCUMENT_ROOT'].'/../../'._ORDER_VHOST.'/home/system/attachfile/'.$order_id;
		if(!is_dir($path)) {
			mkdir($path);
		}

		$up = 0;
		$fileCount = 0;
		$fileName = array();
		$today = date('Y-m-d');
		$root = $_SERVER['DOCUMENT_ROOT']."/";
		if ($handle = opendir($root.$filedir)) {
			setlocale(LC_ALL, 'ja_JP.UTF-8');
			while (false !== ($f = readdir($handle))) {
				if (is_dir($root.$filedir.'/'.$f)==false && $f != "." && $f != "..") {
					$fileCount++;
					$extension = pathinfo($f, PATHINFO_EXTENSION);
					$fileName[] = 'design_'.$order_id.'_'.$today.'_'.$up.'.'.$extension;
					if (rename($root.$filedir.'/'.$f, $path.'/'.$fileName[$up])) {
						$up++;
					}
				}
			}
			closedir($handle);
		}
		
		
//		$root = $_SERVER['DOCUMENT_ROOT'];
//		$fileCount = count($filedir);
//		$up = 0;
//		for ($i=0; $i<$fileCount; $i++) {
//			if (!$filedir[$i]) {
//				break;
//			}
//			$fileName = basename($file[$i]);
//
//			$fileName = $path."/".$fileName;
//
//			rename($root.$filedir[$i], $fileName);
//			$up++;
//		}

		if ($up>0 && $fileCount==$up) {
			$this->removeDirectory($root.$filedir);
		}

		return $fileName;
	}


	/**
	 * ディレクトリとファイルを再帰的に全削除
	 * @param {string} dir 削除するディレクトリ
	 */
	private function removeDirectory($dir) {
		if ($handle = opendir("$dir")) {
			while (false !== ($item = readdir($handle))) {
				if ($item != "." && $item != "..") {
					if (is_dir("$dir/$item")) {
						$this->removeDirectory("$dir/$item");
					} else {
						unlink("$dir/$item");
					}
				}
			}
			closedir($handle);
			rmdir($dir);
		}
	}

}


class WebOrder {
	private $print_codename = array(
		'silk'=>array('name'=>'シルク','abbr'=>'S','index'=>0),
		'inkjet'=>array('name'=>'IJ','abbr'=>'I','index'=>1),
		'digit'=>array('name'=>'デジ','abbr'=>'D','index'=>2),
		'trans'=>array('name'=>'TS','abbr'=>'T','index'=>3),
		'cutting'=>array('name'=>'CS','abbr'=>'C','index'=>4),
		'noprint'=>array('name'=>'商品のみ','abbr'=>'N','index'=>5),
		'embroidery'=>array('name'=>'刺繍','abbr'=>'E','index'=>6),
	);

	/**
	 * パスワードの暗号化
	 * @return {string} 暗号化したバイナリーデータ
	 */
	public function getSha1Pass($s) {
		if (empty($s)) return;
		return sha1(_PASSWORD_SALT.$s);
	}


	/**
	 * データベース接続
	 */
	private function db_connect(){
		$conn = mysqli_connect(_DB_HOST, _DB_USER, _DB_PASS, _DB_NAME, true) 
			or die("MESSAGE : cannot connect!". mysqli_error());
		$conn->set_charset('utf8');
		return $conn;
	}


	/**
	 * エスケープ処理
	 */
	private function quote_smart($conn, $value){
		if (!is_numeric($value)) {
			if(get_magic_quotes_gpc()) $value = stripslashes($value);
			$value = mysqli_real_escape_string($conn, $value);
		}
		return $value;
	}


	/**
	 * SQLの発行
	 */
	private function exe_sql($conn, $sql){
		$result = mysqli_query($conn, $sql)
			or die ('Invalid query: '.$sql.' -->> '.mysqli_error());
		return $result;
	}
	
	
	/**
	 * 注文伝票データベースの操作
	 * @param {string} func 処理内容
	 * @param {string} table テーブル名
	 * @param {array} param 引数の配列
	 *
	 * @return 各処理内容の返り値
	 */
	public function db($func, $table, $param){
		try{
			$conn = $this->db_connect();

			switch($func){
				case 'insert':
					mysqli_query($conn, 'BEGIN');
					$result = $this->insert($conn, $table, $param);
					if(!is_null($result)) mysqli_query($conn, 'COMMIT');
					break;
				case 'update':
					mysqli_query($conn, 'BEGIN');
					$result = $this->update($conn, $table, $param);
					if(!is_null($result)) mysqli_query($conn, 'COMMIT');
					break;
//				case 'delete':
//					mysqli_query($conn, 'BEGIN');
//					$result = $this->delete($conn, $table, $param);
//					if(!is_null($result)) mysqli_query($conn, 'COMMIT');
//					break;
				case 'search':
					$result = $this->search($conn, $table, $param);
					break;
			}
		}catch(Exception $e){
			$result = null;
		}

		mysqli_close($conn);

		return $result;
	}


	/**
	 * レコードの新規追加
	 * @param {string} table テーブル名
	 * @param {array} data 追加データの配列、若しくは注文伝票ID
	 *
	 * @return {array} {orderid=>受注No. , designfile=>true|false}
	 */
	private function insert($conn, $table, $data){
		try{
			switch($table){
				case 'order':
				/**
				 *	data1	顧客
				 *	data2	お届け先
				 *	data3	受注伝票
				 *	data4	注文商品
				 *	data5	業者の時の見積追加行
				 *	data6	プリント情報（orderprint）
				 *	data7	プリント位置（orderarea）
				 *	data8	プリントポジション（orderselectivearea）
				 *	data9	インク（orderink）
				 *	data10	インク色替え（exchink）
				 *	data12	発送元
				 *	-----2016-12-07-------
				 *	2017-11-09 添付ファイルの廃止に伴い仮引数を変更
				 *	2017-11-17 アップロード先の変更に伴い仮引数で親ディレクトを指定
				 *  upDir	アップロードされたファイルのあるディレクトリ
				 *  site 	注文サイト
				 *	return	受注ID, 顧客ID, 顧客Number | プリント位置ID,, | インクID,, | インク色替えID,, | 見積追加行ID,,
				 */
					list($data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9, $data10, $data12, $upDir, $site) = $data;
					$customer_id = 0;
					$deli_id = 0;
					$ship_id=0;

					if(isset($data1)){
						if($data1["customer_id"] == "" || $data1["customer_id"] == "0"){
							list($customer_id, $number) = $this->insert($conn, 'customer', $data1);
							if(empty($customer_id)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
						}else{
							$rs = $this->update($conn, 'customer', $data1);
						}
					}

					if(isset($data2)){
						if(empty($data2['delivery_id'])){
							$newdata2 = $data2;
							if(isset($data1)){
								if($data1 != ""){
									$newdata2 = array_merge($data1, $data2);
								}
							}
							$deli_id = $this->insert($conn, 'delivery', $newdata2);
						}else{
							$deli_id = $this->update($conn, 'delivery', $data2);
						}
						if(empty($deli_id)){
							mysqli_query($conn, 'ROLLBACK');
							return null;
						}
					}
					$delivery_id = empty($data2['delivery_id'])? $deli_id: $data2['delivery_id'];

					if(isset($data12)){
						$ship_id = $this->insert($conn, 'shipfrom', $data12);
						if(empty($ship_id)){
							mysqli_query($conn, 'ROLLBACK');
							return null;
						}
					}

					foreach($data3 as $key=>$val){
						$info3[$key] = $this->quote_smart($conn, $val);
					}

					// Web注文の場合に箱数を算出
					if (isset($site)) {
						$package = $data3["package_yes"]==1? 'yes': 'no';
						$param = array(
							array('curdate'=>'', 'package'=>$package),
							$data4,
						);
						$info3["boxnumber"] = $this->search($conn, 'numberOfBox', $param);
					}

					if(empty($info3["customer_id"]) || $customer_id!=0) $info3["customer_id"] = $customer_id;
					$info3["delivery_id"] = $delivery_id;
					$info3["shipfrom_id"] = $ship_id;
					$info3['created'] = date("Y-m-d");
					$info3['lastmodified'] = date("Y-m-d");
					$sql = sprintf("INSERT INTO orders(reception, ordertype, applyto, maintitle, schedule1, schedule2, schedule3, schedule4, destination, arrival,
					carriage, check_amount, noprint, design, manuscript, discount1, discount2, reduction, reductionname, handover, 
					freeshipping, payment, order_comment, invoicenote, billnote, phase, budget, customer_id, delivery_id, created, 
					lastmodified, estimated, order_amount, paymentdate, exchink_count, exchthread_count, deliver, deliverytime, manuscriptdate, purpose, 
					purpose_text, job, designcharge, repeater, reuse, free_discount, free_printfee, completionimage, contact_number, additionalname, 
					additionalfee, extradiscountname, extradiscount, shipfrom_id, package_yes, package_no, package_nopack, pack_yes_volume, pack_nopack_volume, boxnumber, 
					factory, destcount, repeatdesign, allrepeat, staffdiscount)
								VALUES(%d,'%s',%d,'%s','%s','%s','%s','%s',%d,'%s',
								'%s',%d,%d,'%s','%s','%s','%s',%d,'%s','%s',
								%d,'%s','%s','%s','%s','%s',%d,%d,%d,'%s',
								'%s',%d,%d,'%s',%d,%d,%d,%d,'%s','%s',
								'%s','%s',%d,%d,%d,%d,%d,%d,'%s','%s',
								%d,'%s',%d,%d,%d,%d,%d,%d,%d,%d,
								%d,%d,%d,%d,%d)",
								   $info3["reception"],
								   $info3["ordertype"],
								   $info3["applyto"],
								   $info3["maintitle"],
								   $info3["schedule1"],
								   $info3["schedule2"],
								   $info3["schedule3"],
								   $info3["schedule4"],
								   $info3["destination"],
								   $info3["arrival"],
								   $info3["carriage"],
								   $info3["check_amount"],
								   $info3["noprint"],
								   $info3["design"],
								   $info3["manuscript"],
								   $info3["discount1"],
								   $info3["discount2"],
								   $info3["reduction"],
								   $info3["reductionname"],
								   $info3['handover'],
								   $info3["freeshipping"],
								   $info3["payment"],
								   $info3["order_comment"],
								   $info3["invoicenote"],
								   $info3["billnote"],
								   $info3["phase"],
								   $info3["budget"],
								   $info3["customer_id"],
								   $info3["delivery_id"],
								   $info3["created"],
								   $info3["lastmodified"],
								   $info3["estimated"],
								   $info3["order_amount"],
								   $info3["paymentdate"],
								   $info3["exchink_count"],
								   $info3["exchthread_count"],
								   $info3["deliver"],
								   $info3["deliverytime"],
								   $info3["manuscriptdate"],
								   $info3["purpose"],
								   $info3["purpose_text"],
								   $info3["job"],
								   $info3["designcharge"],
								   $info3["repeater"],
								   $info3["reuse"],
								   $info3["free_discount"],
								   $info3["free_printfee"],
								   $info3["completionimage"],
								   $info3["contact_number"],
								   $info3["additionalname"],
								   $info3["additionalfee"],
								   $info3["extradiscountname"],
								   $info3["extradiscount"],
								   $info3["shipfrom_id"],
								   $info3["package_yes"],
								   $info3["package_no"],
								   $info3["package_nopack"],
								   $info3["pack_yes_volume"],
								   $info3["pack_nopack_volume"],
								   $info3["boxnumber"],
								   $info3["factory"],
								   $info3["destcount"],
								   $info3["repeatdesign"],
								   $info3["allrepeat"],
								   $info3["staffdiscount"]

								  );

					if($this->exe_sql($conn, $sql)){
						$rs = mysqli_insert_id($conn);
						$orders_id = $rs;

						/* reuse 2014-12-10 仕様変更、版元のreuseへの255の設定を廃止
					if($info3['repeater']!=0){
						$sql= sprintf("UPDATE orders SET reuse=%d WHERE id=%d", 255, $info3["repeater"]);
						if(!$this->exe_sql($conn, $sql)){
							mysqli_query($conn, 'ROLLBACK');
							return null;
						}
					}
					*/

						// orderprint
						$orderareaid = array();
						$orderinkid = array();
						$exchinkid = array();
						for($i=0; $i<count($data6); $i++){
							$sql = sprintf("INSERT INTO orderprint(orders_id,category_id,printposition_id,subprice) VALUES(%d,%d,'%s',%d)",
										   $orders_id,
										   $data6[$i]['category_id'],
										   $data6[$i]['printposition_id'],
										   $data6[$i]['subprice']);
							if($this->exe_sql($conn, $sql)){
								$orderprint_id = mysqli_insert_id($conn);
							}else{
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}

							// orderarea
							for($t=0; $t<count($data7); $t++){
								if($data7[$t]['print_id']!=$i) continue;
								$sql = sprintf("INSERT INTO orderarea(orderprint_id,area_path,area_name,origin,ink_count,print_type,
								areasize_from,areasize_to,areasize_id,print_option,jumbo_plate,design_plate,design_type,design_size,repeat_check,silkmethod)
								VALUES(%d,'%s','%s',%d,%d,'%s',%d,%d,%d,%d,%d,%d,'%s','%s',%d,%d)",
											   $orderprint_id,
											   'txt/'.$data7[$t]['area_path'].'/'.$data7[$t]['area_name'].'.txt',
											   $data7[$t]['area_name'],
											   $data7[$t]['origin'],
											   $data7[$t]['ink_count'],
											   $data7[$t]['print_type'],
											   $data7[$t]['areasize_from'],
											   $data7[$t]['areasize_to'],
											   $data7[$t]['areasize_id'],
											   $data7[$t]['print_option'],
											   $data7[$t]['jumbo_plate'],
											   $data7[$t]['design_plate'],
											   $data7[$t]['design_type'],
											   $data7[$t]['design_size'],
											   $data7[$t]['repeat_check'],
											   $data7[$t]['silkmethod']
											  );
								if($this->exe_sql($conn, $sql)){
									$orderarea_id = mysqli_insert_id($conn);
									$orderareaid[$t] = $orderarea_id;
								}else{
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}

								// orderselectivearea
								for($s=0; $s<count($data8); $s++){
									if($data8[$s]['area_id']==$t){
										$sql = sprintf("INSERT INTO orderselectivearea(orderarea_id,selective_key,selective_name) VALUES(%d,'%s','%s')",
													   $orderarea_id,
													   $data8[$s]['selective_key'],
													   $data8[$s]['selective_name']);
										if(!$this->exe_sql($conn, $sql)){
											mysqli_query($conn, 'ROLLBACK');
											return null;
										}
										break;
									}
								}

								// orderink
								for($s=0; $s<count($data9); $s++){
									if($data9[$s]['area_id']!=$t) continue;
									$sql = sprintf("INSERT INTO orderink(orderarea_id,ink_name,ink_code,ink_position) VALUES(%d,'%s','%s','%s')",
												   $orderarea_id, $data9[$s]['ink_name'], $data9[$s]['ink_code'], $data9[$s]['ink_position']);
									if($this->exe_sql($conn, $sql)){
										$orderink_id = mysqli_insert_id($conn);
										$orderinkid[$s] = mysqli_insert_id($conn);
									}else{
										mysqli_query($conn, 'ROLLBACK');
										return null;
									}

									// exchange ink
									/*
								for($a=0; $a<count($data10); $a++){
									if($data10[$a]['ink_id']!=$s) continue;
									$sql = sprintf("INSERT INTO exchink(orderink_id,exchink_name,exchink_code,exchink_volume) VALUES(%d,'%s','%s',%d)",
									$orderink_id, $data10[$a]['exchink_name'], $data10[$a]['exchink_code'], $data10[$a]['exchink_volume']);
									if($this->exe_sql($conn, $sql)){
										$exchinkid[$a] = mysqli_insert_id($conn);
									}else{
										mysqli_query($conn, 'ROLLBACK');
										return null;
									}
								}
								*/

								}
							}
						}

						if(!empty($info3['media'])){
							$tmp = explode(',', $info3['media']);
							for($i=0; $i<count($tmp); $i++){
								$media = explode('|', $tmp[$i]);
								if($media[0]=='mediacheck02'){
									$mediacheck02 = $media[1];
								}
							}
						}
						$sql = sprintf("INSERT INTO contactchecker(orders_id,firstcontactdate,staff_id,medianame,attr) VALUES(%d,'%s',%d,'%s','%s')",
									   $orders_id, date("Y-m-d"), $info3["reception"], $mediacheck02, $info3["purpose"]);
						if(!$this->exe_sql($conn, $sql)){
							mysqli_query($conn, 'ROLLBACK');
							return null;
						}

						$result = $this->insert($conn, 'orderitem', array($orders_id, $info3["ordertype"], $data4));
						if(empty($result)){
							mysqli_query($conn, 'ROLLBACK');
							return null;
						}

						// 進捗ID  Web注文: 90、注文システム: 1
						if(isset($site)){
							$sql = sprintf("INSERT INTO acceptstatus(orders_id,progress_id) VALUES(%d, 90)", $orders_id);
						} else {
							$sql = sprintf("INSERT INTO acceptstatus(orders_id,progress_id) VALUES(%d, 1)", $orders_id);
						}
						if(!$this->exe_sql($conn, $sql)){
							mysqli_query($conn, 'ROLLBACK');
							return null;
						}

						$sql = sprintf("INSERT INTO progressstatus(orders_id,rakuhan) VALUES(%d,%d)", $orders_id, $info3['rakuhan']);
						if(!$this->exe_sql($conn, $sql)){
							mysqli_query($conn, 'ROLLBACK');
							return null;
						}

						$sql = sprintf("select print_type from 
						 (orderprint inner join orderarea on orderprint.id=orderarea.orderprint_id)
						 right join orderselectivearea on orderarea.areaid=orderselectivearea.orderarea_id
						 where orders_id=%d group by orders_id, print_type", $orders_id);
						$result = $this->exe_sql($conn, $sql);
						$f = $info3['factory'];
						if(mysqli_num_rows($result)>0){
							if($info3['repeater']==0){
								$sql = "INSERT INTO printstatus(orders_id,printtype_key,factory_2,factory_3,factory_4,factory_5,factory_6,factory_7) VALUES";
								while($res = mysqli_fetch_assoc($result)){
									$sql .= "(".$orders_id.",'".$res['print_type']."',".$f.",".$f.",".$f.",".$f.",".$f.",".$f."),";
								}
								if($info3['noprint']==1){
									$sql .= "(".$orders_id.",'noprint',".$f.",".$f.",".$f.",".$f.",".$f.",".$f."),";
								}
							}else{
								$sql = "INSERT INTO printstatus(orders_id,printtype_key,state_1,state_2,factory_2,factory_3,factory_4,factory_5,factory_6,factory_7) VALUES";
								while($res = mysqli_fetch_assoc($result)){
									if($res['print_type']=='silk' || $res['print_type']=='digit'){
										$sql .= "(".$orders_id.",'".$res['print_type']."',28,28,".$f.",".$f.",".$f.",".$f.",".$f.",".$f."),";
									}else{
										$sql .= "(".$orders_id.",'".$res['print_type']."',43,0,".$f.",".$f.",".$f.",".$f.",".$f.",".$f."),";
									}
								}
								if($info3['noprint']==1){
									$sql .= "(".$orders_id.",'noprint',28,28,".$f.",".$f.",".$f.",".$f.",".$f.",".$f."),";
								}
							}

							$sql = substr($sql, 0, -1);
							if(!$this->exe_sql($conn, $sql)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
						}else if($info3['noprint']==1){
							$sql = "INSERT INTO printstatus(orders_id,printtype_key,factory_2,factory_3,factory_4,factory_5,factory_6,factory_7) VALUES";
							$sql .= "(".$orders_id.",'noprint',".$f.",".$f.",".$f.",".$f.",".$f.",".$f.")";
							if(!$this->exe_sql($conn, $sql)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
						}

						// シルク作業予定レコードを新規追加
						$sql = sprintf("select * from printstatus where orders_id=%d and printtype_key='silk'", $orders_id);
						$result = $this->exe_sql($conn, $sql);
						if(mysqli_num_rows($result)>0){
							$res = mysqli_fetch_assoc($result);
							$sql = "INSERT INTO workplan(orders_id, prnstatus_id, wp_printkey, quota) VALUES";
							$sql .= "(".$orders_id.", ".$res['prnstatusid'].", 'silk', 100)";
							if(!$this->exe_sql($conn, $sql)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
						}

						if(!empty($info3["discount"])){
							$result = $this->insert($conn, 'discount', array("orders_id"=>$orders_id, "discount"=>$info3["discount"]));
							if(is_null($result)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
						}

						if(!empty($info3['media']) || !empty($info3['media_other'])){
							$result = $this->insert($conn, 'media', array("orders_id"=>$orders_id, "media"=>array($info3["media"], $info3['media_other'])));
							if(is_null($result)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
						}

						if($info3['ordertype']=='general'){
							$sql = sprintf("INSERT INTO estimatedetails(productfee,printfee,
								silkprintfee,colorprintfee,digitprintfee,inkjetprintfee,cuttingprintfee,embroideryprintfee,
								exchinkfee,packfee,expressfee,discountfee,reductionfee,carriagefee,
								extracarryfee,designfee,codfee,conbifee,basefee,salestax,creditfee,orders_id)
							   VALUES(%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)",
										   $info3["productfee"],
										   $info3["printfee"],
										   $info3["silkprintfee"],
										   $info3["colorprintfee"],
										   $info3["digitprintfee"],
										   $info3["inkjetprintfee"],
										   $info3["cuttingprintfee"],
										   $info3["embroideryprintfee"],
										   $info3["exchinkfee"],
										   $info3["packfee"],
										   $info3["expressfee"],
										   $info3["discountfee"],
										   $info3["reductionfee"],
										   $info3["carriagefee"],
										   $info3["extracarryfee"],
										   $info3["designfee"],
										   $info3["codfee"],
										   $info3["conbifee"],
										   $info3["basefee"],
										   $info3["salestax"],
										   $info3["creditfee"],
										   $orders_id);
							if(!$this->exe_sql($conn, $sql)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}

						}else{
							if(!empty($data5)){
								$addestid = array();	// 見積追加行のIDを代入
								$sql = "INSERT INTO additionalestimate(addsummary,addamount,addcost,addprice,orders_id) VALUES";
								for($i=0; $i<count($data5); $i++){
									$sql .= "('".$this->quote_smart($conn, $data5[$i]['addsummary'])."'";
									$sql .= ",".$this->quote_smart($conn, $data5[$i]['addamount']);
									$sql .= ",".$this->quote_smart($conn, $data5[$i]['addcost']);
									$sql .= ",".$this->quote_smart($conn, $data5[$i]['addprice']);
									$sql .= ",".$orders_id."),";
								}
								$sql = substr($sql, 0, -1);
								if(!$this->exe_sql($conn, $sql)){
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}

								// 登録したIDを取得
								$sql = sprintf("select addestid from additionalestimate where orders_id=%d", $orders_id);
								$res = $this->exe_sql($conn, $sql);
								if(!$res){
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}
								while($rec = mysqli_fetch_assoc($res)){
									$addestid[] = $rec['addestid'];
								}
							}
						}

					}else{
						mysqli_query($conn, 'ROLLBACK');
						return null;
					}

					// アップロードされた入稿データのディレクトリ名を受注IDに変更
					$isUpload = true;
					if($upDir != ""){
						$oldPath = $_SERVER['DOCUMENT_ROOT'].'/../../'._ORDER_VHOST.'/home/system/attachfile/'.$upDir;
						$newPath = $_SERVER['DOCUMENT_ROOT'].'/../../'._ORDER_VHOST.'/home/system/attachfile/'.$orders_id;
						$isUpload = rename($oldPath, $newPath);
					}
					
					return array('orderid'=>$orders_id, 'designfile'=>$isUpload);
					break;

				case 'orderitem':
					list($orders_id, $ordertype, $data2) = $data;
					if(empty($data2)) return $orders_id;
					if($ordertype=='general'){	// general
						for($c=0; $c<count($data2); $c++){
							$val = $data2[$c];
							if(empty($val['choice'])) continue;
							if( preg_match('/^mst/',$val['master_id']) ){
								$prm = explode('_', $val['master_id']);
								$val['item_name'] = $prm[2];
								$val['item_color'] = $prm[3];
								$ppID = $prm[1].'_'.$prm[2];

								$item_id = $prm[1]==0? 0: 100000;

								// orderprintのIDを取得
								$sql = sprintf("select orderprint.id as print_id from orderprint where orders_id=%d and printposition_id='%s' and category_id=%d limit 1", 
											   $orders_id, $ppID, $prm[1]);
								$result = $this->exe_sql($conn, $sql);
								if(!mysqli_num_rows($result)){
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}
								$res = mysqli_fetch_assoc($result);

								$sql = sprintf("INSERT INTO orderitem(master_id,size_id,amount,plateis,orders_id,print_id,item_cost,item_printfee,item_printone) VALUES(0,0,%d,%d,%d,%d,%d,%d,%d)",
											   $val['amount'], $val['plateis'], $orders_id, $res['print_id'],$val['item_cost'],$val['item_printfee'],$val['item_printone']);
								if(!$this->exe_sql($conn, $sql)){
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}
								$latest = mysqli_insert_id($conn);
								$sql = sprintf("INSERT INTO orderitemext(item_id,item_name,stock_number,maker,size_name,item_color,price,orderitem_id)
							VALUES(%d,'%s','%s','%s','%s','%s','%s',%d)",
											   $item_id,
											   $val['item_name'],
											   $val['stock_number'],
											   $val['maker'],
											   $val['size_name'],
											   $val['item_color'],
											   $val['price'],
											   $latest);
								$rs = $this->exe_sql($conn, $sql);
								if(!$rs){
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}

							}else{

								// orderprintのIDを取得
								$sql = sprintf("select orderprint.id as print_id from (orderprint inner join catalog
									 on orderprint.category_id=catalog.category_id)
									 inner join item on orderprint.printposition_id=item.printposition_id
									 where catalog.item_id=item.id and orders_id=%d and catalog.id=%d",
											   $orders_id, $val['master_id']);

								$result = $this->exe_sql($conn, $sql);
								if(!mysqli_num_rows($result)){
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}
								$res = mysqli_fetch_assoc($result);

								$sql2 = sprintf("INSERT INTO orderitem(master_id,size_id,amount,plateis,orders_id,print_id,item_cost,item_printfee,item_printone) VALUES(%d,%d,%d,%d,%d,%d,%d,%d,%d)", 
												$val['master_id'],$val['size_id'],$val['amount'],$val['plateis'],$orders_id,$res['print_id'],$val['item_cost'],$val['item_printfee'],$val['item_printone']);
								$rs = $this->exe_sql($conn, $sql2);
								if(!$rs){
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}
							}
						}

					}else{	// industry
						for($i=0; $i<count($data2); $i++){
							foreach($data2[$i] as $key=>$val){
								$info[$key]	= $this->quote_smart($conn, $val);
							}

							// orderprintのIDを取得
							if(strpos($info['position_id'], '_')!==false){	// その他商品または持込
								$tmp = explode('_', $info['position_id']);
								$sql = sprintf("select orderprint.id as print_id from orderprint where orders_id=%d and printposition_id='%s' and category_id=%d limit 1",
											   $orders_id, $info['position_id'], $tmp[0]);
							}else if($info['item_id']=='99999'){	// 転写シート
								$sql = sprintf("select orderprint.id as print_id from orderprint where orders_id=%d and category_id=99 limit 1",
											   $orders_id);
							}else{
								$sql = sprintf("select orderprint.id as print_id from item inner join orderprint
								 on item.printposition_id=orderprint.printposition_id where orders_id=%d and item.id=%d limit 1",
											   $orders_id, $info['item_id']);
							}
							$result = $this->exe_sql($conn, $sql);
							if(!mysqli_num_rows($result)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
							$res = mysqli_fetch_assoc($result);

							$sql = sprintf("INSERT INTO orderitem(master_id,size_id,amount,plateis,orders_id,print_id) VALUES(%d,%d,%d,%d,%d,%d)",
										   $info['master_id'], $info['size_id'], $info['amount'], $info['plateis'], $orders_id, $res['print_id']);
							$rs = $this->exe_sql($conn, $sql);
							if(!$rs){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
							$latest = mysqli_insert_id($conn);
							$sql = sprintf("INSERT INTO orderitemext(item_id,item_name,stock_number,maker,size_name,item_color,price,orderitem_id)
						VALUES(%d,'%s','%s','%s','%s','%s','%s',%d)",
										   $info['item_id'],
										   $info['item_name'],
										   $info['stock_number'],
										   $info['maker'],
										   $info['size_name'],
										   $info['item_color'],
										   $info['price'],
										   $latest);
							$rs = $this->exe_sql($conn, $sql);
							if(!$rs){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
						}
					}

					return $rs;
					break;

				case 'discount':
				/**
				 *	data	"orders_id"=>n, "discount"=>ディスカウント名(カンマ区切り)
				 */
					foreach($data as $key=>$val){
						$info[$key]	= $this->quote_smart($conn, $val);
					}
					$tmp = explode(',', $info['discount']);

					$sql = "INSERT INTO discount(discount_name,discount_state,orders_id) VALUES";
					for($t=0; $t<count($tmp); $t++){
						$discount_name = substr($tmp[$t], 0, -1);
						$state         = substr($tmp[$t],-1);

						if($state==1){
							$sql2 .= "('".$discount_name."',1,".$info["orders_id"]."),";
						}
					}

					if(empty($sql2)) return 0;
					$sql .= substr($sql2, 0, -1);

					break;

				case 'media':
				/**
				 * data	"orders_id"=>n,
				 * 		"media"=>[name値|value値のカンマ区切り, その他のテキスト]
				 */
					$orders_id = $this->quote_smart($conn, $data['orders_id']);
					list($list1, $media_other) = $data['media'];
					$tmp = explode(',', $list1);

					$sql = 'INSERT INTO media(media_type, media_value, orders_id) VALUES';
					for($i=0; $i<count($tmp); $i++){
						$media = explode('|', $tmp[$i]);
						$sql2 .= '("'.$media[0].'","'.$media[1].'",'.$orders_id.'),';
					}
					if(!empty($media_other)){
						$sql2 .= '("mediacheck03","'.$this->quote_smart($conn, $media_other).'",'.$orders_id.'),';
					}
					if(empty($sql2)) return 0;
					$sql .= substr($sql2, 0, -1);

					break;

				case 'delivery':
					foreach($data as $key=>$val){
						$info[$key]	= $this->quote_smart($conn, $val);
					}
					//受注システムの場合
					if(!empty($info[delizipcode]) && $info[delizipcode] != "") {
						$sql = sprintf("INSERT INTO delivery(organization,agent,team,teacher,delizipcode,deliaddr0,deliaddr1,deliaddr2,deliaddr3,deliaddr4,delitel)
							   VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
									   $info["organization"],
									   $info["agent"],
									   $info["team"],
									   $info["teacher"],
									   $info["delizipcode"],
									   $info["deliaddr0"],
									   $info["deliaddr1"],
									   $info["deliaddr2"],
									   $info["deliaddr3"],
									   $info["deliaddr4"],
									   $info["delitel"]);
					} else {
						//Web注文からのデータ
						//既存顧客の場合
						if(!empty($info["delivery_customer"])) {
							//お届き先を選んだ場合
							if($info["delivery_customer"] != "-1") {
								$sql = sprintf("INSERT INTO delivery(organization,agent,team,teacher,delizipcode,deliaddr0,deliaddr1,deliaddr2,deliaddr3,deliaddr4,delitel)
								   SELECT organization,agent,team,teacher,delizipcode,deliaddr0,deliaddr1,deliaddr2,deliaddr3,deliaddr4,delitel FROM delivery_customer where id = %d",
											   $info["delivery_customer"]);
							} else {
								//住所を選んだ場合
								$sql = sprintf("INSERT INTO delivery(organization, delizipcode,deliaddr0,deliaddr1,deliaddr2,deliaddr3,deliaddr4,delitel)
									   SELECT customername, zipcode,addr0,addr1,addr2,addr3,addr4,tel from customer where id = %d",
											   $info["customer_id"]);
							}
						} else {
							//新規顧客の場合、画面で入力した情報を登録
							$sql = sprintf("INSERT INTO delivery(organization,agent,team,teacher,delizipcode,deliaddr0,deliaddr1,deliaddr2,deliaddr3,deliaddr4,delitel)
							   VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
										   $info["customername"],
										   $info["agent"],
										   $info["team"],
										   $info["teacher"],
										   $info["zipcode"],
										   $info["addr0"],
										   $info["addr1"],
										   $info["addr2"],
										   $info["addr3"],
										   $info["addr4"],
										   $info["tel"]);
						}
					}
					break;

				case 'shipfrom':
					foreach($data as $key=>$val){
						$info[$key]	= $this->quote_smart($conn, $val);
					}
					$sql = sprintf("INSERT INTO shipfrom(shipfromname,shipfromruby,shipzipcode,shipaddr0,shipaddr1,shipaddr2,shipaddr3,shipaddr4,shiptel,shipfax,shipemail)
							   VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
								   $info["shipfromname"],
								   $info["shipfromruby"],
								   $info["shipzipcode"],
								   $info["shipaddr0"],
								   $info["shipaddr1"],
								   $info["shipaddr2"],
								   $info["shipaddr3"],
								   $info["shipaddr4"],
								   $info["shiptel"],
								   $info["shipfax"],
								   $info["shipemail"]
								  );
					break;

				case 'customer':
					foreach($data as $key=>$val){
						$info[$key]	= $this->quote_smart($conn, $val);
					}
					$sql = sprintf("select number from customer where cstprefix='%s' order by number desc limit 1 for update", $info['cstprefix']);
					$result = $this->exe_sql($conn, $sql);
					if(!mysqli_num_rows($result)){
						$number = 1;
					}else{
						$res = mysqli_fetch_assoc($result);
						$number = $res['number']+1;
					}
					$reg_site = $info['reg_site'];
					if($reg_site == null || $reg_site == "" || ($reg_site != "1" && $reg_site != "5" && $reg_site != "6")) {
						$reg_site = "1";
					}
					$zipcode = str_replace('-', '', $info["zipcode"]);
					$tel = str_replace('-', '', $info["tel"]);
					$fax = str_replace('-', '', $info["fax"]);
					$mobile = str_replace('-', '', $info["mobile"]);
					$sql = sprintf("INSERT INTO customer(number,cstprefix,customername,customerruby,zipcode,addr0,addr1,addr2,addr3,addr4,tel,fax,email,mobmail,
				company,companyruby,mobile,job,customernote,bill,remittancecharge,cyclebilling,cutofday,paymentday,consumptiontax,password,reg_site,use_created)
							   VALUES(%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,%d,%d,'%s','%s', now())",
								   $number,
								   $info['cstprefix'],
								   $info["customername"],
								   $info["customerruby"],
								   $zipcode,
								   $info["addr0"],
								   $info["addr1"],
								   $info["addr2"],
								   $info["addr3"],
								   $info["addr4"],
								   $tel,
								   $fax,
								   $info["email"],
								   $info["mobmail"],
								   $info["company"],
								   $info["companyruby"],
								   $mobile,
								   $info["job"],
								   $info['customernote'],
								   $info['bill'],
								   $info['remittancecharge'],
								   $info['cyclebilling'],
								   $info['cutofday'],
								   $info['paymentday'],
								   2,
								   $this->getSha1Pass($info['password']),
								   $reg_site
								  );

					if($this->exe_sql($conn, $sql)){
						$newid = mysqli_insert_id($conn);
						$rs = array($newid, $number);
					}else{
						mysqli_query($conn, 'ROLLBACK');
						return null;
					}

					return $rs;
					break;

			}


			if($this->exe_sql($conn, $sql)){
				$rs = mysqli_insert_id($conn);
			}else{
				mysqli_query($conn, 'ROLLBACK');
				return null;
			}

		}catch(Exception $e){
			mysqli_query($conn, 'ROLLBACK');
			$rs = null;
		}

		return $rs;
	}


	/**
	 * レコードの修正更新
	 * @param {string} table テーブル名
	 * @param {array} data 更新データの配列
	 *
	 * @return 更新されたレコード数
	 */
	private function update($conn, $table, $data){
		try{
			$flg= true;
			switch($table){
				case 'customer':
					foreach($data as $key=>$val){
						$data[$key]	= $this->quote_smart($conn, $val);
					}

					if(isset($data['cancel'])){		// 受注伝票との関連付けを取り消す 2013-11-02 廃止
						// $sql = sprintf("UPDATE orders SET customer_id='0' WHERE id='%s'", $data["id"]);
					}else if(!isset($data['from_ordersystem']) && isset($data['reg_site'])){
						$zipcode = str_replace('-', '', $data["zipcode"]);
						$tel = str_replace('-', '', $data["tel"]);
						$sql = sprintf("UPDATE customer
							   SET customername='%s',customerruby='%s',
							   zipcode='%s',addr0='%s',addr1='%s',addr2='%s',tel='%s',email='%s' WHERE id=%d",
									   $data["customername"],
									   $data["customerruby"],
									   $zipcode,
									   $data["addr0"],
									   $data["addr1"],
									   $data["addr2"],
									   $tel,
									   $data["email"],
									   $data["customer_id"]);
					}else{
						$zipcode = str_replace('-', '', $data["zipcode"]);
						$tel = str_replace('-', '', $data["tel"]);
						$fax = str_replace('-', '', $data["fax"]);
						$mobile = str_replace('-', '', $data["mobile"]);
						if(isset($data['reg_site'])) {
							$sql = sprintf("UPDATE customer
							   SET customername='%s',customerruby='%s',
							   zipcode='%s',addr0='%s',addr1='%s',addr2='%s',addr3='%s',addr4='%s',tel='%s',fax='%s',email='%s',mobmail='%s',
							   company='%s',companyruby='%s',mobile='%s',job='%s',customernote='%s',
							   bill=%d,remittancecharge=%d,cyclebilling=%d,
							   cutofday=%d,paymentday=%d,consumptiontax=%d, reg_site=%s WHERE id=%d",
										   $data["customername"],
										   $data["customerruby"],
										   $zipcode,
										   $data["addr0"],
										   $data["addr1"],
										   $data["addr2"],
										   $data["addr3"],
										   $data["addr4"],
										   $tel,
										   $fax,
										   $data["email"],
										   $data["mobmail"],
										   $data["company"],
										   $data["companyruby"],
										   $mobile,
										   $data["job"],
										   $data["customernote"],
										   $data["bill"],
										   $data["remittancecharge"],
										   $data["cyclebilling"],
										   $data["cutofday"],
										   $data["paymentday"],
										   2,
										   $data["reg_site"],
										   $data["customer_id"]);
						} else {
							$sql = sprintf("UPDATE customer
							   SET customername='%s',customerruby='%s',
							   zipcode='%s',addr0='%s',addr1='%s',addr2='%s',addr3='%s',addr4='%s',tel='%s',fax='%s',email='%s',mobmail='%s',
							   company='%s',companyruby='%s',mobile='%s',job='%s',customernote='%s',
							   bill=%d,remittancecharge=%d,cyclebilling=%d,
							   cutofday=%d,paymentday=%d,consumptiontax=%d WHERE id=%d",
										   $data["customername"],
										   $data["customerruby"],
										   $zipcode,
										   $data["addr0"],
										   $data["addr1"],
										   $data["addr2"],
										   $data["addr3"],
										   $data["addr4"],
										   $tel,
										   $fax,
										   $data["email"],
										   $data["mobmail"],
										   $data["company"],
										   $data["companyruby"],
										   $mobile,
										   $data["job"],
										   $data["customernote"],
										   $data["bill"],
										   $data["remittancecharge"],
										   $data["cyclebilling"],
										   $data["cutofday"],
										   $data["paymentday"],
										   2,
										   $data["customer_id"]);
						}
					}
					$rs = $this->exe_sql($conn, $sql);
					if(!$rs){
						mysqli_query($conn, 'ROLLBACK');
						return null;
					}
					$rs = mysqli_affected_rows($conn);

					// 未確定注文の場合に、支払区分が月締めの場合は、入金を済みにし発送可にする
					if(isset($data['orders_id'])){
						$sql = sprintf("SELECT * FROM acceptstatus WHERE orders_id=%d", $data['orders_id']);
						$result = $this->exe_sql($conn, $sql);
						if(!$result){
							mysqli_query($conn, 'ROLLBACK');
							return null;
						}
						$res = mysqli_fetch_assoc($result);
						if($res['progress_id']!=4){
							if($data["bill"]==2){		// 支払区分（1:都度請求　2:月〆請求）
								$readytoship=1;	// 発送可
								$deposit=2;		// 入金済み
							}else{
								$sql2 = "select * from orders inner join customer on customer_id=customer.id where orders.id=".$data['orders_id'];
								$result = $this->exe_sql($conn, $sql2);
								if($result===false){
									mysqli_query($conn, 'ROLLBACK');
									return null;
								}
								$rec = mysqli_fetch_assoc($result);
								$payment = $rec['payment'];
								if($payment=='cash' || $payment=='cod'){
									$readytoship=1;	// 発送可
								}else{
									$readytoship=0;	// 発送不可
								}
								$deposit=1;		// 未入金
							}

							$sql = sprintf("update progressstatus set readytoship=%d, deposit=%d where orders_id=%d", $readytoship, $deposit, $data['orders_id']);
							if(!$this->exe_sql($conn, $sql)){
								mysqli_query($conn, 'ROLLBACK');
								return null;
							}
						}
					}

					$flg = false;
					break;

				case 'delivery':
					if(isset($data['delivery_id'])){
						$id = $data['delivery_id'];
					}else{
						$id = $data['id'];
					}
					if(isset($data['modify'])){
						// delivery_idの付け替え
						$sql= sprintf("UPDATE orders SET delivery_id=%d WHERE delivery_id=%d", $data["modify"],$id);
					}else{
						foreach($data as $key=>$val){
							$data[$key]	= $this->quote_smart($conn, $val);
						}
						$sql= sprintf("UPDATE delivery
								   SET organization='%s',
								   agent='%s',
								   team='%s',
								   teacher='%s',
								   delizipcode='%s',
								   deliaddr0='%s',
								   deliaddr1='%s',
								   deliaddr2='%s',
								   deliaddr3='%s',
								   deliaddr4='%s',
								   delitel='%s' WHERE id=%d",
									  $data["organization"],
									  $data["agent"],
									  $data["team"],
									  $data["teacher"],
									  $data["delizipcode"],
									  $data["deliaddr0"],
									  $data["deliaddr1"],
									  $data["deliaddr2"],
									  $data["deliaddr3"],
									  $data["deliaddr4"],
									  $data["delitel"],
									  $id);
					}
					break;

			}

			if($flg){
				$rs = $this->exe_sql($conn, $sql);
				if(!$rs){
					mysqli_query($conn, 'ROLLBACK');
					return null;
				}
			}

		}catch(Exception $e){
			mysqli_query($conn, 'ROLLBACK');
			$rs = null;
		}
		return $rs;
	}


	/**
	 * レコードの検索
	 * @param {string} table テーブル名
	 * @param {array} data 検索キーの配列
	 *
	 * @return 検索結果の配列
	 */
	private function search($conn, $table, $data){
		try{
			if(isset($data) && !is_array($data)){
				foreach($data as $key=>$val){
					$data[$key] = $this->quote_smart($conn, $val);
				}
			}
			$rs = array();
			$flg = true;
			switch($table){
				case 'numberOfBox':
				/**
				 *	1箱あたりの最大枚数
				 *	mypage.js で使用
				 *	$data1	{schedule2,package}
				 *	$data2	[{アイテムID,サイズID,枚数},{}, ...]
				 */
					list($data1, $data2) = $data;
					if(empty($data1['curdate'])){
						$data1['curdate'] = date('Y-m-d');
					}else{
						$d = explode('-', $data1['curdate']);
						if(checkdate($d[1], $d[2], $d[0])==false){
							$data1['curdate'] = date('Y-m-d');
						}
					}
					$box = 0;
					$rs = 0;
					for($i=0; $i<count($data2); $i++){
						$sql = "SELECT * FROM itemsize where itemsizeapply<='%s' and itemsizedate>'%s' and item_id=%d and size_from=%d limit 1";
						$sql = sprintf($sql, $data1['curdate'],$data1['curdate'],$data2[$i]['item_id'],$data2[$i]['size_id']);
						$result = $this->exe_sql($conn, $sql);
						while($rec = mysqli_fetch_assoc($result)){
							if(empty($rec['numberpack'])) continue;
							if($data1['package']=='yes'){
								$box += $data2[$i]['amount']/$rec['numberpack'];
							}else{
								$box += $data2[$i]['amount']/$rec['numbernopack'];
							}
						}
					}
					$rs = ceil($box);

					$flg = false;
					break;
			}

			if($flg){
				$result = $this->exe_sql($conn, $sql);
				while($res = mysqli_fetch_assoc($result)){
					$rs[] = $res;
				}
			}
		}catch(Exception $e){
			$rs = null;
		}

		return $rs;
	}

}

?>
