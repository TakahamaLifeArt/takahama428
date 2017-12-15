<?php
//include $_SERVER['DOCUMENT_ROOT']."/common/inc/pageinit.php"; 
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head prefix="og: //ogp.me/ns# fb: //ogp.me/ns/fb#  website: //ogp.me/ns/website#">
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Description" content="「早い」作成で業界No.1最速のオリジナルTシャツプリント屋の「イラレ・フォトショのデザインで入稿編」ページ。イラレ・フォトショデザイン作成の注意点やご注文の流れをマンガでご説明致します。1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。" />
        <meta name="keywords" content="デザイン,オリジナル,Tシャツ,作成,プリント" />
        <meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
        <meta property="og:url" content="https://www.takahama428.com/" />
        <meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
        <meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
        <meta property="fb:app_id" content="1605142019732010" />
        <title>イラレ・フォトショのデザイン入稿編 ｜ オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico">
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" href="./css/illust.css" media="screen" />

    </head>

    <body>
        <header>
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
        </header>

        <div id="container">
            <div class="contents">
                <ul class="pan hidden-sm-down">
                    <li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
                    <li><a href="/design/designguide.php">デザインの入稿・作り方</a></li>
                    <li>イラレ・フォトショのデザインで入稿編</li>
                </ul>

                <div class="heading1_wrapper">
                    <h1>Illustrator・Photoshopで入稿</h1>
                </div>
                <h2 id="attn">デザインの注意点</h2>

                <div class="content-lv2">

                    <div class="tab-content">
                        <input type="radio" id="tab1" name="tab" checked>
                        <label for="tab1"><img src="/design/img/designguide/illustdata/adobe_icon_ai.png" class="imgsp" alt="" width="30px" />Illustrator</label>
                        <input type="radio" id="tab2" name="tab">
                        <label for="tab2"><img src="/design/img/designguide/illustdata/adobe_icon_ps.png" class="imgsp" alt="" width="30px" />Photoshop</label>
                        <div class="tab-box">
                            <div id="tabView1">
                                <div class="section clearfix">
                                    <div>
                                        <div class="row">
                                            <p class="col-6 bld_9"><img src="/design/img/designguide/illustdata/sp_aips_required_ai.png" alt="" width="120px" style="display: initial; " /></p>
                                            <p class="col-6 bld_10">Illustrator<br><span class="txt_large">7つの条件</span></p>
                                        </div>
                                        <div class="redline"></div>

                                        <span id="cmyk" class="anchorlink"></span>
                                        <div class="circle">1
                                            <h3 class="flt1_h3" id="cmyk">色彩表現はCMYK</h3>
                                        </div>
                                        <div class="fl_grp">
                                            <div class="flblock">
                                                <p class="atten"><i class="fa fa-exclamation-triangle fa-3x red_txt" aria-hidden="true"></i><br>RGB等<br> 他のカラーモードは
                                                    <br> 変色してしまいます
                                                </p>
                                                <p class="rgtimg"><img src="/design/img/designguide/illustdata/sp_aips_cmyk_monitor.png" alt="" width="100%" /></p>
                                            </div>
                                            <i class="fa fa-arrow-right arrow1 fa-3x" aria-hidden="true"></i><i class="fa fa-arrow-down arrow2 fa-3x" aria-hidden="true"></i>
                                            <div class="flblock">
                                                <p class="atten mgtop"><span class="red_txt">CMYKで作成！</span><br> プリントは印刷物
                                                </p>
                                                <p class="rgtimg"><img src="/design/img/designguide/illustdata/sp_aips_cmyk_tshirt.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>

                                        <span id="size" class="anchorlink"></span>
                                        <div class="circle">2
                                            <h3 class="flt2_h3" id="size">デザインの大きさはプリントする原寸のサイズ</h3>
                                        </div>
                                        <div class="fl_grp">
                                            <div class="flbox">
                                                <p class="atten mgtop">プリントしたい大きさでデザインを作成<br><span class="red_txt">※</span>解像度は<span class="red_txt">300dpi</span></p>
                                                <p class="rgtimg"><img src="/design/img/designguide/illustdata/sp_aips_-printsize.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>

                                        <span id="ume" class="anchorlink"></span>
                                        <div class="circle">3
                                            <h3 class="flt3_h3" id="pic">画像を配置している場合は画像を埋め込む</h3>
                                        </div>
                                        <div class="fl_mg">
                                            <p class="mgsp"><i class="fa fa-exclamation-triangle fa-2x red_txt" aria-hidden="true"></i>画像が埋め込みされていない場合、こちらで画像を見ることができなくなってしまいます。</p>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">【画像の埋め込み方】</span><br>「ウィンドウ」から「リンク」を表示し、画像を選択した状態でリンクのウィンドウの右上を押し、 「画像の埋め込み」を選択
                                                </p>
                                                <p class="rgtimg"><img src="/design/img/designguide/illustdata/sp_aips_placement.png" alt="" width="100%" /></p>
                                            </div>
                                            <div class="flbox">
                                                <p class="atten mgtop2"><span class="bld_img"><i class="fa fa-circle-o fa-2x" aria-hidden="true"></i></span></p>
                                                <ul class="disc">
                                                    <li>画像に×線が表示されない</li>
                                                    <li>リンクの右側に埋め込みマークが表示される</li>
                                                </ul>
                                                <p class="rgtimg2"><img src="/design/img/designguide/illustdata/sp_aips_placement_no_01.png" alt="" width="100%" /></p>
                                                <p class="rgtimg2"><img src="/design/img/designguide/illustdata/sp_aips_placement_ok_02.png" alt="" width="100%" /></p>
                                            </div>
                                            <div class="flbox">
                                                <p class="atten mgtop2"><span class="bld_img2"><i class="fa fa-times fa-2x" aria-hidden="true"></i></span></p>
                                                <ul class="disc">
                                                    <li>画像に×線が表示される</li>
                                                    <li>リンクの右側に何も表示されない</li>
                                                </ul>
                                                <p class="rgtimg2"><img src="/design/img/designguide/illustdata/sp_aips_placement_ok_01.png" alt="" width="100%" /></p>
                                                <p class="rgtimg2"><img src="/design/img/designguide/illustdata/sp_aips_placement_no_02.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>

                                        <span id="mmspace" class="anchorlink"></span>
                                        <div class="circle">4
                                            <h3 class="flt4_h3" id="font">シルクスクリーンプリントは、線0.3ｍｍ、隙間1ｍｍ以上推奨</h3>
                                        </div>
                                        <div class="fl_mg">
                                            <div class="flbox">
                                                <ul>
                                                    <li class="red_txt"><i class="fa fa-exclamation-triangle fa-2x red_txt" aria-hidden="true"></i>シルクスクリーンプリントの場合、線が 0.3ｍｍ、隙間が1ｍｍ以上ないときれいに表現出来ないことがございます。
                                                    </li>
                                                    <li class="lef_txt">インクがのる部分＝<i class="fa fa-square fa-1x" aria-hidden="true"></i></li>
                                                </ul>
                                                <p class="rgtimg2"><img src="/design/img/designguide/illustdata/sp_aips_silk_01.png" alt="" width="100%" /></p>
                                                <p class="rgtimg2"><img src="/design/img/designguide/illustdata/sp_aips_silk_02.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>

                                        <span id="outline" class="anchorlink"></span>
                                        <div class="circle">5
                                            <h3 class="flt5_h3" id="outline">テキストと線にアウトラインをかける</h3>
                                        </div>
                                        <div class="fl_mg">
                                            <ul>
                                                <li class="red_txt"><i class="fa fa-exclamation-triangle fa-2x red_txt" aria-hidden="true"></i>テキスト・線にアウトラインがかけられていない場合、こちらでデザインが正しく表示されなくなってしまします。</li>
                                                <li class="lef_txt">
                                                    <p class="atten mgtop"><span class="bld_txt">【テキストのアウトラインのかけ方】</span><br>テキストを選択した状態で「書式」から「アウトラインを作成」を選択</p>
                                                </li>
                                            </ul>
                                            <div class="fl_blk">
                                                <div class="rgtimg2">
                                                    <p class="rgtimg"><img src="/design/img/designguide/illustdata/sp_aips_font_outline.png" alt="" width="100%" /></p>
                                                </div>
                                                <div class="rgtimg2">
                                                    <p class="atten mgtop"><span class="bld_txt">アウトライン前</span></p>
                                                    <p class="rgtimg"><img src="/design/img/designguide/illustdata/sp_aips_font_outline_02.jpg" alt="" width="100%" /></p>
                                                    <i class="fa fa-caret-down fa-3x" aria-hidden="true"></i>
                                                    <p class="atten mgtop"><span class="bld_txt">アウトライン後</span></p>
                                                    <p class="rgtimg"><img src="/design/img/designguide/illustdata/sp_aips_font_outline_03.jpg" alt="" width="100%" /></p>
                                                </div>
                                            </div>
                                            <ul>
                                                <li>
                                                    <p class="atten mgtop"><span class="bld_txt">【テキストのアウトラインのかけ方】</span><br>①テキストを選択した状態で「書式」から「アウトラインを作成」を選択</p>
                                                </li>
                                                <li class="lef_txt">②オブジェクトを選択した状態で「オブジェクト」から「分割・拡張」を選択する</li>
                                                <li class="lef_txt"><span class="red_txt">※</span>「分割・拡張」が選択出来ない場合は、ひとつ下の「アピアランスを分割」を選択した後に行う</li>
                                            </ul>
                                            <div class="flbox">
                                                <p class="rgtimg1"><img src="/design/img/designguide/illustdata/sp_aips_line_outline.png" alt="" width="100%" /></p>
                                                <p class="rgtimg1"><img src="/design/img/designguide/illustdata/sp_aips_line_outline_ok.png" alt="" width="100%" /></p>
                                                <p class="rgtimg1"><img src="/design/img/designguide/illustdata/sp_aips_line_outline_no.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>

                                        <span id="warp" class="anchorlink"></span>
                                        <div class="circle">6
                                            <h3 class="flt6_h3" id="warp">ワープを使ったツールは分割する</h3>
                                        </div>
                                        <div class="fl_mg">
                                            <ul>
                                                <li>
                                                    <p class="atten mgtop"><span class="bld_txt">【ワープの分割の仕方】</span></p>
                                                </li>
                                                <li class="lef_txt">オブジェクトを選択した状態で「オブジェクト」から「分割・拡張」を選択する</li>
                                                <li class="lef_txt"><span class="red_txt">※</span>「分割・拡張」が選択出来ない場合は、ひとつ下 の「アピアランスを分割」を選択した後に行う</li>
                                            </ul>
                                            <div class="flbox">
                                                <p class="rgtimg1"><img src="img/designguide/illustdata/sp_aips_warp_01.png" alt="" width="100%" /></p>
                                                <p class="rgtimg1"><img src="img/designguide/illustdata/sp_aips_warp_02.png" alt="" width="100%" /></p>
                                                <p class="rgtimg1"><img src="img/designguide/illustdata/sp_aips_warp_03.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>

                                        <span id="cs6" class="anchorlink"></span>
                                        <div class="circle">7
                                            <h3 class="flt7_h3" id="illu">保存形式はCS～CS6</h3>
                                        </div>
                                        <div class="fl_mg">
                                            <p class="mgsp"><span class="bld_txt">【バージョンの下げ方】</span></p>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">1</span>「ファイル」から「別名で保存」を選択</p>
                                                <p class="rgtimg3"><img src="img/designguide/illustdata/sp_aips_downgrade_01.png" alt="" width="100%" /></p>
                                            </div>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">2</span>ファイル名をつけて、ファイルの種類は「Adobe Illustrator」を選択</p>
                                                <p class="rgtimg3"><img src="img/designguide/illustdata/sp_aips_downgrade_02.png" alt="" width="100%" /></p>
                                            </div>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">3</span>Illustratorオプションが表示されるので「バージョン」の隣の枠を選択</p>
                                                <p class="rgtimg3"><img src="img/designguide/illustdata/sp_aips_downgrade_03.jpg" alt="" width="100%" /></p>
                                            </div>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">4</span>バージョンを選択して「OK」を押す<br><span class="red_txt">※</span>弊社はCS～CS6まで対応</p>
                                                <p class="rgtimg3"><img src="img/designguide/illustdata/sp_aips_downgrade_04.jpg" alt="" width="100%" /></p>
                                            </div>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">5</span>次の表示がでたら「OK」を押す</p>
                                                <p class="rgtimg3"><img src="img/designguide/illustdata/sp_aips_downgrade_05.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>
                                        <div class="clearfix red_grp">
                                            <p class="txtleft"><span class="imgleft"><i class="fa fa-exclamation-triangle fa-2x red_txt" aria-hidden="true"></i></span>以上7つの条件の1つでも抜けると正しいデザインで仕上げることができません。
                                            </p>
                                            <p class="txtleft"><span class="leftpsn">再入稿になりますので、ご注意ください。</span></p>
                                        </div>
                                        <div class="clearfix bdr_grp">
                                            <div class="flblock1">
                                                <p class="atten1"><img src="img/designguide/illustdata/sp_aips_discount_thousand.png" alt="" width="100%" />
                                                </p>
                                                <div class="rgtimg4">
                                                    <p class="txtleft1">全ての注意点を守り、全てアウトライン化した完全データの入稿で1000円割引！</p>
                                                    <p class="txtleft1"><span class="red_txt">※</span>画像が含まれている場合は対象外となります。</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix mark_grp">
                                            <h3 class="txtleft2">イラレテンプレート</h3>
                                            <p class="txtleft3">Illustratorでデザインを作成するお客様におすすめ！ Tシャツ・ポロシャツなどの絵型のテンプレートを使ってイメージしながら入稿できます。
                                            </p>
                                            <div class="flblock1">
                                                <p class="atten"><img src="img/designguide/illustdata/sp_aips_template.png" alt="" width="100%" /><br>デザインを絵型にのせて入稿するだけ！
                                                </p>
                                                <div class="rgtimg">
                                                    <a class="next_btn" href="/design/template_illust.php">ダウンロードはこちら</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="txtnone">
                                            <h3 class="txtleft2">デザインを絵型にのせて入稿するだけ！</h3>
                                            <p class="txtleft10">Illustratorでデザインを作成するお客様におすすめ！
                                            </p>
                                            <p class="txtleft10">Illustrator入稿の方はテンプレートをPCからダウンロードできます。是非ご利用くださいませ。
                                            </p>
                                            <p class="atten"><img src="img/designguide/illustdata/sp_aips_template.png" alt="" width="100%" /><br>デザインを絵型にのせて入稿するだけ！
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div id="tabView2">
                                <div class="section clearfix">
                                    <div>
                                        <div class="row">
                                            <p class="col-6 bld_9"><img src="img/designguide/illustdata/sp_aips_required_ps.png" alt="" width="120px" style="display: initial;" /></p>
                                            <p class="col-6 bld_10">Photoshop<br><span class="txt_large">4つの条件</span></p>
                                        </div>
                                        <div class="redline"></div>
                                        <div class="circle">1
                                            <h3 class="flt1_h3" id="cmyk">色彩表現はCMYK</h3>
                                        </div>
                                        <div class="fl_grp">
                                            <div class="flblock">
                                                <p class="atten"><i class="fa fa-exclamation-triangle fa-3x red_txt" aria-hidden="true"></i><br>RGB等<br> 他のカラーモードは
                                                    <br> 変色してしまいます
                                                </p>
                                                <p class="rgtimg"><img src="img/designguide/illustdata/sp_aips_cmyk_monitor.png" alt="" width="100%" /></p>
                                            </div>
                                            <i class="fa fa-arrow-right arrow1 fa-3x" aria-hidden="true"></i><i class="fa fa-arrow-down arrow2 fa-3x" aria-hidden="true"></i>
                                            <div class="flblock">
                                                <p class="atten mgtop"><span class="red_txt">CMYKで作成！</span><br> プリントは印刷物
                                                </p>
                                                <p class="rgtimg"><img src="img/designguide/illustdata/sp_aips_cmyk_tshirt.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>
                                        <div class="circle">2
                                            <h3 class="flt2_h3" id="size">デザインの大きさはプリントする原寸のサイズ</h3>
                                        </div>
                                        <div class="fl_grp">
                                            <div class="flbox">
                                                <p class="atten mgtop">プリントしたい大きさでデザインを作成<br><span class="red_txt">※</span>解像度は<span class="red_txt">300dpi</span></p>
                                                <p class="rgtimg"><img src="img/designguide/illustdata/sp_aips_-printsize.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>
                                        <div class="circle">3
                                            <h3 class="flt4b_h3" id="font">シルクスクリーンプリントは、線0.3ｍｍ、隙間1ｍｍ以上推奨</h3>
                                        </div>
                                        <div class="fl_mg">
                                            <div class="flbox">
                                                <ul>
                                                    <li class="red_txt"><i class="fa fa-exclamation-triangle fa-2x red_txt" aria-hidden="true"></i>シルクスクリーンプリントの場合、線が 0.3ｍｍ、隙間が1ｍｍ以上ないときれいに表現出来ないことがございます。
                                                    </li>
                                                    <li class="lef_txt">インクがのる部分＝<i class="fa fa-square fa-1x" aria-hidden="true"></i></li>
                                                </ul>
                                                <p class="rgtimg2"><img src="img/designguide/illustdata/sp_aips_silk_01.png" alt="" width="100%" /></p>
                                                <p class="rgtimg2"><img src="img/designguide/illustdata/sp_aips_silk_02.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>
                                        <div class="circle">4
                                            <h3 class="flt5b_h3" id="illu">テキストをラスタライズする</h3>
                                        </div>
                                        <div class="fl_mg">
                                            <ul>
                                                <li class="red_txt"><i class="fa fa-exclamation-triangle fa-2x red_txt" aria-hidden="true"></i>テキストがラスタライズされていない場合、こちらでデザインが正しく表示されなくなってしまします。
                                                </li>
                                            </ul>
                                            <p class="mgsp"><span class="bld_txt">【テキストのアウトラインのかけ方】</span></p>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">1</span>図のように「T」の文字が表示されているレイヤーはラスタライズされていない ものである。「T」の文字が表示されているレイヤー上で右クリックする。
                                                </p>
                                                <p class="rgtimg3"><img src="img/designguide/illustdata/sp_aips_rasterization_01.png" alt="" width="100%" /></p>
                                            </div>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">2</span>右クリックするとメニューが出てくるので「テキストをラスタライズ」を選択する</p>
                                                <p class="rgtimg3"><img src="img/designguide/illustdata/sp_aips_rasterization_02.jpg" alt="" width="100%" /></p>
                                            </div>
                                            <div class="flbox">
                                                <p class="atten mgtop"><span class="bld_txt">3</span>レイヤーの「T」の文字が消えてテキストがラスタライズされた状態になる</p>
                                                <p class="rgtimg3"><img src="img/designguide/illustdata/sp_aips_rasterization_03.png" alt="" width="100%" /></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix red_grp">
                                        <p class="txtleft"><span class="imgleft"><i class="fa fa-exclamation-triangle fa-2x red_txt" aria-hidden="true"></i></span>以上4つの条件の1つでも抜けると正しいデザインで仕上げることができません。
                                        </p>
                                        <p class="txtleft"><span class="leftpsn">再入稿になりますので、ご注意ください。</span></p>
                                    </div>
                                    <p class="comment1"><span class="red_txt">※</span>弊社ではCS～CS6まで対応しています</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div>
                    <h2 id="draft">早速注文する！</h2>

                    <div class="section clearfix">
                        <div>
                            <p class="comment">デザインデータは申し込みの際にご入稿ください。</p>
                            <div class="order_bubble">
                                <a href="/order/index.php" class="order_btn"><img src="/delivery/img/deli/sp_go_icon.png" width="20%;" style=" display: initial;">お申し込み</a>
                                <img class="bubble_img" src="/delivery/img/deli/sp_go_min.png" width="100%;"></div>
                        </div>
                        <p class="comment1">申し込み後に入稿、再入稿される方は<a href="mailto:info@takahama428.com">info@takahama428.com</a>までお送り下さい。</p>
                    </div>
                </div>
                <div>
                    <div>
                        <h2 class="txtleft4">おすすめコンテンツ</h2>
                        <div class="section clearfix inner">
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
                                    <a href="/design/designguide_freehand.php"><img src="/design/img/designguide/method/d_tegaki_btm.jpg" width="100%"></a>
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
                    <div class="section clearfix">
                        <div>
                            <p class="comment1">データを送りたい方や相談はこちら！</p>
                            <a href="/design/designguide.php" class="next_btn_2">デザインの入稿TOPへ戻る</a>
                        </div>
                    </div>

                </div>
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
