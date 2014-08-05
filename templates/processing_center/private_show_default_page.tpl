{% extends "default.tpl" %}
{% set centers = component.centers %}
{% block component %}
    <div>
        <a class="get_dialog_create_processing_center">Создать</a>
    </div>
    <ul class="unstyled processing-centers">
        {% include '@processing_center/build_centers.tpl' %}
    </ul>
{% endblock component %}
{% block javascript %}
    <script src="/js/processing_center.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/css/processing_center.css">
{% endblock css %}