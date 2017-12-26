<?php 
require_once dirname(__FILE__).'/designcreator/php_libs/initpage.php';
require_once dirname(__FILE__).'/../php_libs/fontcolors.php';
$info = new Fontcolors();

/*
*	ink list
*/
$inkcolors = Fontcolors::getInkcolors();
$cols = 0;
$inkcolor_table = '<table class="inkcolor_table"><tbody><tr>';
foreach($inkcolors as $key=>$val){
	if($key=='c48') continue;	/* 蛍光ブルーを除外 */
	if($cols!=0 && !($cols%5)) $inkcolor_table .= '</tr><tr>';
	if($key=="c41" || $key=="c42") $src = $key."_w200";
	else $src = $key;
	if($key=='c21'){
		$inkcolor_table .= '<td><img alt="'.$val.'" src="./designcreator/img/inkcolor/'.$src.'.png" width="122" class="b1" /><p>'.$key.' '.$val.'</p></td>';
	}else{
		$inkcolor_table .= '<td><img alt="'.$val.'" src="./designcreator/img/inkcolor/'.$src.'.png" width="124" /><p>'.$key.' '.$val.'</p></td>';
	}
	$cols++;
}
$inkcolor_table .= '</tr></tbody></table>';


/*
*	cuttingsheet list
*/
$cuttingcolors = Fontcolors::getCuttingcolors();
$cols = 0;
$cuttingsheet_table = '<table class="inkcolor_table"><tbody><tr>';
foreach($cuttingcolors as $key=>$val){
	$code = explode("-", $key);
	if($cols!=0 && !($cols%5)) $cuttingsheet_table .= '</tr><tr>';
	$cuttingsheet_table .= '<td><img alt="'.$val.'" src="./designcreator/img/cuttingsheet/'.$key.'.png" width="124" /><p>'.$code[0].'<br>'.$val.'</p></td>';
	$cols++;
}
$cuttingsheet_table .= '</tr></tbody></table>';


/*
*	font list
*/
$hash = $info->getHash();
$fonttype = $info->getFonttype();
$fontnote = $info->getFontnote();
$fontname = $info->getFontname();

