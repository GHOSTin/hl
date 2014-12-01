$(document).ready(function(){
  $(document).on('click', '.delete_error', function(){
    $.get('/error/delete_error',{
      time: $(this).parent().attr('time'),
      user_id: $(this).parent().attr('user_id')
      },function(r){
          init_content(r);
      });
  });
});