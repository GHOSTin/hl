{% extends "ajax.tpl" %}
{% set letters = component.letters %}
{% block js %}
    $('.letters').html(get_hidden_content('._letters'))
    $('.get_user_letters').removeClass('active');
    $('.get_group_letters').addClass('active');
    $('.letter-content').empty();
{% endblock js %}
{% block html %}
    <div class="_letters">
    {% include '@user/build_group_letters.tpl' %}
    </div>
{% endblock html %}