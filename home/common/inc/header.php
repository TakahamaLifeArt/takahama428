<?php
/**
 * カートの状態取得とセッション開始
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/orders.php';
$order = new Orders();
$salesTaxByOrder = $order->salestax();
$salesTaxByOrder /= 100;
$cartdata = $order->reqDetails();
$totalByOrder = $cartdata['total']*(1+$salesTaxByOrder);
if($cartdata['options']['payment']==3) $totalByOrder = $totalByOrder*(1+_CREDIT_RATE);
$cartAmount = $cartdata['amount'];
//$perone = floor($totalByOrder/$cartAmount);
$cartTotal = floor($totalByOrder);
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


	<nav class="navbar navbar-toggleable-xl fixed-top navbar-light bg-faded">
		<a class="navbar-brand" href="/">
		<img alt="Brand" src="/common/img/header/top_logo.png" class="hidden-xs-down">
		<img alt="Brand" src="/common/img/header/sp_top_logo.png" class="hidden-sm-up">
	</a>
		<a class="navbar-brand" href="/">
		<img alt="Brand" src="/common/img/header/top_boast.png" class="hidden-md-down">
		<!--<img alt="Brand" src="/common/img/header/top_boast.png" class="hidden-sm-up">-->
	</a>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item hidden-sm-down">
				<a href="tel:0120130428" class="nav-link">
				<i class="fa fa-phone" aria-hidden="true"></i>
				<p>
					<em>0120-130-428</em>
					<span>営業時間：平日10:00-18:00</span>
				</p>
			</a>
			</li>
			<li class="nav-item hidden-md-up">
				<a href="tel:0120130428" class="nav-link">
				<img class="img-fluid" alt="TEL" src="/common/img/header/sp_tel.jpg" width="90%">
				<span>電話する</span>
			</a>
			</li>
			<li class="nav-item">
				<a href="/contact/" class="nav-link">
				<img class="img-fluid" alt="Contact us" src="/common/img/header/sp_mail.jpg" width="90%">
				<span>お問合せ</span>
			</a>
			</li>
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
						<p>商品枚数<span id="cart_amount"><?php echo number_format($cartAmount);?></span>枚</p>
						<p>商品金額<span id="cart_total"><?php echo number_format($cartTotal);?></span>円</p>
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
		</ul>
	</nav>

	<div class="navi_back">
		<nav class="btn-group" role="group" aria-label="Button group with nested dropdown">
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				アイテム
			</button>
			<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
				<div class="dropdown-menu_in">
					<div class="dropdown_ttl_box hidden-sm-down">
						<p>アイテム</p>
					</div>
					<div class="menu_box">
						<div class="item_1">
							<div class="navi_inner">
								<a class="dropdown-item" href="/items/category/t-shirts/"><img class="top3" src="/common/img/global/item/sp_item_01.png" width="100%"></a>
								<a href="/items/category/t-shirts/">
									<p class="item_txt"><img src="/common/img/global/go_btm_blue.png">Tシャツ</p>
								</a>
							</div>
							<div class="navi_inner">
								<a class="dropdown-item" href="/items/category/polo-shirts/"><img class="top3" src="/common/img/global/item/sp_item_02.png" width="100%"></a>
								<a href="/items/category/polo-shirts/">
									<p class="item_txt"><img src="/common/img/global/go_btm_blue.png">ポロシャツ</p>
								</a>
							</div>
							<div class="navi_inner">
								<a class="dropdown-item" href="/items/category/towel/"><img class="top3" src="/common/img/global/item/sp_item_03.png" width="100%"></a>
								<a href="/items/category/towel/">
									<p class="item_txt"><img src="/common/img/global/go_btm_blue.png">タオル</p>
								</a>
							</div>
						</div>
						<div class="item_2">
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/sweat/"><img class="item_under" src="/common/img/global/item/sp_item_04.png" width="100%"></a>
								<a href="/items/category/sweat/">
									<p class="item_txt_min">スウェット</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/sportswear/"><img class="item_under" src="/common/img/global/item/sp_item_sports.png" width="100%"></a>
								<a href="/items/category/sportswear/">
									<p class="item_txt_min">スポーツ</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/long-shirts/"><img class="item_under" src="/common/img/global/item/sp_item_longt.png" width="100%"></a>
								<a href="/items/category/long-shirts/">
									<p class="item_txt_min">長袖Tシャツ</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/outer/"><img class="item_under" src="/common/img/global/item/sp_item_05.png" width="100%"></a>
								<a href="/items/category/outer/">
									<p class="item_txt_min">ブルゾン</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/ladys/"><img class="item_under" src="/common/img/global/item/sp_item_lady.png" width="100%"></a>
								<a href="/items/category/ladys/">
									<p class="item_txt_min">レディース</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/tote-bag/"><img class="item_under" src="/common/img/global/item/sp_item_bag.png" width="100%"></a>
								<a href="/items/category/tote-bag/">
									<p class="item_txt_min">バッグ</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/apron/"><img class="item_under" src="/common/img/global/item/sp_item_07.png" width="100%"></a>
								<a href="/items/category/apron/">
									<p class="item_txt_min">エプロン</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/baby/"><img class="item_under" src="/common/img/global/item/sp_item_baby.png" width="100%"></a>
								<a href="/items/category/baby/">
									<p class="item_txt_min">ベビー</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/overall/"><img class="item_under" src="/common/img/global/item/sp_item_08.png" width="100%"></a>
								<a href="/items/category/overall/">
									<p class="item_txt_min">つなぎ</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/workwear/"><img class="item_under" src="/common/img/global/item/item_work.png" width="100%"></a>
								<a href="/items/category/workwear/">
									<p class="item_txt_min">ワークウェア</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/goods/"><img class="item_under" src="/common/img/global/item/sp_item_11.png" width="100%"></a>
								<a href="/items/category/goods/">
									<p class="item_txt_min">記念品</p>
								</a>
							</div>
							<div class="navi_inner_2">
								<a class="dropdown-item" href="/items/category/cap/"><img class="item_under" src="/common/img/global/item/sp_item_12.png" width="100%"></a>
								<a href="/items/category/cap/">
									<p class="item_txt_min">キャップ</p>
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
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
					<div class="dropdown-menu_in">
						<div class="dropdown_ttl_box hidden-sm-down">
							<p>注文ナビ</p>
						</div>
						<div class="menu_box">
							<div class="item_2">
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/order/"><img src="/common/img/global/order/sp_order_01.jpg" width="100%"></a>
									<a href="/order/">
										<p class="item_txt" style="font-size: 14px;font-weight: bold;color: #109ed7;"><img src="/common/img/global/go_btm_blue.png">申し込み</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/order/reorder.php"><img src="/common/img/global/order/sp_order_02.jpg" width="100%"></a>
									<a href="/order/reorder.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">追加再注文</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/order/bigorder/"><img src="/common/img/global/order/sp_order_03.jpg" width="100%"></a>
									<a href="/order/bigorder/">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">大口注文</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/guide/orderflow.php"><img src="/common/img/global/order/sp_order_04.jpg" width="100%"></a>
									<a href="/guide/orderflow.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">注文の流れ</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/guide/bring.php"><img src="/common/img/global/order/sp_order_05.jpg" width="100%"></a>
									<a href="/guide/bring.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">持ち込み</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/guide/faq.php"><img src="/common/img/global/order/sp_order_06.jpg" width="100%"></a>
									<a href="/guide/faq.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">よくある質問</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/order/express/"><img src="/common/img/global/order/sp_order_07.jpg" width="100%"></a>
									<a href="/order/express/">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">お急ぎの方へ</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/contact/request.php"><img src="/common/img/global/order/sp_order_08.jpg" width="100%"></a>
									<a href="/contact/request.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">資料請求</p>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				料金
			</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop3">
					<div class="dropdown-menu_in">
						<div class="dropdown_ttl_box_c hidden-sm-down">
							<p>料金</p>
						</div>
						<div class="menu_box">
							<div class="item_2">
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/price/estimate.php"><img src="/common/img/global/charge/sp_chage_01.jpg" width="100%"></a>
									<a href="/price/estimate.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">10秒見積もり</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/guide/discount.php"><img src="/common/img/global/charge/sp_chage_02.jpg" width="100%"></a>
									<a href="/guide/discount.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">割引プラン</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/guide/"><img src="/common/img/global/charge/sp_chage_03.jpg" width="100%"></a>
									<a href="/guide/">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">お支払いについて</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/price/fee/"><img src="/common/img/global/charge/sp_chage_04.jpg" width="100%"></a>
									<a href="/price/fee/">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">プリント料金案内</p>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				プリント
			</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop4">
					<div class="dropdown-menu_in">
						<div class="dropdown_ttl_box_p hidden-sm-down">
							<p>プリント</p>
						</div>
						<div class="menu_box">
							<div class="item_2">
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/printing.php"><img src="/common/img/global/print/sp_print_01.jpg" width="100%"></a>
									<a href="/design/printing.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">プリント方法</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/printsize.php"><img src="/common/img/global/print/sp_print_02.jpg" width="100%"></a>
									<a href="/design/printsize.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">プリントサイズ</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/position.php"><img src="/common/img/global/print/sp_print_03.jpg" width="100%"></a>
									<a href="/design/position.php ">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">プリント位置</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/emb.php"><img src="/common/img/global/print/sp_print_04.jpg" width="100%"></a>
									<a href="/design/emb.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">刺繍</p>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="btn-group dropdown global-menu" role="group">
				<button id="btnGroupDrop1" type="button" class="btn btn-info_glnavi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				デザイン
			</button>
				<div class="dropdown-menu" aria-labelledby="btnGroupDrop5">
					<div class="dropdown-menu_in">
						<div class="dropdown_ttl_box_d hidden-sm-down">
							<p>デザイン</p>
						</div>
						<div class="menu_box">
							<div class="item_2">
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/designguide.php"><img src="/common/img/global/design/sp_design_01.jpg" width="100%"></a>
									<a href="/design/designguide.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">入稿・作り方</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/template_illust.php"><img src="/common/img/global/design/sp_design_02.jpg" width="100%"></a>
									<a href="/design/template_illust.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">入稿テンプレート</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/designtemp.php"><img src="/common/img/global/design/sp_design_03.jpg" width="100%"></a>
									<a href="/design/designtemp.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">無料素材集</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/fontcolor.php"><img src="/common/img/global/design/sp_design_04.jpg" width="100%"></a>
									<a href="/design/fontcolor.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">インク・フォント</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/gallery.php"><img src="/common/img/global/design/sp_design_05.jpg" width="100%"></a>
									<a href="/design/gallery.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">デザイン実例</p>
									</a>
								</div>
								<div class="navi_inner_3">
									<a class="dropdown-item" href="/design/support.php"><img src="/common/img/global/design/sp_design_06.jpg" width="100%"></a>
									<a href="/design/support.php">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">デザインサポート</p>
									</a>
								</div>
<!--
								<div class="navi_inner_3 hidden-sm-down">
									<a class="dropdown-item" href="//takahama428.secure-decoration.com/create_products/5-6-T-?n=69818603"><img src="/common/img/global/design/sp_design_07.jpg" width="100%"></a>
									<a href="//takahama428.secure-decoration.com/create_products/5-6-T-?n=69818603">
										<p class="item_txt"><img src="/common/img/global/go_btm_orange.png">デザインシミュレーター</p>
									</a>
								</div>
-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</nav>
	</div>
