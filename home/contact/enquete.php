<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
$_version = time();
$conn = new ConnDB();
define('_SEND_TO', 'info@takahama428.com');
$with = 'with';
function with($constant){
	return $constant;
}

$isSend = null;
if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket']) && !empty($_POST['ticket']) && isset($_REQUEST['enq']) && !empty($_REQUEST['enq']) ) {
	if (isset($_POST['choice_id'][1])) {
		$check = ['', 5,4,3,2,1];
		$dat['a6'] = $check[$_POST['choice_id'][1][0]];
		$dat['a5'] = $check[$_POST['choice_id'][2][0]-5];
		$dat['a7'] = $check[$_POST['choice_id'][3][0]-10];
		$dat['a1'] = $check[$_POST['choice_id'][4][0]-15];
		$dat['a10'] = $_POST['sentence'][5];
		foreach ($_POST['choice_id'][6] as $key=>$val) {
			$dat['a17'][] = $val==31? 0: $val-20;
		}
		$dat['a9'] = $_POST['sentence'][7];
		$dat['a18'] = $_POST['sentence'][8];
		$dat['number'] = $_POST['number'];
		$dat['customername'] = '';
		$dat['zipcode'] = '';
		$dat['addr'] ='';
		$conn->setEnquete($dat);
	}
	
	// 回答送信
	$headers = [
		'X-Questally-Access-Token:'._API_SECRET,
		'Origin:'._DOMAIN,
	];
	$conn->setURL(_API_ENDPOINT);
	$res = json_decode($conn->request('POST', $_POST, $headers), true);
	if ($res['code'] != 200) {
		$isSend = false;
	} else {
		// 二重送信防止
		header('Location: ' . $_SERVER['SCRIPT_NAME'].'?fin='.$_REQUEST['enq']); 
		exit;
//		$isSend = true;
	}
} else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_REQUEST['fin'])) {
	// 送信完了
	$isSend = true;
	$ticket = '';
	$action = '';
} else if (isset($_REQUEST['enq']) && !empty($_REQUEST['enq'])) {
	// アンケートページ表示
	$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
	$enq = preg_replace('/^\D/', '', $_REQUEST['enq']);
	if (empty($enq)) header("Location: "._DOMAIN);
	$action = $_SERVER['SCRIPT_NAME'].'?enq='.$enq;
	$customerId = sprintf("%d", $enq);
	$number = 'K'.str_pad($customerId, 6, '0', STR_PAD_LEFT);
	
	$param = array();
	$headers = [
		'X-Questally-Access-Token:'._API_SECRET,
		'Origin:'._DOMAIN
	];
	$conn->setURL(_API_ENDPOINT);
	$data = json_decode($conn->request('GET', $param, $headers), true);
} else {
	// その他アクセスはホームへ
	header("Location: "._DOMAIN);
}
?>
<!DOCTYPE html>
<html lang="ja">
	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  websites: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！">
		<meta property="og:type" content="article">
		<meta name="Description" content="1枚～大量のプリントまで、トレーナー・ポロシャツ・オリジナルTシャツの作成・プリントについてのお問合せは、東京都葛飾区のタカハマライフアートにお任せ下さい！">
		<meta name="keywords" content="資料請求,無料サンプル,アンケート">
		<meta property="og:url" content="https://www.takahama428.com/">
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート">
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png">
		<meta property="fb:app_id" content="1605142019732010">
		<title>アンケート　|　オリジナルTシャツ屋タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" href="/contact/css/custom_responsive.css" media="screen">
		<link rel="stylesheet" type="text/css" href="/contact/css/enquete.css" media="screen">
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>
		
		<div id="container">
			<div class="contents">

