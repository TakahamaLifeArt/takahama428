<?php
/*------------------------------------------------------------

	File_name	: ordermail.php
	Description	: takahama428 web site send order mail class
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
				  2017-12-19 注文フロー改修に伴う更新
				  2018-04-01 アップロードファイル名の変更を廃止
				  2018-05-15 イメ画選択と後払いを追加

-------------------------------------------------------------- */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/http.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';

class Ordermail extends Conndb{

	/**
	 * 注文メール本文を生成
	 * @param {array} $args 注文データの配列 [$user, $design, $item, $option, $detail, $sum]
	 * @param {array} uploadfilename アップロードしたデザインファイルのパス
	 * @return {boolean} true:成功
	 *					 false:失敗
	 */
	public function send($args, $uploadfilename) {
		try {
			mb_internal_encoding("UTF-8");
			
			$user = json_decode($args['user'], true);
			$designs = json_decode($args['design'], true);
			$items = json_decode($args['item'], true);
			$opts = json_decode($args['option'], true);
			$detail = json_decode($args['detail'], true);
			$sum = json_decode($args['sum'], true);
			
			$obj = array(
				'user'=>$user,
				'design'=>$designs,
				'item'=>$items,
				'option'=>$opts,
				'detail'=>$detail,
				'sum'=>$sum,
			);
			
			// 受注システムに注文データを登録
			if (!empty($uploadfilename)) {
				// アップロードファイルのディレクトリ名を取得
				$uploadDir = basename(dirname($uploadfilename[0], 1));
			}
			
			// 受注システムから {orderid:受注番号, customerid:顧客ID} を受け取る
			$registered = $this->insertOrderToDB($obj, $uploadDir);
			$systemData = $registered[0];
			$order_id = $systemData['orderid'];
			
			/*
			* design: {
			*			デザインID(id_インデックス): {絵型ID: {
			*												front|back|side 絵型面: {
			*																		表示要素のインデックス: {
			*																			area:箇所名, 
			*																			size:0|1|2 大中小, 
			*																			option:0|1 インクジェット(淡|濃)と刺繍(origin|name)のオプション, 
			*																			method:プリント方法, 
			*																			printable:対応しているプリント方法
			*																			ink:色数
			*																		},
			*																		表示要素のインデックス: {}
			*											},
			*												front|back|side 絵型面: {}
			*										},
			*										絵型ID: {}
			*			},
			*			デザインID(id_インデックス): {}
			*	}
			*/
			/*
			* item: {デザインID: {アイテムID: {
			*								code:アイテムコード,
			*								name:アイテム名,
			*								posId:絵型ID,
			*								cateId:カテゴリID,
			*								rangeId:枚数レンジID
			*								screenId:同版分類ID
			* 								color: [{
			*										vol: {サイズ名: {amount:枚数, cost:単価}, ...},
			*										code: カラーコード,
			*										name: カラー名
			*										}, {}]
			* 								},
			*					  アイテムID: {}
			*					 },
			*		  デザインID: {}
			*		 }
			*/
			/*
			* attach: [ファイル名, ...]
			* option: {publish:割引率, student:割引率, pack:単価, payment:bank|cod|credit|cash|later_payment, delidate:希望納期, delitime:配達時間指定, express:0|1|2, transport:1|2}
			* sum: {item:商品代, print:プリント代, volume:注文枚数, tax:消費税額, total:見積合計, mass:0 通常単価 | 1 量販単価}
			* detail: {discountfee, discountname, packfee, packname, carriage, codfee, paymentfee, expressfee, expressname, rankname}
			* user: {id:, email:, name:, ruby:, zipcode:, addr0:, addr1:, addr2:, tel:, rank:}
			*/
			
			// 本文生成
			$order_info = "☆━━━━━━━━【　お申し込み内容　】━━━━━━━━☆\n\n";
			
			$order_info .= "┏━━━━━━━━┓\n";
			$order_info .= "◆　　ご希望納期\n";
			$order_info .= "┗━━━━━━━━┛\n";
			if (empty($opts['delidate'])) {
				$order_info .= "◇　納期指定なし\n\n";
			} else {
				$order_info .= "◇　納期　：　".$opts['delidate']."\n\n";
			}
			
			
			if (!empty($detail['expressfee'])) {
				$order_info .= "◇　".$detail['expressname']."：　特急料金あり\n\n";
			}
			
			$order_info .= "◇　配達時間指定　：　".$detail['delitimename']."\n\n";
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n";

			// デザインパターン別
			$faceName = array('front'=>'前面', 'back'=>'背面', 'side'=>'側面');
			$methodName = array('silk'=>'シルクスクリーン', 'digit'=>'デジタルコピー転写', 'inkjet'=>'インクジェット', 'cutting'=>'カッティング', 'emb'=>'刺繍', 'recommend'=>'おまかせ');
			$silkSizeName = array('通常版', 'ジャンボ版', 'スーパージャンボ版');
			$embOptionName = array('デザイン', 'ネーム');
			$printSizeName = array('大', '中', '小');
			$inks = ['','','','', '以上'];
			$orderAmount = 0;
			$pattern = 1;
			foreach ($designs as $designId=>$v1) {
				$order_info .= "◆　　デザインパターン - ".$pattern++."\n\n";
				
				$order_info .= "┏━━━━━━━┓\n";
				$order_info .= "◆　　商品情報\n";
				$order_info .= "┗━━━━━━━┛\n\n";
				
				// 当該デザインに対応するアイテム
				foreach ($items[$designId] as $itemId=>$itemInfo) {
					$order_info .= "◆アイテム：　".$itemInfo['name']."\n";
					for ($i=0; $i<count($itemInfo['color']); $i++) {
						$order_info .= "◇カラー：　".$itemInfo['color'][$i]['code']." ".$itemInfo['color'][$i]['name']."\n";
						$order_info .= "◇サイズと枚数\n";
						foreach ($itemInfo['color'][$i]['vol'] as $sizeName=>$v2) {
							$order_info .= $sizeName."　：　".$v2['amount']."枚\n";
							$orderAmount += $v2['amount'];
						}
					}
					$order_info .= "--------------------\n";
				}
				$order_info .= "\n\n";
				
				$order_info .= "┏━━━━━━━━━┓\n";
				$order_info .= "◆　　プリント情報\n";
				$order_info .= "┗━━━━━━━━━┛\n";
				
				// プリント情報
				if ($designId=='id_0') {
					$order_info .= "プリントなし\n";
				} else {
					foreach ($v1 as $posId=>$v2) {

						foreach ($v2 as $face=>$v3) {

							foreach ($v3 as $idx=>$designInfo) {
								$order_info .= "◇プリント位置：　".$faceName[$face].' '.$designInfo['area']."\n";
								$order_info .= "◇デザインの色数：　".$designInfo['ink'].$inks[$designInfo['ink']]."\n";
								$order_info .= "◇プリント方法：　".$methodName[$designInfo['method']]."\n";
								$order_info .= "◇プリントサイズ：　";
								if ($designInfo['method']=='silk') {
									$order_info .= $silkSizeName[$designInfo['size']]."\n";
								} else if ($designInfo['method']!='recommend') {
									$order_info .= $printSizeName[$designInfo['size']]."\n";
								}
								$order_info .= "◇オプション：　";
								if ($designInfo['method']=='emb') {
									$order_info .= $embOptionName[$designInfo['option']];
								}
								$order_info .= "\n";
							}
						}
					}
				}
				$order_info .= "------------------------------------------\n\n";
			}
			$order_info .= "◆枚数合計：　".number_format($orderAmount)." 枚\n";
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			
			
			$order_info .= "┏━━━━━━━━━┓\n";
			$order_info .= "◆　　イメージ画像\n";
			$order_info .= "┗━━━━━━━━━┛\n";
			if (empty($opts['imega'])) {
				$order_info .= "◇イメ画：　作成しない\n";
			} else {
				$order_info .= "◇イメ画：　作成する\n";
			}
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			
			
			$order_info .= "┏━━━━━┓\n";
			$order_info .= "◆　　包装\n";
			$order_info .= "┗━━━━━┛\n";
			if (empty($opts['pack'])) {
				$order_info .= "◇たたみ・袋詰め：　まとめて包装\n";
			} else if ($opts['pack']==50){
				$order_info .= "◇たたみ・袋詰め：　個別包装\n";
			} else {
				$order_info .= "◇たたみ・袋詰め：　袋を同封\n";
			}
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			
			
			$order_info .= "┏━━━━━┓\n";
			$order_info .= "◆　　割引\n";
			$order_info .= "┗━━━━━┛\n";
			
			if (empty($detail['discountname'])) {
				$order_info .= "◇割引：　なし\n";
			} else {
				$order_info .= "◇割引：　".$detail['discountname']."\n";
			}
			
			$order_info .= "◇学校名：　".$opts['school']."\n";
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			
			
			$order_info .= "┏━━━━━━━━┓\n";
			$order_info .= "◆　　お客様情報\n";
			$order_info .= "┗━━━━━━━━┛\n";
			$order_info .= "◇お名前：　".$user['name']."　様\n";
			$order_info .= "◇フリガナ：　".$user['ruby']."　様\n";
			$order_info .= "◇ご住所：　〒".$user['zipcode']."\n";
			$order_info .= "　　　　　　　　".$user['addr0']."\n";
			$order_info .= "　　　　　　　　".$user['addr1']."\n";
			$order_info .= "　　　　　　　　".$user['addr2']."\n";
			$order_info .= "◇TEL：　".$user['tel']."\n";
			$order_info .= "◇E-Mail：　".$user['email']."\n";
			$order_info .= "------------------------------------------\n\n";
			
			
			if (empty($opts['publish'])) {
				$order_info .= "◇デザイン掲載：　掲載不可\n\n";
			} else {
				$order_info .= "◇デザイン掲載：　掲載可\n\n";
			}
			
			$order_info .= "◇デザインについてのご要望など：\n";
			if(empty($opts['note_design'])){
				$order_info .= "なし\n";
			}else{
				$order_info .= $opts['note_design']."\n";
			}
			$order_info .= "------------------------------------------\n\n";
			
			
			$payment = array('bank'=>'銀行振込', 'cod'=>'代金引換', 'credit'=>'カード決済', 'later_payment'=>'後払い');
			$order_info .= "◇お支払方法：　".$payment[$opts['payment']]."\n\n";
			
			
			$order_info .= "◇ご要望・ご質問など：\n";
			if(empty($opts['note_user'])){
				$order_info .= "なし\n\n";
			}else{
				$order_info .= $opts['note_user']."\n\n";
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
				$uploadCount = count($uploadfilename);
				if ($uploadCount===0) {
					$order_info_admin .= "入稿データのアップロードが正常に完了していません。\n";
					$order_info_admin .= "----------\n\n";
				} else {
					for ($a=0; $a<$uploadCount; $a++) {
						$fname = basename($uploadfilename[$a]);
						$order_info_user .= "◇ファイル名：　".rawurldecode($fname)."\n";
						$order_info_admin .= "◇ファイル名：　"._ORDER_DOMAIN."/system/attachfile/".$order_id."/".$fname."\n";
						$order_info_admin .= "◇元ファイル名：　".rawurldecode($fname)."\n";
						$order_info_admin .= "------------------------------------------\n\n";
					}
				}
				
				if (empty($order_id)) {
					$order_info_admin .= "\n===  Error  ===\n";
					$order_info_admin .= "\n◇ 注文データの登録中に通信エラーが発生しています。\n";
					$order_info_admin .= "\n===\n\n";
					
					$order_info_admin .= "【 入稿ファイル 】\n";
					for ($b=0; $b<count($uploadfilename); $b++) {
						$fname = basename($uploadfilename[$b]);
						$order_info_admin .= "◇URL：　".$_ORDER_DOMAIN."/system/attachfile/".$uploadDir."/".$fname."\n\n";
						$order_info_admin .= "------------------------------------------\n\n";
					}
				}
				$order_info_admin .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
				$order_info_user .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			}
			
			$order_info_admin .= "┏━━━━━━━━┓\n";
			$order_info_admin .= "◆　　プリント\n";
			$order_info_admin .= "┗━━━━━━━━┛\n";
			$order_info_admin .= "◇プリント代：　".number_format($sum['print'])."円\n\n";
			$order_info_admin .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			
			$order_info_admin .= "┏━━━━━━━━┓\n";
			$order_info_admin .= "◆　　同梱情報\n";
			$order_info_admin .= "┗━━━━━━━━┛\n";
			if (count($registered)==1) {
				$order_info_admin .= "◇受注No.：　なし\n";
			} else {
				for ($i=0; $i<count($registered); $i++) {
					if ($order_id==$registered[$i]['orderid']) continue;
					$order_info_admin .= "◇受注No.：　".$registered[$i]['orderid']."\n";
				}
			}
			$order_info_admin .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";
			
			$addition = array($order_info_admin, $order_info_user, $order_id);
			
			// send mail
			$res = $this->send_mail($order_info, $user['name'], $user['email'], $attach, $addition);
			if (!$res) {
				throw new Exception();
			}
			
			return $res;
			
		}catch (Exception $e) {
			return false;
		}
	}


