$(document).ready(function(){
	$('body').on('click', '.get_documents', function(){
		$.get('get_documents',{
			 id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});	
	$('body').on('click', '.get_query_content', function(){
		$.get('get_query_content',{
			 id: $(this).attr('query_id')
			},function(r){
				init_content(r);
			});
	});
	$('body').on('click', '.get_query_title', function(){
		$.get('get_query_title',{
			 id: get_query_id($(this))
			},function(r){
				show_content(r);
			});
	});	
	$('body').on('click', '.query-numbers', function(){
		$.get('get_query_numbers',{
			 id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$('body').on('click', '.query-users', function(){
		$.get('get_query_users',{
			 id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$('body').on('click', '.query-works', function(){
		$.get('get_query_works',{
			 id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$('body').on('click', '.get_query_title', function(){
		$.get('get_query_title',{
			 id: get_query_id($(this))
			},function(r){
				show_content(r);
			});
	});	
	$('body').on('click', '.timeline-day', function(){
		$.get('get_day',{
			 time: $(this).attr('time')
			},function(r){
				$('.queries').html(r);
			});
	});		
	$('body').on('click', '.get_search', function(){
		$.get('get_search',{
			},function(r){
				$('.queries').html(r);
			});
	});
	$('body').on('click', '.get_search_result', function(){
		$.get('get_search_result',{
			param: $('.search_parameters').val()
			},function(r){
				$('.queries').html(r);
			});
	});
	$('body').on('click', '.clear_filters', function(){
		$.get('clear_filters',{
			},function(r){
				$('.queries').html(r);
			});
	});	
	$('.filter-content-select-status').change(function(){
		$.get('set_status',{
			value: $('.filter-content-select-status :selected').val()
			},function(r){
				$('.queries').html(r);
			});
	});	
	$('body').on('click', '.get_dialog_create_query', function(){
		$.get('get_dialog_create_query',{
			},function(r){
				show_content(r);
			});
	});	
	$('body').on('click', '.get_dialog_edit_description', function(){
		$.get('get_dialog_edit_description',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});	
	$('body').on('click', '.get_dialog_edit_contact_information', function(){
		$.get('get_dialog_edit_contact_information',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});
	$('body').on('click', '.get_dialog_edit_payment_status', function(){
		$.get('get_dialog_edit_payment_status',{
			id: get_query_id($(this))
			},function(r){
				init_content(r);
			});
	});		
	$('body').on('click', '.get_timeline', function(){
		$.get('get_timeline',{
			act: $(this).attr('act'),
			time: $('.timeline-month').attr('time')
			},function(r){
				init_content(r);
			});
	});	
});
function get_query_id(obj){
	return obj.closest('.query').attr('query_id');
}