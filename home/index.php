<?php
/**
 * お届け日を取得
 * 月：$fin['Month']
 * 日：$fin['Day']
 * 曜日：$fin['Weekname']
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
$conn = new Conndb();
$fin = json_decode($conn->delidate(0, array(1,2,3,4)), true);


/**
 * 注文締切日
 */
$date1 = new DateTime(null, new DateTimeZone('Asia/Tokyo'));
$date2 = new DateTime(null, new DateTimeZone('Asia/Tokyo'));
$hour = date('H');

// 通常、２日、翌日プラン
if ($hour >= 13) {
	$date1->modify('+1 days');
}
$timestamp = $date1->getTimestamp();
$d = json_decode($conn->delidate($timestamp, array(1), 0), true);
$normalDeadline = $d[0]['Year'].'-'.$d[0]['Month'].'-'.$d[0]['Day'];

// 当日特急プラン
if ($hour >= 12) {
	$date2->modify('+1 days');
}
$timestamp = $date2->getTimestamp();
$d = json_decode($conn->delidate($timestamp, array(1), 0), true);
$specialDeadline = $d[0]['Year'].'-'.$d[0]['Month'].'-'.$d[0]['Day'];


/**
 * アイテムランキング
 */
$tmp = json_decode($conn->categoryListV3(), true);
$categoryIds = array_column($tmp, 'id', 'code');
$itemRanking = array(
	't-shirts' => null,
	'polo-shirts' => null,
	'sweat' => null,
	'towel' => null,
	'outer' => null,
);
$contentsLength = 8;
foreach ($categoryIds as $categoryCode => $categoryId) {
	if (!array_key_exists($categoryCode, $itemRanking)) {
		continue;
	} else if ($categoryCode=='sportswear') {
		$_ID = 73;
		$mode = "tag";
	} else {
		$_ID = $categoryId;
		$mode = "category";
	}
	$itemRanking[$categoryCode] = json_decode($conn->categoryInfo($_ID, [], $mode, 'popular', $contentsLength), true);
}

// Description
$itemRanking['t-shirts']['desc'] = "オリジナルTシャツ作成に人気なアイテムです！上位に入ってくるのはカラーとサイズ展開が多く、どんなシーンにも合うTシャツです。価格を安くおさえたい方も多く、生地が薄めのTシャツも人気です。最近のTシャツは、生地が薄くても丈夫なものばかりなので薄いから低品質ということはありませんので是非ご活用ください。近年、ドライタイプの需要が高まり価格もリーズナブルになってのでドライがランキングに入るようになりました！";
$itemRanking['polo-shirts']['desc'] = "オリジナルポロシャツ作成に人気なアイテムです！上位に入ってくるのはカラーとサイズ展開が多く、どんなシーンにも合うポロシャツです。価格を安くおさえたい方も多く、生地が薄めのポロシャツも人気です。生地が薄めでも鹿の子編みのポロシャツはしっかり感あるので丈夫さを気にする方でも問題なく着られます。近年、ドライタイプの需要が高まり価格もリーズナブルになってのでドライがランキングに入るようになりました！";
$itemRanking['sweat']['desc'] = "オリジナルスウェット作成に人気なアイテムです！上位に入ってくるのはカラーとサイズ展開が多く、どんなシーンにも合うパーカーです。価格を安くおさえたい方も多く、生地が薄めのパーカーが人気です。裏生地にはパイルと裏起毛の2種類ありますがオールシーズン着れるパイルが低価格なのもあり人気です。2017年はプルオーバーパーカーが流行りましたが、2018年は、ジップパーカーが流行るみたいです！";
$itemRanking['towel']['desc'] = "オリジナルタオル作成に人気なアイテムです！上位に入ってくるのはカラータオルです。昔はタオルは染めるのが一般でしたが染めは価格が高いので、今は色付きのタオルにプリントを載せるが価格をおさえられて人気です。形は大きすぎず小さすぎないフェイスタオルとマフラータオルがダントツで人気です。タオルの風合いを気にする方は染めタオルも製作できるのでお問い合わせください！";
$itemRanking['outer']['desc'] = "オリジナルブルゾン作成に人気なアイテムです！上位に入ってくるのはイベントのスタッフ用に使用するシンプルなイベントブルゾンです。イベントブルゾンは企業の展示会や選挙活動や学園祭や音楽フェスなどで良く使用されています。近年、価格は少し高くなりますがシンプルなものでなく機能や見た目に拘る方も増えています。幅広い用途に合わせたブルゾン・ジャケットをご用意してますのでお問い合わせください！";

// 評価を0.5単位に変換し画像パスを返す
function getStar($args){
	if($args<0.5){
		$r = '00';
	}else if($args>=0.5 && $args<1){
		$r = '05';
	}else if($args>=1 && $args<1.5){
		$r = '10';
	}else if($args>=1.5 && $args<2){
		$r = '15';
	}else if($args>=2 && $args<2.5){
		$r = '20';
	}else if($args>=2.5 && $args<3){
		$r = '25';
	}else if($args>=3 && $args<3.5){
		$r = '30';
	}else if($args>=3.5 && $args<4){
		$r = '35';
	}else if($args>=4 && $args<4.5){
		$r = '40';
	}else if($args>=4.5 && $args<5){
		$r = '45';
	}else{
		$r = '50';
	}
	return $r;
}

/**
 * 消費税率
 */
$tax = json_decode($conn->salesTax(), true);
?>

	<!DOCTYPE html>

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<title>オリジナルTシャツのプリント作成 ｜ タカハマライフアート</title>
		<meta name="Description" content="オリジナルTシャツ作成が早い！即納可能の業界最速！クラスTシャツの文字入れも最短即日で短納期プリント。1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。">
		<meta name="keywords" content="タカハマライフアート,オリジナル,プリント,tシャツ">
		<link rel=canonical href="https://www.takahama428.com/">
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta name="google-site-verification" content="PfzRZawLwE2znVhB5M7mPaNOKFoRepB2GO83P73fe5M">
		<meta property="og:title" content="オリジナルウェアを通常3日で製作！">
		<meta property="og:type" content="website">
		<meta property="og:description" content="オリジナルTシャツ業界No.1の当日製作のプランあります！！親切対応と高品質で安心の満足度94%！">
		<meta property="og:url" content="https://www.takahama428.com/">
		<meta property="og:site_name" content="オリジナルプリント屋｜タカハマライフアート">
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
		<meta property="fb:app_id" content="1605142019732010">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="/common/js/dist/css/slider-pro.min.css" media="screen" />
		<link rel="stylesheet" href="/common/js/dist/css/owl.carousel.min.css" media="screen" />
		<link rel="stylesheet" href="/common/css/TimeCircles.css" media="screen" />
		<link rel="stylesheet" href="./icon/style.css">
        <link rel="stylesheet" href="./icon/add180803/style.css">
		<!--		<link rel="stylesheet" type="text/css" href="/common/js/libs/fancybox/jquery.fancybox.css" media="screen" />-->
		<link rel="stylesheet" type="text/css" href="/common/css/examples.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="/common/css/side_menu.css" media="screen" />
		<link rel="stylesheet" href="/slick/slick.css">
		<link rel="stylesheet" href="/slick/slick-theme.css">
		<link rel="stylesheet" href="./css/style.css">

		<style>
			.owl-item {
				width: 100% !important;
			}

		</style>

		<script>
			var _TAX = <?php echo $tax;?>;

		</script>
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/top_header.php"; ?>
		</header>

		<div class="container-fluid">
			<div id="example2" class="slider-pro">
				<div class="sp-slides">
				
<!--				和田さんのseo関連でクラスを作った-->
				
					<div class="sp-slide">
					
						<div class="carousel-item active hero-carousel__cell hero-carousel__cell--1 carousel-item_02">
							<img class="sp-image" id="slide-seo-originalprint" src="/common/img/home/main/top_slide_originalwear.jpg" data-small="/common/img/home/main/sp_top_slide_originalwear.jpg" data-medium="/common/img/home/main/sp_top_slide_originalwear.jpg" data-large="/common/img/home/main/top_slide_originalwear.jpg" alt="オリジナルTシャツ通常3日で製作、年間27万枚納品" />
						</div>
						
					</div>
				

					
					<div class="sp-slide">
						<div class="carousel-item hero-carousel__cell hero-carousel__cell--2 carousel-item_02">
							<a class="slide-seo-sameday" href="/order/express/"> 
                            
                            <img class="sp-image slide_link_02"
                                 data-src="/common/img/home/main/top_slide_samedayplan.jpg"
                                 data-small="/common/img/home/main/sp_top_slide_samedayplan.jpg"
                                 data-medium="/common/img/home/main/sp_top_slide_samedayplan.jpg"
								 data-large="/common/img/home/main/top_slide_samedayplan.jpg" alt="当日特急プラン今日仕上げます。業界最速6時間で即日発送！"/>
                            </a>


						
					</div>
					</div>
					
					
					
					
					<div class="sp-slide">
						<div class="carousel-item hero-carousel__cell hero-carousel__cell--3 carousel-item_02">

							<a class="slide-seo-kindly" href="/userreviews/"> 

								<img class="sp-image slide_link_02" 
									 data-src="/common/img/home/main/top_slide_kindly_cs.jpg"
									 data-small="/common/img/home/main/sp_top_slide_kindly_cs.jpg"
									 data-medium="/common/img/home/main/sp_top_slide_kindly_cs.jpg"
									 data-large="/common/img/home/main/top_slide_kindly_cs.jpg" alt="親切対応で選ばれています。レビュー総数1000件以上、対応満足度94%"/>
							</a>

						</div>
				
					</div>
					
					
				
					<div class="sp-slide">
						<div class="carousel-item hero-carousel__cell hero-carousel__cell--3 carousel-item_02">

							<a class="slide-seo-bigorder" href="/order/bigorder/"> 

								<img class="sp-image slide_link_02" 
									 data-src="/common/img/home/main/large-orders_pc.jpg"
									 data-small="/common/img/home/main/large-orders_sp.jpg"
									 data-medium="/common/img/home/main/large-orders_sp.jpg"
									 data-large="/common/img/home/main/large-orders_pc.jpg" alt="大口注文も超早い！200枚を3日仕上げの実績！"/>
							</a>

						</div>
					</div>
					


					<?php
					/*** strtotime(日付)で日付を指定して、HTMLタグの表示を切り替える**/
					// 2018-09-29になったら、echo''を非表示にする
					if (time() < strtotime('2018-09-29 0:00:00')) {
						echo '<div class="sp-slide">';
						echo '<div class="carousel-item hero-carousel__cell hero-carousel__cell--3 carousel-item_02">';
						echo '<a class="slide-seo-anvil" href="/items/?tag=109">';
						echo '<img class="sp-image slide_link_02"
									 data-src="/common/img/home/main/anvil10off_pc.jpg" 
									 data-small="/common/img/home/main/anvil10off_sp.jpg" 
									 data-medium="/common/img/home/main/anvil10off_sp.jpg" 
									 data-large="/common/img/home/main/anvil10off_pc.jpg" alt="anvil10%" />';
						echo '</a>';
						echo '</div>';
						echo '</div>';
					}
					?>
                    
