{% extends '../default/dialog.tpl' %}
{% block title %}Смена пароля{% endblock title %}
{% block dialog %}
	<p>
		Сменить пароль пользователю "{{ user.fio }}"?
	</p>
	<div class="form-group">
		<label>Новый пароль</label>
		<input type="password" class="form-control dialog-new_password" required>
	</div>
	<div class="form-group">
		<label>Подтверждение</label>
		<input type="password" class="form-control dialog-confirm_password" required>
	</div>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-default update_password">Сохранить</div>
{% endblock buttons %}