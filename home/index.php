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
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="google-site-verification" content="PfzRZawLwE2znVhB5M7mPaNOKFoRepB2GO83P73fe5M">
		<meta name="Description" content="オリジナルTシャツ作成が早い！即納可能の業界最速！クラスTシャツの文字入れも最短即日で短納期プリント。1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！">
		<meta property="og:type" content="website">
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。">
		<meta property="og:url" content="https://www.takahama428.com/">
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート">
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
		<meta property="fb:app_id" content="1605142019732010">
		<title>オリジナルTシャツ作成が早い【当日発送】 ｜ タカハマライフアート</title>
		<link rel=canonical href="https://www.takahama428.com/">
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" href="./css/style.css">
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>
		<div class="container-fluid">
			<div id="mainCarouselIndicators" class="carousel slide" data-ride="carousel">
				
				<ol class="carousel-indicators">
					<li data-target="#mainCarouselIndicators" data-slide-to="0" class="active"></li>
					<li data-target="#mainCarouselIndicators" data-slide-to="1"></li>
<!--					<li data-target="#mainCarouselIndicators" data-slide-to="2"></li>-->
				</ol>

				<div class="carousel-inner justify-content-center" role="listbox">
					<div class="carousel-item active">
						<img class="img-fluid" id="pc_slide" src="/common/img/home/main/top_slide_speed.jpg" alt="First slide" width="100%">
						<img class="img-fluid" id="sp_slide" src="/common/img/home/main/sp_top_slide_speed.jpg" alt="First slide" width="100%">
						<div class="carousel-caption">
							<h3></h3>
							<a href="/order/"><button type="button" class="order_btn"><img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 2px;">つくってみる</button></a>
						</div>
					</div>

				<div class="carousel-item">
					<img class="img-fluid" id="pc_slide" src="/common/img/home/main/top_slide_record.jpg" alt="First slide" width="100%">
					<img class="img-fluid" id="sp_slide" src="/common/img/home/main/sp_top_slide_record.jpg" alt="First slide" width="100%">
					<div class="carousel-caption">
						<h3></h3>
						<a href="/order/"><button type="button" class="order_btn"><img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 2px;">つくってみる</button></a>
					</div>
				</div>
<!--
				<div class="carousel-item">
					<img class="d-block img-fluid" src="//placehold.jp/eeeeee/3d4070/1500x450.png?text=Third slide" alt="Third slide">
				</div>
