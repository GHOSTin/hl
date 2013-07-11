{% extends "ajax.tpl" %}
{% set center = component.center %}
{% block js %}
    $('.processing-center[processing-center = {{ center.id }}] .processing-center-content').html(get_hidden_content());
    $('.processing-center[processing-center = {{ center.id }}] .processing-center-title').html('{{ center.name }}');
{% endblock js %}
{% block html %}
    {% include '@processing_center/build_processing_center_content.tpl' %}
{% endblock html %}