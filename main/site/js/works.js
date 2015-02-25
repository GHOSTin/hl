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
  }).on('click', '.get_dialog_add_work', function(){
    $.get('get_dialog_add_work',{
      id: get_workgroup_id($(this))
    },function(r){
      init_content(r);
    });
  }).on('click', '.get_dialog_exclude_work', function(){
    $.get('get_dialog_exclude_work',{
      workgroup_id: get_workgroup_id($(this)),
      work_id: get_work_id($(this))
    },function(r){
      init_content(r);
    });
  }).on('click', '.get_dialog_rename_workgroup', function(){
    $.get('get_dialog_rename_workgroup',{
      workgroup_id: get_workgroup_id($(this))
    },function(r){
      init_content(r);
    });
  }).on('click', '.get_dialog_create_workgroup', function(){
    $.get('get_dialog_create_workgroup',{
    },function(r){
      init_content(r);
    });
  }).on('click', '.work-title', function(){
    var self = $(this);
    if(self.siblings().is('.work-content')){
      self.siblings('.work-content').remove();
    }else{
      $.get('get_work_content',{
        id: get_work_id(self)
      },function(response){
        self.after(response)
      });
    }
  }).on('click', '.get_dialog_rename_work', function(){
    $.get('get_dialog_rename_work',{
      id: get_work_id($(this))
    },function(r){
      init_content(r);
    });
  }).on('click', '.get_dialog_create_work', function(){
    $.get('get_dialog_create_work',{
    },function(r){
      init_content(r);
    });
  }).on('click', '.get_dialog_create_event', function(){
    $.get('get_dialog_create_event',{
    },function(r){
      init_content(r);
    });
  });
});

// возвращает идентификатор группы работ
function get_workgroup_id(obj){
    return obj.closest('.workgroup').attr('workgroup_id');
}

// возвращает идентификатор работы
function get_work_id(obj){
    return obj.closest('.work').attr('work_id');
}