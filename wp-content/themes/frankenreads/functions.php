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

/** Add custom sort to dropdown
 *
add_action( 'bp_members_directory_order_options', 'frankenreads_add_sorting_options' );
function frankenreads_add_sorting_options() { ?>
<option value="country">Country</option>
<?php
}

*/ 

/**
 * Sort Partners by Country
 *
**/

class BP_Custom_User_Ids {
 
    private $custom_ids = array();
 
    public function __construct() {
 
        $this->custom_ids = $this->get_custom_ids();
         
        add_action( 'bp_pre_user_query_construct',  array( $this, 'custom_members_query' ), 1, 1 );
        add_filter( 'bp_get_total_member_count',    array( $this, 'custom_members_count' ), 1, 1 );
     
    }
     
    private function get_custom_ids() {
        global $wpdb;
 
        // collection based on an xprofile field
        $custom_ids = $wpdb->get_col("SELECT user_id FROM {$wpdb->prefix}bp_xprofile_data  WHERE field_id = 11 ORDER BY value ASC");
 
        return $custom_ids;
        
    }   
     
    function custom_members_query( $query_array ) {
 
        $query_array->query_vars['user_ids'] = $this->custom_ids;
                 
        //in case there are other items like widgets using the members loop on the members page
        remove_action( 'bp_pre_user_query_construct', array( $this, 'custom_members_query' ), 1, 1 );
 
    }   
     
    function custom_members_count ( $count ) {
 
        $new_count = count( $this->custom_ids );
        return $count - $new_count; 
 
    }
}
 
function custom_user_ids( ) { 
 
    new BP_Custom_User_Ids ();
 
}
add_action( 'bp_before_directory_members', 'custom_user_ids' );









