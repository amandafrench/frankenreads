<?php
/*
Plugin Name: WD Instagram Feed
Plugin URI: https://web-dorado.com/products/wordpress-instagram-feed-wd.html
Description: WD Instagram Feed is a user-friendly tool for displaying user or hashtag-based feeds on your website. You can create feeds with one of the available layouts. It allows displaying image metadata, open up images in lightbox, download them and even share in social networking websites.
Version: 1.3.2
Author: WebDorado
Author URI: https://web-dorado.com/wordpress-plugins-bundle.html
License: GPLv2 or later
Text Domain: wd-instagram-feed
*/

//define constants
define('WDI_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WDI_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define("WDI_VAR", "wdi_instagram");
define("WDI_OPT",WDI_VAR.'_options');
define("WDI_FSN",'wdi_feed_settings');
define("WDI_TSN",'wdi_theme_settings');
define("WDI_META", "_".WDI_VAR."_meta");
//define("wdi",'wdi');
define('WDI_FEED_TABLE','wdi_feeds');
define('WDI_THEME_TABLE','wdi_themes');
define('WDI_VERSION','1.3.2');
define('WDI_IS_PRO','false');
$wdi_minify = ((isset($_GET['wdi_no_minify']) && $_GET['wdi_no_minify'] == "true") ? false : true);
define('WDI_MINIFY', $wdi_minify);

function wdi_use_home_url() {
  $home_url = str_replace("http://", "", home_url());
  $home_url = str_replace("https://", "", $home_url);
  $pos = strpos($home_url, "/");
  if ($pos) {
    $home_url = substr($home_url, 0, $pos);
  }
  $site_url = str_replace("http://", "", WDI_URL);
  $site_url = str_replace("https://", "", $site_url);
  $pos = strpos($site_url, "/");
  if ($pos) {
    $site_url = substr($site_url, 0, $pos);
  }
  return $site_url != $home_url;
}

if (wdi_use_home_url()) {
  define('WDI_FRONT_URL', home_url("wp-content/plugins/" . plugin_basename(dirname(__FILE__))));
}
else {
  define('WDI_FRONT_URL', WDI_URL);
}
//////////////////////////////////////////////////////////////////


add_action('wp_ajax_wdi_get_cache_data', 'wdi_get_cache_data');
add_action('wp_ajax_nopriv_wdi_get_cache_data', 'wdi_get_cache_data');

add_action('wp_ajax_wdi_set_cache_data', 'wdi_set_cache_data');
add_action('wp_ajax_nopriv_wdi_set_cache_data', 'wdi_set_cache_data');

add_action('wp_ajax_wdi_set_reset_cache', 'wdi_set_reset_cache');

function wdi_set_reset_cache(){
  if(wp_verify_nonce($_POST["wdi_nonce"] , 'wdi_cache')) {
    global $wpdb;
    $data = $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '%wdi_cache_data%'");
    if($data === false){
      echo json_encode(array("success"=>false));
    }else{
      echo json_encode(array("success"=>true));
    }
    die;
  }
}


function wdi_get_cache_data(){
  if(wp_verify_nonce($_POST["wdi_nonce"] , 'wdi_cache')){
    if(isset($_POST["wdi_cache_name"])){
      $transient_key = "wdi_cache_data_".md5($_POST["wdi_cache_name"]);
      $cache_data = get_transient($transient_key);
      if(isset($cache_data) && $cache_data!=false){
        echo json_encode(
          array(
            "success"=>"true",
            "cache_data"=>stripslashes($cache_data)
          )
        );
        die;
      }
    }
    echo json_encode(array("success"=>"false"));die;
  }
}
function wdi_set_cache_data(){
  global $wdi_options;
  if(wp_verify_nonce($_POST["wdi_nonce"] , 'wdi_cache')){
    if(isset($_POST["wdi_cache_name"]) && isset($_POST["wdi_cache_response"])){
      if(isset($wdi_options["wdi_transient_time"])){
        $wdi_transient_time = intval($wdi_options["wdi_transient_time"]);
      }else{
        $wdi_transient_time = 60;
      }


      $wdi_cache_response = $_POST["wdi_cache_response"];
      $transient_key = "wdi_cache_data_".md5($_POST["wdi_cache_name"]);
      $expiration_time = $wdi_transient_time * 60;


      if(!seems_utf8($wdi_cache_response)){
        $wdi_cache_response = utf8_encode($wdi_cache_response);
      }

      set_transient($transient_key, $wdi_cache_response, $expiration_time);
    }
  }
}



add_action('wp_ajax_WDIGalleryBox', 'wdi_ajax_frontend');
add_action('wp_ajax_nopriv_WDIGalleryBox', 'wdi_ajax_frontend');
add_action('admin_init', 'wdi_setup_redirect');
function wdi_ajax_frontend() {

  /* reset from user to site locale*/
  if(function_exists('switch_to_locale')){
    switch_to_locale( get_locale() );
  }

  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $page = WDILibrary::get('action');
  //chenged action hook for esacpeing Photo Gallery confilct
  if($page==='WDIGalleryBox'){$page = 'GalleryBox';}
  if (($page != '') && (($page == 'GalleryBox') || ($page == 'Share'))) {
    require_once(WDI_DIR . '/frontend/controllers/WDIController' . ucfirst($page) . '.php');
    $controller_class = 'WDIController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }else{
    wp_die();
  }
}



///////////////////////////////End GALLERY BOX////////////////////////////////////////



//including admin functions
require_once(WDI_DIR .'/admin-functions.php');
//including shortcode file
require_once(WDI_DIR .'/frontend/shortcode.php');

//installing plugin database
register_activation_hook( __FILE__, 'wdi_instagram_activate' );
function wdi_instagram_activate($networkwide) {
  if (function_exists('is_multisite') && is_multisite()) {
    // Check if it is a network activation - if so, run the activation function for each blog id.
    if ($networkwide) {
      global $wpdb;
      $old_blog = $wpdb->blogid;
      // Get all blog ids.
      $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
      foreach ($blogids as $blog_id) {
        switch_to_blog($blog_id);
        wdi_install();
      }
      switch_to_blog($old_blog);
      return;
    }
  }
  add_option('wdi_do_activation_set_up_redirect', 1);
  // wdi_activate();
  wdi_install();
}
function wdi_setup_redirect() {

  if (get_option('wdi_do_activation_set_up_redirect') ) {
    update_option('wdi_do_activation_set_up_redirect',0);
    //wp_safe_redirect( admin_url( 'index.php?page=gmwd_setup' ) );

    if(get_option( "wdi_subscribe_done" ) == 1){
      return;
    }
    wp_safe_redirect( admin_url( 'admin.php?page=wdi_subscribe' ) );
    exit;
  }
}

add_filter('wdi_sanitize_options', 'wdi_create_sample_feed');

function wdi_create_sample_feed($new_options) {

  if(empty($new_options['wdi_authenticated_users_list'])){
    $new_options['wdi_authenticated_users_list'] = '[]';
  }

  //submit wdi options
  if (!isset($_POST['option_page']) || $_POST['option_page'] != 'wdi_all_settings') {
    return $new_options;
  }

  $wdi_options = wdi_get_options();

  if(empty($new_options['wdi_user_name'])) {
    return $new_options;
  }

  $first_user_username = get_option('wdi_first_user_username');
  if($first_user_username !== false && $new_options['wdi_user_name'] !== $first_user_username){
    update_option('wdi_sample_feed_post_url', '0');
    return $new_options;
  }


  if (!empty($wdi_options['wdi_access_token']) || empty($new_options['wdi_access_token'])) {
    return $new_options;
  }

  $wdi_sample_feed_post_id = get_option('wdi_sample_feed_post_id');
  if ($wdi_sample_feed_post_id !== false) {
    return $new_options;
  }

  require_once(WDI_DIR . '/admin/controllers/WDIControllerFeeds_wdi.php');
  require_once(WDI_DIR . '/framework/WDILibrary.php');

  $default_user = new stdClass();
  $default_user->username = $new_options['wdi_user_name'];
  $default_user->id = "";

  $settings = array(
    'feed_users' => json_encode(array($default_user)),
  );

  $controller = new WDIControllerFeeds_wdi();
  $feed_id = $controller->create_feed($settings);

  //db error
  if ($feed_id == false) {
    return $new_options;
  }

  $post_content = "<p>".__('This is a private page. To make it public, edit it and change the visibility.').
        "</p>".
        '[wdi_feed id="' . $feed_id . '"]';

  $post_args = array(
    'post_content' => $post_content,
    'post_status' => 'private',
    'post_title' => __('My Instagram Feed', 'wd-instagram-feed'),
    'post_type' => 'page',
  );

  $post_id = wp_insert_post($post_args);

  if ($post_id === 0) {
    return $new_options;
  }

  update_option('wdi_first_user_username', $new_options['wdi_user_name']);
  update_option('wdi_sample_feed_id', $feed_id);
  update_option('wdi_sample_feed_post_id', $post_id);
  update_option('wdi_sample_feed_post_url', '1');


  return $new_options;
}







add_action("admin_init",'wdi_register_settings');

function wdi_register_settings(){

  //gettings settings for registering
  $settings = wdi_get_settings();

  //adding configure section
  add_settings_section('wdi_configure_section',"",'wdi_configure_section_callback','settings_wdi');

  //multiple accounts
  add_settings_section('wdi_multiple_accounts_section',"",'wdi_multiple_accounts_section_callback','settings_wdi');
  
  //adding customize section
  add_settings_section('wdi_customize_section',"",'wdi_customize_section_callback','settings_wdi');

  //adding settings fileds form getted settings
  foreach($settings as $setting_name => $setting){

    if(isset($setting['field_or_not'])==true && $setting['field_or_not']!='no_field'){
      add_settings_field(
        $setting_name,
        $setting['title'],
        'wdi_field_callback',
        'settings_wdi',
        $setting['section'],
        array($setting)
      );
    }
  }

  //registering all settings
  register_setting( 'wdi_all_settings', 'wdi_instagram_options','wdi_sanitize_options');

  wdi_get_options();
}


//adding menues
add_action('admin_menu', 'WDI_instagram_menu', 9);
add_action('admin_head-toplevel_page_wdi_feeds', 'wdi_check_necessary_params');
function WDI_instagram_menu() {
  $wdi_options = wdi_get_options();

  $menu_icon = WDI_URL .'/images/menu_icon.png';
  $min_feeds_capability = wdi_get_create_feeds_cap();



  $parent_slug = null;
  $wdi_uninstall = true;
  $wdi_uninstall_success = get_option(WDI_OPT);
  if(isset($wdi_uninstall_success["wdi_plugin_uninstalled"]) && $wdi_uninstall_success["wdi_plugin_uninstalled"]==="true"){
    $wdi_uninstall = false;
  }
  if((!isset($wdi_options['wdi_access_token']) || empty($wdi_options['wdi_access_token'])) && $wdi_uninstall) {
    if( get_option( "wdi_subscribe_done" ) == 1 ) {
      $parent_slug = "wdi_feeds";
      $settings_page = add_menu_page(__('Instagram Feed WD', "wd-instagram-feed"), 'Instagram Feed WD', $min_feeds_capability, 'wdi_settings', 'WDI_instagram_settings_page', $menu_icon);
      add_submenu_page("wdi_settings", __('Settings', "wd-instagram-feed"), __('Settings', "wd-instagram-feed"), 'manage_options', 'wdi_settings', 'WDI_instagram_settings_page');
    }
  }else{
    if( get_option( "wdi_subscribe_done" ) == 1 ){
      $parent_slug = "wdi_feeds";
      $settings_page = add_menu_page(__('Instagram Feed WD', "wd-instagram-feed"), 'Instagram Feed WD', $min_feeds_capability, 'wdi_feeds', 'WDI_instagram_feeds_page', $menu_icon);
    }


    add_submenu_page($parent_slug,__('Feeds',"wd-instagram-feed"),__('Feeds',"wd-instagram-feed"),$min_feeds_capability,'wdi_feeds','WDI_instagram_feeds_page');
    add_submenu_page($parent_slug,__('Themes',"wd-instagram-feed"),__('Themes',"wd-instagram-feed"),$min_feeds_capability,'wdi_themes','WDI_instagram_themes_page');
    add_submenu_page($parent_slug,__('Settings',"wd-instagram-feed"),__('Settings',"wd-instagram-feed"),'manage_options','wdi_settings','WDI_instagram_settings_page');
    //add_submenu_page('overview_wdi',__('Featured Themes',"wd-instagram-feed"),__('Featured Themes',"wd-instagram-feed"),$min_feeds_capability,'wdi_featured_themes','wdi_featured_themes');
    //add_submenu_page('overview_wdi',__('Featured Plugins',"wd-instagram-feed"),__('Featured Plugins',"wd-instagram-feed"),$min_feeds_capability,'wdi_featured_plugins','wdi_featured_plugins');

    add_submenu_page($parent_slug,__('Pro Version',"wd-instagram-feed"),__('Pro Version',"wd-instagram-feed"),$min_feeds_capability,'wdi_licensing','WDI_instagram_licensing_page');
    add_submenu_page("",__('Uninstall',"wd-instagram-feed"),__('Uninstall',"wd-instagram-feed"),'manage_options','wdi_uninstall','WDI_instagram_uninstall_page');
  }

}


add_action('admin_menu', 'WDI_add_uninstall',26);
function WDI_add_uninstall(){
  $parent_slug = null;
  if( get_option( "wdi_subscribe_done" ) == 1 ){
    $parent_slug = "wdi_feeds";
  }


}

//Settings page callback
function WDI_instagram_settings_page(){


  //check if user has already unistalled plugin from settings
  wdi_check_uninstalled();
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $controller_class = "WDIControllerSettings_wdi";
  require_once(WDI_DIR . '/admin/controllers/' . $controller_class . '.php');
  $controller = new $controller_class();
  $controller->execute();

}
function WDI_instagram_feeds_page(){
  //check if user has already unistalled plugin from settings
  wdi_check_uninstalled();
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $controller_class = 'WDIControllerFeeds_wdi';
  require_once(WDI_DIR . '/admin/controllers/' . $controller_class . '.php');
  $controller = new $controller_class();
  $controller->execute();
}

function WDI_instagram_themes_page(){
  wdi_check_uninstalled();
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $controller_class = 'WDIControllerThemes_wdi';
  require_once(WDI_DIR . '/admin/controllers/' . $controller_class . '.php');
  $controller = new $controller_class();
  $controller->execute();
}
function WDI_instagram_licensing_page(){
  $controller_class = 'WDIControllerLicensing_wdi';
  require_once(WDI_DIR . '/admin/controllers/' . $controller_class . '.php');
  $controller = new $controller_class();
  $controller->execute();
}



function WDI_instagram_uninstall_page(){

  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $controller_class = 'WDIControllerUninstall_wdi';
  require_once(WDI_DIR . '/admin/controllers/' . $controller_class . '.php');
  $controller = new $controller_class();
  $controller->execute();
}

//loading admin scripts
add_action('admin_enqueue_scripts','wdi_load_scripts');

function wdi_load_scripts($hook){

  require_once(WDI_DIR . '/framework/WDILibrary.php');
  global $wdi_options;
  $page = WDILibrary::get('page');
  if($page === 'wdi_themes' || $page === 'wdi_feeds' || $page === 'wdi_settings' || $page==='wdi_uninstall'){
    wp_enqueue_script('jquery-color');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');

    if(WDI_MINIFY === true){
      wp_enqueue_script('wdi_admin',plugins_url('js/wdi_admin.min.js', __FILE__),array("jquery",'wdi_instagram'), WDI_VERSION );
      wp_enqueue_script('wdi_instagram',plugins_url('js/wdi_instagram.min.js', __FILE__),array("jquery"), WDI_VERSION );
    }else{
      wp_enqueue_script('wdi_admin',plugins_url('js/wdi_admin.js', __FILE__),array("jquery",'wdi_instagram'), WDI_VERSION );
      wp_enqueue_script('wdi_instagram',plugins_url('js/wdi_instagram.js', __FILE__),array("jquery"), WDI_VERSION );
    }

    $uninstall_url  = wp_nonce_url( admin_url( 'admin-ajax.php' ), 'wdiUninstallPlugin', 'uninstall_nonce' );
    wp_localize_script("wdi_admin", 'wdi_ajax',array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'uninstall_url' => $uninstall_url,
      'wdi_nonce' => wp_create_nonce("wdi_cache"),
      'is_pro' => WDI_IS_PRO
    ));
    wp_localize_script("wdi_admin", 'wdi_version',array('is_pro'=>WDI_IS_PRO));
    wp_localize_script("wdi_admin", 'wdi_messages',array(
      'uninstall_confirm' => __( "All the data will be removed from the database. Continue?", "wd-instagram-feed" ),
      'instagram_server_error' => __('Some error with instagram servers, try agian later :(', "wd-instagram-feed" ),
      'invalid_user' => __('Invalid user:', "wd-instagram-feed" ),
      'already_added' =>  __('already added!', "wd-instagram-feed"),
      'user_not_exist' => __('User %s does not exist.', "wd-instagram-feed"),
      'network_error' => __("Network Error, please try again later. :(", "wd-instagram-feed"),
      'invalid_hashtag' => __('Invalid hashtag', "wd-instagram-feed"),
      'hashtag_no_data' => __('This hashtag currently has no posts. Are you sure you want to add it?','wd-instagram-feed'),
      'only_one_user_or_hashtag'=> __('You can add only one username or hashtag in FREE Version', "wd-instagram-feed"),
      'available_in_pro' => __('Available in Premium','wd-instagram-feed'),
      'username_hashtag_multiple' => __('Combined Usernames/Hashtags are available only in Premium version'),
      'theme_save_message_free' => __('Customizing Themes is available only in Premium version','wd-instagram-feed'),
      'invalid_url' => __('URL is not valid','wd-instagram-feed'),
      'selectConditionType' => __('Please Select Condition Type','wd-instagram-feed'),
      'and_descr' => __('Show Posts Which Have All Of The Conditions','wd-instagram-feed'),
      'or_descr' => __('Show Posts Which Have At Least One Of The Conditions','wd-instagram-feed'),
      'nor_descr' => __('Hide Posts Which Have At Least One Of The Conditions','wd-instagram-feed'),
      'either' => __('EITHER','wd-instagram-feed'),
      'neither' => __('NEITHER','wd-instagram-feed'),
      'not' => __('EXCEPT','wd-instagram-feed'),
      'and' => __('AND','wd-instagram-feed'),
      'or' => __('OR','wd-instagram-feed'),
      'nor' => __('NOR','wd-instagram-feed'),
      'do_you_want_to_delete_selected_items' => __('Do you want to delete selected items?', 'wd-instagram-feed'),

    ));
    wp_localize_script("wdi_admin", 'wdi_url',array('plugin_url'=> WDI_URL . '/'));
    wp_localize_script("wdi_admin", 'wdi_admin',array('admin_url' =>get_admin_url()));
    wp_localize_script("wdi_admin", 'wdi_options',$wdi_options);
  }

  if($page == "wdi_uninstall") {
    wp_enqueue_script('wdi-deactivate-popup', WDI_URL.'/wd/assets/js/deactivate_popup.js', array(), WDI_VERSION, true );
    $admin_data = wp_get_current_user();

    wp_localize_script( 'wdi-deactivate-popup', 'wdiWDDeactivateVars', array(
      "prefix" => "wdi" ,
      "deactivate_class" =>  'wdi_deactivate_link',
      "email" => $admin_data->data->user_email,
      "plugin_wd_url" => "https://web-dorado.com/products/wordpress-instagram-feed-wd.html",
    ));
  }


}

