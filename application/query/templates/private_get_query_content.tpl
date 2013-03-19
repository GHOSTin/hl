{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'}%}
	{% block js %}
		$('.query[query_id = {{component.queries[0].id}}]').html(get_hidden_content())
		.removeClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<div style="padding:5px">
		<h4>
			{% if query.initiator == 'number' %}
				<img src="/templates/default/images/icons/xfn-friend.png" />
			{% else %}
				<img src="/templates/default/images/icons/home-medium.png" />
			{% endif %}
			Заявка №{{component.queries[0].number}} (
			{% if component.queries[0].status in statuses|keys %}
				{{statuses[component.queries[0].status]}}
			{% else %}
				Статус не определен
			{% endif %}
			)
			<button class="close get_query_title">&times;</button>
		</h4>
	</div>
	<div>
		<ul class="nav nav-pills">
			<li><a href="#" class="get_documents">Документы</a></li>
			<li><a href="#">История заявки</a></li>
			<li><a href="#">Закрыть заявку</a></li>
			<li><a href="#">Передать в работу</a></li>
		</ul>
	</div>
	<div style="height:400px">
		<ul>
			<li class="query-general">Общая информация
				{% include '@query/query_description.tpl' %}
			</li>
			<li class="query-numbers">Лицевые счета</li>
			<li class="query-performers">Исполнители</li>
			<li class="query-managers">Ответственные</li>
			<li class="query-works">Работы</li>
			<li class="query-material">Материалы</li>
		</ul>
	</div>
	{% endblock html %}
{% endif %}