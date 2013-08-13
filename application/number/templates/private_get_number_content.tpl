{% extends "ajax.tpl" %}
{% set number = component.number %}
{% set stn = component.setting %}
{% block js %}
    $('.number[number = {{number.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="number-content unstyled">
        <li class="number-content-menu">
            <ul class="nav nav-pills">
                <li {% if stn != 'meters' and stn != 'centers' %}class="active"{% endif %}>
                    <a class="get_number_information">Информация о счете</a>
                </li>
                <li{% if stn == 'meters' %} class="active"{% endif %}><a class="get_meters">Счетчики</a></li>
                <li{% if stn == 'centers' %} class="active"{% endif %}><a class="get_processing_centers">Расчетные центры</a></li>
            </ul>
        </li>
        <li class="number-content-content">
        {% if stn == 'centers' %}
            {% include '@number/build_processing_centers.tpl' %}
        {% elseif stn == 'meters'%}
            {% include '@number/build_meters.tpl' %}
        {% else %}
            {% include '@number/build_number_fio.tpl'%}
        {% endif %}
        </li>
    </ul>
{% endblock html %}