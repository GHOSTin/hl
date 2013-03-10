{% extends "ajax.tpl" %}
{% if query != false %}
	{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'}%}
	{% block js %}
			$('.query[query_id={{query.id}}]').html(get_temp_html())
			.removeClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<div>
		<b style="font-size:16px">Заявка №{{query.number}}</b> (
			{% if query.status in statuses|keys %}
				{{statuses[query.status]}}
			{% else %}
				Статус не определен
			{% endif %}
		)<button class="close get_query_title">&times;</button>
	</div>
	<div style="height:200px">{{query.description}}</div>
	{% endblock html %}
{% endif %}