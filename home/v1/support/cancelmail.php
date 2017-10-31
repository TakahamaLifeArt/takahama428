<?php
ini_set('memory_limit', '128M');
require_once dirname(__FILE__).'/funcs.php';

if(isset($_GET['u'], $_GET['token']) && $_SESSION['token']==$_GET['token']) {
	$conndb = new Conndb();
	
	$args = array('customer_id'=>$_GET['u'], 'cancel'=>1);
	$isSend = $conndb->unsubscribe($args);
	
	if($isSend){
		unset($_SESSION['token']);
		$_SESSION['token'] = array();
		//setcookie(session_name(), "", time()-86400, "/");
		//unset($_SESSION['token']);
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="euc-jp" />
	<title>�ۿ���� | �����ϥޥ饤�ե�����</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/common/css/common.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/common/css/base.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./css/finish.css" media="screen" />
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
			
			<?php
				$cst = 'cst';
				function cst($constant){
					return $constant;
				}
				if($isSend){
					$heading = '�ۿ���ߤμ�³����λ��';
					$sub = 'Unsubscribe';
					$html = <<<DOC
				<div class="inner">
					<p>�����٤ϥ����ϥޥ饤�ե����Ȥ����Ѥ������������ˤ��꤬�Ȥ��������ޤ���</p>
					<p>���Τ餻�᡼����ۿ���ߤμ�³������λ�������ޤ�����</p>
					<hr />
					<p class="gohome"><a href="/">�ۡ���ڡ��������</a></p>
				</div>
DOC;

				}else{
					$heading = '�������顼��';
					$sub = 'Error';
					$html = <<<DOC
				<div class="inner">
					<div class="remarks">
						<p>�ۿ���ߤμ�³�����Ǥ��ޤ���Ǥ�����</p>
					</div>
					<p>��������ޤ��������� [ ���� ] �ܥ���򥯥�å����Ʋ�������</p>
				</div>
				<div class="inner">
					<h3>�� �����б��Ǥ��ä��ꥵ�ݡ��� ��</h3>
					<p class="note">���ޤ��Τ����ͤϡ��ե꡼������� {$cst(_TOLL_FREE)} �ޤǤ����ڤˤ�Ϣ����������</p>
					<p><a href="/contact/">�᡼��ǤΤ��䤤��碌�Ϥ����餫��</a></p>
					<hr />
					<p class="gohome"><a href="/">�ۡ���ڡ��������</a></p>
				</div>
DOC;
				}
			?>
			
			<div class="heading1_wrapper">
				<h1><?php echo $heading;?></h1>
				<p class="comment"></p>
				<p class="sub"><?php echo $sub;?></p>
			</div>
			<?php echo $html;?>
		</div>
		
	</div>
	
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>


<!--Yahoo!�����ޥ͡����㡼Ƴ�� 2014.04 -->
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
