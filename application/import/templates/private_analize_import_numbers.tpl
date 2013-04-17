{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
{% if 'error' in component|keys() %}
    {{ component.error }}
{% else %}
    <h3>Файл {{ component.file.name }}</h3>
    <div>
        {{ component.house.city_name }}, {{ component.house.street_name }}, дом №{{ component.house.number }}
    </div>
    <div>
        {% for number in component.numbers %}
        <div>{{ number.fio }}</div>
        {% endfor %}
    </div>
{% endif %}
{{ component.flats }}
{% endblock html %}