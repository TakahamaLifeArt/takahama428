<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/orders.php';
$order = new Orders();
for($cnt=1,$idx=0; $cnt<5; $cnt++,$idx++){
	$fin = $order->getDelidate(null, 1, $cnt, 'simple');
	$main_month[$idx] = $fin['Month'];
	$main_day[$idx] = $fin['Day'];
}
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Description" content="「早い」作成で業界No.1最速のオリジナルTシャツプリントのタカハマライフアートの「お届け日（納期）を調べる」ページです。納期や注文確定の条件などがWeb上で簡単にわかります。1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。" />
        <meta name="keywords" content="オリジナル,Tシャツ,プリント,お届け日,納期" />
        <meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
        <meta property="og:url" content="https://www.takahama428.com/" />
        <meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
        <meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
        <meta property="fb:app_id" content="1605142019732010" />
        <title>お届け日がすぐに分かる! ｜ オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico">
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/flick/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="/common/css/jquery.ui.css" media="screen">
        <link rel="stylesheet" type="text/css" href="./css/deliveryday.css" media="screen">
    </head>

    <body>
        <header>
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
        </header>

        <div id="container">
            <div class="contents">
                <ul class="pan hidden-sm-down">
                    <li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
                    <li>お届け日を調べる</li>
                </ul>
                <div class="heading1_wrapper">
                    <h1>お届け日を調べる</h1>
                </div>

                <div id="delivery_date_search" class="delivery_date_wrapper">
                    <div class="heading2_s">
                        <h2><img src="/delivery/img/deli/day_title.png" width="100%;"></h2>
                    </div>

                    <div class="search_day_wrapper">
                        <div class="search_day">
                            <div class="cal_date">
                                <p class="date_title">エリアを選ぶ</p>
                                <div class="cal_date_inner">
                                    <select name="都道府県" id="destination">
												<option value="">都道府県をお選びください。</option>  
												<optgroup label="北海道・東北">  
													<option data-destination="2" value="北海道">北海道</option>  
													<option data-destination="2" value="青森県">青森県</option>  
													<option data-destination="2" value="秋田県">秋田県</option>  
													<option data-destination="2" value="岩手県">岩手県</option>  
													<option data-destination="2" value="山形県">山形県</option>  
													<option data-destination="2" value="宮城県">宮城県</option>  
													<option data-destination="2" value="福島県">福島県</option>  
												</optgroup>  
												<optgroup label="甲信越・北陸">  
													<option data-destination="1" value="山梨県">山梨県</option>  
													<option data-destination="1" value="長野県">長野県</option>  
													<option data-destination="1" value="新潟県">新潟県</option>  
													<option data-destination="1" value="富山県">富山県</option>  
													<option data-destination="1" value="石川県">石川県</option>  
													<option data-destination="1" value="福井県">福井県</option>  
												</optgroup>  
												<optgroup label="関東">  
													<option data-destination="1" value="茨城県">茨城県</option>  
													<option data-destination="1" value="栃木県">栃木県</option>  
													<option data-destination="1" value="群馬県">群馬県</option>  
													<option data-destination="1" value="埼玉県">埼玉県</option>  
													<option data-destination="1" value="千葉県">千葉県</option>  
													<option data-destination="1" value="東京都" selected>東京都</option>  
													<option data-destination="1" value="神奈川県">神奈川県</option>  
												</optgroup>  
												<optgroup label="東海">  
													<option data-destination="1" value="愛知県">愛知県</option>  
													<option data-destination="1" value="静岡県">静岡県</option>  
													<option data-destination="1" value="岐阜県">岐阜県</option>  
													<option data-destination="1" value="三重県">三重県</option>  
												</optgroup>  
												<optgroup label="関西">  
													<option data-destination="1" value="大阪府">大阪府</option>  
													<option data-destination="1" value="兵庫県">兵庫県</option>  
													<option data-destination="1" value="京都府">京都府</option>  
													<option data-destination="1" value="滋賀県">滋賀県</option>  
													<option data-destination="1" value="奈良県">奈良県</option>  
													<option data-destination="1" value="和歌山県">和歌山県</option>  
												</optgroup>  
												<optgroup label="中国">  
													<option data-destination="1" value="岡山県">岡山県</option>  
													<option data-destination="1" value="広島県">広島県</option>  
													<option data-destination="1" value="鳥取県">鳥取県</option>  
													<option data-destination="1" value="島根県">島根県</option>  
													<option data-destination="1" value="山口県">山口県</option>  
												</optgroup>  
												<optgroup label="四国">  
													<option data-destination="1" value="徳島県">徳島県</option>  
													<option data-destination="1" value="香川県">香川県</option>  
													<option data-destination="1" value="愛媛県">愛媛県</option>  
													<option data-destination="1" value="高知県">高知県</option>  
												</optgroup>  
												<optgroup label="九州・沖縄">  
													<option data-destination="2" value="福岡県">福岡県</option>  
													<option data-destination="2" value="佐賀県">佐賀県</option>  
													<option data-destination="2" value="長崎県">長崎県</option>  
													<option data-destination="2" value="熊本県">熊本県</option>  
													<option data-destination="2" value="大分県">大分県</option>  
													<option data-destination="2" value="宮崎県">宮崎県</option>  
													<option data-destination="2" value="鹿児島県">鹿児島県</option>  
													<option data-destination="2" value="沖縄県">沖縄県</option>  
												</optgroup>
											</select>
                                </div>
                            </div>
                            <div class="cal_date">
                                <p class="date_title">注文日を選ぶ</p>
                                <div class="cal_date_inner">
                                    <input id="datepicker_firmorder" type="text" size="10" class="forDate" value="">
                                    <!--									<p><input type="button" value="検索" id="btnFirmorder"></p>-->
                                </div>
                            </div>
                        </div>

                        <div class="search_result">
                            <div class="result_img">
                                <img src="/delivery/img/deli/day_choose_03.jpg" width="100%;">
                            </div>
                            <p class="result_ttl"><img src="/delivery/img/deli/day_choose_04.jpg" width="10%" ;>お届け日結果</p>
                            <input id="datepicker_deliday" type="text" size="10" class="forDate" value="" readonly>
                        </div>
                    </div>

                    <div class="note">
                        注文確定 通常13:00まで(当日特急は<span class="red_new">12:00</span>まで)
                    </div>
                </div>

                <div class="order_content">
                    <div class="order_txt">
                        <div class="order_img"><img src="/delivery/img/deli/go_pattern.jpg"></div>
                        <div class="order_p">初めてでもカンタン！<br>迅速丁寧に対応いたします。</div>
                        <div class="order_img"><img src="/delivery/img/deli/go_pattern.jpg"></div>
                    </div>
                    <div class="order_bubble">
                        <a href="/order/" class="order_btn"><img src="/delivery/img/deli/sp_go_icon.png">お申し込み</a>
                        <img class="bubble_img" src="/delivery/img/deli/sp_go_min.png">
                    </div>
                </div>

                <div id="delivery_date_now" class="delivery_date_wrapper"><span id="deri" class="anchorlink"></span>
                    <div class="heading2_s">
                        <h2><img src="/delivery/img/deli/day_plan_title.png"></h2>
                    </div>
                    <div class="plan_txt">
                        <p>「いつまでに注文したら希望の日に届く？」商品のお届け日に関して調べることができます。</p>
                        <p><span class="red_caution">オプションの内容によっては納期が変わる場合がございます。ご了承ください。</span></p>
                    </div>
                    <ul class="cal_red">
                        <li>
                            <div class="plan_ttl">
                                <p>当日特急プラン<br><span class="plan_ttl_min">(当日仕上げ)</span></p>
                            </div>
                            <div class="plan_box">
                                <div class="plan_date">
                                    <p>今なら<br><span class="mm"><?php echo $main_month[0]; ?>/</span><span class="dd"><?php echo $main_day[0]; ?></span><br>に届きます</p>
                                </div>
                                <p class="red_new">注文確定　当日12:00まで</p>
                                <p class="orange_new">通常料金 : <img src="/delivery/img/deli/sp_hurry_yen_orange.png"> ×2</p>
                            </div>
                        </li>
                        <li>
                            <div class="plan_ttl_2">
                                <p>翌日プラン<br><span class="plan_ttl_min">(翌日仕上げ)</span></p>
                            </div>
                            <div class="plan_box">
                                <div class="plan_date">
                                    <p>今なら<br><span class="mm"><?php echo $main_month[1]; ?>/</span><span class="dd"><?php echo $main_day[1]; ?></span><br>に届きます</p>
                                </div>
                                <p class="blue_new">注文確定　当日13:00まで</p>
                                <p class="orange_new">通常料金 : <img src="/delivery/img/deli/sp_hurry_yen_orange.png"> ×1.5</p>
                            </div>
                        </li>
                        <li>
                            <div class="plan_ttl_3">
                                <p>2日プラン<br><span class="plan_ttl_min">(２日仕上げ)</span></p>
                            </div>
                            <div class="plan_box">
                                <div class="plan_date">
                                    <p>今なら<br><span class="mm"><?php echo $main_month[2]; ?>/</span><span class="dd"><?php echo $main_day[2]; ?></span><br>に届きます</p>
                                </div>
                                <p class="blue_new">注文確定　当日13:00まで</p>
                                <p class="orange_new">通常料金 : <img src="/delivery/img/deli/sp_hurry_yen_orange.png"> ×1.3</p>
                            </div>
                        </li>
                        <li class="popular_list">
                            <div class="popular_plan">大人気</div>
                            <div class="plan_ttl_4">
                                <p>通常3日プラン<br><span class="plan_ttl_min">(3日仕上げ)</span></p>
                            </div>
                            <div class="plan_box">
                                <div class="plan_date">
                                    <p>今なら<br><span class="mm"><?php echo $main_month[3]; ?>/</span><span class="dd"><?php echo $main_day[3]; ?></span><br>に届きます</p>
                                </div>
                                <p class="blue_new">注文確定　当日13:00まで</p>
                                <p class="orange_new">通常料金 : <img src="/delivery/img/deli/sp_hurry_yen_orange.png"></p>
                            </div>
                        </li>
                    </ul>
                    <div class="plan_txt_bottom">
                        <p><span class="red_new">※</span>通常料金はアイテム＋プリント代です。<br><span class="red_new">※</span>発送は東京からしております。<br><span class="red_new">※</span>お届け先が、北海道、九州、沖縄、東京離島、島根隠岐郡のいずれかとなる場合は表示日数より１日多くかかります。</p>
                    </div>
                </div>

                <div id="other_info">
                    <div id="condition" class="plan_wrapper">
                        <div class="heading2">
                            <h2>当日特急プラン</h2>
                        </div>
                        <p>業界最速NO.1の速さ！どこよりも早い当日発送で東京都内・近県なら頼んだその日に届きます。</p>
                        <a href="/order/express/" class="banner">
							<img src="/delivery/img/deli/speed_banner.jpg">
						</a>
                        <div class="conditions_list_wrap">
                            <p class="bold_b">【3つの条件】</p>
                            <div class="conditions_list">
                                <div class="con_list_1"><span class="bold">1.12時までに注文確定</span>
                                    <div class="list_inner">
                                        <p>データ入稿が完了している・お電話で注文確定している。</p>
                                    </div>
                                </div>
                                <div class="con_list_2"><span class="bold">2.1番人気のTシャツとタオルのみ対応可</span>
                                    <div class="list_inner">
                                        <p>Tシャツ：085-CVT の白と黒(Sサイズ・XLサイズ)</p>
                                        <p>タオル　：522-FT　の白色(フリーサイズ)</p>
                                    </div>
                                </div>
                                <div class="con_list_3"><span class="bold">3.注文確定後、すぐ入金！</span>
                                    <div class="list_inner">
                                        <p>商品の発送は入金確認後になりますので、 お早めのご入金をお願い致します。
                                        </p>
                                        <p><span class="min"><span class="red_new">※</span>詳しくはスタッフまでお問い合わせください。</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="/order/express/" class="next_btn">当日特急プランページを見る</a>
                    </div>

                    <div id="extracharge" class="plan_wrapper">
                        <div class="heading2">
                            <h3>発注からお届けまでの最短目安</h3>
                        </div>
                        <div class="map_list">
                            <div class="areamap_img"><img src="/delivery/img/deli/sp_day_japan.jpg" width="100%;"></div>
                            <table class="area_list_table">
                                <tbody>
                                    <tr>
                                        <th>発注からお届けまでの目安</th>
                                        <th>配送地域</th>
                                    </tr>
                                    <tr>
                                        <td class="table_1">翌日：午前中</td>
                                        <td>山形・宮城・福島・新潟・栃木・群馬・埼玉・東京・ 神奈川・千葉・茨城・山梨・静岡・愛知・三重・奈良・ 長野・富山・岐阜・石川・福井・滋賀・京都・大阪・ 岩手・宮城・兵庫</td>
                                    </tr>
                                    <tr>
                                        <td class="table_2">翌日：14時&sim;16時</td>
                                        <td>青森・秋田 和歌山・鳥取・島根・岡山・広島・山口・香川・徳島・愛媛・高知</td>
                                    </tr>
                                    <tr>
                                        <td class="table_3">翌々日：午前中</td>
                                        <td>北海道 福岡・大分・宮崎・佐賀・長崎・熊本・鹿児島 沖縄その他離島</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="plan_txt_bottom">
                            <p><span class="red_new">※</span>発送は東京からしております。<br><span class="red_new">※</span>お届け先が、北海道、九州、沖縄、東京離島、島根隠岐郡のいずれかとなる場合は表示日数より１日多くかかります。</p>
                        </div>
                    </div>
                </div>

                <div class="order_content">
                    <div class="order_txt">
                        <div class="order_img"><img src="/delivery/img/deli/go_pattern.jpg"></div>
                        <div class="order_p">初めてでもカンタン！<br>迅速丁寧に対応いたします。</div>
                        <div class="order_img"><img src="/delivery/img/deli/go_pattern.jpg"></div>
                    </div>
                    <div class="order_bubble">
                        <a href="/order/" class="order_btn"><img src="/delivery/img/deli/sp_go_icon.png">お申し込み</a>
                        <img class="bubble_img" src="/delivery/img/deli/sp_go_min.png">
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
        <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
        <script type="text/javascript" src="./js/deliveryday.js"></script>

    </body>

    </html>
