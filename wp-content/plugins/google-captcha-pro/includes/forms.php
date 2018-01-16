<?php
/**
 * Contains the extending functionality
 * @since 1.32
 */
if ( ! function_exists( 'gglcptch_get_forms' ) ) {
	function gglcptch_get_forms() {
		global $gglcptch_options, $gglcptch_forms;

		$default_forms = array(
			'login_form'				=> array( 'form_name' => __( 'Login form', 'google-captcha-pro' ) ),
			'registration_form'			=> array( 'form_name' => __( 'Registration form', 'google-captcha-pro' ) ),
			'reset_pwd_form'			=> array( 'form_name' => __( 'Reset password form', 'google-captcha-pro' ) ),
			'comments_form'				=> array( 'form_name' => __( 'Comments form', 'google-captcha-pro' ) ),
			'contact_form'				=> array( 'form_name' => 'Contact Form' ),
			'cf7'						=> array( 'form_name' => 'Contact Form 7' ),
			'si_contact_form'			=> array( 'form_name' => 'Fast Secure Contact Form' ),
			'jetpack_contact_form'		=> array( 'form_name' => __( 'Jetpack Contact Form', 'google-captcha-pro' ) ),
			'sbscrbr'					=> array( 'form_name' => 'Subscriber' ),
			'bbpress_new_topic_form'	=> array( 'form_name' => __( 'bbPress New Topic form', 'google-captcha-pro' ) ),
			'bbpress_reply_form'		=> array( 'form_name' => __( 'bbPress Reply form', 'google-captcha-pro' ) ),
			'buddypress_register'		=> array( 'form_name' => __( 'BuddyPress Registration form', 'google-captcha-pro' ) ),
			'buddypress_comments'		=> array( 'form_name' => __( 'BuddyPress Comments form', 'google-captcha-pro' ) ),
			'buddypress_group'			=> array( 'form_name' => __( 'BuddyPress Add New Group form', 'google-captcha-pro' ) ),
			'woocommerce_login'			=> array( 'form_name' => __( 'WooCommerce Login form', 'google-captcha-pro' ) ),
			'woocommerce_register'		=> array( 'form_name' => __( 'WooCommerce Registration form', 'google-captcha-pro' ) ),
			'woocommerce_lost_password'	=> array( 'form_name' => __( 'WooCommerce Reset password form', 'google-captcha-pro' ) ),
			'woocommerce_checkout'		=> array( 'form_name' => __( 'WooCommerce Checkout form', 'google-captcha-pro' ) ),
			'wpforo_login_form'			=> array( 'form_name' => __( 'wpForo Login form', 'google-captcha-pro' ) ),
			'wpforo_register_form'		=> array( 'form_name' => __( 'wpForo Registration form', 'google-captcha-pro' ) ),
			'wpforo_new_topic_form'		=> array( 'form_name' => __( 'wpForo New Topic form', 'google-captcha-pro' ) ),
			'wpforo_reply_form'			=> array( 'form_name' => __( 'wpForo Reply form', 'google-captcha-pro') )
		);

		$custom_forms = apply_filters( 'gglcptch_add_custom_form', array() );
		$gglcptch_forms = array_merge( $default_forms, $custom_forms );

		foreach ( $gglcptch_forms as $form_slug => $form_data ) {
			$gglcptch_forms[ $form_slug ]['form_notice'] = gglcptch_get_form_notice( $form_slug );
		}

		$gglcptch_forms = apply_filters( 'gglcptch_forms', $gglcptch_forms );

		return $gglcptch_forms;
	}
}

if ( ! function_exists( 'gglcptch_get_sections' ) ) {
	function gglcptch_get_sections() {
		global $gglcptch_sections;

		$default_sections = array(
			'standard' => array(
				'name' => __( 'WordPress default', 'google-captcha-pro' ),
				'forms' => array(
					'login_form',
					'registration_form',
					'reset_pwd_form',
					'comments_form'
				)
			),
			'external' => array(
				'name' => __( 'External Plugins', 'google-captcha-pro' ),
				'forms' => array(
					'contact_form',
					'cf7',
					'si_contact_form',
					'jetpack_contact_form',
					'sbscrbr'
				)
			),
			'bbpress' => array(
				'name' => 'bbPress',
				'forms' => array(
					'bbpress_new_topic_form',
					'bbpress_reply_form'
				)
			),
			'buddypress' => array(
				'name' => 'BuddyPress',
				'forms' => array(
					'buddypress_register',
					'buddypress_comments',
					'buddypress_group'
				)
			),
			'woocommerce' => array(
				'name' => 'WooCommerce',
				'forms' => array(
					'woocommerce_login',
					'woocommerce_register',
					'woocommerce_lost_password',
					'woocommerce_checkout'
				)
			),
			'wpforo' => array(
				'name' => 'Forums - wpForo',
				'forms' => array(
					'wpforo_login_form',
					'wpforo_register_form',
					'wpforo_new_topic_form',
					'wpforo_reply_form'
				)
			)
		);

		$custom_forms = apply_filters( 'gglcptch_add_custom_form', array() );

		$custom_sections = ( empty( $custom_forms ) ) ? array() : array( 'custom' => array( 'name' => __( 'Custom Forms', 'google-captcha-pro' ), 'forms' => array_keys( $custom_forms ) ) );
		$gglcptch_sections = array_merge( $default_sections, $custom_sections );

		foreach ( $gglcptch_sections as $section_slug => $section_data ) {
			$gglcptch_sections[ $section_slug ]['section_notice'] = gglcptch_get_section_notice( $section_slug );
		}

		$gglcptch_sections = apply_filters( 'gglcptch_sections', $gglcptch_sections );

		return $gglcptch_sections;
	}
}

