<?php
if ( post_password_required() )
	return;
?>
<div id="comments">
<?php 
if ( have_comments() ) 
{
	echo '<ul class="bcomments">';
	wp_list_comments( array(
	'style'         => '',
	'short_ping'    => true,
	'avatar_size'   => '42',
	'callback'  => 'wordpond_media_cmt'
	) );
	echo '</ul>';
	
	if ( ! comments_open() && get_comments_number() )
	{
		echo '<p class="no-comments">';
		_e( 'Comments are closed.' , 'wordpond' ); 
		echo '</p>';
	}
} // have_comments closing 
$aria_req ="required";
$fields =  array(
  'author' =>
    '<p class="comment-form-author"><div class="form-group"><label for="author">' . __( 'Name', 'wordpond' ) . '</label> ' .
    ( $req ? '<span class="required">*</span>' : '' ) .
    '<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
    '" ' . $aria_req . ' /></div></p>',

  'email' =>
    '<p class="comment-form-email"><div class="form-group"><label for="email">' . __( 'Email', 'wordpond' ) . '</label> ' .
    ( $req ? '<span class="required">*</span>' : '' ) .
    '<input id="email" name="email" class="form-control" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
    '" ' . $aria_req . ' /></div></p>',

  'url' =>
    '<p class="comment-form-url"><div class="form-group"><label for="url">' . __( 'Website', 'wordpond' ) . '</label>' .
    '<input id="url" name="url" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
    '"   /></div></p>',
);

$comments_args = array(
		// 'label_submit'=>'Post',
		'title_reply'=>'',	// change "Leave a Reply" to "Comment"
		'title_reply_to'=> __( 'Leave a reply to %s ', 'wordpond'),
		'title_reply_before' => '<h5 id="reply-title" class="comment-reply-title">',
		'title_reply_after' => '</h5>',
		// 'cancel_reply_before'=> '',
		// 'cancel_reply_after'=> '', 
		'cancel_reply_link' =>__('Cancel Reply', 'wordpond'),
		'class_submit'=>'btn btn-success btn-sm',
		'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field' =>'<p class="comment-form-comment"><div class="form-group"><input type="text"  id="" name="comment" placeholder="Write a comment" value="" class="form-control mention med" aria-required="true" /> </div></p>',
		'comment_notes_after' =>''
);

comment_form($comments_args);
	 $pages = paginate_comments_links(array('echo' => false, 'type' => 'array') );

      if( is_array( $pages ) ) {
        $output = '';
        foreach ($pages as $page) {
          $page = "\n<li>$page</li>\n";
          if (strpos($page, ' current') !== false) 
            $page = str_replace( array(' current', '<li>') , array('', '<li class="active">'), $page);
          $output = $output.$page;
        }
        ?>
        <nav aria-label="Comment navigation">
            <ul class="pagination pagination-sm">
                <?php  echo $output; ?>
            </ul>
        </nav>
    <?php
      }
?>
</div>