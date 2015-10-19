$(document).ready(function(){

    $('body').on('click', '.get_street_content', function(){
      var id = $(this).attr('street');
      $.getJSON('/numbers/streets/' + id + '/',
      function(r){
        $('.workspace').html(r['workspace']);
        $('.workspace-path').html(r['path']);
      });

    }).on('click', '.get_streets', function(){
      $.getJSON('/numbers/streets/',
      function(r){
        $('.workspace').html(r['workspace']);
        $('.workspace-path').empty();
        $('.nav > li').removeClass('active');
        $('.get_streets').addClass('active');
      });

    }).on('click', '.get_outages', function(){
      $.getJSON('/numbers/outages/',
      function(r){
        $('.workspace').html(r['workspace']);
        $('.nav > li').removeClass('active');
        $('.get_outages').addClass('active');
      });

    }).on('click', '.get_dialog_create_outage', function(){
      $.get('/numbers/outages/dialogs/create/',
        function(r){
          init_content(r);
        });

    }).on('click', '.get_house_content', function(){
      var id = $(this).attr('house');
      $.getJSON('/numbers/houses/' + id + '/',
      function(r){
        $('.workspace').html(r['workspace']);
        $('.workspace-path').html(r['path']);
      });

    }).on('click', '.get_number_content', function(){
      var id = $(this).parent().attr('number');
      $.getJSON('/numbers/' + id + '/',
      function(r){
        $('.workspace').html(r['workspace']);
        $('.workspace-path').html(r['path']);
        $('.cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
      });

    }).on('click', '.get_dialog_edit_department', function(){
        $.get('get_dialog_edit_department',{
            house_id: $(this).attr('house')
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования счетчика
    }).on('click', '.get_dialog_edit_number', function(){
        $.get('get_dialog_edit_number',{
            id: $(this).attr('number')
            },function(r){
                init_content(r);
            });

    }).on('click', '.get_dialog_generate_password', function(){
      id = $(this).attr("number");
      $.get('/numbers/' + id + '/get_dialog_generate_password/',
        function(r){
          init_content(r);
        });

    }).on('click', '.get_dialog_add_event', function(){
        $.get('get_dialog_add_event',{
            id: $(this).attr('number')
            },function(r){
                init_content(r);
            });

    }).on('click', '.get_dialog_exclude_event', function(){
        $.get('get_dialog_exclude_event',{
            id: $(this).attr('number'),
            event_id: $(this).parent().attr('event_id'),
            time: $(this).parent().attr('time')
            },function(r){
                init_content(r);
            });

    }).on('click', '.get_dialog_contacts', function(){
        var id = $(this).attr('number');
        $.get('/numbers/' + id + '/contacts/'
          ,function(r){
              init_content(r);
          });
    });
});