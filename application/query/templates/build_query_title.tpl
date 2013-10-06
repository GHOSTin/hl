<div  class="query-wrap
{% if query.get_status() in ['working','open', 'close', 'reopen']%}
	query_status_{{query.get_status()}}
{% endif %}">
	<div>
		{% if query.get_initiator() == 'number' %}
			<img src="/templates/default/images/icons/xfn-friend.png" />
		{% else %}
			<img src="/templates/default/images/icons/home-medium.png" />
		{% endif %}
		<b>№{{ query.get_number() }}</b> {{ query.get_time_open()|date("H:i d.m.y") }} {{ query.get_street().get_name() }}, дом №{{ query.get_house().get_number() }}
		{% if query.get_initiator() == 'number' %}
			{% if component.numbers.numbers != false %}
				{% set number = component.numbers.numbers[component.numbers.structure[query.id].true[0]] %}
				, кв. {{number.flat_number}} ({{number.fio}})
			{% endif %}
		{% endif %}
	</div>
	{% if query.get_initiator() == 'number' %}
		кв. {{ number.flat_number }}
	{% endif %}
	{{ query.get_description() }}
</div>