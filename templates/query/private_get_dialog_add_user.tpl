{% extends "ajax.tpl" %}
{% set query = response.query %}
{% set groups = response.groups %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.add_user').click(function(){
		var user_id = $('.dialog-select-user :selected').val();
		if(user_id > 0){
			$.get('add_user',{
				id: {{ query.get_id() }},
				user_id: $('.dialog-select-user :selected').val(),
				type: '{{ request.GET('type') }}'
				},function(r){
					init_content(r);
					$('.dialog').modal('hide');
				});
		}
	});
	$('.dialog-select-group').change(function(){
		var group_id = $('.dialog-select-group :selected').val();
		if(group_id > 0){
			$.get('get_user_options',{
				id: group_id
				},function(r){
					$('.dialog-select-user').html(r).attr('disabled', false);
				});
		}
	});
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>{% if request.GET('type') == 'manager' %}
        		Менеджеры
        	{% else %}
        		Исполнители
        	{% endif %}
        </h3>
    </div>
	<div class="modal-body">
		<select class="dialog-select-group form-control">
			<option value="0">Выберите группу</option>
		{% for group in groups %}
			<option value="{{ group.get_id() }}">{{ group.get_name() }}</option>
		{% endfor %}
		</select>
		<select class="dialog-select-user form-control" style="display:block" disabled="disabled">
			<option value="0">Ожидание...</option>
		</select>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary add_user">Сохранить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>
</div>
{% endblock html %}