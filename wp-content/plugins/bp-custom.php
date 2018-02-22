<?php 
/** BuddyPress Customizations **/


function set_bp_message_content_type() {
    return 'text/html';
}
 
add_filter( 'bp_core_signup_send_validation_email_message', 'custom_buddypress_activation_message', 10, 3 );
 
function custom_buddypress_activation_message( $message, $user_id, $activate_url ) {
    add_filter( 'wp_mail_content_type', 'set_bp_message_content_type' );
    $user = get_userdata( $user_id );
    return '<p>Hi <strong>' . $user->user_login . '</strong>,</p>

<p>Thanks for registering on the Frankenreads site! Please <a href="' . $activate_url . '">click this link to activate your account</a>.</p>

<p>Once you activate your account, you will automatically be listed on <a href="http://frankenreads.org/partners">our Partners page</a> and will automatically be subscribed to our email list. We plan to send updates no more than monthly up until late summer of 2018, as Frankenweek (October 24-31) approaches.</p> 

<p>When you are logged in, you can message other Partners privately via the link on their individual profile and you can submit a Frankenreads readathon or other event at <a href="http://frankenreads.org/submit">http://frankenreads.org/submit</a>. Your event submission will be reviewed, and once it is approved, it will be listed on our site at <a href="http://frankenreads.org/events">http://frankenreads.org/events</a> and automatically tweeted from our <a href="http://twitter.com/frankenreads">@frankenreads</a> Twitter account.</p> You can always edit your event if details change.</p> 

<p>We accept Frankenstein-related events of nearly any kind held on any date, but we strongly encourage you to plan readathons for Frankenweek (again, October 24-31, 2018),  when many other <a href="http://frankenreads.org/partners">organizations around the world</a> will be celebrating the 200th anniversary of the great novel by Mary Shelley in this fashion.</p> 

<p>Want event planning help? See our <a href="http://frankenreads.org/resources">Resources pages</a> for recommended texts and more, and/or email us at <a href="mailto:info@frankenreads.org">info@frankenreads.org</a>. Happy Frankenreading!<p> 

<p>Cheers,</p>

<p>Amanda French<br />
Frankenreads Community Manager</p>
';}

?>