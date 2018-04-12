<?php
// Instagram Photo Slider
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/instaAPI.php';
$idx = 1;
$thumbType = array('thumbnail', 'low_resolution', 'standard_resolution');
foreach($insta['data'] as $data){
	$instaPhoto .= '<div class="pic photo'.$idx++.'">';
	$instaPhoto .= '<a href="'.$data['link'].'" target="_brank"><img src="'.$data['images'][$thumbType[0]]['url'].'" alt=""></a>';
	$instaPhoto .= '</div>';
}

/**
 * お届け日を取得
 * 月：$fin['Month']
 * 日：$fin['Day']
 * 曜日：$fin['weekname']
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/orders.php';
$order = new Orders();
$fin = $order->getDelidate(null, 1, 4, 'simple');
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="google-site-verification" content="PfzRZawLwE2znVhB5M7mPaNOKFoRepB2GO83P73fe5M">
	<meta name="Description" content="オリジナルTシャツ作成が早い！即納可能の業界最速！クラスTシャツの文字入れも最短即日で短納期プリント。1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。">
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！">
	<meta property="og:type" content="website">
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。">
	<meta property="og:url" content="https://www.takahama428.com/">
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート">
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
	<meta property="fb:app_id" content="1605142019732010">
	<title>オリジナルTシャツのプリント作成 ｜ タカハマライフアート</title>
	<link rel=canonical href="https://www.takahama428.com/">
	<link rel="shortcut icon" href="/icon/favicon.ico">
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" href="./css/style.css">
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header_top.php"; ?>
	</header>

	<div class="container-fluid">
		<div id="mainCarouselIndicators" class="carousel slide" data-ride="carousel">

			<ol class="carousel-indicators">
				<li data-target="#mainCarouselIndicators" data-slide-to="0" class="active"></li>
				<li data-target="#mainCarouselIndicators" data-slide-to="1"></li>
				<li data-target="#mainCarouselIndicators" data-slide-to="2"></li>
			</ol>

			<div class="carousel-inner justify-content-center" role="listbox">
				<div class="carousel-item active hero-carousel__cell hero-carousel__cell--1">

					<div class="carousel-caption">
						<p class="text_02_1">1枚1枚、職人がプリントする</p>
						<p class="text_01_1">オリジナルTシャツ</p>
						<p class="psn_btn"><a href="/order/" class="check btn btn-info adj waves-effect waves-light orderbtn_01" type="button">
							<img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 5px;">お申し込み</a>
						</p>
					</div>
				</div>

				<div class="carousel-item hero-carousel__cell hero-carousel__cell--2">

					<div class="carousel-caption">
						<p class="text_01_2">業界最速!<br class="hidden-sm-up">今日届くオリジナルTシャツ</p>
						<p class="text_02">最短6時間で即日発送!</p>
						<p class="psn_btn"><a href="/order/" class="check btn btn-info adj waves-effect waves-light orderbtn_02" type="button">
							<img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 5px;">お申し込み</a>
						</p>
					</div>
				</div>

				<div class="carousel-item hero-carousel__cell hero-carousel__cell--3">

					<div class="carousel-caption">
						<p class="text_01">Made in Tokyo</p>
						<p class="text_02">顧客満足度94%!<br class="hidden-sm-up">安心のプリント実績200万枚以上</p>
						<p class="psn_btn"><a href="/order/" class="check btn btn-info adj waves-effect waves-light orderbtn_03" type="button">
							<img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 5px;">お申し込み</a>
						</p>
					</div>
				</div>

			</div>
			<a class="carousel-control-prev" href="#mainCarouselIndicators" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#mainCarouselIndicators" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>

		</div>
	</div>

	<main class="container">
		<?php
		if (!empty(_EXTRA_NOTICE)) {
			$extNotice = (_EXTRA_NOTICE);
			$txt = explode(PHP_EOL, $extNotice);
			echo '<div id="option">';
			echo '<h3 id="Line005"><a href="/guide/information.php">'.$txt[0].'</a></h3>';
			$message = str_replace($txt[0].PHP_EOL, '', $extNotice);
			echo '<p>'.nl2br($message).'</p></div>';
		}
		if (!empty(_NOTICE_HOLIDAY)) {
			$notice = (_NOTICE_HOLIDAY);
			$txt = explode(PHP_EOL, $notice);
			echo '<div id="option">';
			echo '<h3 id="Line005"><a href="/guide/information.php">'.$txt[0].'</a></h3>';
			$message = str_replace($txt[0].PHP_EOL, '', $notice);
			echo '<p>'.nl2br($message).'</p></div>';
		}
		?>

		<div class="item_category">
			<h2 class="rank_ttl">アイテムカテゴリー</h2>
			<p class="top_p">400種類以上の豊富なアイテムにお客様のデザインをオリジナルプリントできます。</p>

			<section class="hidden-xs-down">

				<div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item t-shirts_btn" href="/items/category/t-shirts/">
						<img src="/items/img/item_01.jpg" width="100%">
							<p class="item_txt_min">Tシャツ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item polo-shirts_btn" href="/items/category/polo-shirts/">
						<img src="/items/img/item_03.jpg" width="100%">
							<p class="item_txt_min">ポロシャツ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item towel_btn" href="/items/category/towel/">
						<img src="/items/img/item_08.jpg" width="100%">
							<p class="item_txt_min">タオル</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item sweat_btn" href="/items/category/sweat/">
						<img src="/items/img/item_02.jpg" width="100%">
							<p class="item_txt_min">スウェット</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item sportswear_btn" href="/items/category/sportswear/">
						<img src="/items/img/item_04.jpg" width="100%">
							<p class="item_txt_min">スポーツ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item long-shirts_btn" href="/items/category/long-shirts/">
						<img src="/items/img/item_05.jpg" width="100%">
							<p class="item_txt_min">長袖Tシャツ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item outer_btn" href="/items/category/outer/">
						<img src="/items/img/item_06.jpg" width="100%">
							<p class="item_txt_min">ブルゾン</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item ladys_btn" href="/items/category/ladys/">
						<img src="/items/img/item_07.jpg" width="100%">
							<p class="item_txt_min">レディース</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item tote-bag_btn" href="/items/category/tote-bag/">
						<img src="/items/img/item_09.jpg" width="100%">
							<p class="item_txt_min">バッグ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item apron_btn" href="/items/category/apron/">
						<img src="/items/img/item_10.jpg" width="100%">
							<p class="item_txt_min">エプロン</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item baby_btn" href="/items/category/baby/">
						<img src="/items/img/item_11.jpg" width="100%">
							<p class="item_txt_min">ベビー</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item overall_btn" href="/items/category/overall/">
						<img src="/items/img/item_12.jpg" width="100%">
							<p class="item_txt_min">つなぎ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item goods_btn" href="/items/category/goods/">
						<img src="/items/img/item_15.jpg" width="100%">
							<p class="item_txt_min">記念品</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item cap_btn" href="/items/category/cap/">
						<img src="/items/img/item_14.jpg" width="100%">
							<p class="item_txt_min">キャップ</p>
						</a>
					</div>
				</div>
			</section>

			<section class="hidden-sm-up">

				<div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item t-shirts_btn" href="/items/category/t-shirts/">
						<img src="/items/img/item_01.jpg" width="100%">
							<p class="item_txt_min">Tシャツ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item polo-shirts_btn" href="/items/category/polo-shirts/">
						<img src="/items/img/item_03.jpg" width="100%">
							<p class="item_txt_min">ポロシャツ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item towel_btn" href="/items/category/towel/">
						<img src="/items/img/item_08.jpg" width="100%">
							<p class="item_txt_min">タオル</p>
						</a>
					</div>
					<div class="navi_inner_2 btn">
						<a class="dropdown-item sweat_btn" href="/items/category/sweat/">
						<img src="/items/img/item_02.jpg" width="100%">
							<p class="item_txt_min">スウェット</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item sportswear_btn" href="/items/category/sportswear/">
						<img src="/items/img/item_04.jpg" width="100%">
							<p class="item_txt_min">スポーツ</p>
						</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item long-shirts_btn" href="/items/category/long-shirts/">
						<img src="/items/img/item_05.jpg" width="100%">
							<p class="item_txt_min">長袖Tシャツ</p>
						</a>
					</div>
				</div>
				<a class="btn_or btn waves-effect waves-light" href="/items/category.php" type="button">アイテム一覧へ</a>
			</section>

			<div class="top_02">
				<h2 class="rank_ttl">お届け日・見積もり計算</h2>
				<p class="top_p">WEB上で、すぐにお届け日とオリジナルTシャツの概算を調べることができます。</p>

				<div class="row outer top_3_wrap">

					<div class="col bk">
						<a href="/delivery/" class="check btn_01 top_3 top_item_flex">
							<div class="bk_area">
								<p class="top3_bu_txt">今注文すると<br><span class="big_font">この日に届く</span></p>
							</div>
							<div class="bk_area">
								<div id="date">
									<p>
										<?php echo $fin['Month'];?>/
										<?php echo $fin['Day'];?><span class="min_txt">(<?php echo $fin['weekname'];?>)</span></p>
								</div>
								<div class="tri_base">
									<p class="btn_arrea">お届け日を調べる</p><span class="triangle1"></span></div>
							</div>
						</a>
					</div>

					<div class="col bk">
						<a href="/price/estimate.php" class="check btn_01 top_3 top_item_flex">
							<div class="top_3_img bk_img"><img src="/common/img/home/main/sp_top_three_estimate.png" width="100%"></div>
							<div class="bk_txt">
								<p class="top3_bu_txt">かんたん10秒<span class="big_font">見積もり</span></p>
								<div class="tri_base">
									<p class="btn_arrea txt_space">見積もりをする</p><span class="triangle1"></span></div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>


			<!--
<div class="row outer top_3_wrap">
<div class="col">
<a href="/delivery/" class="check">
<button type="button" class="btn top_3 top_item_flex">
<div id="date"><p><?php echo $fin['Month'];?>/<?php echo $fin['Day'];?><span class="min_txt">(<?php echo $fin['weekname'];?>)</span></p></div>
<p class="top3_bu_txt">今注文すると<br><span class="big_font">この日に届く</span></p>
</button>
</a>
<a href="/delivery/" class="check_txt">
<p class="top3_txt"><img src="/common/img/global/go_btm_blue.png">お届け日・地域変更はこちら</p>
</a>
</div>
<div class="col">
<a href="/items/category.php" class="check">
<button type="button" class="btn top_3 top_item_flex">
<div class="top_3_img"><img src="/common/img/home/main/sp_top_three_item.png" width="30%"></div>
<p class="top3_bu_txt">200種類以上！<br><span class="big_font">アイテム</span></p>
</button>
</a>
<a href="/items/category.php" class="check_txt">
<p class="top3_txt"><img src="/common/img/global/go_btm_blue.png">アイテム一覧はこちら</p>
</a>
</div>
<div class="col">
<a href="/price/estimate.php" class="check">
<button type="button" class="btn top_3 top_item_flex">
<div class="top_3_img"><img src="/common/img/home/main/sp_top_three_estimate.png" width="30%"></div>
<p class="top3_bu_txt">かんたん10秒<br><span class="big_font">見積もり</span></p>
</button>
</a>
<a href="/price/estimate.php" class="check_txt">
<p class="top3_txt"><img src="/common/img/global/go_btm_blue.png">見積もりをする</p>
</a>
</div>
</div>
-->
			<!--
<section class="hidden-sm-down">
<div>
<a href="//takahama428.secure-decoration.com/create_products/5-6-T-?n=69818603" class="check">
<img class="img-fluid" id="pc_slide" src="/common/img/home/main/top_service_deco.jpg" alt="First slide" width="100%">
</a>
</div>
</section>
-->
		<section class="hidden-xs-down">

			<div class="row no-gutters service">
				<div class="col-12 col-sm-4">
					<div class="row btn-row">
						<div class="col col-item view overlay hm-white-slight">
							<a href="/order/express/" class="btn">
								<img alt="Service" src="/common/img/home/service/top_ser_hurry.jpg" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
					</div>
					<div class="row btn-row">
						<div class="col col-item view overlay hm-white-slight">
							<a href="/design/emb.php " class="btn">
								<img alt="Service" src="/common/img/home/service/top_ser_needle.jpg" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-4">
					<!-- strtotime(日付)で日付を指定して、HTMLタグの表示を切り替える-->
					<?php
					// 2018-04-01になったら表示する
					if (time() >= strtotime('2018-04-01')) {
						echo '<div class="row btn-row">
						<div class="col col-item view overlay hm-white-slight">
							<a href="https://www.instagram.com/takahamalifeart/" class="btn">
						<img alt="Service" src="/common/img/home/service/top_ser_Insta.jpg" class="img-fluid">
						<div class="mask"></div>
					</a>
						</div>
					</div>';
					}

					// 2018-04-01になったら非表示にする
					if (time() < strtotime('2018-04-01')) {
						echo '<div class="row btn-row">
						<div class="col col-item view overlay hm-white-slight">
							<a href="/scene/graduation/index.php" class="btn">
						<img alt="Service" src="/common/img/home/service/top_ser_graduation.jpg" class="img-fluid">
						<div class="mask"></div>
					</a>
						</div>
					</div>';
					}
					?>
						<div class="row btn-row">
							<div class="col col-item view overlay hm-white-slight">
								<a href="/guide/discount.php" class="btn">
								<img alt="Service" src="/common/img/home/service/top_ser_off.jpg" class="img-fluid">
								<div class="mask"></div>
							</a>
							</div>
						</div>
				</div>
				<div class="col-12 col-sm-4">
					<div class="row btn-row">
						<div class="col col-item view overlay hm-white-slight">
							<a href="/contact/request.php" class="btn">
								<img alt="Service" src="/common/img/home/service/top_ser_sample.jpg" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
					</div>
					<div class="row btn-row">
						<div class="col col-item view overlay hm-white-slight">
							<a href="/campaign/towel/" class="btn">
								<img alt="Service" src="/common/img/home/service/top_ser_towel.jpg" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="hidden-sm-up">

			<div class="row btn-row ">
				<div class="col-6 col-item view overlay hm-white-slight">
					<a href="/order/express/" class="btn">
						<img alt="Service" src="/common/img/home/service/sp_top_ser_hurry.jpg" class="img-fluid">
						<div class="mask"></div>
					</a>
				</div>
				<!-- strtotime(日付)で日付を指定して、HTMLタグの表示を切り替える-->
				<?php
				// 2018-04-01になったら表示する
				if (time() >= strtotime('2018-04-01')) {
					echo '<div class="col-6 col-item view overlay hm-white-slight">
					<a href="https://www.instagram.com/takahamalifeart/" class="btn">
				<img alt="Service" src="/common/img/home/service/sp_top_ser_Insta.jpg" class="img-fluid">
				<div class="mask"></div>
			</a>
				</div>';
				}

				// 2018-04-01になったら非表示にする
				if (time() < strtotime('2018-04-01')) {
					echo '<div class="col-6 col-item view overlay hm-white-slight">
					<a href="/scene/graduation/index.php" class="btn">
				<img alt="Service" src="/common/img/home/service/sp_top_ser_graduation.jpg" class="img-fluid">
				<div class="mask"></div>
			</a>
				</div>';
				}
				?>
			</div>
			<div class="row btn-row ">
				<div class="col-6 col-item view overlay hm-white-slight">
					<a href="/contact/request.php" class="btn">
						<img alt="Service" src="/common/img/home/service/sp_top_ser_sample.jpg" class="img-fluid">
						<div class="mask"></div>
					</a>
				</div>
				<div class="col-6 col-item view overlay hm-white-slight">
					<a href="/design/emb.php" class="btn">
						<img alt="Service" src="/common/img/home/service/sp_top_ser_needle.jpg" class="img-fluid">
						<div class="mask"></div>
					</a>
				</div>
			</div>
			<div class="row btn-row ">
				<div class="col-6 col-item view overlay hm-white-slight">
					<a href="/guide/discount.php" class="btn">
						<img alt="Service" src="/common/img/home/service/sp_top_ser_off.jpg" class="img-fluid">
						<div class="mask"></div>
					</a>
				</div>

				<div class="col-6 col-item view overlay hm-white-slight">
					<a href="/campaign/towel/" class="btn">
						<img alt="Service" src="/common/img/home/service/sp_top_ser_towel.jpg" class="img-fluid">
						<div class="mask"></div>
					</a>
				</div>
			</div>

		</section>

		<section class="review_wrap">

			<div class="review_le" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="totalRate">
				<p>お客様の評価 <br><small class="review_total_number">( 口コミ総数：<span itemprop="ratingCount">1,045</span>件 )</small></p>
					<div>
						<img src="/common/img/home/review/sp_review_040.png" style="width: 75%; vertical-align:baseline;">
						<span class="review_total_score" style="font-size:.2px"><span class="emph" itemprop="ratingCount">4.3</span> / 5.0</span>
					</div>
					<a href="/userreviews/">
						<div class="method_button">詳しく見る</div>
					</a>
					</div>

			<!--Carousel Wrapper-->
			<div id="multi-item-example" class="carousel slide carousel-multi-item slide_wrap" data-ride="carousel">

				<!--Slides-->
				<a class="btn-floating_review" href="#multi-item-example" data-slide="prev"><i class="fa fa-chevron-left review_arrow"></i></a>
				<div class="carousel-inner" role="listbox">

					<!--First slide-->
					<div class="carousel-item active">

						<div class="col-md-4">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
									<li>
										<h3>丁寧な対応で満足でした</h3>
									</li>
									<li>
										<p>いつも、丁寧で対応の早さが素晴らしいと思います！私のようにパソコンを使ってのデザイン画を送れない人にとっては、本当に助かり...
										</p>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-md-4 clearfix d-none d-md-block">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
									<li>
										<h3>印刷色を確認したかった</h3>
									</li>
									<li>
										<p>各商品名の印刷色を決める際に、全ての商品に対して、印刷色を確認できるページが欲しいです。 Tシャツだとそんなページがありましたが、...
										</p>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-md-4 clearfix d-none d-md-block">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price">5.0</span></li>
									<li>
										<h3>丁寧な対応ありがとう</h3>
									</li>
									<li>
										<p>窓口のスタッフの対応が大変よく、ありがとうございました。 追加なども夏頃出るかと思いますが、また宜しくお願い致します。
										</p>
									</li>
								</ul>
							</div>
						</div>

					</div>
					<!--/.First slide-->

					<!--Second slide-->
					<div class="carousel-item">

						<div class="col-md-4">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.0</span></li>
									<li>
										<h3>ネット注文なのにイメージ通り！</h3>
									</li>
									<li>
										<p>ネットでこれだけ注文に応じられるのは驚きました。プリントもイメージ通りで大変満足しました。
										</p>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-md-4 clearfix d-none d-md-block">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
									<li>
										<h3>電話対応がわかりやすい</h3>
									</li>
									<li>
										<p>今回は追加注文だったのですが、電話での対応が良かった。金額の事や、デザインの微調整についてわかりやすかった。
										</p>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-md-4 clearfix d-none d-md-block">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
									<li>
										<h3>担当者へ相談できて安心</h3>
									</li>
									<li>
										<p>最初から最後まで、一人の担当者が相談に乗ってくれるので発注までスムーズでした。 また宜しくお願い致します。
										</p>
									</li>
								</ul>
							</div>
						</div>

					</div>
					<!--/.Second slide-->

					<!--Third slide-->
					<div class="carousel-item">

						<div class="col-md-4">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
									<li>
										<h3>安くて、早い</h3>
									</li>
									<li>
										<p>安く、早く届き非常に助かりました。ありがとうございました。
										</p>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-md-4 clearfix d-none d-md-block">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price">5.0</span></li>
									<li>
										<h3>優しく素早い対応でした。</h3>
									</li>
									<li>
										<p>わがままばかりで色々とお手数おかけいたしましたが、優しく素早く対応して下さり感謝しております。 ありがとうございました。
										</p>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-md-4 clearfix d-none d-md-block">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price">3.7</span></li>
									<li>
										<h3>時間がなかったのにスムーズ</h3>
									</li>
									<li>
										<p>注文から発送まで本当にスムーズで時間がなかった私たちには嬉しい限りでした。
										</p>
									</li>
								</ul>
							</div>
						</div>

					</div>
					<!--/.forth slide-->

					<!--Third slide-->
					<div class="carousel-item">

						<div class="col-md-4">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.0</span></li>
									<li>
										<h3>イメージ通りに仕上がった</h3>
									</li>
									<li>
										<p>白インクの部分が、少しだけ明度が下がったように感じたましたが、イメージ通りに仕上がり良かったです。 注文の最終確認の段階で、...
										</p>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-md-4 clearfix d-none d-md-block">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
									<li>
										<h3>またお願いします。</h3>
									</li>
									<li>
										<p>欲しかった通りの物が届いて嬉しかったです。 また何かオリジナルTシャツを作りたいときはお願いしたいと思います。
										</p>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-md-4 clearfix d-none d-md-block">
							<div>
								<ul>
									<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
									<li>
										<h3>感じのよい電話対応でした</h3>
									</li>
									<li>
										<p>電話で確認した時に対応してくださった方が、とても感じがよかったです。 気持ちよくお話ができました。
										</p>
									</li>
								</ul>
							</div>
						</div>

					</div>
					<!--/.forth slide-->

				</div>
				<!--/.Slides-->
				<a class="btn-floating_review" href="#multi-item-example" data-slide="next"><i class="fa fa-chevron-right review_arrow"></i></a>
			</div>
			<!--/.Carousel Wrapper-->
		</section>

		<section class="hidden-xs-down">
			<h2 class="rank_ttl">アイテムランキング　人気BEST５</h2>
			<div class="row justify-content-around">
				<div class="col-sm-2 col-md-offset-1 rank_box">
					<a href="/items/item.php?code=085-cvt">
						<div>
							<div>
								<img src="/common/img/home/ranking/ranking_01.jpg" style="width: 25%;">
								<img src="/common/img/home/ranking/ap_ranking_085.png" style="width: 100%;">
							</div>
							<p class="rank_name">085-CVT<br>5.6オンスヘビーウエイトＴシャツ</p>
							<p class="rank_price">¥500～</p>
						</div>
					</a>
				</div>
				<div class="col-sm-2 rank_box">
					<a href="/items/item.php?code=302-ADP">
						<div>
							<div>
								<img src="/common/img/home/ranking/ranking_02.jpg" style="width: 25%;">
								<img src="/common/img/home/ranking/ap_ranking_302.png" style="width: 100%;">
							</div>
							<p class="rank_name">302-ADP<br>4.4オンスドライポロシャツ</p>
							<p class="rank_price">¥750～</p>
						</div>
					</a>
				</div>

				<div class="col-sm-2 rank_box">
					<a href="/items/item.php?code=537-FTC">
						<div>
							<div>
								<img src="/common/img/home/ranking/ranking_03.jpg" style="width: 25%;">
								<img src="/common/img/home/ranking/ap_ranking_537.png" style="width: 100%;">
							</div>
							<p class="rank_name">537-FTC<br>カラーフェイスタオル</p>
							<p class="rank_price">¥380～</p>
						</div>
					</a>
				</div>

				<div class="col-sm-2 rank_box">
					<a href="/items/item.php?code=300-ACT">
						<div>
							<div>
								<img src="/common/img/home/ranking/ranking_04.jpg" style="width: 25%;">
								<img src="/common/img/home/ranking/ap_ranking_300.png" style="width: 100%;">
							</div>
							<p class="rank_name">300-ACT<br>4.4オンスドライＴシャツ</p>
							<p class="rank_price">¥450～</p>
						</div>
					</a>
				</div>

				<div class="col-sm-2 rank_box">
					<a href="/items/item.php?code=185-NSZ">
						<div>
							<div>
								<img src="/common/img/home/ranking/ranking_05.jpg" style="width: 25%;">
								<img src="/common/img/home/ranking/ap_ranking_185.png" style="width: 100%;">
							</div>
							<p class="rank_name">185-NSZ<br>スタンダードジップパーカー</p>
							<p class="rank_price">¥2,140～</p>
						</div>
					</a>
				</div>
			</div>
		</section>


		<section class="hidden-sm-up">
			<h2 class="rank_ttl">アイテムランキング　人気BEST3</h2>
			<div class="row justify-content-around">
				<div class="col-4 col-md-offset-1 rank_box">
					<a href="/items/item.php?code=085-cvt">
						<div>
							<div>
								<img src="/common/img/home/ranking/ranking_01.jpg" style="width: 25%;">
								<img src="/common/img/home/ranking/ap_ranking_085.png" style="width: 100%;">
							</div>
							<p class="rank_name">085-CVT<br>5.6オンスヘビーウエイトＴシャツ</p>
							<p class="rank_price">¥500～</p>
						</div>
					</a>
				</div>

				<div class="col-4 rank_box">
					<a href="/items/item.php?code=302-ADP">
						<div>
							<div>
								<img src="/common/img/home/ranking/ranking_02.jpg" style="width: 25%;">
								<img src="/common/img/home/ranking/ap_ranking_302.png" style="width: 100%;">
							</div>
							<p class="rank_name">302-ADP<br>4.4オンスドライポロシャツ</p>
							<p class="rank_price">¥750～</p>
						</div>
					</a>
				</div>

				<div class="col-4 rank_box">
					<a href="/items/item.php?code=537-FTC">
						<div>
							<div>
								<img src="/common/img/home/ranking/ranking_03.jpg" style="width: 25%;">
								<img src="/common/img/home/ranking/ap_ranking_537.png" style="width: 100%;">
							</div>
							<p class="rank_name">537-FTC<br>カラーフェイスタオル</p>
							<p class="rank_price">¥380～</p>
						</div>
					</a>
				</div>
			</div>
		</section>

		<section>
			<h2 class="mid_ttl_2"><img src="/common/img/home/main/Instagram.png">お客様インスタ画像
				<div class="ball">
					<p>デザイン実例が見れる！</p>
				</div>
			</h2>
			<div class="stage">
				<div class="photos">
					<?php echo $instaPhoto;?>
				</div>
				<div class="slide-pane"></div>
			</div>
		</section>

		<div class="outer">
			<div class="row ">
				<div class="col-12 col-md-6 pb-sm-down">
					<h2 class="mid_ttl">おすすめブランド</h2>
					<table rules="all">
						<tbody>
							<tr>
								<td>
									<a href="/items/?tag=108"><img src="/common/img/home/brand/sp_brand_gildan.png" style="width: 100%;"></a>
								</td>
								<td>
									<a href="/items/?tag=61"><img src="/common/img/home/brand/sp_brand_wundou.png" style="width: 100%;"></a>
								</td>
								<td>
									<a href="/items/?tag=59"><img src="/common/img/home/brand/sp_brand_unitedathle.png" style="width: 100%;"></a>
								</td>
							</tr>
							<tr>
								<td>
									<a href="/items/?tag=43"><img src="/common/img/home/brand/sp_brand_champion.png" style="width: 100%;"></a>
								</td>
								<td>
									<a href="/items/?tag=58"><img src="/common/img/home/brand/sp_brand_printstar.png" style="width: 100%;"></a>
								</td>
								<td>
									<a href="/items/?tag=60"><img src="/common/img/home/brand/sp_brand_glimmer.png" style="width: 100%;"></a>
								</td>
							</tr>
						</tbody>
					</table>

				</div>
				<div class="col-12 col-md-6">
					<h2 class="mid_ttl">お知らせ</h2>
					<div class="list-group-wrap px-2">
						<ul class="list-group">
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2018.2.27
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【価格改定のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2017.12.14
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【冬季休業のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2017.11.22
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【アイテム価格改定のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2017.10.24
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【アイテム価格改定のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2017.8.11
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【夏季休業のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2017.6.22
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【アイテム価格改定のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2017.5.26
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【初回追加価格終了のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2017.5.23
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【価格改定のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3 news_date">
										2017.5.3
									</div>
									<a href="/guide/information.php">
										<div class="col-12 col-lg news_ttl">
											【GW休業のお知らせ】
										</div>
									</a>
								</div>
							</li>
						</ul>
					</div>
					<a href="/guide/information.php">
						<p class="news_txt"> >「お知らせ」一覧を見る</p>
					</a>
				</div>
			</div>
		</div>

		<section class="hidden-xs-down">
			<div class="outer">
				<div class="row">
					<div class="col-12 col-md-6 pb-sm-down blog_box">
						<div><img src="/common/img/home/blog/top_customerblog.jpg" width="100%"></div>
						<div class="blog_txt">
							<p class="blog_ttl">お客様ブログ</p>
							<p class="blog_com">タカハマの制作実績を紹介！ <br>掲載OKのお客様は<br>写真掲載割3％OFF！ <br>デザインの参考にどうぞ！</p>
							<a href="/app/WP/thanks-blog">
								<div class="method_button_blog">制作実績を見る</div>
							</a>
						</div>
					</div>
					<div class="col-12 col-md-6 pb-sm-down blog_box">
						<div><img src="/common/img/home/blog/top_staffblog.jpg" width="100%"></div>
						<div class="blog_txt">
							<p class="blog_ttl">スタッフブログ</p>
							<p class="blog_com_2">タカハマのスタッフの<br>様子を楽しく週1回更新<br>お得な情報も掲載中！</p>
							<a href="/app/WP">
								<div class="method_button_blog">タカハマの日常を見る</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="hidden-sm-up">
			<div class="outer">
				<div class="row">
					<div class="col-12 col-md-6 pb-sm-down blog_box">
						<div><img src="/common/img/home/blog/sp_top_customerblog.jpg" width="100%"></div>
						<div class="blog_txt">
							<p class="blog_ttl">お客様ブログ</p>
							<p class="blog_com">タカハマの制作実績を紹介！ <br>掲載OKのお客様は<br>写真掲載割3％OFF！ <br>デザインの参考にどうぞ！</p>
							<a href="/app/WP/thanks-blog">
								<div class="method_button_blog">制作実績を見る</div>
							</a>
						</div>
					</div>
					<div class="col-12 col-md-6 pb-sm-down blog_box">
						<div><img src="/common/img/home/blog/sp_top_staffblog.jpg" width="100%"></div>
						<div class="blog_txt">
							<p class="blog_ttl">スタッフブログ</p>
							<p class="blog_com_2">タカハマのスタッフの<br>様子を楽しく週1回更新<br>お得な情報も掲載中！</p>
							<a href="/app/WP">
								<div class="method_button_blog">タカハマの日常を見る</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>

	</main>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
</body>

</html>
