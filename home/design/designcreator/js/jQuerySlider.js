/*

	----------------------------------------------------------------------------------------------------
	Accessible News Slider
	----------------------------------------------------------------------------------------------------
	
	Author:
	Brian Reindel
	
	Author URL:
	http://blog.reindel.com

	License:
	Unrestricted. This script is free for both personal and commercial use.

*/

jQuery.fn.accessNews = function( settings ) {
	settings = jQuery.extend({
        headline : "Top Stories",
        speed : "normal",
		slideBy : 2
    }, settings);
    return this.each(function() {
		jQuery.fn.accessNews.run( jQuery( this ), settings );
    });
};
jQuery.fn.accessNews.run = function( $this, settings ) {
	var ul = jQuery( "ul:eq(0)", $this );
	var li = ul.children();
	if ( li.length > settings.slideBy ) {
		var $next = jQuery( ".next_slider", $this );
		var $back = jQuery( ".back_slider", $this );
		var liWidth = jQuery( li[0] ).width()+4;	/* 4 is border 2px + margin 2px */
		var animating = false;
		ul.css( "width", ( li.length * liWidth ) );
		$next.click(function() {
			if ( !animating ) {
				animating = true;
				offsetLeft = parseInt( ul.css( "left" ) ) - ( liWidth * settings.slideBy );
				if ( offsetLeft + ul.width() > 0 ) {
					$back.css( "display", "block" );
					ul.animate({
						left: offsetLeft
					}, settings.speed, function() {
						if ( parseInt( ul.css( "left" ) ) + ul.width() <= liWidth * settings.slideBy ) {
							$next.css( "display", "none" );
						}
						animating = false;
					});
				} else {
					animating = false;
				}
			}
			return false;
		});
		$back.click(function() {
			if ( !animating ) {
				animating = true;
				offsetRight = parseInt( ul.css( "left" ) ) + ( liWidth * settings.slideBy );
				if ( offsetRight + ul.width() <= ul.width() ) {
					$next.css( "display", "block" );
					ul.animate({
						left: offsetRight
					}, settings.speed, function() {
						if ( parseInt( ul.css( "left" ) ) == 0 ) {
							$back.css( "display", "none" );
						}
						animating = false;
					});
				} else {
					animating = false;
				}
			}
			return false;
		});
		$back.css( "display", "none" );
			
	}
};
