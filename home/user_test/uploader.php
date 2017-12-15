<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';
if($_SERVER['REQUEST_METHOD']!='POST'){
	setToken();
}else{
	chkToken();
}
// for script
$_version = time();
?>
<!DOCTYPE html>
<html lang="ja">

	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#  website: http://ogp.me/ns/website#">
		<meta charset="utf-8" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="http://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="http://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>大きいデータを送る方 | タカハマライフアート</title>
		
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<link rel="stylesheet" type="text/css" media="screen" href="/common/css/common.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/common/css/base.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/style.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./js/upload/jquery.fileupload.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./js/upload/jquery.fileupload-ui.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/uploader.css">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>

	</head>

	<body>

		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>
		
		<div id="container">
			<div class="contents">

				<div class="toolbar">
					<div class="toolbar_inner clearfix">
						<h1>大きいデータを送る方</h1>
					</div>
				</div>

				<div>
					<h2>こちらからデータを送信できます</h2>
					<ul>
						<li>入稿したいデザインファイルサイズが大きい場合は、こちらから送信をお願い致します。</li>
						<li>一括送信ボタンを押すと、ファイルが弊社へ自動送信されます。</li>
						<li>(※対応ファイルは、jpg,png,ai,psd,pdf 及びzip圧縮 のみです。ご了承ください。)</li>
					</ul>
				</div>
				
				<form id="uploadinfo" name="uploadinfo" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onsubmit="return false;">
					<table class="form_table">
						<tfoot>
							<tr>
								<td colspan="2">
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="designfile" value="1">
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<th>お名前</th>
								<td>
									<input type="text" name="customername" placeholder="必須です" required>
								</td>
							</tr>
							<tr>
								<th>メールアドレス</th>
								<td>
									<input type="email" name="email" placeholder="必須です" required>
								</td>
							</tr>
							<tr>
								<th>メッセージ</th>
								<td>
									<textarea name="message" cols="30" rows="10"></textarea>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				
				<form id="fileupload" name="fileupload" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="email" value="">
					
					<!-- Redirect browsers with JavaScript disabled to the origin page -->
<!--					<noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>-->
					<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
					<div class="fileupload-buttonbar">
						<div class="">
							<!-- The fileinput-button span is used to style the file input field as button -->
							<span class="btn btn-success fileinput-button">
								<i class="fa fa-plus" aria-hidden="true"></i>
								<span>ファイルを選択...</span>
								<input type="file" name="files[]" multiple>
							</span>
							<!-- The global file processing state -->
							<span class="fileupload-process"></span>
						</div>
						
						<div class="drop-area">
							<p>ここにファイルをドロップできます</p>
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
					
					<div class="fileupload-buttonbar">
						<button type="submit" class="btn btn-primary start fade">
							<i class="fa fa-cloud-upload" aria-hidden="true"></i>
							<span>一括送信</span>
						</button>
					</div>
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
			</button>
			{% } %} 
			{% if (!i) { %}
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
				<img src="{%=file.thumbnailUrl%}">
			{% } %}
			</span>
		</td>
		<td>
			<p class="name">
				<span>{%=file.name%}</span>
			</p>
				<span class="path" hidden>{%=file.url%}</span>
			{% if (file.error) { %}
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
				
			{% } else { %}
			<button class="btn btn-warning cancel">
				<i class="fa fa-ban" aria-hidden="true"></i>
				<span>キャンセル</span>
			</button> {% } %}
		</td>
	</tr>
	{% } %}
</script>

			</div>
		</div>

		<p class="scroll_top"><a href="#top">デザインのアップロード　ページトップへ</a></p>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		
		<div id="msgbox" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">メッセージ</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<p></p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary is-ok" data-dismiss="modal">OK</button>
						<button type="button" class="btn btn-default is-cancel" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		
		<script src="//code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
		<!--	<script src="/common/js/mdb.min.js"></script>-->
		<script src="js/upload/vendor/jquery.ui.widget.js"></script>
		<script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
		<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
		<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
		<script src="js/upload/jquery.iframe-transport.js"></script>
		<script src="js/upload/jquery.fileupload.js"></script>
		<script src="js/upload/jquery.fileupload-process.js"></script>
		<script src="js/upload/jquery.fileupload-image.js"></script>
		<script src="js/upload/jquery.fileupload-validate.js"></script>
		<script src="js/upload/jquery.fileupload-ui.js"></script>
		<script src="./js/upload/main.js?v=<?php echo $_version;?>"></script>
		<script src="/common/js/tlalib.js"></script>
	</body>

</html>
