<?php
add_action("wp_enqueue_scripts","add_resource_front");

function add_resource_front()
{
    if(is_front_page())
        {
            wp_enqueue_style('index',get_template_directory_uri() . '/me/css/index.css',array());
            //wp_enqueue_script( 'jquery-bom', plugins_url('/js/jquery.js', __FILE__) );
            //wp_enqueue_script( 'slider', plugins_url('/myplugin/create_main_gallery/js/jquery.slides.js'), array( 'jquery' ));
        }
    if ( is_page_template( 'deposit.php' ) ) {
	wp_enqueue_style('deposit',get_template_directory_uri() . '/me/css/deposit.css',array());
        BBResource::init()->add('jqueryvalidate');
        wp_enqueue_script( 'deposit', get_template_directory_uri().'/me/js/deposit.js', array( 'bb_jquery' ));
    }
}
function get_terms_by_post_type( $taxonomies, $post_types ) {

    global $wpdb;

    $query = $wpdb->prepare(
        "SELECT t.*, COUNT(*) from $wpdb->terms AS t
        INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
        INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id
        WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s')
        GROUP BY t.term_id",
        join( "', '", $post_types ),
        join( "', '", $taxonomies )
    );

    $results = $wpdb->get_results( $query );

    return $results;

}

function display_select_choose_type_massage()
{
    ?>
        <div class="form-group">
        <label for="type_massage">Chọn dịch vụ</label>
        <select class ="form-control" name="type_massage">
            <?php
            $args = array( 'post_type' => 'product');
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post();
              echo '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
            endwhile;
            ?>
        </select>
        </div>
    <?php
}
function data_post($name)
{
    if (isset($_POST[$name])) return $_POST[$name];
    return '';
}

function receive_form_deposit()
{
   if(!isset($_POST['fullname'])) return;
   $username = data_post('fullname');
   log_me("Co username ne:".$username);
   $gender = data_post('gender');
   $quantity = data_post('quantity');
   $address = data_post('address');
   $type_room = data_post('type_room');
   $type_massage = data_post('type_massage');
   // Create post object
    $my_post = array(
      'post_title'    => '',
      'post_content'  => 'This is my post.',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'deposit',
    );

    // Insert the post into the database
    wp_insert_post( $my_post );
       // Create post object
    $my_post = array(
      'post_title'    => 'My post',
      'post_content'  => 'This is my post.',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_category' => array(8,39)
    );

    // Insert the post into the database
    wp_insert_post( $my_post );
}
add_action('init','receive_form_deposit');