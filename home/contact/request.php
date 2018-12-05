<?php
$_version = time();
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="早いで業界No.1最速のオリジナルTシャツ屋タカハマライフアートでは資料請求もご対応致します！無料でプリントサンプル貸出もおこなっておりますので、お気軽にお問い合わせください！1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。" />
	<meta name="keywords" content="資料請求,無料サンプル,プリント,作成,早い" />
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>資料請求 ｜ オリジナルTシャツ【タカハマライフアート】</title>
	<link rel="shortcut icon" href="/icon/favicon.ico">
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" href="/contact/css/request.css" media="screen" />
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>

	<div id="container">
		<div class="contents">
			<ul class="pan hidden-sm-down">
				<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
				<li>資料請求も早い！</li>
			</ul>

			<div class="heading1_wrapper">
				<h1>資料請求も早い！</h1>
			</div>
			<div>
				<img class="pc" src="/contact/img/sample_main.jpg" width="100%" alt="無料でサンプル貸出し！">
			</div>
			<div>
				<img class="sp" src="/contact/img/sp_sample_main.jpg" width="100%" alt="無料でサンプル貸出し！">
			</div>

			<h2 class="heading">無料でサンプルを貸出</h2>
			<p>
				&ldquo;ホームページだけじゃ分かりにくい&rdquo;<br> &ldquo;実際にTシャツを見たり触ったりしたい&rdquo;<br> &ldquo;どんな感じにプリントされるのか確かめたい&rdquo;<br>とお嘆きのあなたに朗報！</p>

			<p class="samplefree"><span class="fontred">無料</span>（ご返却送料はお客様ご負担）でサンプルをご確認いただけます。資料はお気軽にお申込み下さい。</p>

			<h3 class="heading3">資料の内容</h3>
			<ul class="msg">
				<li>メーカーの商品小冊子 ※返却不要</li>
				<li>商品サンプル3点まで（ご希望のお客様のみ）<br><span class="fontred">※必ず到着後10日以内に返却</span></li>
			</ul>
			<p class="note"><span>※</span> 商品サンプルをご希望の場合は、フォーム内の「サンプル」でチェックをしてください。
				<br /> ご指定の商品がありましたらメッセージ欄にご記入下さい。
			</p>

			<div class="round_corner_wrapper02">
				<div class="round_lt"></div>
				<div class="round_rt"></div>
				<div class="round_inner">

					<form name="request_form" class="e-mailer" method="post">
						<table id="enq_table">
							<tbody>
								<tr>
									<th><label>お名前</label><span class="point">※</span></th>
									<td><input name="customername" type="text" maxlength="32" class="restrict" required/></td>
								</tr>
								<tr>
									<th><label>フリガナ</label></th>
									<td><input type="text" name="ruby" value="" class="wide" /></td>
								</tr>
								<tr>
									<th><label>ご住所</label><span class="point">※</span></th>
									<td>
										<p><input name="zipcode" id="zipcode" class="forZip p-zip" type="text" required></p>
										<p><input name="addr0" id="addr0" class="p-region" type="text" placeholder="都道府県" maxlength="4" required></p>
										<p><input name="addr1" id="addr1" class="p-locality p-street-address" type="text" maxlength="56" required></p>
										<p><input name="addr2" id="addr2" type="text" maxlength="32"></p>
									</td>
								</tr>
								<tr>
									<th><label>メールアドレス</label><span class="point">※</span></th>
									<td><input name="email" type="email" class="email" required/></td>
								</tr>
								<tr>
									<th><label>電話番号</label><span class="point">※</span></th>
									<td><input name="tel" type="tel" class="forPhone" required/></td>
								</tr>
								<tr>
									<th><label>サンプル</label></th>
									<td><label><input name="sample" type="checkbox" value="商品サンプル希望" />商品サンプルを希望します</label></td>
								</tr>
								<tr>
									<th><label>メッセージ</label><span class="point">※</span></th>
									<td>
										<p>商品サンプルを希望されたお客様は、ご希望の商品（3点まで）をご記入ください。</p>
										<textarea name="message" id="message" cols="40" rows="7" required placeholder="記入例：085-CVT ヘビーウェイトTシャツ　ホワイト-S   ブラック-M   /  083-BBT ライトウェイトTシャツ ブラック-LL"></textarea>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="point">「※」 は必須です。</p>
						<input type="hidden" name="sendto" value="<?php echo _INFO_EMAIL;?>">
						<input type="hidden" name="subject" value="資料請求">
						<input type="hidden" name="title" value="資料請求">
						<input type="hidden" name="replyto" value="email">
						<input type="hidden" name="replyhead" value="このたびは、タカハマライフアートをご利用いただき誠にありがとうございます。">
						<p class="button_area">
							<button type="submit" class="btn btn-primary" id="sendmail">送　信</button>
						</p>
					</form>
				</div>
				<div class="round_lb"></div>
				<div class="round_rb"></div>
			</div>
		</div>
	</div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script src="//doozor.bitbucket.io/email/e-mailform.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
	<script type="text/javascript" src="./js/request.js?v=<?php echo $_version;?>"></script>
</body>

</html>
