{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
{% if 'error' in component|keys() %}
    {{ component.error }}
{% else %}
    <h5>Файл "{{ component.file.name }}"<br>
        <span class="dialog-city_id" city_id="{{ component.city.id }}">{{ component.city.name }}</span>
    </h5>
    <div>
        {% if component.street == false %}
            <div class="alert alert-error">
            <span class="dialog-street">{{ component.street_name }}</span> - не существует!
            </div>
        {% else %}
            <div class="alert alert-success">
            {{ component.street.name }} - уже существует<br>
            Залить улицу нельзя.
            </div>
        {% endif %}
    </div>
    <div>
        {% if component.street == false %}
            <button class="btn btn-success create_street">Залить</button>
        {% endif %}
        <button class="btn btn-default get_dialog_import_street">Отменить</button>
    </div>
{% endif %}
{{ component.flats }}
{% endblock html %}