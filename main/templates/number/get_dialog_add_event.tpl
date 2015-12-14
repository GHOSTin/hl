{% extends "ajax.tpl" %}

{% block js %}
    show_dialog(get_hidden_content());
    var $dropZone = $("#my-awesome-dropzone").dropzone({
      url: '/files',
      autoProcessQueue: false,
      uploadMultiple: true,
      parallelUploads: 100,
      maxFiles: 100,
      addRemoveLinks: true,
      dictDefaultMessage: '<div class="btn btn-primary">Прикрепить файлы</div>',
      previewTemplate: $('#preview-template').html(),
      dictRemoveFile: 'Открепить'
    });
    $('.add_event').click(function(e){
      e.preventDefault();
      e.stopPropagation();
      $dropZone[0].dropzone.processQueue()
    });
    $dropZone[0].dropzone.on('successmultiple', function(files, res) {
      $.post('/numbers/{{ number.get_id() }}/events/',{
        event: $('.dialog-select-event').val(),
        date: $('.dialog-date').val(),
        comment: $('.dialog-com').val(),
        files: res
        },function(r){
          $('.dialog').modal('hide');
          $('.workspace').html(r);
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
{% endblock %}

{% block html %}
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Добавление события</h3>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <select class="form-control dialog-select-category" autofocus>
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
        <label>Комментарий</label>
        <textarea class="dialog-com form-control" rows="5"></textarea>
      </div>
      <form id="my-awesome-dropzone" class="dropzone" action="#">
        <div class="dropzone-previews"></div>
      </form>
    </div>
    <div class="modal-footer">
      <div class="btn btn-primary add_event ">Сохранить</div>
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock %}