{% if query != false %}
	{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'}%}
	<script>
		function _content(){
			$('.query[query_id={{query.id}}]').html(get_temp_html())
			.removeClass('get_query_content');
		}
	</script>
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
{% endif %}