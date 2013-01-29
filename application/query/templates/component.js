$(document).ready(function(){

	$('body').on('click', '.get_query_content', function(){
		$.getJSON('index.php',{p: 'query.get_query_content',
			 id: $(this).attr('query_id')
			},function(r){
	    		
			});
	});
});