	/**
	 * 追加注文メール本文を生成
	 * @param {array} $args 注文データの配列
	 * @return {boolean} true:成功
	 *					 false:失敗
	 */
	public function sendReorder($args) {
		try {
			mb_internal_encoding("UTF-8");

			$mailBody['tpl-title'] = "追加注文のお申し込み";
			$mailBody['tpl-replyhead'] = "このたびは、タカハマライフアートをご利用いただき誠にありがとうございます。";
			
			// 本文生成
			$order_info = "☆━━━━━━━━【　お申し込み内容　】━━━━━━━━☆\n\n";

			$order_info .= "◆元受注No." . $args['ex-orderid'] . "\n\n";
			
			$order_info .= "┏━━━━━━━━┓\n";
			$order_info .= "◆　　ご希望納期\n";
			$order_info .= "┗━━━━━━━━┛\n";
			if (empty($args['delidate'])) {
				$order_info .= "◇　納期指定なし\n\n";
			} else {
				$order_info .= "◇　納期　：　".$args['delidate']."\n\n";
			}

			$order_info .= "◇　配達時間指定　：　".$args['delitime']."\n\n";
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n";

			$order_info .= "┏━━━━━━━┓\n";
			$order_info .= "◆　　商品情報\n";
			$order_info .= "┗━━━━━━━┛\n\n";
			for($i=0, $len=count($args['item']); $i<$len; $i++){
				$order_info .= "◆アイテム：　".$args['item'][$i]['name']."\n";
				$order_info .= "◇カラー：　".$args['item'][$i]['color']."\n";
				$order_info .= "◇サイズ：　".$args['item'][$i]['size']."\n";
				$order_info .= "◇枚数：　".$args['item'][$i]['amount']." 枚\n";
				$order_info .= "--------------------\n\n";
			}
			$order_info .= "\n\n";

			$order_info .= "◆枚数合計：　".number_format($args['order_amount'])." 枚\n";
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";

			$order_info .= "┏━━━━━┓\n";
			$order_info .= "◆　　包装\n";
			$order_info .= "┗━━━━━┛\n";
			if (empty($args['pack'])) {
				$order_info .= "◇たたみ・袋詰め：　まとめて包装\n";
			} else if ($args['pack']==50){
				$order_info .= "◇たたみ・袋詰め：　個別包装\n";
			} else {
				$order_info .= "◇たたみ・袋詰め：　袋を同封\n";
			}
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n\n";

			$order_info .= "┏━━━━━━━━┓\n";
			$order_info .= "◆　　お客様情報\n";
			$order_info .= "┗━━━━━━━━┛\n";
			$order_info .= "◇顧客ID：　".$args['number']."\n";
			$order_info .= "◇お名前：　".$args['name']."　様\n";
			$order_info .= "◇ご住所：　〒".$args['zipcode']."\n";
			$order_info .= "　　　　　　　　".$args['addr']."\n";
			$order_info .= "◇TEL：　".$args['tel']."\n";
			$order_info .= "◇E-Mail：　".$args['email']."\n";
			$order_info .= "------------------------------------------\n\n";

			$order_info .= "◇メッセージ：\n";
			if(empty($args['message'])){
				$order_info .= "なし\n\n";
			}else{
				$order_info .= $args['message']."\n\n";
			}
			$order_info .= "━━━━━━━━━━━━━━━━━━━━━\n\n";

			// send mail
			$res = $this->send_reorder_mail($order_info, $args['name'], $args['email']);
			if (!$res) {
				throw new Exception('Could not send e-mail.');
			}

			return array('send'=>'success');

		}catch (Exception $e) {
			$res = array('send'=>'error', 'message'=>$e->getMessage());
		}
	}


