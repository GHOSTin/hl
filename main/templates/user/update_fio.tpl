{% extends "ajax.tpl" %}

{% block js %}
    $('.user[user = {{ user.get_id() }}] .user-information').html(get_hidden_content())
{% endblock js %}

{% block html %}
    {% include 'user/build_user_content.tpl' %}
{% endblock html %}