/* Add reCAPTCHA forms to the Limit Attempts plugin */
if ( ! function_exists( 'gglcptch_add_lmtttmpts_forms' ) ) {
	function gglcptch_add_lmtttmpts_forms( $forms = array() ) {
		if ( ! is_array( $forms ) )
			$forms = array();

		$forms["gglcptch"] = array(
			'name'		=> __( 'Google Captcha Plugin', 'google-captcha-pro' ),
			'forms'		=> array(),
		);

		$recaptcha_forms = gglcptch_get_forms();

		foreach ( $recaptcha_forms as $form_slug => $form_data ) {
			$forms["gglcptch"]["forms"]["{$form_slug}_recaptcha_check"] = $form_data;
			if ( empty( $form_data['form_notice'] ) )
				$forms["gglcptch"]["forms"]["{$form_slug}_recaptcha_check"]['form_notice'] = gglcptch_get_section_notice( $form_slug );
		}

		return $forms;
	}
}

/**
 * Display section notice
 * @access public
 * @param  $section_slug	string
 * @return array    The action results
 */
if ( ! function_exists( 'gglcptch_get_section_notice' ) ) {
	function gglcptch_get_section_notice( $section_slug = '' ) {
		$section_notice = "";
		$plugins = array(
			'bbpress'			=> 'bbpress/bbpress.php',
			'buddypress'		=> 'buddypress/bp-loader.php',
			'woocommerce'		=> 'woocommerce/woocommerce.php',
			'wpforo'			=> 'wpforo/wpforo.php'
		);

		$is_network_admin = is_network_admin();

		if ( isset( $plugins[ $section_slug ] ) ) {
			$slug = explode( '/', $plugins[ $section_slug ] );
			$slug = $slug[0];
			$plugin_info = gglcptch_plugin_status( $plugins[ $section_slug ], get_plugins(), $is_network_admin );
			if ( 'activated' == $plugin_info['status'] ) {
				/* check required conditions */

				/* BuddyPress works only with single site or main domain */
				if ( 'buddypress' == $section_slug && ! ( is_main_site() || $is_network_admin ) ) {
					$section_notice = __( 'BuddyPress works only with single site or main domain', 'google-captcha-pro' );
				}
			} elseif ( 'deactivated' == $plugin_info['status'] ) {
				$section_notice = '<a href="' . self_admin_url( 'plugins.php' ) . '">' . __( 'Activate', 'google-captcha-pro' ) . '</a>';
			} elseif ( 'not_installed' == $plugin_info['status'] ) {
				$section_notice = sprintf( '<a href="http://wordpress.org/plugins/%s/" target="_blank">%s</a>', $slug, __( 'Install Now', 'google-captcha-pro' ) );
			}
		}

		return apply_filters( 'gglcptch_section_notice', $section_notice, $section_slug );
	}
}

if ( ! function_exists( 'gglcptch_get_form_notice' ) ) {
	function gglcptch_get_form_notice( $form_slug = '' ) {
		global $wp_version, $gglcptch_plugin_info;
		$form_notice = "";

		$plugins = array(
			'contact_form'			=> array( 'contact-form-plugin/contact_form.php', 'contact-form-pro/contact_form_pro.php' ),
			'sbscrbr'				=> array( 'subscriber/subscriber.php', 'subscriber-pro/subscriber-pro.php' ),
			'cf7'					=> 'contact-form-7/wp-contact-form-7.php',
			'si_contact_form'		=> 'si-contact-form/si-contact-form.php',
			'jetpack_contact_form'	=> 'jetpack/jetpack.php'
		);

		if ( isset( $plugins[ $form_slug ] ) ) {
			$plugin_info = gglcptch_plugin_status( $plugins[ $form_slug ], get_plugins(), is_network_admin() );

			if ( 'activated' == $plugin_info['status'] ) {
				/* check required conditions */
				if ( 'cf7' == $form_slug ) {
					if ( version_compare( $plugin_info['plugin_info']['Version'], '3.4', '<' ) ) {
						$form_notice = '<a href="' . self_admin_url( 'plugins.php' ) . '">' . sprintf( __( 'Update %s at least up to %s', 'google-captcha-pro' ), 'Contact Form 7', 'v3.4' ) . '</a>';
					} elseif (
						defined( 'WPCF7_VERSION' ) &&
						defined( 'WPCF7_REQUIRED_WP_VERSION' ) &&
						version_compare( $wp_version, WPCF7_REQUIRED_WP_VERSION, '<' )
					) {
						$form_notice = sprintf(
							__( '%1$s %2$s requires WordPress %3$s or higher.', 'google-captcha-pro' ),
							'Contact Form 7',
							WPCF7_VERSION,
							WPCF7_REQUIRED_WP_VERSION
						);
					}
				}
			} elseif ( 'deactivated' == $plugin_info['status'] ) {
				$form_notice = '<a href="' . self_admin_url( 'plugins.php' ) . '">' . __( 'Activate', 'google-captcha-pro' ) . '</a>';
			} elseif ( 'not_installed' == $plugin_info['status'] ) {
				if ( 'contact_form' == $form_slug ) {
					$form_notice = '<a href="https://bestwebsoft.com/products/wordpress/plugins/contact-form/?k=fa26df3911ebcd90c3e85117d6dd0ce0&pn=281&v=' . $gglcptch_plugin_info["Version"] . '&wp_v=' . $wp_version . '" target="_blank">' . __( 'Install Now', 'google-captcha-pro' ) . '</a>';
				} elseif ( 'sbscrbr' == $form_slug ) {
					$form_notice = '<a href="https://bestwebsoft.com/products/wordpress/plugins/subscriber/?k=c5c7708922e53ab2c3e5c1137d44e3a2&pn=281&v=' . $gglcptch_plugin_info["Version"] . '&wp_v=' . $wp_version . '" target="_blank">' . __( 'Install Now', 'google-captcha-pro' ) . '</a>';
				} else {
					$slug = explode( '/', $plugins[ $form_slug ] );
					$slug = $slug[0];
					$form_notice = sprintf( '<a href="http://wordpress.org/plugins/%s/" target="_blank">%s</a>', $slug, __( 'Install Now', 'google-captcha-pro' ) );
				}
			}
		}
		return apply_filters( 'gglcptch_form_notice', $form_notice, $form_slug );
	}
}

