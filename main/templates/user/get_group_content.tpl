{% extends "ajax.tpl" %}

{% block js %}
    $('.group[group = {{ group.get_id() }}]').append(get_hidden_content())
{% endblock %}

{% block html %}
<div class="group-content">
    <div>
        <ul class="nav nav-pills">
            <li><a class="get_group_profile">Профиль</a></li>
            <li><a class="get_group_users">Пользователи</a></li>
        </ul>
    </div>
    <div class="group-information">{% include 'user/build_group_content.tpl' %}</div>
</div>
{% endblock %}