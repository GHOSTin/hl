{% extends "ajax.tpl" %}
{% set user = component.user %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.remove_user').click(function(){
		$.get('remove_user',{
			id: {{request.GET('id') }},
			user_id: {{ user.get_id() }},
			type: '{{ request.GET('type') }}'
			},function(r){
				init_content(r);
				$('.query[query_id = {{ request.GET('id') }}] .query-users-{{ request.GET('type') }} li[user={{ user.get_id() }}]').remove();
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Удаление пользователя</h3>
    </div>	
	<div class="modal-body">
		Удалить из заявки пользователя "{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}"?
	</div>
	<div class="modal-footer">
		<div class="btn remove_user">Удалить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}