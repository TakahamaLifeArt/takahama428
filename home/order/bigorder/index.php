<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
$conn = new Conndb();

// category selector
$data = $conn->categoryList();
$category_selector = '<select id="category_selector" name="category">';
$category_selector .= '<option value="" selected="selected">-</option>';
for($i=0; $i<count($data); $i++){
$categoryName = $data[$i]['name'];
$category_selector .= '<option value="'.$data[$i]['code'].'" rel="'.$data[$i]['id'].'"';
$category_selector .= '>'.$categoryName.'</option>';
}
$category_selector .= '</select>';
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og://ogp.me/ns# fb://ogp.me/ns/fb#  website: //ogp.me/ns/website#">
	<meta charset="UTF-8">
	<meta https-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content=大口のオリジナルTシャツも短納期で早い！タカハマでは個人様・法人様どなたでも大口の注文を承ります。100枚・200枚でも通常3日仕上げ！150枚以上・300枚以上のご注文は大幅値引き。まとめて買うとお買い得！特急のお急きプリントが早い・丁寧・親切の東京都で作る品質タカハマライフアートへ！ "">
	<meta name="keywords" content="tシャツ,大口,早い,作成">
	<meta property="og:title" content="大口も早い！オリジナルTシャツ作成｜タカハマライフアート" />
	<meta property="og:type" content="website" />
	<meta property="og:description" content="大口のオリジナルTシャツも短納期で早い！100枚・200枚でも通常3日仕上げ！150枚以上・300枚以上のご注文は大幅値引き。特急のお急きプリントが早い・丁寧・親切の東京都で作る品質タカハマライフアートへ！" />
	<meta property="og:url" content="https://www.takahama428.com/" />
	<meta property="og:site_name" content="オリジナルTシャツの作成・プリントはタカハマライフアート" />
	<meta property="fb:app_id" content="1605142019732010" />
	<title>大口お問い合わせ ｜ オリジナルTシャツ【タカハマライフアート】</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<link rel="stylesheet" type="text/css" href="css/mailform.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
</head>

<body>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>


	<div id="container">
		<div class="contents">
			<ul class="pan">
				<li><a href="/">オリジナルＴシャツ屋ＴＯＰ</a></li>
				<li>大口問い合わせ</li>
			</ul>


			<div class="heading1_wrapper_new">
				<h1>大口問い合わせ</h1>
				<p class="comment">法人・学校・団体・個人様！どなたでも大量注文したい方！</p>
			</div>
			<p class="nmt50"><img src="img/high_img_00.png" alt="Tシャツ業界No.1　受注のレスポンスも早い！大量注文も短納期" width="100%" class="mb20"></p>
			<h2 class="responce">シルクスクリーンプリントの実績例</h2>
			<table width="100%" border="0" class="case">
				<tr>
					<th>数量</th>
					<th>プリント1箇所</th>
					<th>プリント2箇所</th>
				</tr>
				<tr>
					<td class="bgred">
						<div class="early">早</div><strong class="fs20">150枚</strong></td>
					<td class="bgpink"><strong>通常<span class="fs20">3日</span></strong>仕上げ</td>
					<td class="bgpink"><strong>4日</strong>仕上げ</td>
				</tr>
				<tr>
					<td class="bgred">
						<div class="early">早</div><strong class="fs20">200枚</strong></td>
					<td class="bgpink"><strong>通常<span class="fs20">3日</span></strong>仕上げ</td>
					<td class="bgpink"><strong>4日</strong>仕上げ</td>
				</tr>
				<tr>
					<td class="btbdr bggray">300枚</td>
					<td class="btbdr rbdr">6日 仕上げ</td>
					<td class="btbdr lbdr">9日 仕上げ</td>
				</tr>
				<tr>
					<td class="btbdr bggray">400枚</td>
					<td class="btbdr rbdr">8日 仕上げ</td>
					<td class="btbdr lbdr">12日 仕上げ</td>
				</tr>
				<tr>
					<td class="btbdr bggray">500枚</td>
					<td class="btbdr rbdr">11日 仕上げ</td>
					<td class="btbdr lbdr">14日 仕上げ</td>
				</tr>
				<tr>
					<td class="bggray">600枚以上</td>
					<td colspan="2" class="btbdr">要相談</td>
				</tr>
			</table>
			<p class="ac mb20"><span class="fontred">※</span>時期や<a href="https://www.takahama428.com/design/printing.php">プリント方法</a>によって上記の納期より前後する場合がございます。
				<a href="https://www.takahama428.com/order/express/">即日発送</a>をご希望の方はご相談ください！</p>
			<p class="ac"><img src="img/high_img_02.png" alt="まとめて買うと150枚以上から安くなる！" width="100%"></p>
			<!--
			<p class="ac fs28 fontred"><strong>他にもこんなサービスあります！</strong></p>
			<p class="ac fs28 mb20"><strong>プリントの仕上がりを確認できます！</strong></p>
			<img src="img/high_img_03.png" alt="サンプル出し" width="100%" class="fr mr70 mb20">
			<h2 class="fs20 ml70 sample">サンプル出し</h2>
			<p class="ml70">1枚だけプリントし<br> 郵送して確認できるサービスです。
			</p>
			<p class="ml70"><span class="fontred">※</span>ご確認後のデザイン変更は新たに版代がかかります</p>
