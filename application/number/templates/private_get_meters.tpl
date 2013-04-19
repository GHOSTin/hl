{% extends "ajax.tpl" %}
{% set number = component.number %}
{% block js %}
    $('.number[number = {{number.id}}] .number-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <li>
        <div>
        <a class="get_meters">Счетчики</a>
        </div>
    </li>
    <li>
        Нет счетчиков
    </li>
{% endblock html %}