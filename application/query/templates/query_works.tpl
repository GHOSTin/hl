{% for current_work in component.works.structure[query.id] %}
	{% set work = component.works.works[current_work.work_id] %}
	<li work="{{work.id}}">{{work.name}} <span class="cm get_dialog_remove_work">удалить</span>
		<div>Время: с {{current_work.time_open|date('H:i d.m.Y')}} до {{current_work.time_close|date('H:i d.m.Y')}}</div>
	</li>
{% endfor %}