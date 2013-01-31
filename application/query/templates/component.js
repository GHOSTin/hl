$(document).ready(function(){

	$('body').on('click', '.get_query_content', function(){
		$.get('index.php',{p: 'query.get_query_content',
			 id: $(this).attr('query_id')
			},function(r){
				show_content(r);
			});
	});

	$('body').on('click', '.get_query_title', function(){
		$.get('index.php',{p: 'query.get_query_title',
			 id: get_query_id($(this))
			},function(r){
				show_content(r);
			});
	});	
});

function get_query_id(obj){
	return obj.closest('.query').attr('query_id');
}