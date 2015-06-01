<?php
class BBResource {
    private $array = array();
    private static $instance = null;
    public static function init()
    {
        if($instance==null)
        {
            $res = new BBResource();
        }
          return $res;
    }
    public function add($name)
    {
        call_user_func(array($this, 'add'.ucfirst($name)));
    }
    public function addBootstrap()
    {
        wp_enqueue_script( 'bootstrap', plugins_url('/res/dist/js/bootstrap.js',__FILE__), array( 'jquery' ));
        wp_enqueue_style('bootstrapcss',plugins_url('/res/dist/css/bootstrap.css',__FILE__));
        wp_enqueue_style('bootstraptheme',plugins_url('/res/dist/css/bootstrap-theme.css',__FILE__),array('bootstrapcss'));
    }
    public function addJquery()
    {
        wp_enqueue_script( 'bb_jquery', plugins_url('/res/jquery-1.11.1.js', __FILE__) );
    }
    public function addJqueryui()
    {
        if(WP_DEBUG)
        {
            wp_enqueue_style('jqueryui',plugins_url('/res/jquery-ui-1.11.2.custom/jquery-ui.css',__FILE__),array());
            wp_enqueue_style('jqueryuitheme',plugins_url('/res/jquery-ui-1.11.2.custom/jquery-ui.theme.css',__FILE__),array('jqueryui'));
            wp_enqueue_script( 'jqueryui', plugins_url('/res/jquery-ui-1.11.2.custom/jquery-ui.js',__FILE__), array( 'bb_jquery' ));
        }
    }
    public function addJqueryvalidate()
    {
        //wp_enqueue_style('jqueryvalidate',plugins_url('/res/jquery-ui-1.11.2.custom/jquery.validate.js',__FILE__),array('jqueryui'));
        if(WP_DEBUG){
         wp_enqueue_script( 'jqueryvalidate', plugins_url('/res/jquery-validation-1.13.1/dist/jquery.validate.js',__FILE__), array( 'bb_jquery' ));   
        }
        else {
          wp_enqueue_script( 'jqueryvalidate', plugins_url('/res/jquery-validation-1.13.1/dist/jquery.validate.min.js',__FILE__), array( 'bb_jquery' ));     
        }
    }
}
if(!is_admin())
{
    
    BBResource::init()->add('bootstrap');
    BBResource::init()->add('jquery');
    BBResource::init()->add('jqueryui');
    
}

