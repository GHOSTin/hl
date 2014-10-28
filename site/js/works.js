$(document).ready(function(){

  // выводит контент группы работ
  $('body').on('click', '.workgroup-title', function(){
    var self = $(this);
    if(self.siblings().is('.workgroup-content')){
      self.siblings('.workgroup-content').remove();
    }else{
      $.get('get_workgroup_content',{
        id: get_workgroup_id(self)
      },function(response){
        self.after(response)
      });
    }
  });
});

// возвращает идентификатор группы работ
function get_workgroup_id(obj){
    return obj.closest('.workgroup').attr('workgroup_id');
}