{% if component.queries != false %}
	{{component.queries|length}} заявок
	{% for query in component.queries %}
		<div class="query get_query_content" query_id="{{query.id}}">
			{% include '@query/build_query_title.tpl' %}
		</div>
	{% endfor %}
{% else %}
    Нет заявок
{% endif %}