{% extends "ajax.tpl" %}
{% set data = component.data %}
{% set centers = component.centers %}
{% block js %}
    $('.number[number = {{ data.number_id }}] .number-content-content').html(get_hidden_content());
    $('.number[number = {{ data.number_id }}] .number-content-menu li').removeClass('active');
    $('.number[number = {{ data.number_id }}] .get_processing_centers').parent().addClass('active');
{% endblock js %}
{% block html %}
        <div>
        {% for center in centers %}
            {{center.processing_center_name}}
        {% endfor %}
    </div>
{% endblock html %}