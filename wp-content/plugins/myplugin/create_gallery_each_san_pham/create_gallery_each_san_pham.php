<?php
define('BOMBAY_DIR_UPLOAD','sanpham');
define('KEY_SAVE_GALLERY','image-galery');
define('BOMBAY_POST_TYPE','san-pham');
define('BOMBAY_DIR_MODULE',pathinfo(__FILE__,PATHINFO_FILENAME));

function bombay_addgallery_admin_script() {
	global $post_type;
	if( BOMBAY_POST_TYPE == $post_type )
	{
		wp_enqueue_script( 'addgallery-admin-script1', plugins_url('/js/jquery.livequery.js', __FILE__),array('jquery-core'));
		wp_enqueue_script( 'addgallery-admin-script2', plugins_url('/js/addgallery.js', __FILE__),array('jquery-core'));
                wp_enqueue_style( 'part_gallery_post_san_pham', plugins_url('/css/style_part_post_san_pham.css', __FILE__) );
	}
}

add_action( 'admin_print_scripts-post-new.php', 'bombay_addgallery_admin_script', 11 );
add_action( 'admin_print_scripts-post.php', 'bombay_addgallery_admin_script', 11 );



function bombay_add_edit_form_multipart_encoding() {

	echo ' enctype="multipart/form-data"';

}
add_action('post_edit_form_tag', 'bombay_add_edit_form_multipart_encoding');


function bombay_add_post_meta_galary() {

	add_meta_box(
		'id-galery',      // Unique ID
		 esc_html__( 'Tạo gallery cho sản phẩm', 'example' ),    // Title
		'bombay_post_meta_galary_class',   // Callback function
		BOMBAY_POST_TYPE,         // Admin page (or post type)
		'normal',         // Context
		'default'         // Priority
	);
}
add_action('add_meta_boxes','bombay_add_post_meta_galary');

/* Display the post meta box. */
function bombay_post_meta_galary_class( $object, $box ) {
	log_me(wp_upload_dir());
        $path = wp_upload_dir()['baseurl']."/".BOMBAY_DIR_UPLOAD;
	wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' );
	$array_save = unserialize(get_post_meta($object->ID,KEY_SAVE_GALLERY,true));
	log_me($array_save);
	?>
	<p>
            <label for="anh1"><?php _e( "Thêm ảnh cho gallery sản phẩm", 'example' ); ?></label>
        </p>
        <div id="gallery">
	<?php 
	
	if(!empty($array_save))
	{
		foreach ($array_save as $key=>$value) {
	        ?> 
		  <input type="hidden" value="0" name="<?php echo $key ;?>"/>
	   	  <div class="show-galary">
                      <img src="<?php echo $path."/".$value;?>"/>	    
                      <button class="btn btn-success delete">Xóa</button>
	          </div>
		<?php 
		}
	}
        
	?>
            <div class="group-gallery">
                  <img src="" />	
	   	  <input  type="file" name="anh0" class="anh" size="30" />
                  <button class="btn btn-default delete disabled">Xóa</button>
	    </div>
        </div>   
   <button class="submit-create-gallery btn btn-default">Create Gallery</button>
<?php 
}

function bombay_post_save_galary_phanbom( $post_id )
{
        log_me('da vao post_save_galary_phanbom');
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
	if (!isset($_FILES['anh0'])||empty($_FILES['anh0']))  return;
	$indexanh = 0;
	$array_save = array();
	while (isset($_FILES['anh'.$indexanh]))
	{
		if ($_FILES['anh'.$indexanh]["name"]=="") 
                {
                    $indexanh++;
                    continue ;
                }
        // thay vi dung ten anh goc minh random ra ten de khong bi lap        
		log_me($_FILES['anh'.$indexanh]);
                $name_image = "image_gallery_".mt_rand().".jpg";
		array_push($array_save,$name_image);
		$uploadedfile = $_FILES['anh'.$indexanh];
		$upload_overrides = array('test_form' => false );
        
		//$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                if (move_uploaded_file($uploadedfile["tmp_name"], wp_upload_dir()['basedir']."/".BOMBAY_DIR_UPLOAD."/".$name_image)) {
                   log_me("Da upload thanh cong");
                } else {
                    log_me("Sorry, there was an error uploading your file.");
                }
		
		$indexanh++;
                log_me($indexanh);
	}
	log_me(get_post_meta($post_id,KEY_SAVE_GALLERY,true));
	if(!get_post_meta($post_id,KEY_SAVE_GALLERY,true)){
		add_post_meta( $post_id, KEY_SAVE_GALLERY ,serialize($array_save));
	}
        else
	{
            $imagegallerycheckremove = unserialize(get_post_meta($post_id,KEY_SAVE_GALLERY,true));
            $indexdelete = 0;
            while (isset($_POST["".$indexdelete]))
            {
                if($_POST["".$indexdelete]=="1")
                {
                    log_me("index delete:".$indexdelete."|".wp_upload_dir()['basedir']."/".$imagegallerycheckremove[$indexdelete]);
                    if(file_exists(wp_upload_dir()['basedir']."/".BOMBAY_DIR_UPLOAD."/".$imagegallerycheckremove[$indexdelete]))
                    {
                        unlink(wp_upload_dir()['basedir']."/".BOMBAY_DIR_UPLOAD."/".$imagegallerycheckremove[$indexdelete]);    
                    }
                    array_slice($imagegallerycheckremove, $indexdelete,1);
                }else
                {
                    array_push($array_save,$imagegallerycheckremove[$indexdelete]);
                }
                $indexdelete++;
                
            }
		update_post_meta( $post_id, KEY_SAVE_GALLERY ,serialize($array_save));
	}
        
}

add_action('save_post', 'bombay_post_save_galary_phanbom' );
