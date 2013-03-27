{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.add_user').click(function(){
		var user_id = $('.dialog-select-user :selected').val();
		if(user_id > 0){
			$.get('add_user',{
				id: {{query.id}},
				user_id: $('.dialog-select-user :selected').val(),
				type: '{{component.type}}'
				},function(r){
					init_content(r);
					$('.dialog').modal('hide');
				});
		}
	});
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>{% if component.type == 'manager' %}
        		Менеджеры
        	{% else %}
        		Исполнители
        	{% endif %}
        </h3>
    </div>	
	<div class="modal-body">
		<select class="dialog-select-user">
			<option value="0">Выберите пользователя</option>
		{% for user in component.users %}
			<option value="{{user.id}}">{{user.lastname}} {{user.firstname}} {{user.middlename}}</option>
		{% endfor %}
		</select>
	</div>
	<div class="modal-footer">
		<div class="btn add_user">Сохранить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}