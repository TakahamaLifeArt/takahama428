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
        <meta name="Description" content="ご注文から最短即日でオリジナルTシャツを当日にお届け。業界最速のスピード対応が可能な3つの理由をご紹介。急なイベントや販促、スタッフTシャツなどもご安心ください、タカハマならまだ間に合います。1枚からでも安い・お急ぎ製作・印刷は東京都内のタカハマライフアート！10秒で簡単・早いオリジナルTシャツ比較お見積もりも承ります。" />
        <meta name="keywords" content="Tシャツ,オリジナル,即日,短納期,早い" />
        <title>【最短即日】お届けが早い「3つの理由」 ｜ オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico">
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" href="./css/speed.css" media="screen" />
    </head>

    <body>
        <header>
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
        </header>

        <div id="container">

            <div class="contents">
                <ul class="pan">
                    <li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
                    <li>早い３つの理由</li>
                </ul>
                <div class="heading1_wrapper_new">
                    <h1>タカハマが早い３つの理由</h1>
                    <div class="clearfix section01">
                        <img src="../reason/img/sp_jpg/sp_s_main.jpg" width="100%" alt="3つの理由">
                    </div>
                </div>
                <div class="ctrbotton">
                    <ul class="top_button">
                        <li class="lft_img">
                            <a class="button" href="#sday"><img src="../reason/img/pc_jpg/s_dbtm_05.png" alt="" width="25%" class="leftcts" />最短当日出荷<br><i class="fa fa-angle-down" aria-hidden="true" style="position: absolute;top: 36px;"></i></a>
                        </li>
                        <li class="lft_img">
                            <a class="button" href="#deri"><img src="../reason/img/pc_jpg/s_dbtm_06.png" alt="" width="25%" class="leftcts" />お届けの流れ<br><i class="fa fa-angle-down" aria-hidden="true" style="position: absolute;top: 36px;"></i></a>
                        </li>
                        <li class="lft_img">
                            <a class="button" href="#item"><img src="../reason/img/pc_jpg/s_dbtm_07.png" alt="" width="25%" class="leftcts" />即日対応商品<br><i class="fa fa-angle-down" aria-hidden="true" style="position: absolute;top: 36px;"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="wrap1">
                    <h2 class="white_ttl"><span id="sday" class="anchorlink"></span>最短当日にお届け！</h2>
                    <div class="rightside"><img src="../reason/img/pc_jpg/sp_s_front.jpg" alt="早い理由1" width="100%" align="right" /></div>
                    <p class="righttxt">
                        タカハマライフアートは<span class="yellowtxt">最短当日</span>にオリジナルTシャツ・オリジナルタオルのお届けができます！ 東京に二つの自社工場があるので、自社一貫で業界でも類を見ない
                        <span class="linetxt">最速の即納対応</span>が可能になっています。
                    </p>
                    <p class="smalltxt">
                        迅速なオペレーション、ハイスピードプリントでお急ぎのお客様にも安心してオリジナルTシャツを<span class="linetxt">最短当日にお届け</span>致します！ 他社で「納期が間に合わない」と断られたお客様、ご安心ください！
                    </p>
                </div>
                <div class="onpc">
                    <h4 class="red_txt">タカハマライフアートならまだ間に合います！</h4>
                    <p class="itembtn">
                        <a class="anchor_1" href="/delivery/">お届け日を確認する</a>
                    </p>
                </div>
                <div class="offpc">
                    <h4 class="red_txt">タカハマライフアートならまだ間に合います！</h4>
                    <p class="itembtn">
                        <a class="anchor_1" href="/delivery/">お届け日を確認する</a>
                    </p>
                </div>
                <div>
                    <h2><img src="../reason/img/pc_jpg/sp_s_crown_01.png" alt="" width="45px" />早い理由<span class="ttltxt">-日本一のプリントスピード-</span></h2>

                    <div class="redline"></div>

                    <div class="section clearfix">
                        <div class="rightside">
                            <p class="comment">二箇所の自社工場</p>
                            <img src="../reason/img/sp_jpg/sp_s_reason_01_a.jpg" alt="早い理由1" width="100%" align="right" />
                        </div>
                        <p class="righttxt">
                            タカハマライフアートのお届けが早い理由は、<span class="yellowtxt">自社で全ての加工を行っている</span>からです。 お客様の電話を受ける受注・デザインを加工するデザイナー・各製造工程を担う現場の部署が一つの工場の中で完結しているため、
                            <span class="yellowtxt">各部署の連携がスムーズ</span>で不足の事態にも臨機応変に対応が可能です。
                        </p>
                        <p class="smalltxt">
                            <span class="linetxt">生産の管理も社内で全て行える</span>ので、注文の入り具合をリアルタイムで確認しながらスケジュールを組んでいます。作成スケジュールの決定・変更や複数のプリント部署による人員配置の調整を行いながら柔軟に対応できるので<span class="yellowtxt">急な納期の注文に即対応できる仕組み</span>になっています。
                        </p>
                        <p class="smalltxt2">
                            タカハマライフアートの一番の強み、<span class="yellowtxt">特急対応</span>も、この柔軟な予定組みと進行によって実現しています。業界内でも最速の当日特急対応(注文日の当日に商品出荷)は注文が決まった瞬間に担当者から現場に連絡が入り各部署で決められた準備に取り掛かります。
                        </p>
                        <p class="smalltxt2">
                            データの加工やプリント資材の準備、実際のプリント工程まで併せて規定時間内で完結できるよう作業に取り掛かります。こういった<span class="yellowtxt">即対応</span>を行うために重要なのは明確なルールを決めて厳守することです。
                        </p>
                        <p class="smalltxt2">
                            お互い何を行っているか分からなくては連携は取れませんし、どこかの部署で止まってしまえば 業務は進まなくなります。いかに円滑に業務が進むかを考えて、ルールを見直しながら他では類を見ない
                            <span class="yellowtxt">早い対応</span>を追求しています。
                        </p>
                    </div>
                </div>
                <div class="section clearfix">
                    <div class="leftside">
                        <p class="comment">商品品質に対する心構え</p>
                        <img src="../reason/img/sp_jpg/sp_s_reason_01_b.jpg" alt="早い理由1" width="100%" align="left" />
                    </div>
                    <p class="lefttxt">
                        タカハマライフアートの早さは、商品の品質や作成<span class="linetxt">スタッフの仕事に対する姿勢</span>からも成り立っています。通常の注文と<span class="yellowtxt">特急対応</span>とで金額や納期に違いが有りますが、どの注文も同じ様に心を込めて丁寧に作成しています。
                    </p>
                    <p class="smalltxt">
                        納期が短い＝「時間がないから仕上がりが雑になるのでは？」と考える方もいらっしゃると思いますし、事実そういったスタイルで仕事を行う業者が無いとは言い切れません。しかし、タカハマライフアートでは<span class="yellowtxt">「時間がないから仕上がりが雑になる」といった状況に決してなりません！</span>機械ではなく、職人の手で一枚一枚、心を込めて仕事をしています。
                    </p>
                    <p class="smalltxt2">
                        一つの注文に目安の時間を決めておき、現場で設けた品質基準を参考に作成を行うので雑にならず安定した<span class="yellowtxt">高品質商品を納期に必ず間に合わせてお届け</span>しています！
                    </p>
                    <p class="smalltxt2">
                        現場スタッフにはＴシャツやプリント好きが多く集まり、<span class="linetxt">お客様目線を意識</span>しながら仕事をしています。自分たちが注文する側の気持ちが分かっているからこそ、「明日商品が無いと困るお客様に<span class="yellowtxt">出来る限り早く</span>お届けしたい。」「Ｔシャツの完成を心待ちにしているお客様に<span class="yellowtxt">出来る限り良いものを作りたい。</span>」といった気持ちで商品作成に励んでいます。
                    </p>
                </div>

                <div class="section clearfix">
                    <div class="rightside">
                        <p class="comment">プリントが早い理由</p>
                        <img src="../reason/img/sp_jpg/sp_s_reason_01_c.jpg" alt="早い理由1" width="100%" />
                    </div>
                    <p class="righttxt">
                        タカハマライフアートの作成が早い商品の中でも、特に他社と比べ抜きん出て、ハイスピードなのが<span class="linetxt">デジタルコピー転写プリント</span>です。デジタルコピー転写とはフルカラーで作成したシートをボディに貼り付けるタイプのプリントです。デジタル転写プリントは一般的に広く利用されているシルクスクリーンプリントに比べて業務工程が多く時間がかかります。
                    </p>
                    <p class="smalltxt">
                        作成目安として、一般的な会社の納期が7xA訓0営業日程度に対して、弊社は<span class="linetxt">3営業日での対応</span>となっています。なぜこんなに違うのか、速さの秘密は<span class="yellowtxt">自社一貫での対応と業務工程の工夫</span>にあります。上でも述べたように、コピー転写はシートを貼り付けるプリントのため、シートを作成する必要があります。<span class="yellowtxt">プリント会社の多くは外部の業者にこのシート作成を依頼</span>しますが、弊社では<span class="yellowtxt">自社で全てを作成</span>しています。
                    </p>
                    <p class="smalltxt2">
                        また、シートを作成するための機材の作成も必要で、こちらも同じく<span class="yellowtxt">自社で作成</span>を行っています。そのため外部に依頼する分の時間が短縮でき、その早くなった分だけ<span class="yellowtxt">納品速度が上がります！</span>シート作成に関しては企業秘密が多く具体的にはお伝えできませんが、設備と材料を研究して一般的な作成工程に改良を加えています。このような対策により他者と比べて圧倒的に早い納品を実現しています。
                    </p>
                    <p class="smalltxt2">
                        転写プリントだけでなく、<span class="linetxt">シルクプリント</span>や<span class="linetxt">インクジェットプリント</span>、製版、梱包全てが、勿論早いです！実際に即日にお届けしてきた<span class="yellowtxt">20年間の実績</span>が、<span class="yellowtxt">最短当日で対応</span>できるスピードの根拠になっています！
                    </p>
                </div>
                <div>
                    <h2><img src="../reason/img/pc_jpg/sp_s_crown_02.png" alt="" width="45px" />早い理由<span class="ttltxt">-スピード受注-</span></h2>

                    <div class="redline"></div>

                    <div class="section clearfix">
                        <div class="rightside">
                            <p class="comment">電話対応日本一！</p>
                            <img src="../reason/img/sp_jpg/sp_s_reason_02_a.jpg" alt="早い理由1" width="100%" />
                        </div>
                        <p class="righttxt">
                            早い理由二つ目は、<span class="yellowtxt">スピード受注の体制</span>です。タカハマライフアートの<span class="linetxt">電話対応の速さ</span>はどこにも負けません！お急ぎのお客様が安心して受け取れるように、当日特急から<span class="yellowtxt">通常納期でも３営業日</span>での発送が可能となっています。
                        </p>
                        <p class="smalltxt">
                            急ぎのお問い合わせや「早くTシャツが欲しい！」という方にも、出来るだけ早いご対応が出来るようお電話は<span class="yellowtxt">３コール以内</span>に必ずお取りし、お客様をお待たせしないよう取り組んでいます。
                        </p>
                        <p class="smalltxt">
                            また、対応が早いだけでなく、Tシャツのサイズや知識、データの入稿の仕方、デザイン相談などもひとつひとつ、分からないことには丁寧にご案内させて頂いております。初めてご注文される方は不安なことや分からないことも沢山あるかと思いますが、スタッフ一人ひとりがお客様のお問い合わせに<span class="linetxt">同じ目線に立って親身になって</span>お話をさせて頂きます。
                        </p>
                        <p class="smalltxt2">
                            お客様にあったプリント方法やご予算で納得のいくご提案をさせて頂きます。初めての方もこれからご注文される方も、<span class="yellowtxt">迷ったときにはまず、お電話ください！</span>急ぎで今すぐTシャツが必要という方も今すぐお電話ください！早いだけでなく、<span class="yellowtxt">ひとりひとりに合わせた提案</span>でよりいいご提案をさせて頂きます。お気軽にお問合せください！
                        </p>
                    </div>
                </div>
                <div class="section clearfix">
                    <div class="leftside">
                        <p class="comment">驚異のレスポンススピード</p>
                        <img src="../reason/img/sp_jpg/sp_s_reason_02_b.jpg" alt="早い理由1" width="100%" />
                    </div>
                    <p class="lefttxt">
                        電話でお話しするのが苦手な方、もしくは電話する時間がとれない！という方。ぜひ<span class="yellowtxt">メールでお問い合わせ</span>をしてみてください！
                        <br>
                        <br>タカハマライフアートでは、電話だけでなく<span class="linetxt">メールの対応</span>も<span class="yellowtxt">業界最速</span>スピードです。急ぎでお問い合わせメールを送ったのに、<span class="yellowtxt">何時間も返事が来なくて注文の確定に間に合わなかった…</span>そんな心配はご無用です。お客様のご希望の納期に間に合うようにお急ぎの方から順番に返信をしていきます。
                    </p>
                    <p class="smalltxt">
                        最近では複雑なデザインでなければ<span class="yellowtxt">メールだけでご注文を確定</span>することも可能となっており、お問い合わせからご注文、お見積り、ご注文確定まで<span class="linetxt">一貫してメールで行うことも可能</span>です。
                    </p>
                    <p class="smalltxt2">
                        もちろんじっくりとお打合せをご希望のお客様や、「メールなんてしている暇はない！すぐに対応してくれ！」という<span class="yellowtxt">お客様もお電話でご注文の確定</span>ができます。
                    </p>
                    <p class="smalltxt2">
                        タカハマライフアートは常に<span class="linetxt">お客様の目線に合わせてご対応</span>することを心がけています！そしてお問い合わせのみなら<a class="speed" href="/contact/line/">LINE</a>でもお受付しております。<span class="yellowtxt">対応時間は営業時間と同じ10:00xA訓8:00</span>ですが、メール以上にお気軽にお問い合わせをすることができます。「納期が知りたいです。」こんな簡単な一言だけでもかまいません！ご不明点があれば<span class="linetxt">お友達にLINEを送る感覚</span>で送ってみてください。電話・メールと変わらないクオリティでご対応をさせて頂きます！
                    </p>
                </div>

                <div class="section clearfix">
                    <div class="rightside">
                        <p class="comment">即日対応できる対応力</p>
                        <img src="../reason/img/sp_jpg/sp_s_reason_02_c.jpg" alt="早い理由1" width="100%" />
                    </div>
                    <p class="righttxt">
                        私たちは<span class="yellowtxt">日本一早い対応</span>を目指しています。電話やメールの返信が早いだけでなく、ご注文が確定したお客様の案件を、すぐに社内各部署に回します。
                        <br>
                        <br>必要な作業は書類作成。商品の詳細やデザインの指示、プリント方法、インクの色、商品の発送先やお支払方法など、必要な情報を素早くまとめ、各部署がストレスなく仕事が流れるようにしています。部署間のやり取りが多いと、その分1件にかかる時間が長くなってしまいます。
                    </p>
                    <p class="smalltxt">
                        いかに少ないやり取りで、素早く連携できるかを、タカハマライフアートでは日々追及しています。<span class="linetxt">1分1秒無駄にしない</span>を心掛けています。また、豊富な知識を持ったスタッフが、お客様のご要望に合ったプリント方法やお見積り金額など、素早くご提案させていただきます！
                    </p>
                    <p class="smalltxt2">
                        誰だって初めてオリジナルTシャツを作成するときは不安でいっぱいですよね。急いでいる場合は尚更です。そんな不安を、タカハマライフアートの受注スタッフが<span class="yellowtxt">親切・丁寧・迅速</span>にお答えし、払拭します！安心してお気軽にご相談ください。
                    </p>
                </div>
                <div class="tel">
                    <p class="red_bld">まずはお電話ください。</p>
                    <p class="red_txt">
                        <img alt="電話" src="/guide/img/tel_mark.png" width="40px" height="50px" style="padding-bottom:8px; padding-left:10px"> 0120-130-428
                    </p>
                    <p style="font-size:20px">お電話受付時間：平日 10:00-18:00</p>
                </div>
                <div>
                    <h2><img src="../reason/img/pc_jpg/sp_s_crown_03.png" alt="" width="45px" />早い理由<span class="ttltxt">-スピードに特化したWEBサイト-</span></h2>

                    <div class="redline"></div>

                    <p class="toptxt">
                        最後の理由が<span class="yellowtxt">スピードに特化した</span>WEBサイトです！ 急いでいるお客様が何を求めているのか追求した結果がタカハマライフアートのWEBサイトに反映されてい ます。
                    </p>

                    <div class="section clearfix">
                        <div class="rightside">
                            <p class="comment">お届け日が早く分かる！</p>
                            <img src="../reason/img/pc_jpg/sp_s_reason_03_a.jpg" alt="早い理由1" width="100%" />
                        </div>
                        <p class="righttxt">
                            例えば、トップページ！急いでいるお客様が一番気にしていること、「間に合うのか？」という不安を無くすために、いつ届くのか分かる、<span class="yellowtxt">お届け日早わかりシステム</span>を導入しています。
                        </p>
                        <p class="smalltxt">
                            日本全国、今日注文したら<span class="linetxt">いつ届くのかすぐに把握</span>することができます。PCの場合、更に注文確定の締め切りをカウントダウンしているので、事前にスピーディーに判断できます。
                        </p>
                    </div>
                    <div class="section clearfix">
                        <div class="leftside">
                            <p class="comment">見積もりが早い！</p>
                            <img src="../reason/img/pc_jpg/sp_s_reason_03_b.jpg" alt="早い理由1" width="100%" />
                        </div>
                        <p class="lefttxt">
                            納期が分かったら、次は見積もり。タカハマライフアートなら、<span class="linetxt">幾らかかるのかも直ぐに分かります。</span>オススメさせて頂きたいのは、<span class="yellowtxt">10秒で簡単に見積もり</span>ができるシステムです。
                        </p>
                        <p class="smalltxt">
                            商品カテゴリーと生地タイプ、プリントする色数を選択すると<span class="linetxt">即座に値段が現れます。</span>最大のポイントは、値段だけでなく、「この商品なら幾らです！」と、<span class="yellowtxt">色々な商品で比較ができる</span>点です。この商品だったら幾らで・・・という手間が省けるという点も<span class="linetxt">タカハマライフアートのスピードを研ぎ澄ます</span>1ピースとなっております。
                        </p>
                    </div>
                    <h4 class="red_txt">かんたん！比較できる！</h4>
                    <p class="itembtn">
                        <a class="anchor_1" href="/price/estimate.php">早速10秒で見積もり</a>
                    </p>
                    <div class="section clearfix">
                        <div class="rightside">
                            <p class="comment">取り扱い商品が早く分かる！</p>
                            <img src="../reason/img/sp_jpg/sp_s_reason_03_c.jpg" alt="早い理由1" width="100%" />
                        </div>
                        <p class="righttxt">
                            タカハマライフアートでは、<span class="linetxt">250商品近くの商品を取り扱っております。</span>沢山の商品の中からご自分の希望にあった商品を選ぶのは楽しい反面、急いいでいるので煩わしいと考えるお客様もいらっしゃると思います。
                        </p>
                        <p class="smalltxt">
                            そういった急いでいるお客様にオススメさせて頂きたいのが、<span class="yellowtxt">アイテムランキング</span>システムです。Tシャツ、ポロシャツ、パーカー、スポーツウェアなど主要商品カテゴリーでのオススメランキング、<span class="yellowtxt">ベスト3位までが自動で表示</span>されます。
                        </p>
                        <p class="smalltxt">
                            <span class="yellowtxt">お客様から特に人気のある3つの商品</span>を表示させているので、こちらの商品からお選び頂ければ、注文までスムーズに移行でき、圧倒的なスピードで商品がお届けできます。Tシャツからポロシャツなど、見たい商品カテゴリーを<span class="yellowtxt">直感的に切り替えられる</span>のが魅力の一つです！
                        </p>
                    </div>
                    <div class="wrap2">
                        <h2 class="white_ttl"><span id="deri" class="anchorlink"></span>即日お届けの流れ</h2>
                        <div class="rightside"><img src="../reason/img/pc_jpg/sp_s_front_02.jpg" alt="早い理由1" width="100%" /></div>
                        <p class="righttxt">
                            電話、WEB、FAXにて平日の午前12時までにご注文をお願いします。
                        </p>
                        <p class="smalltxt">
                            お問い合わせ、申し込みに対しての該当商品の在庫確認を行います。
                            <br>
                            <br>在庫の確認が終わりましたら、対応可能かどうかのご連絡をさせて頂きます。
                        </p>
                    </div>
                    <div class="onpc">
                        <h4 class="red_txt">タカハマのご利用案内はこちら</h4>
                        <p class="itembtn">
                            <a class="anchor_1" href="/guide/orderflow.php">注文の流れを確認する</a>
                        </p>
                    </div>
                    <div class="offpc">
                        <h4 class="red_txt">タカハマのご利用案内はこちら</h4>
                        <p class="itembtn">
                            <a class="anchor_1" href="/guide/orderflow.php">注文の流れを確認する</a>
                        </p>
                    </div>
                    <div class="section clearfix">
                        <div class="rightside">
                            <img src="../reason/img/sp_jpg/sp_s_flow_a.jpg" alt="早い理由1" width="100%" />
                        </div>
                        <p class="righttxt">
                            その後、お客様に用意して頂いたデザインに対して、弊社で綺麗にプリントができるかを確認致します。詳しくはデザインの注意点をご確認ください。お客様と弊社とでデザインの共有ができましたら、注文確定への準備に移ります、即日対応の注文確定は<span class="yellowtxt">お電話での対応</span>をお願いしております。
                        </p>
                        <p class="smalltxt">
                            当日は<span class="yellowtxt">常に連絡が取れる状態</span>にして頂けるとお客様へのご連絡も大変スムーズになります。
                            <br> 弊社からご注文内容に対しての確認がお客様と共有できましたら、注文確定となり、早速プリントの準備へと入ります。
                        </p>
                    </div>
                    <div class="section clearfix">
                        <div class="leftside">
                            <img src="../reason/img/sp_jpg/sp_s_flow_b.jpg" alt="早い理由1" width="100%" />
                        </div>
                        <p class="lefttxt">
                            即日対応でのプリント種類は、シルクスクリーンプリントと、インクジェットプリントからお選びいただけます。
                        </p>
                        <p class="smalltxt">
                            機械ではなく、<span class="yellowtxt">全て、手作業でプリント</span>していきますので、スピードだけでなく、他社よりも高品質な商品が短納期で出来上がります。プリント作業終了後、一枚ずつスタッフが検品を行い、<span class="yellowtxt">少しでも問題がある商品を事前に弾き</span>、完璧な商品をお客様の元へお届け致します。
                        </p>
                    </div>
                    <div class="section clearfix">
                        <div class="rightside">
                            <img src="../reason/img/sp_jpg/sp_s_flow_c.jpg" alt="早い理由1" width="100%" />
                        </div>
                        <p class="righttxt">
                            東京であれば、<span class="yellowtxt">発送した当日</span>に、それ以外の地域であれば、発送した翌日にオリジナルTシャツがお客様のお手元に到着します。
                        </p>
                        <p class="rightposition">
                            基本的には<span class="linetxt">ヤマト便</span>か<span class="linetxt">佐川急便</span>にて配送させて頂くのですが、<span class="yellowtxt">東京工場でお受け取りも可能</span>です。ご希望のお客様は、<span class="yellowtxt">17：00	&sim;18：00</span>の間に工場へお越し下さい。
                        </p>
                    </div>

                </div>
                <div>
                    <h2 class="redborder"><span id="item" class="anchorlink"></span>即日出荷ができる商品</h2>
                    <p class="toptxt">
                        即日発送対応商品はこちらの<span class="yellowtxt">3種類！</span>
                        <br> Tシャツは、綿100%！
                        <span class="yellowtxt">日本で一番売れている</span>高品質Tシャツ。
                        <br> タオルは柔らかな質感が優しく包み込むコットンタイプをご用意致しました！
                    </p>

                    <ul class="leftimg_ul">
                        <li class="leftimg"><img src="../reason/img/sp_jpg/sp_s_item_01.png" alt="085-CVT1" width="100%" class="limg" />
                            <div class="limg">
                                <p class="toptxt_item">ヘビーウェイトTシャツ
                                    <br>085-CVT
                                    <br>カラー：白
                                    <br>サイズ：S、M、L、XL
                                </p>
                            </div>
                            <p class="itembtn2">
                                <a class="button" href="/items/item.php?code=085-cvt">アイテムを見る</a>
                            </p>
                        </li>

                        <li class="leftimg"><img src="../reason/img/sp_jpg/sp_s_item_02.png" alt="085-CVT2" width="100%" class="limg" />
                            <div class="limg">
                                <p class="toptxt_item">ヘビーウェイトTシャツ
                                    <br>085-CVT
                                    <br>カラー：黒
                                    <br>サイズ：S、M、L、XL

                                </p>
                            </div>
                            <p class="itembtn2">
                                <a class="button" href="/items/item.php?code=085-cvt">アイテムを見る</a>
                            </p>
                        </li>
                        <li class="leftimg"><img src="../reason/img/sp_jpg/sp_s_item_03.png" alt="522-FT" width="100%" class="limg" />
                            <div class="limg">
                                <p class="toptxt_item">フェイスタオル
                                    <br>522-FT
                                    <br>カラー：白
                                    <br>サイズ：フリー
                                </p>
                            </div>
                            <p class="itembtn2">
                                <a class="button" href="/items/item.php?code=522-ft">アイテムを見る</a>
                            </p>
                        </li>
                    </ul>
                    <p class="toptxt">
                        タカハマライフアートは<span class="yellowtxt">即日</span>でオリジナルウェアをお届けできます。
                        <br> 東京であれば、
                        <span class="yellowtxt">ご注文日当日に商品が到着</span>しますので、明日のイベントに間に合います！
                        <br> お急ぎのお客様は一度タカハマライフアートまでお電話下さい。
                        <br> 在庫数やご注文からお届けまでの流れをご説明させて頂きます。
                    </p>
                    <ul class="blockimg">
                        <li class="blockleft"><img src="../reason/img/pc_jpg/s_tel.jpg" alt="電話" width="100%" /></li>
                        <li class="blockleft">
                            <a href="/contact/"><img src="../reason/img/pc_jpg/s_mail.jpg" alt="お問合せ" width="100%" /></a>
                        </li>
                    </ul>
                </div>
                <p class="endtxt"><span class="red_txt">※</span>当日配達の受付は午前12時までとなっておりますので、予めご了承ください。
                </p>
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
