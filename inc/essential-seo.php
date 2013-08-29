<?php
/**
 * SEO and header functions.  Not all things in this file are strictly for search engine optimization.  Many 
 * of the functions handle basic <meta> elements for the <head> area of the site.  This file is a catchall file 
 * for adding these types of things to themes.
 *
 *
 * @package    EssentialSEO
 * @subpackage Inc
 * @author     James Geiger <james@seamlessthemes.com>
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2013, James Geiger and Justin Tadlock
 * @link       http://seamlessthemes.com
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Add <meta> elements to the <head> area. */
add_action( 'wp_head', 'essential_seo_start', 1 );
add_action( 'wp_head', 'rel_canonical', 1);
add_action( 'wp_head', 'essential_seo_author', 1 );
add_action( 'wp_head', 'essential_seo_meta_robots', 1 );
add_action( 'wp_head', 'essential_seo_meta_description', 1 );
add_action( 'wp_head', 'essential_seo_end', 1 );

remove_action('wp_head', 'rel_canonical');

function extra_contact_info($contactmethods) {

	$contactmethods['googleplus'] = 'Google+';


	return $contactmethods;

}

add_filter('user_contactmethods', 'extra_contact_info');


function essential_seo_start() {

	echo "\n" . '<!-- Start Essential SEO -->' . "\n";
}

function essential_seo_author() {
	$gplus = '';

	if ( is_home() || is_front_page() ) {
		$gplus = get_the_author_meta( 'googleplus');

	}
	else if ( is_singular() ) {
		global $post;
		$gplus = get_the_author_meta( 'googleplus', $post->post_author );
	}

	$gplus = apply_filters( 'author', $gplus );

	if ( $gplus )
		$gplus = '<link rel="author" href="' . $gplus . '"/>' . "\n";

	echo apply_filters ( 'author', $gplus );

}

/**
 * Sets the default meta robots setting.  If private, don't send meta info to the header.  Runs the 
 * essential_seo_meta_robots filter hook at the end.
 *
 * @since 0.1.0
 * @access public
 * @return void
 */
function essential_seo_meta_robots() {

	/* Do not display index and follow tags on the following. It is the browser default. */
	if ((is_home() && ($paged < 2 )) || is_front_page() || is_single() || is_page() || is_attachment()) {
		return;
	}

	/* If viewing a search page, display noindex and nofollow. */
	elseif ( is_search() ) {
		$robots = '<meta name="robots" content="noindex,nofollow" />' . "\n";

	/* If viewing any other page display noidex and follow. */
	} else {
		$robots = '<meta name="robots" content="noindex,follow" />' . "\n";
	}

	echo apply_filters ( 'meta_robots', $robots );
}

/**
 * Generates the meta description based on either metadata or the description for the object.
 *
 * @since 0.1.0
 * @access public
 * @return void
 */
function essential_seo_meta_description() {

	/* Set an empty $description variable. */
	$description = '';

	/* If viewing the home/posts page, get the site's description. */
	if ( is_home() ) {
		$description = get_bloginfo( 'description' );
	}

	/* If viewing a singular post. */
	elseif ( is_singular() ) {

		/* Get the meta value for the 'Description' meta key. */
		$description = get_post_meta( get_queried_object_id(), 'Description', true );

		/* If no description was found and viewing the site's front page, use the site's description. */
		if ( empty( $description ) && is_front_page() )
			$description = get_bloginfo( 'description' );

		/* For all other singular views, get the post excerpt. */
		elseif ( empty( $description ) )
			$description = get_post_field( 'post_excerpt', get_queried_object_id() );
	}

	/* If viewing an archive page. */
	elseif ( is_archive() ) {

		/* If viewing a user/author archive. */
		if ( is_author() ) {

			/* Get the meta value for the 'Description' user meta key. */
			$description = get_user_meta( get_query_var( 'author' ), 'Description', true );

			/* If no description was found, get the user's description (biographical info). */
			if ( empty( $description ) )
				$description = get_the_author_meta( 'description', get_query_var( 'author' ) );
		}

		/* If viewing a taxonomy term archive, get the term's description. */
		elseif ( is_category() || is_tag() || is_tax() )
			$description = term_description( '', get_query_var( 'taxonomy' ) );

		/* If viewing a custom post type archive. */
		elseif ( is_post_type_archive() ) {

			/* Get the post type object. */
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );

			/* If a description was set for the post type, use it. */
			if ( isset( $post_type->description ) )
				$description = $post_type->description;
		}
	}

	/* Format the meta description. */
	if ( !empty( $description ) )
		$description = '<meta name="description" content="' . str_replace( array( "\r", "\n", "\t" ), '', esc_attr( strip_tags( $description ) ) ) . '" />' . "\n";

	echo apply_filters ( 'meta_description', $description );
}

function essential_seo_end() {

	echo '<!-- End Essential SEO -->' . "\n\n";
}

?>