//loading admin styles
add_action('admin_enqueue_scripts', 'wdi_load_styles');

function wdi_load_styles() {
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $page = WDILibrary::get('page');
  if($page === 'wdi_themes' || $page === 'wdi_feeds' || $page === 'wdi_settings' || $page==='wdi_uninstall'){
      if(WDI_MINIFY === true){
        wp_enqueue_style('wdi_backend', plugins_url('css/wdi_backend.min.css', __FILE__), array(), WDI_VERSION);
      }else{
        wp_enqueue_style('wdi_backend', plugins_url('css/wdi_backend.css', __FILE__), array(), WDI_VERSION);
      }
  }
  if($page === 'wdi_licensing'){
      if(WDI_MINIFY === true){
        wp_enqueue_style('wdi_licensing', plugins_url('css/wdi_licensing.min.css', __FILE__), array(), WDI_VERSION);
        wp_enqueue_style('wdi_backend', plugins_url('css/wdi_backend.min.css', __FILE__), array(), WDI_VERSION);
      }else{
        wp_enqueue_style('wdi_licensing', plugins_url('css/wdi_licensing.css', __FILE__), array(), WDI_VERSION);
        wp_enqueue_style('wdi_backend', plugins_url('css/wdi_backend.css', __FILE__), array(), WDI_VERSION);
      }
  }
  if($page == "wdi_uninstall") {
    wp_enqueue_style('wdi_deactivate-css',  WDI_URL . '/wd/assets/css/deactivate_popup.css', array(), WDI_VERSION);
  }

}

