{% extends "ajax.tpl" %}
{% if component.query != false %}
	{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'}%}
	{% block js %}
		$('.query[query_id = {{component.query.id}}]').html(get_hidden_content())
		.removeClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<div>
		<b style="font-size:16px">Заявка №{{component.query.number}}</b> (
			{% if component.query.status in statuses|keys %}
				{{statuses[component.query.status]}}
			{% else %}
				Статус не определен
			{% endif %}
		)<button class="close get_query_title">&times;</button>
	</div>
	<div style="height:200px">{{component.query.description}}</div>
	{% endblock html %}
{% endif %}