-->
			<div class="orangebdr clear">
				<p class="ac fs20 mb20"><strong>法人・学校・団体・個人様！どなたでもOK !!</strong></p>
				<p class="ml40 mb20">どなたでも対応させていただきます。<a href="https://www.takahama428.com/order/express/">短納期</a>のご相談も承りますので、<br> お気軽にお問い合わせください。
				</p>
				<p class="ac fs28"><strong>まずはお気軽にご相談！</strong></p>
			</div>
			<!--class orange-->
			<h2 class="fs20 ac fwnormal">お問い合わせフォームはこちら</h2>
			<!--form-->
			<h2 class="title_confirmation">入力内容の確認</h2>
			<form name="bigorder_form" id="bigorder_form" class="bigorder e-mailer" method="post">
				<table class="big_table">
					<tbody>
						<tr>
							<th>オリジナル制作について(用途・枚数)</th>
						</tr>
						<tr>
							<th class="gray"><label>ご利用用途をお選びください。</label>(複数可)<span class="must">必須</span></th>
						</tr>
						<tr>
							<td>
								<fieldset>
									<input name="youto" type="checkbox" value="販促" id="c01"><label class="checkbox" for="c01"> 販促</label>
									<input name="youto" type="checkbox" value="商品としての物販" id="c02"><label class="checkbox" for="c02"> 商品としての物販</label>
									<input name="youto" type="checkbox" value="ユニフォーム" id="c03"><label class="checkbox" for="c03"> ユニフォーム</label>
									<input name="youto" type="checkbox" value="その他" id="c04"><label class="checkbox" for="c04"> その他</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th class="gray"><label>制作枚数を選択ください</label><span class="must">必須</span></th>
						</tr>
						<tr>
							<td>
								<fieldset>
									<input name="vol" type="radio" value="150&#65374;299枚" id="c11"><label class="radio" for="c11"> 150&#65374;299枚</label>
									<input name="vol" type="radio" value="300&#65374;399枚" id="c12"><label class="radio" for="c12"> 300&#65374;399枚</label>
									<input name="vol" type="radio" value="400&#65374;499枚" id="c13"><label class="radio" for="c13"> 400&#65374;499枚</label><br>
									<input name="vol" type="radio" value="500&#65374;599枚" id="c14"><label class="radio" for="c14"> 500&#65374;599枚</label>
									<input name="vol" type="radio" value="600枚以上" id="c15"><label class="radio" for="c15"> 600枚以上</label>
								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="big_table02">
					<tbody>
						<tr>
							<th colspan="2" class="gray">お客様情報をご入力ください</th>
						</tr>
						<tr>
							<th><label>お名前</label><span class="must">必須</span></th>
							<td>
								<p class="txt"></p>
								<input name="customername" type="text" value="" placeholder="例)高濱　太郎" required>
							</td>
						</tr>
						<tr>
							<th><label>フリガナ</label><span class="must">必須</span></th>
							<td>
								<p class="txt"></p>
								<input name="customerruby" type="text" value="" placeholder="例)タカハマ　タロウ" required>
							</td>
						</tr>
						<tr>
							<th><label>お電話番号</label><span class="must">必須</span></th>
							<td>
								<p class="txt"></p>
								<input name="tel" type="tel" placeholder="例) 08012345678" required>
							</td>
						</tr>
						<tr>
							<th><label>メールアドレス</label><span class="must">必須</span></th>
							<td>
								<p class="txt"></p>
								<input name="email" type="email" class="email" placeholder="例) xxx@mail.co.jp" required>
							</td>
						</tr>
						<tr>
							<th><label>お届け先都道府県</label><span class="must">必須</span></th>
							<td>
								<p class="txt"></p>
								<select name="pref" required>
									<option value="" selected="selected">都道府県</option>
									<option value="北海道">北海道</option>
									<option value="青森県">青森県</option>
									<option value="岩手県">岩手県</option>
									<option value="宮城県">宮城県</option>
									<option value="秋田県">秋田県</option>
									<option value="山形県">山形県</option>
									<option value="福島県">福島県</option>
									<option value="茨城県">茨城県</option>
									<option value="栃木県">栃木県</option>
									<option value="群馬県">群馬県</option>
									<option value="埼玉県">埼玉県</option>
									<option value="千葉県">千葉県</option>
									<option value="東京都">東京都</option>
									<option value="神奈川県">神奈川県</option>
									<option value="新潟県">新潟県</option>
									<option value="富山県">富山県</option>
									<option value="石川県">石川県</option>
									<option value="福井県">福井県</option>
									<option value="山梨県">山梨県</option>
									<option value="長野県">長野県</option>
									<option value="岐阜県">岐阜県</option>
									<option value="静岡県">静岡県</option>
									<option value="愛知県">愛知県</option>
									<option value="三重県">三重県</option>
									<option value="滋賀県">滋賀県</option>
									<option value="京都府">京都府</option>
									<option value="大阪府">大阪府</option>
									<option value="兵庫県">兵庫県</option>
									<option value="奈良県">奈良県</option>
									<option value="和歌山県">和歌山県</option>
									<option value="鳥取県">鳥取県</option>
									<option value="島根県">島根県</option>
									<option value="岡山県">岡山県</option>
									<option value="広島県">広島県</option>
									<option value="山口県">山口県</option>
									<option value="徳島県">徳島県</option>
									<option value="香川県">香川県</option>
									<option value="愛媛県">愛媛県</option>
									<option value="高知県">高知県</option>
									<option value="福岡県">福岡県</option>
									<option value="佐賀県">佐賀県</option>
									<option value="長崎県">長崎県</option>
									<option value="熊本県">熊本県</option>
									<option value="大分県">大分県</option>
									<option value="宮崎県">宮崎県</option>
									<option value="鹿児島県">鹿児島県</option>
									<option value="沖縄県">沖縄県</option>
								</select>
							</td>
						</tr>
						<tr>
							<th><label>ご希望納期</label><span class="free">任意</span></th>
							<td>
								<p class="txt"></p>
								<input name="deliveryday" type="text" id="datepick">
							</td>
						</tr>
						<tr>
							<th><label>アイテム種類</label><span class="free">任意</span></th>
							<td>
								<p class="txt"></p>
								<?php echo $category_selector;?>
							</td>
						</tr>
						<th><span class="free">任意</span>その他<br> 要望コメント
						</th>
						<td>
							<div class="txt"></div>
							<textarea name="message" id="message" cols="20" rows="7" placeholder="コメントはこちらに記入してください。"></textarea>
						</td>
						<tr>
							<th><label>添付ファイル</label><span class="free">任意</span></th>
							<td>
								<input type="file" name="attachfile" multiple>
<!--								<p class="add_attachfile" onClick="$.add_attach(this);">別の添付ファイルを追加</p>-->
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="sendto" value="<?php echo _INFO_EMAIL;?>">
				<input type="hidden" name="subject" value="大口注文お問い合わせ">
				<input type="hidden" name="title" value="大口注文お問い合わせ">
				<input type="hidden" name="replyto" value="email">
				<input type="hidden" name="replyhead" value="このたびは、タカハマライフアートをご利用いただき誠にありがとうございます。">
				<div class="ac">
					<button type="submit" class="order_btn_2" id="sendmail">送信</button>
				</div>
			</form>

		</div>

	</div>

	<footer class="page-footer">
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
	</footer>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

	<div id="overlay-mask" class="fade"></div>

	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	<script src="https://doozor.bitbucket.io/email/e-mailform.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
	<script src="https://doozor.bitbucket.io/calendar/datepick_calendar.min.js?dat=<?php echo _DZ_ACCESS_TOKEN;?>"></script>
	<script type="text/javascript" src="./js/mailform.js"></script>

</body>

</html>
