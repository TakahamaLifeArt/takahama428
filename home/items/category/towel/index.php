<?php
$_PAGE_CATEGORIES=true;
require_once $_SERVER['DOCUMENT_ROOT']. '/php_libs/pageinfo.php';
?>
<!DOCTYPE html>
<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="納期が早い！まだ間に合いますオリジナルTシャツ。Tシャツのアイテム一覧ページです。Tシャツ・ドライTシャツ・ラグランで各種グレードを取り揃えました。全てオリジナルプリント可能です。最短でオリジナルプリントを作成したい方は、東京葛飾区にあるプリント工場、タカハマライフアートへ！" />
		<meta name="keywords" content="tシャツ,ドライ,ラグラン,オリジナル,早い" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>
			<?php echo $category_name;?>一覧 ｜オリジナルTシャツ作成が早いタカハマライフアート！
		</title>
		<link rel="preconnect" href="https://takahamalifeart.com">
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="/items/css/item.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="/items/css/items_style_responsive.css" media="screen" />
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		
		<div id="container">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/sidenavi.php"; ?>
			<div class="contents">
				<ul class="pan hidden-sm-down">
					<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
					<?php echo $pan_navi;?>
				</ul>

				<h1><strong><?php echo $category_name;?></strong>にオリジナルプリント！</h1>
				<p class="note min"><span>※</span>アイテムメーカーの在庫状況によってはご希望に沿えない場合がございます。</p>
				<p><a class="check" href="/campaign/towel/extrabig.php"><img src="img/list_extrabig.jpg" class="hidden-xs-down"></a></p><br>
				<p><a class="check" href="/campaign/towel/extrabig.php"><img src="img/sp_list_extrabig.jpg"  class="hidden-sm-up" width="100%"></a></p><br>
				<?php echo $tagList; ?>
				<div class="contents-lv2" id="tag_wrap">
					<h3 id="sortbtn" class="list tokucho">並べ替え</h3>
					<div class="tag" id="sort">
						<div class="tag_btn">
							<p id="sort_price_low">価格の低い順</p>
						</div>
						<div class="tag_btn">
							<p id="sort_price_high">価格の高い順</p>
						</div>
						<div class="tag_btn">
							<p id="sort_review_desc">レビューが多い順</p>
						</div>
						<div class="tag_btn">
							<p id="sort_review_asc">レビューが少ない順</p>
						</div>
						<div class="tag_btn">
							<p id="sort_cloth_heavy">生地が厚い順</p>
						</div>
						<div class="tag_btn">
							<p id="sort_cloth_light">生地が薄い順</p>
						</div>
						<div class="tag_btn"></div>
						<div class="tag_btn">
							<p id="sort_popular_popular" class="act">人気順</p>
						</div>
					</div>

					<p class="txt_min">表示件数：
						<span class="number"><?php echo mb_convert_kana($itemCount,'A'); ?></span> アイテム
					</p>

					<ul class="listitems clearfix">
						<?php echo $itemlist_data; ?>
					</ul>

					<?php echo $description; ?>

				</div>
			</div>

		</div>
		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<script type="text/javascript">
			var _CAT_ID = <?php echo $_ID;?>;
			var _IS_TAG = <?php echo $_IS_TAG;?>;
		</script>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="/items/js/itemindex.js"></script>
		<script type="text/javascript" src="/items/js/ScrollPagination.min.js"></script>

	</body>

</html>
