<?php
declare(strict_types=1);
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/package/Calendar.php';
?>
<div class="container-fluid">
	<div class="footer_info">
		<p class="footer_contact">お問い合わせ</p>
		<p class="footer_kindly">親切対応 日本一を目指します!</p>
		<ul class="footer_field">
			<li class="field_area">
				<ul class="field_block">
					<li class="field-item hidden-sm-down">
						<a href="tel:0120130428" class="nav-link">
							<p><i class="fa fa-phone" aria-hidden="true"></i>
								<em style="font-style:normal;">0120-130-428</em><br>
								<em style="font-style:normal;"><span>FAX：03-5670-0730</span></em>
							</p>
						</a>
					</li>
					<li class="field-item hidden-md-up">
						<a href="tel:0120130428" class="tel_btn sam_btn btn waves-effect waves-light" style="border:2px solid #EF6C00;">
							<i class="fa fa-phone" aria-hidden="true" style="top:11px; right:5px;"></i>
							<span style="font-size:1.1rem;">0120-130-428</span><br><span style="font-size:0.9rem;">電話する</span>
						</a>
					</li>
					<li class="field-item">
						<a href="/contact/" class="btn_or_btn btn waves-effect waves-light" type="button">
					<i class="fa fa-envelope-o" aria-hidden="true"></i>
					<span>お問い合わせ</span>
				</a>
					</li>
				</ul>
			</li>
			<li class="field_area">
				<p><span class="time">月&#126;金 10:00&#126;18:00 (土日祝休み)</span></p>
			</li>
			<li class="field_area"><p class="p_line">LINEでもお問い合わせ出来ます。</p>
			</li>
			<li class="field_area">
				<a href="/contact/line/" class="line_btn btn waves-effect waves-light btn-line">
					<svg xmlns="//www.w3.org/2000/svg" xmlns:xlink="//www.w3.org/1999/xlink" width="55px" height="55px" style="margin-left: -130px; margin-top: 18px;" viewBox="0 0 315 300">
						<defs>
							<style>
								.fill_1 {fill: #ffffff;}
								.fill_2 {fill: #00c300;}
							</style>
						</defs>
						<g>
							<path class="fill_1" d="M280.344,206.351 C280.344,206.351 280.354,206.351 280.354,206.351 C247.419,244.375 173.764,290.686 157.006,297.764 C140.251,304.844 142.724,293.258 143.409,289.286 C143.809,286.909 145.648,275.795 145.648,275.795 C146.179,271.773 146.725,265.543 145.139,261.573 C143.374,257.197 136.418,254.902 131.307,253.804 C55.860,243.805 0.004,190.897 0.004,127.748 C0.004,57.307 70.443,-0.006 157.006,-0.006 C243.579,-0.006 314.004,57.307 314.004,127.748 C314.004,155.946 303.108,181.342 280.344,206.351 Z"/>
							<path class="fill_2" d="M253.185,121.872 C257.722,121.872 261.408,125.569 261.408,130.129 C261.408,134.674 257.722,138.381 253.185,138.381
													C253.185,138.381 230.249,138.381 230.249,138.381 C230.249,138.381 230.249,153.146 230.249,153.146 C230.249,153.146 253.185,153.146 253.185,153.146 C257.710,153.146 261.408,156.851 261.408,161.398 C261.408,165.960 257.710,169.660 253.185,169.660 C253.185,169.660 222.018,169.660 222.018,169.660 C217.491,169.660 213.795,165.960 213.795,161.398 C213.795,161.398 213.795,130.149 213.795,130.149 C213.795,130.139 213.795,130.139 213.795,130.129 C213.795,130.129 213.795,130.114 213.795,130.109 C213.795,130.109 213.795,98.878 213.795,98.878 C213.795,98.858 213.795,98.850 213.795,98.841 C213.795,94.296 217.486,90.583 222.018,90.583 C222.018,90.583 253.185,90.583 253.185,90.583 C257.722,90.583 261.408,94.296 261.408,98.841 C261.408,103.398 257.722,107.103 253.185,107.103 C253.185,107.103 230.249,107.103 230.249,107.103 C230.249,107.103 230.249,121.872 230.249,121.872 C230.249,121.872 253.185,121.872 253.185,121.872 ZM202.759,161.398 C202.759,164.966 200.503,168.114 197.135,169.236 C196.291,169.521 195.405,169.660 194.526,169.660 C191.956,169.660 189.502,168.431 187.956,166.354 C187.956,166.354 156.012,122.705 156.012,122.705 C156.012,122.705 156.012,161.398 156.012,161.398 C156.012,165.960 152.329,169.660 147.791,169.660 C143.256,169.660 139.565,165.960 139.565,161.398 C139.565,161.398 139.565,98.841 139.565,98.841 C139.565,95.287 141.829,92.142 145.192,91.010 C146.036,90.730 146.915,90.583 147.799,90.583 C150.364,90.583 152.828,91.818 154.366,93.894 C154.366,93.894 186.310,137.559 186.310,137.559 C186.310,137.559 186.310,98.841 186.310,98.841 C186.310,94.296 190.000,90.583 194.536,90.583 C199.073,90.583 202.759,94.296 202.759,98.841 C202.759,98.841 202.759,161.398 202.759,161.398 ZM127.737,161.398 C127.737,165.960 124.051,169.660 119.519,169.660 C114.986,169.660 111.300,165.960 111.300,161.398 C111.300,161.398 111.300,98.841 111.300,98.841 C111.300,94.296 114.986,90.583 119.519,90.583 C124.051,90.583 127.737,94.296 127.737,98.841 C127.737,98.841 127.737,161.398 127.737,161.398 ZM95.507,169.660 C95.507,169.660 64.343,169.660 64.343,169.660 C59.816,169.660 56.127,165.960 56.127,161.398 C56.127,161.398 56.127,98.841 56.127,98.841 C56.127,94.296 59.816,90.583 64.343,90.583 C68.881,90.583 72.564,94.296 72.564,98.841 C72.564,98.841 72.564,153.146 72.564,153.146 C72.564,153.146 95.507,153.146 95.507,153.146 C100.047,153.146 103.728,156.851 103.728,161.398 C103.728,165.960 100.047,169.660 95.507,169.660 Z"/>
						</g>
					</svg>
					<p class="linettl">LINEで<br>
						相談する</p>
				</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col">
			<div class="blockmenu">
				<div class="area_a">
					<p class="title_bdr title item_ttl">アイテム</p>
					<div class="footer_item">
						<ul class="item_ul">
							<li><a href="/items/t-shirts/">Tシャツ</a></li>
							<li><a href="/items/polo-shirts/">ポロシャツ</a></li>
							<li><a href="/items/sweat/">スウェット</a></li>
							<li><a href="/items/outer/">ブルゾン</a></li>
							<li><a href="/items/long-shirts/">長袖Tシャツ</a></li>
							<li><a href="/items/towel/">タオル</a></li>
							<li><a href="/items/cap/">キャップ</a></li>
							<li><a href="/items/sportswear/">スポーツ</a></li>
						</ul>
						<ul class="item_ul">
							<li><a href="/items/apron/">エプロン</a></li>
							<li><a href="/items/tote-bag/">バッグ</a></li>
							<li><a href="/items/workwear/">ワークウェア</a></li>
							<li><a href="/items/ladys/">レディース</a></li>
							<li><a href="/items/overall/">つなぎ</a></li>
							<li><a href="/items/baby/">ベビー</a></li>
							<li><a href="/items/goods/">記念品</a></li>
						</ul>
					</div>
				</div>
				<div class="area_b">
					<p class="title_bdr title item_ttl_2">料金・納期</p>
					<div class="footer_print">
						<ul>
							<li><a href="/price/estimate.php">カンタン比較見積もり</a></li>
							<li><a href="/guide/">お支払い方法</a></li>
							<li><a href="/delivery/">お届け日を調べる</a></li>
							<li><a href="/order/express/">当日特急プラン</a></li>
							<li><a href="/price/fee/">プリント料金案内</a></li>
						</ul>
					</div>
				</div>
				<div class="area_b">
					<p class="title_bdr title item_ttl_2">デザイン・プリント</p>
					<div class="footer_print">
						<ul>
							<li><a href="/design/designguide.php">デザインの入稿・作り方</a></li>
							<li><a href="/design/designsimulator.php">デザインシミュレーター</a></li>
							<li><a href="/design/template_illust.php">イラレ入稿テンプレート</a></li>
							<li><a href="/design/support.php">デザインサポート</a></li>
							<li><a href="/design/designtemp.php">無料デザイン素材</a></li>
<!--								<li><a href="/design/gallery.php">製作実例</a></li>-->
							<li><a href="/print/">プリント方法</a></li>
							<li><a href="/campaign/towel/noshi.php">短納期粗品タオル</a></li>
						</ul>
					</div>
				</div>
				<div class="area_b">
					<p class="title_bdr title item_ttl_2">会社紹介</p>
					<div class="footer_com">
						<ul>
							<li><a href="/corporate/overview.php">会社概要</a></li>
							<li><a href="https://www.takahama428.com/blog/reservation/">来社予約</a></li>
							<li><a href="/userreviews/">お客様レビュー</a></li>
							<li><a href="/blog/">スタッフブログ</a></li>
							<li><a href="/blog/thanks-blog/">製作実例</a></li>
							<li><a href="/blog/topic/">プリント豆知識</a></li>
							<li><a href="/reason/speed.php">短納期の理由</a></li>
							<li><a href="/corporate/transactions.php">特定商取引法</a></li>
							<li><a href="/corporate/privacy-policy.php">プライバシーポリシー</a></li>
							<li><a href="/sitemap/">サイトマップ</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<?php
			$calendar = new package\Calendar();
			$nextDate = $calendar->getDate(1);
		?>
		<div class="area_c footer_tel_fax">
			<div class="workday_calendar_wrap">
				<p class="title item_ttl">営業カレンダー</p>
				<p><span class="min">月~金 10:00~18:00（土日祝休み）</span></p>
				<div class="blockcalendar">
					<table class="workday_calendar">
						<caption>
							<?php echo date('Y')." 年".date('n')." 月"; ?><span class="min org">&#9632;</span><span class="min">休業日</span>
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
						<tbody><?php echo $calendar->getHTML(); ?></tbody>
					</table>
					<table class="workday_calendar">
						<caption>
							<?php echo "{$nextDate['year']} 年{$nextDate['month']} 月"; ?><span class="min org">&#9632;</span><span class="min">休業日</span>
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
						<tbody><?php echo $calendar->getHTML(1); ?></tbody>
					</table>
				</div>
			</div>
		</div>

	</div>

	<nav class="footer_sp hidden-lg-up">
		<div class="col-10">
			<p class="f_follow">Follow me!!</p>
			<div class="foot_logo">
				<a href="//www.instagram.com/takahamalifeart/" class="btn-floating btn-large btn-ins" target="_blank"><i class="fa fa-instagram"></i></a>
				<a href="//ja-jp.facebook.com/takahamalifeart" class="btn-floating btn-large btn-fb" target="_blank"><i class="fa fa-facebook"></i></a>
				<a href="//twitter.com/takahamalifeart" class="btn-floating btn-large btn-tw" target="_blank"><i class="fa fa-twitter"></i></a>
				<a href="/contact/line/" class="btn-floating btn-large btn-line2" target="_blank">
					<svg xmlns="//www.w3.org/2000/svg" xmlns:xlink="//www.w3.org/1999/xlink" width="43px" height="57px" style="margin-left:8px;" viewBox="0 0 315 300">
						<defs>
							<style>
								.fill_1 {fill: #ffffff;}
								.fill_2 {fill: #00c300;}
								.line_logo1 {fill: #323333;}
							</style>
						</defs>
						<g>
							<path class="fill_1" d="M280.344,206.351 C280.344,206.351 280.354,206.351 280.354,206.351 C247.419,244.375 173.764,290.686 157.006,297.764 C140.251,304.844 142.724,293.258 143.409,289.286 C143.809,286.909 145.648,275.795 145.648,275.795 C146.179,271.773 146.725,265.543 145.139,261.573 C143.374,257.197 136.418,254.902 131.307,253.804 C55.860,243.805 0.004,190.897 0.004,127.748 C0.004,57.307 70.443,-0.006 157.006,-0.006 C243.579,-0.006 314.004,57.307 314.004,127.748 C314.004,155.946 303.108,181.342 280.344,206.351 Z"/>
							<path class="fill_2 line_logo1" d="M253.185,121.872 C257.722,121.872 261.408,125.569 261.408,130.129 C261.408,134.674 257.722,138.381 253.185,138.381
															   C253.185,138.381 230.249,138.381 230.249,138.381 C230.249,138.381 230.249,153.146 230.249,153.146 C230.249,153.146 253.185,153.146 253.185,153.146 C257.710,153.146 261.408,156.851 261.408,161.398 C261.408,165.960 257.710,169.660 253.185,169.660 C253.185,169.660 222.018,169.660 222.018,169.660 C217.491,169.660 213.795,165.960 213.795,161.398 C213.795,161.398 213.795,130.149 213.795,130.149 C213.795,130.139 213.795,130.139 213.795,130.129 C213.795,130.129 213.795,130.114 213.795,130.109 C213.795,130.109 213.795,98.878 213.795,98.878 C213.795,98.858 213.795,98.850 213.795,98.841 C213.795,94.296 217.486,90.583 222.018,90.583 C222.018,90.583 253.185,90.583 253.185,90.583 C257.722,90.583 261.408,94.296 261.408,98.841 C261.408,103.398 257.722,107.103 253.185,107.103 C253.185,107.103 230.249,107.103 230.249,107.103 C230.249,107.103 230.249,121.872 230.249,121.872 C230.249,121.872 253.185,121.872 253.185,121.872 ZM202.759,161.398 C202.759,164.966 200.503,168.114 197.135,169.236 C196.291,169.521 195.405,169.660 194.526,169.660 C191.956,169.660 189.502,168.431 187.956,166.354 C187.956,166.354 156.012,122.705 156.012,122.705 C156.012,122.705 156.012,161.398 156.012,161.398 C156.012,165.960 152.329,169.660 147.791,169.660 C143.256,169.660 139.565,165.960 139.565,161.398 C139.565,161.398 139.565,98.841 139.565,98.841 C139.565,95.287 141.829,92.142 145.192,91.010 C146.036,90.730 146.915,90.583 147.799,90.583 C150.364,90.583 152.828,91.818 154.366,93.894 C154.366,93.894 186.310,137.559 186.310,137.559 C186.310,137.559 186.310,98.841 186.310,98.841 C186.310,94.296 190.000,90.583 194.536,90.583 C199.073,90.583 202.759,94.296 202.759,98.841 C202.759,98.841 202.759,161.398 202.759,161.398 ZM127.737,161.398 C127.737,165.960 124.051,169.660 119.519,169.660 C114.986,169.660 111.300,165.960 111.300,161.398 C111.300,161.398 111.300,98.841 111.300,98.841 C111.300,94.296 114.986,90.583 119.519,90.583 C124.051,90.583 127.737,94.296 127.737,98.841 C127.737,98.841 127.737,161.398 127.737,161.398 ZM95.507,169.660 C95.507,169.660 64.343,169.660 64.343,169.660 C59.816,169.660 56.127,165.960 56.127,161.398 C56.127,161.398 56.127,98.841 56.127,98.841 C56.127,94.296 59.816,90.583 64.343,90.583 C68.881,90.583 72.564,94.296 72.564,98.841 C72.564,98.841 72.564,153.146 72.564,153.146 C72.564,153.146 95.507,153.146 95.507,153.146 C100.047,153.146 103.728,156.851 103.728,161.398 C103.728,165.960 100.047,169.660 95.507,169.660 Z"/>
						</g>
					</svg>
				</a>
			</div>
		</div>

		<div style="display:block;">
		<div class="col-10 foot_logo">
			<a href="/"><img alt="タカハマライフアート" src="/common/img/footer/footer_jota.jpg" width="100%"></a>
			<p>一般社団法人<br>日本オリジナルTシャツ協会会員</p>
		</div>
<!--
			<div class="tsuika_img">
				<a href="/blog/recruitment/" target="_blank" class="icon_wth"><img alt="求人募集" src="/common/img/footer/428_staff.jpg" width="100%"></a>
			</div>
-->
		<div class="col-10 foot_logo1">
			<a href="/"><img alt="タカハマライフアート" src="/common/img/footer/428logo.jpg" width="100%"></a>
			<p class="footer_happy">全スタッフとお客様の幸せを、実現します。</p>
		</div>
		</div>
	</nav>


<div class="call-to-action hidden-md-down">
	<div class="row">
	<div style="display:block;">
		<div class="foot_logo">
			<a href="http://www.jota.or.jp/" target="_blank" class="icon_wth"><img alt="日本オリジナルTシャツ協会のロゴ" src="/common/img/footer/footer_jota.jpg" width="100%"></a>
			<p>一般社団法人<br>日本オリジナルTシャツ協会会員</p>
		</div>
<!--
		<div class="tsuika_img">
			<a href="/blog/recruitment/" target="_blank" class="icon_wth"><img alt="求人募集" src="/common/img/footer/428_staff.jpg" width="100%"></a>
		</div>
-->
	</div>
	<!--追加画像掲載終了したら削除css-->
<!--
		<style>
			.tsuika_img {
				width: 300px;
				display: block;
				margin: 0 auto;
			}
			.tsuika_img:hover{
				opacity: .8;
			}
			@media screen and (max-width: 769px){
				.tsuika_img {
				margin: 30px auto;
				text-align: center;
			}
			}
			
			@media screen and (max-width: 991px){
				.footer_sp {
					height: auto;
			}
			}
		</style>
-->

		<div class="foot_logo1">
			<a href="/"><img alt="タカハマライフアート" src="/common/img/footer/428logo.jpg" width="100%"></a>
			<p class="footer_happy">全スタッフとお客様の幸せを、実現します。</p>
		</div>
		<div>
			<p class="footer_follow">Follow me!!</p>
			<div class="foot_logo">
				<a href="//www.instagram.com/takahamalifeart/" class="btn-floating btn-large btn-ins" target="_blank"><i class="fa fa-instagram"></i></a>
				<a href="//ja-jp.facebook.com/takahamalifeart" class="btn-floating btn-large btn-fb" target="_blank"><i class="fa fa-facebook"></i></a>
				<a href="//twitter.com/takahamalifeart" class="btn-floating btn-large btn-tw" target="_blank"><i class="fa fa-twitter"></i></a>
				<a href="/contact/line/" class="btn-floating btn-large btn-line2" target="_blank">
			<svg xmlns="//www.w3.org/2000/svg" xmlns:xlink="//www.w3.org/1999/xlink" width="43px" height="57px" style="margin-left: 8px;" viewBox="0 0 315 300">
				<defs>
					<style>
						.fill_1 {fill: #ffffff;}
						.fill_2 {fill: #00c300;}
						.line_logo1 {fill: #323333;}
					</style>
				</defs>
				<g>
					<path class="fill_1" d="M280.344,206.351 C280.344,206.351 280.354,206.351 280.354,206.351 C247.419,244.375 173.764,290.686 157.006,297.764 C140.251,304.844 142.724,293.258 143.409,289.286 C143.809,286.909 145.648,275.795 145.648,275.795 C146.179,271.773 146.725,265.543 145.139,261.573 C143.374,257.197 136.418,254.902 131.307,253.804 C55.860,243.805 0.004,190.897 0.004,127.748 C0.004,57.307 70.443,-0.006 157.006,-0.006 C243.579,-0.006 314.004,57.307 314.004,127.748 C314.004,155.946 303.108,181.342 280.344,206.351 Z"/>
					<path class="fill_2 line_logo1" d="M253.185,121.872 C257.722,121.872 261.408,125.569 261.408,130.129 C261.408,134.674 257.722,138.381 253.185,138.381
											C253.185,138.381 230.249,138.381 230.249,138.381 C230.249,138.381 230.249,153.146 230.249,153.146 C230.249,153.146 253.185,153.146 253.185,153.146 C257.710,153.146 261.408,156.851 261.408,161.398 C261.408,165.960 257.710,169.660 253.185,169.660 C253.185,169.660 222.018,169.660 222.018,169.660 C217.491,169.660 213.795,165.960 213.795,161.398 C213.795,161.398 213.795,130.149 213.795,130.149 C213.795,130.139 213.795,130.139 213.795,130.129 C213.795,130.129 213.795,130.114 213.795,130.109 C213.795,130.109 213.795,98.878 213.795,98.878 C213.795,98.858 213.795,98.850 213.795,98.841 C213.795,94.296 217.486,90.583 222.018,90.583 C222.018,90.583 253.185,90.583 253.185,90.583 C257.722,90.583 261.408,94.296 261.408,98.841 C261.408,103.398 257.722,107.103 253.185,107.103 C253.185,107.103 230.249,107.103 230.249,107.103 C230.249,107.103 230.249,121.872 230.249,121.872 C230.249,121.872 253.185,121.872 253.185,121.872 ZM202.759,161.398 C202.759,164.966 200.503,168.114 197.135,169.236 C196.291,169.521 195.405,169.660 194.526,169.660 C191.956,169.660 189.502,168.431 187.956,166.354 C187.956,166.354 156.012,122.705 156.012,122.705 C156.012,122.705 156.012,161.398 156.012,161.398 C156.012,165.960 152.329,169.660 147.791,169.660 C143.256,169.660 139.565,165.960 139.565,161.398 C139.565,161.398 139.565,98.841 139.565,98.841 C139.565,95.287 141.829,92.142 145.192,91.010 C146.036,90.730 146.915,90.583 147.799,90.583 C150.364,90.583 152.828,91.818 154.366,93.894 C154.366,93.894 186.310,137.559 186.310,137.559 C186.310,137.559 186.310,98.841 186.310,98.841 C186.310,94.296 190.000,90.583 194.536,90.583 C199.073,90.583 202.759,94.296 202.759,98.841 C202.759,98.841 202.759,161.398 202.759,161.398 ZM127.737,161.398 C127.737,165.960 124.051,169.660 119.519,169.660 C114.986,169.660 111.300,165.960 111.300,161.398 C111.300,161.398 111.300,98.841 111.300,98.841 C111.300,94.296 114.986,90.583 119.519,90.583 C124.051,90.583 127.737,94.296 127.737,98.841 C127.737,98.841 127.737,161.398 127.737,161.398 ZM95.507,169.660 C95.507,169.660 64.343,169.660 64.343,169.660 C59.816,169.660 56.127,165.960 56.127,161.398 C56.127,161.398 56.127,98.841 56.127,98.841 C56.127,94.296 59.816,90.583 64.343,90.583 C68.881,90.583 72.564,94.296 72.564,98.841 C72.564,98.841 72.564,153.146 72.564,153.146 C72.564,153.146 95.507,153.146 95.507,153.146 C100.047,153.146 103.728,156.851 103.728,161.398 C103.728,165.960 100.047,169.660 95.507,169.660 Z"/>
				</g>
			</svg>
		</a>
			</div>
		</div>
	</div>
	<!--<ul>
<li><a href="#!" class="icons-sm fb-ic"><i class="fa fa-facebook"></i> Facebook</a></li>
<li><a href="#!" class="icons-sm tw-ic"><i class="fa fa-twitter"></i> Twitter</a></li>
<li><a href="#!" class="icons-sm gplus-ic"><i class="fa fa-google-plus"></i> Google +</a></li>
<li><a href="#!" class="icons-sm ins-ic"><i class="fa fa-instagram"> </i> Instagram</a></li>
</ul>-->
</div>

<div class="footer-copyright">
	<div class="container-fluid">
		&copy;2001-2018 有限会社タカハマライフアート<br>【掲載の記事・写真・イラストなどの無断複写・転載等を禁じます。】
	</div>
</div>
