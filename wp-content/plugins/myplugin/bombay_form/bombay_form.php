<?php

function comment_form_me( $args = array(), $post_id = null ) {
	if ( null === $post_id )
		$post_id = get_the_ID();

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$args = wp_parse_args( $args );
	if ( ! isset( $args['format'] ) )
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html5    = 'html5' === $args['format'];
	$fields   =  array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label> ' .
		            '<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );

	/**
	 * Filter the default comment form fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $fields The default comment fields.
	 */
	$fields = apply_filters( 'comment_form_default_fields', $fields );
	$defaults = array(
		'fields'               => $fields,
		'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		/** This filter is documented in wp-includes/link-template.php */
		'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		/** This filter is documented in wp-includes/link-template.php */
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</p>',
		'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'name_submit'          => 'submit',
		'title_reply'          => __( 'Leave a Reply' ),
		'title_reply_to'       => __( 'Leave a Reply to %s' ),
		'cancel_reply_link'    => __( 'Cancel reply' ),
		'label_submit'         => __( 'Post Comment' ),
		'format'               => 'xhtml',
	);

	/**
	 * Filter the comment form default arguments.
	 *
	 * Use 'comment_form_default_fields' to filter the comment fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $defaults The default comment form arguments.
	 */
	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

	?>
		<?php if ( comments_open( $post_id ) ) : ?>
			<?php
			/**
			 * Fires before the comment form.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_before' );
			?>
			<div id="respond" class="comment-respond">
				<h3 id="reply-title" class="comment-reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h3>
				<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
					<?php echo $args['must_log_in']; ?>
					<?php
					/**
					 * Fires after the HTML-formatted 'must log in after' message in the comment form.
					 *
					 * @since 3.0.0
					 */
					do_action( 'comment_form_must_log_in_after' );
					?>
				<?php else : ?>
					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" class="comment-form"<?php echo $html5 ? ' novalidate' : ''; ?>>
						<?php
						/**
						 * Fires at the top of the comment form, inside the <form> tag.
						 *
						 * @since 3.0.0
						 */
						do_action( 'comment_form_top' );
                        echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
						?>
						<?php if ( is_user_logged_in() ) : ?>
							<?php
							/**
							 * Filter the 'logged in' message for the comment form for display.
							 *
							 * @since 3.0.0
							 *
							 * @param string $args_logged_in The logged-in-as HTML-formatted message.
							 * @param array  $commenter      An array containing the comment author's
							 *                               username, email, and URL.
							 * @param string $user_identity  If the commenter is a registered user,
							 *                               the display name, blank otherwise.
							 */
							echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );
							?>
							<?php
							/**
							 * Fires after the is_user_logged_in() check in the comment form.
							 *
							 * @since 3.0.0
							 *
							 * @param array  $commenter     An array containing the comment author's
							 *                              username, email, and URL.
							 * @param string $user_identity If the commenter is a registered user,
							 *                              the display name, blank otherwise.
							 */
							do_action( 'comment_form_logged_in_after', $commenter, $user_identity );
							?>
						<?php else : ?>
							<?php echo $args['comment_notes_before']; ?>
							<?php
							/**
							 * Fires before the comment fields in the comment form.
							 *
							 * @since 3.0.0
							 */
							do_action( 'comment_form_before_fields' );
							foreach ( (array) $args['fields'] as $name => $field ) {
								/**
								 * Filter a comment form field for display.
								 *
								 * The dynamic portion of the filter hook, $name, refers to the name
								 * of the comment form field. Such as 'author', 'email', or 'url'.
								 *
								 * @since 3.0.0
								 *
								 * @param string $field The HTML-formatted output of the comment form field.
								 */
								echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
							}
							/**
							 * Fires after the comment fields in the comment form.
							 *
							 * @since 3.0.0
							 */
							do_action( 'comment_form_after_fields' );
							?>
						<?php endif; ?>
						<?php
						/**
						 * Filter the content of the comment textarea field for display.
						 *
						 * @since 3.0.0
						 *
						 * @param string $args_comment_field The content of the comment textarea field.
						 */
                         if(is_user_logged_in()):
                         ?>
						<p class="form-submit">
							<input name="<?php echo esc_attr( $args['name_submit'] ); ?>" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" class="btn btn-primary"/>
							<?php comment_id_fields( $post_id ); ?>
						</p>
                        <?php endif ;?>
						<?php echo $args['comment_notes_after']; ?>
					
						<?php
						/**
						 * Fires at the bottom of the comment form, inside the closing </form> tag.
						 *
						 * @since 1.5.0
						 *
						 * @param int $post_id The post ID.
						 */
						do_action( 'comment_form', $post_id );
						?>
                        <input type="hidden" name="comment_post_ID" value="<?php echo get_the_ID();?>" />
					</form>
				<?php endif; ?>
			</div><!-- #respond -->
			<?php
			/**
			 * Fires after the comment form.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_after' );
		else :
			/**
			 * Fires after the comment form if comments are closed.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_comments_closed' );
		endif;
}



function thememe_wp_login_form( $args = array() ) {
	$defaults = array(
		'echo' => true,
		'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // Default redirect is back to the current page
		'form_id' => 'loginform',
		'label_username' => __( 'Username' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in' => __( 'Log In' ),
		'id_username' => 'user_login',
		'id_password' => 'user_pass',
		'id_remember' => 'rememberme',
		'id_submit' => 'wp-submit',
		'remember' => true,
		'value_username' => '',
		'value_remember' => false, // Set this to true to default the "Remember me" checkbox to checked
	);

	/**
	 * Filter the default login form output arguments.
	 *
	 * @since 3.0.0
	 *
	 * @see wp_login_form()
	 *
	 * @param array $defaults An array of default login form arguments.
	 */
	$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );

	/**
	 * Filter content to display at the top of the login form.
	 *
	 * The filter evaluates just following the opening form tag element.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_top = apply_filters( 'login_form_top', '', $args );

	/**
	 * Filter content to display in the middle of the login form.
	 *
	 * The filter evaluates just following the location where the 'login-password'
	 * field is displayed.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_middle = apply_filters( 'login_form_middle', '', $args );

	/**
	 * Filter content to display at the bottom of the login form.
	 *
	 * The filter evaluates just preceding the closing form tag element.
	 *
	 * @since 3.0.0
	 *
	 * @param string $content Content to display. Default empty.
	 * @param array  $args    Array of login form arguments.
	 */
	$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );
        
	$form = '
                <h4 class="title-login">Đăng nhập</h4>
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post" role="form">
			' . $login_form_top . '
			<p class="login-username form-group">
				<label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label><br>
				<input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input form-control" value="' . esc_attr( $args['value_username'] ) . '" size="20" />
			</p>
			<p class="login-password form-group">
				<label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label><br>
				<input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input form-control" value="" size="20" />
			</p>
			' . $login_form_middle . '
			' . ( $args['remember'] ? '<p class="login-remember checkbox"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
			<p class="login-submit">
				<input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button-primary btn btn-default" value="' . esc_attr( $args['label_log_in'] ) . '" />
				<input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
			</p>
			' . $login_form_bottom . '
		</form>';

	if ( $args['echo'] )
		echo $form;
	else
		return $form;
}

