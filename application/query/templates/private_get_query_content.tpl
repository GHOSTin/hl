{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'}%}
	{% block js %}
		$('.query[query_id = {{component.queries[0].id}}]').html(get_hidden_content())
		.removeClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<div>
		<b style="font-size:16px">Заявка №{{component.queries[0].number}}</b> (
			{% if component.queries[0].status in statuses|keys %}
				{{statuses[component.queries[0].status]}}
			{% else %}
				Статус не определен
			{% endif %}
		)<button class="close get_query_title">&times;</button>
	</div>
	<div style="height:200px">{{component.queries[0].description}}</div>
	{% endblock html %}
{% endif %}