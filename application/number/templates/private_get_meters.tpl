{% extends "ajax.tpl" %}
{% set number = component.numbers[0] %}
{% set enable_meters = component.enable_meters %}
{% set disable_meters = component.disable_meters %}
{% block js %}
    $('.number[number = {{number.id}}] .number-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <li>
        <ul class="nav nav-pills">
            <li>
                <a class="get_number_information">Информация о счете</a>
            </li>
            <li class="active"><a class="get_meters">Счетчики</a></li>
        </ul>
    </li>
    <div>
        <a class="get_dialog_add_meter">Привязать счетчик</a>
    </div>
    <li class="number-meters">
        {% include '@number/build_meters.tpl' %}
    </li>
{% endblock html %}