<?php
/**
 * BuddyPress - Members
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires at the top of the members directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_before_directory_members_page' ); ?>

<div id="buddypress">

	<?php

	/**
	 * Fires before the display of the members.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_members' ); ?>

	<?php

	/**
	 * Fires before the display of the members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_members_content' ); ?>

	<?php /* Backward compatibility for inline search form. Use template part instead. */ ?>
	<?php if ( has_filter( 'bp_directory_members_search_form' ) ) : ?>

		<div id="members-dir-search" class="dir-search" role="search">
			<?php bp_directory_members_search_form(); ?>
		</div><!-- #members-dir-search -->

	<?php else: ?>

		<?php bp_get_template_part( 'common/search/dir-search-form' ); ?>

	<?php endif; ?>

<p id="partner-intro"><a href="../join">Join the Community</a> to become a partner listed on this page. Click on someone's name to see that person's events. If you are logged in, you can also send them a message, which they will receive by email if their settings allow.<br /><br />

Please also visit our Project Partners, who are also organizing large-scale celebrations of <em>Frankenstein</em>: 

<ul>
<li>ASU Center for Science and the Imagination: <a href="https://frankenreads.org/partners/asu-center-for-science-and-the-imagination/">Frankenbook and Frankenstein200 the Game</a></li>
<li>Indiana Humanities Council: <a href="https://frankenreads.org/partners/one-state-one-story/">One State, One Story</a></li>
<li><a href="https://www.manyconversations.org/">One Book, Many Conversations</a></li>
</ul>

</p>


	<?php
	/**
	 * Fires before the display of the members list tabs.
	 *
	 * @since 1.8.0
	 */
	do_action( 'bp_before_directory_members_tabs' ); ?>

	<form action="" method="post" id="members-directory-form" class="dir-form">

<!--		<div class="item-list-tabs" aria-label="<?php esc_attr_e( 'Members directory main navigation', 'buddypress' ); ?>" role="navigation">
			<ul>
				<li class="selected" id="members-all"><a href="<?php bp_members_directory_permalink(); ?>"><?php printf( __( 'All Members %s', 'buddypress' ), '<span>' . bp_get_total_member_count() . '</span>' ); ?></a></li>

				<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
					<li id="members-personal"><a href="<?php echo esc_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ); ?>"><?php printf( __( 'My Friends %s', 'buddypress' ), '<span>' . bp_get_total_friend_count( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
				<?php endif; ?>

				<?php

				/**
				 * Fires inside the members directory member types.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_members_directory_member_types' ); ?>

			</ul> 
		</div> --> 
		<!-- .item-list-tabs -->
		
<!-- REMOVE SORT TYPE DROPDOWN 

		<div class="item-list-tabs" id="subnav" aria-label="<?php esc_attr_e( 'Members directory secondary navigation', 'buddypress' ); ?>" role="navigation">
			<ul>
				<?php

				/**
				 * Fires inside the members directory member sub-types.
				 *
				 * @since 1.5.0
				 */
				do_action( 'bp_members_directory_member_sub_types' ); ?>

				<li id="members-order-select" class="last filter">
					<label for="members-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
					<select id="members-order-by">
						<?php _e( 'Last Active', 'buddypress' ); ?></option>
						<option value="newest"><?php _e( 'Recently Registered', 'buddypress' ); ?></option>

						<?php if ( bp_is_active( 'xprofile' ) ) : ?>
							<option value="alphabetical"><?php _e( 'Name', 'buddypress' ); ?></option>
						<?php endif; ?>

						<?php

						/**
						 * Fires inside the members directory member order options.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_members_directory_order_options' ); ?>
					</select>
				</li>
			</ul>
		</div>
REMOVE SORT TYPE DROPDOWN -->

		<h2 class="bp-screen-reader-text"><?php
			/* translators: accessibility text */
			_e( 'Members directory', 'buddypress' );
		?></h2>
	
		<div id="members-dir-list" class="members dir-list">
			<?php bp_get_template_part( 'members/members-loop' ); ?>
		</div>
		
		
		<!-- #members-dir-list -->

		<?php

		/**
		 * Fires and displays the members content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_directory_members_content' ); ?>

		<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

		<?php

		/**
		 * Fires after the display of the members content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_directory_members_content' ); ?>

	</form><!-- #members-directory-form -->

	<?php

	/**
	 * Fires after the display of the members.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_members' ); ?>

</div><!-- #buddypress -->

<?php

/**
 * Fires at the bottom of the members directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_directory_members_page' );
