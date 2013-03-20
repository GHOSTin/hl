{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'}%}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'recalculation': 'плановая'}%}
<ul class="query-sub">
	<li>Время открытия: {{query.time_open|date('H:i d.m.Y')}}</li>
	<li>Адрес: {{query.street_name}}, дом №{{query.house_number}}</li>
	<li>Тип оплаты: {% if query.payment_status in payment_statuses|keys %}
						{{payment_statuses[query.payment_status]}}
					{% endif %}</li>
	<li>Тип работ: {{query.work_type_name}}</li>
	<li>Тип заявки: {% if query.warning_status in warning_statuses|keys %}
						{{warning_statuses[query.warning_status]}}
					{% endif %}</li>
	<li>Диспетчер:</li>
</ul>