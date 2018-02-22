<?php
/*
Plugin Name: Substitute Display Names
Plugin URI: https://memberfix.rocks
Version: 1.2.0
Description: Substitutes a default public display name for new and existing users.
Author: MemberFix
Author URI: https://memberfix.rocks
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

require_once(__DIR__ . '/includes/functions.php');

/* Version check */
global $wp_version;
$exit_msg='Substitute Displayname requires WordPress 2.5 or newer.  <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';
if (version_compare($wp_version,"2.5","<")) {
    exit ($exit_msg);
}