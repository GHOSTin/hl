$(document).ready(function(){
	$('body').on('click', '.get_dialog_edit_password', function(){
		$.get('get_dialog_edit_password',{
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
	$('body').on('click', '.get_dialog_edit_telephone', function(){
		$.get('get_dialog_edit_telephone',{
			},function(r){
				init_content(r);
			});
	});
});