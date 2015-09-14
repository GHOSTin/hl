{% extends "ajax.tpl" %}

{% block js %}
    $('.number[number = {{ number.get_id() }}] .number-content-content').html(get_hidden_content());
    $('.cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
{% endblock %}

{% block html %}
    {% include 'number/build_number_fio.tpl' %}
{% endblock %}