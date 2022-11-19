<?php
/**
TABLE OF CONTENTS
1. Wordpond setup and Scripts
2. Wordpond Login
3. Wordpond Sign up
4. Wordpond User Interface
5. Wordpond Comments
6. Wordpond Sidebar
7. Wordpond Pagination
8. Wordpond Custom navigation
*/
function wordpond_logout_redirect() {
	wp_redirect( site_url() );  
	exit();  
}
add_action('wp_logout','wordpond_logout_redirect');
/* Remove etcs */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');
// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/* Wordpond Setup and Scripts */
function wordpond_setup() {
	load_theme_textdomain( 'wordpond', get_template_directory() . '/languages' );
	/*
	 * Let WordPress manage the document title.
	 */
	add_theme_support( 'title-tag' );
	/**
	* Add default posts and comments RSS feed links to <head>.
	*/
	add_theme_support( 'automatic-feed-links' );

	/**
	* Enable support for post thumbnails and featured images.
	*/
	add_theme_support( 'post-thumbnails' );

	/**
	* Add support for two custom navigation menus.
	*/
	register_nav_menus( array(
	'primary'   => __('Primary Menu', 'wordpond' ),
	'secondary' => __('Secondary Menu', 'wordpond' )
	) );

	/**
	* Enable support for the following post formats:
	* aside, gallery, quote, image, and video
	*/
	 add_theme_support( 'post-formats', array (  'quote', 'image' ) );
	 
	 $GLOBALS['content_width'] = 1250;
}
add_action( 'after_setup_theme', 'wordpond_setup' );
show_admin_bar(false);
/*
function wordpond_styles_scripts()
{
	wp_enqueue_style( 'style', get_stylesheet_uri() ,null ,'1.2','all');
	wp_enqueue_style( 'base-style',  get_template_directory_uri() . '/css/base-style.css' ,null ,'1.0.0','all' );
	wp_enqueue_style( 'bootstrap-min','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',null,'3.3.7' );
	// Bootstrap Min js
	wp_register_script('bootstrap-min-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), '3.3.7', true ); // true puts the script in footer
	
	
	wp_enqueue_script( 'bootstrap-min-js');
	
}
add_action( 'wp_enqueue_scripts', 'wordpond_styles_scripts' );
 ----------------- */

