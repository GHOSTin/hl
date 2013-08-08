{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% set user = component.users[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.remove_user').click(function(){
		$.get('remove_user',{
			id: {{query.id}},
			user_id: {{user.id}},
			type: '{{component.type}}'
			},function(r){
				init_content(r);
				$('.query[query_id = {{query.id}}] .query-users-{{component.type}} li[user={{user.id}}]').remove();
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Удаление пользователя</h3>
    </div>	
	<div class="modal-body">
		Удалить из заявки пользователя "{{user.lastname}} {{user.firstname}} {{user.middlename}}"?
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary remove_user">Сохранить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}