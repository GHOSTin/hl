{% for work in query.get_works() %}
	<li work="{{ work.get_id() }}">{{ work.get_name() }}
		{% if query.get_status() in ['open', 'working', 'reopen'] %}
			<span class="cm get_dialog_remove_work">удалить</span>
		{% endif %}
		<div>Время: с {{work.get_time_open()|date('H:i d.m.Y')}} до {{work.get_time_close()|date('H:i d.m.Y')}}</div>
	</li>
{% endfor %}