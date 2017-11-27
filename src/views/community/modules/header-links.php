<?php
// Don't load directly
defined( 'WPINC' ) or die;

/**
 * Header links for edit forms.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/modules/header-links.php
 *
 * @since  3.1
 * @version 4.5
 *
 */
?>

<header class="my-events-header">
	<h2 class="my-events">
		<?php
		if ( get_the_ID() ) {
			esc_html_e( 'Edit Event', 'tribe-events-community' );
		} else {
			esc_html_e( 'Add New Event', 'tribe-events-community' );
		}
		?>
	</h2>

	<?php if ( is_user_logged_in() ) : ?>
	<a
		href="<?php echo esc_url( tribe_community_events_list_events_link() ); ?>"
		class="tribe-button tribe-button-secondary"
	>
		<?php esc_html_e( 'View Your Submitted Events', 'tribe-events-community' ); ?>
	</a>
	<?php endif; ?>
</header>

<?php echo tribe_community_events_get_messages();
