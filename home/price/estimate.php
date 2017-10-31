<?php
$_PAGE_ESTIMATION_1=true; 
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/pageinfo.php';
?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="お届けも見積もり計算も早い！タカハマライフアートでオリジナルＴシャツを作成した場合のお見積もりをオンラインで簡単に見ることができます。お見積り一覧でおすすめの詳細アイテムもご確認できる便利な機能付きですので、是非ご利用くださいませ。最短でオリジナルプリントを作成したい方はタカハマライフアートへ！" />
		<meta name="keywords" content="見積もり,簡単,Tシャツ,オリジナル,早い" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>10秒見積もり ｜ オリジナルTシャツが早い、タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="/common/css/printposition_responsive.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="/items/css/items_style_responsive.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="./css/estimate.css" media="screen" />
		<script type="text/javascript">
			var _IMG_PSS = "<?php echo _IMG_PSS;?>";

		</script>

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
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>


		<div id="container">
			<div class="contents">

				<ul class="pan hidden-sm-down">
					<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
					<li>10秒見積もり</li>
				</ul>

				<div class="heading1_wrapper">
					<div class="title_box">
						<h1 id="toptxt">10秒見積もり</h1>
					</div>

				</div>

				<div id="item_wrap">
					<h2>1.カテゴリーをお選びください</h2>
					<table>
						<caption></caption>
						<tbody>
							<tr>
								<th>商品カテゴリー</th>
								<td>
									<?php echo $category_selector; ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="body_wrap">
					<h2>2.タイプをお選びください</h2>
					<div id="boxwrap" class="clearfix">
						<?php
						$ids = array(1=>'綿Tシャツ',3=>'ドライTシャツ');
						$isFirst = true;
						foreach($ids as $id=>$lbl){
							$files = $pageinfo->positionFor($id, 'pos');
							$imgfile = file_get_contents($files[0]['filename']);
							$f = preg_replace('/.\/img\//', _IMG_PSS, $imgfile);
							//$f = preg_replace('/\/base/', '/layout', $f);
							preg_match('/<img (.*?)>/', $f, $match);
							$f = mb_convert_encoding($match[1], 'euc-jp', 'utf-8');
							$box .= '<div class="box">';
							$box .= '<div class="body_type"><img '.$f.'></div>';
							$box .= '<div class="desc">';
							$box .= '<p><label><input type="radio" value="'.$id.'" name="body_type" class="check_body"';
							if($isFirst) $box .= ' checked="checked"';
							$box .= '> '.$lbl.'</label></p>';
							$box .= '</div>';
							$box .= '</div>';

							$isFirst = false;
						}
						echo $box;
						?>
					</div>
					<div id="color_wrap">
						<h3>アイテムカラーをご指定ください</h3>
						<label><input type="radio" name="color" value="0" checked="checked">白色</label>
						<label><input type="radio" name="color" value="1">白色以外</label>
					</div>
				</div>

				<div id="pos_wrap">
					<div class="content-lv3">
						<h2>3.プリントする位置とデザインの色数をご指定ください</h2>
						<div id="pos_wrap_step3">
							<figure>
								<div>
								</div>
								<ul>
									<?php echo $posdiv; ?>
								</ul>
							</figure>
						</div>
					</div>
				</div>

				<div id="price_wrap">
					<h2>4.枚数をご指定ください</h2>
					<table>
						<caption></caption>
						<tbody>
							<tr>
								<td><input type="number" value="30" min="1" max="100" step="1" id="order_amount"> 枚</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="result_wrap02">
					<p class="ttl">お見積もり一覧<span>（<ins>0</ins> 件）</span></p>
					<p class="caution ar"><span class="fontred">※</span>最も安いサイズ・カラー・プリント方法でのお見積もりです。</p>
					<div class="rankingmore">
						<table class="result">
							<thead>
								<tr>
									<th class="nobdr">&nbsp;</th>
									<th>安い順</th>
									<th>人気順</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="more">さらに表示<span></span></div>
					<div class="rankingmore">
						<table class="result other">
							<tbody></tbody>
						</table>
					</div>
				</div>

			</div>
		</div>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<!--		<script type="text/javascript" src="/common/js/masonry/jquery.masonry.min.js"></script>-->
		<script type="text/javascript" src="./js/estimate.js"></script>
	</body>

	</html>
