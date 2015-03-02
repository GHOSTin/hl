<option value="0">Выберите событие</option>
{% for event in workgroup.get_events() %}
<option value="{{ event.get_id() }}">{{ event.get_name() }}</option>
{% endfor %}