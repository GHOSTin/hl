{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% set query = component.queries[0] %}
	{% block js %}
		$('.query[query_id = {{query.id}}] .query-numbers').append(get_hidden_content())
	{% endblock js %}
	{% block html %}
		{% if component.numbers.numbers %}
			{% if component.numbers.structure[query.id] != false %}
				<ul class="query-sub">
				{% for number_id in component.numbers.structure[query.id].false %}
					{% set number = component.numbers.numbers[number_id] %}
					<li number="{{number.id}}">кв.{{number.flat_number}} {{number.fio}} (№{{number.number}})</li>
				{% endfor %}
				<ul>
			{% endif %}
		{% else %}
			Нет лицевых счетов.
		{% endif %}
	{% endblock html %}
{% endif %}