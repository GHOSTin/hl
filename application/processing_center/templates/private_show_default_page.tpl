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
    <script src="/?js=component.js&p=processing_center"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/?css=component.css&p=processing_center" >
{% endblock css %}