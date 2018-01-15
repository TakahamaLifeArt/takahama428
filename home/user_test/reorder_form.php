<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/package/mail/Mailer.php';
use package\mail\Mailer;

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
jump('login.php');
}

$conndb = new Conndb(_API_U);



// ユーザー情報を設定
$u = $conndb->getUserList($me['id']);
$username = $me['customername'];
$userkana = $me['customerruby'];
$email = $u[0]['email'];

//お届け先情報を再度取得
$deli = $conndb->getDeli($me['id']);
?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="タカハマライフアートのマイページ、追加注文のフォーム画面です。こちらのフォームに、必要な項目を入力することで、オリジナルTシャツの追加注文が完了となります。ぜひご活用ください。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>追加・再注文フォーム | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_reorder.css" />
		<style>
			.lightbox {
				display: none;
			}

		</style>

	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">

				<div class="toolbar">
					<div class="toolbar_inner">
						<div class="pagetitle">
							<h1>追加・再注文フォーム</h1>
						</div>
					</div>
				</div>

				<section>
					<h2>カラー・枚数</h2>
					<h3><ins>1.</ins>アイテムカラーの指定</h3>
					<div class="color_size_sele_wrap">
						<div class="color_sele_wrap">
							<div class="color_sele">
								<p class="item_name">5.6オンスハイクオリティーTシャツ</p>
								<p class="thumb_h">アイテムカラー:<span class="note_color">ホワイト</span>全<span class="num_of_color">50</span>色</p>
								<ul class="color_sele_thumb">
									<li class="nowimg"><img alt="001" title="ホワイト" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_001_s.jpg"></li>
									<li><img alt="003" title="杢グレー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_003_s.jpg"></li>
									<li><img alt="005" title="ブラック" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_005_s.jpg"></li>
									<li><img alt="010" title="レッド" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_010_s.jpg"></li>
									<li><img alt="011" title="ピンク" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_011_s.jpg"></li>
									<li><img alt="014" title="パープル" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_014_s.jpg"></li>
									<li><img alt="015" title="オレンジ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_015_s.jpg"></li>
									<li><img alt="019" title="ラベンダー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_019_s.jpg"></li>
									<li><img alt="020" title="イエロー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_020_s.jpg"></li>
									<li><img alt="021" title="マスタード" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_021_s.jpg"></li>
									<li><img alt="024" title="ライトグリーン" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_024_s.jpg"></li>
									<li><img alt="025" title="グリーン" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_025_s.jpg"></li>
									<li><img alt="031" title="ネイビー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_031_s.jpg"></li>
									<li><img alt="032" title="ロイヤルブルー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_032_s.jpg"></li>
									<li><img alt="034" title="ターコイズ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_034_s.jpg"></li>
									<li><img alt="044" title="アッシュ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_044_s.jpg"></li>
									<li><img alt="077" title="ゴールドイエロー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_077_s.jpg"></li>
									<li><img alt="095" title="アクア" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_095_s.jpg"></li>
									<li><img alt="106" title="ナチュラル" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_106_s.jpg"></li>
									<li><img alt="109" title="デニム" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_109_s.jpg"></li>
									<li><img alt="112" title="バーガンディ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_112_s.jpg"></li>
									<li><img alt="128" title="オリーブ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_128_s.jpg"></li>
									<li><img alt="129" title="チャコール" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_129_s.jpg"></li>
									<li><img alt="131" title="フォレスト" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_131_s.jpg"></li>
									<li><img alt="132" title="ライトピンク" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_132_s.jpg"></li>
									<li><img alt="133" title="ライトブルー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_133_s.jpg"></li>
									<li><img alt="134" title="ライトイエロー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_134_s.jpg"></li>
									<li><img alt="146" title="ホットピンク" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_146_s.jpg"></li>
									<li><img alt="153" title="シルバーグレー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_153_s.jpg"></li>
									<li><img alt="155" title="ライム" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_155_s.jpg"></li>
									<li><img alt="165" title="デイジー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_165_s.jpg"></li>
									<li><img alt="167" title="メトロブルー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_167_s.jpg"></li>
									<li><img alt="168" title="チョコレート" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_168_s.jpg"></li>
									<li><img alt="169" title="イタリアンレッド" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_169_s.jpg"></li>
									<li><img alt="170" title="コーラルオレンジ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_170_s.jpg"></li>
									<li><img alt="171" title="ジャパンブルー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_171_s.jpg"></li>
									<li><img alt="188" title="ライトパープル" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_188_s.jpg"></li>
									<li><img alt="189" title="ライトオレンジ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_189_s.jpg"></li>
									<li><img alt="190" title="ライトサーモン" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_190_s.jpg"></li>
									<li><img alt="191" title="ピーチ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_191_s.jpg"></li>
									<li><img alt="192" title="ディープオレンジ" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_192_s.jpg"></li>
									<li><img alt="193" title="ディープグリーン" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_193_s.jpg"></li>
									<li><img alt="194" title="ブライトグリーン" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_194_s.jpg"></li>
									<li><img alt="195" title="アイスグリーン" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_195_s.jpg"></li>
									<li><img alt="196" title="ミント" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_196_s.jpg"></li>
									<li><img alt="197" title="ピーコックグリーン" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_197_s.jpg"></li>
									<li><img alt="198" title="ミディアムブルー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_198_s.jpg"></li>
									<li><img alt="199" title="シーブルー" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_199_s.jpg"></li>
									<li><img alt="200" title="ディープパープル" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_200_s.jpg"></li>
									<li><img alt="442" title="エメラルド" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_442_s.jpg"></li>
								</ul>
							</div>
							<div class="item_image_big"><img alt="085-cvt_001" src="https://takahamalifeart.com/weblib/img/items/t-shirts/085-cvt/085-cvt_001.jpg" width="300"></div>
						</div>

						<div class="sizeprice">
							<h3>
								<ins>2.</ins>サイズと枚数の指定
							</h3>
							<div class="size_sele_wrap">
								<table class="size_table">
									<tbody>
										<tr class="heading">
											<th></th>
											<th>100</th>
											<th>110</th>
											<th>120</th>
											<th>130</th>
											<th>140</th>
											<th>150</th>
											<th>160</th>
										</tr>
										<tr>
											<th>1枚単価<span class="inter">500</span> 円</th>
											<td class="size_4_100_500"><input id="size_4" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onblur="$.blurNumber(this);"></td>
											<td class="size_5_110_500"><input id="size_5" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_6_120_500"><input id="size_6" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_7_130_500"><input id="size_7" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_8_140_500"><input id="size_8" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_9_150_500"><input id="size_9" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_10_160_500"><input id="size_10" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td>枚</td>
										</tr>
										<tr class="heading">
											<th></th>
											<th>WM</th>
											<th>WL</th>
											<th>S</th>
											<th>M</th>
											<th>L</th>
											<th>XL</th>
										</tr>
										<tr>
											<th>1枚単価<span class="inter">550</span> 円</th>
											<td class="size_30_WM_550"><input id="size_30" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onblur="$.blurNumber(this);"></td>
											<td class="size_31_WL_550"><input id="size_31" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_18_S_550"><input id="size_18" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_19_M_550"><input id="size_19" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_20_L_550"><input id="size_20" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td class="size_21_XL_550"><input id="size_21" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td>枚</td>
										</tr>
										<tr class="heading">
											<th></th>
											<th>3L</th>
											<th>4L</th>
										</tr>
										<tr>
											<th>1枚単価<span class="inter">670</span> 円</th>
											<td class="size_22_3L_670"><input id="size_22" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onblur="$.blurNumber(this);"></td>
											<td class="size_23_4L_670"><input id="size_23" type="number" value="0" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);"></td>
											<td>枚</td>
										</tr>
									</tbody>
								</table>
								<div class="btmline">小計<span class="cur_amount">0</span>枚</div>
							</div>
						</div>
					</div>

					<div class="btn_box flex_add">
						<button class="btn add_btn_min"><i class="fa fa-plus mr-1" aria-hidden="true"></i> 別のカラーを追加</button>
						<button class="btn btn-outline-danger waves-effect del_btn_2">上記カラーを削除</button>
					</div>

					<div class="arrow_line">
						<div style="display:inline-block;">合計<span id="tot_amount">0</span>枚</div>
					</div>
				</section>

				<div class="transition_wrap align-items-center">
					<button type="button" class="btn btn-info">申し込みリストへ追加</button>
					<div class="step_prev hoverable waves-effect">
						<i class="fa fa-chevron-left mr-1"></i>戻る
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
		<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script type="text/javascript" src="./js/account.js"></script>
		<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	</body>

	</html>
