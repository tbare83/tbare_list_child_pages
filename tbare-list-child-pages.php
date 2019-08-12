<?php
/*
Plugin Name: List Child Pages
Plugin URI: http://www.tbare.com/
Description: Creates list with links of pages that are child pages of the current page 
Author: Tim Bare
Version: 1.0.0
Author URI: https://www.tbare.com/
*/

add_shortcode( 'tbarelistchildpages', 'tbarelistchildpages_handler' );

function tbarelistchildpages_handler($atts, $content = null) {
	global $post;
	
	$values = shortcode_atts( array(
        'category' => '',
        'columns'	=> '1',
    ), $atts );
	
	$args = array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
		'post_parent'    => $post->ID,
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'taxonomy' => 'category',
                       'field' => 'slug',
                       'term' => esc_attr($values['category'])
	 );
		 
	$parent = new WP_Query($args);
	
	$return = '';
	if ($parent->have_posts()) {
		if(esc_attr($values['columns']) <> '1') {
		$width = 100 / esc_attr($values['columns']);
		$return .= '<style>
		
	#tbare-child-pages {

	}
	#tbare-child-pages ul {
		list-style: none;
		-webkit-column-count: '.esc_attr($values['columns']).';
		-moz-column-count: '.esc_attr($values['columns']).';
		column-count: '.esc_attr($values['columns']).'; 
		-webkit-column-gap: 20px;
		-moz-column-gap: 20px;
		-o-column-gap: 20px;
		-ms-column-gap: 20px;
		column-gap: 20px;
  		-webkit-column-width: 150px;
     	-moz-column-width: 150px;
        column-width: 150px;
		padding: 0;
	}
	#tbare-child-pages ul li {
		margin-bottom: 5px;
	}
	#tbare-child-pages ul li a {
		padding: 5px;
		display: block;
	}
	</style>';
		}
		
		
		$return .= '<div id="tbare-child-pages">';
		$return .= ' <ul>';
		while ($parent->have_posts()) { 
			$parent->the_post(); 
			$return .= '  <li><a href="'.get_permalink().'">'.$post->post_title.'</a></li>';
		}
	unset($parent); 
	$return .= ' </ul>';
	$return .= '</div>';
	
	}
	wp_reset_postdata();
	
	return $return;
	
}