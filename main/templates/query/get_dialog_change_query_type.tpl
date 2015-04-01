{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.update_query_type').click(function(){
		$.get('update_query_type',{
			id: {{ query.get_id() }},
			type: $('.dialog-query_type :selected').val()
		},function(r){
			init_content(r);
			$('.dialog').modal('hide');
		});
	});
{% endblock %}

{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Тип оплаты заявки</h3>
    </div>
	<div class="modal-body">
		<label>Тип оплаты:</label>
		<select class="dialog-query_type form-control">
			{% for query_type in query_types %}
				<option value="{{ query_type.get_id() }}"
				{% if query.get_query_type().get_id() == query_type.get_id() %}
					selected
				{% endif %}
				>{{ query_type.get_name() }}</option>
			{% endfor %}
		</select>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary update_query_type">Сохранить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>
</div>
{% endblock %}