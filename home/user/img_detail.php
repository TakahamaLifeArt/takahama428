<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if (!$me) {
	jump('./login.php');
}

// クエリストリング
parse_str($_SERVER['QUERY_STRING'], $prm);
if (empty($prm) || !isset($prm['orderid'], $prm['idx'], $prm['date'])) {
	jump('my_img.php');
}

// イメ画のパス
$api_designed = _ORDER_DOMAIN."/system/php_libs/design.php";
$url_designed = _ORDER_DOMAIN."/system/";
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$conndb = new Conndb($api_designed);
$desedImg = $conndb->getDesigned($prm['orderid']);
$encodedFileName = rawurlencode($desedImg[$prm['idx']]);
$img_href = $url_designed.'imgfile/'.$prm['orderid'].'/'.$encodedFileName;
$dataURL = 'data:'.finfo_file($finfo, $img_href).';base64,'.base64_encode(file_get_contents($img_href));

?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="早い！Tシャツでオリジナルを作成するならタカハマへ！タカハマライフアートのログイン画面です。メールアドレスとパスワードを入れてください。マイページからご注文履歴などをご確認することができます。ログインにする為のパスワードをお忘れの方はこちら。">
	<meta name="keywords" content="オリジナル,tシャツ,メンバー">
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>イメージ画像 - 詳細 | タカハマライフアート</title>
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
			<div class="num_data">
				<p>注文番号 : <span><?php echo $prm['orderid']; ?></span></p>
				<p>注文日 : <span><?php echo $prm['date']; ?></span></p>
			</div>
			<div class="flex_inner img_big">
				<img src="<?php echo $dataURL;?>" width="100%">
			</div>

			<a href="<?php echo $img_href;?>" class="btn_or btn">ダウンロード</a>

			<a href="./reorder.php?oi=<?php echo $prm['orderid'];?>" class="btn btn-info line-hi">注文情報を確認する<br>追加・再注文はこちら</a>

			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./my_img.php"><div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div></a>
			</div>
		</div>
		<a href="./my_menu.php" class="next_btn">マイページTOPへ戻る</a>
	</div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
</body>

</html>
