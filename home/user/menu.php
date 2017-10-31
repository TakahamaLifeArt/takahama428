<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ��������֤Υ����å�
$me = checkLogin();
if(!$me){
	jump('login.php');
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="euc-jp" />
	<title>TLA���С��� | �����ϥޥ饤�ե�����</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
<!-- m3 begin -->
	<link rel="stylesheet" type="text/css" href="/m3/common/css/common_responsive.css" media="all">
	<link rel="stylesheet" type="text/css" href="/m3/common/css/slidebars_responsive.css" media="all">
	<link rel="stylesheet" href="/m3/common/css/import_responsive.css">
	<link rel="stylesheet" href="/m3/items/css/detail_responsive.css">
<!-- m3 end -->
	<link rel="stylesheet" type="text/css" media="screen" href="/common/css/common_responsive.css">
	<link rel="stylesheet" type="text/css" media="screen" href="/common/css/base_responsive.css">
	<link rel="stylesheet" type="text/css" media="screen" href="/common/js/modalbox/css/jquery.modalbox.css">
	<link rel="stylesheet" type="text/css" media="screen" href="./css/style_responsive.css" />
	<script type="text/javascript" src="/common/js/jquery.js"></script>
	<script type="text/javascript" src="/common/js/modalbox/jquery.modalbox-min.js"></script>
	<script type="text/javascript" src="./js/common.js"></script>
	<!-- OGP -->
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#  website: http://ogp.me/ns/website#">
	<meta property="og:title" content="������®�������ꥸ�ʥ�T����Ĥ������ž夲����" />
	<meta property="og:type" content="article" /> 
	<meta property="og:description" content="�ȳ�No. 1ûǼ���ǥ��ꥸ�ʥ�T����Ĥ�1�礫��������ޤ����̾�Ǥ�3���ǻž夲�ޤ���" />
	<meta property="og:url" content="http://www.takahama428.com/" />
	<meta property="og:site_name" content="���ꥸ�ʥ�T����Ĳ��å����ϥޥ饤�ե�����" />
	<meta property="og:image" content="http://www.takahama428.com/common/img/header/Facebook_main.png" />
	<meta property="fb:app_id" content="1605142019732010" />
	<!--  -->
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
	<!--m3 begin-->
	<script src="/m3/common/js/common1.js"></script>
	<!--m3 end-->
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
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/globalmenu.php"; ?>

	<!-- m3 begin -->
	<header id="header" class="head2">
		<?php include($_SERVER['DOCUMENT_ROOT']."/m3/common/inc/header.html"); ?>
	</header>
	<?php include($_SERVER['DOCUMENT_ROOT']."/m3/common/inc/gnavi.html"); ?>
	<!-- m3 end -->

	<div id="container">
		
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/sidenavi.php"; ?>
		
		<div class="contents">
			
			<div class="toolbar">
				<div class="toolbar_inner clearfix">
					<div class="menu_wrap">
						<?php echo $menu;?>
					</div>
				</div>
			</div>
			<div class="pagetitle"><h1>�ԣ̣����С���</h1></div>

			
			<div class="section" id="sec_menu">
				<h2>����ʸ����</h2>
				<ul>
					<li><a href="history.php">����ʸ����</a></li>
					<li><a href="progress.php">����οʹԾ���</a></li>
					<li><a href="credit.php">����ʧ������</a></li>
				</ul>
			</div>
			
			<div class="section" id="sec_menu">
				<h2>�����ӥ�</h2>
				<ul>
					<li><a href="progress.php">�֥���ƥե�����</a>�ʿʹԾ����ڡ�������ʤߤޤ���</li>
					<li><a href="history.php">�ɲ���ʸ��������</a>����ʸ����ڡ�������ʤߤޤ���</li>
				</ul>
			</div>
			
			<div class="section" id="sec_menu">
				<h2>���������</h2>
				<ul>
					<li><a href="account.php">��Ͽ����γ�ǧ���Խ�</a></li>
					<li><a href="resend_pass.php">�ѥ���ɤκ�ȯ��</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	<p class="scroll_top"><a href="#header">TLA���С������ڡ����ȥåפ�</a></p>

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

<!-- m3 begin -->
<div id="phonepage">
<div id="fb-root"></div>
<div id="container">
	<?php include($_SERVER['DOCUMENT_ROOT']."/m3/common/inc/footer.html"); ?>
	<div class="sb-slidebar sb-right">
	<?php include($_SERVER['DOCUMENT_ROOT']."/m3/common/sidemenu.html"); ?>
	</div>
<!-- /container --></div>
</div>
<!-- m3 end -->	
</body>
</html>