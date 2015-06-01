<?php

add_action( 'plugins_loaded', 'clean_log');
function clean_log()
{       
      /*  $myFile = WP_CONTENT_DIR."/debug.log";
        $fh = fopen($myFile, 'w+') or die("can't open file roi");
        fwrite($fh, "");
        fclose($fh);
       * 
       */
}

add_action( 'wp_footer', 'display_log');

function display_log()
{  
    $content = file_get_contents(WP_CONTENT_DIR."/debug.log");
    $array_content = split("\r\n|\r|\n",$content);
    //$content = chop(trim(preg_replace('/\r\n|\r|\n/', '\\ \r', $content)));
    ?>
    <script type="text/javascript">
        (function(){
           <?php 
            foreach ($array_content as $key => $value) {
                ?>
                        console.log("<?php echo addcslashes($value,'"'); ?>");
                <?php
            }
           ?>      
        })();
        
    </script>    
    <?php
}


//wp_log_everything();