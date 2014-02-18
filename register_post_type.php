<?php
/***
* Special Thanks To Devin Price
* This file is a modified of the original plugin found @https://github.com/devinsays/portfolio-post-type - Special Thanks!
***/

if ( ! class_exists( 'GF_Hotel_Post_Type' ) ) :
class GF_Hotel_Post_Type {

	// Current plugin version
	var $version = 1;

	function __construct() {

		// Runs when the plugin is activated
		register_activation_hook( __FILE__, array( &$this, 'plugin_activation' ) );

		// Add support for translations
		load_plugin_textdomain( 'symple', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		// Adds the Hotels post type and taxonomies
		add_action( 'init', array( &$this, 'hotel_init' ) );

		// Thumbnail support for hotels posts
		add_theme_support( 'post-thumbnails', array( 'hotel' ) );

		// Adds columns in the admin view for thumbnail and taxonomies
		add_filter( 'manage_edit-hotel_columns', array( &$this, 'hotel_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( &$this, 'hotel_column_display' ), 10, 2 );

		// Allows filtering of posts by taxonomy in the admin view
		add_action( 'restrict_manage_posts', array( &$this, 'hotel_add_taxonomy_filters' ) );
		
		// Customize messaging
		add_filter( 'post_updated_messages', array( &$this, 'hotel_updated_messages' ) );
	}

	/**
	 * Flushes rewrite rules on plugin activation to ensure hotels posts don't 404
	 * http://codex.wordpress.org/Function_Reference/flush_rewrite_rules
	 */

	function plugin_activation() {
		$this->hotel_init();
		flush_rewrite_rules();
	}

	function hotel_init() {

		/**
		 * Enable the hotel custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */

		$labels = array(
			'name' => __( 'Hotels', 'symple' ),
			'singular_name' => __( 'Hotel Item', 'symple' ),
			'add_new' => __( 'Add New Item', 'symple' ),
			'add_new_item' => __( 'Add New Hotel Item', 'symple' ),
			'edit_item' => __( 'Edit Hotel Item', 'symple' ),
			'new_item' => __( 'Add New Hotel Item', 'symple' ),
			'view_item' => __( 'View Item', 'symple' ),
			'search_items' => __( 'Search Hotel', 'symple' ),
			'not_found' => __( 'No Hotel items found', 'symple' ),
			'not_found_in_trash' => __( 'No Hotel items found in trash', 'symple' )
		);
		
		$args = array(
	    	'labels' => $labels,
	    	'public' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ), // You can add 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'revisions'
			'capability_type' => 'post',
			'rewrite' => array("slug" => "hotel"), // Permalinks format
			'has_archive' => true,
			'menu_icon'=> 'dashicons-location', // View Dashicons: http://melchoyce.github.io/dashicons/
		); 
		
		$args = apply_filters('symple_testimnials_args', $args);
		
		register_post_type( 'hotel', $args );
		
		/**
		 * Register a taxonomy for Hotels Categories
		 * http://codex.wordpress.org/Function_Reference/register_taxonomy
		 */

	    $taxonomy_hotel_category_labels = array(
			'name' => _x( 'Hotel Categories', 'symple' ),
			'singular_name' => _x( 'Hotel Category', 'symple' ),
			'search_items' => _x( 'Search Hotel Categories', 'symple' ),
			'popular_items' => _x( 'Popular Hotel Categories', 'symple' ),
			'all_items' => _x( 'All Hotel Categories', 'symple' ),
			'parent_item' => _x( 'Parent Hotel Category', 'symple' ),
			'parent_item_colon' => _x( 'Parent Hotel Category:', 'symple' ),
			'edit_item' => _x( 'Edit Hotel Category', 'symple' ),
			'update_item' => _x( 'Update Hotel Category', 'symple' ),
			'add_new_item' => _x( 'Add New Hotel Category', 'symple' ),
			'new_item_name' => _x( 'New Hotel Category Name', 'symple' ),
			'separate_items_with_commas' => _x( 'Separate Hotel categories with commas', 'symple' ),
			'add_or_remove_items' => _x( 'Add or remove Hotel categories', 'symple' ),
			'choose_from_most_used' => _x( 'Choose from the most used Hotel categories', 'symple' ),
			'menu_name' => _x( 'Hotel Categories', 'symple' ),
	    );

	    $taxonomy_hotel_category_args = array(
			'labels' => $taxonomy_hotel_category_labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'hotels-category' ),
			'query_var' => true
	    );

		$taxonomy_hotel_category_args = apply_filters('symple_taxonomy_hotel_category_args', $taxonomy_hotel_category_args);
		
	    register_taxonomy( 'hotel_category', array( 'hotel' ), $taxonomy_hotel_category_args );

	}

	/**
	 * Add Columns to Hotel Edit Screen
	 * http://wptheming.com/2010/07/column-edit-pages/
	 */

	function hotel_edit_columns( $hotel_columns ) {
		$hotel_columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => _x('Name', 'column name'),
			"hotels_logo" => __('Logo', 'symple'),
			"hotels_thumbnail" => __('Featured Image', 'symple'),
			"hotel_category" => __('Category', 'symple'),
		);
		return $hotel_columns;
	}

