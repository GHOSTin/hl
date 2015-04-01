$(document).ready(function(){

  $('body').on('click', '.get_dialog_create_query_type', function(){
    $.get('get_dialog_create_query_type',{
    },function(r){
      init_content(r);
    });
  });
});

function get_query_type_id(obj){
  return obj.closest('.query_type').attr('query_type_id');
}