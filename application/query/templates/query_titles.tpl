{% if component.queries != false %}
    <div class="muted">
        <small>
	        Количество: {{component.queries|length}} заявок
        </small>
    </div>
	{% for query in component.queries %}
		<div class="query get_query_content" query_id="{{ query.get_id() }}">
			{% include '@query/build_query_title.tpl' %}
		</div>
	{% endfor %}
{% else %}
    Нет заявок
{% endif %}