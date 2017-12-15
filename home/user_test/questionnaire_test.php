<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/package/mail/Mailer.php';
use package\mail\Mailer;

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

$conndb = new Conndb(_API_U);



// ユーザー情報を設定
$u = $conndb->getUserList($me['id']);
$username = $me['customername'];
$userkana = $me['customerruby'];
$email = $u[0]['email'];

//お届け先情報を再度取得
$deli = $conndb->getDeli($me['id']);
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Description" content="早い！Tシャツでオリジナルを作成するならタカハマへ！タカハマライフアートのログイン画面です。メールアドレスとパスワードを入れてください。マイページからご注文履歴などをご確認することができます。ログインにする為のパスワードをお忘れの方はこちら。">
        <meta name="keywords" content="オリジナル,tシャツ,メンバー">
        <meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
        <meta property="og:url" content="https://www.takahama428.com/" />
        <meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
        <meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
        <meta property="fb:app_id" content="1605142019732010" />
        <title>アカウント - TLAメンバーズ | タカハマライフアート</title>
        <link rel="shortcut icon" href="/icon/favicon.ico" />
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" media="screen" href="./css/questionnaire_test.css" />
        <style>
            .lightbox {
                display: none;
            }

        </style>

    </head>

    <body>
        <header>
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
        </header>

        <div id="container">
            <div class="contents">
                <div class="toolbar">
                    <div class="toolbar_inner clearfix">
                        <div class="pagetitle">
                            <h1 id="request">アンケート</h1>
                        </div>
                    </div>
                </div>

                <p>この度のタカハマライフアートへのご注文、 誠にありがとうございました。<br> 弊社では、よりお客様に安心してご注文して 頂けるように、下記のアンケートを実施しております。<br> ぜひともご協力をお願いいたします。
                </p>
                <form action="#">
                    <div><h3>1-1　商品、プリントの品質には満足できましたか？<span class="req">必須</span></h3><br> 満足
                        <label><input type="radio" name="cs_01" value="5">5</label>
                        <label><input type="radio" name="cs_01" value="4">4</label>
                        <label><input type="radio" name="cs_01" value="3">3</label>
                        <label><input type="radio" name="cs_01" value="2">2</label>
                        <label><input type="radio" name="cs_01" value="1">1</label> 不満
                    </div>
                    <div><h3>1-2　その理由があればお聞かせください</h3><br>
                        <input type="textarea" name="cs_01"></div>
                    <div><h3>2-1　スタッフの対応には満足できましたか?<span class="req">必須</span></h3><br> 満足
                        <label><input type="radio" name="cs_02" value="5">5</label>
                        <label><input type="radio" name="cs_02" value="4">4</label>
                        <label><input type="radio" name="cs_02" value="3">3</label>
                        <label><input type="radio" name="cs_02" value="2">2</label>
                        <label><input type="radio" name="cs_02" value="1">1</label>　 不満
                    </div>
                    <div><h3>2-2　その理由があればお聞かせください</h3><br>
                        <input type="textarea" name="cs_02"></div>
                    <div><h3>3-1　タカハマライフアートの「ここが使いづらい！」という点を教えてください。<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(複数回答可)<span class="req">必須</span></h3>
                        <br>
                        <label><input type="checkbox" name="hard_use" value="注文確定の電話">注文確定の電話</label><br>
                        <label><input type="checkbox" name="hard_use" value="商品の選び方">商品の選び方</label><br>
                        <label><input type="checkbox" name="hard_use" value="商品の素材や色">商品の素材や色</label><br>
                        <label><input type="checkbox" name="hard_use" value="実際のお届け日">実際のお届け日</label><br>
                        <label><input type="checkbox" name="hard_use" value="商品の見積もり">商品の見積もり</label><br>
                        <label><input type="checkbox" name="hard_use" value="デザインの入稿の方法">デザインの入稿の方法</label><br>
                        <label><input type="checkbox" name="hard_use" value="プリントサイズ">プリントサイズ</label><br>
                        <label><input type="checkbox" name="hard_use" value="注プリント方法">プリント方法</label><br>
                        <label><input type="checkbox" name="hard_use" value="割引の内容や条件">割引の内容や条件</label><br>
                        <label><input type="checkbox" name="hard_use" value="資料請求・商品サンプルの注文">資料請求・商品サンプルの注文</label><br>
                        <label><input type="checkbox" name="hard_use" value="ホームページ全体">ホームページ全体</label><br>
                        <label><input type="checkbox" name="hard_use" value="特になし">特になし</label><br></div>
                    <div><h3>4-1　全体を通して、ご意見ご感想がありましたらご記入お願いします</h3><br>
                        <input type="textarea" name="comment"></div>
                    <div><h3>5-1　写真掲載割をご利用のお客様は、商品到着後の感想やコメントをご入力ください。</h3><br>
                        <input type="textarea" name="comment_dc"></div>
                    <div><h3>5-2　写真掲載割をご利用のお客様は、商品着用写真をお送りください。</h3><br>
                        <input type="" name="pic"></div>
                    <p><input type="submit" value="回答を送信"></p>
                </form>

                <div class="transition_wrap d-flex justify-content-between align-items-center">
                    <div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left"></i>戻る</div>
                </div>

            </div>


        </div>
        <footer class="page-footer">
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
        </footer>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

        <div id="overlay-mask" class="fade"></div>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
        <script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
        <script type="text/javascript" src="./js/account.js"></script>
        <script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
    </body>

    </html>
