{% if user != false %}
	<div>
		ФИО: {{user.lastname}} {{user.firstname}} {{user.middlename}}
	</div>
	<div>
		Идентификатор: {{user.id}}
	</div>
	<div>
		Телефон: {{user.telephone}} <span class="cm">изменить</span>
	</div>
	<div>
		Сотовый: {{user.cellphone}} <span class="cm">изменить</span>
	</div>
{% else %}
	<div>Информация о профиле не может быть загружена</div>
{% endif %}
