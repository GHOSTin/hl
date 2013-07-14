{% extends "ajax.tpl" %}
{% set number = component.numbers[0] %}
{% block js %}
    $('.number[number = {{number.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="number-content unstyled">
        <li class="number-content-menu">
            <ul class="nav nav-pills">
                <li class="active">
                    <a class="get_number_information">Информация о счете</a>
                </li>
                <li><a class="get_meters">Счетчики</a></li>
                <li><a class="get_processing_centers">Расчетные центры</a></li>
            </ul>
        </li>
        <li class="number-content-content">
        {% include '@number/build_number_fio.tpl'%}
        </li>
    </ul>
{% endblock html %}