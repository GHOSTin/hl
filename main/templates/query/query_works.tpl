{% for work in query.get_works() %}
	<li work="{{ work.get_id() }}">{{ work.get_name() }}
		{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<a class="text-navy get_dialog_remove_work">удалить</a>
		{% endif %}
		<div>Время: с {{work.get_time_open()|date('d.m.Y H:i')}} до {{work.get_time_close()|date('d.m.Y H:i')}}</div>
	</li>
{% endfor %}