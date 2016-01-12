{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.add_work').click(function(){
		var work_id = $('.dialog-select-work  :selected').val();
		if(work_id > 0){
			$.get('add_work',{
				id: {{ query.get_id() }},
				work_id: work_id,
				begin_date: $('.dialog-begin_date').data("DateTimePicker").date().format('X'),
				end_date: $('.dialog-end_date').data("DateTimePicker").date().format('X')
				},function(r){
					init_content(r);
					$('.dialog').modal('hide');
				});
		}
	});
	$('.dialog-begin_date').datetimepicker({
        format: 'DD.MM.YYYY H:m',
        locale: 'ru',
        defaultDate: moment(),
        sideBySide: true,
        stepping: 5
    });
	$('.dialog-end_date').datetimepicker({
        format: 'DD.MM.YYYY H:m',
        locale: 'ru',
        defaultDate: moment(),
        sideBySide: true,
        stepping: 5
    });
{% endblock js %}

{% block html %}
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
        <h3>Диалог добавления работы</h3>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <select class="dialog-select-work form-control">
          <option value="0">Выберите работу</option>
          {% for work in query.get_work_type().get_works() %}
          <option value="{{ work.get_id() }}">{{ work.get_name() }}</option>
          {% endfor %}
        </select>
      </div>
      <div class="form-group">
        <label class="control-label">Дата начала</label>
        <input type="text" class="dialog-begin_date form-control">
      </div>
      <div  class="form-group">
        <label class="control-label col-2">Дата конца</label>
        <input type="text" class="dialog-end_date form-control">
      </div>
    </div>
    <div class="modal-footer">
      <div class="btn btn-primary add_work">Сохранить</div>
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock html %}