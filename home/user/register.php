<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

$registered = 0;
if($_SERVER['REQUEST_METHOD']!='POST'){
	setToken();
	$isFirstView = 0;
}else{
	chkToken();
	$conndb = new Conndb();
	$err = user_registration($_POST);
	
	if($_POST['agree']) $chk_agree = 'checked="checked"';
	$isFirstView = 2;
}

$isFB = 0;

$me = checkLogin();
if($me && $me['agreed']==1){
	if($isFirstView==2) $registered = 1;	// 登録完了
	$ref = basename($_SERVER['SCRIPT_NAME']);
$menu =<<<DOC
<div class="menu" style="display: none";>
	<ul class="pull">
		<li><span>メニュー</span></li>
		<li><a href="./">写真一覧</a></li>
		<li><a href="mypage.php">マイページ</a></li>
		<li><a href="logout.php?refpg={$ref}">ログアウト</a></li>
	</ul>
</div>
<p class="uname">ようこそ {$_SESSION['me']['uname']} 様</p>
DOC;

}else{
	
	if($me && $me['agreed']==0){
	// 規約に同意していない
		$isFirstView = 1;
		if($me['fb']==1){
			$isFB = 1;
		}
	}
	
$menu =<<<DOC
<div class="menu" style:"display: none";>
	<ul class="pull">
		<li><span>メニュー</span></li>
		<li><a href="./">写真一覧</a></li>
		<li class="show_loginform"><span>ログイン</span></li>
	</ul>
</div>
DOC;

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="EUC-JP">
<!-- m3 begin -->
	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
<!-- m3 end -->
	<title>ユーザー登録 | タカハマライフアート</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
<!-- msgbox begin-->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<!-- msgbox end-->
<!-- m3 begin -->
	<link rel="stylesheet" type="text/css" href="/m3/common/css/common_responsive.css" media="all">
	<link rel="stylesheet" type="text/css" href="/m3/common/css/slidebars_responsive.css" media="all">
	<link rel="stylesheet" href="/m3/common/css/import_responsive.css">
	<link rel="stylesheet" href="/m3/items/css/detail_responsive.css">
<!-- m3 end -->
	<link rel="stylesheet" type="text/css" media="screen" href="/common/css/common_responsive.css">
	<link rel="stylesheet" type="text/css" media="screen" href="/common/css/base_responsive.css">
	<link rel="stylesheet" type="text/css" media="screen" href="/common/js/modalbox/css/jquery.modalbox.css">
	<link rel="stylesheet" type="text/css" media="screen" href="./css/style2_responsive.css">
	<link rel="stylesheet" type="text/css" media="screen" href="./css/login_responsive.css">
	<link rel="stylesheet" type="text/css" media="screen" href="./css/register_responsive.css">
<!-- msgbox begin-->
<!--
	<script type="text/javascript" src="/common/js/jquery.js"></script>
	<script type="text/javascript" src="/common/js/modalbox/jquery.modalbox-min.js"></script>
-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
	<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
<!-- msgbox end-->

	<script type="text/javascript" src="./js/common.js"></script>
    <script type="text/javascript" src="./js/register.js"></script>
    <script type="text/javascript">
    	var _FIRST_VIEW = <?php echo $isFirstView; ?>;
    	var _FB = <?php echo $isFB; ?>;
    	var _REGISTERED = <?php echo $registered; ?>;
    </script>
	<!--m3 begin-->
	<script src="/m3/common/js/common1.js"></script>
	<!--m3 end-->
</head>
<body>
	<?php
		$php = file_get_contents($_SERVER['DOCUMENT_ROOT']."/common/inc/header.php");
		eval('?>'.$php.'<?');
	?>
<div id="gnavi">
	<?php
		$php = file_get_contents($_SERVER['DOCUMENT_ROOT']."/common/inc/globalmenu.php");
		eval('?>'.$php. '<?');
	?>
</div>
	<!-- m3 begin -->
	<header id="header" class="head2">
		<?php include($_SERVER['DOCUMENT_ROOT']."/m3/common/inc/header.html"); ?>
	</header>
	<?php include($_SERVER['DOCUMENT_ROOT']."/m3/common/inc/gnavi.html"); ?>
	<!-- m3 end -->
	
	<div id="container">
		<div class="toolbar">
			<div class="toolbar_inner clearfix">
				<div class="menu_wrap"><?php echo $menu; ?></div>
				<h1>ユーザー登録</h1>
			</div>
		</div>

		<p class="comment">登録内容は、後で編集できます。<br>「<ins class="fontred">※</ins>」は必須です。</p>
		
		<form name="register" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" onSubmit="return false;">
			<table class="form_table" id="register_table">
				<tfoot>
					<tr>
						<td colspan="2">
							<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
							<input type="hidden" name="reg_site" value="1">
							<p><label><input type="checkbox" name="agree" id="agree" value="1" <?php echo $chk_agree;?> /> <a href="userpolicy.php" target="_blank">ご利用規約</a>に同意する</label></p>
							<p><span id="register_button">登録</span><span id="cancel_button">Cancel</span></p>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<th>メールアドレス<ins>※</ins></th>
						<td>
							<input type="text" name="email" value="<?php echo h($_POST['email']); ?>" autofocus>
							<p><ins class="err"> <?php echo $err['email']; ?></ins></p>
							<p class="note">ログインIDとして使います。</p>
						</td>
					</tr>
					<tr>
						<th>パスワード<ins>※</ins></th>
						<td>
							<input type="password" name="pass" value="<?php echo h($_POST['pass']); ?>">
							<p><ins class="err"> <?php echo $err['pass']; ?></ins></p>
						</td>
					</tr>
					<tr>
						<th>パスワード確認用<ins>※</ins></th>
						<td>
							<input type="password" name="passconf" value="<?php echo h($_POST['passconf']); ?>">
							<p><ins class="err"> <?php echo $err['passconf']; ?></ins></p>
						</td>
					</tr>
					<tr>
						<th>ユーザーネーム<ins>※</ins></th>
						<td>
							<input type="text" name="uname" value="<?php echo mb_convert_encoding(h($_POST['uname']),'utf-8', auto); ?>">
							<p><ins class="err"> <?php echo $err['uname']; ?></ins></p>
							<p class="note">後で変更できます。</p>
						</td>
					</tr>
					<!--
					<tr>
						<th>アイコン</th>
						<td>
							<input type="file" name="uicon"><ins class="err"> <?php echo $err['uicon']; ?></ins>
							<p class="note">使用できる画像タイプは、GIF、PNG、JPEG です。</p>
						</td>
					</tr>
					-->
						<!--
				<tr class="ext"><th colspan="2" style="padding-top: 3em;"><span class="highlights">ＱＵＯカード プレゼント</span><p>もれなく500円分のQUOカードをお送りしています！お届け先をご指定ください。</p></th></tr>
					<tr class="ext"><th>お名前</th><td><input type="text" value="" name="customername" id="customername" /></td></tr>
			 		<tr class="ext">
			 			<th>ご住所</th>
			 			<td>
			 				〒 <input type="text" value="" name="zipcode" id="zipcode" /><br>
			 				<input type="text" value="" name="addr" id="addr" />
			 			</td>
			 		</tr>					-->
				</tbody>
			</table>
			<!--
			<table class="form_table" id="extend_table">
				<caption>ＱＵＯカードプレゼント</caption>
				<tbody>
					<tr><th>お客様のお名前</th><td><input type="text" value="" name="customername" id="customername" /></td></tr>
			 		<tr>
			 			<th>ＱＵＯカード送付先住所</th>
			 			<td>
			 				〒 <input type="text" value="" name="zipcode" id="zipcode" /><br>
			 				<input type="text" value="" name="addr" id="addr" />
			 			</td>
			 		</tr>
				</tbody>
			</table>
			-->
		</form>
	</div>
<!--
	<div id="loginform_wrapper" style="display:none;">
		<form class="form_m" name="loginform" action="" method="post" onSubmit="return false;">
			<div class="close_form"></div>
-->
			<?php
				/*if($isFirstView){
					echo '<h3>初めてご利用の場合</h3>';
				}*/
			?>
			<!--<label>メールアドレス</label>
			<?php /*
				if($isFirstView){
					echo '<p class="username">'.$me['email'].'</p>';
				}else{
					echo '<input type="text" value="" name="username">';
				}*/
			?>
			<?php /*
				if(!$isFB){
					echo '<label>パスワード</label><input type="password" value="" name="password">';
				}*/
			?>
			<?php /*
				if($isFirstView){
					echo '<p><label><input type="checkbox" name="agree" id="agreed" value="1"> <a href="userpolicy.php" target="_blank">ご利用規約</a>に同意する</label></p>';
				}else{
					echo '<p class="note"><a href="resend_pass.php">パスワードを忘れた方はこちらへ</a></p>';
				}*/
			?>
			<div class="btn_wrap">
				<div id="login_button"></div>
				<?php /*
					if(!$isFirstView){
						echo '<a href="/php_libs/fb_login.php?refpg=designpost" class="fb_login">facebookでログインする</a>';
						echo '<a href="register.php">無料ユーザー登録へ</a>';
					}*/
				?>
			</div>
		</form>
	</div>
	
	<p class="scroll_top"><a href="#header">ユーザー登録　ページトップへ</a></p>
	<?php /*
		$php = file_get_contents($_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php");
		eval('?>'.$php. '<?');*/
	?>
	-->

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
	
<!-- msgbox begin-->
		<div id="msgbox" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">メッセージ</h4>
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
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- msgbox end-->

</body>
</html>

