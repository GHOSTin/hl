{% extends "dialog.tpl" %}

{% block title %}Добавление события{% endblock title %}

{% block dialog %}
    <div class="form-group">
      <select class="form-control dialog-streets">
        <option value="0">Выберите улицу</option>
        {% for street in streets %}
          <option value="{{ street.get_id() }}">{{ street.get_name() }}</option>
        {% endfor %}
      </select>
    </div>
    <div class="form-group">
      <select class="form-control dialog-houses" disabled="disabled">
        <option value="0">Ожидание...</option>
      </select>
    </div>
    <div class="form-group">
      <select class="form-control dialog-numbers" disabled="disabled">
        <option value="0">Ожидание...</option>
      </select>
    </div>
    <div class="form-group">
      <select class="form-control dialog-select-category">
        <option value="0">Выберите категорию</option>
      {% for workgroup in workgroups %}
        <option value="{{ workgroup.get_id() }}">{{ workgroup.get_name() }}</option>
      {% endfor %}
      </select>
    </div>
    <div class="form-group">
      <select class="form-control dialog-select-event" disabled>
        <option>Ожидание...</option>
      </select>
    </div>
    <div class="form-group">
      <input type="text" class="form-control dialog-date" value="{{ "now"|date("d.m.Y") }}">
    </div>
    <div class="form-group">
      <label>Примечание</label>
      <textarea class="dialog-com form-control" rows="5"></textarea>
    </div>
    <form id="my-awesome-dropzone" class="dropzone" action="#">
      <div class="dropzone-previews"></div>
    </form>
{% endblock dialog %}

{% block buttons %}
	<button type="button" class="btn btn-primary create_event">Создать</button>
{% endblock buttons %}

{% block script %}
$(document).ready(function() {
  $('.dialog-streets').change(function(){
      var id = $('.dialog-streets :selected').val();
      if(id > 0){
        $.get('/numbers/events/streets/' + id + '/houses/',
      function(r){
        $('.dialog-houses').html(r).prop('disabled', false);
      });
    }
  });
  $('.dialog-houses').change(function(){
      var id = $('.dialog-houses :selected').val();
      if(id > 0){
        $.get('/numbers/events/houses/' + id + '/numbers/',
      function(r){
        $('.dialog-numbers').html(r).prop('disabled', false);
      });
    }
  });
  var $dropZone = $("#my-awesome-dropzone").dropzone({
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
    $('.create_event').click(function(e){
      e.preventDefault();
      e.stopPropagation();
      if($dropZone[0].dropzone.getQueuedFiles().length > 0) {
        $dropZone[0].dropzone.processQueue();
      } else {
        $.post('/numbers/events/',{
          number: $('.dialog-numbers :selected').val(),
          event: $('.dialog-select-event').val(),
          date: $('.dialog-date').val(),
          comment: $('.dialog-com').val(),
          files: null
          },function(r){
            $('.dialog').modal('hide');
            $('.workspace').find('.events').prepend(r);
            $('.cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
          }
        );
      }
    });
    $dropZone[0].dropzone.on('successmultiple', function(files, res) {
      $.post('/numbers/events/',{
        number: $('.dialog-numbers :selected').val(),
        event: $('.dialog-select-event').val(),
        date: $('.dialog-date').val(),
        comment: $('.dialog-com').val(),
        files: res
        },function(r){
          $('.dialog').modal('hide');
          $('.workspace').find('.events').prepend(r);
          $('.cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
        }
      );
    });
    $('.dialog-select-category').change(function(){
      var category_id = $(this).val();
      if(category_id > 0){
        $.get('get_events', {
          id: category_id
        }, function(r){
          $('.dialog-select-event').html(r);
          $('.dialog-select-event').prop('disabled', false);
        });
      }
    });
    $('.dialog-date').datetimepicker({
      format: 'DD.MM.YYYY', locale: moment.locale('ru')
    });
});
{% endblock script %}