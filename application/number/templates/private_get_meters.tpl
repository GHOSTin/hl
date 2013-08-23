{% extends "ajax.tpl" %}
{% set number = component.number %}
{% block js %}
    $('.number[number = {{number.id}}] .number-content-content').html(get_hidden_content());
    $('.number[number = {{number.id}}] .number-content-menu li').removeClass('active');
    $('.number[number = {{number.id}}] .get_meters').parent().addClass('active');
{% endblock js %}
{% block html %}
    <div>
        <a class="get_dialog_add_meter">Привязать счетчик</a>
    </div>
    <div class="number-meters col-sm-6 col-lg-6">
        {% include '@number/build_meters.tpl' %}
    </div>
{% endblock html %}