<!--
                     <div class="sp-slide">
						<div class="carousel-item hero-carousel__cell hero-carousel__cell--3 carousel-item_02">

							<a class="slide-seo-designsimulator" href="/design/designsimulator.php">

								<img class="sp-image slide_link_02"
									 data-src="/common/img/home/main/simulator-banner.jpg" 
									 data-small="/common/img/home/main/simulator-banner.jpg" 
									 data-medium="/common/img/home/main/simulator-banner.jpg" 
									 data-large="/common/img/home/main/simulator-banner.jpg" alt="designsimulator" />
							</a>

						</div>
					</div>
-->
                    
					
				</div>
			</div>

		</div>

		<!--        <div class="bar_mess"><a href="/guide/information.php"><span class="big_mess"><i class="fas fa-exclamation-triangle"></i>価格改定のお知らせ</span>&nbsp;&nbsp;&nbsp;<span class="small_mess hidden-sm-down">詳しく見る></span></a></div>-->

		<main class="container">
			<!--お知らせ表示-->
<!--
			<div class="an_box">
				<p style="font-size:16px; font-weight:bold;">【配送遅延のお知らせ】</p>
				<p class="an_min">台風21号及び北海道で発生した地震の影響により、配送の遅延が発生しております。</p>
				<p class="an_min">何卒ご理解いただきますよう、お願い申し上げます。被害にあわれました皆様にお見舞い申し上げます。</p>
				<p class="an_min">お届けの遅延が発生している詳細な地域に関しては、<a href="http://www.kuronekoyamato.co.jp/">ヤマト運輸ホームページ内</a>のお荷物のお届け遅延状況についてをご参照ください。</p>
			</div>
-->
			<style>
				.an_box {
					text-align: center;
					padding: 7px 0 3px;
					margin-bottom: 30px;
					font-size: 12px;
					border: 1px solid #000;
				}
				.an_min {
					margin-bottom: .5rem;
				}
				@media screen and (max-width: 768px) {
					.an_box {
						padding: 10px;
						margin: 10px;
					}
					.an_min {
						margin-bottom: .5rem;
						font-size: 10px;
					}
				}
			</style>
			<section class="hidden-md-up" style="padding:0 1rem;">
				<a href="/contact/line/" class="line_banner"><img src="/contact/line/img/line_img_01.jpg" width="100%" alt="LINEでやりとりラクチンのバナー"></a>
			</section>

			<h2 class="rank_ttl">アイテムカテゴリー</h2>
			<div class="wrap_h2_under">
				<p class="h2_under">オリジナルTシャツだけでなく、様々なアイテムにプリントできます！</p>
			</div>
			<section class="hidden-sm-down">

				<div class="owl-carousel owl-theme">

					<div class="navi_inner_2 btn">
						<a class="dropdown-item t-shirts_btn" href="/items/category/t-shirts/" alt="Tシャツ">
							<img src="/items/img/item_01.jpg" width="100%" alt="Tシャツ">
<p class="item_txt_min">Tシャツ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item polo-shirts_btn" href="/items/category/polo-shirts/" alt="ポロシャツ">
							<img src="/items/img/item_03.jpg" width="100%" alt="ポロシャツ">
<p class="item_txt_min">ポロシャツ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item sweat_btn" href="/items/category/sweat/" alt="スウェット">
							<img src="/items/img/item_02.jpg" width="100%" alt="スウェット">
<p class="item_txt_min">スウェット</p>
</a>
					</div>
					<div class="navi_inner_2 btn">
						<a class="dropdown-item towel_btn" href="/items/category/towel/" alt="タオル">
							<img src="/items/img/item_08.jpg" width="100%" alt="タオル">
<p class="item_txt_min">タオル</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item sportswear_btn" href="/items/category/sportswear/" alt="スポーツ">
							<img src="/items/img/item_04.jpg" width="100%" alt="スポーツ">
<p class="item_txt_min">スポーツ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item outer_btn" href="/items/category/outer/" alt="ブルゾン">
							<img src="/items/img/item_06.jpg" width="100%" alt="ブルゾン">
<p class="item_txt_min">ブルゾン</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item long-shirts_btn" href="/items/category/long-shirts/" alt="長袖Tシャツ">
							<img src="/items/img/item_05.jpg" width="100%" alt="長袖Tシャツ">
<p class="item_txt_min">長袖Tシャツ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item tote-bag_btn" href="/items/category/tote-bag/" alt="バッグ">
							<img src="/items/img/item_09.jpg" width="100%" alt="バッグ">
<p class="item_txt_min">バッグ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item cap_btn" href="/items/category/cap/" alt="キャップ">
							<img src="/items/img/item_14.jpg" width="100%" alt="キャップ">
<p class="item_txt_min">キャップ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item apron_btn" href="/items/category/apron/" alt="エプロン">
							<img src="/items/img/item_10.jpg" width="100%" alt="エプロン">
<p class="item_txt_min">エプロン</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item baby_btn" href="/items/category/baby/" alt="ベビー">
							<img src="/items/img/item_11.jpg" width="100%" alt="ベビー">
<p class="item_txt_min">ベビー</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item overall_btn" href="/items/category/overall/" alt="つなぎ">
							<img src="/items/img/item_12.jpg" width="100%" alt="つなぎ">
<p class="item_txt_min">つなぎ</p>
</a>
					</div>
					<div class="navi_inner_2 btn">
						<a class="dropdown-item ladys_btn" href="/items/category/ladys/" alt="レディース">
							<img src="/items/img/item_07.jpg" width="100%" alt="レディース">
<p class="item_txt_min">レディース</p>
</a>
					</div>
					<div class="navi_inner_2 btn">
						<a class="dropdown-item goods_btn" href="/items/category/workwear/" alt="ワークウェア">
							<img src="/items/img/item_13.jpg" width="100%" alt="ワークウェア">
<p class="item_txt_min">ワークウェア</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item goods_btn" href="/items/category/goods/" alt="記念品">
							<img src="/items/img/item_15.jpg" width="100%" alt="記念品">
<p class="item_txt_min">記念品</p>
</a>
					</div>

				</div>
			</section>

			<section class="hidden-md-up sp_item_wrap">
				<div class="sp_items">
					<div class="row">

						<div class="navi_inner_2 btn">
							<a class="dropdown-item t-shirts_btn" href="/items/category/t-shirts/" alt="Tシャツ">
								<img src="/items/img/item_01.jpg" width="100%" alt="Tシャツ">
							<p class="item_txt_min">Tシャツ</p>
						</a>
						</div>

						<div class="navi_inner_2 btn">
							<a class="dropdown-item polo-shirts_btn" href="/items/category/polo-shirts/" alt="ポロシャツ">
								<img src="/items/img/item_03.jpg" width="100%" alt="ポロシャツ">
							<p class="item_txt_min">ポロシャツ</p>
						</a>
						</div>

						<div class="navi_inner_2 btn">
							<a class="dropdown-item towel_btn" href="/items/category/towel/" alt="タオル">
								<img src="/items/img/item_08.jpg" width="100%" alt="タオル">
							<p class="item_txt_min">タオル</p>
						</a>
						</div>
					</div>

					<div class="row">
						<div class="navi_inner_2 btn">
							<a class="dropdown-item sweat_btn" href="/items/category/sweat/" alt="スウェット">
								<img src="/items/img/item_02.jpg" width="100%" alt="スウェット">
							<p class="item_txt_min">スウェット</p>
						</a>
						</div>

						<div class="navi_inner_2 btn">
							<a class="dropdown-item sportswear_btn" href="/items/category/sportswear/" alt="スポーツ">
								<img src="/items/img/item_04.jpg" width="100%" alt="スポーツ">
							<p class="item_txt_min">スポーツ</p>
						</a>
						</div>

						<div class="navi_inner_2 btn">
							<a class="dropdown-item long-shirts_btn" href="/items/category/long-shirts/" alt="長袖Tシャツ">
								<img src="/items/img/item_05.jpg" width="100%" alt="長袖Tシャツ">
							<p class="item_txt_min">長袖Tシャツ</p>
						</a>
						</div>
					</div>
				</div>
				<a class="btn_or btn waves-effect waves-light" href="/items/category.php" type="button">アイテム一覧へ</a>


			</section>

			<!--
			<section class="hidden-sm-up">

				<div class="owl-carousel owl-theme">

					<div class="navi_inner_2 btn">
						<a class="dropdown-item goods_btn" href="/items/category/workwear/">
