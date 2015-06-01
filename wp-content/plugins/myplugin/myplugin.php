<?php


$func_init  = array(
            'bombay_showdebug',
            'helper',
            'create_main_gallery',
            'create_gallery_each_san_pham',
            'bombay_lightbox',
            'bombay_slider',
            'bombay_form',
            'bombay_pagination',
            'memakeup',
            'bombay_cart',
            'bombay_widget',
            'bombay_rating',
            'resource_popular',
    );



foreach ($func_init as $key => $value) {
    include_once $value.'/'.$value.'.php';  
}


















function dump_hook( $tag, $hook ) {
    ksort($hook);
    echo "<pre>>>>>>\t$tag<br>";

    foreach( $hook as $priority => $functions ) {

	echo $priority;

	foreach( $functions as $function )
	    if( $function['function'] != 'list_hook_details' ) {

		echo "\t";

		if( is_string( $function['function'] ) )
		    echo $function['function'];

		elseif( is_string( $function['function'][0] ) )
		     echo $function['function'][0] . ' -> ' . $function['function'][1];

		elseif( is_object( $function['function'][0] ) )
		    echo "(object) " . get_class( $function['function'][0] ) . ' -> ' . $function['function'][1];

		else
		    print_r($function);

		echo ' (' . $function['accepted_args'] . ') <br>';
	    }
    }

    echo '</pre>';
}
function list_hooks( $filter = false ){
	global $wp_filter;

	$hooks = $wp_filter;
	ksort( $hooks );

	foreach( $hooks as $tag => $hook )
	    if ( false === $filter || false !== strpos( $tag, $filter ) )
			dump_hook($tag, $hook);
}
function list_hook_details( $input = NULL ) {
    global $wp_filter;

    $tag = current_filter();
    if( isset( $wp_filter[$tag] ) )
		dump_hook( $tag, $wp_filter[$tag] );

	return $input;
}
function list_live_hooks( $hook = false ) {
    if ( false === $hook )
		$hook = 'all';

    add_action( $hook, 'list_hook_details', -1 );
}