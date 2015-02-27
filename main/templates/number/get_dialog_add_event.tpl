{% extends "ajax.tpl" %}

{% block js %}
    show_dialog(get_hidden_content());
    $('.add_event').click(function(){
        $.get('add_event',{
            id: {{ number.get_id() }},
            event: $('.dialog-select-event').val(),
            date: $('.dialog-date').val()
        },function(r){
          $('.number[number="{{ number.get_id() }}"] .number-content').remove();
          init_content(r);
          $('.dialog').modal('hide');
        });
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
    $('.dialog-date').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
    $('.dialog-date').datepicker('hide');
  });
{% endblock %}

{% block html %}
<div class="modal-content">
  <div class="modal-header">
    <h3>Добавление события</h3>
  </div>
  <div class="modal-body">
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
  </div>
  <div class="modal-footer">
    <div class="btn btn-primary add_event ">Сохранить</div>
    <div class="btn btn-default close_dialog">Отмена</div>
  </div>
</div>
{% endblock %}