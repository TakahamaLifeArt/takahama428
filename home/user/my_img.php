<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if (!$me) {
	jump('./login.php');
}

// 注文履歴を取得
$conndb2 = new Conndb(_API);
$d = $conndb2->getOroderHistory($me['id'].",no_progress");

// イメ画
$api_designed = _ORDER_DOMAIN."/system/php_libs/design.php";
$url_designed = _ORDER_DOMAIN."/system/";
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$conndb = new Conndb($api_designed);

// 注文別イメージ画表示
$designed = [];
$len = count($d)-1;
for($i=$len; $i>=0; $i--){
	if($d[$i]['imagecheck']==1){
		$orderId = $d[$i]['orderid'];
		$desedImg = $conndb->getDesigned($orderId);

		// イメージ画像テーブル
		for ($t=0; $t<(count($desedImg)-2); $t++) {
			$designedWrap = "";
			$idx = $t+2;

			if (empty($desedImg[$idx])) continue;
			
			$encodedFileName = rawurlencode($desedImg[$idx]);
			$img_href = $url_designed.'imgfile/'.$orderId.'/'.$encodedFileName;
			$dataURL = 'data:'.finfo_file($finfo, $img_href).';base64,'.base64_encode(file_get_contents($img_href));

			$designedWrap .= '<div class="col-6 img_box">';
			$designedWrap .= '<p>'.$d[$i]['schedule2'].'</p>';
			$designedWrap .= '<a href="./img_detail.php?idx='.$idx.'&orderid='.$orderId.'&date='.$d[$i]['schedule2'].'">';
			$designedWrap .= '<div class="flex_inner">';
			$designedWrap .= '<img src="'.$dataURL.'" width="100%">';
			$designedWrap .= '</div>';
			$designedWrap .= '</a>';
			$designedWrap .= '</div>';

			$designed[] = $designedWrap;
		}
	}
}

$numberOfDesign = count($designed);
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="タカハマライフアートのマイページ、イメージ画像一覧の画面です。こちらの一覧から、確認したいオリジナルTシャツのイメージ画像を選んでください。">
	<meta name="keywords" content="オリジナル,tシャツ,メンバー">
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>イメージ画像 | タカハマライフアート</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/my_img.css" />
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
						<h1>イメージ画像</h1>
					</div>
				</div>
			</div>

			<div class="flex_box row">
				<?php
				$len = min($numberOfDesign, 8);
				for ($i=0; $i<$len; $i++) {
					echo $designed[$i];
				}
				?>
			</div>
			
			<?php
			if ($numberOfDesign>8) {
				$btn = '<div class="bottom_btn">';
				$btn .= '<button class="btn add_btn" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-angle-down mr-1" aria-hidden="true"></i>もっと見る</button>';
				$btn .= '</div>';
				echo $btn;
			}
			?>
			
			<div class="collapse" id="collapseExample">
				<div class="flex_box row">
					<?php
					for ($i=8; $i<$numberOfDesign; $i++) {
						echo $designed[$i];
					}
					?>
				</div>
			</div>

			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./my_menu.php"><div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div></a>
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
