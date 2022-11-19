<?php get_header();?>
<div class="container-fluid cont-theme cont-margin" >
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 clearfix visible-xs visible-sm visible-md visible-lg">
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 clearfix visible-xs visible-sm visible-md visible-lg" style="background-color:;">
<?php 
$is_user_logged_in = is_user_logged_in();
if($is_user_logged_in)
{
	$usersearch = stripslashes( trim($_GET['usersearch']) );
	if( !empty($usersearch ) ){
		global $wpdb;
		$stmt = $wpdb->prepare("SELECT user_id FROM $wpdb->usermeta AS um
		WHERE ( um.meta_key='first_name' AND um.meta_value LIKE '%%%s%%') OR
		(um.meta_key='last_name' AND um.meta_value LIKE '%%%s%%') OR
		(um.meta_key='nickname' AND um.meta_value LIKE '%%%s%%')
		ORDER BY um.meta_value  
		LIMIT 150",$usersearch, $usersearch, $usersearch );
		$results = $wpdb->get_col( $stmt );
	}
	$st = (isset($_GET['usersearch']) ? $_GET['usersearch'] : '' );
	?>
	<form method="get" style="margin-bottom:10px;">
	<div class="input-group input-lg">
		<input name="usersearch" id="usersearch" value="<?php echo $st; ?>" class="form-control" placeholder="Search Friends " type="text" /><div class="input-group-btn"><button class="btn btn-success ripple" type="submit" name="dosearch" value="Submit"><i class="glyphicon glyphicon-search"></i></button></div>
	</div>	
	</form>

    <?php
		if(!is_null($results))
		{
			$results = array_unique($results);
			$ct= count($results);
			echo '<p><small>About '.$ct.' results</small></p>';
			
			echo '<ul class="bcomments bcomment-notify ">';
			foreach($results as $user_id){
				$user_info = get_userdata($user_id);
			?>
		   <li class="bcomment">  
				<?php echo get_avatar($user_id,40); ?>
				<div class="bcomment-cont">
				<strong><?php 
				if(!empty($user_info->display_name)){
				echo esc_attr($user_info->display_name);
				}
				else if(!empty($user_info->first_name)){
				echo esc_attr($user_info->first_name).' '.esc_attr($user_info->last_name);
				}
				?></strong> 
				<p><?php 
				if(!empty($user_info->description)){
				echo esc_attr($user_info->description);
				}
				?></p>
				<p style="margin-top:4px;">
					<?php  
						if($is_user_logged_in && class_exists('Wordpond_relations'))
						{
						$wprel = new Wordpond_relations();
						$wprel->display_add_friend($user_id);
						} 
					?>
				</p>
				</div>
		   </li>
		   <?php
			}
			echo '</ul>';
		}	
}
else if(class_exists('Wordpond_UI'))
{
	Wordpond_UI::display_login_suggestion();
}
?>
</div>
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 clearfix visible-xs visible-sm visible-md visible-lg">
</div>
</div>
</div>
<?php
get_footer();
?>