<img src="/items/img/item_15.jpg" width="100%">
<p class="item_txt_min">ワークウェア</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item goods_btn" href="/items/category/goods/">
<img src="/items/img/item_15.jpg" width="100%">
<p class="item_txt_min">記念品</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item overall_btn" href="/items/category/overall/">
<img src="/items/img/item_12.jpg" width="100%">
<p class="item_txt_min">つなぎ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item ladys_btn" href="/items/category/ladys/">
<img src="/items/img/item_07.jpg" width="100%">
<p class="item_txt_min">レディース</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item long-shirts_btn" href="/items/category/long-shirts/">
<img src="/items/img/item_05.jpg" width="100%">
<p class="item_txt_min">長袖Tシャツ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item tote-bag_btn" href="/items/category/tote-bag/">
<img src="/items/img/item_09.jpg" width="100%">
<p class="item_txt_min">バッグ</p>
</a>
					</div>

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
						<a class="dropdown-item sweat_btn" href="/items/category/sweat/">
<img src="/items/img/item_02.jpg" width="100%">
<p class="item_txt_min">スウェット</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item towel_btn" href="/items/category/towel/">
<img src="/items/img/item_08.jpg" width="100%">
<p class="item_txt_min">タオル</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item sportswear_btn" href="/items/category/sportswear/">
<img src="/items/img/item_04.jpg" width="100%">
<p class="item_txt_min">スポーツ</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item outer_btn" href="/items/category/outer/">
<img src="/items/img/item_06.jpg" width="100%">
<p class="item_txt_min">ブルゾン</p>
</a>
					</div>

					<div class="navi_inner_2 btn">
						<a class="dropdown-item cap_btn" href="/items/category/cap/">
<img src="/items/img/item_14.jpg" width="100%">
<p class="item_txt_min">キャップ</p>
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



				</div>
			</section>
-->


			<section class="block-content">
				<div class="content-area left_area">
					<h2 class="rank_ttl">お届け日</h2>
					<div class="wrap_h2_under">
						<p class="h2_under">オリジナルTシャツ業界最速！4つのプランからご提案します。</p>
					</div>
					<div class="tab-content">
						<input type="radio" id="tab1" name="tab" checked>
						<label class="tab" for="tab1">通常3日<br>プラン</label>
						<input type="radio" id="tab2" name="tab">
						<label class="tab" for="tab2">2日<br>プラン</label>
						<input type="radio" id="tab3" name="tab">
						<label class="tab" for="tab3">翌日<br>プラン</label>
						<input type="radio" id="tab4" name="tab">
						<label class="tab" for="tab4">当日特急<br>プラン</label>





						<div class="tab-box">

							<div id="tabView1" class="plan">
								<p>今、注文すると...この日にお届け！</p>
								<div id="date">
									<p>
										<?php echo $fin[3]['Month'].'月'.$fin[3]['Day'].'日';?><span class="min_txt">(<?php echo $fin[3]['Weekname'];?>)</span>
									</p>
								</div>
								<p class="fs20">通常3日プランは特急料金なし</p>
								<a href="/delivery/" class="rgt_txt"><span>別の注文日で調べる</span></a>
								<div class="bdr_dot"></div>
								<p class="fs20"> 注文〆切り(13時)まであと&hellip;</p>
								<div class="DateCountdown" data-date="<?php echo $normalDeadline;?> 13:00:00" style="width: 100%;"></div>
								<p class="fs10">※時期や注文内容によっては上記のお届け日以上かかる場合がございます。</p>
							</div>


							<div id="tabView2" class="plan">
								<p>今、注文すると...この日にお届け！</p>
								<div id="date">
									<p>
										<?php echo $fin[2]['Month'].'月'.$fin[2]['Day'].'日';?><span class="min_txt">(<?php echo $fin[2]['Weekname'];?>)</span>
									</p>
								</div>
								<p class="fs20">2日プランは特急料金1.3倍</p>
								<a href="/delivery/" class="rgt_txt"><span>別の注文日で調べる</span></a>
								<div class="bdr_dot"></div>
								<p class="fs20"> 注文〆切り(13時)まであと&hellip;</p>
								<div class="DateCountdown" data-date="<?php echo $normalDeadline;?> 13:00:00" style="width: 100%;"></div>
								<p class="fs10">※時期や注文内容によっては上記のお届け日以上かかる場合がございます。</p>
							</div>


							<div id="tabView3" class="plan">
								<p>今、注文すると...この日にお届け！</p>
								<div id="date">
									<p>
										<?php echo $fin[1]['Month'].'月'.$fin[1]['Day'].'日';?><span class="min_txt">(<?php echo $fin[1]['Weekname'];?>)</span>
									</p>
								</div>
								<p class="fs20">翌日プランは特急料金1.5倍</p>
								<a href="/delivery/" class="rgt_txt"><span>別の注文日で調べる</span></a>
								<div class="bdr_dot"></div>
								<p class="fs20"> 注文〆切り(13時)まであと&hellip;</p>
								<div class="DateCountdown" data-date="<?php echo $normalDeadline;?> 13:00:00" style="width: 100%;"></div>
								<p class="fs10">※時期や注文内容によっては上記のお届け日以上かかる場合がございます。</p>
							</div>


							<div id="tabView4" class="plan">
								<p>今、注文すると...この日にお届け！</p>
								<div id="date">
									<p>
										<?php echo $fin[0]['Month'].'月'.$fin[0]['Day'].'日';?><span class="min_txt">(<?php echo $fin[0]['Weekname'];?>)</span>
									</p>
								</div>
								<p class="fs20">当日特急プランは特急料金2倍</p>
								<a href="/delivery/" class="rgt_txt"><span>別の注文日で調べる</span></a>
								<div class="bdr_dot"></div>
								<p class="fs20"> 注文〆切り(12時)まであと&hellip;</p>
								<div class="DateCountdown" data-date="<?php echo $specialDeadline;?> 12:00:00" style="width: 100%;"></div>
								<p class="fs10">※時期や注文内容によっては上記のお届け日以上かかる場合がございます。</p>
							</div>

						</div>
					</div>
					<div class="cnt_txt">
						<p class="clear">業界最速！今日、発送できます！</p>
						<a href="/order/express/" class="btn_or btn waves-effect waves-light" type="button">
<span>当日特急プランへ</span>
</a>
					</div>
				</div>
				<div class="content-area right_area">
					<h2 class="rank_ttl">10秒見積もり</h2>
					<div class="wrap_h2_under">
						<p class="h2_under">オリジナルTシャツの概算の金額がすぐに計算できます！</p>
					</div>
					<div class="est_con">

						<div class="est_step_01">
							<div class="est_left">
								<div class="est_ttl"> 1.&nbsp;アイテム</div>
							</div>

							<div class="est_item" id="items">
								<div class="est_item_01 btn waves-effect waves-light is-selected" data-category="t-shirts" data-itemid="215" data-color="001">
									<div class="est_item_img">
										<img src="/common/img/home/main/top_est_item_01.png" width="100%" alt="Tシャツ">
									</div>
									<p class="est_item_name">Tシャツ</p>
								</div>

								<div class="est_item_02 btn waves-effect waves-light" data-category="polo-shirts" data-itemid="625" data-color="001">
									<div class="est_item_img">
										<img src="/common/img/home/main/top_est_item_02.png" width="100%" alt="ポロシャツ">
									</div>
									<p class="est_item_name">ポロシャツ</p>
								</div>

								<div class="est_item_03 btn waves-effect waves-light" data-category="sweat" data-itemid="124" data-color="001">
									<div class="est_item_img">
										<img src="/common/img/home/main/top_est_item_03.png" width="100%" alt="スウェット">
									</div>
									<p class="est_item_name">スウェット</p>
								</div>

								<div class="est_item_04 btn waves-effect waves-light" data-category="towel" data-itemid="363" data-color="001">
									<div class="est_item_img">
										<img src="/common/img/home/main/top_est_item_04.png" width="100%" alt="タオル">
									</div>
									<p class="est_item_name">タオル</p>
								</div>


							</div>
						</div>

						<div class="est_step_02">
							<div class="est_left">
								<div class="est_ttl"> 2.&nbsp;プリント色数</div>
							</div>
							<div class="est_item" id="inks">
								<div class="est_item_01 btn waves-effect waves-light is-selected" data-ink="1">
									<div class="est_item_img">
										<img src="/common/img/home/main/top_est_color_01.png" width="100%" alt="Tシャツプリント1色">
									</div>
									<p class="est_item_name">1色</p>
								</div>

								<div class="est_item_02 btn waves-effect waves-light" data-ink="2">
									<div class="est_item_img">
										<img src="/common/img/home/main/top_est_color_02.png" width="100%" alt="Tシャツプリント2色">
									</div>
									<p class="est_item_name">2色</p>
								</div>

								<div class="est_item_03 btn waves-effect waves-light" data-ink="3">
									<div class="est_item_img">
										<img src="/common/img/home/main/top_est_color_03.png" width="100%" alt="Tシャツプリント3色">
									</div>
									<p class="est_item_name">3色</p>
								</div>

								<div class="est_item_04 btn waves-effect waves-light" data-ink="4">
									<div class="est_item_img">
										<img src="/common/img/home/main/top_est_color_04.png" width="100%" alt="Tシャツプリントフルカラー">
									</div>
									<p class="est_item_name">フルカラー</p>
								</div>
							</div>
						</div>

						<div class="est_step_03">
							<div class="est_left_02">
								<div class="est_ttl_02">
									3.&nbsp;枚数<span class="est_dis_02 hidden-sm-up">150枚以上で大幅値引き！</span>
								</div>
							</div>
							<div class="est_right">
								<p class="est_count">
									<input id="order_amount" type="number" name="number" value="30" min="0" step="1" required autocomplete="off">
									<span class="est_mai"> 枚</span></p>
								<p class="est_dis hidden-xs-down">150枚以上で大幅値引き！</p>
							</div>
						</div>
						<div class="est_step_04">
							<p class="est_price_txt">最安値だと...</p>
							<div class="est_price">
								<div class="per_tshirt">
									<div class="price_left">
										1枚あたり:
									</div>
									<div class="price_right"><span class="est_orange_txt" id="per">0</span>円</div>
								</div>


								<div class="total_price">
									<div class="price_left_02">
										合計:
									</div>
									<div class="price_right_02"><span class="est_orange_txt" id="sum">0</span>円</div>
								</div>
							</div>
						</div>
					</div>

					<div class="cnt_txt">
						<p class="clear">全アイテムを比較した見積もりはこちら！</p>
						<a href="/price/estimate.php" class="btn_or btn waves-effect waves-light" type="button">
