{% extends "ajax.tpl" %}
{% set query = component.query %}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'}%}
{% block js %}
	show_dialog(get_hidden_content());
	$('.update_work_type').click(function(){
		$.get('update_work_type',{
			id: {{ query.get_id() }},
			type: $('.dialog-work_type :selected').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Тип оплаты заявки</h3>
    </div>	
	<div class="modal-body">
		<label>Тип оплаты:</label>
		<select class="dialog-work_type form-control">
			{% for work_type in component.work_types %}
				<option value="{{ work_type.get_id() }}"
				{% if query.get_work_type().get_id() == work_type.get_id() %}
					selected
				{% endif %}
				>{{ work_type.get_name() }}</option>
			{% endfor %}
		</select>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary update_work_type">Сохранить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}