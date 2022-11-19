<?php
$author_ID = get_the_author_meta('ID');
$title = get_the_title();
?>
<div class="bpost bshadow-2" id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
	<?php echo get_avatar( $author_ID , '46' , null, $alt='', array( 'class' => array( 'postAuthorImg' ) ) ) ;  ?>
	<div class="bcontent">
	<?php 
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
	<h6><?php the_author_posts_link(); ?> <small>&middot; <?php echo get_the_date('d F'); _e(' at ','wordpond'); the_time(); ?></small></h6>
	<p><?php  if(!is_search())the_content(); else { the_excerpt(); } ?></p>
	<p>
	<?php
	if( has_post_thumbnail() ) {
	the_post_thumbnail( 'large', array( 'class' => 'img-responsive' ) );	
	}
	?>
	</p>
	<div>
	<img style="height:15px;margin-top:-2px;" src="<?php echo get_template_directory_uri().'/multimedia/images/cmt.png'; ?>" title="Comments count"/><?php echo get_comments_number(); ?>
	&middot; <?php 
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		echo '<small>'. $categories[0]->name .'</small>';   
	}
	$posttags = get_the_tags();
	if ($posttags) {
		echo '&nbsp;&middot;&nbsp;<small>';
		foreach($posttags as $tag) {
		echo '<span class="label label-default">'.$tag->name . '</span> '; 
		}
		echo '</small>';
	}
	?>
	</div>
	<?php
	if ( is_single() ) {
	echo "<hr/>";
	}
	if ( comments_open() || get_comments_number() ) :
	comments_template();
	endif; 
	?>
	</div>
</div>