<span>カンタン比較見積もりへ</span>
</a>
					</div>
				</div>
			</section>







			<!--            ここから新　仁神-->
			<div class="ranking_block_bg">
				<section class="itemcategory_01">

					<h2 class="rank_ttl itemcategory_ttl">アイテムランキング</h2>
					<div class="wrap_h2_under">
						<p class="h2_under">今、人気のアイテムをカテゴリーごとにご紹介します。オリジナルTシャツを作成するのが初めてでどのアイテムにしたら良いか迷っている方は是非このランキングを参考にしてください。 多くの方が選んでいる間違いないTシャツ、ポロシャツ、スウェット、タオル、ブルゾンの1位~8位の人気ランキングです！
						</p>
					</div>

					<div class="tabs">

						<input id="tab01" type="radio" name="tab_item" checked>
						<label class="tab_item" for="tab01">Tシャツ</label>
						<input id="tab02" type="radio" name="tab_item">
						<label class="tab_item" for="tab02">ポロシャツ</label>
						<input id="tab03" type="radio" name="tab_item">
						<label class="tab_item" for="tab03">スウェット</label>
						<input id="tab04" type="radio" name="tab_item">
						<label class="tab_item" for="tab04">タオル</label>
						<input id="tab05" type="radio" name="tab_item">
						<label class="tab_item" for="tab05">ブルゾン</label>


						<?php
						foreach ($itemRanking as $categoryKey => $val) {
							$html .= '<div class="tab_content" id="'.$categoryKey.'">';
							$html .= '<div class="ranking_four">';
							for ($i = 0; $i < $contentsLength; $i++) {
								$html .= '<div class="block_ranking">
                                    <p class="rank_number_01"><span class="icon-uniF100"></span>'.($i+1).'位</p>
                                    <div class="ranking_body">
										<p class="catch">'.$val[$i]['i_caption'].'</p>
                                        <div class="border_tab_b"></div>
                                        <div class="ranking_con">
                                        <div class="ranking_con_01">
											<a href="/items/item.php?code='.$val[$i]['item_code'].'">
												<div class="logo_ons">
													<div class="logo"><img src="/img/brand/logo_'.$val[$i]['brand_id'].'.png" width="100%"></div>';
								if (intval($val[$i]['oz'], 10) > 0) {
									$html .= '<div class="ons">'.$val[$i]['oz'].'oz</div>';
								}
								$html .= '</div>
												<div class="ranking_item_img"><img src="'._IMG_PSS.'items/list/'.$categoryKey.'/'.$val[$i]['item_code'].'/'.$val[$i]['item_code'].'_'.$val[$i]['i_color_code'].'.jpg" width="100%" alt="品番：'.$val[$i]['item_code'].'"></div>
												<p class="number">'.strtoupper($val[$i]['item_code']).'</p>
												<div class="name_wrap">
													<p class="name">'.$val[$i]['item_name'].'</p>
												</div>
												<div class="spec">
													<div class="spec_l">全'.$val[$i]['colors'].'色</div>
													<div class="spec_r">サイズ：'.$val[$i]['sizename_from'].'&sim;'.$val[$i]['sizename_to'].'</div>
												</div>
												<p class="price"><span class="price_number">'.number_format($val[$i]['cost']).'</span><span class="price_yen">円&sim;</span></p>
											</a>
                                            </div>
                                            <div class="border_dotted_01"></div>
                                            <a href="/itemreviews/?item='.$val[$i]['item_id'].'">
                                                <div class="review_star">
                                                    <div class="review_star_img">
														<img src="/common/img/home/review/sp_review_0'.getStar($val[$i]['avg_votes']).'.png" width="100%">
                                                    </div>
													<div class="review_star_number">'.round($val[$i]['avg_votes'], 1).'</div>
                                                </div>
												<p class="i_review_link">レビューを見る（'.number_format($val[$i]['reviews']).'）</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>';
							}
							$html .= '<div class="seo">'.$val['desc'].'</div>';
							$html .= '</div></div>';
						}
						echo $html;
						?>
					</div>

				</section>
			</div>

			<!--		プリント方法安永-->
			<div class="top-printhow">
				<div class="inner">
					<h2 class="rank_ttl bland_ttl">プリント方法</h2>
					<p class="h2_under">
						6種もの加工方法からお選び頂けるのでどんなデザインでもプリントできます。そして、全てのプリント方法でオリジナルプリント業界最速の納期を実現しています。プリント方法はデザインやTシャツなどの素材によって出来る出来ないものがありますので受注スタッフから最適なものをご提案させて頂きます。
					</p>
					<div class="printhow-grid">

						<ul class="grid-list">
							<li>

								<p class="card-img"><a href="/print/silk-screen.php"><img class="print-hover" src="/common/img/home/print/top-silk.jpg" alt="3色のシルクスクリーンでプリントされたバイクに乗った男三人のオリジナルデザイン" width="100%"></a></p>
								<div class="grid-content">
									<h3 class="grid-title">"オリジナルプリントの大定番"</h3>

									<p class="printhow-text">版を作り直接インクを刷る方法で耐久性に優れています。<br>
										製作枚数が多いと大幅に安くなります。
									</p>

								</div>
							</li>
							<li>
								<p class="card-img"><a href="/print/inkjet.php"><img class="print-hover" src="/common/img/home/print/top-ink.jpg" alt="淡色のフルカラーインクジェットでプリントされた、数々の著名人がグラフィカルに配置されたオリジナルデザイン" width="100%"></a></p>
								<div class="grid-content">
									<h3 class="grid-title">"どんなデザインでも表現可能"</h3>

									<p class="text">色の再現が1番優れており、色味を大切にしているデザインに向いています。<br>
										1枚からでも低価格で製作できます。</p>

								</div>
							</li>
							<li>
								<p class="card-img"><a href="/print/dejiten.php"><img class="print-hover" src="/common/img/home/print/top-deji.jpg" alt="デジタル転写でプリントされた細かくてカラフルなオリジナルデザインの拡大図" width="100%"></a></p>
								<div class="grid-content">
									<h3 class="grid-title">"色の再現性の高いフルカラー表現"</h3>

									<p class="text">色の再現が1番優れており、色味を大切にしているデザインに向いています。<br>
										製作枚数が多いと大幅に安くなります。</p>

								</div>
							</li>
							<li>
								<p class="card-img"><a href="/print/cutting-sheet.php"><img class="print-hover" src="/common/img/home/print/top-cut.jpg" alt="黒地にカッティングプリントされた42というオリジナルデザイン" width="100%"></a></p>
								<div class="grid-content">
									<h3 class="grid-title">"1枚1枚違う名前や背番号が可能"</h3>

									<p class="text">シートを専用機械でデザイン通りにカットする方法のため仕上がりがとても綺麗な方法です。<br>
										版を使用しないため、1枚からでも低価格で製作できます。</p>

								</div>
							</li>
							<li>
								<p class="card-img"><a href="/print/embroidery.php"><img class="print-hover" src="/common/img/home/print/top-emb.jpg" alt="刺繍機と、Tシャツに刺繍された三味線を持った女性のカラフルなオリジナルデザイン" width="100%"></a></p>
								<div class="grid-content">
									<h3 class="grid-title">"MAX6色の圧倒的な表現力"</h3>

									<p class="text">プリントとは違った高級感を表現できます。<br>
										製作枚数が多いと大幅に安くなります。</p>

								</div>
							</li>
							<li>
								<!--
<p class="card-img"><img src="takahama428/img/print/print-silk.jpg" alt=""></p>
<div class="grid-content">
<h3 class="grid-title">ワッペン</h3>
<p class="text">プリントとは違った高級感を表現<br>MAX6色の表と生地の種類が豊富で鮮やかな仕上がりが可能です。<br>製作枚数が多いと大幅に安くなります。</p>
<p class="btnn"><a href="path.php">詳細はこちら</a></p>
</div>
-->
							</li>
						</ul>

					</div>



				</div>


			</div>
			<!--プリント方法終わり-->
			<div class="review_block_bg">
				<section class="review">
					<h2 class="rank_ttl review_block_ttl">お客様レビュー</h2>
					<div class="wrap_h2_under">
						<p class="h2_under">1000件を超えるお客様の声をご紹介します。タカハマライフアートのオリジナルTシャツを作成して頂いた多くの方が受注スタッフの対応が良かったと評価頂いています。 なんと、94%の方が最高評価の5の評価でした。もちろん、商品の仕上がりやサイトの使いやすさも高評価頂いています！</p>
					</div>

					<p class="kindly">タカハマライフアートは<br class="hidden-sm-up"><span class="orange_txt">親切対応</span><br class="hidden-sm-up">で選ばれています！</p>
					<p class="com_ev">総合評価(1,045件)</p>
					<div class="com_ev_star">
						<div class="com_ev_star_img">
							<img src="/common/img/home/review/sp_review_040.png" width="100%" alt="総合評価4.3点">
						</div>
						<div class="com_ev_star_number"> 4.3</div>
					</div>
					<div class="r_four_block">
						<div class="r_two_block">
							<div class="r_star_block col-sm">
								<div class="r_des_03">
									商品の仕上がり
								</div>
								<div class="r_star_img">
									<img src="/common/img/home/review/sp_review_040.png" width="100%" alt="商品の仕上がり4.2点">
								</div>
								<div class="r_star_number">
									4.2
								</div>
							</div>

							<div class="r_star_block col-sm">
								<div class="r_des_02">
									梱包について
								</div>
								<div class="r_star_img">
									<img src="/common/img/home/review/sp_review_040.png" width="100%" alt="梱包について4.1点">
								</div>
								<div class="r_star_number">
									4.1
								</div>
							</div>
						</div>
						<div class="r_two_block">
							<div class="r_star_block col-sm">
								<div class="r_des_03">
									スタッフの対応
								</div>
								<div class="r_star_img">
									<img src="/common/img/home/review/sp_review_045.png" width="100%" alt="スタッフの対応4.7点">
								</div>
								<div class="r_star_number">
									4.7
								</div>
							</div>

							<div class="r_star_block col-sm">
								<div class="r_des">
									サイトの使いやすさ
								</div>
								<div class="r_star_img">
									<img src="/common/img/home/review/sp_review_040.png" width="100%" alt="サイトの使いやすさ4.2点">
								</div>
								<div class="r_star_number">
									4.2
								</div>
							</div>

						</div>
					</div>



					<!--Carousel Wrapper pc-->
					<div id="multi-item-example_01" class="hidden-sm-down carousel slide carousel-multi-item slide_wrap" data-ride="carousel">

						<!--Slides-->
