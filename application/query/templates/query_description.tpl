<div>
	<dl>
		<dt>Время открытия:</dt><dd>{{component.queries[0].time_open|date('H:i d.m.Y')}}</dd>
		<dt>Адрес:</dt><dd>{{component.queries[0].street_name}}, дом №{{component.queries[0].house_number}}</dd>
		<dt>Тип оплаты:</dt><dd>{{component.queries[0].payment_status}}</dd>
		<dt>Тип работ:</dt><dd>{{component.queries[0].worktype_id}}</dd>
		<dt>Тип заявки:</dt><dd>{{component.queries[0].warning_status}}</dd>
		<dt>Диспетчер:</dt><dd></dd>
	</dl>
</div>