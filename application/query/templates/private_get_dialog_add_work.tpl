{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.add_work').click(function(){
		var work_id = $('.dialog-select-work  :selected').val();
		if(work_id > 0){
			$.get('add_work',{
				id: {{query.id}},
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
	$('.dialog-select-workgroup').change(function(){
		var workgroup_id = $('.dialog-select-workgroup :selected').val();
		if(workgroup_id > 0){
			$.get('get_work_options',{
				id: workgroup_id
				},function(r){
					$('.dialog-select-work').html(r).attr('disabled', false);
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
<div class="modal-content">
    <div class="modal-header">
        <h3>Работы</h3>
    </div>	
	<div class="modal-body">
		<div class="form-group">
			<select class="dialog-select-workgroup form-control">
				<option value="0">Выберите группу работ</option>
			{% for workgroup in component.workgroups %}
				<option value="{{workgroup.id}}">{{workgroup.name}}</option>
			{% endfor %}
			</select>
			<select class="dialog-select-work form-control" disabled="disabled">
				<option value="0">Ожидание...</option>
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
{% endblock html %}