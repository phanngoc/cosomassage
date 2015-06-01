<?php
function bombay_add_fancybox($class)
{
    ?>
    <script type="text/javascript">
     $(document).ready(function(){
         $('.<?php echo $class ;?>').fancybox();
     });   
    </script>
    <script src="<?php echo plugins_url('/fancyapps-fancyBox/lib/jquery.mousewheel-3.0.6.pack.js',__FILE__);?>"></script>
    <script src="<?php echo plugins_url('/fancyapps-fancyBox/source/jquery.fancybox.js',__FILE__);?>"></script>
    <script src="<?php echo plugins_url('/fancyapps-fancyBox/source/helpers/jquery.fancybox-buttons.js',__FILE__);?>"></script>
    <script src="<?php echo plugins_url('/fancyapps-fancyBox/source/helpers/jquery.fancybox-thumbs.js',__FILE__);?>"></script>
    <script src="<?php echo plugins_url('/fancyapps-fancyBox/source/helpers/jquery.fancybox-media.js',__FILE__);?>"></script>
    
    <link rel="stylesheet" href="<?php echo plugins_url('/fancyapps-fancyBox/source/jquery.fancybox.css',__FILE__)?>" />
    <link rel="stylesheet" href="<?php echo plugins_url('/fancyapps-fancyBox/source/jquery.fancybox-buttons.css',__FILE__)?>" />
    <link rel="stylesheet" href="<?php echo plugins_url('/fancyapps-fancyBox/source/jquery.fancybox-thumbs.css',__FILE__)?>" />
    <?php
}





