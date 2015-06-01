<?php

define('BOMBAY_KEY_SAVE_OPTION','slide-image-main');
define('BOMBAY_KEY_SAVE_LINK_OPTION','slide-image-main-link');
if (!defined('BOMBAY_DIR_UPLOAD')) {
   define('BOMBAY_DIR_UPLOAD','sanpham');
}


function bombay_add_resource_page_add_main_gallery(){
	if(!isset($_GET['page'])) return ;
	if($_GET["page"]=="myplugin/create_main_gallery/create_main_gallery.php")
	{
         //   wp_enqueue_script( 'bootstrap', plugins_url('/myplugin/res/dist/js/bootstrap.js'), array( 'jquery' ));
         //   wp_enqueue_style('bootstrapcss',plugins_url('/myplugin/res/dist/css/bootstrap.css'));
         //   wp_enqueue_style('bootstraptheme',plugins_url('/myplugin/res/dist/css/bootstrap-theme.css'),array('bootstrapcss'));
            
		wp_enqueue_script( 'jquery-bom', plugins_url('/js/jquery.js', __FILE__) );
		wp_enqueue_script( 'dropper-file', plugins_url('/js/Dropper/jquery.fs.dropper.js', __FILE__));
		wp_enqueue_script( 'dropper-file-custom-me', plugins_url('/js/customdrop.js', __FILE__) );
		$translation_array = array( 'urlplugin' => plugins_url('/images/', __FILE__));
		wp_localize_script( 'dropper-file-custom-me', 'urlbom', $translation_array );
		
		wp_register_style( 'dropper-file-css', plugins_url('/js/Dropper/jquery.fs.dropper.css', __FILE__) );
		wp_enqueue_style( 'dropper-file-css' );
		wp_register_style( 'myplugin-css', plugins_url('/css/style.css', __FILE__) );
		wp_enqueue_style( 'myplugin-css' );
	}
}

add_action('admin_head', "bombay_add_resource_page_add_main_gallery");


function bombay_remove_head()
{
    remove_all_actions("wp_head", 10);
}
add_action("wp_head","bombay_remove_head",8);

function bombay_register_mysettings() {
        register_setting( 'mfpd-settings-group', 'mfpd_option_name' );
}
 
function bombay_create_menu() {
        add_menu_page('Main Gallery Title', 'Main Gallery Settings', 'administrator', __FILE__, 'bombay_settings_page',plugins_url('/images/A.jpg', __FILE__), 1);
        add_action( 'admin_init', 'bombay_register_mysettings' );
}
add_action('admin_menu', 'bombay_create_menu'); 

function bombay_settings_page() {
?>
<div class="wrap">
    <h2>Tạo trang hình nền slide trang chủ</h2>
    <form method="post" action="#" id="imagelink">
        <?php settings_fields( 'mfpd-settings-group' ); ?>
        <div class="target"></div>
        <div class="queue-image">
                <?php 
                $files_temp = unserialize(get_option(BOMBAY_KEY_SAVE_OPTION));
                $link_image = unserialize(get_option(BOMBAY_KEY_SAVE_LINK_OPTION));
                if(!empty($files_temp))
                {
                        foreach ($files_temp as $key=>$value)
                        {
                                ?>
                                <div class='image-child'>
                                 <img src='<?php echo $value;?>' style='width:150px;height:150px' /> 
                                 <div class='deleteslider'><img src='<?php echo plugins_url('/images/delete.png', __FILE__);?>'/></div>
                                 <label for='link<?php echo $key;?>'>Chèn link</label> 
                                 <input type='text' class='form-control' name='link<?php echo $key;?>' value="<?php 
                                 if(array_key_exists($key, $link_image))
                                   echo urldecode($link_image[$key]);
                                 ?>" />
                                </div>
                                <?php 
                        }
                }
                ?>
        </div>
    </form>
    <div class="clearfix"></div>
    <div class="notify">
        <button class="btn btn-default save">Save Change</button>
    </div>
</div>
<?php
}
add_action( 'wp_ajax_myajax-submit', 'bombay_upload_image_main_gallery' );
 
