<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

// TLA顧客IDを取得
$conndb = new Conndb(_API_U);
$u = $conndb->getUserList($me['id']);
$customerid = $u[0]['id'];
$username = $u[0]['customername'];
$email = $u[0]['email'];

// 受注No.
if(isset($_REQUEST['oi'])){
	$orderid = $_REQUEST['oi'];
}else{
	jump('menu.php');
}

// 注文情報取得
$conndb = new Conndb(_API);
$d = $conndb->getOroderHistory($customerid);
for($i=0; $i<count($d); $i++){
	if($d[$i]['orderid']==$orderid){
		$cur = $i;
		$shcedule2 = $d[$i]['schedule2'];
		break;
	}
}
if(!isset($cur)) jump('menu.php');

/*
*	アイテムのセレクター
*/
$i = $cur;
$curitemid = null;
foreach($d[$i]['itemlist'] as $itemname=>$info){
	foreach($info as $color=>$val){
		if($val[0]['itemcode']=='ss-9999-96') break;
		$options .= '<option value="'.$val[0]['itemid'].'" rel="'.$val[0]['itemcode'].'"';
		$options .= ' >'.$itemname.'</option>';
		$curitemid = is_null($curitemid)? $val[0]['itemid']: $curitemid;
		break;
	}
}
if(empty($options)){
	$options = '<option value="0" rel="" selected="selected">---</option>';
}else{
	$options = preg_replace('/<option/','<option selected="selected"',$options, 1);
}
$item_selector = '<select id="item_selector" onchange="$.changeItem()">'.$options.'</select>';

// サムネイル
if(!is_null(curitemid)){
	$itemattr = $conndb->itemAttr($curitemid);
	list($categorykey, $categoryname) = each($itemattr['category']);
	list($itemcode, $itemname) = each($itemattr['name']);
	list($code, $colorname) = each($itemattr['code']);
	$color_count = 0;
	foreach($itemattr['code'] as $code=>$colorname){
		$color_count++;
		$c = explode('_', $code);
		$thumbs .= '<li';
		if($color_count==1){
			$thumbs .= ' class="nowimg"';
			$curcolor = $colorname;
			$curcode = $code;
		}
		$thumbs .= '><img alt="'.$c[1].'" title="'.$colorname.'" src="'._IMG_PSS.'items/'.$categorykey.'/'.$itemcode.'/'.$code.'_s.jpg" /></li>';
	}
}

// カレントカラーの写真
$curthumb = '<img src="'._IMG_PSS.'items/'.$categorykey.'/'.$itemcode.'/'.$curcode.'.jpg" width="300" height="300">';

