<?php get_header(); ?>
<div class="container" style="margin-top:80px;">
<div class="row">
<div class="col-xs-10 col-sm-6 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-3  col-md-offset-4  col-lg-offset-4 clearfix visible-xs visible-sm visible-md visible-lg" style="padding:0;">
	<style>
	
	</style>
		<?php 	
		if(!empty($errors)) {
			foreach($errors as $err )
			echo '<div class="danger">'.$err.'</div>';
		}
		if(!is_user_logged_in()) : ?>
	
	<div class="panel panel-success login-form bshadow-4 border-none" >
	<div class="panel-body login-text">
		<form name="loginform" id="loginform" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<h3 class="text-center" >Log in</h3>
				<hr class="message-inner-separator" />
			<div class="form-group has-feedback">
			<input type="text" name="log" id="user_login" class="form-control form-control2 login-field" placeholder="Username" />
			<i class="form-control-feedback form-login-icon glyphicon glyphicon-user"></i>
			</div>
			<div class="form-group has-feedback">
			<input type="password" name="pwd" id="user_pass" class="form-control form-control2 login-field  " placeholder="Password" />
			<i class="form-control-feedback form-login-icon glyphicon glyphicon-lock"></i>
			</div>
			<div class="form-group">
			<?php wp_nonce_field( 'wordpond_login_nonce','login_wpnonce') ?>
			<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-login btn-lg btn-block" value="Log in" />
			<input type="hidden" name="redirect_to" value="<?php echo site_url(); ?>" />
			</div>
			<a class="login-link" href="<?php echo WPOND_SIGNUP_PAGE; ?>" style="color:#08386A;">Don't have an account ? Create one now.</a>
			<!-- <a class="login-link" href="#">Lost your password?</a>-->
		</form>
		
	</div>
	</div>
	
	<?php endif; ?>
</div>
</div>
</div>
<?php get_footer(); ?>