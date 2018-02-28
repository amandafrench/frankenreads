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

/* Add partner org and country counts */

function bpfr_unique_org_count() {
			global $wpdb; 
			$unique_orgs = $wpdb->get_row("SELECT COUNT(DISTINCT value) FROM `wp_bp_xprofile_data` WHERE `field_id` = 8");
			foreach ( $unique_orgs as $o )
			echo "<br />Total organizations: "; print_r($o); echo " ";			
}
add_action( 'before-members-loop', 'bpfr_unique_org_org_count' );

function bpfr_unique_country_count() {
			global $wpdb; 
			$unique_countries = $wpdb->get_row("SELECT COUNT(DISTINCT value) FROM `wp_bp_xprofile_data` WHERE `field_id` = 11");
			foreach ( $unique_countries as $c )
			echo "<br />Total countries: "; print_r($c); echo " ";												
}			
add_action( 'before-members-loop', 'bpfr_unique_country_count' );
			
/* 	End add partner org and country counts */
		
/* Add total number of Events to Events page */ 

function fr_tribe_events_count() {

	global $wpdb;
	$count_events = wp_count_posts( 'tribe_events' );
	$published_events = $count_events->publish;
	echo "<p class=\"eventcount\">Total approved events: "; print_r($published_events);
	echo "</p>";
}

add_action( 'tribe_events_bar_before_template', 'fr_tribe_events_count' );

/* End add total number of Events to Events page */ 






