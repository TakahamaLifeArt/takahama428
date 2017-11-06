<?php
/**
 * @package vt-grid-mag
 * @since 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/css.php"; ?>
	<?php wp_head(); ?>
	<style>

		#page {
			padding-top: 90px;
		}
		
		.site-navigation {
			top: 90px;
		}

	</style>
</head>

<body <?php body_class(); ?>>
	<header>
		<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/header.php"; ?>
	</header>
<div id="page" class="hfeed site">

<!--
	<div id="header">

	<div id="masthead">
		<div id="top_bar">
			<div class="inner">

			</div>
		</div>
		<div class="inner">
			<div class="h_wrap hw_1"><a href="//www.takahama428.com/"><img src="//www.takahama428.com/app/WP/wp-content/uploads/2015/12/logo1.png" alt="オリジナルTシャツ屋"></a></div>
				<div class="h_wrap hw_2"><img src="//www.takahama428.com/app/WP/wp-content/uploads/2015/12/no1_mark.png" alt="業界NO.1！スピード仕上げ 親切対応！"></div>

				<div class="h_wrap hw_3">
					<div class="h_tel">
						<a href="/contact/guide/">
							<p class="p1">お急ぎの方は<br>お電話下さい！</p>
							<p class="p2"></p>
							<p class="p3"><img src="//www.takahama428.com/app/WP/wp-content/uploads/2015/12/tel.png" alt="電話"></p>
							<p class="p4">TEL</p>
							<p class="p5">0120-130-428</p>
							<p class="p6">受付時間：平日 10:00-18:00</p>
							<p class="p7"><img src="//www.takahama428.com/app/WP/wp-content/uploads/2015/12/arrow_b.png"></p>
						</a>
					</div>

					<div class="h_mail">
						<a href="/contact/">
							<img src="//www.takahama428.com/app/WP/wp-content/uploads/2015/12/mail.png" alt="メール">
							<span>MAIL</span>
							<p>お問い合わせ（相談）</p>
							<img src="//www.takahama428.com/app/WP/wp-content/uploads/2015/12/arrow_w.png" class="h_arrow">
						</a>
					</div>
				</div>
				<div class="gro">
					<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/globalmenu_wp.php"; ?>
				</div>
			</div>
		</div>
-->

	<nav id="navigation" class="site-navigation clearfix" role="navigation">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => '' ) ); ?>
<!--
		<aside id="search-2" class="widget clearfix widget_search top_serch"><form role="search" method="get" class="search-form" action="https://www.takahama428.com/app/WP/design-templates/">
			<label>
				<span class="screen-reader-text">検索:</span>
				<input type="search" class="search-field" placeholder="検索して Enter &hellip;" value="" name="s" />
			</label>
			<input type="submit" class="search-submit" value="検索" />
			</form></aside>
-->
	</nav>
	
	<main id="main" class="site-main clearfix">
		<div class="container">
      