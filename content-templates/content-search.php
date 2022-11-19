<div class="postArea"> 
<?php 
$s=get_search_query();
$args = array(
's' =>$s
);
$the_query = new WP_Query( $args );
if(have_posts($args))
{
	printf( __( '<div class="alert alert-success">Displaying results for : %s </div>', 'wordpond' ), '<span>' . $s . '</span>' );
	while(have_posts($args))
	{
		the_post();
		get_template_part( 'content-templates/content', 'post_data' );	
	}
	wpond_pagination();
}
else 
{
	_e( '<div  class="alert alert-danger" >Sorry, but nothing matched your search terms. Please try again with some different keywords.</div>', 'wordpond' );
}
?>
</div>