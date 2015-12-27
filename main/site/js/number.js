var AddedFilesView = Backbone.Marionette.ItemView.extend({
    events: {
        'click .close_dialog': 'close_dialog',
        'click .added_files': 'add_files'
    },
    template: Twig.twig({
        href: '/templates/events/build_added_files.tpl',
        async: false
    }),
    className: 'modal-dialog add_files',
    render: function() {
        $(this.el).html(this.template.render());
    },
    onShow: function(){
        var model = this.model;
        this.dropZone = $("#events-files").dropzone({
            url: '/files/',
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 100,
            maxFiles: 100,
            addRemoveLinks: true,
            dictDefaultMessage: '<div class="btn btn-primary">Прикрепить файлы</div>',
            previewTemplate: $('#preview-template').html(),
            dictRemoveFile: 'Открепить'
        });
        this.dropZone[0].dropzone.on('successmultiple', function(files, res) {
            model.files = _.union(model.files, res);
            $.ajax({
                url: '/numbers/events/' + model.id + '/',
                method: 'PUT',
                data: model
            }).done(function(res){
                var template = Twig.twig({
                    href: '/templates/numbers/event.tpl',
                    async: false
                });
                $('.event[event_id = ' + model.id + ']').replaceWith(template.render({"n2e": model}));
                MyApp.modal.hideModal();
            });
        });
    },
    close_dialog: function(){
        MyApp.modal.hideModal();
    },
    add_files: function(){
        if(this.dropZone[0].dropzone.getQueuedFiles().length > 0) {
            this.dropZone[0].dropzone.processQueue();
        }
    }
});

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
        var template = Twig.twig({
            href: '/templates/events/default_page.tpl',
            async: false
        });
        $('.workspace').html(template.render());
        $('.nav:not(#side-menu) > li').removeClass('active');
        $('.get_events').addClass('active');
        $('.workspace-path').empty();
        var events_date = localStorage.getItem('events_date');
        var date = _.isEmpty(events_date)?moment():moment(events_date);
        $('#events-datetimepicker').datetimepicker({
            inline: true,
            format: 'DD.MM.YYYY',
            locale: moment.locale('ru')
        }).on("dp.change", function(e) {
            localStorage.setItem('events_date', e.date.toISOString());
            $.get('/numbers/events/days/' + e.date.format('DD-MM-YYYY') + '/')
                .done(function(res){
                    var template = Twig.twig({
                        href: '/templates/numbers/events.tpl',
                        async: false
                    });
                    $('.workspace').find('.events').html(template.render(res))
                })
        }).data('DateTimePicker').date(date);
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

    }).on('click', '.get_dialog_create_event', function(){
      var id = $(this).attr('number');
        $.get('/numbers/events/dialogs/create/',
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
      var id = $(this).closest('.event').attr('event_id');
      $.get('/numbers/events/' + id + '/dialog_edit/',
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
    })
    .on('click', '.get_dialog_added_files', function(){
        var event_id = $(this).closest('.event').attr('event_id');
        $.get('/numbers/events/' + event_id + '/')
            .done(function(res){
                var AddedFiles = new AddedFilesView({"model": res.event});
                MyApp.modal.show(AddedFiles);
            });
    })
    .on('click', '.delete-file', function(){
        var file = $(this).closest('li').attr('data-file');
        var event_id = $(this).closest('.event').attr('event_id');
        swal({
            title: "Вы уверены?",
            text: "Вы не сможете восстановить удаленный файл!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Удалить",
            cancelButtonText: "Отмена",
            closeOnConfirm: false
        }, function () {
            $.get('/numbers/events/' + event_id + '/')
                .done(function(res){
                    var model = res.event;
                    model.files = _.without(model.files, _.findWhere(model.files, {path: file}));
                    $.ajax({
                        url: '/numbers/events/' + model.id + '/',
                        method: 'PUT',
                        data: model
                    }).done(function(res){
                        var template = Twig.twig({
                            href: '/templates/numbers/event.tpl',
                            async: false
                        });
                        $('.event[event_id = ' + model.id + ']').replaceWith(template.render({"n2e": model}));
                        swal("Удалено!", "", "success");
                    });
                });
        });
    });

});