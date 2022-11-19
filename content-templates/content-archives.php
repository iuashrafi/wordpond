<div class="postArea"> 
<?php 
if(have_posts())
{
	if(is_category())
	{
		echo '<div class="alert alert-info">Category&nbsp;:&nbsp;';
		single_cat_title();
		echo '</div>';
	}
	while(have_posts())
	{
		the_post();
		get_template_part( 'content-templates/content', 'post' );	
	}
	wpond_pagination();
}
else 
{
	_e( '<div class="alert alert-info">It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.</div>', 'wordpond' );
}
?>
</div>