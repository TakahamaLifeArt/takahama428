<?php
/**
 * カートの状態取得とセッション開始
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';

if( empty($_SESSION['me']) ){
	$signinState = 'ログイン';
	$signinName = '';
	$isHidden = 'hidden';
	$buttonName = 'ログイン';
}else{
	$signinState = 'ログアウト';
	$signinName = $_SESSION['me']['customername'].' 様';
	$isHidden = '';
	$buttonName = 'マイページ';
}
?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T5NQFM" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->



	<!--
	<div class="bar_top">
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
			<div id="option">
				<h3 id="Line005"><a href="/guide/information.php"><i class="fas fa-exclamation-triangle"></i>GW休業のお知らせ</a></h3>
				<p>5月3日(木)から5月6日(日)の間、休業とさせて頂きます。5月7日(月)より通常営業行います。</p>
			</div>

	</div>
-->

	<div class="header_nav_top">
		<h1>オリジナルTシャツのプリント作成、最短即日発送！</h1>
		<p id="signin_name" class="hidden-sm-down">
			<?php echo $signinName;?>
		</p>
		<div id="signout" class="dropdown_exit hidden-sm-down" <?php echo $isHidden;?> >
			<p class="exit_a">
				ログアウト
			</p>
		</div>
	</div>
	<nav class="navbar navbar-toggleable-xl fixed-top navbar-light bg-faded">
		<div class="logo_top_area">

			<?php
		// 2018-04-02になったら表示する
		if (time() >= strtotime('2018-04-02')) {
			echo '<div class="wds_top">
				<a class="navbar-brand" href="/">
				<img alt="Brand" src="/common/img/header/top_logo1.png" class="hidden-xs-down" width="100%">
				<img alt="Brand" src="/common/img/header/top_logo1.png" class="hidden-sm-up sp_logo_1 top_logo_sp" width="100%">
				</a>
				</div>';
		}

		// 2018-04-02になったら非表示にする
		if (time() < strtotime('2018-04-02')) {
			echo '<a class="navbar-brand" href="/">
				<img alt="Brand" src="/common/img/header/top_logo.png" class="hidden-xs-down">
				<img alt="Brand" src="/common/img/header/sp_top_logo.png" class="hidden-sm-up sp_logo" width="100%">
				</a>';
		}
		?>
		</div>
		<div class="ttl_sp hidden-md-down">
			<p>
				親切対応 日本一を目指します!
			</p>
		</div>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item hidden-sm-down">
				<a href="tel:0120130428" class="nav-link">
					<p><i class="fa fa-phone" aria-hidden="true"></i>
						<em>0120-130-428</em>
						<span style="font-size: 0.7rem;margin-top: 4px;">月&#126;金 10:00&#126;18:00 (土日祝除く)</span>
					</p>
				</a>
			</li>
			<li class="nav-item hidden-md-up">
				<a href="tel:0120130428" class="nav-link btn waves-effect waves-light sam_btn">
					<i class="fa fa-phone" aria-hidden="true"></i>
				<span>電話する</span>
			</a>
			</li>
			<li class="nav-item">
				<a href="/contact/" class="btn_or_btn btn waves-effect waves-light" type="button">
					<i class="fa fa-envelope-o" aria-hidden="true"></i>
				<span class="head_contact">お問い合わせ</span>
			</a>
			</li>
			<li class="nav-item hidden-sm-down">
				<a href="/contact/line/" class="btn-line">
					<svg xmlns="//www.w3.org/2000/svg" xmlns:xlink="//www.w3.org/1999/xlink" width="38px" height="55px" style="margin-left: 9px; margin-top:0px; " viewBox="0 0 315 300">
						<defs>
							<style>
								.fill_1 {fill: #ffffff;}
								.fill_2 {fill: #00c300;}
							</style>
						</defs>
						<g>
							<path class="fill_1" d="M280.344,206.351 C280.344,206.351 280.354,206.351 280.354,206.351 C247.419,244.375 173.764,290.686 157.006,297.764 C140.251,304.844 142.724,293.258 143.409,289.286 C143.809,286.909 145.648,275.795 145.648,275.795 C146.179,271.773 146.725,265.543 145.139,261.573 C143.374,257.197 136.418,254.902 131.307,253.804 C55.860,243.805 0.004,190.897 0.004,127.748 C0.004,57.307 70.443,-0.006 157.006,-0.006 C243.579,-0.006 314.004,57.307 314.004,127.748 C314.004,155.946 303.108,181.342 280.344,206.351 Z"/>
							<path class="fill_2" d="M253.185,121.872 C257.722,121.872 261.408,125.569 261.408,130.129 C261.408,134.674 257.722,138.381 253.185,138.381
													C253.185,138.381 230.249,138.381 230.249,138.381 C230.249,138.381 230.249,153.146 230.249,153.146 C230.249,153.146 253.185,153.146 253.185,153.146 C257.710,153.146 261.408,156.851 261.408,161.398 C261.408,165.960 257.710,169.660 253.185,169.660 C253.185,169.660 222.018,169.660 222.018,169.660 C217.491,169.660 213.795,165.960 213.795,161.398 C213.795,161.398 213.795,130.149 213.795,130.149 C213.795,130.139 213.795,130.139 213.795,130.129 C213.795,130.129 213.795,130.114 213.795,130.109 C213.795,130.109 213.795,98.878 213.795,98.878 C213.795,98.858 213.795,98.850 213.795,98.841 C213.795,94.296 217.486,90.583 222.018,90.583 C222.018,90.583 253.185,90.583 253.185,90.583 C257.722,90.583 261.408,94.296 261.408,98.841 C261.408,103.398 257.722,107.103 253.185,107.103 C253.185,107.103 230.249,107.103 230.249,107.103 C230.249,107.103 230.249,121.872 230.249,121.872 C230.249,121.872 253.185,121.872 253.185,121.872 ZM202.759,161.398 C202.759,164.966 200.503,168.114 197.135,169.236 C196.291,169.521 195.405,169.660 194.526,169.660 C191.956,169.660 189.502,168.431 187.956,166.354 C187.956,166.354 156.012,122.705 156.012,122.705 C156.012,122.705 156.012,161.398 156.012,161.398 C156.012,165.960 152.329,169.660 147.791,169.660 C143.256,169.660 139.565,165.960 139.565,161.398 C139.565,161.398 139.565,98.841 139.565,98.841 C139.565,95.287 141.829,92.142 145.192,91.010 C146.036,90.730 146.915,90.583 147.799,90.583 C150.364,90.583 152.828,91.818 154.366,93.894 C154.366,93.894 186.310,137.559 186.310,137.559 C186.310,137.559 186.310,98.841 186.310,98.841 C186.310,94.296 190.000,90.583 194.536,90.583 C199.073,90.583 202.759,94.296 202.759,98.841 C202.759,98.841 202.759,161.398 202.759,161.398 ZM127.737,161.398 C127.737,165.960 124.051,169.660 119.519,169.660 C114.986,169.660 111.300,165.960 111.300,161.398 C111.300,161.398 111.300,98.841 111.300,98.841 C111.300,94.296 114.986,90.583 119.519,90.583 C124.051,90.583 127.737,94.296 127.737,98.841 C127.737,98.841 127.737,161.398 127.737,161.398 ZM95.507,169.660 C95.507,169.660 64.343,169.660 64.343,169.660 C59.816,169.660 56.127,165.960 56.127,161.398 C56.127,161.398 56.127,98.841 56.127,98.841 C56.127,94.296 59.816,90.583 64.343,90.583 C68.881,90.583 72.564,94.296 72.564,98.841 C72.564,98.841 72.564,153.146 72.564,153.146 C72.564,153.146 95.507,153.146 95.507,153.146 C100.047,153.146 103.728,156.851 103.728,161.398 C103.728,165.960 100.047,169.660 95.507,169.660 Z"/>
						</g>
					</svg>
				</a>
			</li>
			<li class="hidden-sm-down header_icon">
				<a href="/guide/faq.php" class="icon_a">
					<p class="icon_wrap"><i class="fa fa-question-circle-o" aria-hidden="true"></i></p>
					<p class="icon_p"><span class="icon_sapn">よくある質問</span></p>
				</a>
			</li>

			<li class="hidden-sm-down  header_icon">
				<a href="/user/login.php" class="icon_a">
					<p class="icon_wrap"><i class="fa fa-user-circle-o" aria-hidden="true" style="font-size: 33px;"></i></p>
					<p class="icon_p"><span id="mypage_button" class="icon_sapn"><?php echo $buttonName;?></span></p>
				</a>
			</li>

			<li class="hidden-sm-down  header_icon" style="margin: 0 -7px;">
				<a href="/order/?update=1" class="icon_a">
					<p class="icon_wrap"><i class="fa fa-shopping-cart" aria-hidden="true"></i></p>
					<p class="icon_p"><span class="icon_sapn">カート</span></p>
				</a>
			</li>
			<!--
			<li class="nav-item hidden-sm-down">
				<a href="/guide/faq.php" class="btn-floating btn-large">
					<i class="fa fa-question-circle-o" aria-hidden="true"></i>
					<p class="nav-link"><span>よくある質問</span></p>
				</a>
			</li>
			
			<li class="nav-item hidden-sm-down">
				<a href="/user/login.php" class="dropdown_mypage waves-effect waves-light mypage_a">
					<p style="color: #000; font-size: 2.1rem; "><i class="fa fa-user-circle-o" aria-hidden="true"></i></p>
					<p class="nav-link"><span id="mypage_button"><?php echo $buttonName;?></span></p>
				</a>
			</li>
			
			<li class="nav-item hidden-sm-down">
				<a href="/order/?update=1" class="btn-floating btn-large">
					<i class="fa fa-shopping-cart" aria-hidden="true"></i>
					<p class="nav-link"><span class="ps_ttl">カート</span></p>
				</a>
			</li>
-->

			<!--
			<li class="nav-item dropdown">
				<div class="nav-link dropdown-toggle" id="navbarDropdownUserMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img class="img-fluid" alt="Sign in" src="/common/img/header/sp_login.jpg" width="90%">
					<span id="signin_state"><?php echo $signinState;?></span>
				</div>
				<div class="dropdown-menu header_login" aria-labelledby="navbarDropdownUserMenu" style="padding: 0.5em;">
					<p id="signin_name">
						<?php echo $signinName;?>
					</p>
					<div class="cart_a_t_box">
						<p>商品枚数<span id="cart_amount">0</span>枚</p>
						<p>商品金額<span id="cart_total">0</span>円</p>
					</div>
					<div class="dropdown_cart">
						<div id="show_cart" class="dropdown-item cart_a">
							<p style="color: white;"><img src="/common/img/header/sp_cart.png" class="img-fluid drop_img" width="100%" style="align-items: left;"><span>カートをみる</span></p>
						</div>
					</div>
					<div class="dropdown_mypage">
						<a href="/user/login.php" class="dropdown-item mypage_a">
							<p style="color: white;"><img src="/common/img/header/sp_mypage.png" class="img-fluid drop_img" width="100%" style="align-items: left;"><span id="mypage_button"><?php echo $buttonName;?></span></p>
						</a>
					</div>
					<hr>
					<div id="signout" class="dropdown_exit" <?php echo $isHidden;?> >
						<p class="dropdown-item exit_a">
							ログアウト
						</p>
					</div>
				</div>
			</li>
			<li class="nav-item dropdown">
				<div class="nav-link dropdown-toggle pr-0" id="navbarDropdownLanguageMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img class="img-fluid" alt="Language" src="/common/img/header/sp_language.jpg" width="90%">
					<span>言語</span>
				</div>
				<div class="dropdown-menu header_language" aria-labelledby="navbarDropdownLanguageMenu">
					<div class="dropdown-item" id="google_translate_element"></div>
					<script type="text/javascript">
						function googleTranslateElementInit() {
							new google.translate.TranslateElement({
								pageLanguage: 'ja',
								includedLanguages: 'ja,en,ko',
								layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
								autoDisplay: false
							}, 'google_translate_element');
						}

					</script>
					<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
				</div>
			</li>
-->
		</ul>

	</nav>

	<button type="button" class="drawer_button btn waves-effect waves-light hidden-md-up">
	<span class="drawer_bar drawer_bar1"></span>
	<span class="drawer_bar drawer_bar2"></span>
	<span class="drawer_bar drawer_bar3"></span>
	<span class="drawer_menu_text drawer_text">MENU</span>
	<span class="drawer_close drawer_text">CLOSE</span>
</button>

	<div class="drawer_menu hidden-md-up">
		<div class="drawer_bg"></div>

		<nav class="drawer_nav_wrapper">

			<div class="side_log">
				<div class="sde_name">
					<p id="signin_name">
						<?php echo $signinName;?>
					</p>
				</div>
				<!--
				<div id="signout" class="dropdown_exit" <?php echo $isHidden;?> >
					<p class="dropdown-item exit_a">
						ログアウト
					</p>
				</div>
-->
				<div id="signout" class="dropdown_exit" <?php echo $isHidden;?> >
					<p class="dropdown-item exit_a">
						ログアウト
					</p>
				</div>
			</div>
			<div class="side_log">
				<div class="side_btn waves-effect waves-light"><a href="/order/" class="btn-float-large">
					<i class="fa fa-edit" aria-hidden="true" style="margin-left: 5px;margin-top: 1px;color: #289dda;"></i>
					</a>
					<p class="nav-link"><span style="color: #289dda;">申し込み</span></p>
				</div>
				<div class="side_btn waves-effect waves-light"><a href="/guide/faq.php" class="btn-float-large">
					<i class="fa fa-question-circle-o" aria-hidden="true"></i>
					</a>
					<p class="nav-link"><span>よくある質問</span></p>
				</div>
				<div class="side_btn waves-effect waves-light">
					<a href="/user/login.php" class="mypage_a">
						<p style="color: #000;"><i class="fa fa-user-circle-o" aria-hidden="true" style="font-size: 33px;"></i></p>
					</a>
					<p class="nav-link"><span id="mypage_button"><?php echo $buttonName;?></span></p>
				</div>
				<div class="side_btn waves-effect waves-light"><a href="/order/?update=1" class="btn-float-large">
					<i class="fa fa-shopping-cart" aria-hidden="true"></i>
					</a>
					<p class="nav-link"><span class="ps_ttl">カート</span></p>
				</div>
			</div>
			<div class="side_log_2">
				<div class="btn_list">
					<a href="tel:0120130428" class="nav-link btn waves-effect waves-light sam_btn">
						<i class="fa fa-phone fa-2x phone_top" aria-hidden="true"></i>
						<span>電話する</span>
					</a>
				</div>
				<div class="btn_list">
					<a href="/contact/" class="nav-link btn_or_btn btn waves-effect waves-light" type="button">
						<i class="fa fa-envelope-o" aria-hidden="true"></i>
						<span>お問い合わせ</span>
					</a>
				</div>
				<div class="btn_list">
					<a href="/contact/line/" class="nav-link btn-line btn waves-effect waves-light">
						<svg xmlns="//www.w3.org/2000/svg" xmlns:xlink="//www.w3.org/1999/xlink" width="30px" height="55px" class="linemark" viewBox="0 0 315 300">
							<defs>
								<style>
									.fill_1 {fill: #ffffff;}
									.fill_2 {fill: #00c300;}
								</style>
							</defs>
							<g>
								<path class="fill_1" d="M280.344,206.351 C280.344,206.351 280.354,206.351 280.354,206.351 C247.419,244.375 173.764,290.686 157.006,297.764 C140.251,304.844 142.724,293.258 143.409,289.286 C143.809,286.909 145.648,275.795 145.648,275.795 C146.179,271.773 146.725,265.543 145.139,261.573 C143.374,257.197 136.418,254.902 131.307,253.804 C55.860,243.805 0.004,190.897 0.004,127.748 C0.004,57.307 70.443,-0.006 157.006,-0.006 C243.579,-0.006 314.004,57.307 314.004,127.748 C314.004,155.946 303.108,181.342 280.344,206.351 Z"/>
								<path class="fill_2" d="M253.185,121.872 C257.722,121.872 261.408,125.569 261.408,130.129 C261.408,134.674 257.722,138.381 253.185,138.381
														C253.185,138.381 230.249,138.381 230.249,138.381 C230.249,138.381 230.249,153.146 230.249,153.146 C230.249,153.146 253.185,153.146 253.185,153.146 C257.710,153.146 261.408,156.851 261.408,161.398 C261.408,165.960 257.710,169.660 253.185,169.660 C253.185,169.660 222.018,169.660 222.018,169.660 C217.491,169.660 213.795,165.960 213.795,161.398 C213.795,161.398 213.795,130.149 213.795,130.149 C213.795,130.139 213.795,130.139 213.795,130.129 C213.795,130.129 213.795,130.114 213.795,130.109 C213.795,130.109 213.795,98.878 213.795,98.878 C213.795,98.858 213.795,98.850 213.795,98.841 C213.795,94.296 217.486,90.583 222.018,90.583 C222.018,90.583 253.185,90.583 253.185,90.583 C257.722,90.583 261.408,94.296 261.408,98.841 C261.408,103.398 257.722,107.103 253.185,107.103 C253.185,107.103 230.249,107.103 230.249,107.103 C230.249,107.103 230.249,121.872 230.249,121.872 C230.249,121.872 253.185,121.872 253.185,121.872 ZM202.759,161.398 C202.759,164.966 200.503,168.114 197.135,169.236 C196.291,169.521 195.405,169.660 194.526,169.660 C191.956,169.660 189.502,168.431 187.956,166.354 C187.956,166.354 156.012,122.705 156.012,122.705 C156.012,122.705 156.012,161.398 156.012,161.398 C156.012,165.960 152.329,169.660 147.791,169.660 C143.256,169.660 139.565,165.960 139.565,161.398 C139.565,161.398 139.565,98.841 139.565,98.841 C139.565,95.287 141.829,92.142 145.192,91.010 C146.036,90.730 146.915,90.583 147.799,90.583 C150.364,90.583 152.828,91.818 154.366,93.894 C154.366,93.894 186.310,137.559 186.310,137.559 C186.310,137.559 186.310,98.841 186.310,98.841 C186.310,94.296 190.000,90.583 194.536,90.583 C199.073,90.583 202.759,94.296 202.759,98.841 C202.759,98.841 202.759,161.398 202.759,161.398 ZM127.737,161.398 C127.737,165.960 124.051,169.660 119.519,169.660 C114.986,169.660 111.300,165.960 111.300,161.398 C111.300,161.398 111.300,98.841 111.300,98.841 C111.300,94.296 114.986,90.583 119.519,90.583 C124.051,90.583 127.737,94.296 127.737,98.841 C127.737,98.841 127.737,161.398 127.737,161.398 ZM95.507,169.660 C95.507,169.660 64.343,169.660 64.343,169.660 C59.816,169.660 56.127,165.960 56.127,161.398 C56.127,161.398 56.127,98.841 56.127,98.841 C56.127,94.296 59.816,90.583 64.343,90.583 C68.881,90.583 72.564,94.296 72.564,98.841 C72.564,98.841 72.564,153.146 72.564,153.146 C72.564,153.146 95.507,153.146 95.507,153.146 C100.047,153.146 103.728,156.851 103.728,161.398 C103.728,165.960 100.047,169.660 95.507,169.660 Z"/>
							</g>
						</svg>
						<span>相談する</span>
					</a>
				</div>
			</div>
			<div class="accbox">
				<!--ラベル1-->
				<input type="checkbox" id="label1" class="cssacc" />
				<label for="label1">アイテム</label>
				<div class="accshow">
					<div class="list_block">
						<div class="list_ttl"><a href="/items/category/t-shirts/" class="item_munu">Tシャツ</a></div>
						<div class="list_ttl"><a href="/items/category/sweat/" class="item_munu">スウェット</a></div>
						<div class="list_ttl"><a href="/items/category/polo-shirts/" class="item_munu">ポロシャツ</a></div>
						<div class="list_ttl"><a href="/items/category/outer/" class="item_munu">ブルゾン</a></div>
						<div class="list_ttl"><a href="/items/category/towel/" class="item_munu">タオル</a></div>
						<div class="list_ttl"><a href="/items/category/workwear/" class="item_munu">ワークウェア</a></div>
						<div class="list_ttl"><a href="/items/category/tote-bag/" class="item_munu">バッグ</a></div>
						<div class="list_ttl"><a href="/items/category/ladys/" class="item_munu">レディース</a></div>
						<div class="list_ttl"><a href="/items/category/long-shirts/" class="item_munu">長袖Tシャツ</a></div>
						<div class="list_ttl"><a href="/items/category/overall/" class="item_munu">つなぎ</a></div>
						<div class="list_ttl"><a href="/items/category/apron/" class="item_munu">エプロン</a></div>
						<div class="list_ttl"><a href="/items/category/baby/" class="item_munu">ベビー</a></div>
						<div class="list_ttl"><a href="/items/category/cap/" class="item_munu">キャップ</a></div>
						<div class="list_ttl"><a href="/items/category/goods/" class="item_munu">記念品</a></div>
						<div class="list_ttl"><a href="/items/category/sportswear/" class="item_munu">スポーツ</a></div>
					</div>
				</div>
				<!--//ラベル1-->
				<!--ラベル2-->
				<input type="checkbox" id="label2" class="cssacc" />
				<label for="label2">注文ナビ</label>
				<div class="accshow">
					<div class="list_block">
						<div class="list_ttl"><a href="/order/" class="list_munu order"><span class="bluettl">申し込み</span></a></div>
						<div class="list_ttl"><a href="/order/reorder.php" class="list_munu order"><span class="bluettl">追加再注文</span></a></div>
												<div class="list_ttl"><a href="/delivery/" class="list_munu">お届け日を調べる</a></div>
						<div class="list_ttl"><a href="/guide/orderflow.php" class="list_munu">注文の流れ</a></div>
						<div class="list_ttl"><a href="/order/bigorder/" class="list_munu">大口注文について</a></div>
						<div class="list_ttl"><a href="/guide/bring.php" class="list_munu">持ち込み注文について</a></div>
						<div class="list_ttl"><a href="/order/express/" class="list_munu">お急ぎの方へ</a></div>
					</div>
				</div>
				<!--//ラベル2-->
				<!--ラベル3-->
				<input type="checkbox" id="label3" class="cssacc" />
				<label for="label3" class="label3">料金</label>
				<div class="accshow">
					<div class="list_block">
						<div class="list_ttl"><a href="/price/estimate.php" class="list_munu">カンタン比較見積もり</a></div>
						<div class="list_ttl"><a href="/guide/" class="list_munu">お支払い方法</a></div>
						<div class="list_ttl"><a href="/price/fee/" class="list_munu">プリント価格表</a></div>
						<div class="list_ttl"><a href="/guide/discount.php" class="list_munu">割引プラン一覧</a></div>
					</div>
				</div>
				<!--//ラベル3-->
				<!--ラベル4-->
				<input type="checkbox" id="label4" class="cssacc" />
				<label for="label4">プリント</label>
				<div class="accshow">
					<div class="list_block">
						<div class="list_ttl"><a href="/design/printing.php" class="list_munu">プリント方法</a></div>
						<div class="list_ttl"><a href="/design/printsize.php" class="list_munu">参考プリントサイズ</a></div>
						<div class="list_ttl"><a href="/design/position.php" class="list_munu">参考プリント位置</a></div>
						<div class="list_ttl"><a href="/design/emb.php" class="list_munu">刺繍</a></div>
					</div>
				</div>
				<!--//ラベル4-->
				<!--ラベル5-->
				<input type="checkbox" id="label5" class="cssacc" />
				<label for="label5">デザイン</label>
				<div class="accshow">
					<div class="list_block">
						<div class="list_ttl"><a href="/design/designguide.php" class="list_munu">デザインの入稿・作り方</a></div>
						<div class="list_ttl"><a href="/design/template_illust.php" class="list_munu">イラレ入稿テンプレート</a></div>
						<div class="list_ttl"><a href="/design/designtemp.php" class="list_munu">無料デザイン素材</a></div>
						<div class="list_ttl"><a href="/design/gallery.php" class="list_munu">デザインギャラリー</a></div>
						<div class="list_ttl"><a href="/design/fontcolor.php" class="list_munu">インク・フォント</a></div>
						<div class="list_ttl"><a href="/design/support.php" class="list_munu">デザインサポート</a></div>
					</div>
				</div>
				<!--//ラベル5-->
				<!--ラベル6-->
				<input type="checkbox" id="label6" class="cssacc" />
				<label for="label6">会社紹介</label>
				<div class="accshow">
					<div class="list_block">
						<div class="list_ttl"><a href="/corporate/overview.php" class="list_munu">会社概要</a></div>
						<div class="list_ttl"><a href="/userreviews/" class="list_munu">お客様レビュー</a></div>
						<div class="list_ttl"><a href="/app/WP/" class="list_munu">スタッフブログ</a></div>
						<div class="list_ttl"><a href="/app/WP/thanks-blog/" class="list_munu">製作実例</a></div>
						<div class="list_ttl"><a href="/reason/speed.php" class="list_munu">短納期の理由</a></div>
						<div class="list_ttl"><a href="/corporate/transactions.php" class="list_munu">特定商取引法</a></div>
						<div class="list_ttl"><a href="/corporate/privacy-policy.php" class="list_munu">プライバシーポリシー</a></div>
					</div>
				</div>
				<!--//ラベル6-->
			</div>
			<!--//.accbox-->
		</nav>
	</div>


	<div class="gnavi_back"></div>

	<nav id="nav_sample">
		<ul class="clearfix">
			<li class="g_nav_item_box"><div class="gnavi_ttl">アイテム</div>
				<ul>
					<p class="g-nav_item_txt hidden-sm-down">アイテムページでお見積もり出来ます！</p>

					<div class="g-nav_item_wrap">
						<li>
							<div class="item_btn">
								<a class="" href="/items/category/t-shirts/" type="button">
									<img src="/items/img/item_01.jpg" width="100%">
									<p class="g-nav_item_txt_min">Tシャツ</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/polo-shirts/" type="button">
									<img src="/items/img/item_03.jpg" width="100%">
								<p class="g-nav_item_txt_min">ポロシャツ</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/sweat/" type="button">
									<img src="/items/img/item_02.jpg" width="100%">
								<p class="g-nav_item_txt_min">スウェット</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/towel/" type="button">
									<img src="/items/img/item_08.jpg" width="100%">
								<p class="g-nav_item_txt_min">タオル</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/sportswear/" type="button">
									<img src="/items/img/item_04.jpg" width="100%">
								<p class="g-nav_item_txt_min">スポーツ</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/outer/" type="button">
									<img src="/items/img/item_06.jpg" width="100%">
								<p class="g-nav_item_txt_min">ブルゾン</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/long-shirts/" type="button">
									<img src="/items/img/item_05.jpg" width="100%">
								<p class="g-nav_item_txt_min">長袖Tシャツ</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/tote-bag/" type="button">
									<img src="/items/img/item_09.jpg" width="100%">
								<p class="g-nav_item_txt_min">バッグ</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/cap/" type="button">
									<img src="/items/img/item_14.jpg" width="100%">
								<p class="g-nav_item_txt_min">キャップ</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/apron/" type="button">
									<img src="/items/img/item_10.jpg" width="100%">
								<p class="g-nav_item_txt_min">エプロン</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/baby/" type="button">
									<img src="/items/img/item_11.jpg" width="100%">
								<p class="g-nav_item_txt_min">ベビー</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/overall/" type="button">
									<img src="/items/img/item_12.jpg" width="100%">
								<p class="g-nav_item_txt_min">つなぎ</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/ladys/" type="button">
									<img src="/items/img/item_07.jpg" width="100%">
								<p class="g-nav_item_txt_min">レディース</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/workwear/" type="button">
									<img src="/items/img/item_13.jpg" width="100%">
								<p class="g-nav_item_txt_min">ワークウェア</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn">
							<a class="" href="/items/category/goods/" type="button">
									<img src="/items/img/item_15.jpg" width="100%">
								<p class="g-nav_item_txt_min">記念品</p>
								</a>
							</div>
						</li>
						<li>
							<div class="item_btn sample">
							<a class="" href="/contact/request.php" type="button">
								<p class="sample_txt">無料で<br> アイテムサンプル
									<br> お届け！
								</p>
								<p class="g-nav_item_txt_min">無料サンプル請求</p>
							</a>
							</div>
						</li>
					</div>
				</ul>
			</li>
			<li class="gnavi_title"><div class="gnavi_ttl">注文ナビ</div>
				<ul>
					<li><a class="order_blue" href="/order/">申し込み</a></li>
					<li><a class="order_blue" href="/order/reorder.php">追加再注文</a></li>
					<li><a href="/delivery/">お届け日を調べる</a></li>
					<li><a href="/guide/orderflow.php">注文の流れ</a></li>
					<li><a href="/order/bigorder/">大口注文について</a></li>
					<li><a href="/guide/bring.php">持ち込み注文について</a></li>
					<li><a href="/order/express/">お急ぎの方へ</a></li>
				</ul>
			</li>
			<li class="gnavi_title"><div class="gnavi_ttl">料金</div>
				<ul>
					<li><a href="/price/estimate.php">カンタン比較見積もり</a></li>
					<li><a href="/guide/">お支払い方法</a></li>
					<li><a href="/price/fee/">プリント価格表</a></li>
					<li><a href="/guide/discount.php">割引プラン一覧</a></li>
				</ul>
			</li>
			<li class="gnavi_title"><div class="gnavi_ttl">プリント</div>
				<ul>
					<li><a href="/design/printing.php">プリント方法</a></li>
					<li><a href="/design/printsize.php">参考プリントサイズ</a></li>
					<li><a href="/design/position.php">参考プリント位置</a></li>
					<li><a href="/design/emb.php">刺繍</a></li>
				</ul>
			</li>
			<li class="gnavi_title"><div class="gnavi_ttl">デザイン</div>
				<ul>
					<li><a href="/design/designguide.php">デザインの入稿・作り方</a></li>
					<li><a href="/design/template_illust.php">イラレ入稿テンプレート</a></li>
					<li><a href="/design/designtemp.php">無料デザイン集</a></li>
					<li><a href="/design/gallery.php">デザインギャラリー</a></li>
					<li><a href="/design/fontcolor.php">インク・フォント</a></li>
					<li><a href="/design/support.php">デザインサポート</a></li>
				</ul>
			</li>
			<li class="gnavi_title hidden-sm-down"><div class="gnavi_ttl">会社紹介</div>
				<ul>
					<li><a href="/corporate/overview.php">会社概要</a></li>
					<li><a href="/userreviews/">お客様レビュー</a></li>
					<li><a href="/app/WP/">スタッフブログ</a></li>
					<li><a href="/app/WP/thanks-blog/">製作実例</a></li>
					<li><a href="/reason/speed.php">短納期の理由</a></li>
					<li><a href="/corporate/transactions.php">特定商法取引</a></li>
					<li><a href="/corporate/privacy-policy.php">プライバシーポリシー</a></li>
				</ul>
			</li>
		</ul>
	</nav>

	<style>
		#nav_sample {
			width: 100%;
			margin: 0 auto;
			position: relative;
			top: -42px;
		}

		#nav_sample ul {
			width: 100%;
			margin: 0px auto;
			list-style: none;
			padding: 0px 200px;
			display: block;
			max-width: 1600px;
		}

		#nav_sample ul li {
			width: 16.66%;
			position: relative;
			font-size: 14px;
			float: left;
		}

		#nav_sample ul li .gnavi_ttl {
			width: 100%;
			margin: 0 auto;
			text-align: center;
			display: block;
			text-decoration: none;
			font-size: 13px;
			font-weight: bold;
			line-height: 45px;
			height: 41px;
			color: #000;
			background: #e2ddd1;
			-moz-transition: .2s;
			-webkit-transition: .2s;
			-o-transition: .2s;
			-ms-transition: .2s;
			transition: .2s;
			border-left: 1px solid #fff;
			border-right: 1px solid #fff;
		}
		#nav_sample ul li a {
			width: 100%;
			margin: 0 auto;
			text-align: center;
			display: block;
			text-decoration: none;
			font-size: 13px;
			font-weight: bold;
			line-height: 45px;
			height: 41px;
			color: #000;
			background: #e2ddd1;
			-moz-transition: .2s;
			-webkit-transition: .2s;
			-o-transition: .2s;
			-ms-transition: .2s;
			transition: .2s;
			border-left: 1px solid #fff;
			border-right: 1px solid #fff;
		}

		#nav_sample ul li .gnavi_ttl:hover {
			background: #EFBC7F;
			color: #000;
		}
		#nav_sample ul li a:hover {
			background: #EFBC7F;
			color: #000;
		}

		#nav_sample ul li .order_blue:hover {
			background: #4abde8;
			color: #fff;
		}

		#nav_sample ul li>ul {
			position: relative;
			display: none;
			padding: 0;
		}

		#nav_sample ul li:hover>ul {
			display: block;
			position: absolute;
		}
		#nav_sample ul .g_nav_item_box:hover>ul {
			display: block;
			position: absolute;
			width: 600%;
			z-index: 1;
		}

		#nav_sample ul li ul li {
			position: relative;
			width: 100%;
			margin: 0 auto;
			float: none;
			z-index: 1;
		}

		#nav_sample ul li ul li a {
			display: block;
			font-weight: normal;
			text-align: left;
			font-size: 13px;
			padding: 0px 9px;
			height: 50px;
			border-left: none;
			background: #f6f5f1;
		}

		.gnavi_back {
			background: #e2ddd1;
			height: 42px;
			display: flex;
			position: relative;
			top: 0;
		}
		#nav_sample ul li:nth-child(1) ul li {
			position: relative;
			width: auto;
			margin: 5px auto;
			float: none;
			z-index: 1;
			height: auto;
		}
		#nav_sample ul li:nth-child(1) ul li a {
			display: inline-block;
			font-weight: normal;
			text-align: center;
			font-size: 13px;
			padding: 0px;
			height: auto;
			border-left: none;
			background: #fff;
			width: 117px;
		}
		.g-nav_item_wrap {
			display: flex;
			width: 100%;
			height: auto;
			background: #f6f5f1;
			flex-wrap: wrap;
			top: -17px;
			position: relative;
			padding: 0 2% 2%;
		}
		.g-nav_item_txt {
			width: 100%;
			text-align: center;
			font-size: 23px;
			font-weight: bold;
			padding: 20px;
			background: #f5f4f1;
		}
		.item_btn {
			width: auto;
			margin: 0;
			background: #fff;
			padding: 5px;
			border: 1px solid #eaeaea;
			box-shadow: 1px 1px 1px #7b7b7b;
			border-radius: 2px;
		}
		.item_btn:hover{
			opacity: .8;
		}
		.g-nav_item_txt_min {
			text-align: center;
			padding: 0;
			font-size: 0.6rem;
			font-weight: normal;
			margin-bottom: -13px;
			display: block;
			margin-top: -10px;
		}
		.sample {
			border: 2px solid #F18B1A;
			height: 100%;
		}
		.sample_txt {
			font-size: 11px;
			margin: 0;
			padding: 28% 0;
			color: #F18B1A;
			line-height: 1.5;
		}
		body > .container-fluid {
			margin-top: -41px;
			padding: 0;
		}
		@media screen and (max-width:1460px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 109px;
			}
		}
		@media screen and (max-width:1400px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 100px;
			}
		}
		
		@media screen and (max-width:1370px) {
			#nav_sample ul {
				width: 100%;
				margin: 0px auto;
				list-style: none;
				padding: 0px 5px;
				display: block;
				max-width: 1600px;
			}
			#nav_sample ul li:nth-child(1) ul li a {
				width: 134px;
			}
		}
		@media screen and (max-width:1230px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 120px;
			}
		}
		@media screen and (max-width:1130px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 118px;
			}
		}
		@media screen and (max-width:1050px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 100px;
			}
		}
		@media screen and (max-width:940px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 90px;
			}
			.sample_txt {
				padding: 22% 0;
			}
		}
		@media screen and (max-width:860px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 82px;
			}
			.sample_txt {
				padding: 10% 0;
			}
		}
		@media screen and (max-width:795px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 124px;
			}
			.sample_txt {
				padding: 22% 0;
			}
		}

		@media screen and (max-width:768px) {
			#nav_sample ul li {
				width: 20%;
				position: relative;
				font-size: 14px;
				float: left;
			}
			#nav_sample ul .g_nav_item_box:hover>ul {
				width: 500%;
			}
			.g-nav_item_wrap {
				top: 0px;
			}

/*
			#nav_sample ul li:nth-child(1) ul li {
				position: relative;
				width: 2000px;
				margin: 0 auto;
				float: none;
				z-index: 1;
			}
*/
			#nav_sample ul li:nth-child(2) ul li {
				position: relative;
				width: 2000px;
				margin: 0 auto;
				float: none;
				z-index: 1;
				right: 105%;
			}
			#nav_sample ul li:nth-child(3) ul li {
				position: relative;
				width: 2000px;
				margin: 0 auto;
				float: none;
				z-index: 1;
				right: 206%;
			}
			#nav_sample ul li:nth-child(4) ul li {
				position: relative;
				width: 2000px;
				margin: 0 auto;
				float: none;
				z-index: 1;
				right: 305%;
			}

			#nav_sample ul li:nth-child(5) ul li {
				position: relative;
				width: 2000px;
				margin: 0 auto;
				float: none;
				z-index: 1;
				right: 404%;
			}
			#nav_sample ul li .gnavi_ttl {
				font-size: 12px;
				line-height: 41px;
			}
		}
		@media screen and (max-width:570px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 112px;
			}
			.item_btn {
				padding: 1px;
			}
			#nav_sample ul li:nth-child(1) ul li a {
				width: 81px;
			}
			.g-nav_item_wrap {
				padding: 0 0% 2%;
			}
			.sample_txt {
				font-size: 10px;
			}
		}
