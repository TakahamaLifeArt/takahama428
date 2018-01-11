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
		<meta name="Description" content="タカハマライフアートのマイページ、追加注文時の納期確認画面です。ご希望のお届け日に間違いがないか、ご確認お願いいたします。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>追加・再注文フォーム - お申し込みリスト | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/flick/jquery-ui.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/reorder_day.css" />
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
							<h1 id="user_name">追加・再注文フォーム</h1>
						</div>
					</div>
				</div>

				<section>
					<h2>お申し込みリスト</h2>
					
					<table class="order_list">
						<tbody>
							<tr class="tabl_ttl">
								<td>アイテム</td>
								<td>カラー</td>
								<td>サイズ</td>
								<td>枚数</td>
								<td></td>
							</tr>
							<tr>
								<td>5001 5.6オンスハイクオリティーTシャツ</td>
								<td>オレンジ</td>
								<td>M</td>
								<td>10枚</td>
								<td><button type="button" class="btn btn-outline-danger waves-effect del_btn">削除</button></td>
							</tr>
						</tbody>
					</table>

					<div class="block">
						<table class="order_list cust">
							<thead>
								<tr class="tabl_ttl">
									<th scope="col" colspan="2">お客様情報</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>お名前</td>
									<td>高濱&nbsp;太郎&nbsp;様</td>
								</tr>
								<tr>
									<td>フリガナ</td>
									<td>タカハマ&nbsp;タロウ&nbsp;様</td>
								</tr>
								<tr>
									<td>電話番号</td>
									<td>03-5670-0787</td>
								</tr>
								<tr>
									<td>メールアドレス</td>
									<td>aaa@gmail.com</tr>
							</tbody>
						</table>
					</div>

					<div class="block">
						<div class="list_ttl">お届け希望日</div>
						<div class="date_sel">
							<div id="datepick" class="cale_box">
								<table class="schedule_calendar fade in" id="id_33137">
									<caption>
										<div class="flex-container_wrap justify-between">
											<div><ins class="sc_year">2018</ins><span>年</span><ins class="sc_month">1</ins><span>月</span></div>
											<div class="mdl-layout-spacer"></div>
											<div><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon prev-month" data-upgraded=",MaterialButton,MaterialRipple"><i class="material-icons"></i><span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span></button><button class="mdl-button mdl-js-button mdl-js-ripple-effect current-month" data-upgraded=",MaterialButton,MaterialRipple"><span>今日</span><span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span></button><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon next-month" data-upgraded=",MaterialButton,MaterialRipple"><i class="material-icons"></i><span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span></button></div>
										</div>
									</caption>
									<thead>
										<tr>
											<td class="sun">日</td>
											<td>月</td>
											<td>火</td>
											<td>水</td>
											<td>木</td>
											<td>金</td>
											<td class="sat">土</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="restrict off sun pass"><ins>31</ins></td>
											<td class="restrict off dayoff off"><ins>1</ins></td>
											<td class="restrict off"><ins>2</ins></td>
											<td class="restrict off"><ins>3</ins></td>
											<td class="restrict off"><ins>4</ins></td>
											<td class="restrict off"><ins>5</ins></td>
											<td class="restrict off sat"><ins>6</ins></td>
										</tr>
										<tr>
											<td class="restrict off sun"><ins>7</ins></td>
											<td class="restrict off dayoff off"><ins>8</ins></td>
											<td class="restrict"><ins>9</ins></td>
											<td class="restrict today">
												<div><ins>10</ins></div>
											</td>
											<td class="restrict"><ins>11</ins></td>
											<td class="ripplable"><ins>12</ins></td>
											<td class="ripplable off sat"><ins>13</ins></td>
										</tr>
										<tr>
											<td class="ripplable off sun"><ins>14</ins></td>
											<td class="ripplable"><ins>15</ins></td>
											<td class="ripplable"><ins>16</ins></td>
											<td class="ripplable"><ins>17</ins></td>
											<td class="ripplable"><ins>18</ins></td>
											<td class="ripplable"><ins>19</ins></td>
											<td class="ripplable off sat"><ins>20</ins></td>
										</tr>
										<tr>
											<td class="ripplable off sun"><ins>21</ins></td>
											<td class="ripplable"><ins>22</ins></td>
											<td class="ripplable"><ins>23</ins></td>
											<td class="ripplable"><ins>24</ins></td>
											<td class="ripplable"><ins>25</ins></td>
											<td class="ripplable"><ins>26</ins></td>
											<td class="ripplable off sat"><ins>27</ins></td>
										</tr>
										<tr>
											<td class="ripplable off sun"><ins>28</ins></td>
											<td class="ripplable"><ins>29</ins></td>
											<td class="ripplable"><ins>30</ins></td>
											<td class="ripplable"><ins>31</ins></td>
											<td class="ripplable yet"><ins>1</ins></td>
											<td class="ripplable yet"><ins>2</ins></td>
											<td class="ripplable off sat yet"><ins>3</ins></td>
										</tr>
									</tbody>
								</table>
							</div>
							<p class="note"><span class="red_mark">※</span>特急料金がかかります。(翌日仕上げ)</p>
							<input type="checkbox" value="1" name="" id="">
							<label for="front">お届け先が、北海道、九州、沖縄、東京離島、島根隠岐郡のいずれかとなる場合はチェックして下さい。</label>
							<p class="time_sel">お時間帯の指定</p>
							<div class="form-group">
								<div class="btn-group">
									<select id="" class="down_cond">
									<option value="" selected="selected" rel="">午前中</option>
									<option value="" selected="selected" rel="">14:00-16:00</option>
									<option value="" selected="selected" rel="">16:00-18:00</option>
									<option value="" selected="selected" rel="">18:00-20:00</option>
									<option value="" selected="selected" rel="">19:00-21:00</option>
									<option value="" selected="selected" rel="">指定なし</option>
								</select>
								</div>
							</div>
							<div class="deli_date">
								ご希望納期：<span>-</span>月<span>-</span>日
							</div>
						</div>
					</div>

					<div class="block">
						<table class="order_list cust">
							<thead>
								<tr class="tabl_ttl">
									<th scope="col" colspan="2">お届け先</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>郵便番号</td>
									<td>〒000-0000</td>
								</tr>
								<tr>
									<td>住所1 (都道府県)</td>
									<td>東京都</td>
								</tr>
								<tr>
									<td>住所2 (市区町村番地)</td>
									<td>○○○○○○○○○</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="add_cha block">
						<button class="btn add_btn" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-angle-down mr-1" aria-hidden="true"></i>お届け先を変更する</button>
					</div>

					<div class="collapse" id="collapseExample">

						<div class="cha_addr">
							<ul>
								<li>
									<p>〒<input type="text" name="zipcode" class="forZip" id="zipcode1" onChange="AjaxZip3.zip2addr(this,'','addr0','addr1');" placeholder="郵便番号" /></p>
									<p><input type="text" name="addr0" id="addr0" placeholder="都道府県" maxlength="4" /></p>
									<p><input type="text" name="addr1" id="addr1" placeholder="葛飾区西新小岩1-23-456" maxlength="56" class="restrict" /></p>
									<p><input type="text" name="addr2" id="addr2" placeholder="マンション・ビル名" maxlength="32" class="restrict" /></p>
								</li>
							</ul>
						</div>
						<span class="ok_button">更新</span>
						<span class="cancel_button">Cancel</span>
					</div>




					<div class="block">
						<div class="list_ttl">メッセージ</div>
						<textarea class="mes" placeholder="プリント色を変更"></textarea>
					</div>

					<div class="price_box">
						<p class="total_p">合計：10,000円(税込)</p>
						<p class="solo_p">1枚あたり: 1,000円(税込)</p>
						<a href="/user_test/reorder_final.php"><button type="button" class="btn btn-info">お申し込み内容を確認</button></a>
						<p class="note"><span class="red_mark">※</span>条件によって値段が変わる場合がございます。</p>
					</div>

				</section>


				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<div class="step_prev hoverable waves-effect"><i class="fa fa-chevron-left mr-1"></i>戻る</div>
				</div>
			</div>
		</div>

		<footer class="page-footer">
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
		</footer>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

		<div id="overlay-mask" class="fade"></div>

		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
		<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script type="text/javascript" src="./js/account.js"></script>
		<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="//doozor.bitbucket.io/calendar/datepick_calendar.js"></script>
		<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
	</body>

	</html>
