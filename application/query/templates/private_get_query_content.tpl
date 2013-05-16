{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{%set query = component.queries[0] %}
	{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'}%}
	{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'}%}
	{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'}%}
	{% if query.initiator == 'number' %}
				{% if component.numbers.numbers != false %}
					{% set number = component.numbers.numbers[component.numbers.structure[query.id].true[0]] %}
				{% endif %}
			{% endif %}
	{% block js %}
		$('.query[query_id = {{query.id}}]').html(get_hidden_content())
		.removeClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<div  class="query-wrap
{% if query.status in ['working','open', 'close', 'reopen']%}
	query_status_{{query.status}}
{% endif %}">
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
	{% if rules.showDocs == true %}
		<li><a href="#" class="get_documents">Документы</a></li>
	{% endif %}
	{% if rules.closeQuery == true and query.status in ['open', 'working'] %}
		<li><a href="#" class="get_dialog_close_query">Закрыть заявку</a></li>
	{% endif %}
	{% if rules.closeQuery == true and query.status == 'reopen' %}
		<li><a href="#" class="get_dialog_reclose_query">Перезакрыть заявку</a></li>
	{% endif %}
	{% if query.status == 'open' %}
		<li><a href="#" class="get_dialog_to_working_query">Передать в работу</a></li>
	{% endif %}
	{% if query.status == 'close' %}
		<li><a href="#" class="get_dialog_reopen_query">Переоткрыть</a></li>
	{% endif %}
	</ul>
	<ul class="query-general">
		<li>Время открытия: {{query.time_open|date('H:i d.m.Y')}}</li>
		<li>Адрес: {{query.street_name}}, дом №{{query.house_number}}
			{% if query.initiator == 'number' %}
				{% if component.numbers.numbers != false %}
					, кв. {{number.flat_number}}
					<li>Владелец: {{number.fio}}</li>
					<li>Лицевой счет: №{{number.number}}</li>
				{% endif %}
			{% endif %}
		</li>
		<li>Тип оплаты: <span class="query-general-payment_status">
			{% if query.payment_status in payment_statuses|keys %}
				{{payment_statuses[query.payment_status]}}
			{% endif %}</span>
			{% if query.status in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_payment_status">изменить</span>
			{% endif %}
		</li>
		<li>Тип работ: <span class="query-general-work_type">{{query.work_type_name}}</span>  
			{% if query.status in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_work_type">изменить</span></li>
			{% endif %}
		<li>Тип заявки: 
			<span class="query-general-warning_status">
			{% if query.warning_status in warning_statuses|keys %}
				{{warning_statuses[query.warning_status]}}
			{% endif %}
			</span>
			{% if query.status in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_warning_status">изменить</span>
			{% endif %}
		</li>
		<li>Диспетчер:
		{% if component.users != false %}
			{% set creator = component.users.users[component.users.structure[query.id].creator[0]] %}
			{{creator.lastname}} {{creator.firstname}} {{creator.middlename}}
		{% endif %}</li>
		<li>Описание: <span class="query-general-description">{{query.description}}</span>
			{% if query.status in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_description">изменить</span>
			{% endif %}</li>
		{% if query.status in ['close', 'reopen'] %}
			<li>Причина закрытия: {{query.close_reason}}</li>
		{% endif %}
		<lo>
			<div>Контактная информация
			{% if query.status in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_edit_contact_information">изменить</span></div>
			{% endif %}
			<ul class="query-general-contacts">
				<li>ФИО: {{query.contact_fio}}</li>
				<li>Телефон: {{query.contact_telephone}}</li>
				<li>Сотовый: {{query.contact_cellphone}}</li>
			</ul>
		</li>
	</ul>
	<ul>
		<li class="query-numbers">
			<h5>Лицевые счета</h5>
		</li>
		<li class="query-users">
			<h5>Участники</h5>
		</li>
		<li class="query-works">
			<h5>Работы</h5>
		</li>{#
		<li class="query-material">
			<h5>Материалы</h5>
		</li>#}
	</ul>
	</div>
	{% endblock html %}
{% endif %}