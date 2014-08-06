{% extends "default.tpl" %}
{% set meters = response.meters %}
{% block component %}
<ul class="meters nav nav-tabs nav-stacked">
    {% for meter in meters %}
        <li class="meter" meter="{{ meter.get_id() }}"><a class="get_meter_content">{{ meter.get_name() }}</a></li>
    {% endfor %}
</ul>
{% endblock component %}
{% block javascript %}
    <script src="/js/meter.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/css/meter.css">
{% endblock css %}