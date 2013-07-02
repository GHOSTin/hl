{% extends "ajax.tpl" %}
{% set letters = component.letters %}
{% block js %}
    $('.letters').html(get_hidden_content())
    $('.get_user_letters').removeClass('active');
    $('.get_group_letters').addClass('active');
    $('.letter-content').remove();
{% endblock js %}
{% block html %}
    {% include '@user/build_group_letters.tpl' %}
{% endblock html %}