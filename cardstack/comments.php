<?php
// Do not delete these lines
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.'); ?></p>
	<?php
		return;
	}
?>

<?php

	/* You can start editing here */

	if ( have_comments() ) : ?>
	<h2 id="comments"><?php	printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number() ), number_format_i18n( get_comments_number() ), '&#8220;' . get_the_title() . '&#8221;' ); ?></h2>

	<ol class="commentlist">
	<?php wp_list_comments();?>
	</ol>

	<?php
	$cardstack_previousLink = get_previous_comments_link();
	$cardstack_nextLink = get_next_comments_link();
	if(!empty($cardstack_previousLink) || !empty($cardstack_nextLink)) :
	?>
	<nav class="page-switcher">
		<p><?php previous_comments_link(); if(!empty($cardstack_previousLink) && !empty($cardstack_nextLink)) : ?> &mdash; <?php endif;	next_comments_link(); ?></p>
	</nav>
	<?php
	endif;

	//if there are no comments so far, leave out the clutter
	/* else : 
		if ( comments_open() ) {

		} else {
			print('<p class="nocomments">' . _e('Comments are closed.') . '</p>');
		} */
	endif;
?>

<?php if ( comments_open() ) : ?>

<?php

$cardstack_commenter = wp_get_current_commenter();
$cardstack_req = get_option( 'require_name_email' );
$cardstack_aria_req = ( $cardstack_req ? " aria-required='true'" : '' );

$cardstack_comment_args = array(
	  'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',

	  'fields' => apply_filters( 'comment_form_default_fields', array(

		'author' =>
		  '<p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr( $cardstack_commenter['comment_author'] ) .
		  '" size="30"' . $cardstack_aria_req . ' /> ' . ( $cardstack_req ? '<span class="required">* </span>' : '' ) . '<label for="author">' . __( 'Name' ) . '</label></p>',

		'email' =>
		  '<p class="comment-form-email"><input id="email" name="email" type="text" value="' . esc_attr(  $cardstack_commenter['comment_author_email'] ) .
		  '" size="30"' . $cardstack_aria_req . ' /> ' . ( $cardstack_req ? '<span class="required">* </span>' : '' ) . '<label for="email">' . __( 'Email' ) . '</label></p>',

		'url' =>
		  '<p class="comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr( $cardstack_commenter['comment_author_url'] ) .
		  '" size="30" /> <label for="url">' .  __( 'Website' ) . '</label></p>'

		)
	  )
);

	comment_form($cardstack_comment_args);

?>

<?php endif; // if you delete this the sky will fall on your head ?>