if ( ! function_exists( 'gglcptch_add_pro_actions' ) ) {
	function gglcptch_add_pro_actions() {
		global $gglcptch_options, $wp_version, $gglcptch_ip_in_whitelist;

		$is_user_logged_in = is_user_logged_in();

		if ( ! empty( $gglcptch_options['login_form'] ) || ! empty( $gglcptch_options['reset_pwd_form'] ) || ! empty( $gglcptch_options['registration_form'] ) ) {
			add_action( 'login_enqueue_scripts', 'gglcptch_add_styles' );

			if ( ! ( function_exists( 'is_wpforo_page' ) && is_wpforo_page() ) ) {
				if ( gglcptch_is_recaptcha_required( 'login_form', $is_user_logged_in ) ) {
					add_action( 'login_form', 'gglcptch_login_display' );
					add_action( 'bp_login_widget_form', 'gglcptch_buddypress_login_widget' );
					if ( ! $gglcptch_ip_in_whitelist ) {
						add_action( 'authenticate', 'gglcptch_login_check', 21, 1 );
					}
				}

				if ( gglcptch_is_recaptcha_required( 'registration_form', $is_user_logged_in ) ) {
					if ( ! is_multisite() ) {
						add_action( 'register_form', 'gglcptch_login_display', 99 );
						add_action( 'woocommerce_register_form_start', 'gglcptch_woocommerce_remove_register_action' );
						if ( ! $gglcptch_ip_in_whitelist ) {
							add_action( 'registration_errors', 'gglcptch_register_check', 10, 1 );
						}
					} else {
						add_action( 'signup_extra_fields', 'gglcptch_signup_display' );
						add_action( 'signup_blogform', 'gglcptch_signup_display' );
						if ( ! $gglcptch_ip_in_whitelist ) {
							add_filter( 'wpmu_validate_user_signup', 'gglcptch_signup_check', 10, 3 );
						}
					}
				}
			}

			if ( gglcptch_is_recaptcha_required( 'reset_pwd_form', $is_user_logged_in ) ) {
				add_action( 'lostpassword_form', 'gglcptch_login_display' );

				if ( ! $gglcptch_ip_in_whitelist ) {
					add_action( 'allow_password_reset', 'gglcptch_lostpassword_check' );
				}
			}
		}

		/* Add Google Captcha to WP comments */
		if ( gglcptch_is_recaptcha_required( 'comments_form', $is_user_logged_in ) ) {
			add_action( 'comment_form_after_fields', 'gglcptch_commentform_display' );
			add_action( 'comment_form_logged_in_after', 'gglcptch_commentform_display' );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_action( 'pre_comment_on_post', 'gglcptch_commentform_check' );
			}
		}

		/* Add Google Captcha to Contact Form by BestWebSoft */
		if ( gglcptch_is_recaptcha_required( 'contact_form', $is_user_logged_in ) ) {
			add_filter( 'cntctfrm_display_captcha', 'gglcptch_display', 10, 1 );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_filter( 'cntctfrm_check_form', 'gglcptch_contact_form_check' );
			}
		}

		/* Add Google Captcha to Contact Form 7 */
		if (
			defined( 'WPCF7_REQUIRED_WP_VERSION' ) &&
			version_compare( $wp_version, WPCF7_REQUIRED_WP_VERSION, '>=' ) &&
			gglcptch_is_recaptcha_required( 'cf7', $is_user_logged_in )
		) {
			require_once( dirname( dirname( __FILE__ ) ) . '/captcha_for_cf7.php' );
			/* add shortcode handler */
			wpcf7_add_shortcode_bws_google_captcha_pro();
			if ( ! $gglcptch_ip_in_whitelist ) {
				/* validation for captcha */
				add_filter( 'wpcf7_validate_bwsgooglecaptcha', 'wpcf7_bws_google_captcha_pro_validation_filter', 10, 2 );
				/* add messages for Captha errors */
				add_filter( 'wpcf7_messages', 'wpcf7_bws_google_captcha_pro_messages' );
			}
		}

		/* Add Google Captcha to Subscriber by BestWebSoft */
		if ( gglcptch_is_recaptcha_required( 'sbscrbr', $is_user_logged_in ) ) {
			add_filter( 'sbscrbr_add_field', 'gglcptch_display', 10, 0 );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_filter( 'sbscrbr_check', 'gglcptch_susbscriber_check' );
			}
		}

		/* Add captcha to BuddyPress registration form */
		if ( gglcptch_is_recaptcha_required( 'buddypress_register', $is_user_logged_in ) ) {
			add_action( 'bp_before_registration_submit_buttons', 'gglcptch_buddypress_register_display' );
			if ( ! $gglcptch_ip_in_whitelist ) {
				if ( ! ( ! empty( $gglcptch_options['registration_form'] ) && is_multisite() ) )
				add_action( 'bp_signup_validate', 'gglcptch_buddypress_register_check' );
			}
		}

		/* Add captcha to BuddyPress comments form */
		if ( gglcptch_is_recaptcha_required( 'buddypress_comments', $is_user_logged_in ) ) {
			add_action( 'bp_activity_entry_comments', 'gglcptch_buddypress_comments_display' );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_action( 'bp_activity_before_save', 'gglcptch_buddypress_comments_check' );
			}
		}

		/* Add captcha to BuddyPress add group form */
		if ( gglcptch_is_recaptcha_required( 'buddypress_group', $is_user_logged_in ) ) {
			add_action( 'bp_after_group_details_creation_step', 'gglcptch_buddypress_create_group_display' );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_action( 'groups_group_before_save', 'gglcptch_buddypress_create_group_check' );
			}
		}

		/* Add Google Captcha to WooCommerce Login Form */
		if ( gglcptch_is_recaptcha_required( 'woocommerce_login', $is_user_logged_in ) ) {
			add_action( 'woocommerce_login_form', 'gglcptch_echo_recaptcha', 10, 0 );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_filter( 'woocommerce_process_login_errors', 'gglcptch_woocommerce_login_check' );
			}
		}

		/* Add Google Captcha to WooCommerce Register Form */
		if ( gglcptch_is_recaptcha_required( 'woocommerce_register', $is_user_logged_in ) ) {
			if ( 1 == $gglcptch_options['registration_form'] ) {

			}
			add_action( 'woocommerce_register_form', 'gglcptch_echo_recaptcha', 10, 0 );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_filter( 'woocommerce_process_registration_errors', 'gglcptch_woocommerce_register_check' );
			}
		}

		/* Add Google Captcha to WooCommerce Lost Password Form */
		if ( gglcptch_is_recaptcha_required( 'woocommerce_lost_password', $is_user_logged_in ) ) {
			add_action( 'woocommerce_lostpassword_form', 'gglcptch_echo_recaptcha', 10, 0 );
			if ( ! empty( $gglcptch_options['reset_pwd_form'] ) ) {
				add_action( 'woocommerce_lostpassword_form_start', 'gglcptch_woocommerce_remove_lostpassword_action' );
			}
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_filter( 'allow_password_reset', 'gglcptch_woocommerce_allow_password_reset', 9 );
			}
		}

		/* Add Google Captcha to WooCommerce Checkout Billing Form */
		if ( gglcptch_is_recaptcha_required( 'woocommerce_checkout', $is_user_logged_in ) ) {
			add_action( 'woocommerce_after_checkout_billing_form', 'gglcptch_echo_recaptcha', 10, 0 );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_action( 'woocommerce_checkout_process', 'gglcptch_woocommerce_checkout_process' );
			}
		}

		/* Fast Secure Contact Form */
		if ( gglcptch_is_recaptcha_required( 'si_contact_form', $is_user_logged_in ) ) {
			add_filter( 'si_contact_display_after_fields', 'gglcptch_si_cf_display', 10, 3 );

			if ( ! $gglcptch_ip_in_whitelist ) {
				add_filter( 'si_contact_form_validate', 'gglcptch_si_cf_check', 10, 1 );
			}
		}

		/* jetpack contact form*/
		if ( gglcptch_is_recaptcha_required( 'jetpack_contact_form', $is_user_logged_in ) ) {
			add_filter( 'the_content', 'gglcptch_jetpack_cf_display' );
			add_filter( 'widget_text', 'gglcptch_jetpack_cf_display', 0 );
			add_filter( 'widget_text', 'shortcode_unautop' );
			add_filter( 'widget_text', 'do_shortcode' );
			if ( ! $gglcptch_ip_in_whitelist ) {
				add_filter( 'jetpack_contact_form_is_spam', 'gglcptch_jetpack_cf_check' );
			}
		}

		/* bbPress New Topic, Reply to Topic*/
		if( class_exists( 'bbPress' ) ) {
			if ( gglcptch_is_recaptcha_required( 'bbpress_new_topic_form', $is_user_logged_in ) ) {
				add_action( 'bbp_theme_after_topic_form_content', 'gglcptch_echo_recaptcha', 10, 0 );
				if ( ! $gglcptch_ip_in_whitelist ) {
					add_action( 'bbp_new_topic_pre_extras', 'gglcptch_bbpress_topic_check' );
				}
			}
			if ( gglcptch_is_recaptcha_required( 'bbpress_reply_form', $is_user_logged_in ) ) {
				add_action( 'bbp_theme_after_reply_form_content', 'gglcptch_echo_recaptcha', 10, 0 );
				if ( ! $gglcptch_ip_in_whitelist ) {
					add_action( 'bbp_new_reply_pre_extras', 'gglcptch_bbpress_reply_check' );
				}
			}
		}

		/* wpForo*/
		if ( function_exists('is_wpforo_page') && is_wpforo_page() ) {
			if ( gglcptch_is_recaptcha_required( 'wpforo_login_form', $is_user_logged_in ) ) {
				add_action( 'login_form', 'gglcptch_login_display' );
				if ( ! $gglcptch_ip_in_whitelist ) {
					add_action( 'authenticate', 'gglcptch_wpforo_login_check', 21, 1 );
				}
			}

			if ( gglcptch_is_recaptcha_required( 'wpforo_register_form', $is_user_logged_in ) ) {
				if ( ! is_multisite() ) {
					add_action( 'register_form', 'gglcptch_login_display', 99 );
					if ( ! $gglcptch_ip_in_whitelist ) {
						add_action( 'registration_errors', 'gglcptch_wpforo_register_check', 10, 1 );
					}
				} else {
					add_action( 'signup_extra_fields', 'gglcptch_signup_display' );
					add_action( 'signup_blogform', 'gglcptch_signup_display' );
					if ( ! $gglcptch_ip_in_whitelist ) {
						add_filter( 'wpmu_validate_user_signup', 'gglcptch_signup_check', 10, 3 );
					}
				}
			}

			if ( gglcptch_is_recaptcha_required( 'wpforo_new_topic_form', $is_user_logged_in ) ) {
				add_action( 'wpforo_topic_form_buttons_hook', 'gglcptch_echo_recaptcha', 99, 0 );
				if ( ! $gglcptch_ip_in_whitelist ) {
					add_filter( 'wpforo_add_topic_data_filter', 'gglcptch_wpfpro_topic_check', 10, 1 );
				}
			}

			if ( gglcptch_is_recaptcha_required( 'wpforo_reply_form', $is_user_logged_in ) ) {
				add_action( 'wpforo_reply_form_buttons_hook', 'gglcptch_echo_recaptcha', 99, 0 );
				if ( ! $gglcptch_ip_in_whitelist ) {
					add_filter( 'wpforo_add_post_data_filter', 'gglcptch_wpfpro_reply_check', 10, 1 );
				}
			}
		}
	}
}

