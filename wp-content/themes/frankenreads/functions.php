<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-child-stylesheet', get_stylesheet_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup', 16 );


/**
 * The Events Calendar - Remove Customizer CSS output
 * 
 * From https://gist.github.com/cliffordp/d70d30c6278ffab2ad1db579dd421421
 *
 * Related to this alternative: https://gist.github.com/cliffordp/13c7a2e3f91158d5c9d0fca075b121fb
 **/
function fr_remove_tec_customizer_style_output() {
	if ( class_exists( 'Tribe__Customizer' ) ) {
		remove_action( 'wp_print_footer_scripts', array( Tribe__Customizer::instance(), 'print_css_template' ), 15 );
	}
}
add_action( 'init', 'fr_remove_tec_customizer_style_output' );

function allow_subscriber_media( ) {
$role = 'subscriber';
if(!current_user_can($role) || current_user_can('upload_files'))
return;
$subscriber = get_role( $role );
$subscriber->add_cap('upload_files');
} 
add_action('admin_init', 'allow_subscriber_media');

/* Add Partner's Events to Profile */

function bpfr_get_post_on_profile() {
	
	$myposts = get_posts(  array(
	'posts_per_page' => -1,
	'author'         => bp_displayed_user_id(),
	'post_type'      => 'tribe_events'
	));
	
	if( ! empty($myposts) ) { 
		
		echo '<div class="partner-information"><h2>Partner Events</h2></div>'; 
		
		echo '<ul>';
		
		foreach($myposts as $post) {
			setup_postdata( $post );
				echo '<li><a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a></i>';
		}	
		
		echo '</ul>';
		
			wp_reset_postdata();
			
	} else { 
		
		echo '<div class="partner-information"><h2>Partner Events</h2></div>'; 
		
		echo '<p>This partner has not yet submitted an event.</p>';
	}
}
add_action ( 'bp_after_profile_field_content', 'bpfr_get_post_on_profile' );

/* End Add Partner's Events to Profile */


/*
function alphabetize_by_country_and_state( $bp_user_query ) {
    if ( 'alphabetical' == $bp_user_query->query_vars['type'] )
        $bp_user_query->uid_clauses['orderby'] = "ORDER BY substring_index(u.Country, ' ', -1)";
}
add_action ( 'bp_pre_user_query', 'alphabetize_by_country_and_state' );
*/
