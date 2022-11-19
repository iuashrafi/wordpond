<?php get_header(); ?>
<div class="container cont-margin">
<div class="row">
<div class="col-xs-10 col-sm-6 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-3  col-md-offset-4  col-lg-offset-4 clearfix visible-xs visible-sm visible-md visible-lg" style="background-color:;padding:0;">
<?php 
if(class_exists('MySignin') && !is_user_logged_in())
{
	$sign = new MySignin();
	$sign->signin_registeration();
}
?>
</div>
</div>
</div>
<?php get_footer();?>