/* Echo google captcha */
if ( ! function_exists( 'gglcptch_echo_recaptcha' ) ) {
	function gglcptch_echo_recaptcha( $content = '' ) {
		echo gglcptch_display( $content );
	}
}

/* Add google captcha to the login form */
if ( ! function_exists( 'gglcptch_login_display' ) ) {
	function gglcptch_login_display() {

		global $gglcptch_options;

		if ( isset( $gglcptch_options['recaptcha_version'] ) && in_array( $gglcptch_options['recaptcha_version'], array( 'v1', 'v2' ) ) ) {
			if ( 'v2' == $gglcptch_options['recaptcha_version'] ) {
				$from_width = 302;
			} else {
				$from_width = 320;
				if ( 'clean' == $gglcptch_options['theme'] )
					$from_width = 450;
			} ?>
			<style type="text/css" media="screen">
				.login-action-login #loginform,
				.login-action-lostpassword #lostpasswordform,
				.login-action-register #registerform {
					width: <?php echo $from_width; ?>px !important;
				}
				#login_error,
				.message {
					width: <?php echo $from_width + 20; ?>px !important;
				}
				.login-action-login #loginform .gglcptch,
				.login-action-lostpassword #lostpasswordform .gglcptch,
				.login-action-register #registerform .gglcptch {
					margin-bottom: 10px;
				}
			</style>
		<?php }
		echo gglcptch_display();
		return true;
	}
}

