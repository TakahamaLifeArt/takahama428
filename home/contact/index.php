<?php
$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
$_SESSION['ticket'] = $ticket;
$_version = time();
?>
	<!DOCTYPE html>
	<html lang="ja">

	<head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  websites: //ogp.me/ns/website#">
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="Description" content="オリジナルプリントのTシャツ作成に関する疑問ははタカハマライフアートまでお気軽にお問い合わせくださいませ！お客様のお悩みをスッキリ解決致します！1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。" />
		<meta name="keywords" content="お問合せ,オリジナル,Tシャツ,早い,作成" />
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>お問い合わせ ｜ オリジナルTシャツ【タカハマライフアート】</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="/user/js/upload/jquery.fileupload.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/user/js/upload/jquery.fileupload-ui.css">
		<link rel="stylesheet" type="text/css" media="screen" href="/user/css/uploader.css">
		<link rel="stylesheet" type="text/css" href="./css/mailform_responsive.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="./css/custom_responsive.css" media="screen" />
	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">
				<ul class="pan hidden-sm-down">
					<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
					<li>お問い合わせ</li>
				</ul>
				<div class="heading1_wrapper" style="padding-bottom: 0px;">
					<h1>お問い合わせ</h1>
					<p class="comment">デザインのことや、ご予算、納期などお気軽にお問い合わせ下さい。</p>
				</div>

				<div class="bg hidden-sm-down">
					<p><img alt="お悩み解決" src="./img/contact_main.jpg" width="100%" /></p>
				</div>
				<div class="hidden-md-up">
					<p><img alt="お悩み解決" src="./img/sp_contact_main.jpg" width="100%" /></p>
				</div>


				<div class="round_corner_wrapper02">
					<div class="round_lt"></div>
					<div class="round_rt"></div>
					<div class="round_inner">

						<h2 class="title_confirmation">入力内容の確認</h2>
						<p class="point">「※」 は必須です。</p>
						<form id="fileupload" name="contact_form" method="post" action="/contact/transmit.php?req=contact" enctype="multipart/form-data" onsubmit="return false;">
							<table id="enq_table">
								<tbody>
									<tr>
										<td>
											<div id="table_left">お名前<span class="point">※</span></div>
											<div id="table_right">
												<p class="txt"></p><input name="customername" type="text" value=""></div>
										</td>
									</tr>
									<tr>
										<td>
											<div id="table_left">フリガナ<span class="point">※</span></div>
											<div id="table_right">
												<p class="txt"></p><input name="ruby" type="text" value=""></div>
										</td>
									</tr>
									<tr>
										<td>
											<div id="table_left">メールアドレス<span class="point">※</span></div>
											<div id="table_right">
												<p class="txt"></p><input name="email" type="text" class="email"></div>
										</td>
									</tr>
									<tr>
										<td>
											<div id="table_left">電話番号<span class="point">※</span></div>
											<div id="table_right">
												<p class="txt"></p><input name="tel" type="text" class="forPhone"></div>
										</td>
									</tr>
									<tr>
										<td>
											<div id="table_left">弊社ご利用<span class="point">※</span></div>
											<p class="txt"></p>
											<label><input name="repeater" type="radio" value="初めてのご利用"> 初めてのご利用</label>
											<label><input name="repeater" type="radio" value="以前にも注文したことがある"> 以前にも注文したことがある</label>
										</td>
									</tr>
									<tr>
										<td>
											<div id="table_left">お問合せ項目<span class="point">※</span></div>
											<p class="txt"></p>
											<label><input name="subtitle[]" type="checkbox" value="お見積り"> お見積り</label>
											<label><input name="subtitle[]" type="checkbox" value="デザイン"> デザインについて</label>
											<label><input name="subtitle[]" type="checkbox" value="納期"> 納期について</label>
										</td>
									</tr>
									<tr>
										<td>
											<div id="table_left">件　名<span class="point">※</span></div>
											<div id="table_right">
												<p class="txt"></p><input name="subject" type="text" id="subject" value="お問い合わせ"></div>
										</td>
									</tr>
									<tr>
										<td>
											<div id="table_left">メッセージ<span class="point">※</span></div>
											<div id="table_right">
												<p class="txt"></p><textarea name="message" id="message" cols="40" rows="7"></textarea></div>
										</td>
									</tr>
									<tr>
										<td>
											<div class="fileupload-buttonbar">
												<div class="">
													<!-- The fileinput-button span is used to style the file input field as button -->
													<span class="btn btn-success fileinput-button fade in">
														<i class="fa fa-plus" aria-hidden="true"></i>
														<span>ファイルを選択...</span>
													<input type="file" name="files[]" multiple>
													</span>
													<button type="submit" class="btn btn-primary start fade" hidden>
														<i class="fa fa-cloud-upload" aria-hidden="true"></i>
														<span>アップロード</span>
													</button>

													<!-- The global file processing state -->
													<span class="fileupload-process"></span>
												</div>

												<div class="drop-area hidden-sm-down">
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
										</td>
									</tr>
								</tbody>
							</table>
							<div id="conf_attach"></div>
							<input type="hidden" name="ticket" value="<?php echo $ticket; ?>">
							<input type="hidden" name="title" value="info">
							<input type="hidden" name="mode" value="confirm">
							<div class="button_area">
								<p class="msg">入力内容をご確認の上、よろしければ[ 送信 ]ボタンを押してください。</p>
								<button class="btn btn-primary" id="sendmail">入力内容の確認</button>
								<button class="btn btn-warning" id="goback" hidden>戻る</button>
							</div>
						</form>

						<div class="QA">
							<h2>よくあるご質問</h2>
							<dl class="list">
								<dt class="que">Q1.</dt>
								<dd>納期は間に合いますか？</dd>
								<dt class="ans">A1</dt>
								<dd class="mb">
									業界最速の当日特急プランがございますのでご安心を！<br> 詳しくは「
									<a href="/delivery/" target="_brank">お届日を調べる</a>」をご覧ください。
								</dd>

								<dt class="que">Q2.</dt>
								<dd>このデザインは使用可能ですか？</dd>
								<dt class="ans">A2.</dt>
								<dd class="mb">
									デザインにつきましては「<a href="/design/designguide.php" target="_brank">デザインの作り方</a>」をご覧ください。
								</dd>

								<dt class="que">Q3.</dt>
								<dd>持込は可能ですか？</dd>
								<dt class="ans">A3.</dt>
								<dd class="mb">
									可能ですが、条件がございます。<br>
									<ul>
										<li>アイテムの素材を確認して頂く</li>
										<li>値札タグを取りはずして頂く</li>
										<li>各サイズ1枚づつ予備をご用意して頂く</li>
										<li>弊社で取り扱いのないアイテムは実験用として1枚ご用意して頂く</li>
									</ul>
									以上ですが、ご不明な点がございましたらお問合せください。
								</dd>
							</dl>

							<p class="tor_pc"><a href="/guide/faq.php" target="_blank">よくあるご質問一覧はこちらへ</a></p>
						</div>
					</div>
					<div class="round_lb"></div>
					<div class="round_rb"></div>
				</div>
			</div>
		</div>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

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
						<img src="{%=file.thumbnailUrl%}">
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
					<button class="btn btn-danger delete fade" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}' {% } %}>
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
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script src="/user/js/upload/vendor/jquery.ui.widget.js"></script>
		<script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
		<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
		<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
		<script src="/user/js/upload/jquery.iframe-transport.js"></script>
		<script src="/user/js/upload/jquery.fileupload.js"></script>
		<script src="/user/js/upload/jquery.fileupload-process.js"></script>
		<script src="/user/js/upload/jquery.fileupload-image.js"></script>
		<script src="/user/js/upload/jquery.fileupload-validate.js"></script>
		<script src="/user/js/upload/jquery.fileupload-ui.js"></script>
		<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script src="./js/contact.js?v=<?php echo $_version;?>"></script>
		<script src="./js/upload/main.js"></script>
	</body>

	</html>