foreach($hash as $key=>$val){
	if($key=='ja'){
		$fonthead = '<div class="title_wrapper" id="font_ja"><h3>和文フォント<span>Japanese fonts library</span></h3><a href="/design/fontcolor.php#font_en" class="sub">英文フォントへ</a></div>';
		$fonthead .= '<p class="note"><span>※</span> 商品代+印刷代が30,000円以上の場合、3種類まで打替えフォント無料にて承ります。</p>';
		$fonthead .= '<p class="note"><span>※</span>日本語、英数字ともに可</p>';
		$target = 'font_ja';
		$target_name = '和文';
	}else{
		$fonthead = '<div class="title_wrapper" id="font_en"><h3>英文フォント<span>English fonts library</span></h3><a href="/design/fontcolor.php#font_ja" class="sub">和文フォントへ</a></div>';
		$fonthead .= '<p class="note"><span>※</span> 商品代+印刷代が30,000円以上の場合、3種類まで打替えフォント無料にて承ります。</p>';
		$fonthead .= '<p class="note"><span>※</span>英数字のみ</p>';
		$target = 'font_en';
		$target_name = '英文';
	}
	$anchor = '<ul class="anchor"><li>フォントタイプ：</li>';
	$fontlist = '';
	for($i=0; $i<count($val); $i++){
		$anchor .= '<li><a href="#'.$key.$val[$i].'">'.$fonttype[$val[$i]].'</a></li>';

		$fontlist .= '<div class="font_inner">';
		$fontlist .= '<h4 class="heading4" id="'.$key.$val[$i].'">'.$fonttype[$val[$i]].'<a href="#'.$target.'">'.$target_name.'のトップへ</a></h4>';
		$fontlist .= '<dl class="list">';
		$web_path = '/img/font/'.$key.DIRECTORY_SEPARATOR.$val[$i].DIRECTORY_SEPARATOR;
		$tempfile_path = dirname(__FILE__).$web_path.'*.png';
		foreach (glob("$tempfile_path") as $tempfile) {
			$font = basename($tempfile, '.png');
			if(isset($fontnote[$font])){ 
				$note = '<p class="note"><span>※</span> '.$fontnote[$font].'</p>'; 
			}else{
				$note = '';
			}
			$fontlist .= '<dt>'.$fontname[$font].'</dt><dd><img src=".'.$web_path.$font.'.png" alt="'.$fontname[$font].'" />'.$note.'</dd>';
		}
		$fontlist .= '</dl>';
		$fontlist .= '</div>';
	}

	$anchor .= '</ul>';
	$result_font .= $fonthead.$anchor.$fontlist;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="インクカラーとフォントはこちらでご確認ください。オリジナルTシャツをデザインするときにお好きなインクカラーとフォントを選べます。ご自分のオリジナルデザインに合うインクの色とフォントを、サンプルからお探しください。" />
	<meta name="keywords" content="インク,フォント,プリントカラー,オリジナル,Tシャツ,東京" />

	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！">
	<meta property="og:type" content="website">
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。">
	<meta property="og:url" content="https://www.takahama428.com/">
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート">
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
	<meta property="fb:app_id" content="1605142019732010">
	<title>インクカラーとフォント | オリジナルTシャツ【タカハマライフアート】</title>
	<link rel="shortcut icon" href="/icon/favicon.ico">
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>

	<link rel="stylesheet" type="text/css" href="/design/designcreator/css/color_scrolly.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/design/designcreator/css/itemcolor.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/design/designcreator/css/imgeditor.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/design/designcreator/css/fontlist.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/design/designcreator/css/inkcolor.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/design/designcreator/css/creator.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./css/fontcolor.css" media="screen" />
	<script type="text/javascript">
		var preload_images = "<?php echo implode(',', $preloaddata); ?>";

	</script>
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>


	<div id="container">

		<div class="contents">
			<ul class="pan">
				<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
				<li><a href="/design/designguide.html">デザインの作り方</a></li>
				<li>インクカラーとフォント</li>
			</ul>

			<div class="heading1_wrapper">
				<h1>インクカラーとフォント</h1>
			</div>

			<div id="navi1">
				<ul class="navi">
					<li class="act">
						<p>シミュレーション</p>
					</li>
					<li><a href="#navi2">インク一覧</a></li>
					<li><a href="#navi3">カッティングシート一覧</a></li>
					<li><a href="#navi4" class="lastchild">フォント一覧</a></li>
				</ul>
			</div>

			<h2 class="heading">フォントやインクカラー、商品カラーを変えてお試しいただけます</h2>
			<p class="note"><span>※</span>印刷インク・画面の都合上、多少色味が異なる場合がございます。 (印刷カッティングシートのカラーはお試しできません。)
			</p>

			<!-- start design creator  -->
			<div id="content_wrapper" class="clearfix">
				<div id="left_side">

					<div class="itemcolor_area">
						<p class="current_value step_label">商品の色を選択:<span id="current_color_name"></span></p>
						<div class="accessible_news_slider item_color_slider" id="slider1">
							<div class="back_slider"></div>
							<div class="next_slider"></div>
							<div class="slider_wrapper">
								<?php echo $slider; ?>
							</div>
						</div>
					</div>

					<div id="view_area">
						<div class="image_wrapper">
							<div id="designloader">
								<img id="display_design" alt="Loading..." src="" width="310" />
								<img id="back_design" alt="アイテム" src="./designcreator/img/blank.gif" width="1000" />
							</div>

							<div id="imgloader">
								<div id="dragarea_wrapper">
									<div id="drag_cont"></div>
									<div id="drag_cont_back"></div>
								</div>
							</div>
						</div>
					</div>

					<form name="downloader" action="./download/" method="post" onSubmit="return false;">
						<input type="hidden" name="downloadfile" value="" />
						<input type="hidden" name="act" value="done" />
					</form>

				</div>

				<div id="right_side">

					<div id="control_area">

						<div class="ctrl_content clearfix">

							<div id="colorpalette"></div>

							<div id="fontpalette"></div>

							<div id="iteminfo_wrap">
								<p>Editor</p>
							</div>

							<div id="accordion_wrap">
								<form action="" id="editform_0" name="editform_0" onSubmit="return false;">

									<table class="text_controlbox">
										<tr>
											<td colspan="2">
												<textarea id="printtext_0" class="printtext" name="printtext" rows="3" cols="20">T-shirts</textarea>
												<p class="apply_wrap">
													<input type="button" value="文字を変更する" id="apply" />
												</p>
											</td>
										</tr>
										<tr>
											<td class="label">フォント</td>
											<td>
												<select class="fontfamily" name="fontfamily" size="1">
														<option value="pop/C018016D" selected="selected">クーパー</option>
														<optgroup label="和文フォント">
															<option value="jabasic">和　基本</option>
															<option value="jabrush">和　純和風</option>
															<option value="japop">和　ポップ</option>
															<option value="jaothers">和　その他</option>
														</optgroup>
														<optgroup label="英文フォント">
															<option value="basic">英　基本</option>
															<option value="art">英　アート</option>
															<option value="impact">英　インパクト</option>
															<option value="pop">英　ポップ</option>
															<option value="sports">英　スポーツ</option>
															<option value="others">英　その他</option>
														</optgroup>
													</select>
											</td>
										</tr>
										<tr>
											<td class="label">インクの色</td>
											<td>
												<div class="font_color_wrap">
													<div id="fontcolor_0" class="font_color"></div>
												</div>
												<p id="ink_name_0" class="ink_name">ネイビー</p>
											</td>
										</tr>
									</table>

									<div class="text_sliderbox">
										<p>
											<label for="ratio_0">文字のズーム&nbsp;:</label>
											<input type="text" id="ratio_0" size="4" value="100" />%
										</p>
										<div id="zoom_0"></div>
									</div>

								</form>
							</div>

						</div>

					</div>

					<div id="dialog">&nbsp;</div>
				</div>

			</div>
			<!-- end design creator -->

			<div id="navi2">
				<ul class="navi">
					<li><a href="#navi1">シミュレーション</a></li>
					<li class="act">
						<p>インク一覧</p>
					</li>
					<li><a href="#navi3">カッティングシート一覧</a></li>
					<li><a href="#navi4" class="lastchild">フォント一覧</a></li>
				</ul>
			</div>

			<div class="wrap" id="inclist">
				<h2 class="heading">インクカラーの見本50色です。<br>デザインには、どの色が必要ですか？</h2>
				<p class="note"><span>※</span> 印刷インク・画面の都合上、多少色味が異なる場合がございます。</p>
				<!-- <?php echo $inkcolor_table; ?>  -->

				<div class="thumbnails">
					<div class="span4">
						<div class="thumbnail">
							<img alt="ブラック" src="./designcreator/img/inkcolor/c22.png" width="124" />
							<span class="cl">c22 ブラック</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ダークグレー" src="./designcreator/img/inkcolor/c23.png" width="124" />
							<span class="cl">c23 ダークグレー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ライトグレー" src="./designcreator/img/inkcolor/c24.png" width="124" />
							<span class="cl">c24 ライトグレー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ホワイト" src="./designcreator/img/inkcolor/c21.png" width="122" class="b1" style="border: 1px solid #ddd;" />
							<span class="cl">c21 ホワイト</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ワインレッド" src="./designcreator/img/inkcolor/c51.png" width="124" />
							<span class="cl">c51 ワインレッド</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ラディッシュ" src="./designcreator/img/inkcolor/c25.png" width="124" />
							<span class="cl">c25 ラディッシュ</span>
							<p class="arrow_box">C26より深みがある</p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="レッド" src="./designcreator/img/inkcolor/c26.png" width="124" />
							<span class="cl">c26 レッド</span>
							<p class="arrow_box">一般的な赤色</p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="オレンジ" src="./designcreator/img/inkcolor/c29.png" width="124" />
							<span class="cl">c29 オレンジ</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="アプリコット" src="./designcreator/img/inkcolor/c55.png" width="124" />
							<span class="cl">c55 アプリコット</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ショッキングピンク" src="./designcreator/img/inkcolor/c71.png" width="124" />
							<span class="cl">c71 ショッキングピンク</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ホットピンク" src="./designcreator/img/inkcolor/c27.png" width="124" />
							<span class="cl">c27 ホットピンク</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ピンク" src="./designcreator/img/inkcolor/c68.png" width="124" />
							<span class="cl">c68 ピンク</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ライトピンク" src="./designcreator/img/inkcolor/c28.png" width="124" />
							<span class="cl">c28 ライトピンク</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="サーモンピンク" src="./designcreator/img/inkcolor/c67.png" width="124" />
							<span class="cl">c67 サーモンピンク</span>
							<p class="arrow_box">C65と比べ色味が明るい</p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ゴールドイエロー" src="./designcreator/img/inkcolor/c50.png" width="124" />
							<span class="cl">c50 ゴールドイエロー</span>
							<p class="arrow_box">C30より色味が濃い</p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="サンフラワー" src="./designcreator/img/inkcolor/c30.png" width="124" />
							<span class="cl">c30 サンフラワー</span>
							<p class="arrow_box">C50と比べ色味が明るい</p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="イエロー" src="./designcreator/img/inkcolor/c31.png" width="124" />
							<span class="cl">c31 イエロー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="パステルイエロー" src="./designcreator/img/inkcolor/c60.png" width="124" />
							<span class="cl">c60 パステルイエロー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ダークブラウン" src="./designcreator/img/inkcolor/c39.png" width="124" />
							<span class="cl">c39 ダークブラウン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ライトブラウン" src="./designcreator/img/inkcolor/c40.png" width="124" />
							<span class="cl">c40 ライトブラウン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ベージュ" src="./designcreator/img/inkcolor/c65.png" width="124" />
							<span class="cl">c65 ベージュ</span>
							<p class="arrow_box">C67と比べて色味が暗い</p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="フレッシュ" src="./designcreator/img/inkcolor/c61.png" width="124" />
							<span class="cl">c61 フレッシュ</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="クリーム" src="./designcreator/img/inkcolor/c43.png" width="124" />
							<span class="cl">c43 クリーム</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="オリーブ" src="./designcreator/img/inkcolor/c54.png" width="124" />
							<span class="cl">c54 オリーブ</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="グラスグリーン" src="./designcreator/img/inkcolor/c58.png" width="124" />
							<span class="cl">c58 グラスグリーン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ライム" src="./designcreator/img/inkcolor/c59.png" width="124" />
							<span class="cl">c59 ライム</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="イエローグリーン" src="./designcreator/img/inkcolor/c34.png" width="124" />
							<span class="cl">c34 イエローグリーン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ストロー" src="./designcreator/img/inkcolor/c66.png" width="124" />
							<span class="cl">c66 ストロー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ダークグリーン" src="./designcreator/img/inkcolor/c32.png" width="124" />
							<span class="cl">c32 ダークグリーン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="グリーン" src="./designcreator/img/inkcolor/c33.png" width="124" />
							<span class="cl">c33 グリーン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="グリーンティ" src="./designcreator/img/inkcolor/c70.png" width="124" />
							<span class="cl">c70 グリーンティ</span>
							<p class="arrow_box">C64より若干青みが強い</p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ペールグリーン" src="./designcreator/img/inkcolor/c64.png" width="124" />
							<span class="cl">c64 ペールグリーン</span>
							<p class="arrow_box">C70より若干淡い色</p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="オーシャン" src="./designcreator/img/inkcolor/c53.png" width="124" />
							<span class="cl">c53 オーシャン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="エメラルドグリーン" src="./designcreator/img/inkcolor/c57.png" width="124" />
							<span class="cl">c57 エメラルドグリーン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ミントグリーン" src="./designcreator/img/inkcolor/c63.png" width="124" />
							<span class="cl">c63 ミントグリーン</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ネイビー" src="./designcreator/img/inkcolor/c35.png" width="124" />
							<span class="cl">c35 ネイビー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="リフレックスブルー" src="./designcreator/img/inkcolor/c44.png" width="124" />
							<span class="cl">c44 リフレックスブルー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ブルー" src="./designcreator/img/inkcolor/c36.png" width="124" />
							<span class="cl">c36 ブルー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="サックス" src="./designcreator/img/inkcolor/c37.png" width="124" />
							<span class="cl">c37 サックス</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="バイオレット" src="./designcreator/img/inkcolor/c52.png" width="124" />
							<span class="cl">c52 バイオレット</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="パープル" src="./designcreator/img/inkcolor/c38.png" width="124" />
							<span class="cl">c38 パープル</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ラベンダー" src="./designcreator/img/inkcolor/c56.png" width="124" />
							<span class="cl">c56 ラベンダー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ライラック" src="./designcreator/img/inkcolor/c62.png" width="124" />
							<span class="cl">c62 ライラック</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ラベンダーグレイ" src="./designcreator/img/inkcolor/c69.png" width="124" />
							<span class="cl">c69 ラベンダーグレイ</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ゴールド" src="./designcreator/img/inkcolor/c42_w200.png" width="124" />
							<span class="cl">c42 ゴールド</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="シルバー" src="./designcreator/img/inkcolor/c41_w200.png" width="124" />
							<span class="cl">c41 シルバー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="蛍光ピンク" src="./designcreator/img/inkcolor/c47.png" width="124" />
							<span class="cl">c47 蛍光ピンク</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="蛍光オレンジ" src="./designcreator/img/inkcolor/c46.png" width="124" />
							<span class="cl">c46 蛍光オレンジ</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="蛍光イエロー" src="./designcreator/img/inkcolor/c45.png" width="124" />
							<span class="cl">c45 蛍光イエロー</span>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="蛍光グリーン" src="./designcreator/img/inkcolor/c49.png" width="124" />
							<span class="cl">c49 蛍光グリーン</span>
						</div>
					</div>
				</div>

			</div>

			<div id="navi3">
				<ul class="navi">
					<li><a href="#navi1">シミュレーション</a></li>
					<li><a href="#navi2">インク一覧</a></li>
					<li class="act">
						<p>カッティングシート一覧</p>
					</li>
					<li><a href="#navi4" class="lastchild">フォント一覧</a></li>
				</ul>
			</div>

			<div class="wrap">
				<h2 class="heading">カッティングシートカラーの見本16色です。</h2>
				<p class="note"><span>※</span> 画面の都合上、多少色味が異なる場合がございます。</p>
				<!--  <?php echo $cuttingsheet_table; ?>  -->

				<div class="thumbnails">
					<div class="span4">
						<div class="thumbnail">
							<img alt="ゴールド" src="./designcreator/img/cuttingsheet/402-gold.png" width="124" />
							<p><span class="cl">402
									<br>ゴールド</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="シルバー" src="./designcreator/img/cuttingsheet/423-silver.png" width="124" />
							<p><span class="cl">423
									<br>シルバー</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ホワイト" src="./designcreator/img/cuttingsheet/401-white.png" width="124" />
							<p><span class="cl">401
									<br>ホワイト</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ブラック" src="./designcreator/img/cuttingsheet/403-black.png" width="124" />
							<p><span class="cl">403
									<br>ブラック</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ネイビーブルー" src="./designcreator/img/cuttingsheet/412-navy-blue.png" width="124" />
							<p><span class="cl">412
									<br>ネイビーブルー</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ロイヤルブルー" src="./designcreator/img/cuttingsheet/409-royal-blue.png" width="124" />
							<p><span class="cl">409
									<br>ロイヤルブルー</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ライトブルー" src="./designcreator/img/cuttingsheet/408-light-blue.png" width="124" />
							<p><span class="cl">408
									<br>ライトブルー</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="ダークグリーン" src="./designcreator/img/cuttingsheet/410-dark-green.png" width="124" />
							<p><span class="cl">410
									<br>ダークグリーン</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="アップルグリーン" src="./designcreator/img/cuttingsheet/455-apple-green.png" width="124" />
							<p><span class="cl">455
									<br>アップルグリーン</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="レッド" src="./designcreator/img/cuttingsheet/406-red.png" width="124" />
							<p><span class="cl">406
									<br>レッド</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="オレンジ" src="./designcreator/img/cuttingsheet/405-orange.png" width="124" />
							<p><span class="cl">405
									<br>オレンジ</span></p>
						</div>
					</div>
					<!--
					<div class="span4">
						<div class="thumbnail">
							<img alt="アプリコット" src="./designcreator/img/cuttingsheet/457-apricot.png" width="124" />
							<p><span class="cl">457
									<br>アプリコット</span></p>
						</div>
					</div>
-->
					<div class="span4">
						<div class="thumbnail">
							<img alt="パステルオレンジ" src="./designcreator/img/cuttingsheet/440-pastel-orange.png" width="124" />
							<p><span class="cl">440
									<br>パステルオレンジ</span></p>
						</div>
					</div>
					<!--
					<div class="span4">
						<div class="thumbnail">
							<img alt="ゴールデンイエロー" src="./designcreator/img/cuttingsheet/404-golden-yellow.png" width="124" />
							<p><span class="cl">404
									<br>ゴールデンイエロー</span></p>
						</div>
					</div>
-->
					<div class="span4">
						<div class="thumbnail">
							<img alt="レモンイエロー" src="./designcreator/img/cuttingsheet/413-lemon-yellow.png" width="124" />
							<p><span class="cl">413
									<br>レモンイエロー</span></p>
						</div>
					</div>
					<div class="span4">
						<div class="thumbnail">
							<img alt="プラム" src="./designcreator/img/cuttingsheet/459-plum.png" width="124" />
							<p><span class="cl">459
									<br>プラム</span></p>
						</div>
					</div>
					<!--
					<div class="span4">
						<div class="thumbnail">
							<img alt="フーシャ" src="./designcreator/img/cuttingsheet/451-fushia.png" width="124" />
							<p><span class="cl">451
									<br>フーシャ</span></p>
						</div>
					</div>
-->
					<!--
					<div class="span4">
						<div class="thumbnail">
							<img alt="ライラック" src="./designcreator/img/cuttingsheet/470-lilac.png" width="124" />
							<p><span class="cl">470
									<br>ライラック</span></p>
						</div>
					</div>
-->
					<div class="span4">
						<div class="thumbnail">
							<img alt="ピンク" src="./designcreator/img/cuttingsheet/428-pink.png" width="124" />
							<p><span class="cl">428
									<br>ピンク</span></p>
						</div>
					</div>
					<!--
					<div class="span4">
						<div class="thumbnail">
							<img alt="パステルピンク" src="./designcreator/img/cuttingsheet/444-pastel-pink.png" width="124" />
							<p><span class="cl">444
									<br>パステルピンク</span></p>
						</div>
					</div>
-->
					<div class="span4">
						<div class="thumbnail">
							<img alt="蛍光ピンク" src="./designcreator/img/cuttingsheet/432-fluo-pink.png" width="124" />
							<p><span class="cl">432
									<br>蛍光ピンク</span></p>
						</div>
					</div>
				</div>
			</div>

			<div id="navi4">
				<ul class="navi">
					<li><a href="#navi1">シミュレーション</a></li>
					<li><a href="#navi2">インク一覧</a></li>
					<li><a href="#navi3">カッティングシート一覧</a></li>
					<li class="act">
						<p class="lastchild">フォント一覧</p>
					</li>
				</ul>
			</div>

			<div class="wrap">
				<h2 class="heading">フォントのサンプルをご用意しました</h2>
				<?php echo $result_font; ?>
			</div>

		</div>

	</div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="/design/designcreator/js/jQuerySlider.js"></script>
	<script type="text/javascript" src="/design/designcreator/js/rgbcolor.js"></script>
	<script type="text/javascript" src="/design/designcreator/js/creator.js"></script>
	<script type="text/javascript">
		$(function() {
			$('span').hover(function() {
				$(this).next('p').show();
			}, function() {
				$(this).next('p').hide();
			});
		});

	</script>
</body>

</html>
