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
$contentsLength = 4;
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
    <html lang="ja" style="overflow: auto!important;">

    <head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="google-site-verification" content="PfzRZawLwE2znVhB5M7mPaNOKFoRepB2GO83P73fe5M">
        <meta name="Description" content="オリジナルTシャツ作成が早い！即納可能の業界最速！クラスTシャツの文字入れも最短即日で短納期プリント。1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。">
        <meta property="og:title" content="オリジナルウェアを通常3日で製作！">
        <meta property="og:type" content="website">
        <meta property="og:description" content="オリジナルTシャツ業界No.1の当日製作のプランあります！！親切対応と高品質で安心の満足度94%！">
        <meta property="og:url" content="https://www.takahama428.com/">
        <meta property="og:site_name" content="オリジナルプリント屋｜タカハマライフアート">
        <meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
        <meta property="fb:app_id" content="1605142019732010">
        <title>オリジナルTシャツのプリント作成 ｜ タカハマライフアート</title>
        <link rel=canonical href="https://www.takahama428.com/">
        <link rel="shortcut icon" href="/icon/favicon.ico">
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" href="/common/js/dist/css/slider-pro.min.css" media="screen" />
        <link rel="stylesheet" href="/common/js/dist/css/owl.carousel.min.css" media="screen" />
        <link rel="stylesheet" href="/common/css/TimeCircles.css" media="screen" />
        <link rel="stylesheet" href="./icon/style.css">
        <link rel="stylesheet" type="text/css" href="/common/js/libs/fancybox/jquery.fancybox.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/common/css/examples.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="/common/css/side_menu.css" media="screen" />
        <link rel="stylesheet" href="/slick/slick.css">
        <link rel="stylesheet" href="/slick/slick-theme.css">
        <link rel="stylesheet" href="./css/style.css">

        <style>
            .owl-item {
                width: 100%!important;
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
                    <div class="sp-slide">
                        <div class="carousel-item active hero-carousel__cell hero-carousel__cell--1 carousel-item_02">
                            <div class="carousel-caption">
                                <p class="text_02_1">1枚1枚、職人がプリントする</p>
                                <p class="text_01_1">オリジナルTシャツ</p>
                                <p class="psn_btn"><a href="/order/" class="check btn btn-info adj waves-effect waves-light orderbtn_01" type="button">
<img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 5px;">お申し込み</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="sp-slide">
                        <div class="carousel-item hero-carousel__cell hero-carousel__cell--2 carousel-item_02">
                            <div class="carousel-caption">
                                <p class="text_01_2">業界最速!<br class="hidden-sm-up">今日届くオリジナルTシャツ</p>
                                <p class="text_02">最短6時間で即日発送!</p>
                                <p class="psn_btn"><a href="/order/" class="check btn btn-info adj waves-effect waves-light orderbtn_02" type="button">
<img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 5px;">お申し込み</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="sp-slide">
                        <div class="carousel-item hero-carousel__cell hero-carousel__cell--3 carousel-item_02">
                            <div class="carousel-caption">
                                <p class="text_01">Made in Tokyo</p>
                                <p class="text_02">顧客満足度94%!<br class="hidden-sm-up">安心のプリント実績200万枚以上</p>
                                <p class="psn_btn"><a href="/order/" class="check btn btn-info adj waves-effect waves-light orderbtn_03" type="button">
<img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 5px;">お申し込み</a>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        
        <div class="bar_mess"><a href="/guide/information.php"><span class="big_mess"><i class="fas fa-exclamation-triangle"></i>価格改定のお知らせ</span>&nbsp;&nbsp;&nbsp;<span class="small_mess hidden-sm-down">詳しく見る></span></a></div>

        <main class="container">
            <!--
<div style="text-align:  center;padding-top:  20px;margin-bottom:  30px;color: #d71414;font-size:  14px;">
<p style="font-size:16px; font-weight:bold;">【システムの不具合に関するお知らせ】</p>
<p style="margin-bottom:.5rem;">現在、HPの一部が正常にご覧いただけない状況が発生しております。</p>
<p style="margin-bottom:.5rem;">お急ぎの方は、お電話もしくはお問合せメールにてご対応させて頂きますので、お問い合わせください。</p>
<p style="margin-bottom:.5rem;">お客様には多大なるご迷惑、ご不便をおかけしておりますことを深くお詫び申し上げます。</p>
</div>
-->

            <h2 class="rank_ttl">アイテムカテゴリー</h2>
            <div class="wrap_h2_under">
                <p class="h2_under">オリジナルTシャツだけでなく、様々なアイテムにプリントできます！</p>
            </div>
            <section class="hidden-sm-down">

                <div class="owl-carousel owl-theme">

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
                        <a class="dropdown-item goods_btn" href="/items/category/workwear/">
<img src="/items/img/item_13.jpg" width="100%">
<p class="item_txt_min">ワークウェア</p>
</a>
                    </div>

                    <div class="navi_inner_2 btn">
                        <a class="dropdown-item goods_btn" href="/items/category/goods/">
<img src="/items/img/item_15.jpg" width="100%">
<p class="item_txt_min">記念品</p>
</a>
                    </div>

                </div>
            </section>

            <section class="hidden-md-up sp_item_wrap">
                <div class="sp_items">
                    <div class="row">

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
                    </div>

                    <div class="row">
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
                                    <h5>今、注文すると...この日にお届け！</h5>
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
                                    <h5>今、注文すると...この日にお届け！</h5>
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
                                    <h5>今、注文すると...この日にお届け！</h5>
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
                                    <h5>今、注文すると...この日にお届け！</h5>
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
                                        <img src="/common/img/home/main/top_est_item_01.png" width="100%">
                                    </div>
                                    <p class="est_item_name">Tシャツ</p>
                                </div>

								<div class="est_item_02 btn waves-effect waves-light" data-category="polo-shirts" data-itemid="625" data-color="001">
                                    <div class="est_item_img">
                                        <img src="/common/img/home/main/top_est_item_02.png" width="100%">
                                    </div>
                                    <p class="est_item_name">ポロシャツ</p>
                                </div>

								<div class="est_item_03 btn waves-effect waves-light" data-category="sweat" data-itemid="124" data-color="001">
                                    <div class="est_item_img">
                                        <img src="/common/img/home/main/top_est_item_03.png" width="100%">
                                    </div>
                                    <p class="est_item_name">スウェット</p>
                                </div>

								<div class="est_item_04 btn waves-effect waves-light" data-category="towel" data-itemid="363" data-color="001">
                                    <div class="est_item_img">
                                        <img src="/common/img/home/main/top_est_item_04.png" width="100%">
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
                                        <img src="/common/img/home/main/top_est_color_01.png" width="100%">
                                    </div>
                                    <p class="est_item_name">1色</p>
                                </div>

								<div class="est_item_02 btn waves-effect waves-light" data-ink="2">
                                    <div class="est_item_img">
                                        <img src="/common/img/home/main/top_est_color_02.png" width="100%">
                                    </div>
                                    <p class="est_item_name">2色</p>
                                </div>

								<div class="est_item_03 btn waves-effect waves-light" data-ink="3">
                                    <div class="est_item_img">
                                        <img src="/common/img/home/main/top_est_color_03.png" width="100%">
                                    </div>
                                    <p class="est_item_name">3色</p>
                                </div>

								<div class="est_item_04 btn waves-effect waves-light" data-ink="4">
                                    <div class="est_item_img">
                                        <img src="/common/img/home/main/top_est_color_04.png" width="100%">
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
                                    <div class="price_right"><span class="est_orange_txt" id="per">0</span>円</div></div>
                                
                                
                                <div class="total_price">
                                    <div class="price_left_02">
                                    合計:
                                    </div>
                                    <div class="price_right_02"><span class="est_orange_txt" id="sum">0</span>円</div></div>
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



            <!--kurosu--!>
                    
<!--
                    <div class="content-area right_area">
					<h2 class="rank_ttl">10秒見積もり</h2>
					<p class="h2_under">オリジナルTシャツの概算の金額がすぐに計算できます！</p>

					<div class="menulist">
						<div class="blockmenu">
							<div class="menublock">
								<h3>アイテム</h3>
								<select name="example1">
<option value="Tシャツ">Tシャツ</option>
<option value="ポロシャツ">ポロシャツ</option>
<option value="スウェット">スウェット</option>
<option value="タオル">タオル</option>
<option value="スポーツ">スポーツ</option>
<option value="ブルゾン">ブルゾン</option>
<option value="長袖Tシャツ">長袖Tシャツ</option>
<option value="バッグ">バッグ</option>
<option value="キャップ">キャップ</option>
<option value="エプロン">エプロン</option>
<option value="ベビー">ベビー</option>
<option value="つなぎ">つなぎ</option>
<option value="レディース">レディース</option>
<option value="ワークウェア">ワークウェア</option>
<option value="記念品">記念品</option>
</select>
							</div>
							<div class="menublock">
								<h3>プリント方法</h3>
								<select name="example2">
<option value="シルクスクリーン">シルクスクリーン</option>
<option value="デジタル転写">デジタル転写</option>
<option value="インクジェット">インクジェット</option>
<option value="カッティングシート">カッティングシート</option>
<option value="刺繍">刺繍</option>
</select>
							</div>
							<div class="menublock">
								<h3>プリント色数</h3>
								<select name="example3">
<option value="1色">1色</option>
<option value="2色">2色</option>
<option value="3色">3色</option>
<option value="フルカラー">フルカラー</option>
</select>
							</div>
						</div>
						<div class="blockmenu">
							<div class="menublock">
								<h3>プリントサイズ</h3>
								<select name="example4">
<option value="">5つの選択肢を表示</option>
<option value="選択肢2">選択肢2</option>
<option value="選択肢3">選択肢3</option>
<option value="選択肢4">選択肢4</option>
<option value="選択肢5">選択肢5</option>
<option value="選択肢6">選択肢6</option>
<option value="選択肢7">選択肢7</option>
</select>
							</div>
							<div class="menublock2">
								<h3>枚数<span class="txt_min">150枚以上でお大幅値引き！</span></h3>
								<p class="slidenum"><input type="number" id="jquery-ui-slider-value" name="slidenum" step="10"><span>枚</span></p>
							</div>
						</div>
						<div class="amount_list">
							<h3>最安値だと...</h3>
							<div id="subtotaltxt">
								<p>1枚：<span class="amount_txt">1,230</span>円</p>
							</div>
							<div id="totaltxt">
								<p>1枚：<span class="amount_txt">1,2300</span>円</p>
							</div>
						</div>
					</div>

					<div class="cnt_txt">
						<p class="clear">カンタンに全アイテムの見積もりが比較できます！</p>
						<a href="/price/estimate.php" class="btn_or btn waves-effect waves-light" type="button">
<span>10秒比較見積もりへ</span>
</a>
					</div>
				</div>
-->

            <!--kurosu--!>



			<!--            ここから旧お届け日・見積-->


            <!--
			<div class="item_category">

				<div class="top_02">
					<h2 class="rank_ttl">お届け日・見積もり計算</h2>
					<div class="wrap_h2_under">
						<p class="h2_under">WEB上で、すぐにお届け日とオリジナルTシャツの概算を調べることができます。</p>
					</div>

					<div class="row outer top_3_wrap">

						<div class="col bk">
							<a href="/delivery/" class="check btn_01 top_3 top_item_flex">
								<div class="bk_area">
									<p class="top3_bu_txt">今注文すると<br><span class="big_font">この日に届く</span></p>
								</div>
								<div class="bk_area">
									<div id="date">
										<p>
											<?php echo $fin['Month'];?><span class="slash_01">/</span>
											<?php echo $fin['Day'];?><span class="min_txt youbi">(<?php echo $fin['weekname'];?>)</span></p>
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
									<div class="tri_base tri_base_02">
										<p class="btn_arrea txt_space">見積もりをする</p><span class="triangle1"></span></div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
-->






            <!--            ここまで旧お届け日・見積--->






            <!--            ここから新　仁神-->


            <section class="print_result">
                <h2 class="rank_ttl"> 製作実例</h2>
                <div class="wrap_h2_under">
                    <p class="h2_under">お客様のご利用シーンと製作物を写真とコメントでご紹介します。オリジナルTシャツは様々なシーンで活躍しています。最近ではドライタイプのTシャツやポロシャツがスポーウェアやユニフォームに多く使われています。デザインも企業ロゴから手描デザインなど幅広く見ていて参考になります。Tシャツとインクの配色もご参考に！</p>
                </div>

                <div class="slider_p-result">
                    <div class="p-result">
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2018/02/12/オリジナルスタッフtシャツでイベントも大盛況！/">
                                <div class="result_image"><img src="/img/ex_01.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> Tシャツありがとうございました♪ <br>急な依頼にもかかわらずいい感じに仕上げていただきました(^-^)/<br>おかげでイベントも盛り上がりました!!
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/12/05/親身な対応に安心してお揃いtシャツ作れました♪/">
                                <div class="result_image"><img src="/img/ex_02.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> 昨年タカハマさんで作ったという知人の紹介で、こちらを知りました。<br>他社さんも調べたのですが、タカハマさんのご対応が一番親切・丁寧だったので決めました！…
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/12/01/オリジナルデザインのクラスtもスピーディ！/">
                                <div class="result_image"><img src="/img/ex_03.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> 先日は、クラスTオリジナルデザイン作成～仕上がりまで丁寧な対応有難うございました。<br>スピーディな対応が神的でした^_^
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/05/08/t_218/">
                                <div class="result_image"><img src="/img/ex_04.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> 先日は、お忙しい中対応して頂き、誠にありがとうございました。<br>Ｔシャツの出来上がりに子どもたちも 大満足で、無事にイベントを過ごす事ができ、いい思い出作りができ…
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/11/10/着心地もよく、仕上がりも大変満足なオリジナルt/">
                                <div class="result_image"><img src="/img/ex_05.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> 初めて利用させていただきましたが、早い仕上がりと、完成度の良さに大変満足しております。<br>またぜひ、お願いいたします。
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/11/08/大人数イベントの参加者用オリジナルtシャツ！/">
                                <div class="result_image"><img src="/img/ex_06.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt">いつもTシャツの制作、ありがとうございます。<br>今回で何度目でしょう？？6回目くらいですかね？<br>毎回200人近い参加者用にTシャツを作っていただいております。…
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/11/06/社内イベントにオリジナルのマフラータオル！/">
                                <div class="result_image"><img src="/img/ex_07.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> この度は大変お世話になりました。<br>無事、社内イベントにて製作頂きましたタオルを使用することができました！<br>デザイン・質感とも大変喜んでおりました。
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/11/08/ディズニーランドにお揃いtシャツで最高の思い出/">
                                <div class="result_image"><img src="/img/ex_08.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt">みんなでTシャツを着てディズニーランドに！<br>とっても目立てて最高の思い出になりました??<br>先日は急な注文にもかかわらず、丁寧なご対応、また素敵なTシャツ本当…
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/11/08/ホームステイ記念＆お土産に漢字名前プリントシ/">
                                <div class="result_image"><img src="/img/ex_09.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt">今回ホームステイでサンディエゴから16歳の男の子が来ました。<br>その記念にお揃いのＴシャツを作りました。<br>デザインは、彼が日本の高校に行った時に 書道で書いた彼の名前…
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/12/05/想像以上のきれいなデザインtシャツに満足♪/">
                                <div class="result_image"><img src="/img/ex_10.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> きれいにデザインして頂いて、想像以上に素晴らしいTシャツになりました。<br>良い記念になりました。ありがとうございました！
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/11/08/オーケストラのメンバーtシャツで練習も頑張れる/">
                                <div class="result_image"><img src="/img/ex_11.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> 素敵なTシャツを作って頂きありがとうございました！<br>オーケストラの皆さんも気に入ってくれて演奏会に向けてよりいっそう頑張れます！
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/11/06/サプライズプレゼントのオリジナルタオルで感涙/">
                                <div class="result_image"><img src="/img/ex_12.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> サークルのみんなにサプライズでタオルをプレゼントすることができました！<br>泣いて喜んでくれたのでほんとに嬉しかったです。<br>最高のチームで最高のバスケしてき…</p>
                        </div>
                        <div class="result_block">
                            <a href="app/WP/thanks-blog/2017/11/02/__trashed-4/">
                                <div class="result_image"><img src="/img/ex_13.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> この度は、ステキなTシャツを作っていただいて本当にありがとうございました！<br>みんなで同じTシャツを着ることで、団結力が強まりイベントを盛り上げ…
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/05/08/t_217/">
                                <div class="result_image"><img src="/img/ex_14.jpg" width="100%"></div>
                            </a>
                            <p class="result_txt"> 今回マラソン大会に出るためにオリジナルＴシャツをタカハマライフアートさんで作成していただきました。<br>皆で一致団結して頑張ります！<br>タカハマライフアートさん、ありが…
                            </p>
                        </div>
                        <div class="result_block">
                            <a href="/app/WP/thanks-blog/2017/05/08/t_215/">
                                <div class="result_image"><img src="/img/ex_15.jpg" width="100%"></div>
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
                            <img src="/common/img/home/review/sp_review_040.png" width="100%">
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
                                    <img src="/common/img/home/review/sp_review_040.png" width="100%">
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
                                    <img src="/common/img/home/review/sp_review_040.png" width="100%">
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
                                    <img src="/common/img/home/review/sp_review_045.png" width="100%">
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
                                    <img src="/common/img/home/review/sp_review_040.png" width="100%">
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
                        <a class="btn-floating_review" href="#multi-item-example_01" data-slide="prev"><i class="fa fa-chevron-left review_arrow"></i></a>
                        <div class="carousel-inner" role="listbox">

                            <!--First slide-->
                            <div class="carousel-item active">

                                <div class="col review_comment">
                                    <div>
                                        <ul>
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>

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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price">5.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price">3.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price">5.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price">3.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
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
                        <a class="btn-floating_review" href="#multi-item-example_01" data-slide="next"><i class="fa fa-chevron-right review_arrow"></i></a>
                    </div>
                    <!--/.Carousel Wrapper pc-->




                    <!--Carousel Wrapper sp-->
                    <div id="multi-item-example" class="hidden-md-up carousel slide carousel-multi-item slide_wrap" data-ride="carousel">

                        <!--Slides-->
                        <a class="btn-floating_review" href="#multi-item-example" data-slide="prev"><i class="fa fa-chevron-left review_arrow"></i></a>
                        <div class="carousel-inner" role="listbox">

                            <!---->
                            <div class="carousel-item active">

                                <div class="col review_comment">
                                    <div>
                                        <ul>
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>

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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price">5.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price">3.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_050.png" width="100%" class="imgsz"><span class="rank_price">5.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_035.png" width="100%" class="imgsz"><span class="rank_price">3.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.0</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_040.png" width="100%" class="imgsz"><span class="rank_price">4.2</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.7</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
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
                                            <li><img src="/common/img/home/review/sp_review_045.png" width="100%" class="imgsz"><span class="rank_price">4.5</span></li>
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
                        <a class="btn-floating_review" href="#multi-item-example" data-slide="next"><i class="fa fa-chevron-right review_arrow"></i></a>
                    </div>
                    <!--/.Carousel Wrapper-->


                    <div class="button_01">
                        <a class="btn_or btn waves-effect waves-light" href="/userreviews/" type="button">レビューをもっと見る</a>
                    </div>


                </section>

            </div>





            <section class="itemcategory_01">

                <h2 class="rank_ttl itemcategory_ttl">アイテムランキング</h2>
                <div class="wrap_h2_under">
                    <p class="h2_under">今、人気のアイテムをカテゴリーごとにご紹介します。オリジナルTシャツを作成するのが初めてでどのアイテムにしたら良いか迷っている方は是非このランキングを参考にしてください。 多くの方が選んでいる間違いないTシャツ、ポロシャツ、スウェット、タオル、ブルゾンの1位~4位の人気ランキングです！
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





            <!--
			<section class="itemcategory_01">

				<h2 class="rank_ttl itemcategory_ttl">アイテムランキング</h2>
				<p class="bland_txt">今、人気のアイテムをカテゴリーごとにご紹介します。</p>

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


					<div class="tab_content" id="t-shirts">

						<div class="ranking_four">

							<div class="block_ranking">
								<p class="rank_number_01"><span class="icon-uniF100"></span>1位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>2位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>3位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>4位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="tab_content" id="polo-shirts">

						<div class="ranking_four">

							<div class="block_ranking">
								<p class="rank_number_01"><span class="icon-uniF100"></span>1位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>2位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>3位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>4位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

						</div>

					</div>
					<div class="tab_content" id="sweat">

						<div class="ranking_four">

							<div class="block_ranking">
								<p class="rank_number_01"><span class="icon-uniF100"></span>1位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>2位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>3位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>4位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

						</div>

					</div>
					<div class="tab_content" id="towel">

						<div class="ranking_four">

							<div class="block_ranking">
								<p class="rank_number_01"><span class="icon-uniF100"></span>1位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>2位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>3位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>4位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

						</div>

					</div>

					<div class="tab_content" id="outer">

						<div class="ranking_four">

							<div class="block_ranking">
								<p class="rank_number_01"><span class="icon-uniF100"></span>1位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>2位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>3位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

							<div class="block_ranking">
								<p class="rank_number_02"><span class="icon-uniF100"></span>4位</p>
								<div class="ranking_body">
									<p class="catch">定番品。業界売上No.1</p>
									<div class="border_tab_b"></div>
									<div class="ranking_con">
										<div class="logo_ons">
											<div class="logo"><img src="img/image_dammi.jpg" width="100%"></div>
											<div class="ons">10.0oz</div>
										</div>

										<div class="ranking_item_img"><img src="img/image.jpg" width="100%" alt="品番：085-CVT"></div>
										<p class="number">085-CVT</p>
										<div class="name_wrap">
											<p class="name">7.1オンスオーセンティックスーパーヘヴィーウェイトTシャツ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
										</div>
										<div class="spec">
											<div class="spec_l">全50色</div>
											<div class="spec_r">サイズ：100?XXL</div>
										</div>
										<p class="price"><span class="price_number">2,000</span><span class="price_yen">円?</span></p>
										<div class="border_dotted_01"></div>
										<a href="/itemreviews/?item=4">
											<div class="review_star">
												<div class="review_star_img">
													<img src="/common/img/home/review/sp_review_050.png" width="100%">
												</div>
												<div class="review_star_number">5.0</div>
											</div>
											<p class="i_review_link">レビューを見る（367）</p>
										</a>
									</div>
								</div>
							</div>

						</div>

					</div>




				</div>





			</section>
-->



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
                                    <img src="img/brand/logo_58.png" width="100%">
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
                                    <img src="img/brand/logo_59.png" width="100%">
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
                                    <img src="img/brand/logo_108.png" width="100%">
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
                                    <img src="img/brand/logo_60.png" width="100%">
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
                                    <img src="img/bland_05.png" width="100%">
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
                                    <img src="img/bland_06.png" width="100%">
                                </div>
                                <div class="sen_dotted"></div>
                                <p>
                                    CROSS&nbsp;&amp;&nbsp;STTCH<br>クロスアンドステッチ
                                </p>
                            </a>
                        </div>

                        <div class="item btn waves-effect waves-light">
                            <a href="/items/?tag=62">
                                <div class="bland_a">
                                    <img src="img/bland_07.png" width="100%">
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
                                    <img src="img/bland_08.png" width="100%">
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
                                    <img src="img/bland_09.png" width="100%">
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
                                    <img src="img/bland_10.png" width="100%">
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
                                    <img src="img/bland_11.png" width="100%">
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
                                    <img src="img/bland_12.png" width="100%">
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
                                    <img src="img/bland_13.png" width="100%">
                                </div>

                                <div class="sen_dotted"></div>
                                <p>
                                    BEES&nbsp;BEAM<br>ビーズビーム
                                </p>
                            </a>
                        </div>

                        <div class="item btn waves-effect waves-light">
                            <a href="/items/?tag=65">
                                <div class="bland_d">
                                    <img src="img/bland_14.png" width="100%">
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
                                    <img src="img/bland_15.png" width="100%">
                                </div>

                                <div class="sen_dotted"></div>
                                <p>
                                    Champion<br>チャンピオン
                                </p>
                            </a>
                        </div>

                        <div class="item btn waves-effect waves-light">
                            <a href="/items/?tag=109">
                                <div class="bland_d">
                                    <img src="img/bland_16.png" width="100%">
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
                                    <img src="img/bland_17.png" width="100%">
                                </div>

                                <div class="sen_dotted"></div>
                                <p>
                                    COMFORT&nbsp;COLORS<br>コンフォートカラーズ
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
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_01.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_02.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_03.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_04.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_05.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_06.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_07.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_08.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_09.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_10.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_11.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_12.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_13.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_14.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_15.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_16.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_17.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_18.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_19.jpg" width="100%" alt=""></a></li>
                        <li><a href="https://www.instagram.com/takahamalifeart/"><img src="/img/insta_20.jpg" width="100%" alt=""></a></li>
                    </ul>
                </div>

                <div class="button_01">
                    <a class="btn_or btn waves-effect waves-light" href="https://www.instagram.com/takahamalifeart/" type="button" style="text-transform: none;">Instagramを見る</a>
                </div>


            </section>



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
									<div class="new_tag">NEW</div>
									<div class="news_date">
										2018.06.25
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
									<div class="new_tag">NEW</div>
									<div class="news_date">
										2018.06.19
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【地震による配送遅延のお知らせ】
										</div>
									</a>

								</div>
							</li>

							<li class="list-group-item">
								<div class="row news_set">
									<div class="new_tag">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									<div class="news_date">
										2018.04.17
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【GW休業のお知らせ】
										</div>
									</a>

								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="new_tag">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									<div class="news_date">
										2018.02.27
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【価格改定のお知らせ】
										</div>
									</a>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="new_tag">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
									<div class="news_date">
										2017.12.14
									</div>
									<a href="/guide/information.php">
										<div class="news_ttl">
											【冬季休業のお知らせ】
										</div>
									</a>
								</div>
							</li>

							<!--
<li class="list-group-item">
<div class="row">
<div class="new_tag">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div class="news_date">
2017.10.24
</div>
<a href="/guide/information.php">
<div class="news_ttl">
【アイテム価格改定のお知らせ】
</div>
</a>
</div>
</li>
-->

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
        <script type="text/javascript" src="/common/js/libs/fancybox/jquery.fancybox.pack.js"></script>
        <script type="text/javascript">
            $(function($) {
                $('#example2').sliderPro({
                    width: 300,
                    height: 300,
                    visibleSize: '100%',
                    forceSize: 'fullWidth',
                    autoSlideSize: true
                });

                // instantiate fancybox when a link is clicked
                $(".slider-pro").each(function() {
                    var slider = $(this);

                    slider.find(".sp-image").parent("a").on("click", function(event) {
                        event.preventDefault();

                        if (slider.hasClass("sp-swiping") === false) {
                            var sliderInstance = slider.data("sliderPro"),
                                isAutoplay = sliderInstance.settings.autoplay;

                            $.fancybox.open(slider.find(".sp-image").parent("a"), {
                                index: $(this).parents(".sp-slide").index(),
                                afterShow: function() {
                                    if (isAutoplay === true) {
                                        sliderInstance.settings.autoplay = false;
                                        sliderInstance.stopAutoplay();
                                    }
                                },
                                afterClose: function() {
                                    if (isAutoplay === true) {
                                        sliderInstance.settings.autoplay = true;
                                        sliderInstance.startAutoplay();
                                    }
                                }

                            });
                        }
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
                    prevArrow: '<div class="slider-arrow slider-prev fa fa-angle-left"></div>',
                    nextArrow: '<div class="slider-arrow slider-next fa fa-angle-right"></div>',

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
