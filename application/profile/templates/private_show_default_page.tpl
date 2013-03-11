{% extends "default.tpl" %}
{% block component %}
	{% if component.user != false %}
		<div>
			ФИО: {{component.user.lastname}} {{component.user.firstname}} {{component.user.middlename}}
		</div>
		<div>
			Идентификатор: {{component.user.id}}
		</div>
		<div>
			Телефон: {{component.user.telephone}} <span class="cm">изменить</span>
		</div>
		<div>
			Сотовый: {{component.user.cellphone}} <span class="cm">изменить</span>
		</div>
	{% else %}
		<div>Информация о профиле не может быть загружена</div>
	{% endif %}
{% endblock component %}
