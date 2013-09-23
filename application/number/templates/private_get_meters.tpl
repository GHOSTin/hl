{% extends "ajax.tpl" %}
{% set number = component.number %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .number-content-content').html(get_hidden_content());
    $('.number[number = {{ number.get_id() }}] .number-content-menu li').removeClass('active');
    $('.number[number = {{ number.get_id() }}] .get_meters').parent().addClass('active');
{% endblock js %}
{% block html %}
    <div>
        <a class="get_dialog_add_meter">Привязать счетчик</a>
    </div>
    <div class="number-meters">
        {% include '@number/build_meters.tpl' %}
    </div>
{% endblock html %}