<?php
/**
 * @package vt-grid-mag
 * @since 1.0
 */
?>

		</div><!-- .container #main -->
	</main><!-- #main -->
	
<!--
	<footer id="colophon" class="site-footer clearfix" role="contentinfo">
		<div class="container">
			<div class="copyright clearfix">
				<?php do_action( 'vt_grid_mag_footer_copyright' ); ?>
			</div>
			<?php
				// Social Icons.
				vt_grid_mag_tags_social_links( '<div class="footer-social clearfix">', '</div><!-- .footer-social -->' );
			?>
		</div>
	</footer>
-->
<footer class="page-footer">
	<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/footer.php"; ?>
</footer>

<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/util.php"; ?>

<div id="overlay-mask" class="fade"></div>

<?php include $_SERVER['DOCUMENT_ROOT']."/common/inc/js.php"; ?>
	
	<a href="#" class="menu-toggle"><span class="fa fa-bars"></span></a>
	
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>