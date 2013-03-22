{% if component.queries != false %}
	{% for query in component.queries %}
		<div class="query get_query_content
		{% if query.status in ['working','open', 'close', 'reopen']%}
			query_status_{{query.status}}
		{% else %}
			query_status_default
		{% endif %}
		" query_id="{{query.id}}">
			<div>
				{% if query.initiator == 'number' %}
					<img src="/templates/default/images/icons/xfn-friend.png" />
				{% else %}
					<img src="/templates/default/images/icons/home-medium.png" />
				{% endif %}
				<b>№{{query.number}}</b> {{query.time_open|date("H:i d.m.y")}}
				{{query.street_name}}, дом №{{query.house_number}}
				{% if query.initiator == 'number' %}
					{% if component.numbers.numbers != false %}
						{% set number = component.numbers.numbers[component.numbers.structure[query.id].true[0]] %}
						, кв. {{number.flat_number}} (л/с №{{number.number}}, {{number.fio}})
					{% endif %}
				{% endif %}
			</div>
			{{query.description}}
		</div>
	{% endfor %}
{% else %}
    Нет заявок
{% endif %}