function registration_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {
    
    $prefix = '?';
    $url_action = $_SERVER['REQUEST_URI'];
    $url_action = preg_replace("/[&\?]mode=[\w]*/i", "", $url_action);
    $url_action = preg_replace("/[&\?]login=[\w]*/i", "", $url_action);
    
    if(strpos($url_action,'mode=')!=false){
        $prefix = '&';
    }
   
    $url_action = $url_action . $prefix . 'mode=register';
    echo '
    <h4 class="title-login">Đăng kí</h4> 
    <form action="' . $url_action .'" method="post">
    <div class="form-group">
    <label for="username">Username <strong>*</strong></label>
    <input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '" class="form-control">
    </div>
     
    <div class="form-group">
    <label for="password">Password <strong>*</strong></label>
    <input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '" class="form-control">
    </div>
     
    <div class="form-group">
    <label for="email">Email <strong>*</strong></label>
    <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '" class="form-control">
    </div>
     
    <div class="form-group">
    <label for="website">Website</label>
    <input type="text" name="website" value="' . ( isset( $_POST['website']) ? $website : null ) . '" class="form-control">
    </div>
     
    <div class="form-group">
    <label for="firstname">First Name</label>
    <input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '" class="form-control">
    </div>
     
    <div class="form-group">
    <label for="website">Last Name</label>
    <input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '" class="form-control">
    </div>
     
    <div class="form-group">
    <label for="nickname">Nickname</label>
    <input type="text" name="nickname" value="' . ( isset( $_POST['nickname']) ? $nickname : null ) . '" class="form-control">
    </div>
     
    <div class="form-group">
    <label for="bio">About / Bio</label>
    <textarea name="bio" class="form-control">' . ( isset( $_POST['bio']) ? $bio : null ) . '</textarea>
    </div>
    <input type="submit" name="submit" value="Register" class="btn btn-success"/>
    </form>
    ';
}

function registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio )  {
    global $reg_errors;
    $reg_errors = new WP_Error;
    if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
        $reg_errors->add('field', 'Required form field is missing');
    }
    if ( 4 > strlen( $username ) ) {
        $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
    }
    if ( ! validate_username( $username ) ) {
        $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
    }
    if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'Password length must be greater than 5' );
    }
    if ( !is_email( $email ) ) {
        $reg_errors->add( 'email_invalid', 'Email is not valid' );
    }
    if ( email_exists( $email ) ) {
        $reg_errors->add( 'email', 'Email Already in use' );
    }
    if ( ! empty( $website ) ) {
        if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
            $reg_errors->add( 'website', 'Website is not a valid URL' );
        }
    }
    if ( is_wp_error( $reg_errors ) ) {
 
        foreach ( $reg_errors->get_error_messages() as $error ) {
            ?>
                <div class="alert alert-danger alert-error">
                       <a href="#" class="close" data-dismiss="alert">&times;</a>
                       <strong>ERROR : <?php echo $error;?></strong>
                </div>
            <?php
        }

    }
}
function complete_registration() {
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'user_url'      =>   $website,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'nickname'      =>   $nickname,
        'description'   =>   $bio,
        );
        $user = wp_insert_user( $userdata );
        echo 'Registration complete. Goto <a href="' . site_url('/dangnhap') . '">login page</a>.';   
        return true;
    }
    return false;
}

function custom_registration_function() {
    global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if ( isset($_POST['submit'] ) ) {
        registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['website'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['nickname'],
        $_POST['bio']
        );
         
        // sanitize user form input
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $website    =   esc_url( $_POST['website'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
        $nickname   =   sanitize_text_field( $_POST['nickname'] );
        $bio        =   esc_textarea( $_POST['bio'] );
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        if(!complete_registration($username,$password,$email,$website,$first_name,$last_name,$nickname,$bio))
        {
            registration_form(
            $username,
            $password,
            $email,
            $website,
            $first_name,
            $last_name,
            $nickname,
            $bio
            );
        }
    }
    else{
        registration_form(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );
    }
    
}


add_action( 'wp_login_failed', 'pu_login_failed' ); // hook failed login

function pu_login_failed( $user ) {
  	// check what page the login attempt is coming from
  	$referrer = $_SERVER['HTTP_REFERER'];
        $prefix = '?';
        
        if(isset($_GET)&&!empty($_GET)) $prefix = '&';
        //Neu link da co mode roi thi bo no di
        $referrer = preg_replace("/[&\?]mode=[\w]*/i", "", $referrer);
        $referrer = preg_replace("/[&\?]login=[\w]*/i", "", $referrer);
        
        $referrer = $referrer . $prefix . 'mode=login';
  	$error = false;
        log_me("nhe");
  	// check that were not on the default login page
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
		// make sure we don't already have a failed login attempt
            log_me($referrer);
		if ( !strstr($referrer, '?login=failed' )) {
                    // Redirect to the login page and append a querystring of login failed
                    wp_redirect( $referrer .'&login=failed');
                } else {
                    wp_redirect( $referrer );
                }
	    exit;
	}
}

add_action( 'authenticate', 'pu_blank_login');

function pu_blank_login( $user ){
  
}