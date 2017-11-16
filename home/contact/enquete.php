<?php

$isSend = null;
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/mailer.php';
if( isset($_POST['ticket']) && !empty($_POST['ticket']) ) {
	$mailer = new Mailer($_POST);
	$isSend = $mailer->send_enquete();
}else if(isset($_REQUEST['enq']) && !empty($_REQUEST['enq'])){
	$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
	$enq = preg_replace('/^\D/', '', $_REQUEST['enq']);
	if (empty($enq)) header("Location: "._DOMAIN);
	$customer_id = sprintf("%d", $enq);
	$number = 'K'.str_pad($customer_id, 6, '0', STR_PAD_LEFT);
}else{
	header("Location: "._DOMAIN);
}

?>
<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  websites: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！">
		<meta property="og:type" content="article">
		<meta name="Description" content="1枚～大量のプリントまで、トレーナー・ポロシャツ・オリジナルTシャツの作成・プリントについてのお問合せは、東京都葛飾区のタカハマライフアートにお任せ下さい！">
		<meta name="keywords" content="資料請求,無料サンプル,アンケート">
		<meta property="og:url" content="https://www.takahama428.com/">
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート">
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
		<meta property="fb:app_id" content="1605142019732010">
		<title>アンケート　|　オリジナルTシャツ屋タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="./css/custom_responsive.css" media="screen">
		<link rel="stylesheet" type="text/css" href="./css/enquete.css" media="screen">
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>
		
		<div id="container">
			<div class="contents">

