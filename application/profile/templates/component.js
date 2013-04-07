$(document).ready(function(){
	$('body').on('click', '.get_dialog_edit_password', function(){
		$.get('get_dialog_edit_password',{
			id: $(this).closest('.profile').attr('user')
			},function(r){
				init_content(r);
			});
	});
	$('body').on('click', '.get_dialog_edit_cellphone', function(){
		$.get('get_dialog_edit_cellphone',{
			},function(r){
				init_content(r);
			});
	});
});