-->


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
			<div class="row outer top_3_wrap">
				<div class="col">
					<a href="/delivery/">
						<button type="button" class="btn top_3 top_item_flex">
						<div id="date"><p><?php echo $fin['Month'];?>/<?php echo $fin['Day'];?><span class="min_txt">(<?php echo $fin['weekname'];?>)</span></p></div>
						<p class="top3_bu_txt">今注文すると<br><span class="big_font">この日に届く</span></p>
				</button>
					</a>
					<a href="/delivery/">
						<p class="top3_txt"><img src="/common/img/global/go_btm_blue.png">お届け日・地域変更はこちら</p>
					</a>
				</div>
				<div class="col">
					<a href="/items/category.php">
						<button type="button" class="btn top_3 top_item_flex">
				<div class="top_3_img"><img src="/common/img/home/main/sp_top_three_item.png" width="30%"></div>
				<p class="top3_bu_txt">200種類以上！<br><span class="big_font">アイテム</span></p>
					</button>
					</a>
					<a href="/items/category.php">
						<p class="top3_txt"><img src="/common/img/global/go_btm_blue.png">アイテム一覧はこちら</p>
					</a>
				</div>
				<div class="col">
					<a href="/price/estimate.php">
						<button type="button" class="btn top_3 top_item_flex">
				<div class="top_3_img"><img src="/common/img/home/main/sp_top_three_estimate.png" width="30%"></div>
				<p class="top3_bu_txt">かんたん10秒<br><span class="big_font">見積もり</span></p>
				</button>
					</a>
					<a href="/price/estimate.php">
						<p class="top3_txt"><img src="/common/img/global/go_btm_blue.png">見積もりをする</p>
					</a>
				</div>
			</div>

			<section class="hidden-xs-down">
				<!--			<h1>サービス</h1>-->
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
									<img alt="Service" src="/common/img//home/service/top_ser_needle.jpg" class="img-fluid">
									<div class="mask"></div>
								</a>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-4">
						<div class="row btn-row">
							<div class="col col-item view overlay hm-white-slight">
								<a href="//www.instagram.com/takahamalifeart/" class="btn">
									<img alt="Service" src="/common/img//home/service/top_ser_Insta.jpg" class="img-fluid">
									<div class="mask"></div>
								</a>
							</div>
						</div>
						<div class="row btn-row">
							<div class="col col-item view overlay hm-white-slight">
								<a href="/guide/discount.php" class="btn">
									<img alt="Service" src="/common/img//home/service/top_ser_off.jpg" class="img-fluid">
									<div class="mask"></div>
								</a>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-4">
						<div class="row btn-row">
							<div class="col col-item view overlay hm-white-slight">
								<a href="/contact/request.php" class="btn">
									<img alt="Service" src="/common/img//home/service/top_ser_sample.jpg" class="img-fluid">
									<div class="mask"></div>
								</a>
							</div>
						</div>
						<div class="row btn-row">
							<div class="col col-item view overlay hm-white-slight">
								<a href="/campaign/towel/" class="btn">
									<img alt="Service" src="/common/img//home/service/top_ser_towel.jpg" class="img-fluid">
									<div class="mask"></div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</section>


			<section class="hidden-sm-up">
				<!--			<h1>サービス</h1>-->
				<div class="row btn-row ">
					<div class="col-6 col-item view overlay hm-white-slight">
						<a href="/order/express/" class="btn">
							<img alt="Service" src="/common/img/home/service/sp_top_ser_hurry.jpg" class="img-fluid">
							<div class="mask"></div>
						</a>
					</div>
					<div class="col-6 col-item view overlay hm-white-slight">
						<a href="//www.instagram.com/takahamalifeart/" class="btn">
							<img alt="Service" src="/common/img//home/service/sp_top_ser_Insta.jpg" class="img-fluid">
							<div class="mask"></div>
						</a>
					</div>
				</div>
				<div class="row btn-row ">
					<div class="col-6 col-item view overlay hm-white-slight">
						<a href="/contact/request.php" class="btn">
							<img alt="Service" src="/common/img//home/service/sp_top_ser_sample.jpg" class="img-fluid">
							<div class="mask"></div>
						</a>
					</div>
					<div class="col-6 col-item view overlay hm-white-slight">
						<a href="/design/emb.php" class="btn">
							<img alt="Service" src="/common/img//home/service/sp_top_ser_needle.jpg" class="img-fluid">
							<div class="mask"></div>
						</a>
					</div>
				</div>
				<div class="row btn-row ">
					<div class="col-6 col-item view overlay hm-white-slight">
						<a href="/guide/discount.php" class="btn">
							<img alt="Service" src="/common/img//home/service/sp_top_ser_off.jpg" class="img-fluid">
							<div class="mask"></div>
						</a>
					</div>

					<div class="col-6 col-item view overlay hm-white-slight">
						<a href="/campaign/towel/" class="btn">
							<img alt="Service" src="/common/img//home/service/sp_top_ser_towel.jpg" class="img-fluid">
							<div class="mask"></div>
						</a>
					</div>
				</div>

			</section>



			<section class="review_wrap">

				<div class="review_le">
					<p>お客様の評価</p>
					<div><img src="/common/img/home/review/sp_review_total.png" style="width: 100%;"></div>
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
								<div><img src="/common/img/home/review/review/sp_review_01.png" style="width: 100%;"></div>
							</div>

							<div class="col-md-4 clearfix d-none d-md-block">
								<div><img src="/common/img/home/review/review/sp_review_02.png" style="width: 100%;"></div>
							</div>

							<div class="col-md-4 clearfix d-none d-md-block">
								<div><img src="/common/img/home/review/review/sp_review_03.png" style="width: 100%;"></div>
							</div>

						</div>
						<!--/.First slide-->

						<!--Second slide-->
						<div class="carousel-item">

							<div class="col-md-4">
								<div><img src="/common/img/home/review/review/sp_review_04.png" style="width: 100%;"></div>
							</div>

							<div class="col-md-4 clearfix d-none d-md-block">
								<div><img src="/common/img/home/review/review/sp_review_05.png" style="width: 100%;"></div>
							</div>

							<div class="col-md-4 clearfix d-none d-md-block">
								<div><img src="/common/img/home/review/review/sp_review_06.png" style="width: 100%;"></div>
							</div>

						</div>
						<!--/.Second slide-->

						<!--Third slide-->
						<div class="carousel-item">

							<div class="col-md-4">
								<div><img src="/common/img/home/review/review/sp_review_07.png" style="width: 100%;"></div>
							</div>

							<div class="col-md-4 clearfix d-none d-md-block">
								<div><img src="/common/img/home/review/review/sp_review_08.png" style="width: 100%;"></div>
							</div>

							<div class="col-md-4 clearfix d-none d-md-block">
								<div><img src="/common/img/home/review/review/sp_review_09.png" style="width: 100%;"></div>
							</div>

						</div>
						<!--/.forth slide-->

						<!--Third slide-->
						<div class="carousel-item">

							<div class="col-md-4">
								<div><img src="/common/img/home/review/review/sp_review_10.png" style="width: 100%;"></div>
							</div>

							<div class="col-md-4 clearfix d-none d-md-block">
								<div><img src="/common/img/home/review/review/sp_review_11.png" style="width: 100%;"></div>
							</div>

							<div class="col-md-4 clearfix d-none d-md-block">
								<div><img src="/common/img/home/review/review/sp_review_12.png" style="width: 100%;"></div>
							</div>

						</div>
						<!--/.forth slide-->

					</div>
					<!--/.Slides-->
					<a class="btn-floating_review" href="#multi-item-example" data-slide="next"><i class="fa fa-chevron-right review_arrow"></i></a>
				</div>
				<!--/.Carousel Wrapper-->
			</section>

			<!--
		<section>
			<h2>ご注文の流れ</h2>
			<div class="row">
				<div class="col-12 col-md-4 text-center">
					カンタン３ステップ
				</div>
				<div class="col-12 col-md-4 text-center">
					STEP1　STEP2　STEP3
				</div>
				<div class="col-12 col-md-4 text-center">
					<a href="#!" class="btn btn-outline-primary waves-effect">詳しく見る</a>
				</div>
			</div>
		</section>