//add_action( 'enqueue_block_editor_assets', 'enqueue_block_editor_assets' );

function enqueue_block_editor_assets() {
  $wd_bp_plugin_url = WDI_URL;
  $key = 'tw/wdi';
  $plugin_name = "Instagram WD";
  $data = WDILibrary::get_shortcode_data();
  $icon_url = $wd_bp_plugin_url . '/images/insta_2.svg';
  $icon_svg = $wd_bp_plugin_url . '/images/insta.svg';
  ?>
  <script>
    if ( !window['tw_gb_wdi'] ) {
      window['tw_gb_wdi'] = {};
    }
    if ( !window['tw_gb_wdi']['<?php echo $key; ?>'] ) {
      window['tw_gb_wdi']['<?php echo $key; ?>'] = {
        title: '<?php echo $plugin_name; ?>',
        iconUrl: '<?php echo $icon_url; ?>',
        iconSvg: {
          width: '30',
          height: '30',
          src: '<?php echo $icon_svg; ?>'
        },
        data: '<?php echo $data; ?>',
      };
    }
  </script>
  <?php
  //wp_enqueue_style('wditw-gb-wdi_block', $wd_bp_plugin_url . '/css/wdi_block.css', array( 'wp-edit-blocks' ), WDI_VERSION );
  //wp_enqueue_script( 'wditw-gb-wdi_block', $wd_bp_plugin_url . '/js/wdi_block.js', array( 'wp-blocks', 'wp-element' ), WDI_VERSION );
}