/* Check google captcha in login form */
if ( ! function_exists( 'gglcptch_login_check' ) ) {
	function gglcptch_login_check( $user ) {

		if ( gglcptch_is_woocommerce_page() ) return $user;

		if ( is_wp_error( $user ) )
			return $user;

		$gglcptch_check = gglcptch_check();

		/* reCAPTCHA is not configured */
		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $user;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'login_form' );

		if ( true !== $la_result ) {
			$user = new WP_Error();

			if ( is_wp_error( $la_result ) ) {
				$user = $la_result;
			} elseif ( is_string( $la_result ) ) {
				$user->add( 'gglcptch_la_error', $la_result );
			}

			if ( $gglcptch_check['reason'] == 'VERIFICATION_FAILED' ) {
				wp_clear_auth_cookie();
			}

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$user->add( 'gglcptch_error', $error_message );
			}
		}

		return $user;
	}
}

/* Check google captcha in lostpassword form */
if ( ! function_exists( 'gglcptch_register_check' ) ) {
	function gglcptch_register_check( $allow ) {

		if ( gglcptch_is_woocommerce_page() )
			return $allow;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $allow;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'registration_form' );

		if ( true !== $la_result ) {
			if ( ! is_wp_error( $allow ) )
				$allow = new WP_Error();

			if ( is_wp_error( $la_result ) ) {
				$allow = $la_result;
			} elseif ( is_string( $la_result ) ) {
				$allow->add( 'gglcptch_la_error', $la_result );
			}

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$allow->add( 'gglcptch_error', $error_message );
			}
		}

		return $allow;
	}
}

/* Check google captcha in lostpassword form */
if ( ! function_exists( 'gglcptch_lostpassword_check' ) ) {
	function gglcptch_lostpassword_check( $allow ) {

		if ( gglcptch_is_woocommerce_page() ) return $allow;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $allow;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'reset_pwd_form' );

		if ( true !== $la_result ) {
			if ( ! is_wp_error( $allow ) )
				$allow = new WP_Error();

			if ( is_wp_error( $la_result ) ) {
				$allow = $la_result;
			} elseif ( is_string( $la_result ) ) {
				$allow->add( 'gglcptch_la_error', $la_result );
			}

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$allow->add( 'gglcptch_error', $error_message );
			}
		}

		return $allow;
	}
}

/* Add google captcha to the multisite login form */
if ( ! function_exists( 'gglcptch_signup_display' ) ) {
	function gglcptch_signup_display( $errors ) {
		if ( $error_message = $errors->get_error_message( 'gglcptch_error' ) ) {
			printf( '<p class="error gglcptch_error">%s</p>', $error_message );
		}
		echo gglcptch_display();
	}
}

/* Check google captcha in multisite login form */
if ( ! function_exists( 'gglcptch_signup_check' ) ) {
	function gglcptch_signup_check( $result ) {
		global $current_user;

		if ( is_admin() && ! defined( 'DOING_AJAX' ) && ! empty( $current_user->data->ID ) )
			return $result;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $result;

		$errors = $result['errors'];

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'registration_form' );

		if ( true !== $la_result ) {
			if ( is_wp_error( $la_result ) ) {
				$la_result = $la_result->get_error_message();
				$errors->add( 'gglcptch_la_error', $la_result );
			} elseif ( is_string( $la_result ) ) {
				$errors->add( 'gglcptch_la_error', $la_result );
			}

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$errors->add( 'gglcptch_error', $error_message );
			}

			$result['errors'] = $errors;
		}

		return $result;
	}
}

/* Add google captcha to the comment form */
if ( ! function_exists( 'gglcptch_commentform_display' ) ) {
	function gglcptch_commentform_display() {
		if ( gglcptch_is_hidden_for_role() )
			return;
		echo gglcptch_display();
		return true;
	}
}

/* Check JS enabled for comment form  */
if ( ! function_exists( 'gglcptch_commentform_check' ) ) {
	function gglcptch_commentform_check() {

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'comments_form' );

		if ( true !== $la_result ) {
			$error_message = '';
			if ( is_wp_error( $la_result ) ) {
				$la_result = $la_result->get_error_message();
				$error_message .= $la_result . "<br />";
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result . "<br />";
			}

			if ( ! $gglcptch_check['response'] )
				$error_message .= gglcptch_get_message() . "<br />";

			$error_message = sprintf(
				'<strong>%s</strong>:&nbsp;%s&nbsp;%s',
				__( 'Error', 'google-captcha-pro' ),
				$error_message,
				__( 'Click the BACK button on your browser and try again.', 'google-captcha-pro' )
			);
			wp_die( $error_message );
		}
		return;
	}
}

/* Check google captcha in BWS Contact Form */
if ( ! function_exists( 'gglcptch_contact_form_check' ) ) {
	function gglcptch_contact_form_check( $allow = true ) {
		if ( ! $allow || is_string( $allow ) || is_wp_error( $allow ) ) {
			return $allow;
		}

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return true;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'contact_form' );

		if ( true !== $la_result ) {
			$allow = new WP_Error();

			if ( is_wp_error( $la_result ) ) {
				$allow = $la_result;
			} elseif ( is_string( $la_result ) ) {
				$allow->add( 'gglcptch_la_error', $la_result );
			}

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$allow->add( 'gglcptch_error', $error_message );
			}

			return $allow;
		} else {
			return true;
		}
	}
}

/* display google captcha in subscriber */
if ( ! function_exists( 'gglcptch_susbscriber_display' ) ) {
	function gglcptch_susbscriber_display( $content = "" ) {
		return $content . gglcptch_display();
	}
}