-->
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


			<!--
		<section>
			<h2>プリントしたときの値段</h2>
			<div class="row justify-content-around">
				<div class="col-sm-6 col-md-4 col-lg px-2">
					<div class="card card-inverse">
						<img class="card-img" src="//placehold.jp/999999/3d4070/550x550.png?text=画像" alt="Card image">
						<div class="card-img-overlay">
							<h4 class="card-title">Image title</h4>
							<p class="card-text">Description.</p>
							<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg px-2">
					<div class="card card-inverse">
						<img class="card-img" src="//placehold.jp/999999/3d4070/550x550.png?text=画像" alt="Card image">
						<div class="card-img-overlay">
							<h4 class="card-title">Image title</h4>
							<p class="card-text">Description.</p>
							<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg px-2">
					<div class="card card-inverse">
						<img class="card-img" src="//placehold.jp/999999/3d4070/550x550.png?text=画像" alt="Card image">
						<div class="card-img-overlay">
							<h4 class="card-title">Image title</h4>
							<p class="card-text">Description.</p>
							<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg px-2">
					<div class="card card-inverse">
						<img class="card-img" src="//placehold.jp/999999/3d4070/550x550.png?text=画像" alt="Card image">
						<div class="card-img-overlay">
							<h4 class="card-title">Image title</h4>
							<p class="card-text">Description.</p>
							<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-4 col-lg px-2">
					<div class="card card-inverse">
						<img class="card-img" src="//placehold.jp/999999/3d4070/550x550.png?text=画像" alt="Card image">
						<div class="card-img-overlay">
							<h4 class="card-title">Image title</h4>
							<p class="card-text">Description.</p>
							<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
						</div>
					</div>
				</div>
			</div>
		</section>
		
-->
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


			<!--
		<div class="outer">
			<div class="row no-gutters">
				<div class="col-12 col-md-6 pb-sm-down">
					<h2>おすすめブランド</h2>
					<div class="row btn-row px-2">
						<div class="col col-md-4 view overlay hm-white-slight">
							<a href="#!" class="btn">
								<img alt="Service" src="//placehold.jp/bbbbbb/3d4070/550x550.png?text=画像" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
						<div class="col col-md-4 view overlay hm-white-slight">
							<a href="#!" class="btn">
								<img alt="Service" src="//placehold.jp/bbbbbb/3d4070/550x550.png?text=画像" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
						<div class="col col-md-4 view overlay hm-white-slight">
							<a href="#!" class="btn">
								<img alt="Service" src="//placehold.jp/bbbbbb/3d4070/550x550.png?text=画像" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
						<div class="col col-md-4 view overlay hm-white-slight">
							<a href="#!" class="btn">
								<img alt="Service" src="//placehold.jp/bbbbbb/3d4070/550x550.png?text=画像" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
						<div class="col col-md-4 view overlay hm-white-slight">
							<a href="#!" class="btn">
								<img alt="Service" src="//placehold.jp/bbbbbb/3d4070/550x550.png?text=画像" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
						<div class="col col-md-4 view overlay hm-white-slight">
							<a href="#!" class="btn">
								<img alt="Service" src="//placehold.jp/bbbbbb/3d4070/550x550.png?text=画像" class="img-fluid">
								<div class="mask"></div>
							</a>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<h2>ニュース</h2>
					<div class="list-group-wrap px-2">
						<ul class="list-group">
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3">
										2017-12-31
									</div>
									<div class="col-12 col-lg">
										text .......................... ................................ .................................
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3">
										2017-12-31
									</div>
									<div class="col-12 col-lg">
										text .......................... ................................ .................................
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3">
										2017-12-31
									</div>
									<div class="col-12 col-lg">
										text .......................... ................................ .................................
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3">
										2017-12-31
									</div>
									<div class="col-12 col-lg">
										text .......................... ................................ .................................
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-lg-3">
										2017-12-31
									</div>
									<div class="col-12 col-lg">
										text .......................... ................................ .................................
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
-->


			<section class="hidden-xs-down">
				<div class="outer">
					<div class="row">
						<div class="col-12 col-md-6 pb-sm-down blog_box">
							<div><img src="/common/img/home/blog/top_customerblog.jpg" width="100%"></div>
							<div class="blog_txt">
								<p class="blog_ttl">お客様ブログ</p>
								<p class="blog_com">タカハマの制作実績を紹介！ <br>掲載OKのお客様は<br>ブログ割引3％OFF！ <br>デザインの参考にどうぞ！</p>
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
								<p class="blog_com">タカハマの制作実績を紹介！ <br>掲載OKのお客様は<br>ブログ割引3％OFF！ <br>デザインの参考にどうぞ！</p>
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
