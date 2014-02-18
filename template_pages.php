<?php
add_action("template_redirect", 'my_theme_redirect');

function my_theme_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );

    //A Specific Custom Post Type
    if( get_queried_object()->post_type == 'hotel') {
        $templatefilename = 'single-hotel.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/templates/' . $templatefilename;
        }
        do_theme_redirect($return_template);

    //A Custom Taxonomy Page
    } elseif ($wp->query_vars["taxonomy"] == 'hotel_category') {
        $templatefilename = 'taxonomy-hotel_category.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/templates/' . $templatefilename;
        }
        do_theme_redirect($return_template);

    //A Simple Page
    /*
    } elseif ($wp->query_vars["pagename"] == 'somepagename') {
        $templatefilename = 'page-somepagename.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        do_theme_redirect($return_template);
    */
    }
    
}

function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}