{% extends "ajax.tpl" %}

{% set rest = profile.get_restrictions()[restriction_name] %}

{% block js %}
    $('.user[user = {{ user.get_id() }}] .profile[profile = "{{ profile }}"] .restriction[restriction = "{{ restriction_name }}"]').append(get_hidden_content());
{% endblock js %}

{% block html %}
    <ul class="restriction-content list-unstyled">
        {% if restriction_name == 'departments' %}
            {% for department in items %}
                <li class="item" item="{{ department.get_id() }}"><input type="checkbox"{% if department.get_id() in rest %}checked=""{% endif %}> {{ department.get_name() }}</li>
            {% endfor %}
        {% endif %}
        {% if restriction_name == 'worktypes' %}
            {% for worktype in items %}
                <li class="item" item="{{ worktype.get_id() }}"><input type="checkbox"{% if worktype.get_id() in rest %}checked=""{% endif %}> {{ worktype.get_name() }}</li>
            {% endfor %}
        {% endif %}
    </ul>
{% endblock html %}