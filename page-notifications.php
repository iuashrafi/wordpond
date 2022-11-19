<?php get_header(); ?>
<div class="container-fluid cont-margin cont-theme">
<div class="row">
<div class="col-xs-12 col-sm-12  col-md-3 col-lg-3  clearfix visible-xs visible-sm visible-md visible-lg">
<?php
$is_user_logged_in = is_user_logged_in();
if($is_user_logged_in && class_exists('Wordpond_UI'))
{
	$ui= new Wordpond_UI(get_current_user_ID());
	$ui->author_card();
	$ui->author_menus();
}
?>
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 clearfix visible-xs visible-sm visible-md visible-lg" >
<?php 
if($is_user_logged_in && class_exists('Wordpond_notifications')){
	(new Wordpond_notifications())->display_notifications();
}
else if(class_exists('Wordpond_UI')){
	Wordpond_UI::display_login_suggestion();
}
?>	
</div>
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3  clearfix visible-xs visible-sm visible-md visible-lg" >
<?php get_sidebar(); ?>
</div>
</div>
</div>
<?php get_footer(); ?>