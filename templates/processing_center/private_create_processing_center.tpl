{% extends "ajax.tpl" %}
{% set centers = response.centers %}
{% block js %}
    $('.processing-centers').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@processing_center/build_centers.tpl' %}
{% endblock html %}