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
        $('.nav:not(#side-menu) > li').removeClass('active');
        $('.get_streets').addClass('active');
      });

    }).on('click', '.get_outages', function(){
      $.getJSON('/numbers/outages/',
      function(r){
        $('.workspace').html(r['workspace']);
        $('.nav:not(#side-menu) > li').removeClass('active');
        $('.get_outages').addClass('active');
        $('.workspace-path').empty();
      });

    }).on('click', '.get_events', function(){
      $.getJSON('/numbers/events/',
      function(r){
        $('.workspace').html(r['workspace']);
        $('.nav:not(#side-menu) > li').removeClass('active');
        $('.get_events').addClass('active');
        $('.workspace-path').empty();
      });

    }).on('click', '.get_active_outages', function(){
        $.getJSON('/numbers/outages/active/',
        function(r){
          $('.outages').html(r['outages']);
        });

    }).on('click', '.get_today_outages', function(){
        $.getJSON('/numbers/outages/today/',
        function(r){
          $('.outages').html(r['outages']);
        });

    }).on('click', '.get_yesterday_outages', function(){
        $.getJSON('/numbers/outages/yesterday/',
        function(r){
          $('.outages').html(r['outages']);
        });

    }).on('click', '.get_week_outages', function(){
        $.getJSON('/numbers/outages/week/',
        function(r){
          $('.outages').html(r['outages']);
        });

    }).on('click', '.get_last_week_outages', function(){
        $.getJSON('/numbers/outages/lastweek/',
        function(r){
          $('.outages').html(r['outages']);
        });

    }).on('click', '.get_dialog_create_outage', function(){
      $.get('/numbers/outages/dialogs/create/',
        function(r){
          init_content(r);
        });

    }).on('click', '.edit_outage', function(){
      var id = $(this).attr('outage');
      $.get('/numbers/outages/' + id + '/edit/',
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
      var id = $(this).attr('number');
        $.get('/numbers/' + id + '/events/dialog_add/',
        function(r){
          init_content(r);
        });

    }).on('click', '.get_dialog_exclude_event', function(){
      var id = $(this).attr('event_id').split('-');
      $.get('/numbers/' + id[0]+ '/events/' + id[1] + '/' + id[2] + '/dialog_exclude/',
      function(r){
          init_content(r);
      });

    }).on('click', '.get_dialog_edit_event', function(){
      var id = $(this).attr('event_id').split('-');
      $.get('/numbers/' + id[0]+ '/events/' + id[1] + '/' + id[2] + '/dialog_edit/',
      function(r){
          init_content(r);
      });

    }).on('click', '.get_dialog_contacts', function(){
        var id = $(this).attr('number');
        $.get('/numbers/' + id + '/contacts/'
          ,function(r){
              init_content(r);
          });
    }).on('click', '.remove_element',  function(){
      $(this).parent().remove();
    });

});