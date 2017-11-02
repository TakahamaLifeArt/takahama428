<?php
/**
 * @package vt-grid-mag
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// Post Format Preview.
		vt_grid_mag_tags_post_preview();
	?>

	<header class="entry-header clearfix">
		<?php
			// Post title.
			if( is_singular() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			}
			else {
				the_title( '<h2 class="entry-title"><a href="'. esc_url( get_permalink() ) .'" rel="bookmark">', '</a></h2>' );
			}
		?>

	</header><!-- .entry-header -->
						
	<div class="entry-content clearfix">
		<?php
			// Content.
			the_content();
			wp_link_pages( array( 'before' => '<div class="page-links">', 'after' => '</div><!-- .page-links -->') );

			// Taxonomies.
			if( is_singular() ) {
				// Categories.
				$cats = get_the_category_list( ', ' );
				if( $cats && ! vt_grid_mag('hide_post_cats') ) {
					echo '<div class="cat-links taxonomy-wrap"><span class="taxonomy-wrap-title">'. esc_html__( 'Posted In: ', 'vt-grid-mag' ) .'</span>'. $cats .'</div><!-- .cat-links -->';
				}

				// Post Tags.
				if( ! vt_grid_mag('hide_post_tags') ) {
					the_tags( '<div class="tag-links taxonomy-wrap"><span class="taxonomy-wrap-title">'. esc_html__( 'Tagged In: ', 'vt-grid-mag' ) .'</span>', ', ', '</div><!-- .tag-links -->' );
				}
			}
		?>

	</div><!-- .entry-content -->
						
	<div id="footer" class="entry-footer clearfix">
		<?php
			// Comments Link.
			if( ! vt_grid_mag('hide_post_comments') && ( comments_open() || get_comments_number() ) ) {
				printf( '<div class="comments-link-meta">%s</div>'
					, sprintf( '<a href="%1$s"><i class="fa fa-comments" aria-hidden="true"></i>%2$s</a>'
						, esc_url( get_comments_link() )
						, get_comments_number()
					)
				);
			}

			// Post Date.
			if( ! vt_grid_mag('hide_post_date') ) {
				printf( '<div class="date-meta">%s</div>' 
					, sprintf( _x( '%s ago', '%s = human-readable time difference', 'vt-grid-mag' )
						, human_time_diff( get_the_time( 'U' )
						, current_time( 'timestamp' ) ) 
					)
				);
			}
		?>

	</div>
	<!-- .entry-footer -->
</article>
<!-- #post-## -->