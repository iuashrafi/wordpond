<?php get_header();?>
<div class="container-fluid cont-margin cont-theme">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 clearfix visible-xs visible-sm visible-md visible-lg text-center" >
<div class="alert alert-success">
<img src="<?php echo get_template_directory_uri().'/multimedia/images/pagenotfound.png'; ?>" class="img-responsive" style="margin:auto;"/><br/>
<?php 
_e('Oops! The Page you requested was not found!<hr/>It looks like nothing was found at this location. Maybe try a search?', 'wordpond');
?>
</div>
<?php  get_search_form(); ?>
</div>
</div>
</div>
<?php get_footer();?>