<!--						<a class="btn-floating_review" href="#multi-item-example_01" data-slide="prev"><i class="fa fa-chevron-left review_arrow"></i></a>-->
						<div class="carousel-inner" role="listbox">

							<!--First slide-->
							<div class="carousel-item active">

								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.7点">4.7</span></li>
											<li>
												<p>昨年秋に一度お世話になりました。<br>・低価格でいい商品を提供してくれている<br>・応対がよい…

												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>

											<li>
												<p>いつも、丁寧で対応の早さが素晴らしいと思います！<br>私のようにパソコンを使ってのデザイン画を送れない人にとっては、本当に助かります！…
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.7点">4.7</span></li>
											<li>
												<p>最初から最後まで、一人の担当者が相談に乗ってくれるので発注までスムーズでした。<br>また宜しくお願い致します。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.2点">4.2</span></li>
											<li>
												<p>各商品名の印刷色を決める際に、全ての商品に対して、印刷色を確認できるページが欲しいです。<br>Tシャツだとそんなページがありましたが、ハンドタオル…

												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.0点">4.0</span></li>
											<li>
												<p>ネットでこれだけ注文に応じられるのは驚きました。<br>プリントもイメージ通りで大変満足しました。
												</p>
											</li>
										</ul>
									</div>
								</div>

							</div>
							<!--/.First slide-->

							<!--Second slide-->
							<div class="carousel-item">

								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>
											<li>
												<p>安く、早く届き非常に助かりました。<br>ありがとうございました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price" alt="評価5.0点">5.0</span></li>
											<li>
												<p>わがままばかりで色々とお手数おかけいたしましたが、優しく素早く対応して下さり感謝しております。<br>ありがとうございました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.3点">4.2</span></li>
											<li>
												<p>今回は追加注文だったのですが、電話での対応が良かった。<br>金額の事や、デザインの微調整についてわかりやすかった。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price" alt="評価3.7点">3.7</span></li>
											<li>
												<p>注文から発送まで本当にスムーズで時間がなかった私たちには嬉しい限りでした。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price" alt="評価5.0点">5.0</span></li>
											<li>
												<p>窓口のスタッフの対応が大変よく、ありがとうございました。<br>追加なども夏頃出るかと思いますが、また宜しくお願い致します。
												</p>
											</li>
										</ul>
									</div>
								</div>

							</div>
							<!--/.Second slide-->

							<!--Third slide-->
							<div class="carousel-item">

								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.2点">4.2</span></li>
											<li>
												<p>欲しかった通りの物が届いて嬉しかったです。<br>また何かオリジナルTシャツを作りたいときはお願いしたいと思います。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.0点">4.0</span></li>
											<li>
												<p>・白インクの部分が、少しだけ明度が下がったように感じたましたが、イメージ通りに仕上がり良かったです。<br>・注文の最終確認の段階で、…

												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.7点">4.7</span></li>
											<li>
												<p>電話で確認した時に対応してくださった方が、とても感じがよかったです。<br>気持ちよくお話ができました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>
											<li>
												<p>この度は急ぎの注文に親切な、迅速な対応ありがとうございました。<br>お陰様でイベントに間に合わせることができ、楽しい時間…

												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price" alt="評価3.7点">3.7</span></li>
											<li>
												<p>どのプリントがどの程度費用がかかるというのが分からず、予想以上に低価格で仕上がりました。<br>今回は時間がなかったので、再度変更はできませんでしたが…
												</p>
											</li>
										</ul>
									</div>
								</div>

							</div>
							<!--/.forth slide-->

							<!--Third slide-->
							<div class="carousel-item">

								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.0点">4.0</span></li>
											<li>
												<p>Ｔシャツ作りが初めてだったので、とても不安でしたが、わかりやすく説明していただきました。<br>納期が早くて、とても助かりました。ありがとうございました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.2点">4.2</span></li>
											<li>
												<p>電話対応も非常に丁寧でしたし、大変満足のいく仕上がりでした。<br>また利用させていただきたいと思います。<br>ありがとうございました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.7点">4.7</span></li>
											<li>
												<p>発注～入稿～領収証依頼、と、数回お電話でやりとりさせていただきましたが、どの方もとても気持ちよく対応していただきました。<br>ありがとうございます。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>
											<li>
												<p>メールや電話での対応がとても丁寧で好感がもてました。<br>必ずしも同じ方がご対応ではないかと思いますが、一定のレベルが堅持されていて、安心感…

												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-md-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>
											<li>
												<p>急な注文にもかかわらずご丁寧に対応していただけて嬉しかったです。<br>ありがとうございました！
												</p>
											</li>
										</ul>
									</div>
								</div>

							</div>
							<!--/.forth slide-->

						</div>
						<!--/.Slides-->
<!--						<a class="btn-floating_review" href="#multi-item-example_01" data-slide="next"><i class="fa fa-chevron-right review_arrow"></i></a>-->
					</div>
					<!--/.Carousel Wrapper pc-->




					<!--Carousel Wrapper sp-->
					<div id="multi-item-example" class="hidden-md-up carousel slide carousel-multi-item slide_wrap" data-ride="carousel">

						<!--Slides-->
<!--						<a class="btn-floating_review" href="#multi-item-example" data-slide="prev"><i class="fa fa-chevron-left review_arrow"></i></a>-->
						<div class="carousel-inner" role="listbox">

							<!---->
							<div class="carousel-item active">

								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz" alt="評価4.7点"><span class="rank_price">4.7</span></li>
											<li>
												<p>昨年秋に一度お世話になりました。<br>・低価格でいい商品を提供してくれている<br>・応対がよい…

												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>

											<li>
												<p>いつも、丁寧で対応の早さが素晴らしいと思います！<br>私のようにパソコンを使ってのデザイン画を送れない人にとっては、本当に助かります！…
												</p>
											</li>
										</ul>
									</div>
								</div>

							</div>

							<!---->

							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.7点">4.7</span></li>
											<li>
												<p>最初から最後まで、一人の担当者が相談に乗ってくれるので発注までスムーズでした。<br>また宜しくお願い致します。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.2点">4.2</span></li>
											<li>
												<p>各商品名の印刷色を決める際に、全ての商品に対して、印刷色を確認できるページが欲しいです。<br>Tシャツだとそんなページがありましたが、ハンドタオル…

												</p>
											</li>
										</ul>
									</div>
								</div>

							</div>
							<!---->


							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.0点">4.0</span></li>
											<li>
												<p>ネットでこれだけ注文に応じられるのは驚きました。<br>プリントもイメージ通りで大変満足しました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>
											<li>
												<p>安く、早く届き非常に助かりました。<br>ありがとうございました。
												</p>
											</li>
										</ul>
									</div>
								</div>
							</div>

							<!---->

							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price" alt="評価5.0点">5.0</span></li>
											<li>
												<p>わがままばかりで色々とお手数おかけいたしましたが、優しく素早く対応して下さり感謝しております。<br>ありがとうございました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.2点">4.2</span></li>
											<li>
												<p>今回は追加注文だったのですが、電話での対応が良かった。<br>金額の事や、デザインの微調整についてわかりやすかった。
												</p>
											</li>
										</ul>
									</div>
								</div>
							</div>

							<!---->

							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price" alt="評価3.7点">3.7</span></li>
											<li>
												<p>注文から発送まで本当にスムーズで時間がなかった私たちには嬉しい限りでした。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price" alt="評価5.0点">5.0</span></li>
											<li>
												<p>窓口のスタッフの対応が大変よく、ありがとうございました。<br>追加なども夏頃出るかと思いますが、また宜しくお願い致します。
												</p>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<!---->

							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.2点">4.2</span></li>
											<li>
												<p>欲しかった通りの物が届いて嬉しかったです。<br>また何かオリジナルTシャツを作りたいときはお願いしたいと思います。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.0点">4.0</span></li>
											<li>
												<p>・白インクの部分が、少しだけ明度が下がったように感じたましたが、イメージ通りに仕上がり良かったです。<br>・注文の最終確認の段階で、…

												</p>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<!---->

							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.7点">4.7</span></li>
											<li>
												<p>電話で確認した時に対応してくださった方が、とても感じがよかったです。<br>気持ちよくお話ができました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>
											<li>
												<p>この度は急ぎの注文に親切な、迅速な対応ありがとうございました。<br>お陰様でイベントに間に合わせることができ、楽しい時間…

												</p>
											</li>
										</ul>
									</div>
								</div>
							</div>

							<!---->

							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price" alt="評価3.7点">3.7</span></li>
											<li>
												<p>どのプリントがどの程度費用がかかるというのが分からず、予想以上に低価格で仕上がりました。<br>今回は時間がなかったので、再度変更はできませんでしたが…
												</p>
											</li>
										</ul>
									</div>
								</div>


								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.0点">4.0</span></li>
											<li>
												<p>Ｔシャツ作りが初めてだったので、とても不安でしたが、わかりやすく説明していただきました。<br>納期が早くて、とても助かりました。ありがとうございました。
												</p>
											</li>
										</ul>
									</div>
								</div>
							</div>

							<!---->

							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.2点">4.2</span></li>
											<li>
												<p>電話対応も非常に丁寧でしたし、大変満足のいく仕上がりでした。<br>また利用させていただきたいと思います。<br>ありがとうございました。
												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.7点">4.7</span></li>
											<li>
												<p>発注～入稿～領収証依頼、と、数回お電話でやりとりさせていただきましたが、どの方もとても気持ちよく対応していただきました。<br>ありがとうございます。
												</p>
											</li>
										</ul>
									</div>
								</div>
							</div>

							<!---->

							<div class="carousel-item">
								<div class="col review_comment">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>
											<li>
												<p>メールや電話での対応がとても丁寧で好感がもてました。<br>必ずしも同じ方がご対応ではないかと思いますが、一定のレベルが堅持されていて、安心感…

												</p>
											</li>
										</ul>
									</div>
								</div>

								<div class="col review_comment clearfix d-none d-sm-block">
									<div>
										<ul>
											<li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price" alt="評価4.5点">4.5</span></li>
											<li>
												<p>急な注文にもかかわらずご丁寧に対応していただけて嬉しかったです。<br>ありがとうございました！
												</p>
											</li>
										</ul>
									</div>
								</div>
							</div>

							<!---->
						</div>

						<!--/.Slides-->
