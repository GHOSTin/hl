{% extends "ajax.tpl" %}
{% set data = component.data %}
{% block js %}
    $('.number[number = {{ data.number_id }}] .number-content-content').html(get_hidden_content());
    $('.number[number = {{ data.number_id }}] .number-content-menu li').removeClass('active');
    $('.number[number = {{ data.number_id }}] .get_processing_centers').parent().addClass('active');
{% endblock js %}
{% block html %}
    {% include '@number/build_processing_centers.tpl' %}
{% endblock html %}