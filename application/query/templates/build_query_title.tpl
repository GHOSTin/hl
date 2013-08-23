<div  class="query-wrap
{% if query.status in ['working','open', 'close', 'reopen']%}
	query_status_{{query.status}}
{% endif %}">
	<div>
		{% if query.initiator == 'number' %}
			<img src="/templates/default/images/icons/xfn-friend.png" />
		{% else %}
			<img src="/templates/default/images/icons/home-medium.png" />
		{% endif %}
		<b>№{{query.number}}</b> {{query.time_open|date("H:i d.m.y")}} {{query.street_name}}, дом №{{query.house_number}}
		{% if query.initiator == 'number' %}
			{% if component.numbers.numbers != false %}
				{% set number = component.numbers.numbers[component.numbers.structure[query.id].true[0]] %}
				, кв. {{number.flat_number}} ({{number.fio}})
			{% endif %}
		{% endif %}
	</div>
	{% if query.initiator == 'number' %}
		кв. {{number.flat_number}}
	{% endif %}
	{{query.description}}
</div>