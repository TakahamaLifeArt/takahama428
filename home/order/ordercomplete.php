<?php
ini_set('memory_limit', '256M');
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
require_once dirname(__FILE__).'/ordermail.php';

// ユーザー名
if ($_POST['user']) {
	$user = json_decode($_POST['user'], true);
	$userName = $user['name'];
	$email = $user['email'];
	$purchaseId = $user['number'];
}

if ( isset($userName, $_POST['ticket'], $_SESSION['ticket']) ) {
	$obj = array(
		'user'=>$_POST['user'],
		'design'=>$_POST['design'],
		'item'=>$_POST['item'],
		'option'=>$_POST['option'],
		'detail'=>$_POST['detail'],
		'sum'=>$_POST['sum'],
	);
	
	$items = json_decode($_POST['item'], true);
	foreach ($items as $designId => $item) {
		foreach ($item as $itemId=>$itemInfo) {
			for ($i=0; $i<count($itemInfo['color']); $i++) {
				foreach ($itemInfo['color'][$i]['vol'] as $sizeName=>$v2) {
					$itemNums[] = $v2['amount'];
					$itemIds[] = $itemInfo['code'];
					$prices[] = $v2['cost'];
					$sizes[] = $sizeName;
				}
			}
		}
	}
	$itemNum = rawurlencode(implode('#', $itemNums));
	$itemId = rawurlencode(implode('#', $itemIds));
	$price = rawurlencode(implode('#', $prices));
	$size = rawurlencode(implode('#', $sizes));
	
	if (empty($_POST['attach'])) {
		$attach = [];
	} else {
		$attach = json_decode($_POST['attach'], true);
	}
	
	$ordermail = new Ordermail();
	$isSend = $ordermail->send($obj, $attach);
} else {
	$isSend = false;
	
	$itemNum = '';
	$itemId = '';
	$price = '';
	$size = '';
}

/* セッションを破棄 */
if ($isSend) {
	unset($_SESSION['ticket']);
//	$_SESSION['orders'] = array();
//	setcookie(session_name(), "", time()-86400, "/");
//	unset($_SESSION['orders']);
} else {
	if (!isset($_SESSION['ticket'])) {
		$errMessage = "お手数ですが、お申し込みページを再読み込みしてください。";
	} else {
		$errMessage = "お申し込みメールの送信中にエラーが発生いたしました。";
	}
}

?>
<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta name="Description" content="WebでカンタンにオリジナルTシャツのお申し込みができます。簡単入力で瞬時に料金の目安がわかります！トレーナー・ポロシャツ・オリジナルTシャツの作成・プリントは、東京都葛飾区のタカハマライフアートにお任せ下さい！" />
		<meta name="keywords" content="注文,お申し込み,オリジナル,Tシャツ,早い,東京" />
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
		<title>お申し込みメールの送信完了｜ オリジナルTシャツ【タカハマライフアート】</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="./css/finish_responsive.css" media="screen" />
	</head>

	<body>

		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">
				<?php
				$cst = 'cst';
				function cst($constant){
					return $constant;
				}
				
				if($isSend){
					$heading = 'お申し込み内容の確認メールを返信中です。<br>必ずご確認ください！';
					$sub = 'Sending';
					$html = <<<DOC
				<div class="inner">
					<p>{$userName}　様</p>
					<p>この度はタカハマライフアートをご利用いただき、誠にありがとうございます。</p>
				</div>
				
				<div class="remarks">
					<h3>制作の開始について</h3>
					<p>現時点では、<span class="highlights">ご注文は確定していません。</span></p>
					<p>
						制作を開始するにあたり、お電話によるデザインの確認をもって注文確定とさせていただいております。<br>
						弊社から御見積りメールをお送りいたしますので、
						大変お手数ですが、ご確認後フリーダイヤル <ins> {$cst(_TOLL_FREE)} </ins>までご連絡ください。
					</p>
				</div>
				
				<div class="inner">
					<h3>【 <span class="highlights">確認メールが届かない場合</span> 】</h3>
					<p>
						ご入力頂いたメールアドレス {$email} 宛に、お申し込み内容の確認メールを送信しています。<br>
						お客様に確認メールが届いていない場合、弊社にお申し込みメールが届いていない可能性がございますので、<br>
						恐れ入りますが、フリーダイヤル<ins> {$cst(_TOLL_FREE)} </ins>までお問い合わせ下さい。
					</p>
				</div>
				
				<div class="inner">
					<h3>【 ご注文に関するお問い合わせ 】</h3>
					<p>
						お急ぎのお客様は、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。
					</p>
					<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>

DOC;
				}else{
					$heading = '送信エラー！';
					$sub = 'Error';
					$html = <<<DOC
				<div class="inner">
					<p>{$userName}　様</p>
					<div class="remarks">
						<h3>お申し込みメールの送信が出来ませんでした。</h3>
						<p>{$errMessage}</p>
					</div>
					<p>恐れ入りますが、再度 <a href="/order/">お申し込みフォーム</a> に戻り [ 注文する ] ボタンをクリックして下さい。</p>
				</div>
				<div class="inner">
					<h3>【 ご注文に関するお問い合わせ 】</h3>
					<p class="note">お急ぎのお客様は、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
					<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/order/">お申し込みフォームに戻る</a></p>
				</div>
DOC;
				}
				
			?>

					<div class="heading1_wrapper">
						<h1>
							<?php echo $heading;?>
						</h1>
						<p class="comment"></p>
						<p class="sub">
							<?php echo $sub;?>
						</p>
					</div>
					<p class="heading"></p>
					<?php echo $html;?>
			</div>
		</div>


		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>

		<script type="text/javascript" id ="unisize_tracking_tag">
			// Recommend System
			// 2018-10-16
			
			var uniprot = "https://";
			var CID = "iZCKLimEHIXrDe";
			var purchaseId = "<?php echo $purchaseId;?>";
			var itemNum = "<?php echo $itemNum;?>";
			var itemId = "<?php echo $itemId;?>";
			var price = "<?php echo $price;?>";
			var size = "<?php echo $size;?>";
			var reg = "1";
			var CUID = "";
			var component = "pc_tag_pcTrackingPage";
			var ua = navigator.userAgent.toLowerCase();
			
			if (ua.indexOf('iphone') > 0 || ua.indexOf('ipad') > 0 || ua.indexOf('ipod') > 0 || ua.indexOf('android') > 0 || ua.indexOf('windows phone') > 0) {
				component = "sp_tag_spTrackingPage";
			} else {
				component = "pc_tag_pcTrackingPage";
			}
			var imgel = "<img width='0' height='0' src='"+uniprot +"cl.unisize.makip.co.jp/unisize/unisize.api?component=" + component + "&action=ajaxTrack";
			imgel += "&CID="+CID;
			imgel += "&purchaseId="+purchaseId;
			imgel += "&itemNum="+itemNum;
			imgel += "&itemId="+itemId;
			imgel += "&price="+price;
			imgel += "&size="+size;
			imgel += "&reg="+reg;
			imgel += "&CUID="+CUID;
			imgel += "'/>";
			document.write(imgel);
		</script>
		<script>
			;(function(){
				sessionStorage.removeItem('design');
				sessionStorage.removeItem('item');
			})();
		</script>
	</body>

</html>
