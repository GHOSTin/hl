{% extends "ajax.tpl" %}
{% set import = component.import %}
{% set city = component.city %}
{% set street = component.street %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
<h5>
    <span class="dialog-city_id" city_id="{{ city.get_id() }}">{{ city.get_name() }}</span>
</h5>
<div>
    {% if street == false %}
        <div class="alert alert-error">
        <span class="dialog-street">{{ import.get_street().get_name() }}</span> - не существует!
        </div>
    {% else %}
        <div class="alert alert-success">
        {{ street.get_name() }} - уже существует<br>
        Залить улицу нельзя.
        </div>
    {% endif %}
</div>
<div>
    {% if street == false %}
        <button class="btn btn-success create_street">Залить</button>
    {% endif %}
    <button class="btn get_dialog_import_street">Отменить</button>
</div>
{% endblock html %}