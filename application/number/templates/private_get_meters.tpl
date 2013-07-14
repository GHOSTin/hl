{% extends "ajax.tpl" %}
{% set number = component.numbers[0] %}
{% set enable_meters = component.enable_meters %}
{% set disable_meters = component.disable_meters %}
{% block js %}
    $('.number[number = {{number.id}}] .number-content-content').html(get_hidden_content());
    $('.number[number = {{number.id}}] .number-content-menu li').removeClass('active');
    $('.number[number = {{number.id}}] .get_meters').parent().addClass('active');
{% endblock js %}
{% block html %}
    <div>
        <a class="get_dialog_add_meter">Привязать счетчик</a>
    </div>
    <div class="number-meters">
        {% include '@number/build_meters.tpl' %}
    </div>
{% endblock html %}