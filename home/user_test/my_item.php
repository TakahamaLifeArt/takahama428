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
		<meta name="Description" content="早い！Tシャツでオリジナルを作成するならタカハマへ！タカハマライフアートのログイン画面です。メールアドレスとパスワードを入れてください。マイページからご注文履歴などをご確認することができます。ログインにする為のパスワードをお忘れの方はこちら。">
		<meta name="keywords" content="オリジナル,tシャツ,メンバー">
		<meta property="og:title" content="世界最速！？オリジナルTシャツを当日仕上げ！！" />
		<meta property="og:type" content="article" />
		<meta property="og:description" content="業界No. 1短納期でオリジナルTシャツを1枚から作成します。通常でも3日で仕上げます。" />
		<meta property="og:url" content="https://www.takahama428.com/" />
		<meta property="og:site_name" content="オリジナルTシャツ屋｜タカハマライフアート" />
		<meta property="og:image" content="https://www.takahama428.com/common/img/header/Facebook_main.png" />
		<meta property="fb:app_id" content="1605142019732010" />
		<title>追加・再注文フォーム- TLAメンバーズ | タカハマライフアート</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
		<link rel="stylesheet" type="text/css" media="screen" href="./css/common.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/my_reorder.css" />
		<style>
			.lightbox {
				display: none;
			}

		</style>

	</head>

	<body>
		<header>
			<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
		</header>

		<div id="container">
			<div class="contents">

				<div class="toolbar">
					<div class="toolbar_inner">
						<div class="pagetitle">
							<h1>追加・再注文フォーム</h1>
						</div>
					</div>
				</div>

				<section>
					<h2><strong><?php echo $category_name;?></strong></h2>
					<div class="search_box">
						<!-- Button trigger modal -->
						<button class="btn search_btn" id="modal_search">
							<i class="fa fa-search" aria-hidden="true"></i>絞り込む
						</button>
						<p class="display_res">なし</p>
					</div>
					<div class="item_cond">
						<select id="" class="down_cond">
							<option value="" selected="selected" rel="">価格の低い順</option>
							<option value="" selected="selected" rel="">価格の高い順</option>
							<option value="" selected="selected" rel="">レビューが多い順</option>
							<option value="" selected="selected" rel="">人気順</option>
						</select>
						<p class="txt_min">表示件数：<span class="number"><?php echo mb_convert_kana(count($res),'A'); ?></span> アイテム</p>
					</div>



					<ul class="listitems_top3 row">
						<li class="listitems col-12 col-sm-4">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s"><img class="rankno" src="../img/index/no1.png" width="60" height="34" alt="No1">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/085-cvt/085-cvt_for-express.jpg" width="90%" height="auto" alt="085-CVT">
									</li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">定番品。業界売上No1</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">085-CVT</li>
											<li class="item_name_name">5.6オンスヘビーウエイト Ｔシャツ</li>
											<li class="item_price"><span>500</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>50</span>色</li>
											<li class="item_size">
												<spn>16</spn>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p id="item_info">レビューを見る（349件）</p>
							</div>
						</li>

						<li class="listitems col-12 col-sm-4">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s"><img class="rankno" src="../img/index/no2.png" width="60" height="34" alt="No2">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/300-act/300-act_165.jpg" width="90%" height="auto" alt="300-ACT">
									</li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">ドライ素材定番No.１</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">300-ACT</li>
											<li class="item_name_name">4.4オンスドライＴシャツ</li>
											<li class="item_price"><span>450</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>33</span>色</li>
											<li class="item_size"><span>12</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（89件）</a>
								</p>
							</div>
						</li>

						<li class="listitems col-12 col-sm-4">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s"><img class="rankno" src="../img/index/no3.png" width="60" height="34" alt="No3">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/5806/5806_083.jpg" width="90%" height="auto" alt="5806">
									</li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">安くて丈夫な縫製</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">5806</li>
											<li class="item_name_name">4.0オンスプロモーションTシャツ</li>
											<li class="item_price"><span>360</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>21</span>色</li>
											<li class="item_size"><span>6</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（73件）</a>
								</p>
							</div>
						</li>
					</ul>



					<ul class="listitems_other row">
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/63000/63000_cg10c.jpg" width="90%" height="auto" alt="63000">
									</li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">コスパが魅力の新定番！</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">63000</li>
											<li class="item_name_name">ジャパンフィット 4.5oz アダルトTシャツ</li>
											<li class="item_price"><span>370</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>20</span>色</li>
											<li class="item_size"><span>6</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/63000/63000_cg10c.jpg" width="90%" height="auto" alt="63000">
									</li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">コスパが魅力の新定番！</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">63000</li>
											<li class="item_name_name">ジャパンフィット 4.5oz アダルトTシャツ</li>
											<li class="item_price"><span>370</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>20</span>色</li>
											<li class="item_size"><span>6</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>
						<li class="listitems col-12 col-sm-3">
							<ul class="item_in row">
								<div class="item_img_wrap col-6 col-sm-12">
									<li class="item_image_s">
										<img class="item_pic" src="https://takahamalifeart.com/weblib/img/items/list/t-shirts/dm-030/dm-030_133.jpg" width="90%" height="auto" alt="DM-030"> </li>
								</div>
								<div class="item_info_wrap col-6 col-sm-12">
									<li class="point_s">新定番！柔らか生地</li>
									<li class="item_name_s">
										<ul>
											<li class="item_name_kata">DM-030</li>
											<li class="item_name_name">5.0オンス STANDARD T-SHIRTS</li>
											<li class="item_price"><span>490</span>円～</li>
										</ul>
									</li>
									<li class="item_info_cs">
										<ul>
											<li class="item_color"><span>40</span>色</li>
											<li class="item_size"><span>16</span>サイズ</li>
										</ul>
									</li>
								</div>
							</ul>
							<div class="item_review">
								<p>
									<a href="/itemreviews/?item=4">レビューを見る（2件）</a>
								</p>
							</div>
						</li>

					</ul>

				</section>


				<div class="transition_wrap align-items-center">
					<div class="step_prev hoverable waves-effect">
						<i class="fa fa-chevron-left"></i>戻る
					</div>
				</div>
			</div>

					</div>

				</section>

				<div class="transition_wrap d-flex justify-content-between align-items-center">
					<div class="step_prev hoverable waves-effect">
						<i class="fa fa-chevron-left mr-1"></i>戻る
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
		<script src="//ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script type="text/javascript" src="./js/account.js"></script>
		<script src="./js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	</body>

	</html>
