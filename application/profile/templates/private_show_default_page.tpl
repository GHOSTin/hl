{% if user != false %}
<div>
	ФИО: {{user.lastname}} {{user.firstname}} {{user.middlename}}
</div>
<div>
	Идентификатор: {{user.id}}
</div>
<div>
	Телефон: {{user.telephone}}
</div>
<div>
	Сотовый: {{user.cellphone}}
</div>
{% endif %}