$().ready(function() {
    $("#loginform").validate({
			rules: {
				log: {
					required: true,
					minlength: 2
				},
				pwd: {
					required: true,
					minlength: 2
				},
			},
			messages: {
				log: {
					required: "Không được để trống username",
					minlength: "Tối thiểu 2 ki tự",
				},
				pwd: {
					required: "Không được để trống password",
					minlength: "Tối thiểu 2 ki tự",
				},
			}
		});
});
