{% extends "ajax.tpl" %}
{% block js %}
    $('.queries').html(get_hidden_content('._queries'));
{% endblock js %}
{% block html %}
    <div class="_queries">
        <div class="muted">
            <small>
    	        Количество: {{ response.queries|length }} заявок
            </small>
        </div>
    	{% for query in response.queries %}
    		<div class="query get_query_content" query_id="{{ query.get_id() }}">
    			{% include '@query/build_query_title.tpl' %}
    		</div>
        {% else %}
             Нет заявок
    	{% endfor %}
    </div>
{% endblock html %}