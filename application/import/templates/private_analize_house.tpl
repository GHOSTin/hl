{% extends "ajax.tpl" %}
{% set import = component.import %}
{% set city = import.get_city() %}
{% set street = component.street %}
{% set house = component.house %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
<h5>
    <span class="dialog-city_id" city_id="{{ city.get_id() }}">{{ city.get_name() }}</span>,
    <span class="dialog-street_id" street_id="{{ street.get_id() }}">{{ street.get_name() }}</span>
</h5>
<div>
    {% if house == false %}
        <div class="alert alert-error">
        Дом №<span class="dialog-house">{{ import.get_house().get_number() }}</span> - не существует!
        </div>
    {% else %}
        <div class="alert alert-success">
        {{ house.get_number() }} - уже существует<br>
        Залить дом нельзя.
        </div>
    {% endif %}
</div>
<div>
    {% if house == false %}
        <button class="btn btn-success create_house">Залить</button>
    {% endif %}
    <button class="btn get_dialog_import_house">Отменить</button>
</div>
{% endblock html %}
