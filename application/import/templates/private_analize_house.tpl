{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
{% if 'error' in component|keys() %}
    {{ component.error }}
{% else %}
    <h5>Файл "{{ component.file.name }}"<br>
        <span class="dialog-city_id" city_id="{{ component.city.id }}">{{ component.city.name }}</span>,
        <span class="dialog-street_id" street_id="{{ component.street.id }}">{{ component.street.name }}</span>
    </h5>
    <div>
        {% if component.house == false %}
            <div class="alert alert-error">
            Дом №<span class="dialog-house">{{ component.house_number }}</span> - не существует!
            </div>
        {% else %}
            <div class="alert alert-success">
            {{ component.house.number }} - уже существует<br>
            Залить дом нельзя.
            </div>
        {% endif %}
    </div>
    <div>
        {% if component.street == false %}
            <button class="btn btn-success create_house">Залить</button>
        {% endif %}
        <button class="btn get_dialog_import_house">Отменить</button>
    </div>
{% endif %}
{{ component.flats }}
{% endblock html %}