/*
		@media screen and (max-width:480px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 90px;
			}
		}
		@media screen and (max-width:410px) {
			#nav_sample ul li:nth-child(1) ul li a {
				width: 71px;
			}
		}
*/
		

	</style>



	<!--	<div class="navi_back">
		<nav class="btn-group" role="group" aria-label="Button group with nested dropdown">
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				アイテム
			</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
					<div class="dropdown-menu_in">
						<div class="dropdown_ttl_box hidden-sm-down">
							<p>アイテムページでお見積もり出来ます！</p>
						</div>
						<div class="menu_box">
							<div>
								<div class="navi_inner_3">
									<a class="dropdown-item t-shirts_btn" href="/items/category/t-shirts/" type="button">
										<img src="/items/img/item_01.jpg" width="100%">
										<p class="item_txt_min">Tシャツ</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item polo-shirts_btn" href="/items/category/polo-shirts/" type="button">
										<img src="/items/img/item_03.jpg" width="100%">
										<p class="item_txt_min">ポロシャツ</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item sweat_btn" href="/items/category/sweat/" type="button">
										<img src="/items/img/item_02.jpg" width="100%">
										<p class="item_txt_min">スウェット</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item towel_btn" href="/items/category/towel/" type="button">
										<img src="/items/img/item_08.jpg" width="100%">
										<p class="item_txt_min">タオル</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item sportswear_btn" href="/items/category/sportswear/" type="button">
										<img src="/items/img/item_04.jpg" width="100%">
										<p class="item_txt_min">スポーツ</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item outer_btn" href="/items/category/outer/" type="button">
										<img src="/items/img/item_06.jpg" width="100%">
										<p class="item_txt_min">ブルゾン</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item long-shirts_btn" href="/items/category/long-shirts/" type="button">
										<img src="/items/img/item_05.jpg" width="100%">
										<p class="item_txt_min">長袖Tシャツ</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item tote-bag_btn" href="/items/category/tote-bag/" type="button">
										<img src="/items/img/item_09.jpg" width="100%">
										<p class="item_txt_min">バッグ</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item cap_btn" href="/items/category/cap/" type="button">
										<img src="/items/img/item_14.jpg" width="100%">
										<p class="item_txt_min">キャップ</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item apron_btn" href="/items/category/apron/" type="button">
										<img src="/items/img/item_10.jpg" width="100%">
										<p class="item_txt_min">エプロン</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item baby_btn" href="/items/category/baby/" type="button">
										<img src="/items/img/item_11.jpg" width="100%">
										<p class="item_txt_min">ベビー</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item overall_btn" href="/items/category/overall/" type="button">
										<img src="/items/img/item_12.jpg" width="100%">
										<p class="item_txt_min">つなぎ</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item ladys_btn" href="/items/category/ladys/" type="button">
										<img src="/items/img/item_07.jpg" width="100%">
										<p class="item_txt_min">レディース</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item work_btn" href="/items/category/workwear/" type="button">
										<img src="/items/img/item_13.jpg" width="100%">
										<p class="item_txt_min">ワークウェア</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item goods_btn" href="/items/category/goods/" type="button">
										<img src="/items/img/item_15.jpg" width="100%">
										<p class="item_txt_min">記念品</p>
									</a>
								</div>

								<div class="navi_inner_3">
									<a class="dropdown-item sam_btn" href="/contact/request.php" type="button">
										<p class="ttl_item">無料で<br> アイテムサンプル
											<br> お届け！
										</p>
										<p class="item_txt_min">無料サンプル請求</p>
									</a>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				注文ナビ
			</button>
				<div class="dropdown-menu dropdown-list">
					<a class="dropdown-item list-area odr" href="/order/">申し込み</a>
					<a class="dropdown-item list-area odr" href="/order/reorder.php">追加再注文</a>
					<a class="dropdown-item list-area" href="/delivery/">お届け日を調べる</a>
					<a class="dropdown-item list-area" href="/guide/orderflow.php">注文の流れ</a>
					<a class="dropdown-item list-area" href="/order/bigorder/">大口注文について</a>
					<a class="dropdown-item list-area" href="/guide/bring.php">持ち込み注文について</a>
					<a class="dropdown-item list-area" href="/order/express/">お急ぎの方へ</a>
				</div>

			</div>
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				料金
			</button>
				<div class="dropdown-menu dropdown-list">
					<a class="dropdown-item list-area" href="/price/estimate.php">カンタン比較見積もり</a>
					<a class="dropdown-item list-area" href="/guide/">お支払い方法</a>
					<a class="dropdown-item list-area" href="/price/fee/">プリント価格表</a>
					<a class="dropdown-item list-area" href="/guide/discount.php">割引プラン一覧</a>
				</div>
			</div>
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				プリント
			</button>
				<div class="dropdown-menu dropdown-list">
					<a class="dropdown-item list-area" href="/design/printing.php">プリント方法</a>
					<a class="dropdown-item list-area" href="/design/printsize.php">参考プリントサイズ</a>
					<a class="dropdown-item list-area" href="/design/position.php">参考プリント位置</a>
					<a class="dropdown-item list-area" href="/design/emb.php">刺繍</a>
				</div>
			</div>
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				デザイン
			</button>
				<div class="dropdown-menu dropdown-list lastlist">
					<a class="dropdown-item list-area" href="/design/designguide.php">デザインの入稿・作り方</a>
					<a class="dropdown-item list-area" href="/design/template_illust.php">イラレ入稿テンプレート</a>
					<a class="dropdown-item list-area" href="/design/designtemp.php">無料デザイン素材</a>
					<a class="dropdown-item list-area" href="/design/gallery.php">デザインギャラリー</a>
					<a class="dropdown-item list-area" href="/design/fontcolor.php">インク・フォント</a>
					<a class="dropdown-item list-area" href="/design/support.php">デザインサポート</a>
				</div>

			</div>
			<div class="btn-group dropdown global-menu  hidden-sm-down" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					会社紹介
				</button>
				<div class="dropdown-menu dropdown-list">
					<a class="dropdown-item list-area" href="/corporate/overview.php">会社概要</a>
					<a class="dropdown-item list-area" href="/userreviews/">お客様レビュー</a>
					<a class="dropdown-item list-area" href="/app/WP/">スタッフブログ</a>
					<a class="dropdown-item list-area" href="/app/WP/thanks-blog/">製作実例</a>
					<a class="dropdown-item list-area" href="/reason/speed.php">短納期の理由</a>
					<a class="dropdown-item list-area" href="/corporate/transactions.php">特定商取引法</a>
					<a class="dropdown-item list-area" href="/corporate/privacy-policy.php">プライバシーポリシー</a>
				</div>

			</div>
		</nav>
	</div>


	-->
