<?php 
/*Two types of security- one is to hide the user information for 24 hours , another disable delete button for 48 hours*/
$seconds = current_time( 'timestamp' ) - get_the_time('U');
$anon = false;
$disable = true;
if($seconds < 86400){	// hide post author till 24 hours
	$anon = true;
}
if ( $seconds > 172800 ){	// post edit and delete only after 48 hours 
	$disable = false;
}
if(!isset($current_user_ID)){
$current_user_ID = get_current_user_id();
}
$author_ID = get_the_author_meta('ID');
?>
<div class="bpost bshadow-2" id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
<?php
	if($anon){
		echo '<img src="'.get_template_directory_uri().'/multimedia/images/unknown.jpg" srcset="'.get_template_directory_uri().'/multimedia/images/unknown.jpg 2x" class="avatar avatar-46 photo postAuthorImg" height="46" width="46" />';
	}
	else{
	echo get_avatar( $author_ID , '46' , null, $alt='', array( 'class' => array( 'postAuthorImg' ) ) ) ; 
	} 
	?>
	<div class="bcontent">
		<div class="dropdown pull-right">
		<a href="#" class="dropdown-toggle ripple" data-toggle="dropdown"><i class="glyphicon glyphicon-option-vertical"></i></a>
		<ul class="dropdown-menu" role="menu">
		<?php 
		if($author_ID == $current_user_ID )
		{
			if($disable) // if true then dont show delete
			{
				echo '<li><a type="button" data-toggle="modal" data-target="#editModal">Edit</a></li>';
				echo '<li><a type="button" data-toggle="modal" data-target="#deleteModal">Delete</a></li>';
			}
			else 
			{
				echo '<li><a type="button" href="'.get_edit_post_link().'" >Edit</a></li>';
				echo '<li><a  href="'.get_delete_post_link().'" type="button" >Delete</a></li>';
				
			}
		}
		else
		{
			?>
			<li><a type="button"  data-post_id="<?php the_ID(); ?>" class="reportbox">Report</a></li>
			<?php 
		}
		?>
		</ul>
		</div>
	<?php 
	$title = get_the_title();
	if (empty($title)) { 
	?>
	<div class="btitle"><a href="<?php the_permalink(); ?>">untitled</a></div>
	<?php		
	} else { 
	?>
	<div class="btitle"><a href="<?php the_permalink(); ?>"><?php echo $title;?></a></div>
	<?php
	} 
	?>
	<h6><?php if(!$anon) { the_author(); } ?> <small><?php if(!$anon) { echo '&middot;'; } ?> <?php echo get_the_date('dS F'); _e(' at ','wordpond'); the_time(); ?></small></h6>
	<p><?php  if(!is_search())the_content(); else { the_excerpt(); } ?></p>
	<p>
	<?php
	if( has_post_thumbnail() ) {
	the_post_thumbnail( 'large', array( 'class' => 'img-responsive' ) );	
	}
	?>
	</p>
	<div>
	<img style="height:15px;margin-top:-2px;" src="<?php echo get_template_directory_uri().'/multimedia/images/cmt.png'; ?>" title="Comments count"/><?php $comments_counter = get_comments_number(); echo $comments_counter ; ?>
	</div>
	<?php
	if ( comments_open() || get_comments_number() ) :
		if ($comments_counter >0){ echo "<hr/>"; }
		comments_template();
	endif; 
	?>
	</div>
</div>