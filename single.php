<?php get_header();?>
<div class="container-fluid cont-margin cont-theme">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 clearfix visible-xs visible-sm visible-md visible-lg"></div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 clearfix visible-xs visible-sm visible-md visible-lg" >
<?php	
if(have_posts())
{
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
}		?>
</div>
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 clearfix visible-xs visible-sm visible-md visible-lg" >
<?php get_sidebar(); ?>
</div>
</div>
</div>
<?php get_footer();?>