{% extends "ajax.tpl" %}
{% block js %}
    $('.queries').html(get_hidden_content('._queries'));
    $('.timeline').html(get_hidden_content('._timeline'));
    $('.filter-content-select-status').val('all');
    $('.filter-content-select-street').val('all');
    $('.filter-content-select-house').html('<option value="all">Ожидание...</option>');
    $('.filter-content-select-house').val('all');
    $('.filter-content-select-house').attr('disabled', true);
    $('.filter-content-select-department').val('all');
    $('.filter-content-select-work_type').val('all');
{% endblock js %}
{% block html %}
    <div class="_queries">
        <div class="muted">
            <small>
    	        Количество: {{component.queries|length}} заявок
            </small>
        </div>
    	{% for query in component.queries %}
    		<div class="query get_query_content" query_id="{{query.id}}">
    			{% include '@query/build_query_title.tpl' %}
    		</div>
        {% else %}
             Нет заявок
    	{% endfor %}
    </div>
    <div class="_timeline">
        {% include '@query/timeline.tpl' %}
    </div>
{% endblock html %}