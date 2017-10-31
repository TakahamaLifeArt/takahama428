<?php
require_once dirname(__FILE__).'/funcs.php';


if(isset($_GET['email'])){
// 管理者用、指定したメールアドレスへのフォローメール送信を中止
	$conndb = new Conndb();
	
	if(empty($_GET['email'])){
		jump('/');
	}else if(!isValidEmailFormat($_GET['email'])){
		jump('/');
	}else{
		$users = $conndb->checkExistEmail(array($_GET['email']));
		if(empty($users)) jump('/');
	}
	
	$customer_id = $users[0]['id'];
	$username = mb_convert_encoding($users[0]['customername'], 'euc-jp', auto);
	setToken();
}else if(isset($_GET['u'])){
	$conndb = new Conndb();
	$customer_id = sprintf("%d", $_GET['u']);
	$users = $conndb->getUserList($customer_id);
	if(empty($users)) jump('/');
	$username = mb_convert_encoding($users[0]['customername'], 'euc-jp', auto);
	setToken();
}else if(isset($_POST['uid'], $_POST['email'])){
	chkToken();
	
	$conndb = new Conndb();
	
	$args['email'] = trim(mb_convert_kana($_POST['email'],"s", "euc-jp"));
	$customer_id = $_POST['uid'];
	$username = $_POST['uname'];
	
	if(empty($args['email'])){
		$err['email'] = 'メールアドレスを入力して下さい。';
	}else if(!isValidEmailFormat($args['email'])){
		$err['email'] = 'メールアドレスが正しくありません。';
	}else{
		$users = $conndb->checkExistEmail(array($args['email'], $_POST['uid']));
		if(empty($users)) $err['email'] = 'メールアドレスのご登録がありません。';
	}
	
	if(empty($err)) jump('cancelmail.php?token='.$_POST['token'].'&u='.$customer_id);
}else{
	jump('/');
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="euc-jp" />
	<title>カスタマーセンター | タカハマライフアート</title>
	<meta name="Description" content="" />
	<meta name="keywords" content="" />
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/common/css/common.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/common/css/base.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./css/style.css" media="screen" />

	<script type="text/javascript" src="/common/js/jquery.js"></script>
	<script type="text/javascript" src="/common/js/tlalib.js"></script>
	
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-11155922-2']);
		_gaq.push(['_trackPageview']);
		
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</head>
<body>


<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-T5NQFM"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-T5NQFM');</script>
<!-- End Google Tag Manager -->
	
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	
	<div id="container">
						
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/globalmenu.php"; ?>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/sidenavi.php"; ?>
		
		<div class="contents">
			<div class="heading1_wrapper">
				<h1>カスタマーセンター</h1>
				<p class="comment"></p>
				<p class="sub">Customer Center</p>
			</div>
			
			<h2>配信停止</h2>
			
			<div class="form_wrap">
				<form name="pass" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
					<p>今日は、<?php echo $username;?> 様</p>
					<table class="form_table me" id="pass_table">
						<caption>お知らせメールの配信を停止いたします。<br>ご登録のメールアドレスを入力して[ 送信 ]ボタンをクリックして下さい。</caption>
						<tfoot>
							<tr>
								<td colspan="2" class="toc">
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="uid" value="<?php echo $customer_id; ?>">
									<input type="hidden" name="uname" value="<?php echo $username; ?>">
									<input type="submit" name="send" value="送信">
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<th>メールアドレス</th>
								<td><input type="text" name="email" value="<?php echo $_REQUEST['email'];?>"><br><ins class="err"> <?php echo $err['email']; ?></ins></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			
		</div>
		
	</div>
	
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	

<!--Yahoo!タグマネージャー導入 2014.04 -->
<script type="text/javascript">
  (function () {
    var tagjs = document.createElement("script");
    var s = document.getElementsByTagName("script")[0];
    tagjs.async = true;
    tagjs.src = "//s.yjtag.jp/tag.js#site=bTZi1c8";
    s.parentNode.insertBefore(tagjs, s);
  }());
</script>
<noscript>
  <iframe src="//b.yjtag.jp/iframe?c=bTZi1c8" width="1" height="1" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
</noscript>
	
</body>
</html>