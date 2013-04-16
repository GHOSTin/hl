{% extends "ajax.tpl" %}
{% block js %}
{% endblock js %}
{% block html %}
<h3>Файл {{ component.file.name }}</h3>
<div>
    {{ component.house.city_name }}, {{ component.house.street_name }}, дом №{{ component.house.number }}
</div>
{% endblock html %}