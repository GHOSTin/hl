{% extends "default.tpl" %}
{% set meters = component.meters %}
{% block component %}
<ul class="meters nav nav-tabs nav-stacked">
    {% for meter in meters %}
        <li class="meter" meter="{{ meter.get_id() }}"><a class="get_meter_content">{{ meter.get_name() }}</a></li>
    {% endfor %}
</ul>
{% endblock component %}
{% block javascript %}
    <script src="/?js=component.js&p=meter"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/?css=component.css&p=meter" >
{% endblock css %}