// Instagram WDI Widget.
if (class_exists('WP_Widget')) {
  require_once(WDI_DIR . '/admin/controllers/WDIControllerWidget.php');
  add_action('widgets_init', 'wdi_register_widget');
}

function wdi_register_widget(){
    return register_widget("WDIControllerWidget");
}

//Editor shortcode button
add_filter('media_buttons_context', 'wdi_add_editor_button');

function wdi_add_editor_button($context) {
  global $pagenow;
  if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
    $context .= '
      <a onclick="tb_click.call(this);wdi_thickDims();return false;" href="' . add_query_arg(array('action' => 'WDIEditorShortcode','TB_iframe'=>'1'), admin_url('admin-ajax.php')) . '" class="wdi_thickbox button" style="padding-left: 0.4em;" title="Add Instagram Feed">
        <span class="wp-media-buttons-icon wdi_media_button_icon" style="vertical-align: text-bottom; background: url(' . WDI_URL . '/images/menu_icon.png) no-repeat scroll left top rgba(0, 0, 0, 0);background-size:contain;"></span>
        Add Instagram Feed
      </a>';
  }
  return $context;
}



//Editor button ajax handler
add_action("wp_ajax_WDIEditorShortcode",'wdi_editor_button');

function wdi_editor_button(){
  if (function_exists('current_user_can')) {
    if (!current_user_can('publish_posts')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WDI_DIR . '/framework/WDILibrary.php');
  $page = WDILibrary::get('action');
  if ($page != '' && (($page == 'WDIEditorShortcode'))) {
    wp_register_script('wdi-shortcode', WDI_URL . '/js/shortcode.js', array('jquery'), WDI_VERSION);

    require_once(WDI_DIR . '/admin/controllers/WDIControllerEditorShortcode.php');
    $controller_class = 'WDIControllerEditorShortcode';
    $controller = new $controller_class();
    $controller->execute();
  }
  wp_die();
}

/**
 *  handle editor popup
 */
add_action('admin_head', 'wdi_admin_ajax');

function wdi_admin_ajax() {
  global $pagenow;
  if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {

    ?>
    <script>

      var wdi_thickDims, wdi_tbWidth, wdi_tbHeight;
      wdi_tbWidth = 420;
      wdi_tbHeight = 140;
      wdi_thickDims = function() {
        var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
        w = (wdi_tbWidth && wdi_tbWidth < W - 90) ? wdi_tbWidth : W - 40;
        h = (wdi_tbHeight && wdi_tbHeight < H - 60) ? wdi_tbHeight : H - 40;
        if (tbWindow.size()) {
          tbWindow.width(w).height(h);
          jQuery('#TB_iframeContent').width(w).height(h - 27);
          tbWindow.css({'margin-left': '-' + parseInt((w / 2),10) + 'px'});
          if (typeof document.body.style.maxWidth != 'undefined') {
            tbWindow.css({'top':(H-h)/2,'margin-top':'0'});
          }
        }
      };
    </script>
    <?php
  }
}





add_action( 'init', 'wdi_load_textdomain' );
add_action('init', 'wdi_register_instagram_preview_cpt');

function wdi_register_instagram_preview_cpt() {
  $args = array(
    'public' => true,
    'exclude_from_search' => true,
    'show_in_menu' => false,
    'create_posts' => 'do_not_allow',
    'capabilities' => array(
      'create_posts' => FALSE,
      'edit_post' => 'edit_posts',
      'read_post' => 'edit_posts',
      'delete_posts' => FALSE,
    )
  );

  register_post_type('wdi_instagram', $args);
}

/**
 * Load plugin textdomain.
 *
 */
function wdi_load_textdomain() {
  load_plugin_textdomain( "wd-instagram-feed", false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}

add_action('init', 'wdi_check_silent_update');

function wdi_check_silent_update(){
  $current_version = WDI_VERSION;
  $saved_version = get_option('wdi_version');

  wdi_get_options();
  global $wdi_options;
  if(isset($wdi_options['wdi_plugin_uninstalled']) && $wdi_options['wdi_plugin_uninstalled']=='true') {
    /*we uninstalled plugin, do not create DB tables again*/
    return;
  }
  if($current_version  != $saved_version ){

    wdi_install();
  }
}
add_action('init', 'wdi_wd_lib_init', 9);


function wdi_wd_lib_init(){
  $wdi_options = wdi_get_options();
  $parent_slug = "wdi_feeds";
  if(!isset($wdi_options['wdi_access_token']) || empty($wdi_options['wdi_access_token'])){
    $parent_slug = "wdi_settings";
  }


  if(!isset($_REQUEST['ajax']) && is_admin()){

    if( !class_exists("DoradoWeb") ){
      require_once(WDI_DIR . '/wd/start.php');
    }
    global $wdi_wd_plugin_options;
    $wdi_wd_plugin_options = array (
      "prefix" => "wdi",
      "wd_plugin_id" => 121,
      "plugin_title" => "Instagram WD",
      "plugin_wordpress_slug" => "wd-instagram-feed",
      "plugin_dir" => WDI_DIR,
      "plugin_main_file" => __FILE__,
      "description" => __("The most advanced and user-friendly Instagram plugin. Instagram Feed WD plugin allows you to display image feeds from single or multiple Instagram accounts on a WordPress site.", 'wd-instagram-feed'),
      // from web-dorado.com
      "plugin_features" => array(
        0 => array(
          "title" => __("Responsive", "wd-instagram-feed"),
          "description" => __("Instagram feeds are not only elegantly designed to be displayed on your website, but also come fully responsive for better user experience when using mobile devices and tables.", "wd-instagram-feed"),
        ),
        1 => array(
          "title" => __("SEO Friendly", "wd-instagram-feed"),
          "description" => __("Instagram Feed WD uses clean coding and latest SEO techniques necessary to keep your pages and posts SEO-optimized.", "wd-instagram-feed"),
        ),
        2 => array(
          "title" => __("4 Fully Customizable Layouts", "wd-instagram-feed"),
          "description" => __("There are four layout options for Instagram feeds: Thumbnails, Image Browser, Blog Style and Masonry. Display a feed as a simply arranged thumbnails with captions. Use Masonry layout to create a beautiful combination of images and captions. Create a blog feed by simply sharing Instagram posts with captions using blog style layout. Image browser layout saves space, yet allows to display larger images. In addition users can choose the number of the displayed images, layout columns, image order and etc.", "wd-instagram-feed"),
        ),
        3 => array(
          "title" => __("Individual and Mixed Feeds", "wd-instagram-feed"),
          "description" => __("Create mixed and single feeds of Instagram posts. Single feeds can be based on public Instagram accounts and single Instagram hashtag. Mixed feeds can contain multiple public Instagram accounts and multiple Instagram hashtags. A front end filter is available for mixed feeds. Click to filter only one feed based on a single hashtag or account.", "wd-instagram-feed"),
        ),
        4 => array(
          "title" => __("Advanced Lightbox", "wd-instagram-feed"),
          "description" => __("Upon clicking on image thumbnails an elegant lightbox will be opened, where you will find control buttons for displaying images in larger view, read image comments, captions, view image metadata and easily navigate between images. Lightbox can serve as a slider with various stunning slide transition effects. If the feed contains video, the video will be played within the lightbox as an HTML5 video.", "wd-instagram-feed"),
        )
      ),
      // user guide from web-dorado.com
      "user_guide" => array(
        0 => array(
          "main_title" => __("Installation and configuration", "wd-instagram-feed"),
          "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/installation-and-configuration/installation.html",
          "titles" => array(
            array(
              "title" => __("Getting Instagram Access Token", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/installation-and-configuration/getting-access-token.html"
            )
          )
        ),
        1 => array(
          "main_title" => __("Creating an Instagram Feed", "wd-instagram-feed"),
          "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/creating-feeds.html",
          "titles" => array(
            array(
              "title" => __("Thumbnails and Masonry Layouts", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/creating-feeds/thumbnails-and-masonry-layouts.html",
            ),
            array(
              "title" => __("Blog Style Layout", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/creating-feeds/blog-style-layout.html",
            ),
            array(
              "title" => __("Image Browser", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/creating-feeds/image-browser.html",
            ),
            array(
              "title" => __("Lightbox Settings", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/creating-feeds/lightbox-settings.html",
            ),
            array(
              "title" => __("Conditional Filters", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/creating-feeds/conditional-filters.html",
            ),
          )
        ),
        2 => array(
          "main_title" => __("Publishing Instagram Feed", "wd-instagram-feed"),
          "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/publishing-feed.html",
          "titles" => array(
            array(
              "title" => __("Publishing in a Page/Post", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/publishing-feed/page-post.html",
            ),
            array(
              "title" => __("Publishing as a Widget", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/publishing-feed/widget.html",
            ),
            array(
              "title" => __("Publishing by PHP function", "wd-instagram-feed"),
              "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/publishing-feed/php-function.html",
            ),
          )
        ),
        3 => array(
          "main_title" => __("Styling with Themes", "wd-instagram-feed"),
          "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/editing-themes.html",
          "titles" => array()
        ),
        4 => array(
          "main_title" => __("Advanced customizing options", "wd-instagram-feed"),
          "url" => "https://web-dorado.com/wordpress-instagram-feed-wd/advanced-customizing-options.html",
          "titles" => array()
        ),
      ),
      "overview_welcome_image" => null,
      "video_youtube_id" => "ijdrpkVAfEw",  // e.g. https://www.youtube.com/watch?v=ijdrpkVAfEw youtube id is the ijdrpkVAfEw
      "plugin_wd_url" => "https://web-dorado.com/products/wordpress-instagram-feed-wd.html",
      "plugin_wd_demo_link" => "http://wpdemo.web-dorado.com/instagram-wd/?_ga=1.208438225.212018776.1470817467",
      "plugin_wd_addons_link" => "",
      "after_subscribe" => "admin.php?page=overview_wdi", // this can be plagin overview page or set up page
      "plugin_wizard_link" => "",
      "plugin_menu_title" => "Instagram Feed WD",
      "plugin_menu_icon" => WDI_URL . '/images/menu_icon.png',
      "deactivate" => true,
      "subscribe" => true,
      "custom_post" => $parent_slug,  // if true => edit.php?post_type=contact
      "menu_capability" => wdi_get_create_feeds_cap()
    );

    dorado_web_init($wdi_wd_plugin_options);

  }


}

if (!function_exists('wdi_wd_bp_install_notice')) {

  if(get_option('wd_seo_notice_status') !=='' && get_option('wd_seo_notice_status')!=='1') {

    add_action('admin_enqueue_scripts', 'wdi_wd_bp_script_style');

  }

  
  function wdi_wd_bp_script_style() {
    $wd_bp_plugin_url = WDI_URL;
    if(WDI_MINIFY === true){
      wp_enqueue_script('wd_bck_install', $wd_bp_plugin_url . '/js/wd_bp_install.min.js', array('jquery'));
      wp_enqueue_style('wd_bck_install', $wd_bp_plugin_url . '/css/wd_bp_install.min.css');
    }else{
      wp_enqueue_script('wd_bck_install', $wd_bp_plugin_url . '/js/wd_bp_install.js', array('jquery'));
      wp_enqueue_style('wd_bck_install', $wd_bp_plugin_url . '/css/wd_bp_install.css');
    }
  }

  /**
   * Show notice to install backup plugin
   */
  function wdi_wd_bp_install_notice() {
    $wd_bp_plugin_url = WDI_URL;
	
	$screen = get_current_screen();
	if($screen->id != "instagram-feed-wd_page_wdi_licensing" && $screen->id != "toplevel_page_wdi_feeds" && $screen->id != "instagram-feed-wd_page_wdi_themes" && $screen->id != "instagram-feed-wd_page_wdi_settings" && $screen->id != "instagram-feed-wd_page_wdi_uninstall" && $screen->id != "instagram-feed-wd_page_overview_wdi" && $screen->id != "instagram-feed-wd_page_wdi_updates" ){
		return;
	}
	
    $prefix = 'wdi';
    $meta_value = get_option('wd_seo_notice_status');
    if ($meta_value === '' || $meta_value === false) {
      ob_start();
      ?>
      <div class="notice notice-info" id="wd_bp_notice_cont">
        <p>
          <img id="wd_bp_logo_notice" src="<?php echo $wd_bp_plugin_url . '/images/seo_logo.png'; ?>">
          <?php _e("Instagram Feed WD advises: Optimize your web pages for search engines with the", $prefix) ?>
          <a href="https://wordpress.org/plugins/seo-by-10web/" title="<?php _e("More details", $prefix) ?>"
             target="_blank"><?php _e("FREE SEO", $prefix) ?></a>
          <?php _e("plugin.", $prefix) ?>
          <a class="button button-primary"
             href="<?php echo esc_url(wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=seo-by-10web'), 'install-plugin_seo-by-10web')); ?>">
            <span onclick="wd_bp_notice_install()"><?php _e("Install", $prefix); ?></span>
          </a>
        </p>
        <button type="button" class="wd_bp_notice_dissmiss notice-dismiss"><span class="screen-reader-text"></span>
        </button>
      </div>
      <script>wd_bp_url = '<?php echo add_query_arg(array('action' => 'wd_seo_dismiss',), admin_url('admin-ajax.php')); ?>'</script>
      <?php
      echo ob_get_clean();
    }
  }

  if (!is_dir(plugin_dir_path(dirname(__FILE__)) . 'seo-by-10web')) {
    add_action('admin_notices', 'wdi_wd_bp_install_notice');
  }

  /**
   * Add usermeta to db
   *
   * empty: notice,
   * 1    : never show again
   */
  function wdi_wd_bp_install_notice_status() {
    update_option('wd_seo_notice_status', '1', 'no');
  }
  add_action('wp_ajax_wd_seo_dismiss', 'wdi_wd_bp_install_notice_status');
}


add_filter("plugin_row_meta", 'wdi_add_plugin_meta_links', 10, 2);

$wdi_token_error_flag = get_option("wdi_token_error_flag");
if($wdi_token_error_flag === "1"){
  add_action('admin_notices', 'wdi_token_error_flag_notice');
}

function wdi_token_error_flag_notice(){
  $screen_base = get_current_screen()->base;
  if($screen_base === "dashboard" || $screen_base === "toplevel_page_wdi_feeds" || $screen_base === "instagram-feed-wd_page_wdi_themes" || $screen_base === "instagram-feed-wd_page_wdi_settings" || $screen_base === "instagram-feed-wd_page_wdi_uninstall" || $screen_base === "instagram-feed-wd_page_overview_wdi" || $screen_base === "instagram-feed-wd_page_wdi_updates" ){
    $link_to_reset = "<a href='".site_url()."/wp-admin/admin.php?page=wdi_settings' >reset token</a>";
    if($screen_base === "instagram-feed-wd_page_wdi_settings"){
      $link_to_reset = "reset token";
    }
    echo "<div class='notice notice-error '>
	    <p>Instagram token is invalid or expired. Please ".$link_to_reset." and sign-in again to get new one.</p>
    </div>";
  }
}

function wdi_add_plugin_meta_links($meta_fields, $file){

  if(plugin_basename(__FILE__) == $file) {

    $meta_fields[] = "<a href='https://wordpress.org/support/plugin/wd-instagram-feed/' target='_blank'>Support Forum</a>";
    $meta_fields[] = "<a href='https://wordpress.org/support/plugin/wd-instagram-feed/reviews#new-post' target='_blank' title='Rate'>
            <i class='wdi-rate-stars'>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
      . "</i></a>";

    $stars_color = "#ffb900";

    echo "<style>"
      . ".wdi-rate-stars{display:inline-block;color:" . $stars_color . ";position:relative;top:3px;}"
      . ".wdi-rate-stars svg{fill:" . $stars_color . ";}"
      . ".wdi-rate-stars svg:hover{fill:" . $stars_color . "}"
      . ".wdi-rate-stars svg:hover ~ svg{fill:none;}"
      . "</style>";
  }

  return $meta_fields;
}

add_action('admin_notices', 'wdi_filter_var_notice');
function wdi_filter_var_notice(){
  $screen = get_current_screen();
  if(!function_exists('filter_var') && ($screen->base === 'toplevel_page_wdi_feeds' || $screen->base === 'instagram-feed-wd_page_wdi_themes')) {
    echo "<div class='notice notice-error '>
	    <p>Some functionality may be broken. Please enable PHP Filters extension or make sure you have PHP version not older than 5.2.</p>
    </div>";
  }
}