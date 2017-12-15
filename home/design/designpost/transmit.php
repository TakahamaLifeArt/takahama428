<?php
ini_set('memory_limit', '128M');
require_once dirname(__FILE__).'/php_libs/mailer.php';
require_once dirname(__FILE__).'/php_libs/conndbpost.php';

if( isset($_REQUEST['ticket'], $_REQUEST['u']) ) {
	$conndb = new Conndbpost();
	
	$newpass = substr(sha1(_PASSWORD_SALT.time().mt_rand()),0,10);
	$args = array('userid'=>$_REQUEST['u'], 'pass'=>$newpass, 'temp'=>$newpass);
	$res = $conndb->updatePass($args);
	if($res){
		$dat = $conndb->getUserList($_REQUEST['u']);
		$args = array('email'=>$dat[0]['email'], 'newpass'=>$newpass, 'username'=>$dat[0]['customername']);
		$mailer = new Mailer($args);
		$isSend = $mailer->send();
	}
}
/*
else{
	unset($_SESSION['ticket']);
	header("Location: "._DOMAIN);
}
*/
/* セッションの使用を廃止
if($isSend){
	unset($_SESSION['ticket']);
}
*/
	
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="utf-8" />
        <meta name="keywords" content="<?php echo $categoryname; ?>,オリジナル<?php echo $categoryname; ?>,作成,プリント,東京,即日,最短" />
        <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
        <title>メール送信 ｜ オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="/items/css/items_style_responsive.css" media="screen" />
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" href="/design/designpost/css/finish_responsive.css" media="screen" />
    </head>

    <body>

        <header>
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
        </header>

        <div id="container">
            <div class="contents">

                <?php
				$cst = 'cst';
				function cst($constant){
					return $constant;
				}
				if($isSend){
					$heading = '仮パスワードを送信しています。<br>ご確認ください！';
					$sub = 'Sending';
					$html = <<<DOC
				<div class="inner">
					<p>この度はタカハマライフアートをご利用いただき、誠にありがとうございます。</p>
					<p>仮パスワードは、ログイン後にマイページで変更できます。</p>
				</div>
				<div class="inner">
					<h3>【 <span class="highlights">メールが届かない場合</span> 】</h3>
					<p>
						お客様が入力されました {$args['email']} 宛てに確認メールを返信しておりますが。届かない場合には、<br>
						お手数ですが下記の連絡先までお問い合わせください。<br>
						お急ぎのお客様は、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。
					</p>
					<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>
DOC;

				}else{
					$heading = '送信エラー！';
					$sub = 'Error';
					$html = <<<DOC
				<div class="inner">
					<div class="remarks">
						<p><strong>メールの送信が出来ませんでした。</strong></p>
						<p>メールの送信中にエラーが発生いたしました。</p>
					</div>
					<p>恐れ入りますが、再度 [ 送信 ] ボタンをクリックして下さい。</p>
				</div>
				<div class="inner">
					<h3>【 親切対応でしっかりサポート 】</h3>
					<p class="note">お急ぎのお客様は、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
					<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>
DOC;
				}
			?>

                    <div class="heading1_wrapper">
                        <h1>
                            <?php echo $heading;?>
                        </h1>
                        <p class="comment"></p>
                        <p class="sub">
                            <?php echo $sub;?>
                        </p>
                    </div>
                    <?php echo $html;?>
            </div>
        </div>

        <footer class="page-footer">
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
        </footer>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
        <script type="text/javascript" src="/common/js/jquery.js"></script>

    </body>

    </html>