/* check google captcha in subscriber */
if ( ! function_exists( 'gglcptch_susbscriber_check' ) ) {
	function gglcptch_susbscriber_check( $check_result = true ) {

		if ( is_array( $check_result ) ) {
			$check_result = false;
		}

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $check_result;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'sbscrbr' );

		if ( true !== $la_result ) {
			$check_result = "";
			if ( is_wp_error( $la_result ) ) {
				$la_result = $la_result->get_error_message();
				$check_result .= $la_result . "\n";
			} elseif ( is_string( $la_result ) ) {
				$check_result .= $la_result . "\n";
			}

			if ( ! $gglcptch_check['response'] )
				$check_result .= gglcptch_get_message();
		}

		return $check_result;
	}
}

/* add scripts and styles google captcha for BuddyPress comments form */
if ( ! function_exists( 'gglcptch_add_buddypress_script' ) ) {
	function gglcptch_add_buddypress_script() {
		global $gglcptch_plugin_info;
		/*get bp version */
		if ( ! function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$bp_plugin_info = get_plugin_data( dirname( dirname( dirname( __FILE__ ) ) ) . "/buddypress/bp-loader.php" );

		if ( ! wp_script_is( 'gglcptch_buddypress', 'registered' ) ) {
			$file_version   = isset( $bp_plugin_info ) && $bp_plugin_info["Version"] < '2.1' ? '_before_2.1' : '' ;
			wp_register_script( 'gglcptch_buddypress', plugins_url( "js/bp_script{$file_version}.js" , dirname( __FILE__ ) ), array( 'gglcptch_script' ), $gglcptch_plugin_info['Version'], true );
		}
	}
}

/* display google captcha in BuddyPress registration form */
if ( ! function_exists( 'gglcptch_buddypress_register_display' ) ) {
	function gglcptch_buddypress_register_display() {
		global $bp;

		$error = '';

		if ( ! empty( $bp->signup->errors['gglcptch_buddypress_registration'] ) ) {
			$error = sprintf( '<div class="error">%s</div>', $bp->signup->errors['gglcptch_buddypress_registration'] );
		}
		printf( '<div class="register-section gglcptch-section" id="profile-details-section">%s<div class="editfield"></div>%s</div>', $error, gglcptch_display() );
		gglcptch_add_buddypress_script();
	}
}

/* check google captcha in BuddyPress registration form */
if ( ! function_exists( 'gglcptch_buddypress_register_check' ) ) {
	function gglcptch_buddypress_register_check( $errors ) {
		global $bp;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return true;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'buddypress_register' );

		if ( true !== $la_result ) {
			$error_message = "";
			if ( is_wp_error( $la_result ) ) {
				$la_result = $la_result->get_error_message();
				$error_message .= $la_result . "\n";
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result . "\n";
			}

			if ( ! $gglcptch_check['response'] )
				$error_message .= gglcptch_get_message();
			$bp->signup->errors['gglcptch_buddypress_registration'] = $error_message;
			return false;
		} else {
			return true;
		}
	}
}

/* add captcha to buddypress login form in widget */
if ( ! function_exists ( 'gglcptch_buddypress_login_widget' ) ) {
	function gglcptch_buddypress_login_widget() {
		/* captcha html - buddypress registration form */
		echo gglcptch_display();
		gglcptch_add_buddypress_script();
	}
}

/* display google captcha in BuddyPress comments form */
if ( ! function_exists( 'gglcptch_buddypress_comments_display' ) ) {
	function gglcptch_buddypress_comments_display() {
		printf( '<div class="ac-reply-content ac-reply-content-gglcptch">%s</div>', gglcptch_display() );
		gglcptch_add_buddypress_script();
	}
}

/* check google captcha in BuddyPress comments form */
if ( ! function_exists( 'gglcptch_buddypress_comments_check' ) ) {
	function gglcptch_buddypress_comments_check( $bp_activity ) {

		if ( 'activity_comment' != $bp_activity->type )
			return $bp_activity;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $activity_id;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'buddypress_comments' );

		if ( true !== $la_result ) {
			$bp_activity->error_type = 'wp_error';
			$bp_activity->errors = new WP_Error();

			if ( is_wp_error( $la_result ) ) {
				$error_message = $la_result->get_error_message();
				$bp_activity->errors->add( 'gglcptch_la_error', $error_message );
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result;
				$bp_activity->errors->add( 'gglcptch_la_error', $error_message );
			}

			if ( ! $gglcptch_check['response'] ) {
				$error_message = gglcptch_get_message();
				$bp_activity->errors->add( 'gglcptch_error', $error_message );
			}
		}

		return $bp_activity;
	}
}

/* Display google captcha in BuddyPress create group */
if ( ! function_exists( 'gglcptch_buddypress_create_group_display' ) ) {
	function gglcptch_buddypress_create_group_display() {
		printf( '<div class="gglcptch_buddypress_group_form">%s</div>', gglcptch_display() );
		gglcptch_add_buddypress_script();
	}
}

/* check google captcha in BuddyPress create group */
if ( ! function_exists( 'gglcptch_buddypress_create_group_check' ) ) {
	function gglcptch_buddypress_create_group_check( $bp_group ) {
		$gglcptch_check = gglcptch_check();

		/* Skip reCAPTCHA check for the second step */
		if ( ! bp_is_group_creation_step( 'group-details' ) )
			return false;

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return false;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'buddypress_group' );

		if ( true !== $la_result ) {
			$error_message = "";
			if ( is_wp_error( $la_result ) ) {
				$la_result = $la_result->get_error_message();
				$error_message .= $la_result . "\n";
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result . "\n";
			}

			if ( ! $gglcptch_check['response'] )
				$error_message .= gglcptch_get_message();

			$bp_group->name = '';
			bp_core_add_message( $error_message, 'error' );
			bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
		}
		return false;
	}
}

/* WooCommerce Login Form Hooks */

/**
 *
 * @since 1.27
 */
