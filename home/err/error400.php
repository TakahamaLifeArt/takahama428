<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>400 Bad Request</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	
	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

	<!-- Styles -->
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<style>
		html,
		body {
			background-color: #fff;
			color: #636b6f;
			font-family: "Raleway", "ヒラギノ丸ゴ ProN", "Hiragino Maru Gothic ProN", "メイリオ", "Meiryo", "游ゴシック体", "Yu Gothic", sans-serif;
			font-weight: 100;
			height: 100vh;
			margin: 0;
		}

		.full-height {
			height: 100vh;
		}

		.flex-center {
			align-items: center;
			display: flex;
			justify-content: center;
		}

		.position-ref {
			position: relative;
		}

		.flex-center .content {
			text-align: center;
		}

		.flex-center .title {
			font-size: 36px;
			padding: 20px;
		}

	</style>
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>
	
	<div class="flex-center position-ref full-height">
		<div class="content">
			<div class="title">
<!--				Sorry, resource could not be read because the network status is unstable.-->
				ネットワークが不安定なため、リソースが読み込めませんでした。ページをリロードをしてください。
			</div>
		</div>
	</div>
	
	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>
</body>

</html>
