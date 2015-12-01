{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.add_work').click(function(){
		var work_id = $('.dialog-select-work  :selected').val();
		if(work_id > 0){
			$.get('add_work',{
				id: {{ query.get_id() }},
				work_id: work_id,
				begin_hours: $('.dialog-begin_hours :selected').val(),
				begin_minutes: $('.dialog-begin_minutes :selected').val(),
				begin_date: $('.dialog-begin_date').val(),
				end_hours: $('.dialog-end_hours :selected').val(),
				end_minutes: $('.dialog-end_minutes :selected').val(),
				end_date: $('.dialog-end_date').val()
				},function(r){
					init_content(r);
					$('.dialog').modal('hide');
				});
		}
	});
	$('.dialog-begin_date').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
		$('.dialog-begin_date').datepicker('hide');
	});
	$('.dialog-end_date').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
		$('.dialog-end_date').datepicker('hide');
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
      <div class="form-group form-inline">
        <label class="control-label col-2">Дата начала</label>
        <select class="dialog-begin_hours form-control" style="width: 75px;">
        {% for i in 1..24 %}
            <option value="{{i}}">{{i}}</option>
        {% endfor %}
        </select>
        <select class="dialog-begin_minutes form-control" style="width: 75px;">
        {% for i in range(0, 55, 5) %}
            <option value="{{i}}">{{i}}</option>
        {% endfor %}
        </select>
        <input type="text" class="dialog-begin_date form-control" value="{{'now'|date('d.m.Y')}}" style="width: 120px;">
      </div>
      <div  class="form-group form-inline">
        <label class="control-label col-2">Дата конца</label>
        <select class="dialog-end_hours form-control" style="width:75px">
        {% for i in 1..24 %}
          <option value="{{i}}">{{i}}</option>
        {% endfor %}
        </select>
        <select class="dialog-end_minutes form-control" style="width:75px">
        {% for i in range(0, 55, 5) %}
          <option value="{{i}}">{{i}}</option>
        {% endfor %}
        </select>
        <input type="text" class="dialog-end_date form-control" value="{{'now'|date('d.m.Y')}}" style="width:120px" />
      </div>
    </div>
    <div class="modal-footer">
      <div class="btn btn-primary add_work">Сохранить</div>
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock html %}