	function hotel_column_display( $hotel_columns, $post_id ) {

		// Code from: http://wpengineer.com/display-post-thumbnail-post-page-overview

		switch ( $hotel_columns ) {

			// Display the thumbnail in the column view
			case "hotels_logo":
			
				$attachment_id = get_field('logo');
				$size = "logo";
				$image = wp_get_attachment_image_src( $attachment_id, $size );	
				
				if ( isset( $image ) ) {
					echo '<img src="' .$image[0]. '" width="'.$image[1].'" height="'.$image[2].'" />';
				} else {
					echo __('None', 'symple');
				}
				break;
				
			case "hotels_thumbnail":
				$width = (int) 80;
				$height = (int) 80;
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );

				// Display the featured image in the column view if possible
				if ( $thumbnail_id ) {
					$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				}
				if ( isset( $thumb ) ) {
					echo $thumb;
				} else {
					echo __('None', 'symple');
				}
				break;	

			// Display the Hotels categories in the column view
			case "hotel_category":

			if ( $category_list = get_the_term_list( $post_id, 'hotel_category', '', ', ', '' ) ) {
				echo $category_list;
			} else {
				echo __('None', 'symple');
			}
			break;	
		
		}
	}

	/**
	 * Adds taxonomy filters to the Hotels admin page
	 * Code artfully lifed from http://pippinsplugins.com
	 */

	function hotel_add_taxonomy_filters() {
		global $typenow;

		// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
		$taxonomies = array( 'hotel_category' );

		// must set this to the post type you want the filter(s) displayed on
		if ( $typenow == 'hotel' ) {

			foreach ( $taxonomies as $tax_slug ) {
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}
		 
		/*
		 * Customize Messeging
		 */

		 function hotel_updated_messages( $messages ) {
			 global $post, $post_ID;

			 $messages['Hotel'] = array(
			   0 => '', // Unused. Messages start at index 1.
			   1 => sprintf( __('Hotel updated. <a href="%s">View Hotel</a>', 'your_text_domain'), esc_url( get_permalink($post_ID) ) ),
			   2 => __('Custom field updated.', 'your_text_domain'),
			   3 => __('Custom field deleted.', 'your_text_domain'),
			   4 => __('Hotel updated.', 'your_text_domain'),
			   /* translators: %s: date and time of the revision */
			   5 => isset($_GET['revision']) ? sprintf( __('Hotel restored to revision from %s', 'your_text_domain'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			   6 => sprintf( __('Hotel published. <a href="%s">View Hotel</a>', 'your_text_domain'), esc_url( get_permalink($post_ID) ) ),
			   7 => __('Hotel saved.', 'your_text_domain'),
			   8 => sprintf( __('Hotel submitted. <a target="_blank" href="%s">Preview Hotel</a>', 'your_text_domain'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			   9 => sprintf( __('Hotel scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Hotel</a>', 'your_text_domain'),
			     // translators: Publish box date format, see http://php.net/date
			     date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			   10 => sprintf( __('Hotel draft updated. <a target="_blank" href="%s">Preview Hotel</a>', 'your_text_domain'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			 );
			 
			 return $messages;
			 }
}



new GF_Hotel_Post_Type;

endif;