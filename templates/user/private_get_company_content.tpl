{% extends "ajax.tpl" %}
{% set user = response.user %}
{% set company = response.company %}
{% set profiles = response.profiles %}
{% block js %}
    $('.user[user = {{ user.get_id() }}] .company[company = {{ company.get_id() }}]').append(get_hidden_content());
{% endblock js %}
{% block html %}
    <ul class="company-content list-unstyled">
        <li class="company-content-content">
            <ul class="list-unstyled">
                {% include '@user/build_profiles.tpl' %}
            </ul>
        </li>
    </ul>
{% endblock html %}