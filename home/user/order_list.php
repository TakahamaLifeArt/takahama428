<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('./login.php');
}

// イメ画を取得
$api_designed = _ORDER_DOMAIN."/system/php_libs/design.php";
$url_designed = _ORDER_DOMAIN."/system/";
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$connDesign = new Conndb($api_designed);

// 注文履歴
$conndb = new Conndb(_API);
$d = $conndb->getOroderHistory($me['id'], 0, 1);
$history = '';
$histories = [];
$cnt = count($d)-1;
for($i=$cnt; $i>=0; $i--){

	// イメ画
	$desedImg = $connDesign->getDesigned($d[$i]['orderid']);
	if (empty($desedImg[2])) {
		$dataURL = '/user/img/noprint.svg';
	} else {
		$encodedFileName = rawurlencode($desedImg[2]);	// 最初のファイル
		$img_href = $url_designed.'imgfile/'.$d[$i]['orderid'].'/'.$encodedFileName;
		$dataURL = 'data:'.finfo_file($finfo, $img_href).';base64,'.base64_encode(file_get_contents($img_href));
	}

	$history = '<div class="button_gr">';
	$history .= '<a href="order_detail.php?oi='.$d[$i]['orderid'].'" class="btn rd_sq_button">';

	$history .= '<div class="imgblk">';
	$history .= '<img src="'.$dataURL.'" class="btn_img" width="100px">';
	$history .= '</div>';

	$history .= '<div class="grdil">';

	$history .= '<div class="txtarea">';
	$history .= '<p class="txt_btn">注文番号：'.$d[$i]['orderid'].'</p>';
	$history .= '<p class="txt_btn">注文日：'.$d[$i]['schedule2'].'</p>';
	$history .= '</div>';

	$history .= '<div class="txtgrp">';
	if ($d[$i]['deposit']!=2) {
		$history .= '<p class="txt_btn"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>未決済</p>';
	} else {
		$history .= '<p class="txt_btn"><i class="fa fa-check-circle" aria-hidden="true"></i>決済完了</p>';
	}
	$history .= '<p class="txt_btn"><img src="/user/img/sp_m_history_progress_icon.png" class="btn_img" width="30px">'.$d[$i]['progressname'].'</p>';
	$history .= '</div>';

	$history .= '</div>';

	$history .= '</a>';
	$history .= '</div>';
	
	$histories[] = $history;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="タカハマライフアートのマイページ、制作状況一覧画面です。こちらから、現在制作進行中のオリジナルTシャツの状況を確認することができます。宅配便の荷物追跡サービスとも連携しているので、いつ届くか確認したいお客様は是非ご活用ください。">
	<meta name="keywords" content="オリジナル,tシャツ,メンバー">
	<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
	<meta property="og:type" content="article" />
	<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
	<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>お支払い・製作状況 | タカハマライフアート</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="./css/order_list.css" />
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>

	<div id="container">
		<div class="contents">

			<div class="toolbar">
				<div class="toolbar_inner clearfix">
					<div class="pagetitle">
						<h1>お支払い・制作状況</h1>
					</div>
				</div>
			</div>
			<div class="button_fld">
				<?php
				$len = count($histories);
				for ($i=0; $i<8; $i++) {
					echo $histories[$i];
				}
				?>
			</div>

			<?php
			if ($len>8) {
				$btn = '<div class="bottom_btn">';
				$btn .= '<button class="btn add_btn" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-angle-down mr-1" aria-hidden="true"></i>もっと見る</button>';
				$btn .= '</div>';
				echo $btn;
			}
			?>

			<div class="collapse" id="collapseExample">
				<div class="button_fld">
					<?php
					for ($i=8; $i<$len; $i++) {
						echo $histories[$i];
					}
					?>
				</div>
			</div>
			
			<div class="transition_wrap d-flex justify-content-between align-items-center">
				<a href="./my_menu.php">
					<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left"></i>戻る</div>
				</a>
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
