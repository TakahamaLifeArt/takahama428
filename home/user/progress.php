<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

if(isset($_GET['oi'])){
	$orders_id = $_GET['oi'];
}

// TLA顧客IDを取得
$conndb = new Conndb(_API_U);
$u = $conndb->getUserList($me['id']);
//$customerid = $u[0]['tla_customer_id'];
$customerid = $u[0]['id'];
$params = array($customerid, 0);
$conndb = new Conndb(_API);
$d = $conndb->getProgress($params);
if(count($d)==0){
	$msg = "現在進行中のご注文はございません。";
}else{
	$msg = "現在進行中のご注文リスト";
	if(empty($orders_id)) $orders_id = $d[0]['orderid'];
	$idx = 0;
	$ls = '<ol class="orders_list">';
	for($i=0; $i<count($d); $i++){
		$ls .= '<li>';
		if($d[$i]['orderid']!=$orders_id){
			$ls .= '<a href="'.$_SERVER['SCRIPT_NAME'].'?oi='.$d[$i]['orderid'].'">'.$d[$i]['schedule2'].' ご注文確定　No.'.$d[$i]['orderid'].'</a>';
		}else{
			$ls .= $d[$i]['schedule2'].' ご注文確定　No.'.$d[$i]['orderid'];
			$idx = $i;
		}
		$ls .= '</li>';
	}
	$ls .= '</ol>';

	$aryFin = explode(',', $d[$idx]['fin_print']);

	$prog[0] = $d[$idx]['fin_1']? 'pas': 'act';
	if(!$d[$idx]['fin_1']){
		$prog[1] = '';
	}else if(min($aryFin)==0){
		$prog[1] = 'act';
		$prog[2] = '';
	}else{
		$prog[1] = 'pas';
		$prog[2] = 'act';
	}

	$progress = '<p style="margin-left:15px;">【 注文No.'.$d[$idx]['orderid'].' 】</p>';
	$progress .= '<div class="progress_wrap">';
	$progress .= '<ul class="progress_bar">';
	$progress .= '<li><p class="pas">受付完了</p></li>';
	$progress .= '<li><p class="'.$prog[0].'">デザイン制作中</p></li>';
	$progress .= '<li><p class="'.$prog[1].'">プリント開始</p></li>';
	$progress .= '<li><p class="'.$prog[2].'">発送準備中</p></li>';
	$progress .= '<li><p class="'.$prog[3].'">発送完了</p></li>';
	$progress .= '</ul>';
	$progress .= '</div>';

	$progress .= '<dl class="list" style="margin:0 20px 70px;">';
	if($d[$idx]['payment']=='wiretransfer' || $d[$idx]['payment']=='credit'){
		$payment = $d[$idx]['deposit']==2? '確認済み': '<span class="fontred">未確認</span>';
		$progress .= '<dt>ご入金</dt>';
		$progress .= '<dd>'.$payment.'　---　<a href="credit.php?oi='.$d[$idx]['orderid'].'">お支払い状況へ</a></dd>';
	}
	$progress .= '<dt>お届け日</dt>';
	$progress .= '<dd>'.$d[$idx]['schedule4'].' 予定です</dd>';
	$aryBlog = explode(',', $d[$idx]['blog']);
	if(max($aryBlog)>0){
		$progress .= '<dt>ブログ割の方</dt>';
		$progress .= '<dd>お写真とコメントをお待ちしております。<a href="./blog/postform.php?oi='.$d[$idx]['orderid'].'">投稿フォームへ進む</a></dd>';
	}
	$progress .= '</dl>';


	// 配送業者
	$deliver = array(
		array('name'=>'-', 'url'=>''),
		array('name'=>'佐川急便', 'url'=>'//k2k.sagawa-exp.co.jp/p/sagawa/web/okurijoinput.jsp'),
		array('name'=>'ヤマト運輸', 'url'=>'//toi.kuronekoyamato.co.jp/cgi-bin/tneko'),
	);
	$deliver_name = $deliver[$d[$idx]['deliver']]['name'];
	$url = $deliver[$d[$idx]['deliver']]['url'];
}

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
        <title>製作の進行状況 - TLAメンバーズ | オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico" />
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" type="text/css" media="screen" href="./css/my_progress.css" />
    </head>

    <body>
        <header>
            <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
        </header>

        <div id="container">
            <div class="contents">

                <div class="toolbar">
                    <div class="toolbar_inner clearfix">
                        <div class="menu_wrap">
                            <?php echo $menu;?>
                        </div>
                    </div>
                </div>
                <div class="pagetitle">
                    <h1>製作の進行状況</h1>
                </div>

                <div class="section">
                    <h2 class="title">
                        <?php echo $msg; ?>
                    </h2>
                    <?php echo $ls; ?>
                </div>

                <?php echo $progress; ?>

                <div class="section">
                    <h2>進行状況の説明</h2>
                    <div class="inner">
                        <table class="form_table" id="description">
                            <thead>
                                <tr>
                                    <th>進行状況</th>
                                    <th>説明</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>受付完了</td>
                                    <td>注文が確定し、受付が完了した状態です。</td>
                                </tr>
                                <tr>
                                    <td>デザイン制作中</td>
                                    <td>デザインの校正等をおこない、プリントするデザインを制作します。</td>
                                </tr>
                                <tr>
                                    <td>プリント開始</td>
                                    <td>版下、製版などの工程を経て実際に商品にプリントしていきます。</td>
                                </tr>
                                <tr>
                                    <td>発送準備中</td>
                                    <td>商品が完成し発送の準備をします。</td>
                                </tr>
                                <tr>
                                    <td>発送完了</td>
                                    <td>運送会社への出荷が済んだ状態です。<br>問合せ番号で荷物の配達状況が分かります。</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="section">
                    <h2>運送会社に問い合わせる</h2>
                    <div class="inner">
                        <dl class="list">
                            <dt>お問合せ番号</dt>
                            <dd>
                                <?php echo $d[$idx]['contact_number'];?>
                            </dd>
                            <dt>運送業者</dt>
                            <dd>
                                <?php echo $deliver_name;?>
                            </dd>
                            <dt>URL</dt>
                            <dd>
                                <?php
								if(!empty($url)){
									echo '<a href="'.$url.'" target="_blank" rel="nofollow">'.$deliver_name.'の荷物お問合せへ</a>';
								}
								?>
                            </dd>
                        </dl>
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
