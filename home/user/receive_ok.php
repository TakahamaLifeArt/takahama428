<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

$isOK = true;

?>
    <!DOCTYPE html>
    <html lang="ja">

    <head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
        <meta charset="utf-8" />
        <meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
        <meta property="og:url" content="https://www.takahama428.com/" />
        <meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
        <meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
        <meta property="fb:app_id" content="1605142019732010" />
        <title>決済 - TLAメンバーズ｜ オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico" />

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" media="screen" href="./css/my_account.css" />
        <link rel="stylesheet" type="text/css" href="/contact/css/finish_responsive.css" media="screen" />

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
				if($isOK){
					$heading = 'お支払い完了';
					$html = <<<DOC
				<div class="inner">
					<p>
						この度はタカハマライフアートをご利用いただき、誠にありがとうございました。<br>
						Webサイトの表示が更新されるまでに1両日ほどかかる場合がございます。ご了承ください。
					</p>
				</div>
				<div class="inner">
					<h3>【 親切対応でしっかりサポート 】</h3>
					<p class="note">ご不明な点やお気づきのことがございましたら、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
					<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>
DOC;

				}else{
					$heading = 'エラー！';
					$html = <<<DOC
				<div class="inner">
					<div class="remarks">
						<p><strong>決済が出来ませんでした。</strong></p>
						<p>エラーが発生いたしました。</p>
					</div>
				</div>
				<div class="inner">
					<h3>【 親切対応でしっかりサポート 】</h3>
					<p class="note">ご不明な点やお気づきのことがございましたら、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
					<p><a href="/contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>
DOC;
				}
			?>


                    <div class="toolbar">
                        <div class="toolbar_inner clearfix">
                            <div class="menu_wrap">
                                <?php echo $menu;?>
                            </div>
                        </div>
                    </div>
                    <div class="pagetitle">
                        <h1>
                            <?php echo $heading;?>
                        </h1>
                    </div>

                    <?php echo $html;?>
            </div>

        </div>


        <footer class="page-footer">
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
        </footer>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
        <script type="text/javascript" src="/common/js/jquery.js"></script>
        <script type="text/javascript" src="./js/common.js"></script>




    </body>

    </html>
