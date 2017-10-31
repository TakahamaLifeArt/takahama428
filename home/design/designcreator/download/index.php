<?php
require_once dirname(__FILE__).'/../php_libs/downloader.php';
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>ダウンロードページ</title>
		<meta name="description" content="デザイン画像のダウンローダーです。" />
		<meta name="keywords" content="Tシャツデザイン,プリントＴシャツ,オリジナルＴシャツ,Ｔシャツ作成,イラスト,自作,タカハマライフアート" />
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="/common/css/common.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/common/css/base.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="./css/download.css" media="screen" />
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-11155922-2']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();

		</script>

	</head>

	<body>
		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-T5NQFM"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>
			(function(w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({
					'gtm.start': new Date().getTime(),
					event: 'gtm.js'
				});
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src =
					'//www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', 'GTM-T5NQFM');

		</script>
		<!-- End Google Tag Manager -->
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>

		<div id="container">

			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/globalmenu.php"; ?>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/sidenavi.php"; ?>

			<div class="contents">

				<div class="heading1_wrapper">
					<h1>デザイン画像をダウンロード</h1>
					<p class="comment"></p>
					<p class="sub">Download</p>
				</div>

				<div class="list_wrapper">
					<div class="img_box">
						<img alt="ダウンロードファイル" src="/<?php echo $webpath;?>" width="200" />
					</div>
					<div class="info_box">
						<p>プリントする際の原寸でのデザイン画像です。<ins>ダウンロードして画質をご確認下さい。</ins></p>
						<table class="info_table" cellpadding="0" cellspacing="0">
							<tr>
								<td>ファイル名</td>
								<td>:</td>
								<td>
									<?php echo $filename;?>
								</td>
							</tr>
						</table>
						<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
							<input type="hidden" name="downloadfile" value="/<?php echo $webpath?>" />
							<input type="hidden" name="act" value="downloader" />
							<input type="submit" value="ダウンロード" />
						</form>
						<p class="comment"><span>※</span>&nbsp;ダウンロードできるのは一度に一回です。キャンセルした場合は、<br /><a href="../../fontcolor.html">シミュレーション</a>からダウンロードし直してください。</p>
					</div>
				</div>

				<div class="list_wrapper">
					<div class="img_box">
						<img alt="ダウンロードファイル" src="/<?php echo $webpath2; ?>" width="200" />
					</div>
					<div class="info_box">
						<p>Ｔシャツにプリントしたイメージ画像です。</p>
						<table class="info_table" cellpadding="0" cellspacing="0">
							<tr>
								<td>ファイル名</td>
								<td>:</td>
								<td>
									<?php echo $filename2;?>
								</td>
							</tr>
						</table>
						<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
							<input type="hidden" name="downloadfile" value="/<?php echo $webpath2; ?>" />
							<input type="hidden" name="act" value="downloader" />
							<input type="submit" value="ダウンロード" />
						</form>
						<p class="comment"><span>※</span>&nbsp;ダウンロードできるのは一度に一回です。キャンセルした場合は、<br /><a href="./../fontcolor.html">シミュレーション</a>からダウンロードし直してください。</p>
					</div>
				</div>

			</div>
		</div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>

	</body>

</html>
