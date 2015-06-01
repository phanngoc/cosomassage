

$(".target").dropper({
	action: "admin-ajax.php?action=myajax-submit"
}).on("fileComplete.dropper", onFileComplete);

function onFileComplete(e, file, response) {
    //$(".image-child").remove();
	// $.each(response, function(index, element) {
         console.log("File Complete");
         console.log(response);
         $(".queue-image").append("<div class='image-child'>\
                                    <img src='"+response+"' style='width:150px;height:150px' /> \
                                    <div class='deleteslider'><img src='"+urlbom.urlplugin+"delete.png'/></div> \
                                    <label for='link'>Chèn link</label> \
                                    <input type='text' class='form-control' name='link"+($('.image-child').length)+"' />\
                                   </div>");
      //});
	    
}

         $(".deleteslider").on('click',function(){
		 var url = $(this).prev().attr("src");
		 console.log("url ne:"+url);
		 $.get("admin-ajax.php?action=delete-image-slide&url="+url,function(data,status){
                 });
		 $(this).parent().remove();
                 
		 $.each($('.image-child'), function( key, value ) {
                    $(value).children('input').attr('name','link'+key);
                 });
	 });


$('.save').on('click',function(){
    $('.notify').append('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    $.each($('.image-child'), function( key, value ) {
        console.log($(value).children('input').val());
    });
    console.log($( "#imagelink" ).serialize());
    $.post( "admin-ajax.php?action=save-main-gallery-link-image",{data : $( "#imagelink" ).serialize()},function(result){
         $('.notify').children('.glyphicon').remove();  
         $('.notify').append('<span class="text-success">Đã lưu thành công</span>');
         (function (el) {
                setTimeout(function () {
                    el.children('span').remove();
                }, 3000);
            }($(".notify")));
    });
});