$ticket = htmlspecialchars(md5(uniqid().mt_rand()), ENT_QUOTES);
?>
    <!DOCTYPE html>
    <html lang="ja">

    <head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
        <meta charset="UTF-8">
        <meta name="Description" content="オリジナルTシャツ屋でオリジナルＴシャツを作成した場合のお見積もりをオンラインで簡単に見ることができます。最短でオリジナルプリントを作成したい方はタカハマライフアートへ！" />
        <meta name="keywords" content="見積,簡単,Tシャツ,オリジナル,作成,激安,東京" />
        <meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
        <meta property="og:type" content="article" />
        <meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
        <meta property="og:url" content="https://www.takahama428.com/" />
        <meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
        <meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
        <meta property="fb:app_id" content="1605142019732010" />
        <title>追加注文フォーム - TLAメンバーズ ｜ オリジナルTシャツ【タカハマライフアート】</title>
        <link rel="shortcut icon" href="/icon/favicon.ico" />
        <?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/flick/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="/common/css/jquery.ui.css" media="screen">
        <link rel="stylesheet" type="text/css" media="screen" href="./css/repeatorder_responsive.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="./css/my_history.css" />
        <script type="text/javascript">
            var _IMG_PSS = "<?php echo _IMG_PSS?>";

        </script>
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
                    <h1>追加注文フォーム</h1>
                </div>

                <div id="item_wrap" class="wrap">
                    <h2>1.商品をお選びください</h2>
                    <table>
                        <caption></caption>
                        <tbody>
                            <tr>
                                <th>商品名</th>
                                <td>
                                    <?php echo $item_selector; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="note"><span>※</span>違うアイテムをご希望の場合は新規注文扱いとなりますので、<a href="/order/">お申し込みページ</a>へお進みください。</p>
                </div>

                <div id="price_wrap" class="wrap">
                    <h2>2.カラーとサイズごとの枚数をご指定ください。</h2>
                    <div class="thumb_wrap clearfix">
                        <div id="item_image">
                            <?php echo $curthumb; ?>
                            <div class="dotted"></div>
                            <p>カラー：　<span id="notes_color"><?php echo $curcolor; ?></span></p>
                        </div>

                        <div id="item_thumb">
                            <div class="item_colors" class="throbber">
                                <p class="thumb_h">(1) Color　全<span class="num_of_color"><?php echo $color_count; ?></span>色</p>
                                <ul class="color_thumb clearfix">
                                    <?php echo $thumbs; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <table>
                        <caption>(2) サイズと枚数</caption>
                        <tbody></tbody>
                    </table>
                    <div id="addList">申込リストに追加</div>
                </div>

                <div id="repeat_wrap" class="wrap">
                    <h2>3.申込リスト</h2>
                    <table class="form_table" id="detail_item">
                        <thead>
                            <tr>
                                <th>商品名</th>
                                <th>カラー</th>
                                <th>サイズ</th>
                                <th>枚数</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="foot_total">
                                <th></th>
                                <td colspan="2">合計枚数</td>
                                <td class="tot"><ins>0</ins> 枚</td>
                                <td></td>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="wrap">
                    <h2>4.メール送信</h2>
                    <p class="note">申込リストの内容を送信いたします。</p>
                    <form name="contact_form" id="contact_form" method="post" action="/contact/transmit.php" enctype="multipart/form-data" onsubmit="return false;">
                        <table id="enq_table">
                            <tbody>
                                <tr>
                                    <th>お名前</th>
                                    <td class="point">※</td>
                                    <td><input name="customername" type="text" value="<?php echo $username; ?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td class="point">※</td>
                                    <td><input name="email" type="text" value="<?php echo $email;?>" class="email" /></td>
                                </tr>
                                <tr>
                                    <th>電話番号</th>
                                    <td>&nbsp;</td>
                                    <td><input name="tel" type="text" class="forPhone" /></td>
                                </tr>
                                <tr>
                                    <th>お届け希望日</th>
                                    <td>&nbsp;</td>
                                    <td><input name="deliveryday" id="deliveryday" class="datepicker" type="text" value="" /></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><label><input type="checkbox" name="deli" id="deli" value="1">別のお届け先を指定する</label></td>
                                </tr>
                                <tr id="deli_wrap">
                                    <th>ご住所</th>
                                    <td class="point">&nbsp;</td>
                                    <td>
                                        <p>〒<input name="zipcode" id="zipcode" class="forZip" type="text" placeholder="○○○-○○○" onKeyup="AjaxZip3.zip2addr(this,'','addr0','addr1');" /></p>
                                        <p><input name="addr0" id="addr0" type="text" placeholder="都道府県" maxlength="4" /></p>
                                        <p><input name="addr1" id="addr1" type="text" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" class="restrict" /></p>
                                        <p><input name="addr2" id="addr2" type="text" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>メッセージ</th>
                                    <td>&nbsp;</td>
                                    <td><textarea name="message" id="message" cols="40" rows="7"></textarea></td>
                                </tr>
                                <tr>
                                    <th>添付ファイル</th>
                                    <td>&nbsp;</td>
                                    <td><input type="file" name="attachfile[]" /></td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="add_attachfile" onClick="$.add_attach('enq_table');">別の添付ファイルを追加</p>
                        <p class="point">「※」 は必須です。</p>
                        <input type="hidden" name="orders_id" value="<?php echo $orderid; ?>" />
                        <input type="hidden" name="ticket" value="<?php echo $ticket; ?>" />
                        <input type="hidden" name="title" value="repeat" />
                        <p class="button_area">
                            <button class="btn btn-primary" id="sendmail">送　信</button>
                        </p>
                        <div id="estimate_detail"></div>
                    </form>
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
        <script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
        <script src="./js/repeatorder.js"></script>
    </body>

    </html>
