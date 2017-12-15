<?php
//include $_SERVER['DOCUMENT_ROOT']."/common/inc/pageinit.php";
$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
$_SESSION['ticket'] = $ticket;
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Description" content="「早い」作成で業界No.1最速のオリジナルTシャツプリントのタカハマライフアートの「デザインの作り方」ページです。デザインデータの種類ごとにデザインの作り方、入稿方法をご案内致します。1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。" />
        <meta name="keywords" content="デザイン,オリジナル,Tシャツ,作成,プリント" />
        <meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
        <meta property="og:url" content="https://www.takahama428.com/" />
        <meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
        <meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
        <meta property="fb:app_id" content="1605142019732010" />
        <title>デザインの作り方 ｜ オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico">
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" href="./css/designguide.css" media="screen" />
    </head>

    <body>
        <header>
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
        </header>
        <div id="container">
            <div class="contents">

                <ul class="pan hidden-sm-down">
                    <li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
                    <li>デザインの入稿・作り方</li>
                </ul>

                <div class="heading1_wrapper">
                    <h1>デザインの入稿・作り方</h1>
                </div>

                <div>
                    <h2>選べる入稿方法と注意点</h2>
                    <div class="section clearfix">
                        <div>
                            <p class="comment">入稿できるファイル形式は以下のとおりです。</p>
                        </div>
                        <div class="design_Method">
                            <div class="ok">
                                <div class="ok_ttl"><img src="/design/img/designguide/method/sp_d_ok.png" style="vertical-align:center;display: initial;box-shadow: none;" width="40px"><span style="display:inline-block;line-height:52px;padding-left:1%;font-weight: bold;width: 70px;">入稿OK</span></div>
                                <div class="ok_1">
                                    <img src="/design/img/designguide/method/sp_d_ok_01.png" width="100%">
                                    <p style="margin:0;">Illustrator・Photoshop<br><span class="red_txt">(CS～CS6)</span></p>
                                    <img src="/design/img/designguide/method/sp_d_ok_arrow.png" width="30%" style="display: initial;box-shadow: none;">
                                    <a href="/design/designguide_illust.php">
                                        <p class="method_button">作り方</p>
                                    </a>
                                </div>

                                <div class="ok_1_2">
                                    <img src="/design/img/designguide/method/sp_d_ok_02.png" width="100%">
                                    <p>手描き</p>
                                    <img src="/design/img/designguide/method/sp_d_ok_arrow.png" width="30%" style="display: initial;box-shadow: none;">
                                    <a href="/design/designguide_freehand.php">
                                        <p class="method_button">作り方</p>
                                    </a>
                                </div>

                                <div class="ok_2">
                                    <img src="/design/img/designguide/method/sp_d_ok_03.png" width="100%">
                                    <p class="txt">画像<br><span class="red_txt">(.jpg .png .pdf等)</span></p>
                                    <img src="/design/img/designguide/method/sp_d_ok_arrow.png" width="30%" style="display: initial;box-shadow: none;">
                                    <a href="/design/designguide_image.php">
                                        <p class="method_button">作り方</p>
                                    </a>
                                </div>
                            </div>
                            <div class="ng">
                                <div class="ng_ttl"><img src="/design/img/designguide/method/sp_d_ng.png" style="vertical-align:center;display: initial;box-shadow: none;" width="40px"><span style="display:inline-block;line-height:52px;padding-left:1%;font-weight: bold;width: 70px;">入稿NG</span></div>
                                <div class="ng_1">
                                    <div class="ng_1_img"><img src="/design/img/designguide/method/sp_d_ng_01.png" width="100%"></div>
                                    <p class="txt_top">エクセル・ワード・Power Point</p>
                                    <p class="txt_bottom"><span class="red_txt">※</span>上記の内容は受け付けで対応できません</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h2>プリントサイズイメージ</h2>
                    <div class="section clearfix">
                        <div>
                            <p class="comment">デザイン作成の際にご参照ください。</p>
                        </div>
                        <div class="size_image">
                            <div class="size_image_inner">
                                <div class="item_image">
                                    <p class="item_name">Tシャツ<br>(Mサイズ)</p>
                                    <div class="item_image_inner"><img src="/design/img/designguide/method/sp_d_image_01.jpg" width="100%">
                                        <p>横27cm×縦35cm</p>
                                    </div>
                                </div>
                                <div class="item_image">
                                    <p class="item_name">エプロン<br>(フリーサイズ)</p>
                                    <div class="item_image_inner"><img src="/design/img/designguide/method/sp_d_image_02.jpg" width="100%">
                                        <p>横20cm×縦20cm</p>
                                    </div>
                                </div>
                            </div>
                            <div class="size_image_inner">
                                <div class="item_image">
                                    <p class="item_name">ポロシャツ<br>(Mサイズ)</p>
                                    <div class="item_image_inner"><img src="/design/img/designguide/method/sp_d_image_03.jpg" width="100%">
                                        <p>横5cm×縦5cm</p>
                                    </div>
                                </div>
                                <div class="item_image">
                                    <p class="item_name">タオル<br>(フリーサイズ)</p>
                                    <div class="item_image_inner"><img src="/design/img/designguide/method/sp_d_image_04.jpg" width="100%">
                                        <p>横27cm×縦35cm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="comment_right"><span class="red_txt">※</span>アイテムによってプリント可能サイズが変わります。予めご了承ください。</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2>平置き・着用時のちがいについて</h2>

                    <div class="section clearfix">
                        <div>
                            <p class="comment">入稿は平置き(着用せず平らなところに置いた場合)になりますので、着用時は、湾曲により柄が大きく見えます。</p>
                        </div>
                        <div class="size_image">
                            <div class="item_image">
                                <p class="item_name">平置きイメージ</p>
                                <div class="item_image_inner"><img src="/design/img/designguide/method/sp_d_difference_01.jpg" width="100%">
                                </div>
                            </div>
                            <div class="item_image">
                                <p class="item_name">着用イメージ</p>
                                <div class="item_image_inner"><img src="/design/img/designguide/method/sp_d_difference_02.jpg" width="100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h2>著作権について</h2>

                    <div class="section_last clearfix">
                        <div>
                            <p class="comment">著作権・商標登録・肖像権・パブリシティー権のあるデザイン使用について</p>
                            <p class="comment_wrap">下記の内容をそのまま使用するのは不可となります。</p>
                            <div class="clearfix icon_wrap">
                                <ul>
                                    <li class="fl"><img alt="著作権" src="img/designguide/top/d_copy.png" width="325px"></li>
                                    <li class="fl">
                                        <ul>
                                            <li class="point1">企業ロゴやブランド・飲食物のロゴあるいはイラスト</li>
                                            <li class="point1">漫画やアニメ・ゲームのロゴまたはキャラクター</li>
                                            <li class="point1">ディズニーのキャラやロゴ</li>
                                            <li class="point1">アイドルやタレントの名前・歌手名・バンド名</li>
                                            <li class="point1">CDや雑誌の写真</li>
                                            <li class="point1">歌詞や曲のタイトル</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inner">
                    <ol>
                        <li class="point_num">キャラクターは、ライセンス所有者の貴重な財産です。特にメジャーなキャラクターの場合は、個人使用であっても絶対にダメです。ライセンス所有元の使用許諾が無ければお受けできません。マイナーなキャラクターの場合　でも必ず著作権を所有している元に使用許諾申請が必要です。許可があれば弊社でプリントすることが可能です。</li>
                        <li class="point_num">ただし、販売目的でない場合は、既存作品を借用をしながらも、オリジナリティを十分に表現できているデザインはパロディー作品と認めプリントをさせていただきます。</li>
                        <li class="point_num">© マークが入ったものは不可</li>
                        <li class="point_num">© マークは著作権をアピールするための物です。cマークが無ければ良いわけではなく、著作物となるとなるので　気をつけください。著作者に許可を取ることができれば弊社でプリントすることが可能です。</li>
                    </ol>
                    <h3 class="spc">お断りをする際の判断基準について</h3>
                    <ul>
                        <li class="point1">個人を特定できる、著名人等の似顔絵・シルエットを含んだデザイン。</li>
                        <li class="point1">パロディー元のデザインに対して、一部分でも全く手を加えていない箇所があるデザイン。</li>
                        <li class="point1">デザイン内容が違っていても第3者が見て本物と誤解を招く恐れのあるデザイン。</li>
                    </ul>
                    <h3>デザインに関する法的トラブルについて</h3>
                    <p>
                        デザインに関する法的トラブルが発生した場合、最終責任はお客様に帰属します。
                    </p>
                </div>
                <div>
                    <h2>おすすめコンテンツ</h2>
                    <div class="section clearfix">
                        <div>
                            <p class="comment">デザインをお手伝いする内容から申し込み用紙までおすすめコンテンツを紹介！</p>
                        </div>
                        <div class="rec_content_wrap">
                            <div class="rec_content">
                                <a href="/design/support.php"><img src="/design/img/designguide/method/d_support_btm.jpg" width="100%"></a>
                            </div>
                            <div class="rec_content">
                                <a href="/design/fontcolor.php#font_en"><img src="/design/img/designguide/method/d_font_btm.jpg" width="100%"></a>
                            </div>
                            <div class="rec_content">
                                <a href="/design/fontcolor.php#inclist"><img src="/design/img/designguide/method/d_inc_btm.jpg" width="100%"></a>
                            </div>
                            <div class="rec_content">
                                <a href="/design/template_illust.php"><img src="/design/img/designguide/method/d_template_btm.jpg" width="100%"></a>
                            </div>
                            <div class="rec_content">
                                <a href="/contact/faxorderform.pdf"><img src="/design/img/designguide/method/d_tegaki_btm.jpg" width="100%"></a>
                            </div>
                        </div>
                        <div class="rec_content_wrap_sp">
                            <div class="rec_content">
                                <a href="/design/support.php"><img src="/design/img/designguide/method/sp_d_support_btm.jpg" width="100%"></a>
                            </div>
                            <div class="rec_content">
                                <a href="/design/fontcolor.php#font_en"><img src="/design/img/designguide/method/sp_d_font_btm.jpg" width="100%"></a>
                            </div>
                            <div class="rec_content">
                                <a href="/design/fontcolor.php#inclist"><img src="/design/img/designguide/method/sp_d_inc_btm.jpg" width="100%"></a>
                            </div>
                            <div class="rec_content">
                                <a href="/design/template_illust.php"><img src="/design/img/designguide/method/sp_d_template_btm.jpg" width="100%"></a>
                            </div>

                            <a href="/contact/faxorderform.pdf">
                                <div class="rec_content_la">
                                    <p class="rec_la_ttl"><span class="pcyou">PC用</span>手描きデザイン用紙</p>
                                    <div class="last_img">
                                        <img src="/design/img/designguide/method/sp_d_tegaki_btm.jpg" width="100%">
                                    </div>
                                    <div class="last_txt">
                                        <p><span class="bold">入稿の時に便利！</span></p>
                                        <p><span class="red_txt">※</span>手描き入稿の方は手描き デザインシートをPCから ダウンロードできます。 是非ご利用くださいませ
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <p class="bold_last_txt">疑問点はお電話またはメールでお気軽にお問い合わせください！</p>
            </div>
        </div>
        <footer class="page-footer">
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
        </footer>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

        <div id="overlay-mask" class="fade"></div>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>

    </body>

    </html>
