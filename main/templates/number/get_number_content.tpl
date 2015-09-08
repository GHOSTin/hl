{% extends "ajax.tpl" %}

{% block js %}
    $('.number[number = {{ number.get_id() }}]').append(get_hidden_content());
    $('.cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
{% endblock %}

{% block html %}
<div class="number-content">
    <div class="number-content-content">{% include 'number/build_number_fio.tpl'%}</div>
</div>
{% endblock %}