<?php
//include $_SERVER['DOCUMENT_ROOT']."/common/inc/pageinit.php";
require_once $_SERVER['DOCUMENT_ROOT']."/php_libs/orders.php";
$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
$_SESSION['ticket'] = $ticket;

$order = new Orders();
$fin = $order->getDelidate();

$ua=$_SERVER['HTTP_USER_AGENT'];
if((strpos($ua,' iPhone')!==false)||(strpos($ua,' iPod')!==false)||(strpos($ua,' Android')!==false)) {
$txt_SP02 = '<p><a href="tel:0120130428" style="margin-left:0px;font-size:60px;font-weight:bold;">0120-130-428</a></p>';
$txt_SP02 .= '<p class="note" style="margin-left:95px;">受付時間：平日10:00-18:00</p>';
}else{
$txt_SP02 = '<img src="img/phoneno.png" width="456" height="104" alt="TEL:0120-130-428 受付時間：平日10:00-18:00"><br>';
}

// category selector
$data = $order->categoryList();
$category_selector = '<select id="category_selector" name="category">';
$category_selector .= '<option value="" selected="selected">-</option>';
for($i=0; $i<count($data); $i++){
$categoryName = mb_convert_encoding($data[$i]['name'], 'euc-jp','utf-8');
$category_selector .= '<option value="'.$data[$i]['code'].'" rel="'.$data[$i]['id'].'"';
$category_selector .= '>'.$categoryName.'</option>';
}
$category_selector .= '</select>';
?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="オリジナルウェアの追加・再注文の方法をまとめているページです。注文1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。" />
		<meta name="keywords" content="オリジナル,tシャツ,追加注文,再注文" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！">
		<meta property="og:type" content="website">
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。">
		<meta property="og:url" content="https://www.takahama428.com/">
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート">
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
		<meta property="fb:app_id" content="1605142019732010">
		<title>追加・再注文の方法 ｜ オリジナルTシャツ【タカハマライフアート】</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="/order/css/reorder.css" media="screen" />
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">
				<ul class="pan hidden-sm-down">
					<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
					<li>追加・再注文の方法</li>
				</ul>

				<div class="toolbar">
					<div class="toolbar_inner clearfix">
						<h1>追加・再注文の方法</h1>
					</div>
				</div>
				<div id="main" class="reorder">
					<div class="banner"><img src="/order/img/tsuika/additional_order_top_pc.jpg" width="100%" alt="追加・再注文"></div>
					<div class="banner_sp"><img src="/order/img/tsuika/additional_order_top_sp.jpg" width="100%" alt="追加・再注文"></div>

					<div class="heading1_wrapper">

					</div>

					<div id="bene_1">
						<h2 class="pc">特典1 同じデザインで追加注文</h2>
						<h2 class="sp">特典1<br><span class="lf">同じデザインで追加注文</span></h2>
						<div class="dotted"></div>

						<div class="fuki_sp"><img src="/order/img/tsuika/balloon_01.png" width="100%"></div>

						<div class="discount">
							<p class="min_ttl">割引について</p>
							<img src="/order/img/tsuika/edition_fee_free.jpg" alt="保存期間なし" width="100%">
							<p><span class="red_txt">※</span>割引との併用はできません</p>
							<p><span class="red_txt">※</span>シルクスクリーン、デジタル転写のみの対応です。</p>
						</div>

						<div class="flow">
							<p class="min_ttl">ご注文の流れ</p>
							<p class="flow_txt">マイページにログインして、追加注文フォームか簡単注文！</p>

							<ol class="pc">
								<li><span class="circle">1</span>マイページ<span class="triangle"></span></li>
								<li><span class="circle">2</span>ご注文履歴<span class="triangle"></span></li>
								<li><span class="circle">3</span>ご注文一覧<span class="triangle"></span></li>
								<li><span class="circle">4</span>追加注文をクリック</li>
							</ol>

							<ol class="sp">
								<li><span class="circle">1</span>マイページ<span class="triangle_b"></span></li>
								<li><span class="circle">2</span>ご注文履歴<span class="triangle_b"></span></li>
								<li><span class="circle">3</span>ご注文一覧<span class="triangle_b"></span></li>
								<li><span class="circle">4</span>追加注文をクリック</li>
							</ol>
						</div>

					</div>


					<div id="bene_2">
						<h2 class="pc">特典2 新しいデザインで注文</h2>
						<h2 class="sp">特典2<br><span class="lf">新しいデザインで注文</span></h2>
						<div class="dotted"></div>

						<div class="fuki_sp"><img src="/order/img/tsuika/balloon_02.png" width="100%"></div>

						<div class="discount_2">
							<p class="min_ttl">割引について</p>
							<div class="repeat">
								<p class="flow_txt">新しいデザインで<span class="red_b">2回目</span>のご注文の方！</p>
								<img src="/order/img/tsuika/discount_repeat.png" alt="保存期間なし" width="100%">
								<p><span class="red_txt">※</span>当日特急、翌日・2日仕上げは対象外</p>
							</div>

							<div class="vip">
								<p class="flow_txt">新しいデザインで<span class="red_b">3回目</span>以降のご注文の方！</p>
								<img src="/order/img/tsuika/discount_vip.png" alt="保存期間なし" width="100%">
								<p><span class="red_txt">※</span>当日特急、翌日・2日仕上げは対象外</p>
							</div>
						</div>

						<div class="flow_2">
							<p class="min_ttl">ご注文の流れ</p>
							<p class="flow_txt"><span class="red_txt">マイページにログイン</span>してから申し込みをすると個人情報の入力がスムーズ！</p>

							<ol class="pc">
								<li><span class="circle">1</span>マイページ<span class="triangle"></span></li>
								<li><span class="circle">2</span>お申し込みボタン</li>
							</ol>

							<ol class="sp">
								<li><span class="circle">1</span>マイページ<span class="triangle_b"></span></li>
								<li><span class="circle">2</span>お申し込みボタン</li>
							</ol>

						</div>

					</div>

					<div id="mypage_reorder">
						<h3 class="mp">マイページについて</h3>
						<div class="dotted"></div>
						<img src="/order/img/tsuika/banner_additional_pc.jpg" class="pc" alt="マイページ" width="100%">
						<img src="/order/img/tsuika/banner_additional_sp.jpg" class="sp" alt="マイページ" width="100%">


						<div class="mail_change">
							<div class="mail_change_l">
								<p class="mail">メールアドレスを変更したい方へ</p>
								<p class="example">例えば...</p>


								<div class="arrow_box">
									<p class="arrow_txt">以前の注文した人のアドレスから自分のアドレスに変えたい！</p>
								</div>


								<ul class="example_1">
									<li class="ab_list">
										<div class="asan">
											<p class="ab_txt">Aさん</p>
											<img src="/order/img/tsuika/bsan.png" width="100%">
											<p class="ab_mail">aaa@mail.com</p>
										</div>
									</li>

									<li>
										<div class="arrow"></div>
									</li>

									<li class="ab_list">
										<div class="bsan">
											<p class="ab_txt">Bさん</p>
											<img src="/order/img/tsuika/asan.png" width="100%">
											<p class="ab_mail"><span class="red_txt">bbb@mail.co.jp</span></p>
										</div>
									</li>
								</ul>
								<p class="next_txt">次のステップに従って手続きを行ってください。</p>
								<p class="user_2">まずはメール（またはお電話）にて弊社にご連絡ください</p>
							</div>



							<div class="step">
								<div class="step_1">
									<p class="min_ttl">STEP1 弊社に連絡</p>
									<p>メール（またはお電話）で下記内容をお伝えください。</p>
									<div class="user">
										<p class="red_txt">現在登録されているお客様氏名</p>
										<p class="">例：高濱　太郎</p>
										<p class="red_txt">変更するお客様氏名</p>
										<p>例：山田　花子</p>
										<p class="red_txt">変更後の連絡先</p>
										<p>例：yamada@gmail.com</p>
									</div>
								</div>

								<div class="step_2">
									<p class="min_ttl">STEP2 パスワード再発行で完了</p>
									弊社から連絡が来たら、マイページのログイン画面を開きます。 「
									<span class="ora">パスワードを忘れた方はこちら</span>」に進み、変更後のメールアド レスを入力します。届いたメールに従って、パスワード再発行の 手続きを行ってください。
								</div>
							</div>
						</div>


						<div class="login">
							<div class="log_box">
								<p class="log_txt">早速マイページにログインして、追加・再注文！</p>
							</div>
							<div class="log_box_sp">
								<p class="log_txt">早速マイページにログインして<br>追加・再注文！</p>
							</div>

							<div class="login_btn_2">
								<a href="/user/history.php" id="mypage_btn"><img src="/order/img/tsuika/sp_login_icon.png">マイページ</a>
							</div>

							<p class="log_txt_2"><a href="/user/resend_pass.php"><span class="bluepas">パスワードを忘れた方はこちらへ</span></a></p>

							<p class="comment2">ログイン方法が分からない等の疑問点は、電話、メールでお問い合わせください。</p>

							<div class="flow_1_butt hidden-xs-down">
								<a class="button1" href="/contact/">お問い合わせ</a>
							</div>



							<div>
								<ul class="cal_red hidden-xs-down">
									<li>
										<div class="phone_txt">
											<p class="timetx">受付時間：平日 10:00-18:00</p>
											<p class="red_txt_2"><img alt="電話" src="/common/img/tel_mark.png" width="40px" height="50px" style="padding-bottom:5px; padding-right:10px;">0120-130-428</p>
										</div>
									</li>
								</ul>
							</div>
						</div>

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

	</body>

	</html>
