<?php
/*
 * <?php 
                $html = '<div class="panel panel-default">
                            <div class="panel-heading">
                               Thông tin đặt hàng của bạn
                            </div>
                            <div class="panel-body">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                                Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.

                            </div>
                        </div>';
                bombay_add_colorbox('colorbox',$html);
                
                ?>
                
                <a href="#" class="colorbox">Bam de ra colorbox </a>
 */
function bombay_add_colorbox($class,$html)
{
    $html = preg_replace('/^\s+|\n|\r|\s+$/m', '', $html);
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('/colorbox-master/colorbox.css',__FILE__);?>"/>
    <script type="text/javascript" src="<?php echo plugins_url('/colorbox-master/jquery.colorbox.js',__FILE__);?>"></script>
    <script type="text/javascript">   
     $(document).ready(function(){
         $('.<?php echo $class ;?>').colorbox({html:'<?php echo $html;?>',
             width : '480px',
             height : '350px',
             opacity : 0.5, 
         });
     });   
    </script>
    <?php
}