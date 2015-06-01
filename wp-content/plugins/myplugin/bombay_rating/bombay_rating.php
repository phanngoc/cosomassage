<?php
define("KEY_META_RATING","key_rating");
define("KEY_META_NUMBER_RATING","key_number_rating") ;
function bombay_rating()
{
    $id = get_the_ID();
    $result_rate = get_post_meta($id,KEY_META_RATING,true) ? intval(get_post_meta($id,KEY_META_RATING,true)) : 3;
    
    ?>
    <link href="<?php echo plugins_url('/jRating-master/jquery/jRating.jquery.css',__FILE__);?>" rel="stylesheet"/>
    <script src="<?php echo plugins_url('/jRating-master/jquery/jRating.jquery.js',__FILE__);?>"></script>
    <!-- HTML CODE -->
    <div class="basic" data-average="<?php echo $result_rate;?>" data-id="<?php echo $id;?>"></div>
    <!-- JS to add -->
    <script type="text/javascript">
      $(document).ready(function(){
            $(".basic").jRating({
                length : 5, // so ngoi sao
                rateMax : 5, // diem max rate
                phpPath : '<?php echo site_url('/wp-admin/admin-ajax.php');?>',
                bigStarsPath : '<?php echo plugins_url('/jRating-master/jquery/icons/stars.png',__FILE__);?>', // path of the icon stars.png
                smallStarsPath : '<?php echo plugins_url('/jRating-master/jquery/icons/small.png',__FILE__);?>', // path of the icon small.png
                step : false, // tung ngoi sao mot hay la len tu tu
                canRateAgain : true, // co duoc phep rate lai hay khong
                nbRates : 10,  // so lan co the rate cua nguoi dung      
                onSuccess : function(){
		    alert('Success : your rate has been saved :)');
		},
            });
      });
    </script>
    <?php
}
function bombay_ajax_rating()
{
    $id = $_POST['idBox'];
    $rate = $_POST['rate'];
    log_me("hello world");
    log_me(get_post_meta($id,KEY_META_RATING,true));
    log_me(get_post_meta($id,KEY_META_NUMBER_RATING,true));
    $result_rate = floatval(get_post_meta($id,KEY_META_RATING,true));
    $count_rate = intval(get_post_meta($id,KEY_META_NUMBER_RATING,true));
    log_me($result_rate);
    log_me($count_rate);
    
    $count_update = $count_rate+1;
    $result_rate_update = ($result_rate*$count_rate+$rate)/($count_update);
    log_me($count_update);
    log_me($result_rate_update);
    update_post_meta($id,KEY_META_RATING, $result_rate_update);
    update_post_meta($id,KEY_META_NUMBER_RATING, $count_update);    
    exit();
}
add_action( 'wp_ajax_rating', 'bombay_ajax_rating' );
add_action( 'wp_ajax_nopriv_bombay_ajax_rating', 'bombay_ajax_rating' );