$(document).ready(function(){

  $('body').on('click', '.get_dialog_create_query_type', function(){
    $.get('get_dialog_create_query_type',{
    },function(r){
      init_content(r);
    });
  });
  $(".colorpicker").spectrum({
    showInput: true,
    showInitial: true,
    preferredFormat: "hex",
    change: function(color){
      var id = $(this).closest('.query_type').attr('query_type_id');
      $.get('/system/query_types/' + id + '/color/',{
        color: color.toHex()
      },function(response){
      })
    }
  });
});

function get_query_type_id(obj){
  return obj.closest('.query_type').attr('query_type_id');
}