function bombay_upload_image_main_gallery() {
    // get the submitted parameters
    if(!isset($_FILES["file"])) return ;
	$uploadedfile = $_FILES["file"];
        $name_new_file = "upload_gallery_main_".mt_rand().".jpg";
	if (move_uploaded_file($uploadedfile["tmp_name"], wp_upload_dir()['basedir']."/".BOMBAY_DIR_UPLOAD."/".$name_new_file)) {
		log_me("Da upload thanh cong");
	} else {
		log_me("Sorry, there was an error uploading your file.");
	}
 	$files_temp = array();
    // generate the response
    $link_new_file =  wp_upload_dir()['baseurl']."/".BOMBAY_DIR_UPLOAD."/".$name_new_file;   
    if(get_option(BOMBAY_KEY_SAVE_OPTION))
    {
    	$files_temp = unserialize(get_option(BOMBAY_KEY_SAVE_OPTION));
    	array_push($files_temp,$link_new_file);
    	update_option(BOMBAY_KEY_SAVE_OPTION, serialize($files_temp));
    }
    else
    {
    	array_push($files_temp,$link_new_file);
    	$files_ser = serialize($files_temp);
    	add_option(BOMBAY_KEY_SAVE_OPTION, $files_ser);
    }
 	
    //header( "Content-Type: application/json" );
    echo $link_new_file;
 
    // IMPORTANT: don't forget to "exit"
    exit;
}



add_action( 'wp_ajax_delete-image-slide', 'bombay_delete_image_main_gallery' );

function bombay_delete_image_main_gallery() {
	
	if(!isset($_GET['url'])) return;
	if(get_option(BOMBAY_KEY_SAVE_OPTION))
	{
		$files_temp = unserialize(get_option(BOMBAY_KEY_SAVE_OPTION));
		foreach ($files_temp as $key => $value)
		{
			if($value==$_GET['url'])
			{
				log_me("Key de cat:$key");
				array_splice($files_temp, $key,1);
				$name_file = substr($value,strrpos($value,"/")+1);
				log_me("ten file:".$name_file);
				$link_delete = wp_upload_dir()['basedir']."/".BOMBAY_DIR_UPLOAD."/".$name_file;
				if(file_exists($link_delete))
				{
					unlink($link_delete);
				}
				
				break;
			}
		}
		update_option(BOMBAY_KEY_SAVE_OPTION, serialize($files_temp));
	}
	exit;
}

add_action( 'wp_ajax_save-main-gallery-link-image', 'bombay_save_main_gallery_link_image' );
 
function bombay_save_main_gallery_link_image() {
  $data = $_POST['data'];
  $arr_data = explode("&",$data);
  log_me($arr_data);
  $arr_link = array();
  foreach ($arr_data as $key => $value) {
      if(strpos($value, 'link')!==false)
      {
          $real_data = substr($value, strpos($value, '=')+1);
          log_me($real_data);
          array_push($arr_link, $real_data);
      }
  }
  log_me($arr_link);
  update_option(BOMBAY_KEY_SAVE_LINK_OPTION, serialize($arr_link));
  exit;
}

add_action("wp_enqueue_scripts","add_resource_client");

function add_resource_client()
{
    if(is_front_page())
        {
            wp_enqueue_script( 'jquery-bom', plugins_url('/js/jquery.js', __FILE__) );
            wp_enqueue_script( 'slider', plugins_url('/myplugin/create_main_gallery/js/jquery.slides.js'), array( 'jquery' ));
        }
}
function display_slider_top(){
  
	$image_slider = unserialize(get_option("slide-image-main"));
        $link_slider = unserialize(get_option("slide-image-main-link"));
	?>
        <div id="content-main-gallery">    
            <div id="slides-detail" u="slides">
                      <?php 
                        foreach ($image_slider as $key=>$value)
                        {
                            $link = array_key_exists($key, $link_slider) ? $link_slider[$key] : '#';
                            $link = urldecode($link);
                            echo "<a href='$link'><img src='".$value."' u='image'/></a>";
                        }
                      ?>
            </div>
        </div>
        <script>
             (function($) {
                $('#slides-detail').slidesjs({
                  width: 600,
                  height: 245,
                  navigation: false
                });
             })(jQuery);
        </script>
<?php 
}