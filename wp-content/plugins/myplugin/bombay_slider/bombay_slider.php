<?php
function bombay_add_jssor()
{
    ?>
    <script type="text/javascript" src="<?php echo plugins_url('/js/jssor.slider.mini.js',__FILE__)?>"></script>
    <script type="text/javascript">
         jQuery(document).ready(function ($) {
            var options = { $AutoPlay: true };
                var jssor_slider1 = new $JssorSlider$('content-main-gallery', options);
            });
    </script>
    <?php
}

function bombay_add_novo_slider($id,$html_imgs="",$theme="default")
{
    ?>
    <div class="slider-wrapper theme-<?php echo $theme;?>">
        
        <div class="ribbon"></div>
                              
        <div id="<?php echo $id;?>" class="nivoSlider"> 
            <?php
                echo $html_imgs;
            ?>
        </div>  
                              
    </div>
    <link href="<?php echo plugins_url('/Nivo-Slider-master/nivo-slider.css',__FILE__);?>"  rel="stylesheet" />
    <link href="<?php echo plugins_url("/Nivo-Slider-master/themes/$theme/$theme.css",__FILE__);?>"  rel="stylesheet" />
    <script type="text/javascript" src="<?php echo plugins_url('/Nivo-Slider-master/jquery.nivo.slider.js',__FILE__);?>"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#<?php echo $id;?>').nivoSlider({
                effect: 'random',               // Specify sets like: 'fold,fade,sliceDown'
                slices: 15,                     // For slice animations
                boxCols: 8,                     // For box animations
                boxRows: 4,                     // For box animations
                animSpeed: 500,                 // Slide transition speed
                pauseTime: 2000,                // How long each slide will show
                startSlide: 0,                  // Set starting Slide (0 index)
                directionNav: true,             // Next & Prev navigation
                controlNav: true,               // 1,2,3... navigation
                controlNavThumbs: false,        // Use thumbnails for Control Nav
                pauseOnHover: true,             // Stop animation while hovering
                manualAdvance: false,           // Force manual transitions
                prevText: 'Prev',               // Prev directionNav text
                nextText: 'Next',               // Next directionNav text
                randomStart: false,             // Start on a random slide
                beforeChange: function(){},     // Triggers before a slide transition
                afterChange: function(){},      // Triggers after a slide transition
                slideshowEnd: function(){},     // Triggers after all slides have been shown
                lastSlide: function(){},        // Triggers when last slide is shown
                afterLoad: function(){}         // Triggers when slider has loaded);
        });
    });
    </script>
    <?php
}