/*	for localhost 
function wordpond_scripts()
{
wp_enqueue_style( 'style', get_stylesheet_uri() );
wp_enqueue_style( 'base-style', get_template_directory_uri() . '/css/base-style.css',false,'1.2','all');
wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css',false,'1.0.0','all');
wp_enqueue_style( 'bootstrap.min', get_template_directory_uri() . '/css/bootstrap.min.css',false,'3.3.7','all');
wp_enqueue_script( 'bootstrap.min', get_template_directory_uri() . '/js/bootstrap.min.js', array ( 'jquery' ),'3.3.7', true);
}
add_action( 'wp_enqueue_scripts', 'wordpond_scripts' );
*/
/**
* Wordpond Login
*/
if(isset($_POST['wp-submit'])) {
	if(!wp_verify_nonce($_POST['login_wpnonce'],'wordpond_login_nonce'))
	{
		die("Oops, your nonce didn't verify. So there.");
	}
	else 
	{
		$creds                  = array();
		$creds['user_login']    = stripslashes( trim( $_POST['log'] ) );
		$creds['user_password'] = stripslashes( trim( $_POST['pwd'] ) );
		$creds['remember']      = isset( $_POST['rememberme'] ) ? sanitize_text_field( $_POST['rememberme'] ) : '';
		$redirect_to            = esc_url_raw( $_POST['redirect_to'] );
		$secure_cookie          = null;
			
		if($redirect_to == '')
			$redirect_to= get_site_url(). '/dashboard/' ; 
			
			if ( ! force_ssl_admin() ) {
				$user = is_email( $creds['user_login'] ) ? get_user_by( 'email', $creds['user_login'] ) : get_user_by( 'login', sanitize_user( $creds['user_login'] ) );

			if ( $user && get_user_option( 'use_ssl', $user->ID ) ) {
				$secure_cookie = true;
				force_ssl_admin( true );
			}
		}

		if ( force_ssl_admin() ) {
			$secure_cookie = true;
		}

		if ( is_null( $secure_cookie ) && force_ssl_admin() ) {
			$secure_cookie = false;
		}

		$user = wp_signon( $creds, $secure_cookie );

		if ( $secure_cookie && strstr( $redirect_to, 'wp-admin' ) ) {
			$redirect_to = str_replace( 'http:', 'https:', $redirect_to );
		}

		if ( ! is_wp_error( $user ) ) {
			wp_redirect( $redirect_to );
			// header("Location: ".$_SERVER['REQUEST_URI']);
			exit; // exit cmd is very important here
		} else {			
			if ( $user->errors ) {
				$errors['invalid_user'] = __('<strong>ERROR</strong>: Invalid username or password.', 'wordpond'); 
			} else {
				$errors['invalid_user_credentials'] = __( 'Please enter your username and password to login.', 'wordpond' );
			}
		}
	}
}
/**
* Wordpond Sign up 
*/
class MySignin
{
	public $username, $email, $password, $cpassword;
	public $reg_errors;
	function __construct()
	{
		$this->reg_errors = new WP_Error;
	}
	/**
	*	signin_registeration function is the main function which calls all the functions( for signIn form, form validation and user data insertion) required for user registeration 
	*/
	public function signin_registeration()
	{
		if ( isset($_POST['submit'] )  ) {
		if( wp_verify_nonce($_POST['signup_wpnonce'],'wordpond_signup_nonce') ){
        $this->signin_validate(
        $_POST['username'],
		$_POST['email'],
        $_POST['password'],
        $_POST['cpassword']
        );
         
        // Sanitizing user's input
        $this->username   =   sanitize_user( $_POST['username'] );
		$this->email      =   sanitize_email( $_POST['email'] );
        $this->password   =   esc_attr( $_POST['password'] );
        $this->cpassword   =   esc_attr( $_POST['cpassword'] );
		
        // call @function complete_registration to create the user
        // only when no WP_error is found
        $this->register_user($this->username,$this->email,$this->password,$this->cpassword);
		
		}
		
		}
		$this->signin_form($this->username,$this->email,$this->password,$this->cpassword);
	}
	public function signin_form($username,$email,$password,$cpassword)
	{
			if ( !empty( $this->reg_errors->errors )   ) {
			$this->signin_error();
			}
			?>
	<div class="panel panel-default login-form bshadow-4 border-none">
	<div class="panel-body login-text">
	<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
			<h3 class="text-center" >Sign up</h3>
			<h5 class="text-center" >Lets get you setup</h5>
			<hr class="message-inner-separator" />
			<div class="form-group has-feedback">
			<input type="text" name="username" id="" class="form-control form-control2 login-field" placeholder="Username" value="<?php echo ( isset( $_POST['username'] ) ? $this->username : null ) ?>" />
			<i class="form-control-feedback form-login-icon glyphicon glyphicon-user"></i>
			</div>
			
			<div class="form-group has-feedback">
			<input type="text" name="email" id="" class="form-control form-control2 login-field" placeholder="Email id" value="<?php echo ( isset( $_POST['email'] ) ? $this->email : null ); ?>" />
			<i class="form-control-feedback form-login-icon glyphicon glyphicon-envelope"></i>
			</div>
			
			<div class="form-group has-feedback">
			<input type="password" name="password" id="" class="form-control form-control2 login-field" placeholder="Password"  value="<?php echo ( isset( $_POST['password'] ) ? $this->password : null ); ?>" />
			<i class="form-control-feedback form-login-icon glyphicon glyphicon-lock"></i>
			</div>
			
			
			<div class="form-group has-feedback">
			<input type="password" name="cpassword" id="" class="form-control form-control2 login-field" placeholder="Confirm password"  value="<?php echo ( isset( $_POST['cpassword'] ) ? $this->cpassword : null ); ?>" />
			<i class="form-control-feedback form-login-icon glyphicon glyphicon-lock"></i>
			</div>
			
			<div class="form-group">
			<?php wp_nonce_field( 'wordpond_signup_nonce','signup_wpnonce') ?>
			<button type="submit"  name="submit" value="sigin"  class="btn btn-login btn-lg btn-block" >Sign up</button>
		
		</div>	
		 <p class="text-center">New users will be assigned roles as "Wordpond User".</p>
		 </form>
	</div>
	</div>
	<?php
	}
	/* depreciated  */
	public function signin_form2($username,$email,$password,$cpassword)
	{
	?>
	<div class="panel panel-default bshadow-3">
	<div class="panel-body">
	<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
		<h3 class="text-center">Sign up</h3>
		<hr/>
		<?php 
		if ( !empty( $this->reg_errors->errors )   ) {
		$this->signin_error();
		}
		?>
		<div class="form-group">
			<label>Username <span class="text-danger">*</span></label>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input name="username" id="" type="text" class="form-control" value="<?php echo ( isset( $_POST['username'] ) ? $this->username : null ) ?>" />
			</div>
		</div>
		<div class="form-group">
			<label>Emailid <span class="text-danger">*</span></label>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
				<input name="email" id="" type="text" value="<?php echo ( isset( $_POST['email'] ) ? $this->email : null ); ?>" class="form-control" />
			</div>
		</div>
				
		<div class="form-group">
			<label>Password <span class="text-danger">*</span></label>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input name="password" id="" type="password"  value="<?php echo ( isset( $_POST['password'] ) ? $this->password : null ); ?>"  class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label>Confirm Password <span class="text-danger">*</span></label>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input name="cpassword" id="" type="password"  value="<?php echo ( isset( $_POST['cpassword'] ) ? $this->cpassword : null ); ?>"  class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<?php wp_nonce_field( 'wordpond_signup_nonce','signup_wpnonce') ?>
			<button class="btn btn-success bshadow-2" name="submit" value="sigin" type="submit">Sign up</button>
		</div>	
		 <p class="text-center">New users will be assigned roles as Suscribers.</p>
		</form>
	</div>
	</div>
	<?php 
	}
	public function signin_validate($username,$email,$password,$cpassword)
	{
		if ( empty( $username ) || empty( $email ) || empty( $password ) || empty($cpassword) ) {
			$this->reg_errors->add('field', 'Please fill all the form fields');
		}
		else {
			
		if ( 4 > strlen( $username ) ) {
			$this->reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
		}
		else if ( username_exists( $username ) )
			$this->reg_errors->add('user_name', 'Sorry, that username already exists!');
		else if ( ! validate_username( $username ) ) {
			$this->reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
		}

		if ( 5 > strlen( $password ) ) {
			$this->reg_errors->add( 'password', 'Password length must be greater than 5' );
		}
		else if(5 > strlen( $cpassword ) || $cpassword != $password || strlen( $password )!=strlen( $cpassword ) ) {
			$this->reg_errors->add( 'password', 'Passwords does not matches' );
		}
	
	
		if ( !is_email( $email ) ) {
			$this->reg_errors->add( 'email_invalid', 'Email is not valid' );
		}
		else if ( email_exists( $email ) ) {
			$this->reg_errors->add( 'email', 'Email Already in use' );
		}
		} // else closing for first err check
	}
	private function signin_error()
	{
		if ( is_wp_error( $this->reg_errors )   ) {
			echo '<div class="danger">';
			foreach ( $this->reg_errors->get_error_messages() as $error ) {
			echo $error . '<br/>';
			}
			echo '</div>';
		}
	}
	public function register_user($username,$email,$password,$cpassword)
	{
		if ( 1 > count( $this->reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $this->username,
        'user_email'    =>   $this->email,
        'user_pass'     =>   $this->password,
		);
        $user = wp_insert_user( $userdata );
		/* var_dump($user); */
		echo '<div class="alert alert-success">Registration successful. Go to <a href="'. get_permalink( get_page_by_title( 'login') ) .'">Login</a> page.</div>';
		$this->username   =  "";
		$this->email      =  "";
		$this->password   =  "";
		$this->cpassword  = "";
		}
	}
}
/**
* Wordpond User Interface
*/
class Wordpond_UI
{
		private $user_id, $user_info;
		private $full_name, $display_name, $description,$user_roles;
		public function __construct($ID)
		{
			$this->user_id = $ID;
			$this->user_info = get_userdata($ID);
			$this->full_name = $this->user_info->first_name." ".$this->user_info->last_name;
			$this->display_name = $this->user_info->display_name;
			$this->description = $this->user_info->description;
			$this->user_roles = array_shift($this->user_info->roles);	
		}
	private function get_user_role()
	{
		$user_role = $this->user_roles;
		if ($user_role == 'administrator') {
		return 'Administrator';
		} elseif ($user_role == 'editor') {
		return 'Editor';
		} elseif ($user_role == 'author') {
		return 'Author';
		} elseif ($user_role == 'contributor') {
		return 'Contributor';
		} elseif ($user_role == 'subscriber') {
		return 'Subscriber';
		}elseif ($user_role == 'wpond_user') {
		return 'Wordpond User';
		} else {
		return '<strong>' . $user_role . '</strong>';
		}
	}
	public static function display_login_suggestion()
	{
		echo '<div class="bpost alert alert-info"><a href="'.WPOND_LOGIN_PAGE.'">Login to continue</a></div>';
	}
	public function author_card()
	{
		echo '<div class="my-card2 ">
			<div class="my-card2-body text-center">';
			if( get_current_user_id() == $this->user_id   ) {  ?>
				<a href="<?php echo admin_url( 'profile.php'); ?>">
				<?php echo get_avatar( $this->user_id , '' , null, $alt=$this->display_name, array( 'class' => array( 'authr-img' ) ) );	?>
				</a>
			<?php 
			}
			else {
				echo get_avatar( $this->user_id , '' , null, $alt=$this->display_name, array( 'class' => array( 'authr-img' ) ) ); 
			}
			if(!empty($this->display_name)){
				echo '<h6 style="margin-bottom:5px;">'.$this->display_name.'</h6>';
			}
			echo '<h6>('.$this->get_user_role().')</h6>';
			if(is_user_logged_in() && class_exists("Wordpond_relations")){
				$fob = new Wordpond_relations();
				$fob->display_add_friend($this->user_id);
			}
			if(!empty($this->description)){
				echo '<p class="my-card2-element" >'.$this->description.'</p>';
			}
		echo '</div></div>';
	}
	public function author_menus()
	{	$badge="";
		if(class_exists("Wordpond_notifications"))
		{
			$nobj = new Wordpond_notifications();
			$nct = $nobj->get_unread_notifications($this->user_id);
			if($nct>0)
				$badge = '<span class="badge pull-right">'.$nct.'</span>';
		}
		?>
		<div class="my-card2">
			<div class="my-card2-body">
			<ul class="nav profile-usermenu">
			<li><a href="<?php  echo WPOND_BLOG_PAGE;  ?>"><span class="dashicons dashicons-admin-home"></span>&nbsp;&nbsp;Blog</a></li>
			<li><a href="<?php  echo WPOND_NEWSFEED_PAGE;  ?>"><span class="dashicons dashicons-format-status"></span>&nbsp;&nbsp;News Feed</a></li>
			<li><a href="<?php  echo WPOND_NOTIFICATIONS_PAGE;  ?>"><span class="dashicons dashicons-admin-site"></span>&nbsp;&nbsp;Notifications<?php echo $badge;?></a></li>
			<li><a href="<?php  echo WPOND_FRIENDS_PAGE;  ?>"><span class="dashicons dashicons-groups"></span>&nbsp;&nbsp;Friends</a></li>
			<li><a href="<?php  echo WPOND_FRIENDS_SEARCH_PAGE;  ?>"><span class="dashicons dashicons-search"></span>&nbsp;&nbsp;Friends Search</a></li>
			<li><a href="<?php echo admin_url( 'profile.php' ); ?>"><span class="dashicons dashicons-admin-tools"></span>&nbsp;&nbsp;Settings</a></li>
			<li><a href="<?php echo get_dashboard_url(); ?>"><span class="dashicons dashicons-dashboard"></span>&nbsp;&nbsp;Dashboard</a></li>
			<?php 
			if( current_user_can('edit_posts') && current_user_can("publish_posts"))
			{
				?><li><a href="<?php echo admin_url('post-new.php'); ?>"><span class="dashicons dashicons-edit"></span>&nbsp;&nbsp;Add to Forum</a></li><?php
			}
			?>
			</ul>
			</div>
		</div>
		<?php
	}
	public static function displayModal($modalID,$modalTitle, $modalContent)
	{
		?>
		<div id="<?php echo $modalID; ?>" class="modal modal-primary fade" role="dialog">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?php echo $modalTitle;?></h4>
		</div>
		<div class="modal-body">
		<p><?php echo $modalContent;?></p>
		<br/>
		</div>
		</div>
		</div>
		</div>
		<?php 
	}
	public static function reportModal($modalTitle, $modalContent)
	{	?>
		<div id="" class="reportModal modal  fade" role="dialog">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?php echo $modalTitle;?></h4>
		</div>
		<div class="modal-body">
		<p><?php echo $modalContent;?></p>
		<form>
		<div class="form-group">
			<textarea class="form-control " id="reportReason" placeholder="Type your recommendation with reason">
			</textarea>
		</div>
		<button type="button" id="reportSubmit" data-post_id="" class=" btn btn-success">Submit</button>
		</form>
		<br/>
		</div>
		</div>
		</div>
		</div>
		<?php 
	}
}
/**
* WordPress Comments 
*/
function wordpond_media_cmt($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	switch($comment->comment_type)
	{
		case 'pingback':
		case 'trackback':
		?>
		<li class="media" id="comment-<?php comment_ID(); ?>">
		<div class="media-body">
		<p>
		<?php _e('Pingback:', 'wordpond'); ?> <?php comment_author_link(); ?>
		</p>
		</div><!--/.media-body -->
		</li>
		<?php
		break;
		default:
		global $post;
		?>
		<li class="bcomment">
			<?php echo get_avatar($comment->user_id, 35); ?>
			<div class="bcomment-cont">
			<strong><?php echo $comment->comment_author;?></strong>
			<?php if ('0' == $comment->comment_approved) : ?>
			<p class="comment-awaiting-moderation">
			<?php _e('<small>Your comment is awaiting moderation.</small>','wordpond'); ?>
			</p>
			<?php endif; ?>
			<?php echo comment_text(); ?>
			<p>
			<small>
			<?php 
				printf(
				__('%1$s at %2$s','wordpond'),
				get_comment_date(),
				get_comment_time()
				);
				echo " ";
				comment_reply_link( array( 'reply_text'=>__('Reply','wordpond'),  'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] )
				, $comment->comment_ID
				, $comment->comment_post_ID
				);
			?>
			</small>
			</p>
			</div>
		</li>
		<?php
	}
}
/* Remove the logout link in comment form */
add_filter( 'comment_form_logged_in', '__return_empty_string' );

/**
* Register Sidebar
*/
function wordpond_register_sidebar()
{
	$sidebar_args = array(
	'name'          => __( 'Primary Sidebar', 'wordpond' ),
	'id'            => 'primary-sidebar-1',
	'description'   => 'This is a primary, basice sidebar of Wordpond theme and will consist of Search bar, Recent Posts, Categories and Meta  ',
    'class'         => '',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget'  => '</li>',
	'before_title'  => '<h2 class="widgettitle">',
	'after_title'   => '</h2>' ); 
	
register_sidebar( $sidebar_args );
}
add_action( 'widgets_init', 'wordpond_register_sidebar' );

/**
* Wordpond Pagination
*/
 function wpond_pagination( $query=null ) {
	global $wp_query;
	$query = $query ? $query : $wp_query;
	$big = 999999999;

	$paginate = paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'type' => 'array',
	'total' => $query->max_num_pages,
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'prev_text' => '&laquo;',
	'next_text' => '&raquo;',
	)
	);

	if ($query->max_num_pages > 1) :
		echo '<ul class="pagination">';
		foreach ( $paginate as $page ) {
		echo '<li>' . $page . '</li>';
		}
		echo '</ul>';
	endif;
}
/**
* Wordpond  custom Navigations
*/
function get_logged_menus()
{
	if(is_user_logged_in()){
		?>
		<ul class="nav navbar-nav navbar-right" >
			<li> <a href="<?php echo WPOND_BLOG_PAGE;?>" >Blog</a> </li>
			<li> <a href="<?php echo get_dashboard_url();?>" >Dashboard</a> </li>
			<li> <a href="<?php echo wp_logout_url(site_url());?>" >Logout</a> </li>
		</ul>
		<?php
	}
	else {
		wp_nav_menu( array( 
		'menu'=>'logged-out-menu',
		'menu_class' => 'nav navbar-nav navbar-right '
		) );
		/* Create a 'logged-out-menu' in Menus section and add Signup and Login pages */
	}
}
// Replaces the excerpt "Read More" text by a link
function new_excerpt_more($more) {
	global $post;
	return '  <a class="moretag" href="'. get_permalink($post->ID).'">[Read more...]</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');
?>