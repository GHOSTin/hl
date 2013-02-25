{% if initiator == 'number' %}
	{%if number != false %}
		<ul>
			<li>л/с №{{number.number}}</li>
			<li>Владелец №{{number.fio}}</li>
			<li>Телефон: {{number.telephone}}</li>
			<li>Сотовый: {{number.cellphone}}</li>
			<li>Контактное лицо: {{number.contact_fio}}</li>
			<li>Телефон контактного лица: {{number.contact_telephone}}</li>
			<li>Сотовые телефон контактного лица: {{number.contact_cellphone}}</li>
		</ul>
	{% endif %}
{% elseif initiator == 'house' %}
	{% if house != false %}
		<div>
			{{house.street_name}}, дом №{{house.number}}
		</div>
	{% endif %}
{% endif %}