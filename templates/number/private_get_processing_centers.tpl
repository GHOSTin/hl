{% extends "ajax.tpl" %}
{% set number = response.number %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .number-content-content').html(get_hidden_content());
    $('.number[number = {{ number.get_id() }}] .number-content-menu li').removeClass('active');
    $('.number[number = {{ number.get_id() }}] .get_processing_centers').parent().addClass('active');
{% endblock js %}
{% block html %}
    {% include '@number/build_processing_centers.tpl' %}
{% endblock html %}