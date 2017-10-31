<?php
$_PAGE_ITEMDETAIL=true;
require_once $_SERVER["DOCUMENT_ROOT"].'/php_libs/pageinfo.php';
?>
<!DOCTYPE html>
<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content=<?php echo $itemname. '（'.$itemcode. '）'; ?>"の詳細ページです。1枚～大量のプリントまで、オリジナルTシャツの作成・プリントは、東京都葛飾区のタカハマライフアートにお任せ下さい！団体やグループなどで着用し、文化祭、体育祭のイベントを盛り上げてください。" />
	<meta name="keywords" content="<?php echo $categoryname; ?>,オリジナル<?php echo $categoryname; ?>,作成,プリント,東京,即日,最短" />
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>
		<?php echo $itemname.' '.$itemcode; ?>　|　オリジナル
		<?php echo $categoryname; ?>
	</title>
	<link rel="shortcut icon" href="/icon/favicon.ico">
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" href="/common/css/printposition_responsive.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/common/js/lightbox/jquery.lightbox-0.5.css" />
	<link rel="stylesheet" type="text/css" href="/items/css/items_style_responsive.css" media="screen" />
	<script type="text/javascript">
		var _CAT_ID = <?php echo $_PAGE_CATEGORYID; ?>;
		var _CAT_KEY = '<?php echo $categorykey; ?>';
		var _CAT_NAME = '<?php echo $categoryname; ?>';
		var _ITEM_ID = <?php echo $data['itemid']; ?>;
		var _ITEM_CODE = '<?php echo $itemcode; ?>';
		var _ITEM_NAME = '<?php echo $itemname; ?>';
		var _POS_ID = <?php echo $posid; ?>;
	</script>
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>
	
	<div id="container">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/sidenavi.php"; ?>
		<div class="contents">
			<div id="topicpath">
				<a href="/">オリジナルＴシャツ屋ＴＯＰ</a> >
				<a href="/items/?<?php echo $_QUERY;?>">
					<?php echo $categoryname;?>
				</a> >
				<?php echo $itemname; ?>
			</div>
			<?php if($itemcode=="085-cvt") {?>
			<div class="name">
				<h2>085CVT ヘビーウェイトTシャツ</h2>
			</div>
			<div><img src="/items/img/t-shirts/des_085cvt_01.jpg" width="100%"></div>
			<div><img src="/items/img/t-shirts/des_085cvt_02.jpg" width="100%"></div>
			<?php } ?>
			<div id="item_left">
				<div id="item_image">
					<?php echo $curthumb; ?>
					<div class="dotted"></div>
					<a class="info_icon" href="#size">サイズ目安</a>
					<ul id="item_image_notes">
						<li id="notes_color">
							<?php echo $curcolor; ?>
						</li><br>
						<li id="notes_size">展開サイズ <span id="size_span"><?php echo $cursize; ?></span></li>
						<li id="size_info"></li>
					</ul>
				</div>

				<div id="item_thumb">
					<div id="item_colors">
						<p class="thumb_h">Color<span id="num_of_color">全<?php echo $color_count; ?>色</span></p>
						<ul id="color_thumb">
							<?php echo $thumbs; ?>
						</ul>
					</div>
				</div>

				<div id="model">
					<?php echo $model_gallery; ?>
				</div>

				<div id="item_style">
					<?php echo $style_gallery; ?>
				</div>

			</div>

			<div id="item_right">
				<?php echo $right_column;?>
			</div>

			<div class="contents-lv2">
				<h2 id="size">サイズ目安</h2>
				<div class="dotted"></div>
				<div id="size_detail">
					<?php echo $itemsize_table; ?>
				</div>
			</div>

			<div class="contents-lv2 printarea_wrap">
				<h2 id="printarea">プリント可能範囲</h2>
				<div class="dotted"></div>
				<p>サイズ対応表とプリント可能範囲・プリント最大サイズについてご説明いたします。</p>
				<div class="flex-container flex-around">
					<?php echo $printAreaImage; ?>
				</div>
				<div class="flex-container">
					<?php echo $printSizeTable; ?>
				</div>
				<div class="footnote">
					<?php echo $footNote; ?>
				</div>
				<div class="footnote"><p>※シルクスクリーンプリントで幅27cm×高さ35cm以上のサイズの場合、版代・プリント代が割り増しになります。
					詳しくは<a href="/price/fee/">こちら</a>をご覧ください。</p></div>
				<div class="printtype">
					<p>対応プリント：</p>
					<?php echo $printMethod; ?>
				</div>
			</div>

			<div class="up"><a class="up_icon" href="#topicpath">ページ上部へ</a></div>

			<div class="contents-lv2">
				<h2 id="howmuch">いくらになるか、計算してみる。</h2>
				<div class="dotted"></div>
				<h3 id="hmguide">枚数やプリント位置などを指定して、<span>お値段を計算します。</span>※概算となります。</h3>
				<?php echo $DOC; ?>
				<img src="/common/img/flow_s.png" class="flow_s" width="100%" height="100%">
				<div class="flow_1_butt">
					<a class="button1 hidden-xs-down" href="/contact/">お問い合わせはこちら</a>
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
	<script type="text/javascript" src="/common/js/lightbox/jquery.lightbox-0.5.js"></script>
	<script type="text/javascript" src="/items/js/jquery.changephoto.js"></script>
	<script type="text/javascript" src="/items/js/jquery.tableselect.js"></script>
	<script type="text/javascript" src="/items/js/estimate_sole.js"></script>
</body>

</html>
