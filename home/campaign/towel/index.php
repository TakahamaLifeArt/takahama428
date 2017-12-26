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
	<meta name="Description" content="早い！オリジナルタオルを1枚から作成・即日発送可能。タオル・スポーツタオル・ファイスタオル・マフラータオル・バスタオルで各種グレードを取り揃えました。全てオリジナルプリント可能です。短納期でオリジナルプリントを作成したい方は、東京葛飾区にあるプリント工場、タカハマライフアートへ！" />
	<meta name="keywords" content="タオル,オリジナルタオル,1枚から,小ロット,早い,短納期,作成,プリント,東京,即日安い" />
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>オリジナルタオルの作成・プリントが早い ｜ オリジナルTシャツ【タカハマライフアート】</title>
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
				<li>最速オリジナルタオル</li>
			</ul>
			<div class="towel_header">
				<h1>最速オリジナルタオル</h1> <img src="./img/towel_img.jpg" class="hidden-xs-down" width="100%" alt="最速オリジナルタオル"> <img src="./img/sp_towel_img.jpg" class="hidden-sm-up" width="100%" alt="最速オリジナルタオル">
				<div class="towel_title"><span class="red_bold">当日特急プラン</span>が適用できるタオルがあります！</div>

			</div>
			<div class="wrap">
				<div class="speed_plan">
					<h2>当日特急プランとは..</h2>
					<p><span class="red_new">12時まで</span>にお電話での注文確定をして頂ければ、最短で<span class="red_new">当日のお届けが可能</span>なプランです！</p>
				</div>
				<div class="heading"></div>
				<h2 class="lines-on-sides">お届けプラン早見表</h2>
				<div class="plan_day">
					<div class="plan_table"><img src="./img/sp_towel_graph.jpg" width="100%"></div>
					<div class="plan_table">
						<p>当日特急プランでのご注文で<br>お電話での確認まで完了した場合、</p>
						<div class="plan_box">
							<p class="plan_ttl">当日特急プラン</p>
							<div class="plan_date">
								<p><span class="mm"><?php echo $main_month[0]; ?>/</span><span class="dd"><?php echo $main_day[0]; ?></span></p>
							</div>
						</div>
						<p class="txt_right">にお届け可能！</p>
						<a href="/delivery/">
							<div class="method_button">その他のお届けプランを見る</div>
						</a>
					</div>
				</div>

				<div class="can_item">
					<div class="balloon1">
						<p>当日発送可能なアイテムはこちら</p>
					</div>
					<div class="ok_item">
						<p id="ok_name">522-FT フェイスタオル</p>
						<div class="ok_item_inner">
							<div class="one"><img src="./img/sp_towel_ft_01.png" width="100%"></div>
							<div class="two"><img src="./img/sp_towel_ft_02.png" width="100%"></div>
						</div>
						<p class="item_cau">※スーパージャンボ版を使用しております。</p>
					</div>
				</div>

				<div class="order">
					<a class="order_btn" href="/items/item.php?code=522-ft"><img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 2px;">このアイテムで注文する</a>
				</div>
				<h2 class="lines-on-sides">プリントについて</h2>
				<div>
					<p class="sub_ttl">プリント可能範囲</p>
					<div class="ok_item_inner_2">
						<div class="towel_size">
							<p>通常版</p> <img src="./img/sp_towel_printsize_01.png" width="100%"> </div>
						<div class="towel_size">
							<p>スーパージャンボ版</p> <img src="./img/sp_towel_printsize_02.png" width="100%"> </div>
					</div>
					<p class="towel_txt">※ 版とはシルクプリントの際に必要な「型」のことです。１箇所1色に対して、版代が必要になります。</p>
				</div>

				<div>
					<p class="sub_ttl">サイズとプリントイメージ</p>
					<div class="ok_item_inner_3">
						<div class="image_inner">
							<p class="image_ttl">524-MT　マフラータオル</p>
							<div class="image_in_in">
								<p>プリントイメージ</p>
								<div class="posi_15"><img src="./img/sp_towel_mt_01.png" width="100%"></div>
								<p class="red_new posi_20">プリント範囲（ 版のサイズ ）</p>
								<div class="img_Adj posi_20"><img src="./img/sp_towel_mt_02.png" width="100%"></div>
								<p class="item_cau_2 posi_15">※スーパージャンボ版を使用しております。</p>
								<div class="model"><img src="./img/sp_towel_mt_03.jpg" width="100%"></div>
								<p class="red_new">他にもこんなプリントの方法ができます！</p>
								<div class="posi"><img src="./img/sp_towel_mt_04.png" width="100%"></div>
								<a href="/items/item.php?code=524-mt">
									<div class="method_button">524-MT マフラータオルページを見る</div>
								</a>
							</div>
						</div>
						<div class="image_inner">
							<p class="image_ttl">528-BT　バスタオル</p>
							<div class="image_in_in">
								<p>プリントイメージ</p>
								<div><img src="./img/sp_towel_bt_01.png" width="100%"></div>
								<p class="red_new">プリント範囲（ 版のサイズ ）</p>
								<div class="img_Adj"><img src="./img/sp_towel_bt_02.png" width="100%"></div>
								<p class="item_cau_2">※スーパージャンボ版を使用しております。</p>
								<div class="model"><img src="./img/sp_towel_bt_03.jpg" width="100%"></div>
								<p class="red_new">他にもこんなプリントの方法ができます！</p>
								<div><img src="./img/sp_towel_bt_04.png" width="100%"></div>
								<a href="/items/item.php?code=528-bt">
									<div class="method_button">528-BT バスタオルページを見る</div>
								</a>
							</div>
						</div>
						<div class="image_inner">
							<p class="image_ttl">526-ST　スポーツタオル</p>
							<div class="image_in_in">
								<p>プリントイメージ</p>
								<div class="posi_10"><img src="./img/sp_towel_st_01.png" width="100%"></div>
								<p class="red_new posi_5">プリント範囲（ 版のサイズ ）</p>
								<div class="img_Adj posi_10"><img src="./img/sp_towel_st_02.png" width="100%"></div>
								<p class="item_cau_2 posi_5">※スーパージャンボ版を使用しております。</p>
								<div class="model"><img src="./img/sp_towel_st_03.jpg" width="100%"></div>
								<p class="red_new">他にもこんなプリントの方法ができます！</p>
								<div><img src="./img/sp_towel_st_04.png" width="100%"></div>
								<a href="/items/item.php?code=526-st">
									<div class="method_button">526-ST スポーツタオルページを見る</div>
								</a>
							</div>
						</div>
					</div>
					<div class="ok_item_inner_3">
						<div class="image_inner">
							<p class="image_ttl">519-HT　ハンドタオル</p>
							<div class="image_in_in">
								<div style="display:flex">
									<p>プリントイメージ</p>
									<p class="red_new">プリント範囲（ 版のサイズ ）</p>
								</div>
								<div><img src="./img/sp_towel_ht_01.png" width="100%"></div>
								<div class="model_2"><img src="./img/sp_towel_hkt_03.jpg" width="100%"></div>
								<a href="/items/item.php?code=519-ht">
									<div class="method_button">519-HT　ハンドタオルページを見る</div>
								</a>
							</div>
						</div>
						<div class="image_inner">
							<p class="image_ttl">540-HKT　ハンカチタオル</p>
							<div class="image_in_in">
								<div style="display:flex">
									<p>プリントイメージ</p>
									<p class="red_new">プリント範囲（ 版のサイズ ）</p>
								</div>
								<div><img src="./img/sp_towel_ht_01.png" width="100%"></div>
								<div class="model_2"><img src="./img/sp_towel_ht_03.jpg" width="100%"></div>
								<a href="/items/item.php?code=540-hkt">
									<div class="method_button">540-HKT　ハンカチタオルページを見る</div>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="material">
					<h2 class="lines-on-sides">素材について</h2>
					<div>
						<div class="material_box">
							<p class="material_ttl">シャーリング</p>
							<div class="ok_item_inner_3">
								<div><img src="./img/sp_towel_cloth_01.jpg" width="100%"></div>
								<div class="mate_top_txt">
									<p>シャーリング生地と呼ばれる。滑らかな肌触りの表生地です。裏のパイル生地と比較すると吸水性が少ないですが、特殊な加工により、繊細なデザインも細かくプリントできることが魅力です。</p>
								</div>
							</div>
							<div class="ok_item_inner_4">
								<div class="zoom_pic">
									<p class="zoom_ttl">シルク(染み込みインク)でプリントした写真</p>
									<a href="img/t_print_up_01.jpg" rel="lightbox[rubber]"><img src="img/t_print_01.jpg" width="100%"></a>
									<p>薄い色の生地に3色までプリント可能です。白・金・銀カラーには非対応です。 </p>
								</div>
								<div class="zoom_pic">
									<p class="zoom_ttl">インクジェットでプリントした写真</p>
									<a href="img/t_print_up_02.jpg" rel="lightbox[rubber1]"><img src="img/t_print_02.jpg" width="100%"></a>
									<p>フルカラーに対応しています。白色のみプリント可能です。 </p>
								</div>
							</div>
						</div>
						<div class="material_box">
							<p class="material_ttl">フラット織り</p>
							<div class="ok_item_inner_3">
								<div><img src="./img/sp_towel_cloth_02.jpg" width="100%"></div>
								<div class="mate_top_txt">
									<p>表面がTシャツ生地のように平らになっているので、Tシャツと同じプリントが可能になります。裏面はよくあるタオルと同じ生地なので拭いて頂く時は裏面を使用してください。</p>
								</div>
							</div>
							<div class="ok_item_inner_4">
								<div class="zoom_pic">
									<p class="zoom_ttl">シルク(ラバーインク)でプリントした写真</p>
									<a href="img/t_print_up_03.jpg" rel="lightbox[rubber2]"><img src="img/t_print_03.jpg" width="100%"></a>
									<p>3色までプリント可能で、白・金・銀カラーにも対応しています。 </p>
								</div>
								<div class="zoom_pic">
									<p class="zoom_ttl">インクジェットでプリントした写真</p>
									<a href="img/t_print_up_04.jpg" rel="lightbox[rubber3]"><img src="img/t_print_04.jpg" width="100%"></a>
									<p>フルカラーに対応しています。白色のみプリント可能です。 </p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<a href="/items/index.php?cat=8">
			<div class="method_button_2">タオル一覧から申し込む</div>
		</a>
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
