/**
 * 
 */
$ = jQuery;
$(document).ready(function(){
	function readURL(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
                
	        reader.onload = function (e) {
	            $(input).parent().find('img').attr('src', e.target.result);
                    //$(input).prop('disabled',true);
                    $(input).next().prop('disabled',false);
                    $(input).next().removeClass('disabled');
                    $(input).hide();
	            $("#gallery").append($('<div class="group-gallery"> \
	            	                   <img src=""/>	\
	            					   <input  type="file" name="anh'+($(".group-gallery").size())+'" class="anh" size="30" /> \
	            					   <button class="xoagallery btn btn-default" disabled>Xóa</button> \
	            					  </div>'));
                    //Tim cac class .anh de dinh kem lai su kien
	            $("#gallery").livequery(".anh",function(elem){
	        		$(elem).change(function(){
	        			console.log($(this).index());
	        		    readURL(this);
	        		});	
                    });
                    // Dinh kem lai su kien xoa
	            $("#gallery").livequery(".xoagallery",function(elem){
	        		$(elem).click(function(){
                                        resetHeightGallery();
	        			$(this).parent().find("input").val("");
		        		$(this).parent().remove();
		        		$("#gallery").find(".group-gallery").each(function( index ) {
		        			$(this).children("input").attr("name","anh"+index);
		        		});	
	        		});
                    });
                    resetHeightGallery();
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	
	   $(".anh").change(function(){
                    $(this).removeClass("anh").addClass("none");
                    console.log($(this).index());
		    readURL(this);
            });	
            
        $(".delete").click(function(event){
            console.log("delete press");
            event.preventDefault(); // cancel default behavior
            $(this).parent().prev().val(1);
            $(this).parent().remove();
            resetHeightGallery();
        });  
        function resetHeightGallery()
        {
            var count_item = $('.show-galary').length + $('.group-gallery').length;
            var height = (Math.floor(count_item / 4.1) + 1)*160 ;
            $("#gallery").css({height:height+'px'});    
        }    
        resetHeightGallery();
		
})