if ( ! function_exists( 'gglcptch_woocommerce_login_check' ) ) {
	function gglcptch_woocommerce_login_check( $allow ) {

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $allow;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'woocommerce_login' );

		if ( true !== $la_result ) {
			if ( is_wp_error( $la_result ) ) {
				$la_result = $la_result->get_error_message();
			}
			$allow->add( 'gglcptch_la_error', $la_result );

			if ( ! $gglcptch_check['response'] )
				$allow->add( 'gglcptch_error', gglcptch_get_message() );
		}
		return $allow;
	}
}

/* WooCommerce Register Form Hooks */

/**
 *
 * @since 1.27
 */
if ( ! function_exists( 'gglcptch_woocommerce_register_check' ) ) {
	function gglcptch_woocommerce_register_check( $allow ) {
		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $allow;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'woocommerce_register' );

		if ( true !== $la_result ) {
			if ( ! is_wp_error( $allow ) ) {
				$allow = new WP_Error();
			}

			if ( is_wp_error( $la_result ) ) {
				$la_result = $la_result->get_error_message();
			}
			$allow->add( 'gglcptch_la_error', $la_result );

			if ( ! $gglcptch_check['response'] ) {
				$error_message = gglcptch_get_message();
				$allow->add( 'gglcptch_error', $error_message );
			}
		}

		return $allow;
	}
}

/* WooCommerce Lost Password Form Hooks */

/**
 * Check the Google Captcha answer in the WooCommerce lost password form
 * @since 1.27
 * @param  boolean      $allow    if 'false' - the password changing is not allowed
 * @return object/true  $allow    an instance of the class WP_ERROR  or boolean
 */
if ( ! function_exists( 'gglcptch_woocommerce_allow_password_reset' ) ) {
	function gglcptch_woocommerce_allow_password_reset( $allow = false ) {
		/* prevent the repeated checking of the WP lost password form */
		$backtraces = debug_backtrace();
		foreach ( $backtraces as $key => $backtrace ) {
			if ( $backtrace['function'] == 'get_password_reset_key' ) {
				return $allow;
			}
		}

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $allow;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'woocommerce_lost_password' );

		if ( true !== $la_result ) {
			if ( ! is_wp_error( $allow ) ) {
				$allow = new WP_Error();
			}

			if ( is_wp_error( $la_result ) ) {
				$la_result = $la_result->get_error_message();
			}

			$allow->add( 'gglcptch_la_error', $la_result );

			if ( ! $gglcptch_check['response'] ) {
				$error_message = gglcptch_get_message();
				$allow->add( 'gglcptch_error', $error_message );
			}
		}

		return $allow;
	}
}

/* WooCommerce Checkout Form Hooks */

/**
 * Check the Google Captcha from the WooCommerce Checkout Billings page
 * @since 1.27
 * @param  void
 * @return void
 */
if ( ! function_exists( 'gglcptch_woocommerce_checkout_process' ) ) {
	function gglcptch_woocommerce_checkout_process() {
		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return false;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'woocommerce_checkout' );

		if ( true !== $la_result ) {
			$error_message = '';
			if ( is_wp_error( $la_result ) ) {
				$error_message .= $la_result->get_error_message();
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result;
			}

			if ( ! $gglcptch_check['response'] )
				$error_message .= gglcptch_get_message();

			wc_add_notice( $error_message, 'error' );
		}
	}
}

/**
 * Prevent the duplicate displaying of the Google Captcha in the WooCommerce login, register, lostpassword forms
 * @since 1.27
 * @param  void
 * @return void
 */
if ( ! function_exists( 'gglcptch_woocommerce_remove_register_action' ) ) {
	function gglcptch_woocommerce_remove_register_action() {
		remove_action( 'register_form', 'gglcptch_login_display', 99 );
	}
}

if ( ! function_exists( 'gglcptch_woocommerce_remove_lostpassword_action' ) ) {
	function gglcptch_woocommerce_remove_lostpassword_action() {
		remove_action( 'lostpassword_form', 'gglcptch_login_display' );
	}
}

/*						Fast Secure Contact Form						*/

/* Add reCAPTCHA to the Fast Secure Contact Form */
if ( ! function_exists( 'gglcptch_si_cf_display' ) ) {
	function gglcptch_si_cf_display( $content = '', $style = array(), $form_errors = array() ) {
		/* if no reCAPTCHA errors */
		if ( ! isset( $form_errors['gglcptch_error'] ) )
			return gglcptch_display( $content );

		/* If reCAPTCHA is failed */
		return sprintf(
			'%s<div %s>%s</div>%s',
			$content,
			isset( $style['error'] ) ? $style['error'] : '',
			$form_errors['gglcptch_error'],
			gglcptch_display()
		);
	}
} /* end function gglcptch_si_cf_display */

/* check Fast Secure Contact Form reCAPTCHA answer */
if ( ! function_exists( 'gglcptch_si_cf_check' ) ) {
	function gglcptch_si_cf_check( $form_errors ) {
		global $gglcptch_si_cf_is_checked;

		if ( isset( $gglcptch_si_cf_is_checked ) )
			return $form_errors;

		$gglcptch_si_cf_is_checked = true;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $form_errors;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'si_contact_form' );

		if ( true !== $la_result ) {
			$error_message = '';
			if ( ! is_array( $form_errors ) )
				$form_errors = array();

			if ( is_wp_error( $la_result ) ) {
				$error_message .= $la_result->get_error_message();
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result;
			}
			$form_errors['gglcptch_la_error'] = $error_message;

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$form_errors['gglcptch_error'] = $error_message;
			}
		}

		return $form_errors;
	}
} /* end function gglcptch_si_cf_check */

/*						Jetpack						*/

/* Add reCAPTCHA to the Jetpack Contact Form */
if ( ! function_exists( 'gglcptch_jetpack_cf_display' ) ) {
	function gglcptch_jetpack_cf_display( $content ) {
		return preg_replace_callback( "~(\[contact-form([\s\S]*)?\][\s\S]*)(\[\/contact-form\])~U", "gglcptch_jetpack_cf_callback", $content );
	}
} /* end function gglcptch_jetpack_cf_display */

