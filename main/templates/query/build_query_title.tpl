<div  class="query-wrap
{% if query.get_status() in ['working','open', 'close', 'reopen']%}
	query_status_{{query.get_status()}}
{% endif %}">
	<div>
		{% if query.get_initiator() == 'number' %}
			<i class="glyphicon glyphicon-user notification-center-icon" style="color:#AADDAF; font-size:12px" alt="Заявка на личевой счет"></i>
		{% else %}
      <i class="glyphicon glyphicon-home notification-center-icon" style="color:#C8D5C8; font-size:12px" alt="Заявка на дом"></i>
		{% endif %}
    {% if query.get_request %}
     <i class="glyphicon glyphicon-eye-open notification-center-icon" style="font-size:12px" alt="Заявка из личного кабинета"></i>
    {% endif %}
		<b> №{{ query.get_number() }}</b> {{ query.get_time_open()|date("H:i d.m.y") }} {{ query.get_house().get_street().get_name() }}, дом №{{ query.get_house().get_number() }}
		{% if query.get_initiator() == 'number' %}
			{% for number in query.get_numbers() %}
				, кв.{{ number.get_flat().get_number() }} {{ number.get_number() }} ({{ number.get_fio() }})
			{% endfor %}
		{% endif %}
	</div>
	{% if query.get_initiator() == 'number' %}
		{% for number in query.get_numbers() %}
			кв.{{ number.get_flat().get_number() }}
		{% endfor %}
	{% endif %}
	{{ query.get_description() }}
</div>