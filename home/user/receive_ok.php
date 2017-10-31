<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

$isOK = true;

?>
	<!DOCTYPE html>
<html lang="ja">

	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#  website: http://ogp.me/ns/website#">
		<meta charset="utf-8" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="http://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="http://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>決済 - TLAメンバーズ | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
<!--
		<link rel="stylesheet" type="text/css" href="/common/css/common_responsive.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/common/css/base_responsive.css" media="screen" />

		<link rel="stylesheet" type="text/css" media="screen" href="./css/style.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/common/css/common_responsive.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/common/css/base_responsive.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/common/js/modalbox/css/jquery.modalbox.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/style_responsive.css" />
-->
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_account.css" />
		<link rel="stylesheet" type="text/css" href="/contact/css/finish_responsive.css" media="screen" />

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
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/globalnavi.php"; ?>

		<div id="container">
			<div class="contents">
				<?php
				$cst = 'cst';
				function cst($constant){
					return $constant;
				}
				if($isOK){
					$heading = 'お支払い完了';
					$html = <<<DOC
				<div class="inner">
					<p>
						この度はタカハマライフアートをご利用いただき、誠にありがとうございました。<br>
						Webサイトの表示が更新されるまでに1両日ほどかかる場合がございます。ご了承ください。
					</p>
				</div>
				<div class="inner">
					<h3>【 親切対応でしっかりサポート 】</h3>
					<p class="note">ご不明な点やお気づきのことがございましたら、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
					<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>
DOC;

				}else{
					$heading = 'エラー！';
					$html = <<<DOC
				<div class="inner">
					<div class="remarks">
						<p><strong>決済が出来ませんでした。</strong></p>
						<p>エラーが発生いたしました。</p>
					</div>
				</div>
				<div class="inner">
					<h3>【 親切対応でしっかりサポート 】</h3>
					<p class="note">ご不明な点やお気づきのことがございましたら、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
					<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>
DOC;
				}
			?>


					<div class="toolbar">
						<div class="toolbar_inner clearfix">
							<div class="menu_wrap">
								<?php echo $menu;?>
							</div>
						</div>
					</div>
					<div class="pagetitle">
						<h1>
							<?php echo $heading;?>
						</h1>
					</div>

					<?php echo $html;?>
			</div>

		</div>


		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>
		
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="/common/js/jquery.js"></script>
		<script type="text/javascript" src="./js/common.js"></script>




	</body>

	</html>