<!--						<a class="btn-floating_review" href="#multi-item-example" data-slide="next"><i class="fa fa-chevron-right review_arrow"></i></a>-->
					</div>
					<!--/.Carousel Wrapper-->


					<div class="button_01">
						<a class="btn_or btn waves-effect waves-light" href="/userreviews/" type="button">レビューをもっと見る</a>
					</div>


				</section>

			</div>

			<section class="print_result">
				<h2 class="rank_ttl"> 製作実例</h2>
				<div class="wrap_h2_under">
					<p class="h2_under">お客様のご利用シーンと製作物を写真とコメントでご紹介します。オリジナルTシャツは様々なシーンで活躍しています。最近ではドライタイプのTシャツやポロシャツがスポーウェアやユニフォームに多く使われています。デザインも企業ロゴから手描デザインなど幅広く見ていて参考になります。Tシャツとインクの配色もご参考に！</p>
				</div>

				<div class="slider_p-result">
					<div class="p-result">
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2018/02/12/オリジナルスタッフtシャツでイベントも大盛況！/">
								<div class="result_image"><img src="/img/ex_01.jpg" width="100%" alt="イベント先でオリジナルTシャツを着る男性たち"></div>
							</a>
							<p class="result_txt"> Tシャツありがとうございました♪ <br>急な依頼にもかかわらずいい感じに仕上げていただきました(^-^)/<br>おかげでイベントも盛り上がりました!!
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/12/05/親身な対応に安心してお揃いtシャツ作れました♪/">
								<div class="result_image"><img src="/img/ex_02.jpg" width="100%" alt="緑のオリジナルTシャツを着る女性たち"></div>
							</a>
							<p class="result_txt"> 昨年タカハマさんで作ったという知人の紹介で、こちらを知りました。<br>他社さんも調べたのですが、タカハマさんのご対応が一番親切・丁寧だったので決めました！…
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/12/01/オリジナルデザインのクラスtもスピーディ！/">
								<div class="result_image"><img src="/img/ex_03.jpg" width="100%" alt="オリジナルTシャツを着る後ろ姿の女子学生達"></div>
							</a>
							<p class="result_txt"> 先日は、クラスTオリジナルデザイン作成～仕上がりまで丁寧な対応有難うございました。<br>スピーディな対応が神的でした^_^
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/05/08/t_218/">
								<div class="result_image"><img src="/img/ex_04.jpg" width="100%" alt="オリジナルTシャツをお祭りの衣装と着る女性達" の後ろ姿></div>
							</a>
							<p class="result_txt"> 先日は、お忙しい中対応して頂き、誠にありがとうございました。<br>Ｔシャツの出来上がりに子どもたちも 大満足で、無事にイベントを過ごす事ができ、いい思い出作りができ…
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/11/10/着心地もよく、仕上がりも大変満足なオリジナルt/">
								<div class="result_image"><img src="/img/ex_05.jpg" width="100%" alt="青いオリジナルTシャツを着る会社員達"></div>
							</a>
							<p class="result_txt"> 初めて利用させていただきましたが、早い仕上がりと、完成度の良さに大変満足しております。<br>またぜひ、お願いいたします。
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/11/08/大人数イベントの参加者用オリジナルtシャツ！/">
								<div class="result_image"><img src="/img/ex_06.jpg" width="100%" alt="赤いオリジナルTシャツを着る女性2人"></div>
							</a>
							<p class="result_txt">いつもTシャツの制作、ありがとうございます。<br>今回で何度目でしょう？？6回目くらいですかね？<br>毎回200人近い参加者用にTシャツを作っていただいております。…
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/11/06/社内イベントにオリジナルのマフラータオル！/">
								<div class="result_image"><img src="/img/ex_07.jpg" width="100%" alt="イラストが印刷された青いオリジナルタオル数枚"></div>
							</a>
							<p class="result_txt"> この度は大変お世話になりました。<br>無事、社内イベントにて製作頂きましたタオルを使用することができました！<br>デザイン・質感とも大変喜んでおりました。
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/11/08/ディズニーランドにお揃いtシャツで最高の思い出/">
								<div class="result_image"><img src="/img/ex_08.jpg" width="100%" alt="ディズニーランドでピンク色のオリジナルTシャツを着る女性達"></div>
							</a>
							<p class="result_txt">みんなでTシャツを着てディズニーランドに！<br>とっても目立てて最高の思い出になりました??<br>先日は急な注文にもかかわらず、丁寧なご対応、また素敵なTシャツ本当…
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/11/08/ホームステイ記念＆お土産に漢字名前プリントシ/">
								<div class="result_image"><img src="/img/ex_09.jpg" width="100%" alt="空港でオリジナルTシャツを着る留学生と日本人のホストファミリー"></div>
							</a>
							<p class="result_txt">今回ホームステイでサンディエゴから16歳の男の子が来ました。<br>その記念にお揃いのＴシャツを作りました。<br>デザインは、彼が日本の高校に行った時に 書道で書いた彼の名前…
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/12/05/想像以上のきれいなデザインtシャツに満足♪/">
								<div class="result_image"><img src="/img/ex_10.jpg" width="100%" alt="ギターのデザインのオリジナルTシャツを着る男性"></div>
							</a>
							<p class="result_txt"> きれいにデザインして頂いて、想像以上に素晴らしいTシャツになりました。<br>良い記念になりました。ありがとうございました！
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/11/08/オーケストラのメンバーtシャツで練習も頑張れる/">
								<div class="result_image"><img src="/img/ex_11.jpg" width="100%" alt="赤いオリジナルTシャツを着ながら演奏する男女"></div>
							</a>
							<p class="result_txt"> 素敵なTシャツを作って頂きありがとうございました！<br>オーケストラの皆さんも気に入ってくれて演奏会に向けてよりいっそう頑張れます！
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/11/06/サプライズプレゼントのオリジナルタオルで感涙/">
								<div class="result_image"><img src="/img/ex_12.jpg" width="100%" alt="黄色と黒のオリジナルタオルを囲む女子高生達"></div>
							</a>
							<p class="result_txt"> サークルのみんなにサプライズでタオルをプレゼントすることができました！<br>泣いて喜んでくれたのでほんとに嬉しかったです。<br>最高のチームで最高のバスケしてき…</p>
						</div>
						<div class="result_block">
							<a href="app/WP/thanks-blog/2017/11/02/__trashed-4/">
								<div class="result_image"><img src="/img/ex_13.jpg" width="100%" alt="青いオリジナルTシャツを着る女性達"></div>
							</a>
							<p class="result_txt"> この度は、ステキなTシャツを作っていただいて本当にありがとうございました！<br>みんなで同じTシャツを着ることで、団結力が強まりイベントを盛り上げ…
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/05/08/t_217/">
								<div class="result_image"><img src="/img/ex_14.jpg" width="100%" alt="マラソン用の赤いオリジナルTシャツ"></div>
							</a>
							<p class="result_txt"> 今回マラソン大会に出るためにオリジナルＴシャツをタカハマライフアートさんで作成していただきました。<br>皆で一致団結して頑張ります！<br>タカハマライフアートさん、ありが…
							</p>
						</div>
						<div class="result_block">
							<a href="/app/WP/thanks-blog/2017/05/08/t_215/">
								<div class="result_image"><img src="/img/ex_15.jpg" width="100%" alt="イラストが印刷されている青いオリジナルTシャツ"></div>
							</a>
							<p class="result_txt"> イベント開催にともない、スタッフ用のTシャツを作成しました。<br>希望の納期が短かったので、Takahama Life Art様にお願いしました。<br>キレイで目立つ希望通りの鮮やかな…
							</p>
						</div>
					</div>
				</div>

				<div class="button_01">
					<a class="btn_or btn waves-effect waves-light" href="/app/WP/thanks-blog" type="button">製作実例をもっと見る</a>
				</div>

			</section>



			<div class="tla_bland_bg">

				<section class="tla_bland">
					<h2 class="rank_ttl bland_ttl">取扱ブランド</h2>
					<div class="wrap_h2_under">
						<p class="h2_under">オリジナルTシャツを中心としたブランド豊富に取り揃えています。日本のブランドから海外のブランドまで幅広く扱うことで様々なジャンルのお客様のご要望に対応しています。<br> アパレルで使用されるTシャツから企業の販促品に使用されるタオルやトートバッグや学生に流行に合わせたクラスTシャツなど400種類以上のアイテムを展開中です！
						</p>
					</div>


					<div class="cont">
						<!-- flexboxのコンテナ -->
						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=58">
								<div class="bland_a">
									<img src="img/bland_01.png" width="100%" alt="Printstarのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									Printstar<br>プリントスター
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=59">
								<div class="bland_a">
									<img src="img/bland_02.png" width="100%" alt="United Athleのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									United Athle<br>ユナイテッドアスレ
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=108">
								<div class="bland_a">
									<img src="img/bland_03.png" width="100%" alt="GILDANのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									GILDAN<br>ギルダン
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=60">
								<div class="bland_a">
									<img src="img/bland_04.png" width="100%" alt="Glimmerのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									glimmer<br>グリマー
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=54">
								<div class="bland_d">
									<img src="img/bland_05.png" width="100%" alt="TRUSSのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									TRUSS<br>トラス
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=55">
								<div class="bland_b">
									<img src="img/bland_06.png" width="100%" alt="CROSS&STITCHのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									CROSS&nbsp;&amp;&nbsp;STITCH<br>クロスアンドステッチ
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=62">
								<div class="bland_a">
									<img src="img/bland_07.png" width="100%" alt="CROSSのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									CROSS<br>クロス
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=61">
								<div class="bland_c">
									<img src="img/bland_08.png" width="100%" alt="wundouのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									wundou<br>ウンドウ
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=67">
								<div class="bland_a">
									<img src="img/bland_09.png" width="100%" alt="ruccaのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									rucca<br>ルッカ
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=66">
								<div class="bland_a">
									<img src="img/bland_10.png" width="100%" alt="AIMYのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									AIMY<br>エイミー
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=68">
								<div class="bland_d">
									<img src="img/bland_11.png" width="100%" alt="DALUCのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									DALUC<br>ダルク
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=69">
								<div class="bland_d">
									<img src="img/bland_12.png" width="100%" alt="TouchandGoのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									Touch&nbsp;AND&nbsp;Go<br>タッチアンドゴー
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=56">
								<div class="bland_a">
									<img src="img/bland_13.png" width="100%" alt="BEES&BEAMのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									BEES&nbsp;BEAM<br>ビーズビーム
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=124">
								<div class="bland_b">
									<img src="img/brand/logo_272.png" width="100%" alt="Fruit of the Loom のロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									FRUIT&nbsp;OF&nbsp;THE&nbsp;LOOM<br>フルーツオブザルーム
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=65">
								<div class="bland_d">
									<img src="img/bland_14.png" width="100%" alt="SOWAのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									SOWA<br>ソウワ
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=43">
								<div class="bland_a">
									<img src="img/bland_15.png" width="100%" alt="Championのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									Champion<br>チャンピオン
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=125">
								<div class="bland_a">
									<img src="img/brand/logo_273.png" width="100%" alt="LIFEMAXのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									LIFEMAX<br>ライフマックス
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=126">
								<div class="bland_b">
									<img src="img/brand/logo_274.png" width="100%" alt="NEWHATTANのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									NEWHATTAN<br>ニューハッタン
								</p>
							</a>
						</div>

						<!--
