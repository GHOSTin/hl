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
	$('body').on('click', '.get_query_title', function(){
		$.get('index.php',{p: 'query.get_query_title',
			 id: get_query_id($(this))
			},function(r){
				show_content(r);
			});
	});	
	$('body').on('click', '.timeline-day', function(){
		$.get('index.php',{p: 'query.get_day',
			 time: $(this).attr('time')
			},function(r){
				$('.queries').html(r);
			});
	});		
	$('body').on('click', '.get_search', function(){
		$.get('index.php',{p: 'query.get_search'
			},function(r){
				$('.queries').html(r);
			});
	});
	$('body').on('click', '.get_search_result', function(){
		$.get('index.php',{p: 'query.get_search_result',
			param: $('.search_parameters').val()
			},function(r){
				$('.queries').html(r);
			});
	});
	$('body').on('click', '.clear_filters', function(){
		$.get('index.php',{p: 'query.clear_filters'
			},function(r){
				$('.queries').html(r);
			});
	});	
});
function get_query_id(obj){
	return obj.closest('.query').attr('query_id');
}