<?php
	if(is_null($isSend)){
		$desc = nl2br($data['enquete']['description']);
		$enqueteId = $data['enquete']['id'];
		$html = <<<DOC
			<div class="heading1_wrapper">
				<h1>{$data['enquete']['title']}</h1>
				<p class="comment"></p>
				<p class="sub">Enquete</p>
			</div>
			
			<div class="firstmessage">
				<p>{$desc}</p>
			</div>
			
			<main>
				<form id="fileupload" class="e-mailer" action="{$action}" method="POST" enctype="multipart/form-data">
					<div>
						<label id="number">顧客ID： <ins>{$number}</ins></label>
						<input type="hidden" value="{$customerId}" name="number" class="e-none">
						<input type="hidden" value="{$enqueteId}" name="enquete_id" class="e-none">
					</div>
DOC;
		echo $html;
		
		$html = '';
		$val = $data['question'];
		for ($i=0, $len=count($val); $i<$len; $i++) {
			$qId = $val[$i]['id'];
			$html .= '<div>';
				$html .= '<p class="q">';
					$html .= '<em>Q'.($i+1).'</em>';
					$html .= '<label>'.$val[$i]['sentence'].'</label>';
					$html .= '<input type="hidden" value="'.$qId.'" name="question_id[]" class="e-none">';
				$html .= '</p>';
				$html .= '<div class="a">';
				switch ($val[$i]['format_id']) {
					case '1':
						$html .= '<input type="hidden" name="choice_id['.$qId.'][]" value="0" class="e-none">';
						$html .= '<textarea name="sentence['.$qId.']"></textarea>';
						break;
					case '2':
						$choice = $data['choice'][$qId];
						for ($n=0, $cnt=count($choice); $n<$cnt; $n++) {
							$html .= '<p><label><input type="radio" name="choice_id['.$qId.'][]" value="'.$choice[$n]['id'].'" required>'.$choice[$n]['sentence'].'</label></p>';
						}
						$html .= '<textarea name="sentence['.$qId.']" class="e-none" hidden></textarea>';
						break;
					case '3':
						$choice = $data['choice'][$val[$i]['id']];
						$html .= '<fieldset>';
						for ($n=0, $cnt=count($choice); $n<$cnt; $n++) {
							$html .= '<p><label><input type="checkbox" name="choice_id['.$qId.'][]" value="'.$choice[$n]['id'].'" required>'.$choice[$n]['sentence'].'</label></p>';
						}
						$html .= '</fieldset>';
						$html .= '<textarea name="sentence['.$qId.']" class="e-none" hidden></textarea>';
						break;
				}
				$html .= '</div>';
			$html .= '</div>';
		}
		echo $html;

		$len++;
		$html = <<<DOC
					<button type="submit" id="sendmail" class="btn" hidden>アンケートを送信する</button>
					
					<label hidden>写真掲載割をご利用のお客様は、商品着用写真をお送りください</label>
					<textarea id="filename" hidden></textarea>
					<label hidden>ダウンロードURL</label>
					<input id="deownload_link" type="hidden" name="deownload_link" value="">
				
					<input type="hidden" name="ticket" class="e-none" value="{$ticket}">
					<input type="hidden" name="sendto" value="{$with(_SEND_TO)}">
					<input type="hidden" name="subject" value="お客様アンケート">
					<input type="hidden" name="title" value="お客様アンケート">
				</form>
				
				<div>
					<p class="q">
						<em>Q{$len}</em>
						<label>写真掲載割をご利用のお客様は、商品着用写真をお送りください</label>
					</p>
				</div>
				<div id="file-uploader"></div>
				
				<p class="button_area">
					<button id="send_button" class="btn">アンケートを送信する</button>
				</p>
				
			</main>
DOC;

		echo $html;
	}else if($isSend){
			
		$html = <<<DOC
			<div class="heading1_wrapper">
				<h1>アンケートのご協力ありがとうございました</h1>
				<p class="comment"></p>
				<p class="sub">Enquete</p>
			</div>
			<main>
				<p>
					アンケートにお答えいただき、ありがとうございました。<br>
					またのご利用、心よりお待ち申し上げております。
				</p>
			</main>
DOC;

		echo $html;
	}else{
		
		$html = <<<DOC
			<div class="heading1_wrapper">
				<h1>送信エラー</h1>
				<p class="comment"></p>
				<p class="sub">Error: {$res['message']}</p>
			</div>
			<main>
				<p>
					アンケートの送信中にエラーが発生しました。<br>
					恐れ入りますが、もう一度送信をお願いいたします。
				</p>
			</main>
DOC;

		echo $html;
	}
?>

				<div class="box_c">
					<div class="bg"></div>
				</div>

			</div>
		</div>
		
		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>

		<script src="https://doozor.bitbucket.io/email/e-mailform.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
		<script src="https://doozor.bitbucket.io/uploader/file_uploader.min.js?m=drop&ci=phl57jus0l"></script>
		<script type="text/javascript" src="/contact/js/enquete.js?v=<?php echo $_version;?>"></script>

	</body>

</html>
