<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
$_ITEM_ID = isset($_REQUEST['item_id'])? $_REQUEST['item_id'] : 1;
$_CATEGORY_ID = isset($_REQUEST['category_id'])? $_REQUEST['category_id'] : 1;
$_UPDATED = empty($_REQUEST['update'])? 0: $_REQUEST['update'];
$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
$_SESSION['ticket'] = $ticket;
$_version = time();
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb#  website: https://ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="お申し込みフォームからカンタンにオリジナルTシャツがご注文できます。Web上で金額を確認しながら進めるので安心です。対応も早い！割引キャンペーンでオンライン見積の料金よりお安くなるかも？トレーナー・ポロシャツ・オリジナルTシャツの作成・プリントは、東京都葛飾区のタカハマライフアートにお任せ下さい！">
	<meta name="keywords" content="注文,お申し込み,オリジナル,Tシャツ,作成">
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！">
	<meta property="og:type" content="article">
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。">
	<meta property="og:url" content="https://www.takahama428.com/">
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート">
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
	<meta property="fb:app_id" content="1605142019732010">
	<title>お申し込みフォーム ｜ オリジナルTシャツ【タカハマライフアート】</title>
	<link rel="shortcut icon" href="/icon/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/user/js/upload/jquery.fileupload.css" media="screen">
	<link rel="stylesheet" type="text/css" href="/user/js/upload/jquery.fileupload-ui.css" media="screen">
	<link rel="stylesheet" type="text/css" href="/user/css/uploader.css" media="screen">
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" href="./css/animations.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./css/order.css" media="screen">
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>

	<main class="container">
		<ul class="pan hidden-sm-down">
			<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
			<li>お申し込み</li>
		</ul>

		<div class="heading1_wrapper">
			<h1>お申し込み</h1>
		</div>

		<div class="contents">

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="current"><em>アイテム</em></li>
						<li><em>プリント</em></li>
						<li><em>カート</em></li>
					</ol>
				</nav>

				<section id="categories">
					<h2>アイテムカテゴリーを選択</h2>
					<div class="item_top3 row fade">
						<div class="top3_inner col-12 col-sm-4" data-category-id="1">
							<p class="item_txt_o">Tシャツ</p>
							<img class="top3_o first_item" src="/common/img/global/item/sp_item_01.png" width="100%">
						</div>
						<div class="top3_inner col-6 col-sm-4" data-category-id="3">
							<p class="item_txt_o">ポロシャツ</p>
							<img class="top3_o" src="/common/img/global/item/sp_item_02.png" width="100%">
						</div>
						<div class="top3_inner col-6 col-sm-4" data-category-id="8">
							<p class="item_txt_o">タオル</p>
							<img class="top3_o" src="/common/img/global/item/sp_item_03.png" width="100%">
						</div>
					</div>
					<div class="item_other row fade">
						<div class="other_inner col-6 col-sm-3" data-category-id="2">
							<p class="item_txt_o">スウェット</p>
							<img class="item_under" src="/common/img/global/item/sp_item_04.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="4">
							<p class="item_txt_o">スポーツ</p>
							<img class="item_under" src="/common/img/global/item/sp_item_sports.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="13">
							<p class="item_txt_o">長袖Tシャツ</p>
							<img class="item_under" src="/common/img/global/item/sp_item_longt.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="6">
							<p class="item_txt_o">ブルゾン</p>
							<img class="item_under" src="/common/img/global/item/sp_item_05.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="5">
							<p class="item_txt_o">レディース</p>
							<img class="item_under" src="/common/img/global/item/sp_item_lady.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="9">
							<p class="item_txt_o">バッグ</p>
							<img class="item_under" src="/common/img/global/item/sp_item_bag.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="10">
							<p class="item_txt_o">エプロン</p>
							<img class="item_under" src="/common/img/global/item/sp_item_07.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="14">
							<p class="item_txt_o">ベビー</p>
							<img class="item_under" src="/common/img/global/item/sp_item_baby.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="16">
							<p class="item_txt_o">つなぎ</p>
							<img class="item_under" src="/common/img/global/item/sp_item_08.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="12">
							<p class="item_txt_o">記念品</p>
							<img class="item_under" src="/common/img/global/item/sp_item_11.png" width="100%">
						</div>
						<div class="other_inner col-6 col-sm-3" data-category-id="7">
							<p class="item_txt_o">キャップ</p>
							<img class="item_under" src="/common/img/global/item/sp_item_12.png" width="100%">
						</div>
					</div>
				</section>
			</div>

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="current"><em>アイテム</em></li>
						<li><em>プリント</em></li>
						<li><em>カート</em></li>
					</ol>
				</nav>

				<section>
					<h2><strong id="category_name"></strong></h2>
					<div class="search_box">
						<!-- Button trigger modal -->
						<button class="btn search_btn" id="modal_search">
							<i class="fa fa-search" aria-hidden="true"></i>絞り込む
						</button>
						<p class="display_res" id="tag"></p>
					</div>
					<div class="item_cond">
						<select id="sort" class="down_cond">
							<option value="popular" selected>人気順</option>
							<option value="low">価格の低い順</option>
							<option value="high">価格の高い順</option>
							<option value="desc">レビューが多い順</option>
						</select>
						<p class="txt_min">表示件数：<span id="item_count"></span> アイテム</p>
					</div>

					<div class="listitems_top3 row"></div>

					<div class="listitems_other row"></div>
				</section>

				<div class="transition_wrap align-items-center">
					<div class="step_prev hoverable waves-effect">
						<i class="fa fa-chevron-left"></i>戻る
					</div>
				</div>
			</div>

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="current"><em>アイテム</em></li>
						<li><em>プリント</em></li>
						<li><em>カート</em></li>
					</ol>
				</nav>
				<section id="item_info">
					<h2>カラー・枚数</h2>
					<h3><ins>1.</ins>アイテムカラーの指定</h3>
					<div class="pane">
						<div class="color_sele_wrap">
							<div class="color_sele">
								<p class="item_name"></p>
								<p class="thumb_h">アイテムカラー:<span class="note_color"></span>全<span class="num_of_color">0</span>色</p>
								<ul class="color_sele_thumb"></ul>
							</div>
							<div class="item_image_big"><img alt="" src="" width="300"></div>
						</div>

						<div class="sizeprice">
							<h3>
								<ins>2.</ins>サイズと枚数の指定
							</h3>
							<div class="size_sele_wrap">
								<table class="size_table">
									<tbody></tbody>
								</table>
								<div class="btmline">小計<span class="cur_amount">0</span>枚</div>
							</div>
						</div>
					</div>

					<div class="btn_box flex_add">
						<button class="btn add_btn" id="add_item_color"><i class="fa fa-plus mr-1" aria-hidden="true"></i> 別のカラーを追加</button>
					</div>

					<div class="arrow_line">
						<div style="display:inline-block;">合計<span id="tot_amount">0</span>枚</div>
					</div>
				</section>

				<div class="transition_wrap align-items-center">
					<button class="btn btn-info" id="goto_printing">次へ進む</button>
					<div class="step_prev hoverable waves-effect">
						<i class="fa fa-chevron-left"></i>戻る
					</div>
				</div>
			</div>

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="done"><em class="fa fa-check" aria-hidden="true">アイテム</em></li>
						<li class="current"><em>プリント</em></li>
						<li><em>カート</em></li>
					</ol>
				</nav>

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

							</div>
						</form>

						<h3><ins>2.</ins>プリント位置を選択</h3>
						<div class="form-group_2 area">

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

						</div>
						<!--刺繍のみ-->
						<div class="embroidery print_cond">
							<p class="four_t">プリント内容を選択してください。</p>
							<form method="post" action="" class="form-group">
								<div class="emb">
									<label>
										<img src="/order/img/flow/shi_type_01.jpg">
										<p>ネーム刺繍</p>
										<p class="four_t">弊社所有書体の中から 1行で刺繍します。
										</p>
										<input type="radio" value="1" name="emb_opt[]" class="design_opt">
									</label>
								</div>
								<div class="emb">
									<label>
										<img src="/order/img/flow/shi_type_02.jpg">
										<p>デザイン刺繍</p>
										<p class="four_t">オリジナルのデザイン・ 文字を刺繍します。
										</p>
										<input type="radio" value="0" name="emb_opt[]" class="design_opt">
									</label>
								</div>
							</form>
							<p class="note"><span class="red_mark">※</span>3D刺繍の場合は、一箇所＋400円の追加料金をいただきます。</p>
							<p class="note"><span class="red_mark">※</span>ご希望のお客様は、次のページ「デザインに関する要望」の欄に「3D刺繍希望」とご記入ください。</p>
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
							<button class="btn add_btn add_print_area"><i class="fa fa-plus mr-1" aria-hidden="true"></i> プリント箇所を追加</button>
							<button class="hidden btn del_print_area btn-outline-danger waves-effect del_btn_2">上記プリント情報を削除</button>
						</div>
					</div>
				</section>

				<div class="transition_wrap align-items-center">
					<button class="btn btn-info" id="goto_cart">カートに入れる</button>
					<div class="step_prev hoverable waves-effect">
						<i class="fa fa-chevron-left"></i>戻る
					</div>
				</div>
			</div>

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="done"><em class="fa fa-check" aria-hidden="true">アイテム</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">プリント</em></li>
						<li class="current"><em>カート</em></li>
					</ol>
				</nav>

				<section id="cart">
					<h2>カート</h2>

					<!--デザインパターン 1-->
					<div class="cart_box">
						<div class="item_wrap">
							<div class="item_name_box">
								<p>アイテム：<span class="code">5001</span><span class="name"></span></p>
							</div>
							<div class="color_diff">
								<div class="item_info_order">
									<div class="item_color_cart">
										<p class="cart_fb"></p>
										<p class="color_name"></p>
										<img src="" class="thumb">
									</div>
									<table class="size_count">
										<tbody>
											<tr>
												<td class="cart_fb">サイズ</td>
												<td class="cart_fb">枚数</td>
											</tr>
											<tr>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
									<div class="ch_box">
										<button class="btn btn-outline-warning waves-effect ch_btn">変更</button>
										<button class="btn btn-outline-danger waves-effect del_btn">削除</button>
									</div>
								</div>

							</div>

							<div class="item_info_order_3">
								<button class="btn add_btn alter_print"><i class="fa fa-plus mr-1" aria-hidden="true"></i> プリントの変更</button>
							</div>
						</div>

						<div class="item_info_order_2">
							<div>
								<p class="cart_fb">プリント情報</p>
							</div>
							<table class="print_info">
								<tbody>
									<tr>
										<td>前</td>
										<td>2色</td>
										<td>シルクスクリーン
											<br>(通常サイズ)</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="cart_price_min">
							<p>枚数:<span>0</span>枚</p>
							<p>小計金額:<span>0</span>円(税抜)</p>
						</div>

						<button class="add_btn_or btn add_item">同じデザインで<br>別のアイテムを追加</button>
					</div>

					<div class="price_box">
						<p class="total_p">合計：<span>0</span>円(税込)</p>
						<p class="note red_txt hidden"><span class="red_mark">※</span>大口注文割引きが適用されました。</p>
						<p class="note mb-1 inkjet_notice" hidden="hidden"><span class="red_mark">※</span>プリント色が生地より薄い色の場合、記載金額より高くなりますのでご了承ください</p>
						<p class="note"><span class="red_mark">※</span>お見積もりは概算です。デザインの内容によって変更になる場合がございます。</p>
					</div>

					<button class="add_btn_gr btn" id="add_design">別のデザインで<br>アイテムを選ぶ</button>

					<section id="manuscript">
						<h3>デザインデータ入稿</h3>
						<div class="cart_inner">

							<form id="fileupload" name="fileupload" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" enctype="multipart/form-data">
								<div class="fileupload-buttonbar">
									<div>
										<!-- The fileinput-button span is used to style the file input field as button -->
										<span class="btn btn-success fileinput-button">
											<i class="fa fa-plus" aria-hidden="true"></i>
											<span>ファイルを選択...</span>
										<input type="file" name="files[]" multiple>
										</span>
										<!--
										<button type="submit" class="btn btn-primary start fade">
										<i class="fa fa-cloud-upload" aria-hidden="true"></i>
										<span>入稿する</span>
										</button>
										-->
										<!-- The global file processing state -->
										<span class="fileupload-process"></span>
									</div>
									<!-- The global progress state -->
									<div class="fileupload-progress fade">
										<!-- The global progress bar -->
										<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
											<div class="progress-bar progress-bar-success" style="width:0%;"></div>
										</div>
										<!-- The extended global progress state -->
										<div class="progress-extended">&nbsp;</div>
									</div>
								</div>
								<!-- The table listing the files available for upload/download -->
								<table role="presentation" class="table table-striped" id="fileupload-table">
									<tbody class="files"></tbody>
								</table>
							</form>

							<script id="template-upload" type="text/x-tmpl">
								{% for (var i=0, file; file=o.files[i]; i++) { %}
								<tr class="template-upload fade">
									<td>
										<span class="preview"></span>
									</td>
									<td>
										<p class="name">{%=file.name%}</p>
										<strong class="error text-danger"></strong>
									</td>
									<td>
										<p class="size">Processing...</p>
										<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
											<div class="progress-bar progress-bar-success" style="width:0%;"></div>
										</div>
									</td>
									<td>
										{% if (!i && !o.options.autoUpload) { %}
										<button class="btn btn-primary start" hidden disabled>
										<i class="fa fa-cloud-upload" aria-hidden="true"></i>
										<span>アップロード</span>
								</button> {% } %} {% if (!i) { %}
										<button class="btn btn-warning cancel">
										<i class="fa fa-ban" aria-hidden="true"></i>
										<span>キャンセル</span>
								</button> {% } %}
									</td>
								</tr>
								{% } %}
							</script>
							<!-- The template to display files available for download -->
							<script id="template-download" type="text/x-tmpl">
								{% for (var i=0, file; file=o.files[i]; i++) { %}
								<tr class="template-download fade">
									<td>
										<span class="preview">
								{% if (file.thumbnailUrl) { %}

								{% } %}
								</span>
									</td>
									<td>
										<p class="name">
											<span>{%=file.name%}</span>
										</p>
										<span class="path" hidden>{%=file.url%}</span> {% if (file.error) { %}
										<div><span class="label label-danger">Error</span> {%=file.error%}</div>
										{% } else { %}
										<div><span class="label" style="font-size:1.2rem;font-weight:bold;color:#0275d8;">完了</span></div>
										{% } %}
									</td>
									<td>
										<span class="size">{%=o.formatFileSize(file.size)%}</span>
									</td>
									<td>
										{% if (file.deleteUrl) { %}
										<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}' {% } %}>
										<i class="fa fa-trash" aria-hidden="true"></i>
										<span>削除</span>
								</button> {% } else { %}
										<button class="btn btn-warning cancel">
										<i class="fa fa-ban" aria-hidden="true"></i>
										<span>キャンセル</span>
								</button> {% } %}
									</td>
								</tr>
								{% } %}
							</script>


							<p class="ri_txt">最大容量：100MB</p>
							<p class="note">ファイルアップロードできない場合は、下記のファイル転送サービスをご利用ください。</p>
							<div id="upload_link" class="modal_style_line">
								<i class="fa fa-question-circle mr-1" aria-hidden="true"></i>ファイル転送サービス
							</div>

							<h3>デザインに関する要望</h3>
							<textarea id="note_design" class="demand" name="note_design" placeholder="例:前のデザイン「TAKAHAMA」を極太ゴシックで打ち替え , 後ろのデザインC22ブラックで着色"></textarea>
							<p class="note"><span class="red_mark">※</span>文字の打ち替え希望の方はテキストを入力して ください。
							</p>
						</div>
					</section>

					<section id="discount">
						<h3>割引</h3>
						<div id="sale_link" class="modal_style_line">
							<i class="fa fa-question-circle mr-1" aria-hidden="true"></i>割引の説明を見る
						</div>
						<div class="cart_inner">
							<div class="disc_chi">
								<label>
									<input type="checkbox" value="3" name="student">
									学割<span class="red_txt">(3%OFF)</span>
								</label>
								<div class="school_name_form">
									<p class="note school_name"><span class="red_mark">※</span>学校名を入力してください。<span class="req">必須</span></p>
									<input type="text" value="" name="school" placeholder="例：〇〇区△△中学校">
								</div>
							</div>
							<div class="disc_chi">
								<label>
									<input type="checkbox" value="3" name="publish">
									写真掲載割<span class="red_txt">(3%OFF)</span>
								</label>
							</div>
							<p class="note"><span class="red_mark">※</span>WEBに掲載可能な方が対象です。</p>
							<p class="note"><span class="red_mark">※</span>購入後のお客様アンケートご回答と商品写真と感想コメントの投稿が必須となります。</p>
						</div>
					</section>

					<section id="pack">
						<h3>袋詰め</h3>
						<div class="cart_inner">
							<p class="note"><span class="red_mark">※</span>個別包装10枚以上で制作日数にプラス1日いただきます。</p>
							<form method="post" action="">
								<div class="form-group flexwrap">
									<div class="print_position">
										<label>
											<img src="/order/img/flow/sp_order_cart_packing_01.jpg">
											<p>まとめて包装 (<span class="red_txt">無料</span>)</p>
											<input type="radio" value="0" name="pack" checked>
										</label>
									</div>
									<div class="print_position">
										<label>
											<img src="/order/img/flow/sp_order_cart_packing_02.jpg">
											<p>個別包装 (<span class="red_txt">50円/1枚</span>)</p>
											<input type="radio" value="50" name="pack">
										</label>
									</div>
									<div class="print_position">
										<label>
											<img src="/order/img/flow/sp_order_cart_packing_03.jpg">
											<p>個別袋を同封 (<span class="red_txt">10円/1枚</span>)</p>
											<input type="radio" value="10" name="pack">
										</label>
									</div>
								</div>
							</form>
						</div>
					</section>

					<section id="payment">
						<h3>お支払い方法</h3>
						<div class="cart_inner">
							<div class="cashflow modal_style_line">
								<i class="fa fa-question-circle mr-1" aria-hidden="true"></i>ご利用方法を見る
							</div>
							<div class="form-group_2 block">
								<div class="pay_sel">
									<label>
										<input type="radio" value="bank" name="payment" checked>
										銀行振込
									</label>
								</div>
								<div class="pay_sel">
									<label>
										<input type="radio" value="cod" name="payment">
										代金引換 (手数料800円)
									</label>
								</div>
								<div class="pay_sel">
									<label>
										<input type="radio" value="credit" name="payment">
										カード決済
									</label>
								</div>
							</div>
						</div>
					</section>

					<section id="delivery">
						<h3>ご希望納期</h3>
						<div class="cart_inner">
							<div id="ex_form" class="modal_style_line">
								<i class="fa fa-question-circle mr-1" aria-hidden="true"></i>当日発送をご希望の方はこちら
							</div>
							<div class="date_sel">
								<h4>カレンダーから選択してください。</h4>
								<div id="datepick" class="cale_box"></div>
								<p class="note hidden" id="express_info">
									<span class="red_mark">※</span> 特急料金がかかります。(
									<em>翌日仕上げ</em>)
								</p>
								<label for="transport"><input type="checkbox" value="2" name="transport" id="transport">お届け先が、北海道、九州、沖縄、東京離島、島根隠岐郡のいずれかとなる場合はチェックして下さい。</label>
							</div>

							<h4>お時間帯の指定</h4>
							<div class="pull_down">
								<div class="btn-group">
									<select id="deliverytime" name="delitime" class="down_cond">
										<option value="0" selected>指定なし</option>
										<option value="1">午前中</option>
										<option value="3">14:00-16:00</option>
										<option value="4">16:00-18:00</option>
										<option value="5">18:00-20:00</option>
										<option value="6">19:00-21:00</option>
									</select>
								</div>
							</div>

							<div class="deli_date">
								ご希望納期：<span>-</span>月<span>-</span>日
							</div>

							<div id="estimation" class="price_box">
								<p class="total_p">合計：<span></span>円(税込)</p>
								<p class="solo_p">1枚あたり: <span></span>円(税込)</p>
								<p class="note mb-1 inkjet_notice" hidden="hidden"><span class="red_mark">※</span>プリント色が生地より薄い色の場合、記載金額より高くなりますのでご了承ください</p>
								<p class="note mb-1"><span class="red_mark">※</span>お見積もりは概算です。デザインの内容によって変更になる場合がございます。</p>
							</div>
							<button class="btn btn-info adj" id="goto_customer">お客様情報へ進む</button>
						</div>
					</section>
				</section>
			</div>

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="done"><em class="fa fa-check" aria-hidden="true">アイテム</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">プリント</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">カート</em></li>
					</ol>
				</nav>

				<section>
					<h2>お客様情報</h2>
					<button class="add_btn_or_cus btn" id="goto_member">2回目以降のご注文の方はこちら</button>
					<button class="add_btn_gr_cus btn" id="goto_firsttime"><img src="/order/img/flow/sp_order_firstorder.png">初めてのご注文の方はこちら</button>
				</section>

				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<div class="step_prev hoverable waves-effect">
						<i class="fa fa-chevron-left"></i>戻る
					</div>
				</div>
			</div>

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="done"><em class="fa fa-check" aria-hidden="true">アイテム</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">プリント</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">カート</em></li>
					</ol>

				</nav>

				<section id="customer">
					<h2>お客様情報</h2>
					<div class="member hidden">
						<h3>2回目以降のご注文の方</h3>
						<p>前回ご利用時のメールアドレスとパスワードをご入力ください。<br>ログインをすると住所入力が省略できます。また、購入情報は購入履歴から確認できます。</p>
						<form name="pass" class="e-mailer" action="" method="post" onsubmit="return false;">
							<div class="user_pass">
								<li>
									<h3>メールアドレス</h3><input type="text" id="login_email" name="sendto" value="" />
								</li>
								<li>
									<h3>パスワード</h3><input type="password" value="" id="login_pass" name="login_pass" class="e-none" />
								</li>
							</div>
							<div id="login_btn">
								<img src="/order/img/tsuika/sp_login_icon.png">ログイン
							</div>
							<p>
								<span id="resend_pass"><ins class="red_mark">※</ins>パスワードを再発行する</span>
							</p>
							
							<input type="hidden" name="subject" value="パスワードを再発行いたしました">
							<input type="hidden" name="title" value="パスワード再発行">
							<textarea name="summary" hidden>いつもご利用いただき、誠にありがとうございます。新しいパスワードを発行いたしました。</textarea>
							<label hidden>パスワード</label>
							<input type="text" name="newpass" id="newpass" value="" hidden>
							<button type="submit" id="sendmail" hidden></button>
						</form>
					</div>

					<div class="first_time hidden">
						<h3>初めてのご注文の方</h3>
						<p>下記フォームに必要事項をご入力ください。<br>ご入力いただいた情報はSSL暗号通信により保護されています。</p>

						<div class="new_user_pass">
							<ul>
								<li>
									<h3>メールアドレス<span class="req">必須</span></h3>
									<input type="text" id="email" name="email" value="" placeholder="例:aaa@gmail.com" />
								</li>
								<li>
									<h3>新規パスワード<span class="req">必須</span></h3>
									<input type="password" id="pass" name="pass" value="" />
								</li>
								<li>
									<h3>パスワード確認用<span class="req">必須</span></h3>
									<input type="password" id="pass_conf" name="pass_conf" value="" />
									<p class="note"><span class="red_mark">※</span>半角英数字4文字以上16文字以内で、パスワードを設定してください。</p>
								</li>
								<li>
									<h3>お名前<span class="req">必須</span></h3>
									<input type="text" id="customername" name="customername" value="<?php echo $user['customername']; ?>" placeholder="例:高濱　太郎">様
								</li>
								<li>
									<h3>フリガナ<span class="req">必須</span></h3>
									<input type="text" id="customerruby" name="customerruby" value="<?php echo $user['customerruby']; ?>" placeholder="例:タカハマ　タロウ">様
								</li>
								<li>
									<h3>お電話番号<span class="req">必須</span></h3>
									<input type="text" id="tel" name="tel" value="<?php echo $user['tel']; ?>" placeholder="例:08012345678" />
								</li>
							</ul>

							<ul>
								<li>
									<h3 class="login_display">ご住所<span class="req">必須</span></h3>
								</li>

								<li>
									<p>〒<input type="text" name="zipcode" id="zipcode" value="<?php echo $user['zipcode']; ?>" onChange="AjaxZip3.zip2addr(this,'','addr0','addr1');" placeholder="郵便番号" /></p>
									<p><input type="text" name="addr0" id="addr0" value="<?php echo $user['addr0']; ?>" placeholder="都道府県" maxlength="4" /></p>
									<p><input type="text" name="addr1" id="addr1" value="<?php echo $user['addr1']; ?>" placeholder="葛飾区西新小岩1-23-456" maxlength="56" class="restrict" /></p>
									<p><input type="text" name="addr2" id="addr2" value="<?php echo $user['addr2']; ?>" placeholder="マンション・ビル名" maxlength="32" class="restrict" /></p>
								</li>
							</ul>
						</div>

						<div class="transition_wrap align-items-center">
							<button class="btn btn-info" id="confirm_customer">次へ進む</button>
						</div>
					</div>
				</section>

				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<div class="step_prev customer hoverable waves-effect">
						<i class="fa fa-chevron-left"></i>戻る
					</div>

				</div>
			</div>

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="done"><em class="fa fa-check" aria-hidden="true">アイテム</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">プリント</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">カート</em></li>
					</ol>
				</nav>

				<section id="confirm_user">
					<h2>お客様情報</h2>
					<p>ご入力いただいた情報はSSL暗号通信により保護されています。</p>
					<div class="user_pass_confir">
						<ul>
							<li>
								<h3>メールアドレス</h3>
								<p id="conf_email"><span></span></p>
							</li>

							<li>
								<h3>お名前</h3>
								<p id="conf_customername"><span></span>様</p>
							</li>

							<li>
								<h3>フリガナ</h3>
								<p id="conf_customerruby"><span></span>様</p>
							</li>

							<li>
								<h3>お電話番号</h3>
								<p id="conf_tel"><span></span></p>
							</li>

						</ul>

						<ul>
							<li>
								<h3>ご住所</h3>
								<p id="conf_zipcode">〒<span></span></p>
								<p id="conf_addr0"><span></span></p>
								<p id="conf_addr1"><span></span></p>
								<p id="conf_addr2"><span></span></p>
							</li>

							<li>
								<h3 class="opinion">ご意見・ご要望など<span class="any">任意</span></h3>
								<textarea id="note_user" class="demand" name="note_user" placeholder="例:11月1日には使用したいです。"></textarea>
							</li>
						</ul>
					</div>

				</section>

				<div class="transition_wrap align-items-center">
					<button class="btn btn-info" id="confirm_order">最終確認へ進む</button>
					<div class="step_prev conf_user hoverable waves-effect">
						<i class="fa fa-chevron-left"></i>戻る
					</div>
				</div>
			</div>

			<div class="step">
				<nav>
					<ol class="cd-multi-steps text-bottom count">
						<li class="done"><em class="fa fa-check" aria-hidden="true">アイテム</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">プリント</em></li>
						<li class="done"><em class="fa fa-check" aria-hidden="true">カート</em></li>
					</ol>
				</nav>

				<section id="confirm_order">
					<h2>内容確認</h2>

					<div class="final_confir">
						<div class="item_info_final">
							<table class="final_detail">

							</table>
						</div>

						<div class="item_info_final_2">
							<table class="print_info_final">
								<tbody>
									<tr class="tabl_ttl_2">
										<td colspan="3" class="print_total">プリント代</td>
										<td class="print_total_p"><span id="final_printfee">0</span>円</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="subtotal">
							<p>小計<span class="inter" id="order_amount">0</span>枚<span class="inter_2" id="sub_total">0</span>円</p>
						</div>
					</div>

					<div class="final_confir">
						<div class="item_info_final_2">
							<table class="discount_t">
								<tbody>
									<tr>
										<td>割引</td>
										<td></td>
										<td class="txt_righ"><span class="red_txt" id="discount_fee">0</span>円</td>
									</tr>
									<!--
									<tr>
									<td><span id="rank_name"></span>会員割</td>
									<td></td>
									<td class="txt_righ"><span class="red_txt" id="rank_fee"></span>円</td>
									</tr>
									-->
									<tr>
										<td>送料</td>
										<td class="note"><span class="red_mark">※</span>30,000円以上で送料無料</td>
										<td class="txt_righ"><span id="carriage">0</span>円</td>
									</tr>
									<tr class="hidden" id="expressfee_wrap">
										<td>特急料金</td>
										<td></td>
										<td class="txt_righ"><span id="express_fee">0</span>円</td>
									</tr>
									<tr>
										<td>計</td>
										<td></td>
										<td class="txt_righ"><span id="base_price">0</span>円</td>
									</tr>
									<tr>
										<td>消費税</td>
										<td></td>
										<td class="txt_righ"><span id="salestax">0</span>円</td>
									</tr>
									<tr class="bold_t">
										<td>お見積もり合計</td>
										<td></td>
										<td class="big_total txt_righ"><span id="total_estimation">0</span>円</td>
									</tr>
									<tr class="bold_t">
										<td>1枚あたり</td>
										<td></td>
										<td class="txt_righ"><span id="perone">0</span>円</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="final_confir">
						<div class="item_info_final_2">
							<table class="design_t table_info">
								<tbody>
									<tr class="tabl_ttl">
										<td colspan="2">デザイン情報</td>
									</tr>
									<tr>
										<td>入稿データ</td>
										<td id="design_file"></td>
									</tr>
									<tr>
										<td>デザインに関する要望</td>
										<td id="final_note_design"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="final_confir">
						<div class="item_info_final_2">
							<table class="design_t">
								<tbody>
									<tr class="tabl_ttl">
										<td>割引</td>
									</tr>
									<tr>
										<td id="discount_name">なし</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="final_confir">
						<div class="item_info_final_2">
							<table class="design_t">
								<tbody>
									<tr class="tabl_ttl">
										<td>袋詰め</td>
									</tr>
									<tr>
										<td id="pack_name">なし</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="final_confir">
						<div class="item_info_final_2">
							<table class="design_t">
								<tbody>
									<tr class="tabl_ttl">
										<td>お支払い方法</td>
									</tr>
									<tr>
										<td id="payment_name"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="final_confir">
						<div class="item_info_final_2">
							<table class="design_t">
								<tbody>
									<tr class="tabl_ttl">
										<td colspan="2">お届け</td>
									</tr>
									<tr>
										<td>ご希望納期</td>
										<td id="delivery_date"></td>
									</tr>
									<tr>
										<td>お届け時間帯</td>
										<td id="delivery_time"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="final_confir">
						<div class="item_info_final_2">
							<table class="design_t table_info">
								<tbody>
									<tr class="tabl_ttl">
										<td colspan="2">お客様情報</td>
									</tr>
									<tr>
										<td>メールアドレス</td>
										<td id="final_email"></td>
									</tr>

									<tr>
										<td>お名前</td>
										<td id="final_customername"></td>
									</tr>
									<tr>
										<td>フリガナ</td>
										<td id="final_customerruby"></td>
									</tr>
									<tr>
										<td>お電話番号</td>
										<td id="final_tel"></td>
									</tr>
									<tr>
										<td>ご住所</td>
										<td id="final_address"></td>
									</tr>
									<tr>
										<td>備考欄</td>
										<td id="final_note_user"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="order_caution">
						<p class="red_big">まだご注文は確定しておりません。</p>
						<p>制作開始にあたり、お電話でデザインの確認をさせていただきます。<br> 弊社よりお送りする御見積りメールをご確認いただいた後、お電話ください。
						</p>

						<div class="mail_img">
							<div>
								<img src="/order/img/flow/sp_order_confirmation_mail_01.jpg" width="100%">
							</div>
							<div>
								<img src="/order/img/flow/sp_order_confirmation_mail_02.jpg" width="100%">
							</div>
						</div>
						<div class="confir_img">
							<img src="/order/img/flow/sp_order_confirmation_tel.png" width="100%">
						</div>

						<div class="caution_child">
							<p class="mid_txt">メールの受信設定</p>
							<p>迷惑メールの設定により、弊社からのメールが届かない場合があります。<br> ドメイン指定をして<span class="red_txt">「info@takahama428.com」</span>を受信出来るように設定してください。
							</p>
						</div>

						<div id="user_policy" class="modal_style">
							<i class="fa fa-question-circle mr-1" aria-hidden="true"></i>ご利用規約を見る
						</div>
						<label for="agree"><input type="checkbox" id="agree">ご利用規約を確認して同意しました。</label>
					</div>

				</section>

				<form id="orderform" name="orderform" method="post" action="./ordercomplete.php" onSubmit="return false;">
					<input type="hidden" name="ticket" class="ticket" value="<?php echo $ticket;?>">
					<input type="hidden" name="design" value="">
					<input type="hidden" name="item" value="">
					<input type="hidden" name="sum" value="">
					<input type="hidden" name="detail" value="">
					<input type="hidden" name="option" value="">
					<input type="hidden" name="user" value="">
				</form>
				<div class="transition_wrap align-items-center">
					<button class="btn btn-info" id="order" disabled>この内容で申し込む</button>
					<div class="step_prev hoverable waves-effect">
						<i class="fa fa-chevron-left"></i>戻る
					</div>
				</div>
			</div>

			<div class="smooth-scroll-btn">
				<a href="#top" class="btn-floating btn-large red">
					<i class="fa fa-arrow-up"></i>
				</a>
			</div>
		</div>
	</main>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script type="text/javascript">
		var _ITEM_ID = <?php echo $_ITEM_ID; ?>;
		var _CATEGORY_ID = <?php echo $_CATEGORY_ID; ?>;
		var _UPDATED = <?php echo $_UPDATED; ?>;
		var IMG_PATH = '<?php echo _IMG_PSS; ?>';

	</script>
	<script src="https://ajaxzip3.github.io/ajaxzip3.js" async></script>
	<script src="https://doozor.bitbucket.io/calendar/datepick_calendar.min.js"></script>
	<script src="https://doozor.bitbucket.io/email/e-mailform.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
	<script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
	<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
	<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
	<script src="/user/js/upload/vendor/jquery.ui.widget.js"></script>
	<script src="/user/js/upload/jquery.iframe-transport.js?v=<?php echo $_version;?>"></script>
	<script src="/user/js/upload/jquery.fileupload.js?v=<?php echo $_version;?>"></script>
	<script src="/user/js/upload/jquery.fileupload-process.js?v=<?php echo $_version;?>"></script>
	<script src="/user/js/upload/jquery.fileupload-image.js?v=<?php echo $_version;?>"></script>
	<script src="/user/js/upload/jquery.fileupload-validate.js?v=<?php echo $_version;?>"></script>
	<script src="/user/js/upload/jquery.fileupload-ui.js?v=<?php echo $_version;?>"></script>
	<script src="./js/upload/main.js?v=<?php echo $_version;?>"></script>
	<script src="/common/js/api.js"></script>
	<script src="./js/pagetransition.js"></script>
	<script src="./js/orderlib.js?v=<?php echo $_version;?>"></script>
	<script src="./js/order.js?v=<?php echo $_version;?>"></script>
	<script src="./js/dialog.js"></script>
</body>

</html>
