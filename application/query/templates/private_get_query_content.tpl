{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{%set query = component.queries[0] %}
	{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'}%}
	{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'}%}
	{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'recalculation': 'плановая'}%}
	{% block js %}
		$('.query[query_id = {{query.id}}]').html(get_hidden_content())
		.removeClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<h4>
		{% if query.initiator == 'number' %}
			<img src="/templates/default/images/icons/xfn-friend.png" />
		{% else %}
			<img src="/templates/default/images/icons/home-medium.png" />
		{% endif %}
		Заявка №{{component.queries[0].number}} (
		{% if query.status in statuses|keys %}
			{{statuses[query.status]}}
		{% else %}
			Статус не определен
		{% endif %}
		)
		<button class="close get_query_title">&times;</button>
	</h4>
	<ul class="nav nav-pills">
		<li><a href="#" class="get_documents">Документы</a></li>
		<li><a href="#">История заявки</a></li>
		<li><a href="#">Закрыть заявку</a></li>
		<li><a href="#">Передать в работу</a></li>
	</ul>
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
	<ul>
		<li class="query-numbers">
			<h5>Лицевые счета</h5>
		</li>
		<li class="query-performers">
			<h5>Исполнители</h5>
		</li>
		<li class="query-managers">
			<h5>Ответственные</h5>
		<li class="query-works">
			<h5>Работы</h5>
		</li>
		<li class="query-material">
			<h5>Материалы</h5>
		</li>
	</ul>
	{% endblock html %}
{% endif %}