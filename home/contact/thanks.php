<?php
$isSend = false;
if( isset($_REQUEST['title']) ) {
	$titles = array(
		'info'=>'お問い合わせ',
		'request'=>'資料請求', 
		'estimate'=>'お見積問合せ',
		'test'=>'テスト',
		'minit'=>'ユニフォームミニTお申し込み',
		'illusttemplate'=>'イラレ入稿テンプレート',
		'repeat'=>'追加注文',
		'visit'=>'出張打ち合わせ',
		'expresstoday'=>'当日特急プラン',
		'towel'=>'オリジナルタオルお問い合わせ',
		'designconsierge'=>'デザインコンシェルジュ',
		'orange'=>'俺んじ君ワークショップお申し込み',
		'express'=>'お急ぎの製作お問合せ',
		'bigorder'=>'大口注文お問い合わせ'

	);
	$title = $titles[$_REQUEST['title']];
	$isSend = true;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
	<meta name="Description" content="1枚～大量のプリントまで、トレーナー・ポロシャツ・オリジナルTシャツの作成・プリントは、東京都葛飾区のタカハマライフアートにお任せ下さい！団体やグループなどで着用し、文化祭、体育祭のイベントを盛り上げてください。" />
	<meta name="keywords" content="オリジナル,Tシャツ,東京,作成,プリント" />
	<meta name="google-site-verification" content="PfzRZawLwE2znVhB5M7mPaNOKFoRepB2GO83P73fe5M" />
	<title>メール送信完了 ｜ オリジナルTシャツ【タカハマライフアート】</title>
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
				$heading = '確認メールを返信しています。<br>ご確認ください！';
				$sub = 'Sending';
				$html = <<<DOC

			<h2 class="heading">{$title}</h2>
			<div class="inner">
				<p>この度はタカハマライフアートをご利用いただき、誠にありがとうございます。</p>
				<p>内容を確認後、弊社スタッフからご連絡いたします。</p>
			</div>
			<div class="inner">
				<p class="red_txt">制作を開始するにあたり、お電話によるご注文内容の最終確認をさせていただいております。</p>
				<p class="red_txt">ご入稿いただいたデザインの内容とプリント位置などの打合せを行い、納期と正規見積りの最終確認をおこなっていただき注文確定となります。</p>
			</div>
			<div class="inner">
				<h3>【 親切対応でしっかりサポート 】</h3>
				<p>
					返信メールが届かない場合は、お手数ですが下記の連絡先までお問い合わせください。<br />
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
				<p>{$customer}　様</p>
				<div class="remarks">
					<p><strong>メールの送信が出来ませんでした。</strong></p>
					<p>メールの送信中にエラーが発生いたしました。</p>
				</div>
				<p>恐れ入りますが、再度 [ 送信 ] ボタンをクリックして下さい。</p>
			</div>
			<div class="inner">
				<h3>【 親切対応でしっかりサポート 】</h3>
				<p class="note">お急ぎのお客様は、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
				<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
				<hr />
				<p class="gohome"><a href="/">ホームページに戻る</a></p>
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
				<?php echo $html;?>

		</div>

	</div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>

</body>

</html>
