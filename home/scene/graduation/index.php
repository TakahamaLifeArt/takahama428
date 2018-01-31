<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/jd/japaneseDate.php';
for($cnt=1,$idx=0; $cnt<5; $cnt++,$idx++){
	$fin = getDelidate(null, 1, $cnt, 'simple');
	$main_month[$idx] = $fin['Month'];
	$main_day[$idx] = $fin['Day'];
}
function getDelidate($baseSec=null, $transport=1, $count_days=4, $mode=null){	
	$orderAmount = 0;	// 注文枚数
	$isPack = false;	// 袋詰めの有無
	$jd = new japaneseDate();
	$one_day = 86400;										// 一日の秒数

	if(empty($baseSec)){
		$time_stamp = time()+39600;							// 13:00からは翌日扱いのため11時間の秒数分を足す
		$year  = date("Y", $time_stamp);
		$month = date("m", $time_stamp);
		$day   = date("d", $time_stamp);
		$baseSec = mktime(0, 0, 0, $month, $day, $year);	// 注文確定日の00:00のtimestampを取得
	}
	$workday=0;												// 作業に要する日数をカウント
	if(is_null($mode)){
		if($isPack && $orderAmount>=10){					// 袋詰めありで且つ10枚以上のときは作業日数がプラス1日
			$count_days++;
		}
	}
	$_from_holiday = strtotime(_FROM_HOLIDAY);				// お休み開始日
	$_to_holiday = strtotime(_TO_HOLIDAY);				// お休み最終日
	while($workday<$count_days){
		$fin = $jd->makeDateArray($baseSec);
		if( (($fin['Weekday']>0 && $fin['Weekday']<6) && $fin['Holiday']==0) && ($baseSec<$_from_holiday || $_to_holiday<$baseSec) ){
			$workday++;
		}
		$baseSec += $one_day;
	}

	// 配送日数は曜日に関係しないため、2日かかる地域の場合に1日分を足す
	if($transport==2){
		$baseSec += $one_day;
	}

	$fin = $jd->makeDateArray($baseSec);
	$weekday = $jd->viewWeekday($fin['Weekday']);
	$fin['weekname'] = $weekday;
	$fin['timestamp'] = $baseSec;
	return $fin;
}
?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="大切な人の卒業・卒園記念に、オリジナルグッズを作成してプレゼントしませんか？Tシャツやタオルに思い入れのある写真をフルカラーでプリント、また、高級感ある刺繍で名入れも可能です。あなたのおめでとうを形にしましょう。
" />
		<meta name="keywords" content="卒業,卒園,記念,オリジナル" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>卒業・卒園記念品グッズを作成しよう！ | オリジナルTシャツ【タカハマライフアート】</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="/common/js/lightbox/jquery.lightbox-0.5.css" media="all" />
		<link rel="stylesheet" type="text/css" href="./css/style.css" media="screen" /> </head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?> </header>

		<div id="container">
			<div class="contents" width="100%">
				<ul class="pan hidden-sm-down">
					<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
					<li>シーン</li>
					<li>卒業・卒園記念品</li>
				</ul>
				<div class="graduation_header">
					<h1>卒業・卒園記念品</h1>
					<img src="./img/graduation_main.jpg" class="hidden-xs-down" width="100%" alt="卒業・卒園記念品"> <img src="./img/sp_graduation_main.jpg" class="hidden-sm-up" width="100%" alt="卒業・卒園記念品">
				</div>
				<div class="wrap">
					<div class="kind_correspondence">
						<div class="kind_left">
							<p class="serif">お客様の立場に立って<br class="hidden-sm-down">ご提案致します！</p>
							<p>卒業の記念に皆でお揃いのオリジナルグッズを作りたいけれど、どうしたら良いの？</p>
							<p>そんな時はタカハマライフアートにお任せください！</p>
							<p>デザインの相談やおすすめのアイテム、ご予算に合わせたプリント方法など、<span class="red_bold">お客様のご要望に合わせてご提案させていただきます。</span>ぜひお気軽にご相談ください。</p>
						</div>
						<div class="kind_right">
							<img src="./img/sp_graduation_kind.jpg" width="100%">
						</div>
					</div>
					<div class="recommend_item">
						<h2>おすすめアイテム</h2>
						<p>タカハマライフアートの卒業記念品として人気のアイテムをご紹介します。かけがえのない仲間との思い出を、おすすめアイテムで作成できます。</p>
						<div class="three_item">
							<div class="item">
								<h3>Tシャツ</h3>
								<p>085-CVT<br> 5.6オンスヘビーウエイトＴシャツ
								</p>
								<div class="img_tshirt">
									<img src="./img/sp_graduation_item_01.png" width="100%">
								</div>
								<p>Tシャツの人気第1位！全50色でサイズ展開も幅広く、お気に入りの1枚が必ず見つかります。どんなシーンでも活躍すること間違いなし！</p>
								<a href="/items/category/t-shirts/">
									<button type="button" class="btn_or btn">Tシャツをもっと見る</button>
                            </a>
							</div>
							<div class="item">
								<h3>バッグ</h3>
								<p>777-SCT<br> スタンダードキャンバストートバッグ
								</p>
								<div class="img_bag">
									<img src="./img/sp_graduation_item_02.png" width="100%">
								</div>
								<p>T12オンスのキャンバス素材でしっかりした生地感なのに、お手頃価格が魅力です。カラーも豊富なので、色違いで揃えてみるのもおすすめです！</p>
								<a href="/items/category/tote-bag/">
									<button type="button" class="btn_or btn">バッグをもっと見る</button>
                                    </a>
							</div>
							<div class="item">
								<h3>タオル</h3>
								<p>537-FTC<br> カラーフェイスタオル
								</p>
								<div class="img_towel">
									<img src="./img/sp_graduation_item_03.png" width="100%"></div>
								<p>大判サイズが嬉しいフェイスタオル。コットン100%で優しい肌触りなので普段使いにも最適！ビビットなカラーが豊富です。</p>
								<a href="/items/category/towel/">
									<button type="button" class="btn_or btn">タオルをもっと見る</button>
                            </a>
							</div>
						</div>
					</div>
					<div class="design_example">
						<h2>デザイン例</h2>
						<p>デザインを制作する際の参考にご覧ください。</p>
						<div class="three_design">
							<div class="design">
								<div class="img_tshirt">
									<img src="./img/sp_graduation_design_01.png" width="100%">
								</div>
								<p>幼稚園のクラス全員と先生の手描きの似顔絵を載せたデザインのTシャツは卒園の記念にみんなで着ましょう！</p>
							</div>
							<div class="design">
								<div class="img_bag">
									<img src="./img/sp_graduation_design_02.png" width="100%">
								</div>
								<p>満開の桜と飛び立つ鳥のデザインのトートバッグは卒業記念品。生徒の新たな門出を祝う気持ちを込めました。</p>
							</div>
							<div class="design">
								<div class="img_towel">
									<img src="./img/sp_graduation_design_03.png" width="100%"></div>
								<p>ロゴが目を引く20期チアダンスチームのタオル。チームメイトの名前を載せて先輩へプレゼントすれば喜ばれること間違いなし！</p>
							</div>
						</div>
					</div>
					<div class="customer_voice">
						<h2>お客様の声</h2>
						<div class="review">
							<div class="photo">
								<img src="./img/sp_graduation_review_01.jpg" width="100%">
							</div>
							<div class="comment">
								<h4>サプライズプレゼントのオリジナルタオルで感涙！</h4>
								<p>サークルのみんなにサプライズでタオルを<br> プレゼントすることができました！
									<br> 泣いて喜んでくれたのでほんとに嬉しかったです。
									<br> 最高のチームで最高のバスケしてきます！
									<br> ありがとうございました！！！
								</p>
							</div>
						</div>
						<div class="review">
							<div class="photo">
								<img src="./img/sp_graduation_review_02.jpg" width="100%">
							</div>
							<div class="comment">
								<h4>先輩へ引退の記念品にオリジナルTシャツ！</h4>
								<p>引退する先輩方へのプレゼントとして利用させて<br> 頂きました。とても喜んで頂けました。
									<br> 迅速に対応していただき、ありがとうございました！
								</p>
							</div>
						</div>
						<div class="review">
							<div class="photo">
								<img src="./img/sp_graduation_review_03.jpg" width="100%">
							</div>
							<div class="comment">
								<h4>卒業記念にメッセージも書いちゃうクラスTシャツ！</h4>
								<p>リセ フランコ ジャポネで卒業記念に作りました、<br> みんなでメッセージを書きあって、盛り上がりました！
									<br> 本当にありがとうございました！
								</p>
							</div>
						</div>
					</div>
					<div class="contact">
						<div class="contact_left">
							<div class="contact_left_right">
								<p class="contact_us">まずはお気軽にお問い合わせください!</p>
								<p class="tel"><i class="fa fa-phone" aria-hidden="true"></i>0120-130-428</p>
								<p class="reception_time">受付時間：平日10:00-18:00</p>
							</div>
						</div>
						<div class="contact_right">
							<button type="button" class="btn_or btn">お問い合わせ</button>
							<button type="button" class="btn btn-info">お申し込み</button>
						</div>

					</div>
				</div>
			</div>
		</div>
		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>
		<script type="text/javascript">
			window._pt_sp_2 = [];
			_pt_sp_2.push('setAccount,52e3170c');
			var _protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
			(function() {
				var atag = document.createElement('script');
				atag.type = 'text/javascript';
				atag.async = true;
				atag.src = _protocol + 'js.ptengine.jp/pta.js';
				var stag = document.createElement('script');
				stag.type = 'text/javascript';
				stag.async = true;
				stag.src = _protocol + 'js.ptengine.jp/pts.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(atag, s);
			})();

		</script>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>
		<div id="overlay-mask" class="fade"></div>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="/common/js/lightbox/jquery.lightbox-0.5.js"></script>
	</body>

	</html>
