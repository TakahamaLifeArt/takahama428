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
		<title>追加・再注文フォーム | タカハマライフアート</title>
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
						<div class="date_block">
						<div id="datepicker" class="hasDatepicker">
							<div class="ui-datepicker-inline ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all date" style="display: block;">
								<div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all"><a class="ui-datepicker-prev ui-corner-all" data-handler="prev" data-event="click" title="<前"><span class="ui-icon ui-icon-circle-triangle-w">&lt;前</span></a><a class="ui-datepicker-next ui-corner-all" data-handler="next" data-event="click" title="次>"><span class="ui-icon ui-icon-circle-triangle-e">次&gt;</span></a>
									<div class="ui-datepicker-title"><span class="ui-datepicker-year">2017</span>年&nbsp;<span class="ui-datepicker-month">12月</span></div>
								</div>
								<table class="ui-datepicker-calendar">
									<thead>
										<tr>
											<th scope="col" class="ui-datepicker-week-end"><span title="日曜日">日</span></th>
											<th scope="col"><span title="月曜日">月</span></th>
											<th scope="col"><span title="火曜日">火</span></th>
											<th scope="col"><span title="水曜日">水</span></th>
											<th scope="col"><span title="木曜日">木</span></th>
											<th scope="col"><span title="金曜日">金</span></th>
											<th scope="col" class="ui-datepicker-week-end"><span title="土曜日">土</span></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class=" ui-datepicker-week-end ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">1</a></td>
											<td class=" ui-datepicker-week-end days_blue" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">2</a></td>
										</tr>
										<tr>
											<td class=" ui-datepicker-week-end days_red" title="休日" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">3</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">4</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">5</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">6</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">7</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">8</a></td>
											<td class=" ui-datepicker-week-end days_blue" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">9</a></td>
										</tr>
										<tr>
											<td class=" ui-datepicker-week-end days_red" title="休日" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">10</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">11</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">12</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">13</a></td>
											<td class=" ui-datepicker-days-cell-over undefined ui-datepicker-current-day ui-datepicker-today" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default ui-state-highlight ui-state-active" href="#">14</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">15</a></td>
											<td class=" ui-datepicker-week-end days_blue" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">16</a></td>
										</tr>
										<tr>
											<td class=" ui-datepicker-week-end days_red" title="休日" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">17</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">18</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">19</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">20</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">21</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">22</a></td>
											<td class=" ui-datepicker-week-end days_blue" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">23</a></td>
										</tr>
										<tr>
											<td class=" ui-datepicker-week-end days_red" title="休日" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">24</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">25</a></td>
											<td class=" undefined" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">26</a></td>
											<td class=" days_red" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">27</a></td>
											<td class=" days_red" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">28</a></td>
											<td class=" days_red" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">29</a></td>
											<td class=" ui-datepicker-week-end days_blue" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">30</a></td>
										</tr>
										<tr>
											<td class=" ui-datepicker-week-end days_red" title="休日" data-handler="selectDay" data-event="click" data-month="11" data-year="2017"><a class="ui-state-default" href="#">31</a></td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
											<td class=" ui-datepicker-week-end ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td>
										</tr>
									</tbody>
								</table>
							</div>
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
						<div class="deli_time">
							ご希望納期：<span>10</span>月<span>27</span>日
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
						<button class="btn add_btn"type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-angle-down mr-1" aria-hidden="true"></i>お届け先を変更する</button>
					</div>

					<div class="collapse" id="collapseExample">
						
							<div class="cha_addr">
								<ul>
									<li>
										<p>〒<input type="text" name="zipcode" class="forZip" id="zipcode1" onChange="AjaxZip3.zip2addr(this,'','addr0','addr1');" placeholder="郵便番号" /></p>
										<p><input type="text" name="addr0" id="addr0"  placeholder="都道府県" maxlength="4" /></p>
										<p><input type="text" name="addr1" id="addr1"  placeholder="葛飾区西新小岩1-23-456" maxlength="56" class="restrict" /></p>
										<p><input type="text" name="addr2" id="addr2"  placeholder="マンション・ビル名" maxlength="32" class="restrict" /></p>
									</li>
								</ul>
							</div>
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
	</body>

	</html>