<div class="item btn waves-effect waves-light">
<a href="/items/?tag=277">
<div class="bland_d">
<img src="img/brand/logo_277.png" width="100%">
</div>
<div class="sen_dotted"></div>
<p>
American&nbsp;Apparel<br>アメリカンアパレル
</p>
</a>
</div>
-->

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=109">
								<div class="bland_d">
									<img src="img/bland_16.png" width="100%" alt="ANVILのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									ANVIL<br>アンビル
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=110">
								<div class="bland_a">
									<img src="img/bland_17.png" width="100%" alt="COMFORTCOLORSのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									COMFORT&nbsp;COLORS<br>コンフォートカラーズ
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=127">
								<div class="bland_d">
									<img src="img/brand/logo_275.png" width="100%" alt="OTTOのロゴ">
								</div>
								<div class="sen_dotted"></div>
								<p>
									OTTO<br>オットー
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=130">
								<div class="bland_a">
									<img src="img/brand/logo_278.png" width="100%" alt="dyenomite apparelのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									dyenomite&nbsp;apparel<br>ダイナマイトアパレル
								</p>
							</a>
						</div>

						<div class="item btn waves-effect waves-light">
							<a href="/items/?tag=128">
								<div class="bland_b">
									<img src="img/brand/logo_276.png" width="100%" alt="RABBIT SKINSのロゴ">
								</div>

								<div class="sen_dotted"></div>
								<p>
									RABBIT&nbsp;SKINS<br>ラビットスキンズ
								</p>
							</a>
						</div>

					</div>
				</section>
			</div>




			<!--            インスタ-->
			<section class="tla_insta">
				<h2 class="rank_ttl bland_ttl">Instagram</h2>
				<div class="wrap_h2_under">
					<p class="h2_under">タカハマライフアートのプリントスタッフがオリジナルTシャツをプリントしている様子やオリジナルプリントの注文に役立つアイテムやサービスなどを発信しています。<br> オリジナルデザインがシルクスクリーンや刺繍などの加工がどんな工場でどんな雰囲気で作成されているか見ることができるのでオリジナルプリントファンは必見です！
					</p>
				</div>


				<div class="loopSlider">
					<ul>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_01.jpg" width="100%" alt="プリント用のインク"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_02.jpg" width="100%" alt="タカハマのオリジナルキャップ"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_03.jpg" width="100%" alt="プリント作業を行うプリント職人達"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_04.jpg" width="100%" alt="シルクスクリーンプリント"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_05.jpg" width="100%" alt="様々な色のプリントインク"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_06.jpg" width="100%" alt="グラフィックなTシャツデザイン"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_07.jpg" width="100%" alt="雪の中工場に向かう職人達"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_08.jpg" width="100%" alt="新小平の夕日"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_09.jpg" width="100%" alt="工場で作業をする職人達"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_10.jpg" width="100%" alt="機械から顔を出す職人達"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_11.jpg" width="100%" alt="シルクスクリーンの型を見る社員"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_12.jpg" width="100%" alt="ビンに入ったプリントインク"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_13.jpg" width="100%" alt="工場から見えるスカイツリー"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_14.jpg" width="100%" alt="Studio Locallyの型"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_15.jpg" width="100%" alt="オリジナルプリントの作業台"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_16.jpg" width="100%" alt="シルクスクリーンに使う印刷代"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_17.jpg" width="100%" alt="印刷に使う機械"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_18.jpg" width="100%" alt="工場で写真を撮る社員"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_19.jpg" width="100%" alt="インクが付いたスキージ"></a></li>
						<li><a href="https://www.instagram.com/takahamalifeart/" target="_blank" rel="nofollow"><img src="/img/insta_20.jpg" width="100%" alt="工場に並ぶカラフルなオリジナルウェア"></a></li>
					</ul>
				</div>

				<div class="button_01">
					<a class="btn_or btn waves-effect waves-light" href="https://www.instagram.com/takahamalifeart/" type="button" style="text-transform: none;" target="_blank" rel="nofollow">Instagramを見る</a>
				</div>


			</section>


			<!--            安永記入ビデオ-->
			<div class="topvideo_bg">
				<section class="topvideo">
					<h2 class="rank_ttl bland_ttl">タカハマライフアートについて</h2>
					<div class="insertvideo">
						<iframe width="672" height="378" src="https://www.youtube.com/embed/6pGo6TKJ8_4?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen onPlay="ga(‘send’,‘event’,‘Video’,‘Play’,‘company-movie’);" id=“movie”></iframe>
						<!--						not youtube code
				
				<video class="percentvideo" src="/vid/topvideo.mp4" poster="/img/previewvideo.png" controls controlsList="nodownload" preload="none" onclick="this.play()" onPlay="ga(‘send’,‘event’,‘Video’,‘Play’,‘company-movie’);" id=“movie”></video>-->
					
					
					</div>
<div class="sound-attention">
	<p>※音楽が流れます</p>
</div>

					<div class="wrap_h2_under movie-text">
						<p class="h2_under">タカハマライフアートは、オリジナルTシャツを年間27万枚、短納期で納品しているプリント製作会社です。 タカハマライフアート最大の強みは、製作からお届けまでのスピードです。
							<a href="https://www.takahama428.com/order/express/">最短即日発送</a>で、都内であれば最短当日にオリジナルTシャツがお手元に届きます。お急ぎの方は是非ご利用ください、タカハマライフアートなら、まだ間に合います。 </p>

						<p class="h2_under"><a href="https://www.takahama428.com/userreviews/">お客様満足度94%</a>、日本一のカスタマーサポートスタッフが最後までオリジナルTシャツ作りをサポートします。「Tシャツの最安値は？」「デザインの手直しをして欲しい」など何でも大丈夫です、お気軽にご相談ください。 また、
							<a href="https://www.takahama428.com/contact/line/">LINEでも簡単にお問い合わせができる</a>ので、忙しいお客様も片手間にすぐに疑問を解決することができます。 お見積りは無料です、「Tシャツ30枚で一箇所一色でプリントした場合いくらかかる？」などお気軽にお問い合わせください。すぐに返答させて頂きます。
						</p>

						<p class="h2_under">タカハマライフアートは、東京下町にある工場でプリント製作しています。職人が一枚一枚、丁寧にプリントしているので、<a href="https://www.takahama428.com/app/WP/thanks-blog/">お客様のデザイン</a>を、完璧に表現します。 イベント用に作成したい、作成したオリジナルTシャツを販売したいなど、どんなニーズにもお答えします。お客様だけの、面白い、可愛いオリジナルデザイン、お待ちしてます。 </p>
					</div>

					<div class="button_01">
						<a class="btn_or btn waves-effect waves-light" href="https://www.takahama428.com/corporate/overview.php" type="button" style="text-transform: none;">会社概要を見る</a>
					</div>
				</section>
			</div>

