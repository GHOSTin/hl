{% extends "ajax.tpl" %}
{% set user = component.users[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.update_password').click(function(){
		$.get('update_password',{
			id: {{ user.id }},
			new_password: $('.dialog-new_password').val(),
			confirm_password: $('.dialog-confirm_password').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Смена пароля</h3>
    </div>	
	<div class="modal-body">
		<p>
			Сменить пароль пользователю "{{ user.lastname }} {{ user.firstname }} {{ user.middlename }}"?
		</p>
		<dl class="dl-horizontal">
			<dt>Новый пароль</dt>
			<dd><input type="text" class="dialog-new_password"></dd>
			<dt>Подтверждение</dt>
			<dd><input type="text" class="dialog-confirm_password"></dd>
		</dl>
	</div>
	<div class="modal-footer">
		<div class="btn update_password">Сохранить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}