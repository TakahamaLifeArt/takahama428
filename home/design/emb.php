<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/orders.php';
$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
$_SESSION['ticket'] = $ticket;
$order = new Orders();
for($cnt=4,$i=2; $cnt>1; $cnt--,$i--){
	$fin[$i] = $order->getDelidate(null, 1, $cnt);
}

$ua=$_SERVER['HTTP_USER_AGENT'];
if((strpos($ua,' iPhone')!==false)||(strpos($ua,' iPod')!==false)||(strpos($ua,' Android')!==false)) {
	$txt_SP02 = '<p><a href="tel:0120130428" style="margin-left:0px;font-size:60px;font-weight:bold;">0120-130-428</a></p>';
	$txt_SP02 .= '<p class="note" style="margin-left:95px;">受付時間：平日10:00-18:00</p>';
}else{
	$txt_SP02 = '<img src="img/phoneno.png" width="456" height="104" alt="TEL:0120-130-428 受付時間：平日10:00-18:00"><br>';
}

// category selector
$data = $order->categoryList();
$category_selector = '<select id="category_selector" name="category">';
$category_selector .= '<option value="" selected="selected">-</option>';
for($i=0; $i<count($data); $i++){
	$categoryName = mb_convert_encoding($data[$i]['name'], 'euc-jp','utf-8');
	$category_selector .= '<option value="'.$data[$i]['code'].'" rel="'.$data[$i]['id'].'"';
	$category_selector .= '>'.$categoryName.'</option>';
}
$category_selector .= '</select>';
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Description" content="業界最速Tシャツへの刺繍ならタカハマライフアート！最短2日で納品が可能、一枚からでも簡単注文。ロゴや名入れネーム、持ち込みにも対応！ポロシャツ、エプロン、はっぴ、キャップ、スタッフジャンパーにも刺繍作成可能です。オリジナルTシャツ作成が早い" />
        <meta name="keywords" content="オリジナル,刺繍,Tシャツ,早い,ポロシャツ" />
        <title>刺繍・名入れサービス ｜ オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico">
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" href="css/style_emb.css">
    </head>

    <body>
        <header>
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
        </header>
        <div class="container-fluid">
        </div>

        <div id="container">
            <div class="contents">
                <ul class="pan hidden-sm-down">
                    <li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
                    <li>刺繍・名入れサービス</li>
                </ul>

                <div class="toolbar">
                    <div class="toolbar_inner clearfix">
                        <h1>刺繍・名入れサービス</h1>
                    </div>
                </div>
                <div id="main" class="delivery">
                    <div class="banner"><img src="img/other/st_main_img.jpg" width="100%" alt="刺繍・名入れサービス"></div>
                    <div class="heading1_wrapper">

                    </div>
                    <div class="button_menu">
                        <ul>
                            <li class="button_left">
                                <a class="button" href="#emb_date">刺繍の種類</a>
                            </li>
                            <li class="button_left">
                                <a class="button" href="#emb_date2">価格一覧</a>
                            </li>
                            <li class="button_left">
                                <a class="button" href="#itoiro">糸色一覧</a>
                            </li>
                            <li class="button_left">
                                <a class="button" href="#font">書体一覧</a>
                            </li>
                        </ul>
                    </div>
                    <div id="delivery_date">
                        <h2>納期</h2>
                        <div class="dotted"></div>
                        <div class="derileft"><img src="img/other/shi_date_img.jpg" alt="最短明日お届け" width="100%"></div>
                        <div class="derileft_2">
                            <p class="nouki_text">弊社では、刺繍商品を最短翌日仕上げが可能です！</p>
                            <p><span class="smtxt">お急ぎの方は特急対応で翌日仕上げまたは2日仕上げでご対応致します。<br>それぞれ別途料金が発生します。<br>通常納期でも3営業日で発送するスピーディーな対応が可能です。</span></p>
                        </div>

                    </div>

                    <div id="delivery_date_wrapper">
                        <div id="delivery_date1">
                            <p class="txt1">今すぐ
                                <span class="red_txt">注文確定</span>した場合の<span class="red_txt">お届け日</span>(注文〆13時まで)
                            </p>

                            <div class="time">
                                <ul class="box_time">
                                    <li class="block1">
                                        <section class="round2">
                                            <div class="block_sq">
                                                <p class="text"><span class=red_txt>翌日</span>仕上げ</p>
                                            </div>
                                            <div class="block_sq1">
                                                <div class="block_sq2">
                                                    <table class="bk_table">
                                                        <tr id="result_date3">
                                                            <td class="dt">
                                                                <p>
                                                                    <?php echo $fin[0]['Month'];?>
                                                                </p>
                                                            </td>
                                                            <td>/</td>
                                                            <td class="dt">
                                                                <p>
                                                                    <?php echo $fin[0]['Day'];?>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </section>
                                    </li>
                                    <li class="block1">
                                        <section class="round3">
                                            <div class="block_sq">
                                                <p class="text"><span class=red_txt>2日</span>仕上げ</p>
                                            </div>
                                            <div class="block_sq1">
                                                <div class="block_sq2">
                                                    <table class="bk_table">
                                                        <tr id="result_date2">
                                                            <td class="dt">
                                                                <p>
                                                                    <?php echo $fin[1]['Month'];?>
                                                                </p>
                                                            </td>
                                                            <td>/</td>
                                                            <td class="dt">
                                                                <p>
                                                                    <?php echo $fin[1]['Day'];?>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </section>
                                    </li>
                                    <li class="block1">
                                        <section class="round4">
                                            <div class="block_sq">
                                                <p class="text">通常<span class=red_txt>3日</span>仕上げ</p>
                                            </div>
                                            <div class="block_sq1">
                                                <div class="block_sq2">
                                                    <table class="bk_table">
                                                        <tr id="result_date">
                                                            <td class="dt">
                                                                <p class="date">
                                                                    <?php echo $fin[2]['Month'];?>
                                                                </p>
                                                            </td>
                                                            <td>/</td>
                                                            <td class="dt">
                                                                <p class="date">
                                                                    <?php echo $fin[2]['Day'];?>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </section>
                                    </li>
                                </ul>
                            </div>
                            <p class="undertxt">(※) お届け先が、北海道、九州、沖縄、東京離島、島根隠岐郡　のいずれかとなる場合は表示日数より１日多くかかります。</p>
                        </div>
                    </div>
                </div>

                <div class="offpc">
                    <h4 class="red_txt_2">※時期や枚数によって上記の納期より多く日数をいただく場合がございます。詳細はお問い合わせ下さい！</h4>

                </div>

                <div id="emb_date">
                    <h2>刺繍の種類</h2>
                    <div class="dotted"></div>
                    <div class="derileft">
                        <h3>ネーム刺繍</h3>
                        <img src="img/other/shi_type_01.jpg" alt="最短明日お届け" width="100%">
                        <p class="undertxt"><span style="color:#dc0000;">※</span>糸色1色のみ</p>
                        <p class="mid">文字の方(1行のみ)</p>
                        <p>型代不要！<br>当社指定のフォントをお選びいただき、お好きな文言を刺繍できます。</p>
                        <li class="syotaibtn">
                            <a class="button_2" href="#font">書体を選ぶ</a>
                        </li>
                    </div>

                    <div class="derileft_2">
                        <h3>オリジナル刺繍</h3>
                        <img src="img//other/shi_type_02.jpg" alt="最短明日お届け" width="100%">
                        <p class="undertxt"><span style="color:#dc0000;">※</span>糸色12色まで</p>
                        <p class="mid">デザインの方(ネーム刺繍以外)</p>
                        <p>箇所・大きさ・デザイン・色を指定してオリジナルの刺繍が製作できます。</p>
                    </div>



                    <div class="nuikata">
                        <h3>縫い方</h3>
                        <p>刺繍の縫い方には「サテン縫い」「タタミ縫い」「ステッチ縫い」があり、この3種類を使い分けて刺繍していきます。</p>
                        <ul class="nuikata_list">
                            <li class="block2"><img src="img/other/shi_sewing_01.jpg" alt="サテン縫い" width="100%">
                                <figcaption class="nui">デザインの端と端を縫う方法です。<br>ネームなど基本的にはこの縫い方で刺繍表現した方が綺麗に仕上がります。綺麗に縫える幅は1mm～10mm程です。<br>10mm以上の場合には、タタミ縫いを用います。</figcaption>
                            </li>
                            <li class="block2"><img src="img/other/shi_sewing_02.jpg" alt="タタミ縫い" width="100%">
                                <figcaption class="nui">畳みの目の様に細かく縫う方法です。<br>縫い幅が10mm以上の時や、刺繍を立体的に見せたい場合に土台部分で使われます。<br> サテン縫いより強度が良い縫い方になります。
                                </figcaption>
                            </li>
                            <li class="block2"><img src="img/other/shi_sewing_03.jpg" alt="ステッチ縫い" width="100%">
                                <figcaption class="nui">点線で表現する方法です。<br>1mm以下のデザインを表現する場合に使われます。<br>小さなマークや文字にも、よく使われます。</figcaption>
                            </li>
                        </ul>
                    </div>

                </div>

                <div id="emb_date2">
                    <div id="emb_date">
                        <h2>価格一覧</h2>
                        <div class="dotted"></div>
                        <p class="kakaku_text">ご希望のサイズの価格表をご覧ください。同じデザインの場合、次回からは刺繍代のみ(型代無料)でご注文いただけます。</p>
                        <p class="katadaibtn">
                            <abbr title="お客様からいただいたデザインを
										 刺繍専用ソフトを用いて刺繍データを
										 設計する費用が「型代」です。
										 型は半永久に保存しますので、
										 リピートのお客様は無料になります。
										 " rel="tooltip"><a class="anchor_1"><img src="img/other/st_question_icon.png">型代とは</a></abbr>
                        </p>

                        <table border="1" class="sisyuudai">
                            <tr class="line" align="center">
                                <th class="thin_1"></th>
                                <th style="text-align:center;" bgcolor="E0E0E2" height="10">刺繍サイズ</th>
                                <th class="td-css1" style="text-align:center;" bgcolor="E0E0E2" height="10">型代</th>
                                <th class="td-css1" style="text-align:center;" bgcolor="E0E0E2" height="10">刺繍代</th>
                            </tr>

                            <tr align="center">
                                <td class="thin" 　bgcolor="E0E0E2" rowspan="3">ネーム刺繍</td>
                                <td bgcolor="FDB985">大25×25cm以内</td>
                                <td class="td-css"></td>
                                <td class="td-css2" bgcolor="FDB985">大3,000円</td>
                            </tr>

                            <tr align="center">
                                <td bgcolor="FED5B6">中18×18cm以内</td>
                                <td class="td-css" style="color:#dc0000">無料</td>
                                <td class="td-css2" bgcolor="FED5B6">中1,500円</td>
                            </tr>

                            <tr align="center">
                                <td class="td-css3" bgcolor="FEE6D3">小10×10cm以内</td>
                                <td class="td-css"></td>
                                <td class="td-css2" bgcolor="FEE6D3">小900円</td>
                            </tr>

                            <tr align="center">
                                <td class="thin" 　bgcolor="E0E0E2" rowspan="3">オリジナル刺繍</td>
                                <td bgcolor="FDB985">大25×25cm以内</td>
                                <td class="td-css4" bgcolor="FDB985">大7,000円</td>
                                <td class="td-css4" bgcolor="FDB985">大3,000円</td>
                            </tr>

                            <tr align="center">
                                <td bgcolor="FED5B6">中18×18cm以内</td>
                                <td class="td-css1" bgcolor="FED5B6">中5,000円</td>
                                <td class="td-css2" bgcolor="FED5B6">中1,500円</td>
                            </tr>

                            <tr align="center">
                                <td bgcolor="FEE6D3">小10×10cm以内</td>
                                <td class="td-css1" bgcolor="FEE6D3">小4,000円</td>
                                <td class="td-css2" bgcolor="FEE6D3">小900円</td>
                            </tr>
                        </table>
                        <p class="undertxt"><span style="color:#dc0000;">※</span>表示は税抜き価格です</p>


                        <div class="price_masi">
                            <h3>素材別<span style="border-bottom:solid 2px color:#dc0000;">割り増し</span>金額</h3>
                            <p class="kakaku_text">刺繍を施すアイテムの素材によって追加の料金が発生致します。</p>

                            <table border class="sozaitable">
                                <tr>
                                    <th width="70px;">綿素材</th>
                                    <th style="text-align:right; " width="20px">＋0円</th>
                                    <th width="70px;" class="th-css3"></th>
                                    <th class="th-css3" width="20px;"></th>
                                </tr>
                                <tr align="center">
                                    <td align="left">スウェット素材</td>
                                    <td align="right">＋50円</td>
                                    <td align="left">ブルゾン(1枚生地)</td>
                                    <td align="right">＋80円</td>
                                </tr>
                                <tr align="center">
                                    <td align="left">ポリエステル素材</td>
                                    <td align="right">＋80円</td>
                                    <td align="left">ブルゾン(厚手生地)</td>
                                    <td align="right">＋230円</td>
                                </tr>
                                <tr align="center">
                                    <td align="left">キャップ</td>
                                    <td align="right">＋80円</td>
                                    <td align="left">つなぎ</td>
                                    <td align="right">＋230円</td>
                                </tr>
                            </table>
                        </div>


                        <div class="price_masi_2">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="ritno">綿素材</td>
                                        <td class="lftno">＋0円</td>
                                    </tr>
                                    <tr>
                                        <td class="ritno">スウェット素材</td>
                                        <td class="lftno">＋50円</td>
                                    </tr>
                                    <tr>
                                        <td class="ritno">ポリエステル素材</td>
                                        <td class="lftno">＋80円</td>
                                    </tr>
                                    <tr>
                                        <td class="ritno">キャップ</td>
                                        <td class="lftno">＋80円</td>
                                    </tr>
                                    <tr>
                                        <td class="ritno">ブルゾン(1枚生地)</td>
                                        <td class="lftno">＋80円</td>
                                    </tr>
                                    <tr>
                                        <td class="ritno">ブルゾン(厚手生地)</td>
                                        <td class="lftno">＋230円</td>
                                    </tr>
                                    <tr>
                                        <td class="ritno">つなぎ</td>
                                        <td class="lftno">＋230円</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="example">
                            <h3>ご注文例</h3>

                            <div class="ex_item_1">
                                <div class="ex_item_1_img">
                                    <img src="img/other/shi_example_01.jpg" alt="100-VP" width="100%">
                                </div>
                                <div class="ex_item_1_txt">
                                    <div class="ex_item_name">
                                        <p><span style="font-size: 17px; font-weight: bold;">100-VP TCポロシャツ（ポケ付）</span></p>
                                        <p>ボディカラー：ネイビー</p>
                                        <p>サイズ：L</p>
                                    </div>
                                    <ul class="item_syousai">
                                        <li>枚数　　　：30枚</li>
                                        <li>箇所　　　：ポケ上</li>
                                        <li>刺繍　　　：ネーム（W8cm H1cm）</li>
                                        <li>糸色　　　：ホワイト</li>
                                    </ul>
                                    <ul class="item_syousai_2">
                                        <li>型代</li>
                                        <li>刺繍代　　：900円×30枚</li>
                                        <li>ボディ代　：1,020円×30枚</li>
                                    </ul>
                                    <ul class="item_syousai_2_2">
                                        <li>0円</li>
                                        <li>27,000円</li>
                                        <li>30,600円</li>
                                    </ul>


                                    <div class="item_price">
                                        <p class="big">合計金額&nbsp;&nbsp;<span style="color:#DD0000;">62,208円</span></p>
                                        <p class="kasen">（税込）</p>
                                        <p class="red_txt">1枚あたり：2,074円</p>
                                    </div>


                                </div>
                            </div>


                            <div class="ex_item_2">
                                <div class="ex_item_1_img">
                                    <img src="img/other/shi_example_02.jpg" alt="193-CP" width="100%">
                                </div>
                                <div class="ex_item_1_txt">
                                    <div class="ex_item_name">
                                        <p><span style="font-size: 17px; font-weight: bold;">193-CP カジュアルポロシャツ</span></p>
                                        <p>ボディカラー：ホワイト</p>
                                        <p>サイズ：M</p>
                                    </div>
                                    <ul class="item_syousai">
                                        <li>枚数　　　：20枚</li>
                                        <li>箇所　　　：首後</li>
                                        <li>刺繍　　　：オリジナル（W14cm H13cm）</li>
                                        <li>糸色　　　：ベージュ・ブラック</li>
                                    </ul>
                                    <ul class="item_syousai_2">
                                        <li>型代</li>
                                        <li>刺繍代　　：1,500円×20枚</li>
                                        <li>ボディ代　：840円×20枚</li>
                                    </ul>
                                    <ul class="item_syousai_2_2">
                                        <li>5,000円</li>
                                        <li>30,000円</li>
                                        <li>16,800円</li>
                                    </ul>


                                    <div class="item_price">
                                        <p class="big">合計金額&nbsp;&nbsp;<span style="color:#DD0000;">55,944円</span></p>
                                        <p class="kasen">（税込）</p>
                                        <p class="red_txt">1枚あたり：2,798円</p>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="section clearfix">
                            <div>
                                <p class="comment2_fo">さっそく申し込みを始める！</p>
                                <a class="button3" href="/order/">お申し込みはこちら</a>
                            </div>
                        </div>

                        <div id="itoiro">
                            <div class="itoiro">

                                <h2>糸色・書体一覧</h2>
                                <div class="dotted"></div>
                                <p class="sisyu_text">刺繍糸の色を決める際に、参考としてご活用ください。</p>
                                <img src="img/other/shi_list_img.jpg" alt="" width="100%">

                                <p class="itoiro_title">糸色</p>
                                <p class="sisyu_text_2">こちらから糸色をお選び下さい。</p>
                                <p class="sisyu_text">全46色/レーヨン糸</p>

                                <div class="color">
                                    <ul>
                                        <li>
                                            <figcaption>C22<br>ブラック</figcaption>
                                            <img src="img/color_font/st_1_1197.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C23<br>ダークグレー</figcaption>
                                            <img src="img/color_font/st_2_2944.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C24<br>ライトグレー</figcaption>
                                            <img src="img/color_font/st_3_1142.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C21<br>ホワイト</figcaption>
                                            <img src="img/color_font/st_4_1198.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C51<br>ワインレッド</figcaption>
                                            <img src="img/color_font/st_5_1329.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C25<br>ラディッシュ</figcaption>
                                            <img src="img/color_font/st_6_9300.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C26<br>レッド</figcaption>
                                            <img src="img/color_font/st_7_1022.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C29<br>オレンジ</figcaption>
                                            <img src="img/color_font/st_8_1114.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C55<br>アプリコット</figcaption>
                                            <img src="img/color_font/st_9_1250.jpg" alt="">
                                        </li>

                                        <li>
                                            <p>C71</p>
                                            <p>ショッキングピンク</p>
                                            <img src="img/color_font/st_10_1359.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C27<br>ホットピンク</figcaption>
                                            <img src="img/color_font/st_11_1358.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C68<br>ピンク</figcaption>
                                            <img src="img/color_font/st_12_1326.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C28<br>ライトピンク</figcaption>
                                            <img src="img/color_font/st_13_1242.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C67<br>サーモンピンク</figcaption>
                                            <img src="img/color_font/st_14_1177.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C50<br>ゴールドイエロー</figcaption>
                                            <img src="img/color_font/st_15_1710.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C30<br>サンフラワー</figcaption>
                                            <img src="img/color_font/st_16_1111.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C31<br>イエロー</figcaption>
                                            <img src="img/color_font/st_17_1095.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C60<br>パステルイエロー</figcaption>
                                            <img src="img/color_font/st_18_1249.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C39<br>ダークブラウン</figcaption>
                                            <img src="img/color_font/st_19_1855.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C40<br>ライトブラウン</figcaption>
                                            <img src="img/color_font/st_20_1859.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C65<br>ベージュ</figcaption>
                                            <img src="img/color_font/st_21_1171.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C61<br>フレッシュ</figcaption>
                                            <img src="img/color_font/st_22_2602.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C43<br>クリーム</figcaption>
                                            <img src="img/color_font/st_23_1011.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C54<br>オリーブ</figcaption>
                                            <img src="img/color_font/st_24_1427.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C58<br>グラスグリーン</figcaption>
                                            <img src="img/color_font/st_25_1058.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C59<br>ライム</figcaption>
                                            <img src="img/color_font/st_26_1418.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C34<br>イエローグリーン</figcaption>
                                            <img src="img/color_font/st_27_1057.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C66<br>ストロー</figcaption>
                                            <img src="img/color_font/st_28_1407.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C32<br>ダークグリーン</figcaption>
                                            <img src="img/color_font/st_29_1063.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C33<br>グリーン</figcaption>
                                            <img src="img/color_font/st_30_1405.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C70<br>グリーンティ</figcaption>
                                            <img src="img/color_font/st_31_1413.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C64<br>ペールグリーン</figcaption>
                                            <img src="img/color_font/st_32_1084.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C53<br>オーシャン</figcaption>
                                            <img src="img/color_font/st_33_1047.jpg" alt="">
                                        </li>

                                        <li>
                                            <p>C57</p>
                                            <p>エメラルドグリーン</p>
                                            <img src="img/color_font/st_34_1246.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C63<br>ミントグリーン</figcaption>
                                            <img src="img/color_font/st_35_1244.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C35<br>ネイビー</figcaption>
                                            <img src="img/color_font/st_36_1522.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <p>C44</p>
                                            <p>リフレックスブルー</p>
                                            <img src="img/color_font/st_37_2521.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C36<br>ブルー</figcaption>
                                            <img src="img/color_font/st_38_1036.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C37<br>サックス</figcaption>
                                            <img src="img/color_font/st_39_1035.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C52<br>バイオレット</figcaption>
                                            <img src="img/color_font/st_40_2815.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C38<br>パープル</figcaption>
                                            <img src="img/color_font/st_41_1803.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C56<br>ラベンダー</figcaption>
                                            <img src="img/color_font/st_42_2613.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C62<br>ライラック</figcaption>
                                            <img src="img/color_font/st_43_2612.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C69<br>ラベンダーグレイ</figcaption>
                                            <img src="img/color_font/st_44_1043.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <figcaption>C42<br>ゴールド</figcaption>
                                            <img src="img/color_font/st_45_f107n.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption>C41<br>シルバー</figcaption>
                                            <img src="img/color_font/st_46_f101n.jpg" alt="">
                                        </li>
                                    </ul>

                                    <p class="undertxt"><span style="color:#dc0000;">※</span>実際の色と異なって見える場合があります。</p>
                                </div>
                            </div>

                            <div id="font">
                                <p class="itoiro_title">書体</p>
                                <p class="sisyu_text">こちらから書体をお選び下さい。</p>
                                <p class="itoiro_title_2">日本語</p>
                                <div class="japanese">
                                    <li>
                                        <figcaption style="margin:10px 0 0 5px;">1.平成楷書</figcaption>
                                        <img src="img/color_font/st_font_j_01.jpg" alt="">
                                    </li>
                                </div>

                                <p class="itoiro_title_2">アルファベット</p>
                                <div class="alphabet">
                                    <ul>
                                        <li>
                                            <figcaption class="alp_fi">1.アデル</figcaption>
                                            <img src="img/color_font/st_font_a_01.jpg" alt="">
                                        </li>

                                        <li>
                                            <figcaption class="alp_fi">2.ボドニー</figcaption>
                                            <img src="img/color_font/st_font_a_02.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li class="alp">
                                            <figcaption class="alp_fi">3.トゥシ</figcaption>
                                            <img src="img/color_font/st_font_a_03.jpg" alt="">
                                        </li>

                                        <li class="alp">
                                            <figcaption class="alp_fi">4.カジュアルセリフ</figcaption>
                                            <img src="img/color_font/st_font_a_04.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li class="alp">
                                            <figcaption class="alp_fi">5.エイリアルラウンデッド</figcaption>
                                            <img src="img/color_font/st_font_a_05.jpg" alt="">
                                        </li>

                                        <li class="alp">
                                            <figcaption class="alp_fi">6.アバンギャルド</figcaption>
                                            <img src="img/color_font/st_font_a_06.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li class="alp">
                                            <figcaption class="alp_fi">7.ゴーディーサンズ</figcaption>
                                            <img src="img/color_font/st_font_a_07.jpg" alt="">
                                        </li>

                                        <li class="alp">
                                            <figcaption class="alp_fi">8.コロンボ</figcaption>
                                            <img src="img/color_font/st_font_a_08.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li class="alp">
                                            <figcaption class="alp_fi_2">9.ブックスクリプト</figcaption>
                                            <img src="img/color_font/st_font_a_09.jpg" alt="" class="alp_img">
                                        </li>

                                        <li class="alp">
                                            <figcaption class="alp_fi_2">10.イージースクリプト</figcaption>
                                            <img src="img/color_font/st_font_a_10.jpg" alt="" class="alp_img">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li class="alp">
                                            <figcaption class="alp_fi">11.バランタインスクリプト</figcaption>
                                            <img src="img/color_font/st_font_a_11.jpg" alt="">
                                        </li>

                                        <li class="alp">
                                            <figcaption class="alp_fi">12.ケイマン</figcaption>
                                            <img src="img/color_font/st_font_a_12.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li class="alp">
                                            <figcaption class="alp_fi">13.オールドイングリッシュ</figcaption>
                                            <img src="img/color_font/st_font_a_13.jpg" alt="">
                                        </li>

                                        <li class="alp">
                                            <figcaption class="alp_fi">14.オリエントエクスプレス</figcaption>
                                            <img src="img/color_font/st_font_a_14.jpg" alt="">
                                        </li>
                                    </ul>

                                    <ul>
                                        <li class="alp">
                                            <figcaption class="alp_fi_3">15.アガサ</figcaption>
                                            <img src="img/color_font/st_font_a_15.jpg" alt="" class="alp_img">
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="caution">
                            <h2>ご注意点</h2>
                            <div class="dotted"></div>
                            <p class="top">刺繍加工時に糸によって生地面が多少引っ張られるため、刺繍サイズは若干ですが縮む場合がございます。また縮小幅は生地素材や各個体によっても異なります。予めご了承ください。また、刺繍加工時に出来るシワを防ぎ、綺麗に仕上げるために下地に専用の紙を入れております。</p>
                            <br>
                            <p class="last">加工箇所の裏面に出ている糸は刺繍の強度維持のために残してあるものです。短く切りすぎると糸がほどけてしまう可能性がございます。残っている紙は取り除いても問題はございませんが、糸を切らないようにご注意ください。</p>
                        </div>


                        <div class="menu_box">
                            <div class="item_1">
                                <div class="navi_inner">
                                    <a class="dropdown-item" href="/items/?cat=1"><img class="top3" src="/common/img/global/item/sp_item_01.png" width="100%"></a>
                                    <a href="/items/?cat=1">
                                        <p class="item_txt"><img src="/common/img/global/go_btm_blue.png">Tシャツ</p>
                                    </a>
                                </div>
                                <div class="navi_inner">
                                    <a class="dropdown-item" href="/items/?cat=3"><img class="top3" src="/common/img/global/item/sp_item_02.png" width="100%"></a>
                                    <a href="/items/?cat=3">
                                        <p class="item_txt"><img src="/common/img/global/go_btm_blue.png">ポロシャツ</p>
                                    </a>
                                </div>
                                <div class="navi_inner">
                                    <a class="dropdown-item" href="/items/?cat=8"><img class="top3" src="/common/img/global/item/sp_item_03.png" width="100%"></a>
                                    <a href="/items/?cat=8">
                                        <p class="item_txt"><img src="/common/img/global/go_btm_blue.png">タオル</p>
                                    </a>
                                </div>
                            </div>
                            <div class="item_2">
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=2"><img class="item_under" src="/common/img/global/item/sp_item_04.png" width="100%"></a>
                                    <a href="/items/?cat=2">
                                        <p class="item_txt_min">スウェット</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?tag=73"><img class="item_under" src="/common/img/global/item/sp_item_sports.png" width="100%"></a>
                                    <a href="/items/?tag=73">
                                        <p class="item_txt_min">スポーツ</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=13"><img class="item_under" src="/common/img/global/item/sp_item_longt.png" width="100%"></a>
                                    <a href="/items/?cat=13">
                                        <p class="item_txt_min">長袖Tシャツ</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=6"><img class="item_under" src="/common/img/global/item/sp_item_05.png" width="100%"></a>
                                    <a href="/items/?cat=6">
                                        <p class="item_txt_min">ブルゾン</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=5"><img class="item_under" src="/common/img/global/item/sp_item_lady.png" width="100%"></a>
                                    <a href="/items/?cat=5">
                                        <p class="item_txt_min">レディース</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=9"><img class="item_under" src="/common/img/global/item/sp_item_bag.png" width="100%"></a>
                                    <a href="/items/?cat=9">
                                        <p class="item_txt_min">バッグ</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=10"><img class="item_under" src="/common/img/global/item/sp_item_07.png" width="100%"></a>
                                    <a href="/items/?cat=10">
                                        <p class="item_txt_min">エプロン</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=14"><img class="item_under" src="/common/img/global/item/sp_item_baby.png" width="100%"></a>
                                    <a href="/items/?cat=14">
                                        <p class="item_txt_min">ベビー</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=16"><img class="item_under" src="/common/img/global/item/sp_item_08.png" width="100%"></a>
                                    <a href="/items/?cat=16">
                                        <p class="item_txt_min">つなぎ</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=12"><img class="item_under" src="/common/img/global/item/sp_item_11.png" width="100%"></a>
                                    <a href="/items/?cat=12">
                                        <p class="item_txt_min">記念品</p>
                                    </a>
                                </div>
                                <div class="navi_inner_2">
                                    <a class="dropdown-item" href="/items/?cat=7"><img class="item_under" src="/common/img/global/item/sp_item_12.png" width="100%"></a>
                                    <a href="/items/?cat=7">
                                        <p class="item_txt_min">キャップ</p>
                                    </a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="section clearfix">
                <!--<div>
					<p class="comment1_fo">データを送りたい方や相談はこちら！</p>
					<a class="button1" href="/contact/"><img src="/common/img/header/mail.png" alt="注意">お問い合わせ</a>
					<a class="button2" href="/m3/items/category.html"><img src="img/other/st_item_icon.png" alt="注意">アイテムはこちら</a>
				</div>-->
            </div>
            <!--
			<div>
				<ul class="cal_red_txt">
					<li>
						<div class="phone_txt">
							<p class="red_txt">お気軽にお電話ください</p>
							<p class="timetx">受付時間：平日 10:00-18:00</p>
							<p class="red_txt"><img alt="電話" src="../sameday/img/tel_mark.png" width="40px" height="50px" style="padding-bottom:5px; padding-right:10px;">0120-130-428</p>
							<p class="comment1">疑問点はお電話またはメールでお気軽にお問い合わせください！</p>
						</div>
					</li>
				</ul>
			</div>
-->
        </div>
        <footer class="page-footer">
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
        </footer>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

        <div id="overlay-mask" class="fade"></div>

        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>

    </body>

    </html>