/* Add reCAPTCHA shortcode to the provided shortcode for Jetpack contact form */
if ( ! function_exists( 'gglcptch_jetpack_cf_callback' ) ) {
	function gglcptch_jetpack_cf_callback( $matches ) {
		if ( ! preg_match( "~\[bws_google_captcha\]~", $matches[0] ) ) {
			return $matches[1] . "[bws_google_captcha]" . $matches[3];
		}
		return $matches[0];
	}
} /* end function gglcptch_jetpack_cf_callback */

/* check reCAPTCHA answer from the Jetpack Contact Form */
if ( ! function_exists( 'gglcptch_jetpack_cf_check' ) ) {
	function gglcptch_jetpack_cf_check( $is_spam = false ) {
		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $is_spam;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'jetpack_contact_form' );

		if ( true !== $la_result ) {
			$error_message = '';
			if ( ! is_wp_error( $is_spam ) )
				$is_spam = new WP_Error();

			if ( is_wp_error( $la_result ) ) {
				$error_message .= $la_result->get_error_message();
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result;
			}
			$is_spam->add( 'gglcptch_la_error', $error_message );

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$is_spam->add( 'gglcptch_error', $error_message );
			}
		}

		return $is_spam;
	}
} /* end function gglcptch_jetpack_cf_check */

/*						bbPress						*/

/* check reCAPTCHA answer from the bbPress New Topic form */
if ( ! function_exists( 'gglcptch_bbpress_topic_check' ) ) {
	function gglcptch_bbpress_topic_check() {
		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'bbpress_new_topic_form' );

		if ( true !== $la_result && function_exists( 'bbp_add_error' ) ) {
			$error_message = '';
			if ( is_wp_error( $la_result ) ) {
				$error_message .= $la_result->get_error_message();
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result;
			}
			bbp_add_error( 'gglcptch_la_error', $error_message );

			if ( ! $gglcptch_check['response'] )
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );

			bbp_add_error( 'gglcptch_error', $error_message );
		}
	}
} /* end function gglcptch_bbpress_topic_check */

/* check reCAPTCHA answer from the bbPress Reply form */
if ( ! function_exists( 'gglcptch_bbpress_reply_check' ) ) {
	function gglcptch_bbpress_reply_check() {
		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'bbpress_reply_form' );

		if ( true !== $la_result && function_exists( 'bbp_add_error' ) ) {
			$error_message = '';
			if ( is_wp_error( $la_result ) ) {
				$error_message .= $la_result->get_error_message();
			} elseif ( is_string( $la_result ) ) {
				$error_message .= $la_result;
			}
			bbp_add_error( 'gglcptch_la_error', $error_message );

			if ( ! $gglcptch_check['response'] )
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );

			bbp_add_error( 'gglcptch_error', $error_message );
		}
	}
} /* end function gglcptch_bbpress_reply_check */

/*						wpForo						*/

/* Check google captcha in wpForo login form */
if ( ! function_exists( 'gglcptch_wpforo_login_check' ) ) {
	function gglcptch_wpforo_login_check( $user ) {

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $user;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'wpforo_login_form' );

		if ( true !== $la_result ) {
			$user = new WP_Error();

			if ( is_wp_error( $la_result ) ) {
				$user = $la_result;
			} elseif ( is_string( $la_result ) ) {
				$user->add( 'gglcptch_la_error', $la_result );
			}

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$user->add( 'gglcptch_error', $error_message );
			}

			if ( $gglcptch_check['reason'] == 'VERIFICATION_FAILED' ) {
				wp_clear_auth_cookie();
			}
		}

		return $user;
	}
}

/* Check google captcha in wpForo Register form */
if ( ! function_exists( 'gglcptch_wpforo_register_check' ) ) {
	function gglcptch_wpforo_register_check( $allow ) {

		if ( gglcptch_is_woocommerce_page() ) return $allow;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $allow;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'wpforo_register_form' );

		if ( true !== $la_result ) {
			if ( ! is_wp_error( $allow ) )
				$allow = new WP_Error();

			if ( is_wp_error( $la_result ) ) {
				$allow = $la_result;
			} elseif ( is_string( $la_result ) ) {
				$allow->add( 'gglcptch_la_error', $la_result );
			}

			if ( ! $gglcptch_check['response'] ) {
				$error_message = sprintf( '<strong>%s</strong>:&nbsp;%s', __( 'Error', 'google-captcha-pro' ), gglcptch_get_message() );
				$allow->add( 'gglcptch_error', $error_message );
			}
		}

		return $allow;
	}
}

/* check reCAPTCHA answer from wpForo New Topic form */
if ( ! function_exists( 'gglcptch_wpfpro_topic_check' ) ) {
	function gglcptch_wpfpro_topic_check( $data ) {
		global $wpforo;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $data;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'wpforo_new_topic_form' );

		if ( true !== $la_result ) {
			if ( is_wp_error( $la_result ) ) {
				$error_message = $la_result->get_error_message();
			} elseif ( is_string( $la_result ) ) {
				$error_message = $la_result;
			}
			$wpforo->notice->add( $error_message, 'error');

			if ( ! $gglcptch_check['response'] ) {
				$wpforo->notice->add( gglcptch_get_message(), 'error');
			}

			return false;
		}

		return $data;
	}
} /* end function gglcptch_wpfpro_topic_check */

/* check reCAPTCHA answer from wpForo Reply form */
if ( ! function_exists( 'gglcptch_wpfpro_reply_check' ) ) {
	function gglcptch_wpfpro_reply_check( $data ) {
		global $wpforo;

		$gglcptch_check = gglcptch_check();

		if ( ! $gglcptch_check['response'] && $gglcptch_check['reason'] == 'ERROR_NO_KEYS' )
			return $data;

		$la_result = gglcptch_handle_by_limit_attempts( $gglcptch_check['response'], 'wpforo_reply_form' );

		if ( true !== $la_result ) {
			if ( is_wp_error( $la_result ) ) {
				$error_message = $la_result->get_error_message();
			} elseif ( is_string( $la_result ) ) {
				$error_message = $la_result;
			}
			$wpforo->notice->add( $error_message, 'error');

			if ( ! $gglcptch_check['response'] ) {
				$wpforo->notice->add( gglcptch_get_message(), 'error');
			}

			return false;
		}

		return $data;
	}
} /* end function gglcptch_wpfpro_reply_check */


