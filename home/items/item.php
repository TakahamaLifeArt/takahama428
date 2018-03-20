<?php
$_PAGE_ITEMDETAIL=true;
require_once $_SERVER["DOCUMENT_ROOT"].'/php_libs/pageinfo.php';
$_version = time();
?>
<!DOCTYPE html>
<html lang="ja">
<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content=<?php echo $itemname. '（'.$itemcode. '）'; ?>"の詳細ページです。1枚～大量のプリントまで、オリジナルTシャツの作成・プリントは、東京都葛飾区のタカハマライフアートにお任せ下さい！団体やグループなどで着用し、文化祭、体育祭のイベントを盛り上げてください。" />
	<meta name="keywords" content="<?php echo $categoryname; ?>,オリジナル<?php echo $categoryname; ?>,作成,プリント,東京,即日,最短" />
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>
		<?php echo $itemname.' '.$itemcode; ?>　|　オリジナル
		<?php echo $categoryname; ?>
	</title>
	<link rel="shortcut icon" href="/icon/favicon.ico">
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" href="/common/css/printposition_responsive.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/common/js/lightbox/jquery.lightbox-0.5.css" />
	<link rel="stylesheet" type="text/css" href="/items/css/items_style_responsive.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/items/css/item.css" media="screen" />
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>

	<div id="container">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/sidenavi.php"; ?>
		<div class="contents">
			<div id="topicpath">
				<a href="/">オリジナルＴシャツ屋ＴＯＰ</a> >
				<a href="/items/category/<?php echo $categorykey;?>/">
					<?php echo $categoryname;?>
				</a> >
				<?php echo $itemname; ?>
			</div>
			<?php if($itemcode=="085-cvt") {?>
			<div class="name">
				<h2>085CVT ヘビーウェイトTシャツ</h2>
			</div>
			<div><img src="/items/img/t-shirts/des_085cvt_01.jpg" width="100%"></div>
			<div><img src="/items/img/t-shirts/des_085cvt_02.jpg" width="100%"></div>
			<?php } ?>
			<div id="item_left">
				<div id="item_image">
					<?php echo $curthumb; ?>
					<div class="dotted"></div>
					<a class="info_icon" href="#size">サイズ目安</a>
					<ul id="item_image_notes">
						<li id="notes_color">
							<?php echo $curcolor; ?>
						</li><br>
						<li id="notes_size">展開サイズ <span id="size_span"><?php echo $cursize; ?></span></li>
						<li id="size_info"></li>
					</ul>
				</div>

				<div id="item_thumb">
					<div id="item_colors">
						<p class="thumb_h">Color<span id="num_of_color">全<?php echo $color_count; ?>色</span></p>
						<ul id="color_thumb">
							<?php echo $thumbs_min; ?>
						</ul>
					</div>
				</div>

				<div id="model">
					<?php echo $model_gallery; ?>
				</div>

				<div id="item_style">
					<?php echo $style_gallery; ?>
				</div>

			</div>

			<div id="item_right">
				<?php echo $right_column;?>
			</div>

			<div class="contents-lv2">
				<h2 id="size">サイズ目安</h2>
				<div class="dotted"></div>
				<div id="size_detail">
					<?php echo $itemsize_table; ?>
				</div>
			</div>

			<div class="contents-lv2 printarea_wrap">
				<h2 id="printarea">プリント可能範囲</h2>
				<div class="dotted"></div>
				<p>サイズ対応表とプリント可能範囲・プリント最大サイズについてご説明いたします。</p>
				<div class="flex-container flex-around">
					<?php echo $printAreaImage; ?>
				</div>
				<div class="flex-container">
					<?php echo $printSizeTable; ?>
				</div>
				<div class="footnote">
					<?php echo $footNote; ?>
				</div>
				<div class="footnote">
					<p>※シルクスクリーンプリントで幅27cm×高さ35cm以上のサイズの場合、版代・プリント代が割り増しになります。 詳しくは
						<a href="/price/fee/">こちら</a>をご覧ください。</p>
				</div>
				<div class="printtype">
					<p>対応プリント：</p>
					<?php echo $printMethod; ?>
				</div>
			</div>

			<div class="up"><a class="up_icon" href="#topicpath">ページ上部へ</a></div>

			<div class="contents-lv2">
				<h2 id="howmuch">いくらになるか、計算してみる。</h2>
				<div class="dotted"></div>
				<h3 id="hmguide">枚数やプリント位置などを指定して、<span>お値段を計算します。</span>※概算となります。</h3>

				<section id="item_info">
					<h2>カラー・枚数</h2>
					<h3><ins>1.</ins>アイテムカラーの指定</h3>

					<?php echo $DOC; ?>

					<div class="btn_box flex_add">
						<button class="btn add_btn waves-effect waves-light" id="add_item_color"><i class="fa fa-plus mr-1" aria-hidden="true"></i> 別のカラーを追加</button>
					</div>

					<div class="arrow_line">
						<div style="display:inline-block;">合計<span id="tot_amount">2</span>枚</div>
					</div>
				</section>

				<section id="printing">
					<h2>プリント</h2>
					<div class="form-group_top">
						<p class="print_none"><label><input type="checkbox" name="noprint" id="noprint" value="1"> プリントなしで購入する</label></p>
						<p class="note"><span class="red_mark">※</span>プリントなしの場合1割増の料金です。</p>
					</div>
					<div class="pane">
						<h3><ins>1.</ins>プリントする面を選択</h3>
						<form method="post" action="">
							<div class="form-group pos">
								<div class="print_position"><label><img src="https://takahamalifeart.com/weblib/img/printpattern/t-shirts/normal-tshirts/base_front.svg"><p>前面</p><input type="radio" name="face[]" class="face" value="front" checked=""></label></div>
								<div class="print_position"><label><img src="https://takahamalifeart.com/weblib/img/printpattern/t-shirts/normal-tshirts/base_back.svg"><p>背面</p><input type="radio" name="face[]" class="face" value="back"></label></div>
								<div class="print_position"><label><img src="https://takahamalifeart.com/weblib/img/printpattern/t-shirts/normal-tshirts/base_side.svg"><p>側面</p><input type="radio" name="face[]" class="face" value="side"></label></div>
							</div>
						</form>

						<h3><ins>2.</ins>プリント位置を選択</h3>
						<div class="form-group_2 area"><img alt="front" src="https://takahamalifeart.com/weblib/img/printpattern/t-shirts/normal-tshirts/base_front.svg">
							<p class="pos_selector_wrap"><select class="down_cond"><option value="前" selected="">前</option><option value="右胸">右胸</option><option value="左胸">左胸</option><option value="右すそ">右すそ</option><option value="左すそ">左すそ</option><option value="前すそ">前すそ</option></select></p>
						</div>

						<h3><ins>3.</ins>プリント色数を選択</h3>
						<form method="post" action="">
							<div class="form-group_2">
								<div class="print_color">
									<label><input type="radio" value="1" name="ink[]" class="ink" checked>1色</label>
								</div>
								<div class="print_color">
									<label><input type="radio" value="2" name="ink[]" class="ink">2色</label>
								</div>
								<div class="print_color">
									<label><input type="radio" value="3" name="ink[]" class="ink">3色</label>
								</div>
								<div class="print_color">
									<label><input type="radio" value="4" name="ink[]" class="ink">4色以上</label>
								</div>
							</div>
						</form>

						<h3><ins>4.</ins>プリント方法を選択</h3>
						<p class="print_link modal_style_line">
							<i class="fa fa-question-circle mr-1" aria-hidden="true"></i>プリント方法の説明を見る
						</p>

						<div class="pull_down method">
							<p>
								<select class="print_selector down_cond">
									<option value="recommend" selected="">おまかせ</option>
									<option value="silk">シルクスクリーン</option>
									<option value="inkjet">インクジェット</option>
									<option value="emb">刺繍</option>
								</select>
							</p>
						</div>
						
						<!--刺繍のみ-->
						<div class="embroidery print_cond">
							<p class="four_t">プリント内容を選択してください。</p>
							<form method="post" action="" class="form-group">
								<div class="emb">
									<label>
										<img src="/order/img/flow/shi_type_01.jpg">
										<p>ネーム刺繍</p>
										<p class="four_t">弊社所有書体の中から 1行で刺繍します。</p>
										<input type="radio" value="1" name="emb_opt[]" class="design_opt">
									</label>
								</div>
								<div class="emb">
									<label>
										<img src="/order/img/flow/shi_type_02.jpg">
										<p>デザイン刺繍</p>
										<p class="four_t">オリジナルのデザイン・ 文字を刺繍します。</p>
										<input type="radio" value="0" name="emb_opt[]" class="design_opt">
									</label>
								</div>
							</form>
						</div>

						<h3 class="print_cond_note hidden"><ins>5.</ins>プリントサイズの選択</h3>
						<!--シルク-->
						<form method="post" action="" class="form-group silk print_cond">
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_silk_01.png">
									<p>通常</p>
									<p class="four_t">w27×H35cm以内</p>
									<input type="radio" value="0" name="silk_size[]" class="design_size">
								</label>
							</div>
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_silk_02.png">
									<p>ジャンボ</p>
									<p class="four_t">w32×H43cm以内</p>
									<input type="radio" value="1" name="silk_size[]" class="design_size">
								</label>
							</div>
						</form>

						<!--デジテン,IJ,カティング-->
						<form method="post" action="" class="form-group other_print print_cond">
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_dic_01.png">
									<p>小</p>
									<p class="four_t">w10×H10cm以内</p>
									<input type="radio" value="2" name="other_size[]" class="design_size">
								</label>
							</div>
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_dic_02.png">
									<p>中</p>
									<p class="four_t">w18×H27cm以内</p>
									<input type="radio" value="1" name="other_size[]" class="design_size">
								</label>
							</div>
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_dic_03.png">
									<p>大</p>
									<p class="four_t">w27×H38cm以内</p>
									<input type="radio" value="0" name="other_size[]" class="design_size">
								</label>
							</div>
						</form>

						<!--刺繍-->
						<form method="post" action="" class="form-group embroidery print_cond">
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_shi_01.png">
									<p>小</p>
									<p class="four_t">w10×H10cm以内</p>
									<input type="radio" value="2" name="emb_size[]" class="design_size">
								</label>
							</div>
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_shi_02.png">
									<p>中</p>
									<p class="four_t">w18×H18cm以内</p>
									<input type="radio" value="1" name="emb_size[]" class="design_size">
								</label>
							</div>
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_shi_03.png">
									<p>大</p>
									<p class="four_t">w25×H25cm以内</p>
									<input type="radio" value="0" name="emb_size[]" class="design_size">
								</label>
							</div>
						</form>

						<!--タオル-->
						<form method="post" action="" class="form-group silk_towel print_cond">
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_silk_01.png">
									<p>通常</p>
									<p class="four_t">w27×H35cm以内</p>
									<input type="radio" value="0" name="silk_towel_size[]" class="design_size">
								</label>
							</div>
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_silk_02.png">
									<p>ジャンボ</p>
									<p class="four_t">w32×H43cm以内</p>
									<input type="radio" value="1" name="silk_towel_size[]" class="design_size">
								</label>
							</div>
							<div class="print_size">
								<label>
									<img src="/order/img/flow/sp_order_printsize_silk_03.png">
									<p>スーパージャンボ</p>
									<p class="four_t">w30×H52cm以内</p>
									<input type="radio" value="2" name="silk_towel_size[]" class="design_size">
								</label>
							</div>
						</form>

						<p class="note print_cond_note hidden"><span class="red_mark">※</span>プリント箇所やアイテムサイズにより、ご希望のサイズに対応できない場合もございます。</p>

						<div class="price_box">
							<p class="total_p">合計：<span>0</span>円(税込)</p>
							<p class="solo_p">1枚あたり: <span>0</span>円(税込)</p>
							<p class="note mb-1 inkjet_notice" hidden="hidden"><span class="red_mark">※</span>プリント色が生地より薄い色の場合、記載金額より高くなりますのでご了承ください</p>
							<p class="note"><span class="red_mark">※</span>お見積もりは概算です。デザインの内容によって変更になる場合がございます。</p>
						</div>

						<!--プリント方法をおまかせで選択した場合、合計料金下に表示-->
						<div class="price_box_2">
							<p class="print_re">合計金額は<span></span>で計算されました</p>
							<p class="solo_p">お客様のプリント条件に最適な最安価のプリント方法を適用しています。</p>
							<div class="print_link modal_style">
								<i class="fa fa-question-circle mr-1" aria-hidden="true"></i>プリント方法の説明を見る
							</div>
							<p class="note mb-1 inkjet_notice" hidden="hidden"><span class="red_mark">※</span>プリント色が生地より薄い色の場合、記載金額より高くなりますのでご了承ください</p>
							<p class="note"><span class="red_mark">※</span>お見積もりは概算です。デザインの内容によって変更になる場合がございます。</p>
						</div>
						
						<div class="btn_box flex_add">
							<button class="btn add_btn add_print_area waves-effect waves-light"><i class="fa fa-plus mr-1" aria-hidden="true"></i> プリント箇所を追加</button>
							<button class="hidden btn del_print_area btn-outline-danger waves-effect del_btn_2">上記プリント情報を削除</button>
						</div>

					</div>
				</section>

				<div id="order_wrap">
					<p id="orderguide"><span>お見積もり金額について</span><br>デザイン、ボディのカラー・サイズ・素材により、表示されているお見積もり金額と別のプリント方法でご提案させていただくこともございますので、お見積もり金額がお打ち合わせ後変わることがございます。</p>
					<div id="orderbtn_wrap">
						<form name="f1" action="/order/" method="post">
							<input type="hidden" name="item_id" value="<?php echo $data['itemid']; ?>">
							<input type="hidden" name="category_id" value="<?php echo $_PAGE_CATEGORYID; ?>">
							<input type="hidden" name="update" value="3">
							<div id="btnOrder" class="order_btn"><img src="/common/img/home/main/sp_go_icon.png" width="40px" style="padding-right: 12px;padding-bottom: 5px;">お申し込み</div>
						</form>
					</div>
				</div>

				<img src="/common/img/flow_s.png" class="flow_s" width="100%" height="100%">
				<div class="flow_1_butt">
					<a class="button1 hidden-xs-down" href="/contact/">お問い合わせはこちら</a>
				</div>
			</div>
		</div>
	</div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<script type="text/javascript">
		var _CAT_ID = <?php echo $_PAGE_CATEGORYID; ?>;
		var _CAT_KEY = '<?php echo $categorykey; ?>';
		var _CAT_NAME = '<?php echo $categoryname; ?>';
		var _ITEM_ID = <?php echo $data['itemid']; ?>;
		var _ITEM_CODE = '<?php echo $itemcode; ?>';
		var _ITEM_NAME = '<?php echo $itemname; ?>';
		var _POS_ID = <?php echo $posid; ?>;
		var IMG_PATH = '<?php echo _IMG_PSS; ?>';
	</script>
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script type="text/javascript" src="/common/js/lightbox/jquery.lightbox-0.5.js"></script>
	<script type="text/javascript" src="/items/js/jquery.changephoto.js"></script>
	<script type="text/javascript" src="/items/js/jquery.tableselect.js"></script>
	<script type="text/javascript" src="/common/js/api.js"></script>
	<script type="text/javascript" src="/order/js/orderlib.js"></script>
	<script type="text/javascript" src="/items/js/estimate_sole.js?v=<?php echo $_version;?>"></script>
</body>
</html>
