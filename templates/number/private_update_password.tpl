{% extends "ajax.tpl" %}
{% set user = component.user %}
{% block js %}
    $('.profile-content').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@profile/build_user_info.tpl' %}
{% endblock html %}