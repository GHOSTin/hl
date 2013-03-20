<ul class="query-sub">
	<li>Время открытия: {{component.queries[0].time_open|date('H:i d.m.Y')}}</li>
	<li>Адрес: {{component.queries[0].street_name}}, дом №{{component.queries[0].house_number}}</li>
	<li>Тип оплаты: {{component.queries[0].payment_status}}</li>
	<li>Тип работ: {{component.queries[0].worktype_id}}</li>
	<li>Тип заявки: {{component.queries[0].warning_status}}</li>
	<li>Диспетчер:</li>
</ul>