<!--よくある質問　安永-->

			<div class="faq_top_bg">
				<section class="faq_block">
					<h2 class="rank_ttl bland_ttl">よくある質問(FAQ)</h2>
					<p class="h2_under">
						オリジナルTシャツの製作で特によくいただく質問を下記にまとめました。他にご不明な点がありましたらお気軽にご相談ください。
						</p>
					
					<div class="inner">
						<h3 class="title">
							<div class="q">Q.</div>
							発注して最短どのくらいにできますか？
						</h3>
						<p class="sub">
							<a href="/order/express/">最短即日の当日仕上げがございます。</a>
							 例えば、月曜の12:00 までにデザインとサイズ、枚数等をお電話でご確認いただければ、その日中にオリジナルTシャツが完成いたします。
							 東京工場で直接のお引渡しであれば、17：00から18：00の間にお受取が可能です。配送をご希望の場合は、完成後に1日（1部地域は2日）でご指定のご住所に配達されます。
							特急製作は料金が割増（通常料金の1.3から2.0倍）となります。その他割引の適用条件など制限がございますので。詳しくは<a href="/order/express/">お急ぎの方</a>へをご参照下さい。
						</p>
						
					</div>
					
					<div class="inner">
						<h3 class="title">
							<div class="q">Q.</div>
							全部で○枚なのですが、1枚○円、全体で○円以内で収めたいです。
						</h3>
						<p class="sub">
							まずは簡単でいいのでFAX(03-5670-0730)または<a href="/contact/">メール</a>、
							<a href="/contact/line/">LINE</a>等でプリントのイメージをお伝えください。
							（例 ： 胸○色、～とフォントを打つ。背中に○色でイラストが入るなど。）
							その後こちらで予算に合わせたプリント方法を算出いたしますのでご参考ください。
							（安価で仕上げる場合、いろいろなデメリットが出てくることをご了承ください。）
							また当WEBサイトでは<a href="/price/estimate.php">自動で見積り金額</a>を算出するサービスがありますので、ぜひご活用下さい。
						</p>

					</div>
					
					<div class="inner">
						<h3 class="title">
							<div class="q">Q.</div>
							インクジェットの濃色と淡色の金額の違いは？
						</h3>
						<p class="sub">
							淡色のほうが濃色より2,000円程高い金額です。<br>
							淡色は白や薄い色の生地に使用できる加工で、濃色は黒など濃い色の生地に使用できる加工方法です。<br>
							詳しくは<a href="/print/inkjet.php">インクジェットプリントの説明ページ</a>をご参照下さい。
						</p>

					</div>
					
					<div class="inner">
					
						<h3 class="title">
							<div class="q">Q.</div>
							受付枚数は何枚からですか？
						</h3>
						<p class="sub">
							当社は1枚からでも作成可能です。枚数に合わせた<a href="/print/">プリント方法</a>をご提案いたします。
						</p>

					</div>
					
					<div class="inner">
						<h3 class="title">
							<div class="q">Q.</div>
							追加発注したい時はどうしたらよいですか？
						</h3>
						<p class="sub">
							<a href="/user/my_menu.php">マイページ</a>の注文履歴から、追加発注ボタンをクリックすることで発注お手続きが可能です。詳しくは<a href="/order/reorder.php">追加注文説明ページ</a>をご参照ください。
						</p>

					</div>
					
				
					<div class="button_01">
						<a class="btn_or btn waves-effect waves-light" href="/guide/faq.php" type="button" style="text-transform: none; margin-top: 40px;">よくある質問をもっと見る</a>
					</div>
				</section>
			</div>







			<div class="news_block_bg">
				<section class="news_block">


					<h2 class="rank_ttl news_block_ttl">お知らせ</h2>
					<div class="wrap_h2_under">
						<p class="h2_under">タカハマライフアートからオリジナルTシャツ作成に関わる最新のお知らせやお得な情報です。<br>オリジナルプリントを楽しめる新アイテム・サービスを発信していきます。どんどん発信していくのでお見逃しなく！</p>
					</div>

					<div class="list-group-wrap news_list">
						<ul class="list-group">
                           
							<li class="list-group-item">
								<div class="row news_set">
									<div class="new_tag">NEW&nbsp;</div>
									<div class="news_date">
										2018.09.18
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【7059 ナイロンコーチジャケット仕様変更のお知らせ】
										</div>
									</a>

								</div>
							</li>
                            
                            <li class="list-group-item">
								<div class="row news_set">
									<div class="new_tag">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									<div class="news_date">
										2018.09.13
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【価格改定のお知らせ】
										</div>
									</a>

								</div>
							</li>
						
                            <li class="list-group-item">
								<div class="row news_set">
									<div class="new_tag">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									<div class="news_date">
										2018.09.04
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【価格改定のお知らせ】
										</div>
									</a>

								</div>
							</li>
						
							<li class="list-group-item">
								<div class="row news_set">
									<div class="new_tag">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									<div class="news_date">
										2018.07.31
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【夏季休業のお知らせ】
										</div>
									</a>

								</div>
							</li>
							<li class="list-group-item">
								<div class="row news_set">
									<div class="new_tag">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									<div class="news_date">
										2018.07.09
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【大雨による配送遅延のお知らせ】
										</div>
									</a>

								</div>
							</li>





						</ul>
					</div>


				</section>
			</div>



			<!--            ここまで新　仁神-->


		</main>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js_2.php"; ?>
		<script src="/common/js/api.js"></script>
		<script src="/js/top.min.js"></script>
		<script type="text/javascript" src="/common/js/dist/js/jquery.sliderPro.min.js"></script>
		<script type="text/javascript" src="/common/js/dist/js/owl.carousel.min.js"></script>
		<script type="text/javascript" src="/common/js/TimeCircles.js"></script>
		<!--		<script type="text/javascript" src="/common/js/libs/fancybox/jquery.fancybox.pack.js"></script>-->
		<script type="text/javascript">
			$(function($) {
				$('#example2').sliderPro({
					width: 1000,
					height: 280,
					smallSize: 320,
					mediumSize: 768,
					largeSize: 2000,
					arrows: true, //左右の矢印
					visibleSize: '100%',
					forceSize: 'fullWidth',
					autoSlideSize: true,

					breakpoints: {
						768: {
							height: 180,
							width: 450,
						}
					}
				});

				// instantiate fancybox when a link is clicked
				$(".slider-pro").each(function() {
					var slider = $(this);

					slider.find(".sp-image").parent("a").on("click", function(event) {
						if (slider.hasClass("sp-swiping")) event.preventDefault();

					});
				});


				//共通のjsに移動

				//				$('.drawer_button').click(function() {
				//					$(this).toggleClass('active');
				//					$('.drawer_bg').fadeToggle();
				//					$('nav').toggleClass('open');
				//				})
				//				$('.drawer_bg').click(function() {
				//					$(this).fadeOut();
				//					$('.drawer_button').removeClass('active');
				//					$('nav').removeClass('open');
				//				});
				//			$(document).ready(function(){
				//				$('.owl-carousel').owlCarousel();
				//			});

				var owl = $('.owl-carousel');
				owl.owlCarousel({
					//				margin: 10,
					loop: false,
					responsive: {
						//					0: {
						//						items: 15,
						//						loop:true,
						//					},
						//					600: {
						//						items: 15,
						//						loop:true,

						//					},
						//					1000: {
						//						items: 15,
						//						loop:true,

						//					}
					}
				})


				$('.tab-content > .tab-box > div').css('display', 'block');
				$(".DateCountdown").TimeCircles({
					"animation": "smooth",
					"bg_width": 1,
					"fg_width": 0.04,
					"circle_bg_color": "#EEEEEE",
					"time": {
						"Days": {
							"text": "日",
							"color": "#F18B1A",
							"show": true
						},
						"Hours": {
							"text": "時間",
							"color": "#F18B1A",
							"show": true
						},
						"Minutes": {
							"text": "分",
							"color": "#F18B1A",
							"show": true
						},
						"Seconds": {
							"text": "秒",
							"color": "#F18B1A",
							"show": true
						}
					}
				});
				$('.tab-content > .tab-box > div').css('display', '');
			});

		</script>

		<!--   インスタ  スライダー-->
		<script src="/common/js/jquery.pause.min.js"></script>
		<script>
			$(function() {
				var setElm = $('.loopSlider'),
					slideSpeed = 3000;

				setElm.each(function() {
					var self = $(this),
						selfWidth = self.innerWidth(),
						findUl = self.find('ul'),
						findLi = findUl.find('li'),
						listWidth = findLi.outerWidth(),
						listCount = findLi.length,
						loopWidth = listWidth * listCount;

					findUl.wrapAll('<div class="loopSliderWrap" />');
					var selfWrap = self.find('.loopSliderWrap');

					if (loopWidth > selfWidth) {
						findUl.css({
							width: loopWidth
						}).clone().appendTo(selfWrap);

						selfWrap.css({
							width: loopWidth * 3
						});

						function loopMove() {
							selfWrap.animate({
								left: '-' + (loopWidth) + 'px'
							}, slideSpeed * listCount, 'linear', function() {
								selfWrap.css({
									left: '0'
								});
								loopMove();
							});
						};
						loopMove();

						setElm.hover(function() {
							selfWrap.pause();
						}, function() {
							selfWrap.resume();
						});
					}
				});
			});

		</script>
		<!--   インスタ  スライダー-->

		<!--      制作実績  スライダー-->
		<script type="text/javascript" src="slick/slick.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {


				var $slider_container = $('.slider_p-result'),
					$slider = $('.print_result');

				$('.p-result').slick({
					arrows: true,
					slidesToShow: 4,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 4000,
					appendArrows: $slider_container,
					// FontAwesomeのクラスを追加
					prevArrow: '<div class="slider-arrow slider-prev icon-angle-left-solid"></div>',
					nextArrow: '<div class="slider-arrow slider-next icon-angle-right-solid"></div>',

					responsive: [{
							breakpoint: 768, // 767px以下のサイズに適用
							settings: {
								slidesToShow: 2,
							}
						},

					],

				});

			});

		</script>
		<!--      制作実績  スライダー-->



	</body>

	</html>
