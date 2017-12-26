<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

$orderid = 0;

// TLA顧客IDを取得
$conndb = new Conndb(_API_U);
$u = $conndb->getUserList($me['id']);
$customerid = $u[0]['id'];
$conndb2 = new Conndb(_API);
$d = $conndb2->getOroderHistory($customerid.",no_progress");

$api_designed = _ORDER_DOMAIN."/system/php_libs/design.php";
$url_designed = _ORDER_DOMAIN."/system/";
$finfo = finfo_open(FILEINFO_MIME_TYPE);
//takahama_log('[designed.php]'.serialize($d));

// 注文別イメージ画表示
$designed_wrap = "";

for($i=0; $i<count($d); $i++){
	//takahama_log('[designed.php]  imagecheck = '.$d[$i]['imagecheck']);

	if($d[$i]['imagecheck']==1){
		$orderid = $d[$i]['orderid'];

		$conndb = new Conndb($api_designed);
		$desedImg = $conndb->getDesigned($orderid);
		$designed_wrap .= '<div class="desined_'.$orderid.'">';

		//　注文情報テーブル
		$designed_wrap .= '<div>';
		$designed_wrap .= '<h2>注文No.'.$orderid.'</h2><table><tr><th>発送日</th><td>'.$d[$i]['schedule3'].'</td></tr>';
		$designed_wrap .= '<tr><th>アイテム</th>';
		foreach($d[$i]['itemlist'] as $itemname=>$val){
			$designed_wrap .= '<td>'.$itemname.'</td></tr>';
		}
		$designed_wrap .= '</table></div>';

		// イメージ画像テーブル
		for($t=0; $t<(count($desedImg)-2); $t++){
			$desedImg[$t+2] = rawurlencode($desedImg[$t+2]);
			$img_href = $url_designed.'imgfile/'.$orderid.'/'.$desedImg[$t+2];
			$dataURL = 'data:'.finfo_file($finfo, $img_href).';base64,'.base64_encode(file_get_contents($img_href));
			$designed_wrap .= '<div class="desined_img_wrap"><table><tr><td class="desined_img"><img src="'.$dataURL.'" class="img" alt="'.$desedImg[$t+2].'"></td></tr></table>';
			$designed_wrap .= '<div class="download_btn_wrap"><input type="button" class="download_btn" value="ダウンロード"  name="'.$img_href.'"></div></div>';
		}
		$designed_wrap .= '</div>';
	}
}
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
		<title>イメージ画像 | オリジナルTシャツ【タカハマライフアート】</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_design_img.css" />
		<script type="text/javascript">
			var _CUR_ORDER = <?php echo $orderid?>;
			var _HOGE_COUNT = <?php echo $d[0]['imagecheck'];?>;
			var _HOGE_ID = <?php echo $d[0]['orderid'];?>;

		</script>

	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">

				<div class="toolbar">
					<div class="toolbar_inner clearfix">
						<div class="menu_wrap">
							<?php echo $menu;?>
						</div>
					</div>
				</div>
				<div class="pagetitle">
					<h1>イメージ画像</h1>
				</div>
				<div style="font-weight:bold;"><span class="fontred">※</span>同デザインでアイテムやインクの色替えがある場合、1パターンのみの表示となります。ご了承ください。</div>
				<div id="designed_wrap">
					<?php echo $designed_wrap;?>
				</div>
			</div>
		</div>



		<div id="printform_wrapper"><iframe id="printform" name="printform"></iframe></div>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script type="text/javascript" src="./js/designed.js"></script>
	</body>

	</html>
