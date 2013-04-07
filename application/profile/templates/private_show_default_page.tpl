{% extends "default.tpl" %}
{% block component %}
	{% set user = component.users[0] %}
	<div class="profile" user="{{ user.id }}">
		<div>
			ФИО: {{ user.lastname }} {{ user.firstname }} {{ user.middlename }} ( {{ user.id }} )
		</div>
		
		<div>
			Телефон: {{ user.telephone }} <span class="cm">изменить</span>
		</div>
		<div>
			Сотовый: {{ user.cellphone }} <span class="cm get_dialog_edit_cellphone">изменить</span>
		</div>
		<div>
			Пароль: ****** <span class="cm get_dialog_edit_password">изменить</span>
		</div>
	</div>
{% endblock component %}