	/**
	 * 追加注文メール送信
	 * @param {string} mail_text	顧客情報と注文内容
	 * @param {string} name			お客様の名前
	 * @param {string} to			返信先のメールアドレス
	 * @param {array} attach		添付ファイル情報
	 * @return {boolean} true:送信成功 , false:送信失敗
	 */
	protected function send_reorder_mail($mail_text, $name, $to, $attach=null){
		mb_language("japanese");
		mb_internal_encoding("UTF-8");
		$sendto = _ORDER_EMAIL;

		$subject = '追加注文のお申し込み【takahama428】';
		$msg = "";
		$boundary = md5(uniqid(rand()));

		$fromname = "タカハマ428";
		$from = mb_encode_mimeheader($fromname, "JIS")."<".$sendto.">";

		$header = "From: $from\n";
		$header .= "Reply-To: $from\n";
		$header .= "X-Mailer: PHP/".phpversion()."\n";
		$header .= "MIME-version: 1.0\n";

		if(!empty($attach)){
			$header .= "Content-Type: multipart/mixed;\n";
			$header .= "\tboundary=\"$boundary\"\n";
			$msg .= "This is a multi-part message in MIME format.\n\n";
			$msg .= "--$boundary\n";
			$msg .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$msg .= "Content-Transfer-Encoding: 7bit\n\n";
		}else{
			$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$header .= "Content-Transfer-Encoding: 7bit\n";
		}

		$msg .= mb_convert_encoding($mail_text, "JIS","UTF-8");

		if(!empty($attach)){
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
			$msg .= "追加注文のお申し込みを承りました。\n";
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

			$msg .= $mail_text;

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
	 * メール送信
	 * @param {string} mail_text	顧客情報と注文内容
	 * @param {string} name			お客様の名前
	 * @param {string} to			返信先のメールアドレス
	 * @param {array} attach		添付ファイル情報
	 * @param {array} addition		本文への追加[注文メール, 顧客への返信, 受注No.]
	 * @return {boolean} true:送信成功 , false:送信失敗
	 */
	protected function send_mail($mail_text, $name, $to, $attach, $addition){
		mb_language("japanese");
		mb_internal_encoding("UTF-8");
		$sendto = _ORDER_EMAIL;
		
		$suffix = "【takahama428】";
		$subject = "お申し込み - No.".$addition[2].$suffix;		// 件名
		$msg = "";
		$boundary = md5(uniqid(rand()));
		
		$fromname = "タカハマ428";
		$from = mb_encode_mimeheader($fromname, "JIS")."<".$sendto.">";
		
		$header = "From: $from\n";
		$header .= "Reply-To: $from\n";
		$header .= "X-Mailer: PHP/".phpversion()."\n";
		$header .= "MIME-version: 1.0\n";
		
		if(!empty($attach)){
			$header .= "Content-Type: multipart/mixed;\n";
			$header .= "\tboundary=\"$boundary\"\n";
			$msg .= "This is a multi-part message in MIME format.\n\n";
			$msg .= "--$boundary\n";
			$msg .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$msg .= "Content-Transfer-Encoding: 7bit\n\n";
		}else{
			$header .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
			$header .= "Content-Transfer-Encoding: 7bit\n";
		}
		
		// ここで本文をエンコードして設定
		$msg .= mb_convert_encoding("受注No.".$addition[2]."\n\nお名前：　".$name."　様\n".$mail_text.$addition[0], "JIS","UTF-8");
		
		if(!empty($attach)){
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
	 * @param {array} obj 注文情報 {$user, $design, $item, $option, $detail, $sum}
	 * @param {array} uploadDir アップロードしたデザインファイルのディレクトリ
	 * @return {array} [{orderid=>受注No. , customerid=>顧客ID}, ...]
	 */
	private function insertOrderToDB($obj, $uploadDir){
		$user = $obj['user'];
		$designs = $obj['design'];
		$items = $obj['item'];
		$opts = $obj['option'];
		$detail = $obj['detail'];
		$sum = $obj['sum'];

		// 顧客情報
		$customer_id = "";
		if(empty($user['id'])){
			// 新規顧客
			$data1 = array(
				"number"=>"","cstprefix"=>"k","customer_id"=>"",
				"customerruby"=>$user['ruby'],"companyruby"=>"","customername"=>$user['name'],"company"=>"",
				"tel"=>$user['tel'],"mobile"=>"","fax"=>"","email"=>$user['email'],"password"=>$user['pass'],"mobmail"=>"",
				"bill"=>"1","cutofday"=>"20","cyclebilling"=>"1","paymentday"=>"31","remittancecharge"=>"1",
				"zipcode"=>$user['zipcode'],"addr0"=>$user['addr0'],"addr1"=>$user['addr1'],"addr2"=>$user['addr2'],"addr3"=>"","addr4"=>"","reg_site"=>_SITE
			);
		} else {
			// 会員
			$customer_id = $user['id'];
			$data1 = array(
				"customer_id"=>$customer_id,
				"customerruby"=>$user['ruby'],"customername"=>$user['name'],
				"tel"=>$user['tel'],"email"=>$user['email'],
				"zipcode"=>$user['zipcode'],"addr0"=>$user['addr0'],"addr1"=>$user['addr1'],"addr2"=>$user['addr2'],"reg_site"=>_SITE
			);
		}

		// お届け先情報
		$data2 = array();

		// 写真掲載割（旧ブログ割）
		if(empty($opts['publish'])){
			$discount = "blog0";
		}else{
			$discount = "blog1";
		}

		// 学割
		if(empty($opts['student']) || empty($opts['school'])){
			$discount1 = "";
		} else {
			$discount1 = "student";
		}

		// 支払方法
		$payment = $opts['payment'];
		if ($payment=='bank') $payment = 'wiretransfer';
		

		// デザインパターン毎の注文枚数
		$amounts = $this->getOrderAmount($items);

		// 受注システム登録クラス
		$orders = new WebOrder();
		
		/*
		* design: {
		*			デザインID(id_インデックス): {絵型ID: {
		*												front|back|side 絵型面: {
		*																		表示要素のインデックス: {
		*																			area:箇所名, 
		*																			size:0|1|2 大中小, 
		*																			option:0|1 インクジェット(淡|濃)と刺繍(origin|name)のオプション, 
		*																			method:プリント方法, 
		*																			printable:対応しているプリント方法
		*																			ink:色数
		*																		},
		*																		表示要素のインデックス: {}
		*											},
		*												front|back|side 絵型面: {}
		*										},
		*										絵型ID: {}
		*			},
		*			デザインID(id_インデックス): {}
		*	}
		*/
		/*
		* item: {デザインID: {アイテムID: {
		*								master:マスターID,
		*								code:アイテムコード,
		*								name:アイテム名,
		*								posId:絵型ID,
		*								cateId:カテゴリID,
		*								rangeId:枚数レンジID
		*								screenId:同版分類ID
		* 								color: [{
		*										vol: {サイズ名: {amount:枚数, cost:単価, id:サイズID}, ...},
		*										code: カラーコード,
		*										name: カラー名
		*										}, {}]
		* 								},
		*					  アイテムID: {}
		*					 },
		*		  デザインID: {}
		*		 }
		*/
		
		/**
		 * デザインパターン毎にデータを登録
		 * アップロードファイルは最初の受注データに紐付ける
		 */
		$orderNumber = 0;	// 受注システムに登録する注文伝票の数、複数の場合は同梱扱い
		$res = [];			// 登録データの返り値
		foreach ($designs as $designId=>$d1) {

			// 同梱扱いで且つ新規ユーザーの場合、新規登録した顧客IDを使用
			if (empty($customer_id) && $orderNumber>0) {
				$customer_id = $res[0]['customerid'];
				$data1 = array(
					"customer_id"=>$customer_id,
					"customerruby"=>$user['ruby'],"customername"=>$user['name'],
					"tel"=>$user['tel'],"email"=>$user['email'],
					"zipcode"=>$user['zipcode'],"addr0"=>$user['addr0'],"addr1"=>$user['addr1'],"addr2"=>$user['addr2'],"reg_site"=>_SITE
				);
			}
			
			// プリントの有無
			$noprint = $designId=='id_0'? 1: 0;
			
			// ランク割はプリントありの時だけ適用
			$rank = $noprint==1? 0: $user["rank"];

			// 見積
			$basefee = 0;
			$salestax = 0;
			$total = 0;
			$credit = 0;
			$perone = 0;

			// コメント欄
			$comment[] = $opts['note_design'];
			$comment[] = $opts['note_user'];
			$comment[] = $opts['school'];
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
			"free_discount","additionalfee","extradiscount","rakuhan","completionimage","imega",
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
			"designfee","codfee","paymentfee","basefee","salestax","creditfee", "conbifee","repeatdesign","allrepeat");

			$data3 = array
			("","0","0",$strComment,"",
			"0","0",$opts['delitime'],"","","",
			"",
			"",$detail["rankname"],"0","0","0",
			"1","general","","","",$opts["delidate"],

			"0","normal",$amounts[$designId],$noprint,"","",
			$discount1,$discount2,"0","","0",$payment,

			"accept","0","2","","0","その他","0",
			"0","0",$rank,"0","0",$opts['imega'],
			"0","",$customer_id,$total,$amounts[$designId],

			"","0","0","0",
			empty($opts['pack'])? 1: 0,
			$opts['pack']!=10? 0: 1,
			$opts['pack']!=10? 0: $amounts[$designId],
			$opts['pack']!=50? 0: 1,
			$opts['pack']!=50? 0: $amounts[$designId],
			$discount,"","",

			$sum["item"],$sum["print"],"0","0","0",
			"0","0","0","0","0",$detail["packfee"],

			 $detail["expressfee"],$detail["discountfee"],"0",$detail["carriage"],"0",
			 "0", $detail["codfee"], $detail["paymentfee"], $basefee, $salestax, $credit, "0","0","0");

			/*
			* attach: [ファイル名, ...]
			* option: {publish:割引率, student:割引率, pack:単価, payment:bank|cod|credit|cash|later_payment, delidate:希望納期, delitime:配達時間指定, 
			*			express:0|1|2, transport:1|2, note_design, note_user}
			* sum: {item:商品代, print:プリント代, volume:注文枚数, tax:消費税額, total:見積合計, mass:0 通常単価 | 1 量販単価}
			* detail: {discountfee, discountname, packfee, packname, carriage, codfee, paymentfee, expressfee, expressname, rankname}
			* user: {id:, email:, name:, ruby:, zipcode:, addr0:, addr1:, addr2:, tel:, rank:}
			*/

			/*
			*	data4	業者の時の注文商品（orderitem）
			*	data5	業者の時の見積追加行（orderitemext）
			*	data6	プリント情報（orderprint）
			*	data7	プリント位置（orderarea）
			*	data8	プリントポジション（orderselectivearea）
			*/
			$field4 = array("master_id","choice","plateis","size_id","amount","item_cost","item_printfee","item_printone","item_id","item_name","stock_number","maker","size_name","item_color","price");
			$field5 = array();
			$field6 = array("category_id","printposition_id","subprice");
			$field7 = array("areaid", "print_id", "area_name", "area_path", "origin", "ink_count", "print_type","areasize_from", "areasize_to", "areasize_id", "print_option", "jumbo_plate", "design_plate","design_type","design_size", "repeat_check", "silkmethod");
			$field8 = array("selectiveid", "area_id", "selective_key", "selective_name");

			//注文商品
			$data4 = array();
			$data5 = array();

			//商品カテゴリーごとのプリント情報
			//data6
			$orderprint = array();

			// 同じカテゴリで且つ同じプリントポジションIDの有無をチェック
			$isExistOrderPrint = array();

			//data7
			$orderarea = array();

			//data8
			// プリントなしの場合は登録なし
			$orderselectivearea = array();

			$attach_info = array();
			$orderprint_id = 0;	// orderprint table のID
			$orderarea_id = 0;	// orderarea table のID
			$origin = 1;

			// シルクの版の大きさ
			$silkPlateSize = array(
				array('h'=>35, 'w'=>27),
				array('h'=>43, 'w'=>32),
				array('h'=>52, 'w'=>30),
			);
			
			// 当該デザインに対応するアイテム情報
			foreach ($items[$designId] as $itemId=>$itemInfo) {
				
				/* 
				 * カテゴリ別の絵型ごとに登録
				 */
				$tmpKey = $itemInfo['cateId'].'-'.$itemInfo['posId'];
				if (array_key_exists($tmpKey, $isExistOrderPrint)===false) {
					
					// orderprint
					$orderprint[] = $itemInfo['cateId']."|".$itemInfo['posId']."|0";
					$isExistOrderPrint[$tmpKey] = true;
					
					// 絵型毎のプリント情報
					foreach ($d1[$itemInfo['posId']] as $face=>$d2) {

						// 絵型画像のパス情報を取得
						$pattern = $this->getPrintPosition($itemInfo['posId'], 'pos');
						
						// プリント箇所毎
						$origin = 1;
						foreach ($d2 as $idx=>$designInfo) {
							/*
							 * orderarea
							 * orderprintの絵型に対するプリント指定情報
							 */
							if ($designInfo['method']=='emb') {
								$printType = 'embroidery';
							} else {
								$printType = $designInfo['method'];
							}

							if ($printType=='silk') {
								$jumboPlate = $designInfo['size'];
								$plateHeight = $silkPlateSize[$jumboPlate]['h'];
								$plateWidth = $silkPlateSize[$jumboPlate]['w'];
							} else {
								$plateHeight = 0;
								$plateWidth = 0;
								$jumboPlate = 0;
							}

							$tempData7 = "0|".$orderprint_id."|".$face."|".$pattern[0]['category']."/".$pattern[0]['item']."|".$origin."|";
							$tempData7 .= $designInfo['ink']."|".$printType."|".$plateHeight."|".$plateWidth."|".$designInfo['size']."|";
							$tempData7 .= $designInfo['option']."|".$jumboPlate."|1|||0|1";
							$orderarea[] = $tempData7;

							// orderselectivearea プリントなしの場合は登録なし
							if ($designId != 'id_0') {
								$r = $this->request('POST', array('act'=>'matchpattern', 'posid'=>$itemInfo['posId'], 'face'=>$face, 'name'=>$designInfo['area']));
								$data8 = unserialize($r);
								$orderselectivearea[] = "0|".$orderarea_id."|".$data8['code']."|".$data8['area'];
							}
							
							$orderarea_id++;
							
							// 同じ絵型面が2つ以上ある場合
							$origin = 0;
						}
						
					}
					
					$orderprint_id++;
				}
				
				// orderitem
				for ($i=0; $i<count($itemInfo['color']); $i++) {
					$colors = $itemInfo['color'][$i];
					foreach ($colors['vol'] as $sizeName=>$val) {
						$data4[] = $colors['master']."|1|1|".$val['id']."|".$val['amount']."|".$val['cost']."|0|0|".$itemId."||||||" ;
					}
				}
			}
			
			// Web注文で未使用のデータ
			$field9 = array("inkid", "area_id", "ink_name", "ink_code", "ink_position");
			$orderink = array();
			$field10= array("exchid","ink_id","exchink_name","exchink_code","exchink_volume");
			$exchink = array();
			$field12 = array();
			$data12 = array();

			// アップロードファイル
			$upDir = '';
			if (!empty($uploadDir) && $orderNumber===0) {
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
			$res[] = $orders->db('insert', 'order', $data);
			
			$orderNumber++;
		}
		
		return $res;
	}
	
	
	/**
	 * デザインパターン毎の注文商品の枚数と単価
	 * @param {array} items Web注文の商品データー
	 * @return {array} デザインパターンごとの注文枚数 {designId: 注文枚数}
	 */
	private function getOrderAmount($items) {
		foreach ($items as $designId=>$v1) {
			$orderAmount[$designId] = 0;
			foreach ($v1 as $itemId=>$itemInfo) {
				for ($i=0; $i<count($itemInfo['color']); $i++) {
					foreach ($itemInfo['color'][$i]['vol'] as $sizeName=>$v2) {
						$orderAmount[$designId] += $v2['amount'];
					}
				}
			}
		}
		return $orderAmount;
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
	 * @return {array} {orderid=>受注No. , customerid=>顧客ID}
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
							$customer_id = $data1["customer_id"];
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
					factory, destcount, repeatdesign, allrepeat, staffdiscount, imega)
								VALUES(%d,'%s',%d,'%s','%s','%s','%s','%s',%d,'%s',
								'%s',%d,%d,'%s','%s','%s','%s',%d,'%s','%s',
								%d,'%s','%s','%s','%s','%s',%d,%d,%d,'%s',
								'%s',%d,%d,'%s',%d,%d,%d,%d,'%s','%s',
								'%s','%s',%d,%d,%d,%d,%d,%d,'%s','%s',
								%d,'%s',%d,%d,%d,%d,%d,%d,%d,%d,
								%d,%d,%d,%d,%d,%d)",
								$info3["reception"],$info3["ordertype"],$info3["applyto"],$info3["maintitle"],$info3["schedule1"],
								$info3["schedule2"],$info3["schedule3"],$info3["schedule4"],$info3["destination"],$info3["arrival"],
								$info3["carriage"],$info3["check_amount"],$info3["noprint"],$info3["design"],$info3["manuscript"],
								$info3["discount1"],$info3["discount2"],$info3["reduction"],$info3["reductionname"],$info3['handover'],
								$info3["freeshipping"],$info3["payment"],$info3["order_comment"],$info3["invoicenote"],$info3["billnote"],
								$info3["phase"],$info3["budget"],$info3["customer_id"],$info3["delivery_id"],$info3["created"],
								$info3["lastmodified"],$info3["estimated"],$info3["order_amount"],$info3["paymentdate"],$info3["exchink_count"],
								$info3["exchthread_count"],$info3["deliver"],$info3["deliverytime"],$info3["manuscriptdate"],$info3["purpose"],
								$info3["purpose_text"],$info3["job"],$info3["designcharge"],$info3["repeater"],$info3["reuse"],
								$info3["free_discount"],$info3["free_printfee"],$info3["completionimage"],$info3["contact_number"],$info3["additionalname"],
								$info3["additionalfee"],$info3["extradiscountname"],$info3["extradiscount"],$info3["shipfrom_id"],$info3["package_yes"],
								$info3["package_no"],$info3["package_nopack"],$info3["pack_yes_volume"],$info3["pack_nopack_volume"],$info3["boxnumber"],
								$info3["factory"],$info3["destcount"],$info3["repeatdesign"],$info3["allrepeat"],$info3["staffdiscount"],$info3["imega"]
							);

					if($this->exe_sql($conn, $sql)){
						$rs = mysqli_insert_id($conn);
						$orders_id = $rs;

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
									if($data8[$s]['area_id']!=$t) continue;
									$sql = sprintf("INSERT INTO orderselectivearea(orderarea_id,selective_key,selective_name) VALUES(%d,'%s','%s')",
												   $orderarea_id,
												   $data8[$s]['selective_key'],
												   $data8[$s]['selective_name']);
									if(!$this->exe_sql($conn, $sql)){
										mysqli_query($conn, 'ROLLBACK');
										return null;
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

									/* exchange ink
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
								extracarryfee,designfee,codfee,paymentfee,conbifee,basefee,salestax,creditfee,orders_id)
							   VALUES(%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)",
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
										   $info3["paymentfee"],
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

					// 入稿データがアップロードされている場合
					$isUpload = true;
					if($upDir != ""){
						$oldPath = $_SERVER['DOCUMENT_ROOT'].'/../../'._ORDER_VHOST.'/home/system/attachfile/'.$upDir;
						$newPath = $_SERVER['DOCUMENT_ROOT'].'/../../'._ORDER_VHOST.'/home/system/attachfile/'.$orders_id;
						
						// 2018-04-01 廃止
						// ファイル名を変更（design_受注ID_YYY-MM-DD_連番）
//						$fileName = array();
//						$up = 0;
//						$today = date('Y-m-d');
//						$root = $_SERVER['DOCUMENT_ROOT']."/";
//						if ($handle = opendir($oldPath)) {
//							setlocale(LC_ALL, 'ja_JP.UTF-8');
//							while (false !== ($f = readdir($handle))) {
//								if (is_dir($oldPath.'/'.$f)==false && $f != "." && $f != "..") {
//									$extension = pathinfo($f, PATHINFO_EXTENSION);
//									$newFileName = 'design_'.$orders_id.'_'.$today.'_'.$up.'.'.$extension;
//									if (rename($oldPath.'/'.$f, $oldPath.'/'.$newFileName)) {
//										$fileName[$up++] = $newFileName;
//									}
//								}
//							}
//							closedir($handle);
//						}
						
						// ディレクトリ名を受注IDに変更
						$isUpload = rename($oldPath, $newPath);
					}
					
					return array('orderid'=>$orders_id, 'customerid'=>$customer_id);
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
								if($payment=='cash' || $payment=='cod' || $payment=='later_payment'){
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
