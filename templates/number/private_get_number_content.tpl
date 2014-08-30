{% extends "ajax.tpl" %}
{% set number = response.number %}
{% set stn = response.setting %}
{% block js %}
    $('.number[number = {{ number.get_id() }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="number-content list-unstyled">
        <li class="number-content-menu">
            <ul class="nav nav-pills">
                <li {% if stn != 'meters' and stn != 'centers' %}class="active"{% endif %}>
                    <a class="get_number_information">Информация о счете</a>
                </li>
            </ul>
        </li>
        <li class="number-content-content row">
            {% include '@number/build_number_fio.tpl'%}
        </li>
    </ul>
{% endblock html %}