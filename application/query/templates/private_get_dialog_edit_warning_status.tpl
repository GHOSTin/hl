{% extends "ajax.tpl" %}
{% set query = component.query %}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'}%}
{% block js %}
	show_dialog(get_hidden_content());
	$('.update_warning_status').click(function(){
		$.get('update_warning_status',{
			id: {{query.id}},
			status: $('.dialog-warning_status :selected').val()
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
		<select class="dialog-warning_status form-control">
			{% for key, warning_status in warning_statuses %}
				<option value="{{key}}"
				{% if query.warning_status == key%}
					selected
				{% endif %}
				>{{warning_status}}</option>
			{% endfor %}
		</select>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary update_warning_status">Сохранить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}