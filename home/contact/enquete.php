<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
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
		<link rel="stylesheet" type="text/css" media="screen" href="/user/js/upload/jquery.fileupload.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/user/js/upload/jquery.fileupload-ui.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/user/css/uploader.css">
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
					<div>
						<p class="q">
							<em>Q{$len}</em>
							<label>写真掲載割をご利用のお客様は、商品着用写真をお送りください</label>
						</p>

						<div class="fileupload-buttonbar">
							<div class="">
								<!-- The fileinput-button span is used to style the file input field as button -->
								<span class="btn btn-success fileinput-button fade in">
									<i class="fa fa-plus" aria-hidden="true"></i>
									<span>ファイルを選択...</span>
									<input type="file" name="files[]" class="e-none" multiple>
								</span>

								<!-- The global file processing state -->
								<span class="fileupload-process"></span>
							</div>

							<div class="drop-area">
								<p>ここにファイルをドロップできます</p>
							</div>
							<p class="ri_txt">最大容量：100MB</p>

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
					</div>

					<div id="uploaded-files"></div>

					<p class="button_area">
						<button type="submit" id="sendmail" class="btn">アンケートを送信する</button>
					</p>

					<input type="hidden" name="ticket" class="e-none" value="{$ticket}">
					<input type="hidden" name="sendto" value="{$with(_SEND_TO)}">
					<input type="hidden" name="subject" value="お客様アンケート">
					<input type="hidden" name="title" value="お客様アンケート">
				</form>
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

		<!-- The template to display files available for upload -->
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
					<img src="{%=file.thumbnailUrl%}?auth=admin">
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
		<script src="//doozor.bitbucket.io/email/e-mailform.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
		<script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
		<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
		<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
		<script src="/user/js/upload/vendor/jquery.ui.widget.js"></script>
		<script src="/user/js/upload/jquery.iframe-transport.js"></script>
		<script src="/user/js/upload/jquery.fileupload.js"></script>
		<script src="/user/js/upload/jquery.fileupload-process.js"></script>
		<script src="/user/js/upload/jquery.fileupload-image.js"></script>
		<script src="/user/js/upload/jquery.fileupload-validate.js"></script>
		<script src="/user/js/upload/jquery.fileupload-ui.js"></script>
		<script type="text/javascript" src="/contact/js/enquete.js"></script>

	</body>

</html>
