<?php
/**
 * Admin-notices handler.
 *
 * @package Fusion-Library
 * @since 1.5
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Handle admin notices.
 *
 * @since 1.5
 */
class Fusion_Admin_Notice {

	/**
	 * Already added notices.
	 * We use the ID to differentiate them.
	 *
	 * @static
	 * @access private
	 * @since 1.5
	 * @var array
	 */
	private static $already_added_ids = array();

	/**
	 * The ID.
	 *
	 * @access private
	 * @since 1.5
	 * @var string
	 */
	private $id;

	/**
	 * The screens in which we'll be showing the notice.
	 *
	 * @access private
	 * @since 1.5
	 * @var string
	 */
	private $screen;

	/**
	 * The content.
	 *
	 * @access private
	 * @since 1.5
	 * @var string
	 */
	private $content = '';

	/**
	 * Whether we want to show the notice ot not.
	 *
	 * @access private
	 * @since 1.5
	 * @var bool
	 */
	private $show;

	/**
	 * The notice-type.
	 *
	 * @access private
	 * @since 1.5
	 * @var string
	 */
	private $type;

	/**
	 * Is this notice dismissible?
	 *
	 * @access private
	 * @since 1.5
	 * @var bool
	 */
	private $dismissible;

	/**
	 * The type of option we'll save the data to.
	 * user_meta|option etc.
	 *
	 * @access private
	 * @since 1.0
	 * @var string
	 */
	private $dismiss_type;

	/**
	 * The name of the dismiss-option|meta etc.
	 *
	 * @access private
	 * @since 1.5
	 * @var string
	 */
	private $dismiss_option;

	/**
	 * Constructor.
	 *
	 * @since 1.5
	 * @param string $id             The ID of this notice.
	 * @param string $content        The contents of this notice.
	 * @param bool   $show           Whether we want to show the notice or not.
	 * @param string $type           The admin-notice type (error|warning|success|info).
	 * @param bool   $dismissible    Whether we want this notice to be dismissible or not.
	 * @param string $dismiss_type   The type of data we want to save when dismissing the notice (example:user_meta).
	 * @param string $dismiss_option The option to save in $dismiss_type (if using user-meta, then the meta-name).
	 * @param array  $screen         An array of screen-IDs (the ID returned from the get_current_screen() function ).
	 */
	public function __construct( $id = '', $content = '', $show = true, $type = 'warning', $dismissible = true, $dismiss_type = 'user_meta', $dismiss_option = '', $screen = array() ) {
		// Early exit if already added.
		if ( in_array( $id, self::$already_added_ids ) ) {
			return;
		}

		// Set the object properties.
		$this->id             = $id;
		$this->content        = $content;
		$this->show           = $show;
		$this->type           = $type;
		$this->dismissible    = $dismissible;
		$this->dismiss_type   = $dismiss_type;
		$this->dismiss_option = $dismiss_option;
		$this->screen         = $screen;

		// Check the screen.
		$this->check_screen();

		// Check if dismissed.
		$this->check_dismissed();

		// Early exit if $show is false.
		if ( ! $this->show ) {
			return;
		}

		// Add the notice.
		add_action( 'admin_notices', array( $this, 'the_notice' ) );

		// Enqueue the script.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Handle saving the data via ajax.
		add_action( 'wp_ajax_fusion_dismiss_admin_notice', array( $this, 'dismiss_notice' ) );

		// Mark as added.
		self::$already_added_ids[] = $id;
	}

	/**
	 * Check if we're on the right screen.
	 *
	 * @access private
	 * @since 1.5
	 * @return void
	 */
	private function check_screen() {
		if ( ! $this->screen || empty( $this->screen ) ) {
			return;
		}

		// Make sure the get_current_screen function exists before using it.
		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once ABSPATH . 'wp-admin/includes/screen.php';
		}
		$this->show     = false;
		$current_screen = get_current_screen();
		foreach ( $this->screen as $screen ) {
			if ( $current_screen['id'] === $screen ) {
				$this->show = true;
			}
		}
	}

	/**
	 * Check if the notice has been dismissed.
	 * If yes, then it sets $this->show to false.
	 *
	 * @access private
	 * @since 1.5
	 * @return void
	 */
	private function check_dismissed() {

		// Early exit if not dismissible.
		if ( ! $this->dismissible ) {
			return;
		}

		switch ( $this->dismiss_type ) {
			case 'user_meta':
				if ( $this->dismiss_option && get_user_meta( get_current_user_id(), $this->dismiss_option, true ) ) {
					$this->show = false;
				}
				break;
		}
	}

	/**
	 * The notice.
	 *
	 * @access public
	 * @since 1.5
	 * @return void Directly echoes the content.
	 */
	public function the_notice() {
		$attrs = array(
			'class' => 'notice fusion-notice notice-' . $this->type,
		);
		if ( $this->id ) {
			$attrs['id'] = $this->id;
		}
		if ( $this->dismissible && $this->dismiss_type && $this->dismiss_option ) {
			$attrs['data-dismissible']    = 'true';
			$attrs['data-dismiss-type']   = $this->dismiss_type;
			$attrs['data-dismiss-option'] = $this->dismiss_option;
			$attrs['class']              .= ' is-dismissible fusion-is-dismissible';
		}

		$attrs_html = '';
		foreach ( $attrs as $key => $val ) {
			$attrs_html .= ' ' . $key . '="' . esc_attr( $val ) . '"';
		}

		echo '<div' . $attrs_html . '>' . $this->content . '</div>'; // WPCS: XSS ok.
	}

	/**
	 * Enqueue the scripts.
	 *
	 * @access public
	 * @since 1.5
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script(
			'fusion-admin-notices',
			trailingslashit( Fusion_Scripts::$js_folder_url ) . 'general/fusion-admin-notice.js',
			array( 'jquery', 'common' ),
			time(),
			true
		);
		wp_localize_script( 'fusion-admin-notices', 'fusionAdminNoticesNonce', wp_create_nonce( 'fusion_admin_notice' ) );
	}

	/**
	 * Save the data when dismissing the notice.
	 *
	 * @access public
	 * @since 1.5
	 * @return void
	 */
	public function dismiss_notice() {

		check_ajax_referer( 'fusion_admin_notice', 'nonce' );

		if ( ! empty( $_POST ) && isset( $_POST['data'] ) ) {
			$option = '';
			if ( isset( $_POST['data']['dismissOption'] ) ) {
				$option = sanitize_text_field( wp_unslash( $_POST['data']['dismissOption'] ) );
			} elseif ( isset( $_POST['data']['dismiss-option'] ) ) {
				$option = sanitize_text_field( wp_unslash( $_POST['data']['dismiss-option'] ) );
			}

			$type = '';
			if ( isset( $_POST['data']['dismissType'] ) ) {
				$type = sanitize_text_field( wp_unslash( $_POST['data']['dismissType'] ) );
			} elseif ( isset( $_POST['data']['dismiss-type'] ) ) {
				$type = sanitize_text_field( wp_unslash( $_POST['data']['dismiss-type'] ) );
			}

			switch ( $type ) {
				case 'user_meta':
					update_user_meta( get_current_user_id(), $option, true );
					break;
			}
		}

		wp_die();
	}
}
