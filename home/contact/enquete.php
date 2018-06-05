<?php

$isSend = null;
if( isset($_POST['ticket']) && !empty($_POST['ticket']) ) {
	require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
	$data = $_POST;
	$data['customername'] = '';
	$data['zipcode'] = '';
	$data['addr'] ='';
	$conn = new ConnDB();
	$conn->setEnquete($data);
	$isSend = true;
}else if(isset($_REQUEST['enq']) && !empty($_REQUEST['enq'])){
	$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
	$enq = preg_replace('/^\D/', '', $_REQUEST['enq']);
	if (empty($enq)) header("Location: "._DOMAIN);
	$customer_id = sprintf("%d", $enq);
	$number = 'K'.str_pad($customer_id, 6, '0', STR_PAD_LEFT);
}else{
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
		<link rel="stylesheet" type="text/css" href="./css/custom_responsive.css" media="screen">
		<link rel="stylesheet" type="text/css" href="./css/enquete.css" media="screen">
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>
		
		<div id="container">
			<div class="contents">

<?php
	if(is_null($isSend)){
		$html = <<<DOC
			<div class="heading1_wrapper">
				<h1>アンケートのお願い</h1>
				<p class="comment"></p>
				<p class="sub">Enquete</p>
			</div>
			
			<div class="firstmessage">
				<p>この度のタカハマライフアートへのご注文、誠にありがとうございました。</p>
				<p>
					弊社では、更なるお客様サービスの向上のため、下記のアンケートを実施しておりますので、<br>
					ぜひともご協力をお願いいたします。
				</p>
			</div>
			
			<main>
				<form id="fileupload" class="e-mailer" action="{$_SERVER['SCRIPT_NAME']}" method="POST" enctype="multipart/form-data">
					<div>
						<label id="number">顧客ID： <ins>{$number}</ins></label>
						<input type="hidden" value="{$customer_id}" name="number" class="e-none">
					</div>

					<div>
						<p class="q">
							<em>Q1</em>
							<label>商品、プリントの品質には満足できましたか？</label>
						</p>
						<div class="a">
							<p><label><input type="radio" name="a6" value="5" required>とても満足</label></p>
							<p><label><input type="radio" name="a6" value="4" required>満足</label></p>
							<p><label><input type="radio" name="a6" value="3" required>普通</label></p>
							<p><label><input type="radio" name="a6" value="2" required>不満</label></p>
							<p><label><input type="radio" name="a6" value="1" required>とても不満</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q2</em>
							<label>スタッフの対応には満足できましたか?</label>
						</p>
						<div class="a">
							<p><label><input type="radio" name="a5" value="5" required>とても満足</label></p>
							<p><label><input type="radio" name="a5" value="4" required>満足</label></p>
							<p><label><input type="radio" name="a5" value="3" required>普通</label></p>
							<p><label><input type="radio" name="a5" value="2" required>不満</label></p>
							<p><label><input type="radio" name="a5" value="1" required>とても不満</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q3</em>
							<label>梱包状態には満足できましたか?</label>
						</p>
						<div class="a">
							<p><label><input type="radio" name="a7" value="5" required>とても満足</label></p>
							<p><label><input type="radio" name="a7" value="4" required>満足</label></p>
							<p><label><input type="radio" name="a7" value="3" required>普通</label></p>
							<p><label><input type="radio" name="a7" value="2" required>不満</label></p>
							<p><label><input type="radio" name="a7" value="1" required>とても不満</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q4</em>
							<label>ホームページは使いやすかったですか?</label>
						</p>
						<div class="a">
							<p><label><input type="radio" name="a1" value="5" required>とても満足</label></p>
							<p><label><input type="radio" name="a1" value="4" required>満足</label></p>
							<p><label><input type="radio" name="a1" value="3" required>普通</label></p>
							<p><label><input type="radio" name="a1" value="2" required>不満</label></p>
							<p><label><input type="radio" name="a1" value="1" required>とても不満</label></p>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q5</em>
							<label>商品の着心地はいかがでしたでしょうか</label>
						</p>
						<p class="a">
							<textarea name="a10"></textarea>
						</p>
					</div>

					<div>
						<p class="q">
							<em>Q6</em>
							<label>タカハマライフアートの「ここが使いづらい！」という点を教えてください</label><span>(複数回答可)</span>
						</p>
						<div class="a">
							<fieldset>
								<p><label><input type="checkbox" name="a17[]" value="1" required>注文確定の電話</label></p>
								<p><label><input type="checkbox" name="a17[]" value="2" required>商品の選び方</label></p>
								<p><label><input type="checkbox" name="a17[]" value="3" required>商品の素材や色</label></p>
								<p><label><input type="checkbox" name="a17[]" value="4" required>お届け日</label></p>
								<p><label><input type="checkbox" name="a17[]" value="5" required>商品の見積もり</label></p>
								<p><label><input type="checkbox" name="a17[]" value="6" required>デザインの入稿の方法</label></p>
								<p><label><input type="checkbox" name="a17[]" value="7" required>プリントサイズ</label></p>
								<p><label><input type="checkbox" name="a17[]" value="8" required>プリント方法</label></p>
								<p><label><input type="checkbox" name="a17[]" value="9" required>割引の内容や条件</label></p>
								<p><label><input type="checkbox" name="a17[]" value="10" required>資料請求・商品サンプルの注文</label></p>
								<p><label><input type="checkbox" name="a17[]" value="0" required>特になし</label></p>
							</fieldset>
						</div>
					</div>

					<div>
						<p class="q">
							<em>Q7</em>
							<label>全体を通して、ご意見ご感想がありましたらご記入お願いします</label>
						</p>
						<p class="a">
							<textarea name="a9"></textarea>
						</p>
					</div>

					<div>
						<p class="q">
							<em>Q8</em>
							<label>写真掲載割をご利用のお客様は、商品到着後の感想やコメントをご入力ください</label>
						</p>
						<p class="a">
							<textarea name="a18"></textarea>
						</p>
					</div>

					<div>
						<p class="q">
							<em>Q9</em>
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
					<input type="hidden" name="sendto" value="order@takahama428.com">
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
				<p class="sub">Error</p>
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
		<script type="text/javascript" src="./js/enquete.js"></script>

	</body>

</html>
