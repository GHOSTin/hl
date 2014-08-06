{% extends "ajax.tpl" %}
{% set center = response.center %}
{% block js %}
    $('.processing-center[processing-center = {{ center.get_id() }}] .processing-center-content').html(get_hidden_content());
    $('.processing-center[processing-center = {{ center.get_id() }}] .processing-center-title').html('{{ center.get_name() }}');
{% endblock js %}
{% block html %}
    {% include '@processing_center/build_processing_center_content.tpl' %}
{% endblock html %}