<?php
	if(is_null($isSend)){
		$html = <<<DOC
			<div class="heading1_wrapper">
				<h1>アンケートのお願い</h1>
				<p class="comment"></p>
				<p class="sub">Enquete</p>
			</div>
			
			<div class="firstmessage">
				<p>この度のタカハマライフアートへのご注文、誠にありがとうございました。</p>
				<p>
					弊社では、更なるお客様サービスの向上のため、下記のアンケートを実施しておりますので、<br>
					ぜひともご協力をお願いいたします。
				</p>
			</div>
			
			<main>
				<form id="contact_form" name="contact_form" method="post" action="{$_SERVER['SCRIPT_NAME']}" onsubmit="return false;">
					<div>
						<p id="number">顧客ID： <ins>{$number}</ins></p>
						<input type="hidden" value="{$customer_id}" name="number">
					</div>

					<div>
						<p class="q">
							<em>Q1</em>
							今回、タカハマライフアートをお選びいただいた理由をお聞かせ下さい。
						</p>
						<p class="a">
							<textarea name="a12"></textarea>
						</p>
					</div>

					<div>
						<p class="q">
							<em>Q2</em>
							タカハマライフアートのホームページはわかりやすかったでしょうか？
						</p>
						<div class="a">
							<p><label><input type="radio" name="a1" value="5">とても分りやすかった</label></p>
							<p><label><input type="radio" name="a1" value="4">分りやすかった</label></p>
							<p><label><input type="radio" name="a1" value="3">普通</label></p>
							<p><label><input type="radio" name="a1" value="2">分りにくかった</label></p>
							<p><label><input type="radio" name="a1" value="1">とても分りにくかった</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q3</em>
							ホームページで、わかりやすかった点、わかりにくかった点について、<br>具体的に教えて下さい。
						</p>
						<p class="a">
							<textarea name="a2"></textarea>
						</p>
					</div>

					<div>
						<p class="q">
							<em>Q4</em>
							ご注文いただいた際の弊社の対応はいかがでしたでしょうか？
						</p>
						<div class="a">
							<p><label><input type="radio" name="a5" value="5">とても良かった</label></p>
							<p><label><input type="radio" name="a5" value="4">良かった</label></p>
							<p><label><input type="radio" name="a5" value="3">普通</label></p>
							<p><label><input type="radio" name="a5" value="2">悪かった</label></p>
							<p><label><input type="radio" name="a5" value="1">とても悪かった</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q5</em>
							プリントの仕上がりは、お客様のイメージ通りでしたでしょうか？
						</p>
						<div class="a">
							<p><label><input type="radio" name="a6" value="5">イメージ以上に良かった</label></p>
							<p><label><input type="radio" name="a6" value="4">イメージ通り良かった</label></p>
							<p><label><input type="radio" name="a6" value="3">普通</label><br></p>
							<p><label><input type="radio" name="a6" value="2">イメージしていたより悪かった</label></p>
							<p><label><input type="radio" name="a6" value="1">全くイメージ通りではなかった</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q6</em>
							商品が到着した際の梱包状態はいかがでしたでしょうか？
						</p>
						<div class="a">
							<p><label><input type="radio" name="a7" value="5">とても良かった</label></p>
							<p><label><input type="radio" name="a7" value="4">良かった</label></p>
							<p><label><input type="radio" name="a7" value="3">普通</label></p>
							<p><label><input type="radio" name="a7" value="2">悪かった</label></p>
							<p><label><input type="radio" name="a7" value="1">とても悪かった</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q7</em>
							実際に商品を着用・使用してみての、アイテムに関する感想をお願いします。
						</p>
						<p class="note"><ins>※</ins>プリントについてではなく、Ｔシャツやポロシャツなどアイテム自体への感想（着心地や生地感、色合い、<br>サイズについてなど）をお願いします。</p>
						<p class="note"><ins>※</ins>商品レビューとしてＨＰ等に使用させていただく場合があります。予めご了承下さい。</p>
						<p class="a">
							<textarea name="a10"></textarea>
						</p>
					</div>

					<div>
						<p class="q">
							<em>Q8</em>
							ご使用の用途を教えてください。(音楽イベント、文化祭など)
						</p>
						<p class="a">
							<textarea name="a13"></textarea>
						</p>
					</div>

					<div>
						<p class="q">
							<em>Q9</em>
							「もっとこんなサービス・商品があれば良いのに！」というご要望があれば<br>お聞かせ下さい。
						</p>
						<p class="a">
							<textarea name="a8"></textarea>
						</p>
					</div>

					<div>
						<p class="q">
							<em>Q10</em>
							弊社を知ったきっかけを教えてください。
						</p>
						<div class="a">
							<p><label><input type="radio" name="a14" value="6">インターネット検索</label></p>
							<p><label><input type="radio" name="a14" value="5">知り合いの紹介</label></p>
							<p><label><input type="radio" name="a14" value="4">雑誌、新聞記事、広告</label></p>
							<p><label><input type="radio" name="a14" value="3">セミナー講演会</label></p>
							<p><label><input type="radio" name="a14" value="2">2回目以降の購入</label></p>
							<p><label><input type="radio" name="a14" value="1">その他</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q11</em>
							その他、注文してみての感想・お気づきの点などがありましたら<br>お聞かせ下さい。
						</p>
						<p class="a">
							<textarea name="a9"></textarea>
						</p>
					</div>

					<input type="hidden" name="ticket" value="{$ticket}">

					<p class="button_area" id="sendmail">
						<input type="button" value="アンケートを送信する" class="btn">
					</p>
				</form>
			</main>
DOC;

		echo $html;
	}else if($isSend){
			
		$html = <<<DOC
			<div class="heading1_wrapper">
				<h1>アンケートのご協力ありがとうございました</h1>
				<p class="comment"></p>
				<p class="sub">Enquete</p>
			</div>
			<main>
				<p>
					アンケートにお答えいただき、ありがとうございました。<br>
					またのご利用、心よりお待ち申し上げております。
				</p>
			</main>
DOC;

		echo $html;
	}else{
		
		$html = <<<DOC
			<div class="heading1_wrapper">
				<h1>送信エラー</h1>
				<p class="comment"></p>
				<p class="sub">Error</p>
			</div>
			<main>
				<p>
					アンケートの送信中にエラーが発生しました。<br>
					恐れ入りますが、もう一度送信をお願いいたします。
				</p>
			</main>
DOC;

		echo $html;
	}
?>

		<div class="box_c">
			<div class="bg"></div>
		</div>
		
		</div>
		</div>
		
		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="./js/enquete.js"></script>

	</body>

</html>
