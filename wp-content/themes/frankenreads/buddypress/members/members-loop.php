<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the display of the members loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_get_current_member_type() ) : ?>
	<p class="current-member-type"><?php bp_current_member_type_message() ?></p>
<?php endif; ?>


<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) . '&per_page=500' . '&populate_extras&type=alphabetical' . '&exclude=1,2,3,4,6') ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_members_list' ); ?>

	<?php /** 
	* Begin custom members table 
	*/
	?>
	
<table id="members-list" class="item-list" aria-live="assertive" aria-relevant="all">
	<thead>
	<tr class="table-row">
    <th>Name</th>
    <th>Organization</th>
    <th>City</th>
    <th>State, Province, Region</th>
    <th>Country</th>
    </tr>
    </thead>
	<?php while ( bp_members() ) : bp_the_member(); ?>

		<tr class="item" <?php bp_member_class(); ?>>
<!--			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
			</div>
-->		
				<td class="partner-item-name">
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>

<!--					<?php if ( bp_get_member_latest_update() ) : ?>

						<td class="update"> <?php bp_member_latest_update(); ?></td>

					<?php endif; ?>
-->
				</td>

<!--				<div class="item-meta"><td class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => false ) ) ); ?>"><?php bp_member_last_active(); ?></td></div>
-->
				<?php

				/**
				 * Fires inside the display of a directory member item.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_directory_members_item' ); ?>

				<?php
				 /***
				  * If you want to show specific profile fields here you can,
				  * but it'll add an extra query for each member in the loop
				  * (only one regardless of the number of fields you show):
				  *
				  * bp_member_profile_data( 'field=the field name' );
				  */
				?>
				
				<td class="partner-item-org"><?php bp_member_profile_data( 'field=Organization'); ?></td>
				
				<td class="partner-item-city"><?php bp_member_profile_data( 'field=City'); ?></td>
				
				<td class="partner-item-state"><?php bp_member_profile_data( 'field=State, Province, or Region'); ?></td>
				
				<td class="partner-item-country"><?php bp_member_profile_data( 'field=Country'); ?></td>

		</tr>

	<?php endwhile; ?>

</table>						

			<div class="action">

				<?php

				/**
				 * Fires inside the members action HTML markup to display actions.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_directory_members_actions' ); ?>

			</div>

			<div class="clear"></div>
	
	<?php /** 
	* End custom members table 
	*/
	?>

	<?php

	/**
	 * Fires after the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of the members loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_members_loop' );
